<?php
// Test route to verify the new gap analysis system works
// Add this temporarily to routes/web.php for testing

Route::get('/test-gap-analysis', function () {
    $employeeService = new \App\Services\EmployeeApiService();
    
    // Test 1: Can we get employees from API?
    $employees = $employeeService->getEmployees();
    $employeeCount = $employees ? count($employees) : 0;
    
    // Test 2: Can we get existing gap analyses?
    $gapAnalyses = \App\Modules\competency_management\Models\GapAnalysis::all();
    
    // Test 3: Test employee data retrieval for existing gap analyses
    $gapAnalysisWithEmployees = $gapAnalyses->map(function ($gap) use ($employeeService) {
        $employeeData = $gap->getEmployeeData();
        return [
            'gap_id' => $gap->id,
            'stored_employee_id' => $gap->employee_id,
            'employee_found' => $employeeData !== null,
            'employee_name' => $employeeData['full_name'] ?? 'Not found',
            'employee_id_display' => $employeeData['employee_id'] ?? 'Not found'
        ];
    });
    
    return response()->json([
        'api_status' => $employees !== null,
        'api_employee_count' => $employeeCount,
        'database_gap_count' => $gapAnalyses->count(),
        'gap_analyses_test' => $gapAnalysisWithEmployees,
        'sample_api_employee' => $employees ? $employees[0] : null,
        'message' => 'Gap Analysis system test completed'
    ], 200, [], JSON_PRETTY_PRINT);
});