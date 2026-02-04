<?php
// Comprehensive Job Title Analysis for Succession Planning
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

// Extract job titles and departments properly from nested objects
$jobTitles = [];
$employeeDetails = [];

foreach ($employees as $emp) {
    // Get job title from nested 'job' object
    $job = $emp['job'] ?? null;
    $position = $emp['position'] ?? null;
    
    $jobTitle = 'Unknown';
    $department = 'Unknown';
    
    if ($job && is_array($job)) {
        $jobTitle = $job['job_title'] ?? 'Unknown';
    } elseif ($job && is_string($job)) {
        $decoded = json_decode($job, true);
        $jobTitle = $decoded['job_title'] ?? 'Unknown';
    }
    
    if ($position && is_array($position)) {
        $department = $position['department'] ?? 'Unknown';
    } elseif ($position && is_string($position)) {
        $decoded = json_decode($position, true);
        $department = $decoded['department'] ?? 'Unknown';
    }
    
    if (!isset($jobTitles[$jobTitle])) {
        $jobTitles[$jobTitle] = [
            'count' => 0,
            'departments' => [],
            'employees' => []
        ];
    }
    
    $jobTitles[$jobTitle]['count']++;
    
    if (!in_array($department, $jobTitles[$jobTitle]['departments'])) {
        $jobTitles[$jobTitle]['departments'][] = $department;
    }
    
    if (count($jobTitles[$jobTitle]['employees']) < 5) {
        $jobTitles[$jobTitle]['employees'][] = [
            'id' => $emp['employee_id'] ?? 'N/A',
            'name' => $emp['full_name'] ?? 'N/A',
            'department' => $department,
            'salary' => $emp['base_salary'] ?? 0,
            'status' => $emp['status'] ?? 'Unknown',
            'hired_date' => $emp['hired_date'] ?? 'N/A'
        ];
    }
}

// Sort by count descending
uasort($jobTitles, function($a, $b) {
    return $b['count'] - $a['count'];
});

echo "╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                    HR4 JOB TITLES ANALYSIS FOR SUCCESSION                    ║\n";
echo "╠══════════════════════════════════════════════════════════════════════════════╣\n";
echo "║ Total Employees: " . str_pad(count($employees), 58) . "║\n";
echo "║ Unique Job Titles: " . str_pad(count($jobTitles), 56) . "║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

echo "=== JOB TITLES BY COUNT ===\n";
echo str_repeat('-', 80) . "\n";
echo sprintf("%-40s | %-8s | %s\n", "JOB TITLE", "COUNT", "DEPARTMENTS");
echo str_repeat('-', 80) . "\n";

foreach ($jobTitles as $title => $info) {
    $depts = implode(', ', $info['departments']);
    echo sprintf("%-40s | %-8d | %s\n", substr($title, 0, 40), $info['count'], $depts);
}

echo "\n\n=== EMPLOYEES BY JOB TITLE ===\n";
foreach ($jobTitles as $title => $info) {
    echo "\n┌─ " . $title . " (" . $info['count'] . " employees)\n";
    echo "│  Departments: " . implode(', ', $info['departments']) . "\n";
    echo "│\n";
    foreach ($info['employees'] as $e) {
        $statusIcon = $e['status'] === 'Active' ? '✓' : '✗';
        echo "│  $statusIcon {$e['id']}: {$e['name']}\n";
        echo "│    └─ Dept: {$e['department']} | Salary: ₱" . number_format($e['salary'], 2) . " | Hired: {$e['hired_date']}\n";
    }
    echo "└" . str_repeat('─', 70) . "\n";
}

// Suggest Career Progression Paths
echo "\n\n";
echo "╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║               SUGGESTED SUCCESSION/PROMOTION PATHS                           ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

