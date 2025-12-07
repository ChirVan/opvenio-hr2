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
                'leave_type' => 'required|string|in:Vacation,Sick,Emergency,Personal',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $id = DB::connection('ess')->table('leaves')->insertGetId([
                'employee_id' => $request->employee_id,
                'employee_name' => $request->employee_name,
                'employee_email' => $request->employee_email,
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'status' => 'pending',
                'remarks' => $request->remarks ?? 'Awaiting approval',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $leave = DB::connection('ess')->table('leaves')->where('id', $id)->first();

            Log::info("New leave request created", [
                'id' => $id,
                'employee_id' => $request->employee_id,
                'leave_type' => $request->leave_type,
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
}
