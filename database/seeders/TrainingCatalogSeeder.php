<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainingCatalogs = [
            [
                'title' => 'Leadership Development',
                'label' => 'Leadership',
                'description' => 'Comprehensive training programs designed to develop leadership skills, strategic thinking, and team management capabilities for current and aspiring leaders.',
                'is_active' => true,
                'framework_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Technical Skills Training',
                'label' => 'Technical',
                'description' => 'Hands-on technical training courses covering programming, software development, IT infrastructure, and emerging technologies.',
                'is_active' => true,
                'framework_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Communication Excellence',
                'label' => 'Communication',
                'description' => 'Training programs focused on improving verbal and written communication, presentation skills, and interpersonal effectiveness.',
                'is_active' => true,
                'framework_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Project Management',
                'label' => 'PM',
                'description' => 'Comprehensive project management training covering methodologies like Agile, Scrum, and traditional waterfall approaches.',
                'is_active' => true,
                'framework_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Compliance & Ethics',
                'label' => 'Compliance',
                'description' => 'Mandatory training on corporate policies, regulatory compliance, workplace ethics, and legal requirements.',
                'is_active' => true,
                'framework_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Customer Service Excellence',
                'label' => 'Customer Service',
                'description' => 'Training programs to enhance customer interaction skills, problem-solving, and service delivery excellence.',
                'is_active' => true,
                'framework_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Data Analytics & Business Intelligence',
                'label' => 'Analytics',
                'description' => 'Courses on data analysis, visualization tools, statistical methods, and business intelligence platforms.',
                'is_active' => true,
                'framework_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Health & Safety',
                'label' => 'Safety',
                'description' => 'Workplace safety training including emergency procedures, hazard identification, and occupational health standards.',
                'is_active' => true,
                'framework_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Digital Transformation',
                'label' => 'Digital',
                'description' => 'Training on digital tools, cloud computing, automation, and adapting to technological changes in the workplace.',
                'is_active' => true,
                'framework_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Human Resources Management',
                'label' => 'HR',
                'description' => 'HR-focused training covering recruitment, performance management, employee relations, and HR best practices.',
                'is_active' => false,
                'framework_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::connection('training_management')->table('training_catalogs')->insert($trainingCatalogs);
    }
}
