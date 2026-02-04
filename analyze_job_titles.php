<?php
// Analyze job titles from HR4 API
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

echo "Fetching employees from HR4 API...\n\n";

$response = Http::timeout(20)
    ->withOptions(['verify' => false])
    ->withHeaders(['X-API-Key' => 'b24e8778f104db434adedd4342e94d39cee6d0668ec595dc6f02c739c522b57a'])
    ->get('https://hr4.microfinancial-1.com/allemployees');

$data = $response->json();
$employees = $data['data'] ?? $data;

// Extract and count job titles
$jobTitles = [];
$employeesByTitle = [];

foreach ($employees as $emp) {
    $title = $emp['job_title'] ?? 'Unknown';
    $dept = $emp['department'] ?? 'Unknown';
    
    if (!isset($jobTitles[$title])) {
        $jobTitles[$title] = ['count' => 0, 'departments' => []];
    }
    $jobTitles[$title]['count']++;
    
    if (!in_array($dept, $jobTitles[$title]['departments'])) {
        $jobTitles[$title]['departments'][] = $dept;
    }
    
    // Store sample employees
    if (!isset($employeesByTitle[$title])) {
        $employeesByTitle[$title] = [];
    }
    if (count($employeesByTitle[$title]) < 3) {
        $employeesByTitle[$title][] = [
            'id' => $emp['employee_id'] ?? 'N/A',
            'name' => $emp['full_name'] ?? $emp['firstname'] ?? 'N/A',
            'dept' => $dept
        ];
    }
}

// Sort by count descending
uasort($jobTitles, function($a, $b) {
    return $b['count'] - $a['count'];
});

echo "=== JOB TITLES FROM HR4 API ===\n";
echo "Total Employees: " . count($employees) . "\n";
echo "Unique Job Titles: " . count($jobTitles) . "\n\n";

echo str_repeat('=', 80) . "\n";
echo sprintf("%-40s | %-8s | %s\n", "JOB TITLE", "COUNT", "DEPARTMENTS");
echo str_repeat('-', 80) . "\n";

foreach ($jobTitles as $title => $info) {
    $depts = implode(', ', array_slice($info['departments'], 0, 3));
    if (count($info['departments']) > 3) {
        $depts .= '...';
    }
    echo sprintf("%-40s | %-8d | %s\n", substr($title, 0, 40), $info['count'], $depts);
}

echo "\n\n=== SAMPLE EMPLOYEES BY TITLE ===\n";
foreach ($employeesByTitle as $title => $emps) {
    echo "\n[$title]\n";
    foreach ($emps as $e) {
        echo "  - {$e['id']}: {$e['name']} ({$e['dept']})\n";
    }
}
