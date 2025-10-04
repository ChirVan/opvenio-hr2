<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class EmployeeApiService
{
    private $baseUrl;
    private $timeout;

    public function __construct()
    {
        $this->baseUrl = 'https://hr4.microfinancial-1.com/services/hcm-services/public';
        $this->timeout = 30; // 30 seconds timeout
    }

    /**
     * Fetch all employees from the external API
     * 
     * @return array|null
     */
    public function getEmployees()
    {
        try {
            // Cache the API response for 5 minutes to avoid frequent API calls
            return Cache::remember('external_employees', 300, function () {
                $response = Http::timeout($this->timeout)
                    ->withOptions([
                        'verify' => false, // Disable SSL verification for external API
                    ])
                    ->get($this->baseUrl . '/employees');

                if ($response->successful()) {
                    $employees = $response->json();
                    
                    // Transform the data to only include the fields we need
                    return collect($employees)->map(function ($employee) {
                        return [
                            'id' => $employee['id'],
                            'employee_id' => $employee['employee_id'],
                            'full_name' => $employee['full_name'],
                            'email' => $employee['email'],
                            'employment_status' => $employee['employment_status'],
                            'job_title' => $employee['job_title'],
                        ];
                    })->toArray();
                }

                Log::error('Employee API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'url' => $this->baseUrl . '/employees'
                ]);

                return null;
            });

        } catch (\Exception $e) {
            Log::error('Employee API service error: ' . $e->getMessage(), [
                'url' => $this->baseUrl . '/employees'
            ]);
            return null;
        }
    }

    /**
     * Get a specific employee by ID
     * 
     * @param int $employeeId
     * @return array|null
     */
    public function getEmployee($employeeId)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withOptions([
                    'verify' => false, // Disable SSL verification for external API
                ])
                ->get($this->baseUrl . '/employees/' . $employeeId);

            if ($response->successful()) {
                $employee = $response->json();
                
                return [
                    'id' => $employee['id'],
                    'employee_id' => $employee['employee_id'],
                    'full_name' => $employee['full_name'],
                    'email' => $employee['email'],
                    'employment_status' => $employee['employment_status'],
                    'job_title' => $employee['job_title'],
                ];
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Employee API service error for employee ID ' . $employeeId . ': ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Search employees by name or employee ID
     * 
     * @param string $query
     * @return array
     */
    public function searchEmployees($query)
    {
        $employees = $this->getEmployees();
        
        if (!$employees) {
            return [];
        }

        return array_filter($employees, function ($employee) use ($query) {
            return stripos($employee['full_name'], $query) !== false ||
                   stripos($employee['employee_id'], $query) !== false ||
                   stripos($employee['email'], $query) !== false;
        });
    }

    /**
     * Clear the employees cache
     * 
     * @return void
     */
    public function clearCache()
    {
        Cache::forget('external_employees');
    }
}