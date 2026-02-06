<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Modules\competency_management\Controllers\CompetencyGapAnalysisController;
use App\Http\Controllers\Api\LeaveApiController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\AiRecommendationController;
use App\Http\Controllers\AiEvaluationController;
use App\Http\Controllers\AiJobRecommendationController;

// AI Job Recommendation API
Route::post('/ai/job-recommendation', [AiJobRecommendationController::class, 'recommend']);

// Run AI Evaluation in LMS Checking
Route::post('ai/evaluate/{resultId}', [AiEvaluationController::class, 'evaluateByAi']);
Route::post('ai/evaluate/{resultId}/approve', [AiEvaluationController::class, 'approveByAi']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// ============================================================
// ======= Employee API Proxy (for ESS pages) =================
// ============================================================
Route::prefix('ess')->group(function () {

    // Sync HR4 employees to local users table
    Route::post('/syncdb', function (Request $request) {
        $created = $updated = $skipped = $errors = 0;
        $errorMessages = [];

        try {
            $response = Http::timeout(20)
                ->withOptions(['verify' => false])
                ->withHeaders(['X-API-Key' => 'b24e8778f104db434adedd4342e94d39cee6d0668ec595dc6f02c739c522b57a'])
                ->get('https://hr4.microfinancial-1.com/allemployees');

            if (! $response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch employee data from HR4.'
                ], 500);
            }

            $responseData = $response->json();
            $employees = $responseData['data'] ?? $responseData;

            // Log total employees received
            Log::info('HR4 Sync: Received ' . count($employees) . ' employees from API');

            if (empty($employees) || !is_array($employees)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No employee data received from HR4 API.',
                    'raw_response' => $responseData
                ], 500);
            }

            foreach ($employees as $employee) {
                try {
                    if (empty($employee['employee_id'])) {
                        $skipped++;
                        continue;
                    }

                    $employee_id = $employee['employee_id'];
                    $name = $employee['full_name'] ?? ($employee['firstname'] ?? null);
                    $email = $employee['email'] ?? null;
                    $status = $employee['employment_status'] ?? null;

                    // Skip if no name or email
                    if (empty($name) && empty($email)) {
                        $skipped++;
                        Log::warning("HR4 Sync: Skipping employee {$employee_id} - no name or email");
                        continue;
                    }

                    $user = User::where('employee_id', $employee_id)->first();

                    if ($user) {
                        $dirty = false;

                        if ($name && $name !== $user->name) {
                            $user->name = $name;
                            $dirty = true;
                        }

                        if ($email && $email !== $user->email) {
                            $user->email = $email;
                            $dirty = true;
                        }

                        if (!is_null($status) && $status !== $user->employment_status) {
                            $user->employment_status = $status;
                            $dirty = true;
                        }

                        if ($dirty) {
                            $user->save();
                            $updated++;
                        }

                    } else {
                        // Generate unique email if null or duplicate
                        if (empty($email)) {
                            $email = 'employee_' . $employee_id . '@placeholder.local';
                        } else {
                            // Check if email already exists for another user
                            $existingUser = User::where('email', $email)->first();
                            if ($existingUser) {
                                $email = $employee_id . '_' . $email;
                            }
                        }

                        // Create new user
                        $user = User::create([
                            'employee_id' => $employee_id,
                            'name' => $name ?? 'Employee ' . $employee_id,
                            'email' => $email,
                            'role' => 'employee',
                            'password' => Hash::make('12345678'),
                            'employment_status' => $status,
                        ]);
                        $created++;
                        Log::info("HR4 Sync: Created user for employee {$employee_id}");
                    }
                } catch (\Throwable $e) {
                    $errors++;
                    $errorMessages[] = "Employee {$employee['employee_id']}: " . $e->getMessage();
                    Log::error("HR4 sync error for employee {$employee['employee_id']}: " . $e->getMessage(), [
                        'employee' => $employee,
                        'exception' => $e,
                    ]);
                    continue;
                }
            }

            $noChanges = ($created === 0 && $updated === 0 && $errors === 0);

            return response()->json([
                'success' => true,
                'message' => "Sync finished",
                'created' => $created,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors' => $errors,
                'error_details' => $errors > 0 ? $errorMessages : null,
                'no_changes' => $noChanges,
            ]);

        } catch (\Exception $e){
            Log::error('HR4 sync fatal error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: API connection error. ' . $e->getMessage()
            ], 500);
        }
        
    });
    

    // Get current employee by email
    Route::get('/employee/by-email/{email}', function ($email) {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->withHeaders(['X-API-Key' => 'b24e8778f104db434adedd4342e94d39cee6d0668ec595dc6f02c739c522b57a'])
                ->get('https://hr4.microfinancial-1.com/allemployees');
            
            if ($response->successful()) {
                $responseData = $response->json();
                $employees = $responseData['data'] ?? $responseData;
                $employee = collect($employees)->firstWhere('email', $email);
                
                if ($employee) {
                    return response()->json([
                        'success' => true,
                        'employee' => $employee
                    ]);
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch employee data'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'API connection error: ' . $e->getMessage()
            ], 500);
        }
    });

    // Get employee attendance/schedule from HR3 API
    Route::get('/attendance/{employeeId}', function ($employeeId) {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->get('https://hr3.microfinancial-1.com/api/v1/065c6b0ff2fd56be5bbb7bb45cef10dc22d97ddfbb0e4d23344a0b98d9df6140/attendance-logs');
            
            if ($response->successful()) {
                $data = $response->json();
                $logs = $data['data'] ?? $data;
                
                // Find the most recent attendance record for this employee
                $employeeLog = collect($logs)->first(function ($log) use ($employeeId) {
                    return $log['employee_id_no'] === $employeeId;
                });
                
                if ($employeeLog) {
                    return response()->json([
                        'success' => true,
                        'attendance' => $employeeLog
                    ]);
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'No attendance record found for this employee'
                ], 404);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch attendance data'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'API connection error: ' . $e->getMessage()
            ], 500);
        }
    });

    // Get employee by ID
    Route::get('/employee/{id}', function ($id) {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->withHeaders(['X-API-Key' => 'b24e8778f104db434adedd4342e94d39cee6d0668ec595dc6f02c739c522b57a'])
                ->get("https://hr4.microfinancial-1.com/allemployees/{$id}");
            
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'employee' => $response->json()
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'API connection error: ' . $e->getMessage()
            ], 500);
        }
    });

    // ============================================================
    // ======= Payroll Registration API ===========================
    // ============================================================
    
    // Submit payroll registration (Employee submits bank details)
    Route::post('/payroll-registration', function (Request $request) {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'employee_name' => 'required|string',
                'employee_id' => 'nullable|string',
                'payment_method' => 'required|in:Bank Transfer,GCash,Maya',
                'bank_name' => 'required|string',
                'bank_branch' => 'nullable|string',
                'account_name' => 'required|string',
                'account_number' => 'required|string',
                'account_type' => 'nullable|string',
                'id_type' => 'required|string',
                'id_number' => 'required|string',
                'proof_of_account' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'valid_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);

            // Handle file uploads
            $proofPath = null;
            $idPath = null;

            if ($request->hasFile('proof_of_account')) {
                $proofPath = $request->file('proof_of_account')->store('payroll/proof_of_accounts', 'public');
            }

            if ($request->hasFile('valid_id')) {
                $idPath = $request->file('valid_id')->store('payroll/valid_ids', 'public');
            }

            // Get user_id by looking up the user from email
            $user = \App\Models\User::where('email', $validated['email'])->first();
            $userId = $user ? $user->id : null;

            // If user not found by email, try to get from session/auth
            if (!$userId && auth()->check()) {
                $userId = auth()->id();
            }

            // If still no user_id, return error
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User account not found for this email. Please contact HR.',
                ], 404);
            }

            // Check if employee already has a registration
            $existing = \App\Models\PayrollRegistration::where('email', $validated['email'])->first();

            if ($existing) {
                // Update existing registration
                $existing->update([
                    'user_id' => $userId,
                    'employee_id' => $validated['employee_id'] ?? $existing->employee_id,
                    'employee_name' => $validated['employee_name'],
                    'payment_method' => $validated['payment_method'],
                    'bank_name' => $validated['bank_name'],
                    'bank_branch' => $validated['bank_branch'],
                    'account_name' => $validated['account_name'],
                    'account_number' => $validated['account_number'],
                    'account_type' => $validated['account_type'],
                    'id_type' => $validated['id_type'],
                    'id_number' => $validated['id_number'],
                    'proof_of_account_path' => $proofPath ?? $existing->proof_of_account_path,
                    'valid_id_path' => $idPath ?? $existing->valid_id_path,
                    'status' => 'pending', // Reset to pending on update
                    'remarks' => null,
                    'approved_by' => null,
                    'approved_at' => null,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payroll registration updated successfully',
                    'data' => $existing->fresh()
                ]);
            }

            // Create new registration
            $registration = \App\Models\PayrollRegistration::create([
                'user_id' => $userId,
                'employee_id' => $validated['employee_id'],
                'email' => $validated['email'],
                'employee_name' => $validated['employee_name'],
                'payment_method' => $validated['payment_method'],
                'bank_name' => $validated['bank_name'],
                'bank_branch' => $validated['bank_branch'],
                'account_name' => $validated['account_name'],
                'account_number' => $validated['account_number'],
                'account_type' => $validated['account_type'],
                'id_type' => $validated['id_type'],
                'id_number' => $validated['id_number'],
                'proof_of_account_path' => $proofPath,
                'valid_id_path' => $idPath,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payroll registration submitted successfully',
                'data' => $registration
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit registration: ' . $e->getMessage()
            ], 500);
        }
    });

    // Get employee's payroll registration status
    Route::get('/payroll-registration/{email}', function ($email) {
        try {
            $registration = \App\Models\PayrollRegistration::where('email', $email)
                ->with('approver:id,name')
                ->first();

            if (!$registration) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'No registration found'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $registration
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching registration: ' . $e->getMessage()
            ], 500);
        }
    });

    // Get payslip data from external HR4 API (keeps credentials server-side)
    Route::get('/payslips/{employeeId}', function ($employeeId) {
        try {
            // API credentials kept server-side for security
            $apiUrl = 'https://hr4.microfinancial-1.com/GetAllPayslip';
            $apiKey = 'b24e8778f104db434adedd4342e94d39cee6d0668ec595dc6f02c739c522b57a';
            
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->withHeaders([
                    'Accept' => 'application/json',
                    'X-API-Key' => $apiKey
                ])
                ->get($apiUrl);
            
            if (!$response->successful()) {
                Log::warning("Payslip API failed for employee {$employeeId}: " . $response->status());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch payslip data from external API',
                    'payslips' => []
                ]);
            }
            
            $data = $response->json();
            $allPayslips = $data['payslips'] ?? $data['data'] ?? [];
            
            // Find the employee's payslip data
            $employeePayslipData = collect($allPayslips)->first(function ($item) use ($employeeId) {
                return $item['employee_id'] == $employeeId;
            });
            
            if (!$employeePayslipData) {
                return response()->json([
                    'success' => true,
                    'message' => 'No payslip records found for this employee',
                    'employee' => null,
                    'payrolls' => [],
                    'paid_payrolls' => []
                ]);
            }
            
            // Get the payrolls array from the employee data
            $payrolls = $employeePayslipData['payrolls'] ?? [];
            
            // Filter only "Paid" status payrolls
            $paidPayrolls = collect($payrolls)->filter(function ($payroll) {
                $status = strtolower($payroll['status'] ?? '');
                return $status === 'paid' || $status === 'released' || $status === 'completed';
            })->values()->all();
            
            return response()->json([
                'success' => true,
                'employee' => [
                    'employee_id' => $employeePayslipData['employee_id'],
                    'full_name' => $employeePayslipData['full_name'],
                    'position' => $employeePayslipData['position'],
                    'department' => $employeePayslipData['department'],
                ],
                'payrolls' => $payrolls,
                'paid_payrolls' => $paidPayrolls,
                'total_records' => count($payrolls),
                'paid_count' => count($paidPayrolls)
            ]);
            
        } catch (\Exception $e) {
            Log::error("Payslip API error for employee {$employeeId}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error connecting to payslip API: ' . $e->getMessage(),
                'payrolls' => []
            ], 500);
        }
    });

    // Get ALL payslips from external API
    Route::get('/payslips-all', function () {
        try {
            $apiUrl = 'https://hr4.microfinancial-1.com/GetAllPayslip';
            $apiKey = 'b24e8778f104db434adedd4342e94d39cee6d0668ec595dc6f02c739c522b57a';
            
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->withHeaders([
                    'Accept' => 'application/json',
                    'X-API-Key' => $apiKey
                ])
                ->get($apiUrl);
            
            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch payslip data',
                    'status_code' => $response->status()
                ]);
            }
            
            $data = $response->json();
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    });

    // ============================================================
    // ======= Recent Activities API (for ESS Dashboard) ==========
    // ============================================================
    Route::get('/activities/{email}', function ($email) {
        try {
            $activities = [];
            
            // Get user by email to find employee_id
            $user = \App\Models\User::where('email', $email)->first();
            $employeeId = $user ? $user->employee_id : null;
            
            if (!$employeeId) {
                return response()->json([
                    'success' => true,
                    'activities' => [],
                    'message' => 'No employee ID found'
                ]);
            }

            // 1. Get Assessment Results (completed assessments, evaluations)
            $assessmentResults = DB::connection('ess')
                ->table('assessment_results')
                ->where('employee_id', $employeeId)
                ->whereNotNull('completed_at')
                ->orderBy('completed_at', 'desc')
                ->limit(10)
                ->get();

            foreach ($assessmentResults as $result) {
                // Determine activity type based on status/evaluation
                if ($result->status === 'passed' || $result->evaluation_status === 'passed') {
                    $activities[] = [
                        'type' => 'assessment_passed',
                        'title' => 'Assessment Passed',
                        'description' => 'You passed an assessment with a score of ' . number_format($result->score, 1) . '%',
                        'icon' => 'bx-check-circle',
                        'icon_class' => 'success',
                        'timestamp' => $result->evaluated_at ?? $result->completed_at,
                    ];
                } elseif ($result->status === 'failed' || $result->evaluation_status === 'failed') {
                    $activities[] = [
                        'type' => 'assessment_failed',
                        'title' => 'Assessment Needs Improvement',
                        'description' => 'Assessment score: ' . number_format($result->score, 1) . '%',
                        'icon' => 'bx-x-circle',
                        'icon_class' => 'warning',
                        'timestamp' => $result->evaluated_at ?? $result->completed_at,
                    ];
                } elseif ($result->status === 'completed') {
                    $activities[] = [
                        'type' => 'assessment_completed',
                        'title' => 'Assessment Completed',
                        'description' => 'You completed an assessment with a score of ' . number_format($result->score, 1) . '%',
                        'icon' => 'bx-check',
                        'icon_class' => 'primary',
                        'timestamp' => $result->completed_at,
                    ];
                }
            }

            // 2. Get Training Assignment activities
            $trainingActivities = DB::connection('training_management')
                ->table('training_assignment_employees as tae')
                ->join('training_assignments as ta', 'tae.training_assignment_id', '=', 'ta.id')
                ->join('training_catalogs as tc', 'ta.training_catalog_id', '=', 'tc.id')
                ->where('tae.employee_id', $employeeId)
                ->select([
                    'tc.title as course_title',
                    'tae.status',
                    'tae.started_at',
                    'tae.completed_at',
                    'tae.progress_percentage'
                ])
                ->orderByRaw('COALESCE(tae.completed_at, tae.started_at) DESC')
                ->limit(10)
                ->get();

            foreach ($trainingActivities as $training) {
                if ($training->status === 'completed' && $training->completed_at) {
                    $activities[] = [
                        'type' => 'training_completed',
                        'title' => 'Training Completed',
                        'description' => 'Completed: ' . $training->course_title,
                        'icon' => 'bx-book-bookmark',
                        'icon_class' => 'success',
                        'timestamp' => $training->completed_at,
                    ];
                } elseif ($training->started_at && $training->status === 'in_progress') {
                    $activities[] = [
                        'type' => 'training_started',
                        'title' => 'Training Started',
                        'description' => 'Started: ' . $training->course_title . ' (' . ($training->progress_percentage ?? 0) . '% complete)',
                        'icon' => 'bx-book-open',
                        'icon_class' => 'primary',
                        'timestamp' => $training->started_at,
                    ];
                }
            }

            // 3. Get Leave Request activities
            $leaveActivities = DB::connection('ess')
                ->table('leaves')
                ->where('employee_id', $employeeId)
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();

            foreach ($leaveActivities as $leave) {
                $leaveTypeCodes = [
                    'Vacation Leave' => 'VL',
                    'Sick Leave' => 'SL',
                    'Emergency Leave' => 'EL',
                    'Bereavement Leave' => 'BL',
                    'Maternity Leave' => 'ML',
                    'Paternity Leave' => 'PL',
                    'Solo Parent Leave' => 'SPL',
                    'Service Incentive Leave' => 'SIL',
                    'Leave Without Pay' => 'LWOP'
                ];
                $typeCode = $leaveTypeCodes[$leave->leave_type] ?? substr($leave->leave_type, 0, 2);
                $startDate = \Carbon\Carbon::parse($leave->start_date)->format('M d');
                $endDate = \Carbon\Carbon::parse($leave->end_date)->format('M d');
                $dateRange = $startDate === $endDate ? $startDate : "{$startDate} - {$endDate}";

                if ($leave->status === 'approved') {
                    $activities[] = [
                        'type' => 'leave_approved',
                        'title' => 'Leave Request Approved',
                        'description' => "{$typeCode}: {$dateRange} ({$leave->days_requested} day" . ($leave->days_requested > 1 ? 's' : '') . ")",
                        'icon' => 'bx-calendar-check',
                        'icon_class' => 'success',
                        'timestamp' => $leave->updated_at,
                    ];
                } elseif ($leave->status === 'rejected') {
                    $activities[] = [
                        'type' => 'leave_rejected',
                        'title' => 'Leave Request Rejected',
                        'description' => "{$typeCode}: {$dateRange}",
                        'icon' => 'bx-calendar-x',
                        'icon_class' => 'warning',
                        'timestamp' => $leave->updated_at,
                    ];
                } elseif ($leave->status === 'pending') {
                    $activities[] = [
                        'type' => 'leave_pending',
                        'title' => 'Leave Request Submitted',
                        'description' => "{$typeCode}: {$dateRange} ({$leave->days_requested} day" . ($leave->days_requested > 1 ? 's' : '') . ") - Awaiting approval",
                        'icon' => 'bx-calendar-event',
                        'icon_class' => 'primary',
                        'timestamp' => $leave->created_at,
                    ];
                }
            }

            // Sort all activities by timestamp (most recent first)
            usort($activities, function($a, $b) {
                return strtotime($b['timestamp']) - strtotime($a['timestamp']);
            });

            // Return only the 5 most recent activities
            $activities = array_slice($activities, 0, 5);

            // Format timestamps for display
            foreach ($activities as &$activity) {
                $activity['time_ago'] = \Carbon\Carbon::parse($activity['timestamp'])->diffForHumans();
            }

            return response()->json([
                'success' => true,
                'activities' => $activities
            ]);

        } catch (\Exception $e) {
            Log::error("Activities API error for {$email}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching activities: ' . $e->getMessage(),
                'activities' => []
            ], 500);
        }
    });
});

