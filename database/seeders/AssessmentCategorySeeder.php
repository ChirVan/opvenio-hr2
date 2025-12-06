<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssessmentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'category_name' => 'Technical Skills Assessment',
                'category_slug' => 'technical-skills-assessment',
                'category_icon' => 'fas fa-code',
                'description' => 'Evaluate technical competencies including programming, software development, system administration, and IT infrastructure skills. These assessments measure proficiency in technical tools and technologies.',
                'color_theme' => 'blue',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Leadership & Management',
                'category_slug' => 'leadership-management',
                'category_icon' => 'fas fa-users-cog',
                'description' => 'Assess leadership capabilities, decision-making skills, team management, strategic thinking, and organizational abilities. Ideal for current and aspiring managers.',
                'color_theme' => 'purple',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Communication Skills',
                'category_slug' => 'communication-skills',
                'category_icon' => 'fas fa-comments',
                'description' => 'Measure verbal and written communication abilities, presentation skills, active listening, and interpersonal effectiveness in professional settings.',
                'color_theme' => 'green',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Compliance & Regulations',
                'category_slug' => 'compliance-regulations',
                'category_icon' => 'fas fa-balance-scale',
                'description' => 'Test knowledge of company policies, industry regulations, legal requirements, and ethical standards. Essential for maintaining organizational compliance.',
                'color_theme' => 'red',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Customer Service Excellence',
                'category_slug' => 'customer-service-excellence',
                'category_icon' => 'fas fa-headset',
                'description' => 'Evaluate customer interaction skills, problem-solving abilities, empathy, and service delivery quality. Designed for customer-facing roles.',
                'color_theme' => 'orange',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Data Analytics & BI',
                'category_slug' => 'data-analytics-bi',
                'category_icon' => 'fas fa-chart-bar',
                'description' => 'Assess data analysis capabilities, statistical knowledge, visualization skills, and business intelligence tool proficiency.',
                'color_theme' => 'teal',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Project Management',
                'category_slug' => 'project-management',
                'category_icon' => 'fas fa-tasks',
                'description' => 'Evaluate project planning, execution, monitoring, and closing skills. Includes assessment of Agile, Scrum, and traditional methodologies.',
                'color_theme' => 'indigo',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Health & Safety',
                'category_slug' => 'health-safety',
                'category_icon' => 'fas fa-hard-hat',
                'description' => 'Test knowledge of workplace safety protocols, emergency procedures, hazard identification, and occupational health standards.',
                'color_theme' => 'pink',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Product Knowledge',
                'category_slug' => 'product-knowledge',
                'category_icon' => 'fas fa-box-open',
                'description' => 'Assess understanding of company products, services, features, benefits, and competitive advantages. Essential for sales and support teams.',
                'color_theme' => 'blue',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Onboarding Assessment',
                'category_slug' => 'onboarding-assessment',
                'category_icon' => 'fas fa-user-plus',
                'description' => 'Evaluate new employee understanding of company culture, policies, procedures, and role-specific requirements during the onboarding process.',
                'color_theme' => 'green',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::connection('learning_management')->table('assessment_categories')->insert($categories);
    }
}