// Define typical career progressions based on common patterns
$careerPaths = [
    'Customer Service Representative' => [
        'next' => ['Senior Customer Service Representative', 'Customer Service Team Lead', 'Customer Service Supervisor'],
        'senior' => ['Customer Service Manager', 'Operations Manager'],
        'executive' => ['Director of Customer Experience', 'VP of Operations']
    ],
    'Credit Analysts' => [
        'next' => ['Senior Credit Analyst', 'Credit Team Lead'],
        'senior' => ['Credit Manager', 'Risk Manager', 'Credit Operations Manager'],
        'executive' => ['Chief Risk Officer', 'VP of Credit']
    ],
    'Collections Officer' => [
        'next' => ['Senior Collections Officer', 'Collections Team Lead'],
        'senior' => ['Collections Manager', 'Recovery Manager'],
        'executive' => ['Director of Collections', 'VP of Operations']
    ],
    'Marketing' => [
        'next' => ['Senior Marketing Specialist', 'Marketing Team Lead'],
        'senior' => ['Marketing Manager', 'Brand Manager', 'Digital Marketing Manager'],
        'executive' => ['Chief Marketing Officer', 'VP of Marketing']
    ],
    'Accounting' => [
        'next' => ['Senior Accountant', 'Accounting Team Lead'],
        'senior' => ['Accounting Manager', 'Finance Manager', 'Controller'],
        'executive' => ['Chief Financial Officer', 'VP of Finance']
    ],
    'IT/Technical' => [
        'next' => ['Senior Developer', 'Tech Lead', 'Senior IT Support'],
        'senior' => ['IT Manager', 'Development Manager', 'IT Operations Manager'],
        'executive' => ['Chief Technology Officer', 'VP of Technology']
    ],
    'HR/Human Resources' => [
        'next' => ['Senior HR Specialist', 'HR Team Lead', 'Talent Acquisition Specialist'],
        'senior' => ['HR Manager', 'HR Business Partner', 'Talent Manager'],
        'executive' => ['Chief Human Resources Officer', 'VP of People']
    ],
    'Branch Operations' => [
        'next' => ['Senior Operations Officer', 'Operations Supervisor'],
        'senior' => ['Branch Manager', 'Area Operations Manager'],
        'executive' => ['Regional Manager', 'VP of Operations']
    ],
    'Loan Officer' => [
        'next' => ['Senior Loan Officer', 'Loan Processing Lead'],
        'senior' => ['Lending Manager', 'Loan Operations Manager'],
        'executive' => ['Chief Lending Officer', 'VP of Lending']
    ]
];

foreach ($jobTitles as $title => $info) {
    if ($title === 'Unknown') continue;
    
    echo "📋 Current Role: $title\n";
    echo str_repeat('-', 60) . "\n";
    
    // Find matching career path
    $matchedPath = null;
    foreach ($careerPaths as $pathKey => $path) {
        if (stripos($title, $pathKey) !== false || stripos($pathKey, $title) !== false) {
            $matchedPath = $path;
            break;
        }
    }
    
    // Also match by department
    if (!$matchedPath) {
        foreach ($info['departments'] as $dept) {
            foreach ($careerPaths as $pathKey => $path) {
                if (stripos($dept, $pathKey) !== false || stripos($pathKey, $dept) !== false) {
                    $matchedPath = $path;
                    break 2;
                }
            }
        }
    }
    
    if ($matchedPath) {
        echo "   🔼 NEXT LEVEL (1-2 years):\n";
        foreach ($matchedPath['next'] as $role) {
            echo "      → $role\n";
        }
        echo "\n   🔼🔼 SENIOR LEVEL (3-5 years):\n";
        foreach ($matchedPath['senior'] as $role) {
            echo "      → $role\n";
        }
        echo "\n   🔼🔼🔼 EXECUTIVE LEVEL (5+ years):\n";
        foreach ($matchedPath['executive'] as $role) {
            echo "      → $role\n";
        }
    } else {
        echo "   🔼 SUGGESTED PROMOTIONS:\n";
        echo "      → Senior $title\n";
        echo "      → $title Team Lead\n";
        echo "      → $title Manager\n";
        echo "      → Department Manager\n";
        echo "      → Director\n";
    }
    echo "\n";
}

echo "\n=== READY FOR DATABASE: Job Title Succession Mapping ===\n";
echo "Copy this data structure for your succession planning feature:\n\n";

$successionMap = [];
foreach ($jobTitles as $title => $info) {
    if ($title === 'Unknown') continue;
    
    $matchedPath = null;
    foreach ($careerPaths as $pathKey => $path) {
        if (stripos($title, $pathKey) !== false || stripos($pathKey, $title) !== false) {
            $matchedPath = $path;
            break;
        }
    }
    
    if (!$matchedPath) {
        foreach ($info['departments'] as $dept) {
            foreach ($careerPaths as $pathKey => $path) {
                if (stripos($dept, $pathKey) !== false || stripos($pathKey, $dept) !== false) {
                    $matchedPath = $path;
                    break 2;
                }
            }
        }
    }
    
    if ($matchedPath) {
        $successionMap[$title] = [
            'current_role' => $title,
            'departments' => $info['departments'],
            'employee_count' => $info['count'],
            'promotion_paths' => [
                'immediate' => $matchedPath['next'][0] ?? "Senior $title",
                'mid_term' => $matchedPath['senior'][0] ?? "$title Manager",
                'long_term' => $matchedPath['executive'][0] ?? "Department Director"
            ]
        ];
    } else {
        $successionMap[$title] = [
            'current_role' => $title,
            'departments' => $info['departments'],
            'employee_count' => $info['count'],
            'promotion_paths' => [
                'immediate' => "Senior $title",
                'mid_term' => "$title Manager",
                'long_term' => "Department Director"
            ]
        ];
    }
}

echo json_encode($successionMap, JSON_PRETTY_PRINT);
echo "\n";