// ============================================================
// ======= Payroll Department API (for reviewing registrations)
// ============================================================
Route::prefix('payroll')->group(function () {
    // Get all payroll registrations (with optional status filter)
    Route::get('/registrations', function (Request $request) {
        try {
            $query = \App\Models\PayrollRegistration::with('approver:id,name');

            // Filter by status
            if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
                $query->where('status', $request->status);
            }

            // Search by employee name or email
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('employee_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('account_number', 'like', "%{$search}%");
                });
            }

            $registrations = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $registrations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching registrations: ' . $e->getMessage()
            ], 500);
        }
    });

    // Get single registration detail
    Route::get('/registrations/{id}', function ($id) {
        try {
            $registration = \App\Models\PayrollRegistration::with('approver:id,name')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $registration
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching registration: ' . $e->getMessage()
            ], 500);
        }
    });

    // Approve registration
    Route::post('/registrations/{id}/approve', function (Request $request, $id) {
        try {
            $registration = \App\Models\PayrollRegistration::findOrFail($id);

            $registration->update([
                'status' => 'approved',
                'approved_by' => auth()->id() ?? $request->input('approved_by'),
                'approved_at' => now(),
                'remarks' => $request->input('remarks'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration approved successfully',
                'data' => $registration->fresh()->load('approver:id,name')
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving registration: ' . $e->getMessage()
            ], 500);
        }
    });

    // Reject registration
    Route::post('/registrations/{id}/reject', function (Request $request, $id) {
        try {
            $validated = $request->validate([
                'remarks' => 'required|string|max:500'
            ]);

            $registration = \App\Models\PayrollRegistration::findOrFail($id);

            $registration->update([
                'status' => 'rejected',
                'approved_by' => auth()->id() ?? $request->input('rejected_by'),
                'approved_at' => now(),
                'remarks' => $validated['remarks'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration rejected',
                'data' => $registration->fresh()->load('approver:id,name')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rejection reason is required',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting registration: ' . $e->getMessage()
            ], 500);
        }
    });

    // Get pending count (for badge/notification)
    Route::get('/registrations/count/pending', function () {
        try {
            $count = \App\Models\PayrollRegistration::pending()->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching count: ' . $e->getMessage()
            ], 500);
        }
    });
});

