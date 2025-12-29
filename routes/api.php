<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\competency_management\Controllers\CompetencyGapAnalysisController;
use App\Http\Controllers\Api\LeaveApiController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

        try {
            $response = Http::timeout(20)
                ->withOptions(['verify' => false])
                ->get('https://hr4.microfinancial-1.com/allemployees');

            if (! $response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch employee data from HR4.'
                ], 500);
            }

            $employees = $response->json();

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
                        // Create
                        $user = User::create([
                            'employee_id' => $employee_id,
                            'name' => $name,
                            'email' => $email,
                            'role' => 'employee',
                            'password' => Hash::make('12345678'),
                            'employment_status' => $status,
                        ]);
                        $created++;
                    }
                } catch (\Throwable $e) {
                    $errors++;
                    Log::error("HR4 sync error for employee {$employee['employee_id']}: " . $ex->getMessage(), [
                        'employee' => $employee,
                        'exception' => $ex,
                    ]);
                    continue;
                }
            }

            $noChanges = ($created === 0 && $updated === 0);

            return response()->json([
                'success' => true,
                'message' => "Sync finished",
                'created' => $created,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors' => $errors,
                'no_changes' => $noChanges,
            ]);

        } catch (\Exception $e){
            Log::error('HR4 sync fatal error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: API connection error.'
            ], 500);
        }
        
    });
    

    // Get current employee by email
    Route::get('/employee/by-email/{email}', function ($email) {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->get('https://hr4.microfinancial-1.com/allemployees');
            
            if ($response->successful()) {
                $employees = $response->json();
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

    // Get employee by ID
    Route::get('/employee/{id}', function ($id) {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
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

            // Check if employee already has a registration
            $existing = \App\Models\PayrollRegistration::where('email', $validated['email'])->first();

            if ($existing) {
                // Update existing registration
                $existing->update([
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
                'user_id' => auth()->id(),
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
    // Fetch all leave requests (with optional filters)
    Route::get('/', [LeaveApiController::class, 'index']);
    
    // Fetch a single leave request
    Route::get('/{id}', [LeaveApiController::class, 'show']);
    
    // Create a new leave request
    Route::post('/', [LeaveApiController::class, 'store']);
    
    // Update a leave request
    Route::put('/{id}', [LeaveApiController::class, 'update']);
    Route::patch('/{id}', [LeaveApiController::class, 'update']);
    
    // Delete a leave request
    Route::delete('/{id}', [LeaveApiController::class, 'destroy']);
    
    // Bulk update leave statuses
    Route::post('/bulk-update-status', [LeaveApiController::class, 'bulkUpdateStatus']);
    
    // Get leave statistics
    Route::get('/stats/summary', [LeaveApiController::class, 'statistics']);
});
