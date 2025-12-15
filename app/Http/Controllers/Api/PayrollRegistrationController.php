<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PayrollRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PayrollRegistrationController extends Controller
{
    /**
     * Get the current user's payroll registration
     */
    public function show()
    {
        $user = Auth::user();
        
        $registration = PayrollRegistration::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->latest()
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'No payroll registration found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $registration
        ]);
    }

    /**
     * Store a new payroll registration
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if user already has a pending or approved registration
        $existingRegistration = PayrollRegistration::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRegistration) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a ' . $existingRegistration->status . ' payroll registration.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:Bank Transfer,GCash,Maya',
            'bank_name' => 'required|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_type' => 'nullable|string|max:50',
            'id_type' => 'required|string|max:100',
            'id_number' => 'required|string|max:100',
            'proof_of_account' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle file uploads
        $proofPath = null;
        $idPath = null;

        if ($request->hasFile('proof_of_account')) {
            $proofPath = $request->file('proof_of_account')->store('payroll/proof_of_account', 'public');
        }

        if ($request->hasFile('valid_id')) {
            $idPath = $request->file('valid_id')->store('payroll/valid_id', 'public');
        }

        // Create registration
        $registration = PayrollRegistration::create([
            'user_id' => $user->id,
            'employee_id' => $request->employee_id,
            'employee_name' => $request->employee_name ?? $user->name,
            'email' => $user->email,
            'payment_method' => $request->payment_method,
            'bank_name' => $request->bank_name,
            'bank_branch' => $request->bank_branch,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'account_type' => $request->account_type,
            'id_type' => $request->id_type,
            'id_number' => $request->id_number,
            'proof_of_account_path' => $proofPath,
            'valid_id_path' => $idPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payroll registration submitted successfully',
            'data' => $registration
        ], 201);
    }

    /**
     * Update an existing payroll registration (only if rejected or pending)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $registration = PayrollRegistration::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'rejected'])
            ->latest()
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'No editable payroll registration found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:Bank Transfer,GCash,Maya',
            'bank_name' => 'required|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_type' => 'nullable|string|max:50',
            'id_type' => 'required|string|max:100',
            'id_number' => 'required|string|max:100',
            'proof_of_account' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'valid_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle file uploads (only if new files provided)
        if ($request->hasFile('proof_of_account')) {
            // Delete old file
            if ($registration->proof_of_account_path) {
                Storage::disk('public')->delete($registration->proof_of_account_path);
            }
            $registration->proof_of_account_path = $request->file('proof_of_account')->store('payroll/proof_of_account', 'public');
        }

        if ($request->hasFile('valid_id')) {
            // Delete old file
            if ($registration->valid_id_path) {
                Storage::disk('public')->delete($registration->valid_id_path);
            }
            $registration->valid_id_path = $request->file('valid_id')->store('payroll/valid_id', 'public');
        }

        // Update registration
        $registration->update([
            'employee_id' => $request->employee_id ?? $registration->employee_id,
            'employee_name' => $request->employee_name ?? $registration->employee_name,
            'payment_method' => $request->payment_method,
            'bank_name' => $request->bank_name,
            'bank_branch' => $request->bank_branch,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'account_type' => $request->account_type,
            'id_type' => $request->id_type,
            'id_number' => $request->id_number,
            'status' => 'pending', // Reset to pending after edit
            'remarks' => null, // Clear previous remarks
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payroll registration updated successfully',
            'data' => $registration
        ]);
    }

    /**
     * Get all pending registrations (for Payroll Department)
     */
    public function getPending()
    {
        $registrations = PayrollRegistration::pending()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $registrations
        ]);
    }

    /**
     * Get all registrations (for Payroll Department)
     */
    public function getAll(Request $request)
    {
        $query = PayrollRegistration::query();

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $registrations = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $registrations
        ]);
    }

    /**
     * Approve a registration (for Payroll Department)
     */
    public function approve(Request $request, $id)
    {
        $registration = PayrollRegistration::findOrFail($id);

        if ($registration->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'This registration is already approved'
            ], 400);
        }

        $registration->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'remarks' => $request->remarks,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration approved successfully',
            'data' => $registration
        ]);
    }

    /**
     * Reject a registration (for Payroll Department)
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'remarks' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a reason for rejection',
                'errors' => $validator->errors()
            ], 422);
        }

        $registration = PayrollRegistration::findOrFail($id);

        $registration->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'remarks' => $request->remarks,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration rejected',
            'data' => $registration
        ]);
    }
}