// Assigned Competencies API
Route::get('/assigned-competencies', [CompetencyGapAnalysisController::class, 'getAssignedCompetencies']);

// Leave API Endpoints (for other departments)
Route::prefix('leaves')->group(function () {
    // Get all leave types
    Route::get('/types', [LeaveApiController::class, 'getLeaveTypes']);
    
    // Get employee leave balances
    Route::get('/balances/{email}', [LeaveApiController::class, 'getBalances']);
    
    // Get employee leave calendar
    Route::get('/calendar/{email}', [LeaveApiController::class, 'getCalendar']);
    
    // Validate leave request before submission
    Route::post('/validate', [LeaveApiController::class, 'validateLeaveRequest']);
    
    // Fetch all leave requests (with optional filters)
    Route::get('/', [LeaveApiController::class, 'index']);
    
    // Get leave statistics
    Route::get('/stats/summary', [LeaveApiController::class, 'statistics']);
    
    // Fetch a single leave request
    Route::get('/{id}', [LeaveApiController::class, 'show'])->where('id', '[0-9]+');
    
    // Create a new leave request
    Route::post('/', [LeaveApiController::class, 'store']);
    
    // Update a leave request
    Route::put('/{id}', [LeaveApiController::class, 'update']);
    Route::patch('/{id}', [LeaveApiController::class, 'update']);
    
    // Delete a leave request
    Route::delete('/{id}', [LeaveApiController::class, 'destroy']);
    
    // Bulk update leave statuses
    Route::post('/bulk-update-status', [LeaveApiController::class, 'bulkUpdateStatus']);
});

