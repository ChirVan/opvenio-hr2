<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeesTableSeeder extends Seeder
{
    public function run()
    {
        DB::connection('competency_management')->table('employees')->insert([
            [
                'lastname' => 'Dela Cruz',
                'firstname' => 'Juan',
                'job_role' => 'Accounting Clerk',
                'email' => 'juan.delacruz@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lastname' => 'Santos',
                'firstname' => 'Maria',
                'job_role' => 'Loan Officer',
                'email' => 'maria.santos@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lastname' => 'Reyes',
                'firstname' => 'Pedro',
                'job_role' => 'Cashier',
                'email' => 'pedro.reyes@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lastname' => 'Lim',
                'firstname' => 'Ana',
                'job_role' => 'Book Keeper',
                'email' => 'ana.lim@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}