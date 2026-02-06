<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LeaveApiController extends Controller
{
    /**
     * Fetch all leave requests
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = DB::connection('ess')->table('leaves');

            // Filter by employee_id if provided
            if ($request->has('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            // Filter by employee_email if provided
            if ($request->has('employee_email')) {
                $query->where('employee_email', $request->employee_email);
            }

            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by leave_type if provided
            if ($request->has('leave_type')) {
                $query->where('leave_type', $request->leave_type);
            }

            // Filter by date range if provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
            }

            // Order by created_at descending (newest first)
            $leaves = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Leave requests fetched successfully',
                'data' => $leaves,
                'total' => $leaves->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching leave requests: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch leave requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch a single leave request by ID
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $leave = DB::connection('ess')->table('leaves')->where('id', $id)->first();

            if (!$leave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Leave request fetched successfully',
                'data' => $leave
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching leave request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch leave request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new leave request
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required|string',
                'employee_name' => 'required|string',
                'employee_email' => 'required|email',
                'leave_type' => 'required|string',
                'leave_type_id' => 'nullable|integer',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'required|string',
                'is_half_day' => 'nullable|boolean',
                'half_day_period' => 'nullable|string|in:AM,PM',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Calculate days requested
            $startDate = \Carbon\Carbon::parse($request->start_date);
            $endDate = \Carbon\Carbon::parse($request->end_date);
            $isHalfDay = $request->is_half_day ?? false;
            $daysRequested = $isHalfDay ? 0.5 : ($startDate->diffInDays($endDate) + 1);

            // Get leave type info for validation
            $leaveType = null;
            if ($request->leave_type_id) {
                $leaveType = DB::connection('ess')
                    ->table('leave_types')
                    ->where('id', $request->leave_type_id)
                    ->first();
            }

            // Validate against limits if leave type found
            if ($leaveType) {
                $currentYear = $startDate->year;
                
                // Check balance
                $balance = DB::connection('ess')
                    ->table('leave_balances')
                    ->where('employee_id', $request->employee_id)
                    ->where('leave_type_id', $leaveType->id)
                    ->where('year', $currentYear)
                    ->first();

                if ($balance) {
                    $approvedDays = DB::connection('ess')
                        ->table('leaves')
                        ->where('employee_id', $request->employee_id)
                        ->where('leave_type', $leaveType->name)
                        ->where('status', 'approved')
                        ->whereYear('start_date', $currentYear)
                        ->sum('days_requested');

                    $pendingDays = DB::connection('ess')
                        ->table('leaves')
                        ->where('employee_id', $request->employee_id)
                        ->where('leave_type', $leaveType->name)
                        ->where('status', 'pending')
                        ->whereYear('start_date', $currentYear)
                        ->sum('days_requested');

                    $available = $balance->total_credits - $approvedDays - $pendingDays;

                    if ($daysRequested > $available) {
                        return response()->json([
                            'success' => false,
                            'message' => "Insufficient leave balance. Available: {$available} day(s), Requested: {$daysRequested} day(s)."
                        ], 400);
                    }
                }

                // Check monthly limit
                $monthlyCount = DB::connection('ess')
                    ->table('leaves')
                    ->where('employee_id', $request->employee_id)
                    ->where('leave_type', $leaveType->name)
                    ->whereIn('status', ['pending', 'approved'])
                    ->whereMonth('start_date', $startDate->month)
                    ->whereYear('start_date', $currentYear)
                    ->count();

                if ($monthlyCount >= $leaveType->max_per_month) {
                    return response()->json([
                        'success' => false,
                        'message' => "Maximum {$leaveType->max_per_month} request(s) per month for this leave type."
                    ], 400);
                }

                // Check max days per request
                if ($daysRequested > $leaveType->max_days_per_request) {
                    return response()->json([
                        'success' => false,
                        'message' => "Maximum {$leaveType->max_days_per_request} day(s) per request for this leave type."
                    ], 400);
                }
            }

            // Check for overlapping requests
            $overlap = DB::connection('ess')
                ->table('leaves')
                ->where('employee_id', $request->employee_id)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('start_date', '<=', $request->start_date)
                              ->where('end_date', '>=', $request->end_date);
                        });
                })
                ->first();

            if ($overlap) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a leave request for these dates.'
                ], 400);
            }

            // Check if there's already a pending leave request (only 1 allowed at a time)
            $pendingRequest = DB::connection('ess')
                ->table('leaves')
                ->where('employee_id', $request->employee_id)
                ->where('status', 'pending')
                ->first();

            if ($pendingRequest) {
                $pendingStartDate = \Carbon\Carbon::parse($pendingRequest->start_date)->format('M d, Y');
                $pendingEndDate = \Carbon\Carbon::parse($pendingRequest->end_date)->format('M d, Y');
                return response()->json([
                    'success' => false,
                    'message' => "You already have a pending leave request ({$pendingRequest->leave_type}: {$pendingStartDate} - {$pendingEndDate}). Please wait for it to be approved or rejected before submitting a new request."
                ], 400);
            }

            $id = DB::connection('ess')->table('leaves')->insertGetId([
                'employee_id' => $request->employee_id,
                'employee_name' => $request->employee_name,
                'employee_email' => $request->employee_email,
                'leave_type' => $request->leave_type,
                'leave_type_id' => $request->leave_type_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'days_requested' => $daysRequested,
                'is_half_day' => $isHalfDay,
                'half_day_period' => $request->half_day_period,
                'reason' => $request->reason,
                'status' => 'pending',
                'remarks' => $request->remarks ?? 'Awaiting approval',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update pending credits in balance
            if ($leaveType && $balance) {
                DB::connection('ess')
                    ->table('leave_balances')
                    ->where('employee_id', $request->employee_id)
                    ->where('leave_type_id', $leaveType->id)
                    ->where('year', $startDate->year)
                    ->increment('pending_credits', $daysRequested);
            }

            $leave = DB::connection('ess')->table('leaves')->where('id', $id)->first();

            Log::info("New leave request created", [
                'id' => $id,
                'employee_id' => $request->employee_id,
                'leave_type' => $request->leave_type,
                'days_requested' => $daysRequested,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leave request created successfully',
                'data' => $leave
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating leave request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create leave request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a leave request (status, remarks, etc.)
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $leave = DB::connection('ess')->table('leaves')->where('id', $id)->first();

            if (!$leave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'sometimes|string|in:pending,approved,rejected',
                'remarks' => 'sometimes|string',
                'leave_type' => 'sometimes|string|in:Vacation,Sick,Emergency,Personal',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after_or_equal:start_date',
                'reason' => 'sometimes|string',
                'approved_by' => 'sometimes|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [];

            // Only update fields that are provided
            if ($request->has('status')) {
                $updateData['status'] = $request->status;
            }
            if ($request->has('remarks')) {
                $updateData['remarks'] = $request->remarks;
            }
            if ($request->has('leave_type')) {
                $updateData['leave_type'] = $request->leave_type;
            }
            if ($request->has('start_date')) {
                $updateData['start_date'] = $request->start_date;
            }
            if ($request->has('end_date')) {
                $updateData['end_date'] = $request->end_date;
            }
            if ($request->has('reason')) {
                $updateData['reason'] = $request->reason;
            }
            if ($request->has('approved_by')) {
                $updateData['approved_by'] = $request->approved_by;
            }

            $updateData['updated_at'] = now();

            DB::connection('ess')->table('leaves')
                ->where('id', $id)
                ->update($updateData);

            $updatedLeave = DB::connection('ess')->table('leaves')->where('id', $id)->first();

            Log::info("Leave request updated", [
                'id' => $id,
                'updated_fields' => array_keys($updateData),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leave request updated successfully',
                'data' => $updatedLeave
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating leave request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update leave request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a leave request
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $leave = DB::connection('ess')->table('leaves')->where('id', $id)->first();

            if (!$leave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request not found'
                ], 404);
            }

            // Only allow deletion of pending leaves
            if ($leave->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending leave requests can be deleted'
                ], 400);
            }

            DB::connection('ess')->table('leaves')->where('id', $id)->delete();

            Log::info("Leave request deleted", ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Leave request deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting leave request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete leave request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update leave statuses (for approvers)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ids' => 'required|array',
                'ids.*' => 'required|integer',
                'status' => 'required|string|in:approved,rejected',
                'remarks' => 'sometimes|string',
                'approved_by' => 'sometimes|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [
                'status' => $request->status,
                'updated_at' => now(),
            ];

            if ($request->has('remarks')) {
                $updateData['remarks'] = $request->remarks;
            }
            if ($request->has('approved_by')) {
                $updateData['approved_by'] = $request->approved_by;
            }

            $affected = DB::connection('ess')->table('leaves')
                ->whereIn('id', $request->ids)
                ->where('status', 'pending')
                ->update($updateData);

            Log::info("Bulk leave status update", [
                'ids' => $request->ids,
                'status' => $request->status,
                'affected' => $affected,
            ]);

            return response()->json([
                'success' => true,
                'message' => "{$affected} leave request(s) updated successfully",
                'affected_count' => $affected
            ]);

        } catch (\Exception $e) {
            Log::error('Error bulk updating leave statuses: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk update leave statuses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get leave statistics/summary
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics(Request $request)
    {
        try {
            $query = DB::connection('ess')->table('leaves');

            // Filter by employee_id if provided
            if ($request->has('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            // Get counts by status
            $pending = (clone $query)->where('status', 'pending')->count();
            $approved = (clone $query)->where('status', 'approved')->count();
            $rejected = (clone $query)->where('status', 'rejected')->count();

            // Get counts by leave type
            $byType = DB::connection('ess')->table('leaves')
                ->select('leave_type', DB::raw('count(*) as count'))
                ->when($request->has('employee_id'), function ($q) use ($request) {
                    return $q->where('employee_id', $request->employee_id);
                })
                ->groupBy('leave_type')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Leave statistics fetched successfully',
                'data' => [
                    'total' => $pending + $approved + $rejected,
                    'by_status' => [
                        'pending' => $pending,
                        'approved' => $approved,
                        'rejected' => $rejected,
                    ],
                    'by_type' => $byType,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching leave statistics: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch leave statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all active leave types
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeaveTypes()
    {
        try {
            $leaveTypes = DB::connection('ess')
                ->table('leave_types')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $leaveTypes
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching leave types: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch leave types',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get employee leave balances
     * 
     * @param string $employeeEmail
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBalances($employeeEmail)
    {
        try {
            $currentYear = now()->year;
            
            // Get employee info
            $user = \App\Models\User::where('email', $employeeEmail)->first();
            $employeeId = $user ? $user->employee_id : null;

            if (!$employeeId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            // Get all active leave types
            $leaveTypes = DB::connection('ess')
                ->table('leave_types')
                ->where('is_active', true)
                ->get();

            $balances = [];

            foreach ($leaveTypes as $type) {
                // Check if balance record exists for this year
                $balance = DB::connection('ess')
                    ->table('leave_balances')
                    ->where('employee_id', $employeeId)
                    ->where('leave_type_id', $type->id)
                    ->where('year', $currentYear)
                    ->first();

                if (!$balance) {
                    // Create default balance for this employee and leave type
                    DB::connection('ess')->table('leave_balances')->insert([
                        'employee_id' => $employeeId,
                        'employee_email' => $employeeEmail,
                        'leave_type_id' => $type->id,
                        'year' => $currentYear,
                        'total_credits' => $type->default_credits,
                        'used_credits' => 0,
                        'pending_credits' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $balance = (object)[
                        'total_credits' => $type->default_credits,
                        'used_credits' => 0,
                        'pending_credits' => 0,
                    ];
                }

                // Calculate approved leaves for this year
                $approvedDays = DB::connection('ess')
                    ->table('leaves')
                    ->where('employee_id', $employeeId)
                    ->where('leave_type', $type->name)
                    ->where('status', 'approved')
                    ->whereYear('start_date', $currentYear)
                    ->sum('days_requested');

                // Calculate pending leaves for this year
                $pendingDays = DB::connection('ess')
                    ->table('leaves')
                    ->where('employee_id', $employeeId)
                    ->where('leave_type', $type->name)
                    ->where('status', 'pending')
                    ->whereYear('start_date', $currentYear)
                    ->sum('days_requested');

                $usedCredits = $approvedDays ?: $balance->used_credits;
                $pendingCredits = $pendingDays ?: $balance->pending_credits;
                $availableCredits = $balance->total_credits - $usedCredits - $pendingCredits;

                $balances[] = [
                    'leave_type_id' => $type->id,
                    'code' => $type->code,
                    'name' => $type->name,
                    'description' => $type->description,
                    'total_credits' => (float) $balance->total_credits,
                    'used_credits' => (float) $usedCredits,
                    'pending_credits' => (float) $pendingCredits,
                    'available_credits' => max(0, (float) $availableCredits),
                    'max_days_per_request' => $type->max_days_per_request,
                    'max_per_month' => $type->max_per_month,
                    'advance_notice_days' => $type->advance_notice_days,
                    'requires_medical_cert' => (bool) $type->requires_medical_cert,
                    'is_paid' => (bool) $type->is_paid,
                    'icon' => $type->icon,
                    'color_class' => $type->color_class,
                ];
            }

            return response()->json([
                'success' => true,
                'year' => $currentYear,
                'employee_id' => $employeeId,
                'data' => $balances
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching leave balances: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch leave balances',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate leave request before submission
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateLeaveRequest(Request $request)
    {
        try {
            $errors = [];
            $warnings = [];

            $employeeEmail = $request->employee_email;
            $leaveTypeId = $request->leave_type_id;
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $isHalfDay = $request->is_half_day ?? false;

            // Get employee info
            $user = \App\Models\User::where('email', $employeeEmail)->first();
            $employeeId = $user ? $user->employee_id : null;

            if (!$employeeId) {
                return response()->json([
                    'success' => false,
                    'valid' => false,
                    'errors' => ['Employee not found']
                ]);
            }

            // Check if there's already a pending leave request (only 1 allowed at a time)
            $pendingRequest = DB::connection('ess')
                ->table('leaves')
                ->where('employee_id', $employeeId)
                ->where('status', 'pending')
                ->first();

            if ($pendingRequest) {
                $pendingStartDate = \Carbon\Carbon::parse($pendingRequest->start_date)->format('M d, Y');
                $pendingEndDate = \Carbon\Carbon::parse($pendingRequest->end_date)->format('M d, Y');
                $errors[] = "You already have a pending leave request ({$pendingRequest->leave_type}: {$pendingStartDate} - {$pendingEndDate}). Please wait for it to be approved or rejected.";
            }

            // Get leave type
            $leaveType = DB::connection('ess')
                ->table('leave_types')
                ->where('id', $leaveTypeId)
                ->first();

            if (!$leaveType) {
                return response()->json([
                    'success' => false,
                    'valid' => false,
                    'errors' => ['Invalid leave type']
                ]);
            }

            // Calculate days requested
            $start = \Carbon\Carbon::parse($startDate);
            $end = \Carbon\Carbon::parse($endDate);
            $daysRequested = $isHalfDay ? 0.5 : ($start->diffInDays($end) + 1);

            // 1. Check advance notice requirement
            $today = \Carbon\Carbon::today();
            $noticeDays = $today->diffInDays($start, false);
            if ($noticeDays < $leaveType->advance_notice_days) {
                $errors[] = "This leave type requires at least {$leaveType->advance_notice_days} day(s) advance notice.";
            }

            // 2. Check max days per request
            if ($daysRequested > $leaveType->max_days_per_request) {
                $errors[] = "Maximum {$leaveType->max_days_per_request} day(s) per request for this leave type.";
            }

            // 3. Check monthly limit
            $currentMonth = $start->month;
            $currentYear = $start->year;
            $monthlyCount = DB::connection('ess')
                ->table('leaves')
                ->where('employee_id', $employeeId)
                ->where('leave_type', $leaveType->name)
                ->whereIn('status', ['pending', 'approved'])
                ->whereMonth('start_date', $currentMonth)
                ->whereYear('start_date', $currentYear)
                ->count();

            if ($monthlyCount >= $leaveType->max_per_month) {
                $errors[] = "You have reached the maximum of {$leaveType->max_per_month} request(s) per month for this leave type.";
            }

            // 4. Check available balance
            $balance = DB::connection('ess')
                ->table('leave_balances')
                ->where('employee_id', $employeeId)
                ->where('leave_type_id', $leaveTypeId)
                ->where('year', $currentYear)
                ->first();

            if ($balance) {
                $approvedDays = DB::connection('ess')
                    ->table('leaves')
                    ->where('employee_id', $employeeId)
                    ->where('leave_type', $leaveType->name)
                    ->where('status', 'approved')
                    ->whereYear('start_date', $currentYear)
                    ->sum('days_requested');

                $pendingDays = DB::connection('ess')
                    ->table('leaves')
                    ->where('employee_id', $employeeId)
                    ->where('leave_type', $leaveType->name)
                    ->where('status', 'pending')
                    ->whereYear('start_date', $currentYear)
                    ->sum('days_requested');

                $available = $balance->total_credits - $approvedDays - $pendingDays;

                if ($daysRequested > $available) {
                    $errors[] = "Insufficient leave balance. Available: {$available} day(s), Requested: {$daysRequested} day(s).";
                }
            }

            // 5. Check for overlapping leave requests
            $overlap = DB::connection('ess')
                ->table('leaves')
                ->where('employee_id', $employeeId)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('start_date', '<=', $startDate)
                              ->where('end_date', '>=', $endDate);
                        });
                })
                ->first();

            if ($overlap) {
                $errors[] = "You already have a leave request for these dates.";
            }

            // Warnings
            if ($leaveType->requires_medical_cert) {
                $warnings[] = "This leave type requires a medical certificate for approval.";
            }

            if (!$leaveType->is_paid) {
                $warnings[] = "This is an unpaid leave type.";
            }

            return response()->json([
                'success' => true,
                'valid' => count($errors) === 0,
                'days_requested' => $daysRequested,
                'errors' => $errors,
                'warnings' => $warnings
            ]);

        } catch (\Exception $e) {
            Log::error('Error validating leave request: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'valid' => false,
                'errors' => ['An error occurred while validating the request']
            ], 500);
        }
    }

    /**
     * Get leave calendar data for an employee
     * 
     * @param string $employeeEmail
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendar($employeeEmail, Request $request)
    {
        try {
            // Get employee info
            $user = \App\Models\User::where('email', $employeeEmail)->first();
            $employeeId = $user ? $user->employee_id : null;

            if (!$employeeId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found',
                    'events' => []
                ]);
            }

            // Get month and year from request, default to current month
            $month = $request->input('month', now()->month);
            $year = $request->input('year', now()->year);

            // Get start and end of month with buffer for multi-day leaves
            $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->subDays(7);
            $endOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->addDays(7);

            // Fetch leaves that overlap with the month
            $leaves = DB::connection('ess')
                ->table('leaves')
                ->where('employee_id', $employeeId)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                        ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                        ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                            $q->where('start_date', '<=', $startOfMonth)
                              ->where('end_date', '>=', $endOfMonth);
                        });
                })
                ->orderBy('start_date')
                ->get();

            // Format as calendar events
            $events = [];
            foreach ($leaves as $leave) {
                $color = $leave->status === 'approved' ? '#10b981' : '#f59e0b'; // Green for approved, amber for pending
                $textColor = '#ffffff';
                
                // Get leave type color
                $leaveType = DB::connection('ess')
                    ->table('leave_types')
                    ->where('name', $leave->leave_type)
                    ->first();

                if ($leaveType) {
                    switch ($leaveType->color_class) {
                        case 'vacation': $color = $leave->status === 'approved' ? '#2563eb' : '#93c5fd'; break;
                        case 'sick': $color = $leave->status === 'approved' ? '#dc2626' : '#fca5a5'; break;
                        case 'emergency': $color = $leave->status === 'approved' ? '#d97706' : '#fcd34d'; break;
                        case 'bereavement': $color = $leave->status === 'approved' ? '#db2777' : '#f9a8d4'; break;
                        case 'maternity': $color = $leave->status === 'approved' ? '#ec4899' : '#fbcfe8'; break;
                        case 'paternity': $color = $leave->status === 'approved' ? '#0284c7' : '#7dd3fc'; break;
                        case 'incentive': $color = $leave->status === 'approved' ? '#ca8a04' : '#fde047'; break;
                        case 'lwop': $color = $leave->status === 'approved' ? '#64748b' : '#cbd5e1'; break;
                        default: $color = $leave->status === 'approved' ? '#10b981' : '#86efac';
                    }
                }

                $events[] = [
                    'id' => $leave->id,
                    'title' => $leave->leave_type . ($leave->status === 'pending' ? ' (Pending)' : ''),
                    'start' => $leave->start_date,
                    'end' => \Carbon\Carbon::parse($leave->end_date)->addDay()->format('Y-m-d'), // Add 1 day for inclusive end
                    'color' => $color,
                    'textColor' => $textColor,
                    'status' => $leave->status,
                    'leave_type' => $leave->leave_type,
                    'days' => $leave->days_requested ?? 1,
                    'reason' => $leave->reason,
                    'allDay' => true,
                ];
            }

            // Get pending count for display
            $pendingCount = DB::connection('ess')
                ->table('leaves')
                ->where('employee_id', $employeeId)
                ->where('status', 'pending')
                ->count();

            // Check if there's a pending request
            $hasPending = $pendingCount > 0;

            return response()->json([
                'success' => true,
                'month' => $month,
                'year' => $year,
                'pending_count' => $pendingCount,
                'max_pending' => 1,
                'has_pending' => $hasPending,
                'events' => $events
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching leave calendar: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch calendar data',
                'events' => []
            ], 500);
        }
    }
}