// ============================================================
// ======= Training Room Bookings API =========================
// ============================================================
Route::prefix('training-room-bookings')->group(function () {
    
    // Get all bookings
    Route::get('/', [\App\Modules\training_management\Controllers\TrainingRoomBookingApiController::class, 'index']);
    
    // Get booking statistics
    Route::get('/stats', [\App\Modules\training_management\Controllers\TrainingRoomBookingApiController::class, 'stats']);
    
    // Get a specific booking
    Route::get('/{id}', [\App\Modules\training_management\Controllers\TrainingRoomBookingApiController::class, 'show']);
    
    // Create a new booking
    Route::post('/', [\App\Modules\training_management\Controllers\TrainingRoomBookingApiController::class, 'store']);
    
    // Update a booking
    Route::put('/{id}', [\App\Modules\training_management\Controllers\TrainingRoomBookingApiController::class, 'update']);
    Route::patch('/{id}', [\App\Modules\training_management\Controllers\TrainingRoomBookingApiController::class, 'update']);
    
    // Update booking status only
    Route::patch('/{id}/status', [\App\Modules\training_management\Controllers\TrainingRoomBookingApiController::class, 'updateStatus']);
    
    // Delete a booking
    Route::delete('/{id}', [\App\Modules\training_management\Controllers\TrainingRoomBookingApiController::class, 'destroy']);
});

// ============================================================
// ======= Jobs API (for Succession Planning) =================
// ============================================================
Route::get('/jobs', function () {
    try {
        $response = Http::timeout(30)
            ->withOptions(['verify' => false])
            ->withHeaders([
                'Accept' => 'application/json',
                'X-API-Key' => ''
            ])
            ->get('https://hr4.microfinancial-1.com/api/employees/job');
        
        if (!$response->successful()) {
            Log::warning("Jobs API failed: " . $response->status());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch jobs from external API',
                'data' => []
            ]);
        }
        
        $result = $response->json();
        
        // Return data in expected format
        return response()->json([
            'status' => 'success',
            'data' => $result['data'] ?? $result ?? []
        ]);
        
    } catch (\Exception $e) {
        Log::error("Jobs API error: " . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Unable to connect to jobs service',
            'data' => []
        ]);
    }
});