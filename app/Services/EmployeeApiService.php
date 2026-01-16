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
        // Base URL should NOT end with /employees
        $this->baseUrl = 'https://hr4.microfinancial-1.com/allemployees';
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
            // Cache the API response for 5 minutes
            return Cache::remember('external_employees', 300, function () {
                $url = $this->baseUrl; // Use the base URL directly

                $response = Http::timeout($this->timeout)
                    ->withOptions([
                        'verify' => false, // Disable SSL verification for external API
                    ])
                    ->withHeaders(['X-API-Key' => 'b24e8778f104db434adedd4342e94d39cee6d0668ec595dc6f02c739c522b57a'])
                    ->get($url);

                if ($response->successful()) {
                    $data = $response->json();

                    // API returns {"status":"success","data":[...]} or {"employees":[...]} or [...]
                    $employees = $data['data'] ?? $data['employees'] ?? $data;

                    return collect($employees)->map(function ($employee) {
                        // Job title is nested in the 'job' object, not at the top level
                        $jobTitle = $employee['job']['job_title'] ?? $employee['job_title'] ?? '';
                        
                        return [
                            'id' => $employee['id'] ?? null,
                            'employee_id' => $employee['employee_id'] ?? '',
                            'full_name' => $employee['full_name'] ?? '',
                            'email' => $employee['email'] ?? '',
                            'employment_status' => $employee['employment_status'] ?? '',
                            'job_title' => $jobTitle,
                        ];
                    })->toArray();
                }

                Log::error('Employee API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'url' => $url,
                ]);

                return null;
            });
        } catch (\Exception $e) {
            Log::error('Employee API service error: ' . $e->getMessage(), [
                'url' => $this->baseUrl
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
            $url = $this->baseUrl . '/' . $employeeId; // Append ID directly to base URL

            $response = Http::timeout($this->timeout)
                ->withOptions([
                    'verify' => false,
                ])
                ->withHeaders(['X-API-Key' => 'b24e8778f104db434adedd4342e94d39cee6d0668ec595dc6f02c739c522b57a'])
                ->get($url);

            if ($response->successful()) {
                $employee = $response->json();
                
                // Job title is nested in the 'job' object, not at the top level
                $jobTitle = $employee['job']['job_title'] ?? $employee['job_title'] ?? '';

                return [
                    'id' => $employee['id'] ?? null,
                    'employee_id' => $employee['employee_id'] ?? '',
                    'full_name' => $employee['full_name'] ?? '',
                    'email' => $employee['email'] ?? '',
                    'employment_status' => $employee['employment_status'] ?? '',
                    'job_title' => $jobTitle,
                ];
            }

            Log::error('Employee API individual request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => $url,
            ]);

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
     */
    public function clearCache()
    {
        Cache::forget('external_employees');
    }
}
