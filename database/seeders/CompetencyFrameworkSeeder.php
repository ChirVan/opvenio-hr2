<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompetencyFrameworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $frameworks = [
            [
                'framework_name' => 'Leadership & Management',
                'description' => 'Competencies required for leadership roles including strategic thinking, team management, decision-making, and organizational development skills.',
                'effective_date' => Carbon::now()->subMonths(6)->format('Y-m-d'),
                'end_date' => null,
                'status' => 'active',
                'notes' => 'Core framework for all management and leadership positions across the organization.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'framework_name' => 'Technical Skills',
                'description' => 'Technical competencies covering software development, IT infrastructure, data management, and technology-related skills across all proficiency levels.',
                'effective_date' => Carbon::now()->subMonths(6)->format('Y-m-d'),
                'end_date' => null,
                'status' => 'active',
                'notes' => 'Framework for IT, Engineering, and Technical departments.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'framework_name' => 'Communication & Interpersonal',
                'description' => 'Competencies focused on verbal and written communication, presentation skills, active listening, and building effective professional relationships.',
                'effective_date' => Carbon::now()->subMonths(4)->format('Y-m-d'),
                'end_date' => null,
                'status' => 'active',
                'notes' => 'Applicable to all employees regardless of department or role.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'framework_name' => 'Customer Service',
                'description' => 'Competencies for customer-facing roles including customer relationship management, problem resolution, and service excellence.',
                'effective_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'end_date' => null,
                'status' => 'active',
                'notes' => 'Primary framework for Customer Support, Sales, and Account Management teams.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'framework_name' => 'Project Management',
                'description' => 'Competencies related to project planning, execution, monitoring, risk management, and stakeholder communication.',
                'effective_date' => Carbon::now()->subMonths(5)->format('Y-m-d'),
                'end_date' => null,
                'status' => 'active',
                'notes' => 'For project managers, team leads, and anyone involved in project delivery.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'framework_name' => 'Data & Analytics',
                'description' => 'Competencies for data analysis, statistical methods, business intelligence tools, and data-driven decision making.',
                'effective_date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
                'end_date' => null,
                'status' => 'active',
                'notes' => 'Framework for Data Analysts, BI Specialists, and Data Scientists.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'framework_name' => 'Compliance & Risk',
                'description' => 'Competencies covering regulatory compliance, risk assessment, policy adherence, and ethical business practices.',
                'effective_date' => Carbon::now()->subMonths(4)->format('Y-m-d'),
                'end_date' => null,
                'status' => 'active',
                'notes' => 'Essential for Legal, Compliance, and Risk Management departments.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'framework_name' => 'Human Resources',
                'description' => 'Competencies for HR professionals including talent acquisition, employee relations, performance management, and organizational development.',
                'effective_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'end_date' => null,
                'status' => 'active',
                'notes' => 'Core framework for all HR department roles.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'framework_name' => 'Finance & Accounting',
                'description' => 'Competencies related to financial management, accounting principles, budgeting, forecasting, and financial analysis.',
                'effective_date' => Carbon::now()->subMonths(5)->format('Y-m-d'),
                'end_date' => null,
                'status' => 'active',
                'notes' => 'Framework for Finance, Accounting, and Treasury departments.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'framework_name' => 'Digital Transformation',
                'description' => 'Competencies for driving digital initiatives, change management, innovation, and adopting new technologies.',
                'effective_date' => Carbon::now()->subMonths(1)->format('Y-m-d'),
                'end_date' => null,
                'status' => 'draft',
                'notes' => 'New framework under development for digital transformation initiatives.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::connection('competency_management')->table('competency_frameworks')->insert($frameworks);
    }
}
