<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\EmployeeApiService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SyncEmployeesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employees:sync {--limit=10 : Limit the number of employees to sync} {--force : Force sync all employees}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync employees from API and create user accounts';

    protected $employeeApiService;

    public function __construct(EmployeeApiService $employeeApiService)
    {
        parent::__construct();
        $this->employeeApiService = $employeeApiService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting employee sync from API...');

        // Fetch employees from API
        $employees = $this->employeeApiService->getEmployees();

        if (!$employees) {
            $this->error('Failed to fetch employees from API');
            return 1;
        }

        $this->info('Found ' . count($employees) . ' employees from API');

        $limit = $this->option('limit');
        $force = $this->option('force');

        if (!$force && !$this->confirm('Do you want to continue with syncing employees?')) {
            $this->info('Employee sync cancelled.');
            return 0;
        }

        // Apply limit if not forcing
        if (!$force && $limit) {
            $employees = array_slice($employees, 0, $limit);
            $this->info("Processing first {$limit} employees...");
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($employees as $employeeData) {
            try {
                // Skip if no email
                if (empty($employeeData['email'])) {
                    $this->warn("Skipping employee {$employeeData['employee_id']} - No email provided");
                    $skipped++;
                    continue;
                }

                // Check if user already exists by employee_id or email
                $existingUser = User::where('employee_id', $employeeData['employee_id'])
                    ->orWhere('email', $employeeData['email'])
                    ->first();

                if ($existingUser) {
                    // Update existing user
                    $existingUser->update([
                        'employee_id' => $employeeData['employee_id'],
                        'name' => $employeeData['full_name'],
                        'email' => $employeeData['email'],
                    ]);
                    $this->line("Updated: {$employeeData['full_name']} ({$employeeData['employee_id']})");
                    $updated++;
                } else {
                    // Create new user
                    $user = User::create([
                        'employee_id' => $employeeData['employee_id'],
                        'name' => $employeeData['full_name'],
                        'email' => $employeeData['email'],
                        'role' => 'employee', // Default role
                        'password' => Hash::make('password123'), // Default password
                        'email_verified_at' => now(),
                    ]);
                    $this->info("Created: {$employeeData['full_name']} ({$employeeData['employee_id']})");
                    $created++;
                }

            } catch (\Exception $e) {
                $this->error("Error processing employee {$employeeData['employee_id']}: " . $e->getMessage());
                $skipped++;
                continue;
            }
        }

        // Summary
        $this->info('');
        $this->info('=== Sync Summary ===');
        $this->info("Created: {$created} users");
        $this->info("Updated: {$updated} users");
        $this->info("Skipped: {$skipped} users");
        $this->info("Total processed: " . ($created + $updated + $skipped));

        if ($created > 0) {
            $this->info('');
            $this->warn('NOTE: New users have default password "password123"');
            $this->warn('Users should change their password on first login.');
        }

        return 0;
    }
}
