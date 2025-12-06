<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all assessment categories
        $categories = DB::connection('learning_management')->table('assessment_categories')->get();
        
        // Get competencies from competency_management database to map by name
        $competencies = DB::connection('competency_management')->table('competencies')->get()->keyBy('competency_name');

        $quizzesData = [
            // Technical Skills Assessment (category 1)
            'Technical Skills Assessment' => [
                [
                    'quiz_title' => 'Python Fundamentals Quiz',
                    'competency_name' => 'Software Development',
                    'description' => 'Test your knowledge of Python programming basics including data types, control structures, functions, and object-oriented programming concepts.',
                    'time_limit' => 30,
                    'status' => 'published',
                    'total_questions' => 20,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'SQL Database Proficiency Test',
                    'competency_name' => 'Database Management',
                    'description' => 'Evaluate your SQL skills including queries, joins, aggregations, subqueries, and database design principles.',
                    'time_limit' => 45,
                    'status' => 'published',
                    'total_questions' => 25,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'Web Development Assessment',
                    'competency_name' => 'Software Development',
                    'description' => 'Comprehensive assessment covering HTML, CSS, JavaScript, and modern web development frameworks.',
                    'time_limit' => 60,
                    'status' => 'draft',
                    'total_questions' => 30,
                    'total_points' => 150,
                ],
            ],

            // Leadership & Management (category 2)
            'Leadership & Management' => [
                [
                    'quiz_title' => 'Leadership Styles Assessment',
                    'competency_name' => 'Team Leadership',
                    'description' => 'Identify and understand different leadership styles and when to apply them effectively in various organizational situations.',
                    'time_limit' => 25,
                    'status' => 'published',
                    'total_questions' => 15,
                    'total_points' => 75,
                ],
                [
                    'quiz_title' => 'Team Management Skills Quiz',
                    'competency_name' => 'Team Leadership',
                    'description' => 'Assess your ability to manage teams, delegate tasks, resolve conflicts, and motivate team members.',
                    'time_limit' => 30,
                    'status' => 'published',
                    'total_questions' => 20,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'Strategic Decision Making Test',
                    'competency_name' => 'Decision Making',
                    'description' => 'Evaluate your strategic thinking and decision-making abilities in complex business scenarios.',
                    'time_limit' => 40,
                    'status' => 'published',
                    'total_questions' => 20,
                    'total_points' => 100,
                ],
            ],

            // Communication Skills (category 3)
            'Communication Skills' => [
                [
                    'quiz_title' => 'Business Writing Skills Test',
                    'competency_name' => 'Written Communication',
                    'description' => 'Assess your professional writing skills including email etiquette, report writing, and business correspondence.',
                    'time_limit' => 35,
                    'status' => 'published',
                    'total_questions' => 20,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'Presentation Skills Assessment',
                    'competency_name' => 'Verbal Communication',
                    'description' => 'Evaluate your ability to create and deliver effective presentations, handle Q&A sessions, and engage audiences.',
                    'time_limit' => 25,
                    'status' => 'published',
                    'total_questions' => 15,
                    'total_points' => 75,
                ],
                [
                    'quiz_title' => 'Active Listening Quiz',
                    'competency_name' => 'Active Listening',
                    'description' => 'Test your understanding of active listening techniques and their application in professional settings.',
                    'time_limit' => 20,
                    'status' => 'published',
                    'total_questions' => 15,
                    'total_points' => 75,
                ],
            ],

            // Compliance & Regulations (category 4)
            'Compliance & Regulations' => [
                [
                    'quiz_title' => 'Company Policy Compliance Test',
                    'competency_name' => 'Policy Development',
                    'description' => 'Mandatory assessment on company policies, code of conduct, and workplace behavior expectations.',
                    'time_limit' => 30,
                    'status' => 'published',
                    'total_questions' => 25,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'Data Privacy & GDPR Quiz',
                    'competency_name' => 'Regulatory Knowledge',
                    'description' => 'Test your knowledge of data protection regulations, GDPR compliance, and privacy best practices.',
                    'time_limit' => 25,
                    'status' => 'published',
                    'total_questions' => 20,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'Anti-Harassment Training Assessment',
                    'competency_name' => 'Ethics & Integrity',
                    'description' => 'Evaluate understanding of harassment prevention, reporting procedures, and creating a respectful workplace.',
                    'time_limit' => 20,
                    'status' => 'published',
                    'total_questions' => 15,
                    'total_points' => 75,
                ],
            ],

            // Customer Service Excellence (category 5)
            'Customer Service Excellence' => [
                [
                    'quiz_title' => 'Customer Service Fundamentals',
                    'competency_name' => 'Service Excellence',
                    'description' => 'Assess basic customer service skills including communication, empathy, and problem resolution.',
                    'time_limit' => 25,
                    'status' => 'published',
                    'total_questions' => 20,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'Handling Difficult Customers Quiz',
                    'competency_name' => 'Problem Resolution',
                    'description' => 'Test your ability to de-escalate situations, manage complaints, and maintain professionalism under pressure.',
                    'time_limit' => 30,
                    'status' => 'published',
                    'total_questions' => 20,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'Customer Experience Strategy Assessment',
                    'competency_name' => 'Customer Relationship Management',
                    'description' => 'Evaluate understanding of CX principles, journey mapping, and customer satisfaction metrics.',
                    'time_limit' => 35,
                    'status' => 'draft',
                    'total_questions' => 25,
                    'total_points' => 125,
                ],
            ],

            // Data Analytics & BI (category 6)
            'Data Analytics & BI' => [
                [
                    'quiz_title' => 'Data Analysis Fundamentals Quiz',
                    'competency_name' => 'Data Analysis',
                    'description' => 'Test basic data analysis skills including data cleaning, visualization, and interpretation.',
                    'time_limit' => 30,
                    'status' => 'published',
                    'total_questions' => 20,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'Excel & Spreadsheet Skills Test',
                    'competency_name' => 'Data Visualization',
                    'description' => 'Assess proficiency in Excel functions, pivot tables, charts, and data manipulation techniques.',
                    'time_limit' => 40,
                    'status' => 'published',
                    'total_questions' => 25,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'Statistical Analysis Assessment',
                    'competency_name' => 'Statistical Methods',
                    'description' => 'Evaluate understanding of statistical concepts, hypothesis testing, and data-driven decision making.',
                    'time_limit' => 45,
                    'status' => 'published',
                    'total_questions' => 25,
                    'total_points' => 125,
                ],
            ],

            // Project Management (category 7)
            'Project Management' => [
                [
                    'quiz_title' => 'Project Management Basics Quiz',
                    'competency_name' => 'Project Planning',
                    'description' => 'Test fundamental project management concepts including planning, scheduling, and resource management.',
                    'time_limit' => 30,
                    'status' => 'published',
                    'total_questions' => 20,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'Agile & Scrum Certification Prep',
                    'competency_name' => 'Agile Methodology',
                    'description' => 'Comprehensive assessment on Agile principles, Scrum framework, and sprint management.',
                    'time_limit' => 45,
                    'status' => 'published',
                    'total_questions' => 30,
                    'total_points' => 150,
                ],
                [
                    'quiz_title' => 'Risk Management Assessment',
                    'competency_name' => 'Risk Management',
                    'description' => 'Evaluate your ability to identify, assess, and mitigate project risks effectively.',
                    'time_limit' => 25,
                    'status' => 'published',
                    'total_questions' => 15,
                    'total_points' => 75,
                ],
            ],

            // Health & Safety (category 8)
            'Health & Safety' => [
                [
                    'quiz_title' => 'Workplace Safety Orientation Quiz',
                    'competency_name' => 'Risk Assessment',
                    'description' => 'Mandatory safety training covering emergency procedures, hazard identification, and safety protocols.',
                    'time_limit' => 20,
                    'status' => 'published',
                    'total_questions' => 20,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'Fire Safety & Emergency Response',
                    'competency_name' => 'Risk Assessment',
                    'description' => 'Test knowledge of fire safety procedures, evacuation routes, and emergency response protocols.',
                    'time_limit' => 15,
                    'status' => 'published',
                    'total_questions' => 15,
                    'total_points' => 75,
                ],
                [
                    'quiz_title' => 'Ergonomics & Workplace Wellness',
                    'competency_name' => 'Employee Relations',
                    'description' => 'Assess understanding of ergonomic principles, proper workstation setup, and health practices.',
                    'time_limit' => 20,
                    'status' => 'published',
                    'total_questions' => 15,
                    'total_points' => 75,
                ],
            ],

            // Product Knowledge (category 9)
            'Product Knowledge' => [
                [
                    'quiz_title' => 'Core Products Overview Quiz',
                    'competency_name' => 'Product Knowledge',
                    'description' => 'Test knowledge of company\'s core products, features, benefits, and use cases.',
                    'time_limit' => 30,
                    'status' => 'published',
                    'total_questions' => 25,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'Competitive Analysis Assessment',
                    'competency_name' => 'Strategic Thinking',
                    'description' => 'Evaluate understanding of competitive landscape and product differentiators.',
                    'time_limit' => 25,
                    'status' => 'published',
                    'total_questions' => 20,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'New Product Launch Certification',
                    'competency_name' => 'Product Knowledge',
                    'description' => 'Certification quiz for newly launched products and services.',
                    'time_limit' => 35,
                    'status' => 'draft',
                    'total_questions' => 25,
                    'total_points' => 125,
                ],
            ],

            // Onboarding Assessment (category 10)
            'Onboarding Assessment' => [
                [
                    'quiz_title' => 'Company Culture & Values Quiz',
                    'competency_name' => 'Ethics & Integrity',
                    'description' => 'Test understanding of company mission, vision, values, and organizational culture.',
                    'time_limit' => 20,
                    'status' => 'published',
                    'total_questions' => 15,
                    'total_points' => 75,
                ],
                [
                    'quiz_title' => 'New Employee Orientation Test',
                    'competency_name' => 'Learning & Development',
                    'description' => 'Comprehensive assessment covering all onboarding materials and company procedures.',
                    'time_limit' => 30,
                    'status' => 'published',
                    'total_questions' => 25,
                    'total_points' => 100,
                ],
                [
                    'quiz_title' => 'Systems & Tools Training Quiz',
                    'competency_name' => 'Digital Literacy',
                    'description' => 'Test proficiency with company systems, software tools, and internal platforms.',
                    'time_limit' => 25,
                    'status' => 'published',
                    'total_questions' => 20,
                    'total_points' => 100,
                ],
            ],
        ];

        foreach ($categories as $category) {
            if (isset($quizzesData[$category->category_name])) {
                foreach ($quizzesData[$category->category_name] as $quiz) {
                    // Get the competency ID by name
                    $competencyId = isset($competencies[$quiz['competency_name']]) 
                        ? $competencies[$quiz['competency_name']]->id 
                        : 1; // fallback to 1 if not found
                    
                    DB::connection('learning_management')->table('quizzes')->insert([
                        'quiz_title' => $quiz['quiz_title'],
                        'category_id' => $category->id,
                        'competency_id' => $competencyId,
                        'description' => $quiz['description'],
                        'time_limit' => $quiz['time_limit'],
                        'status' => $quiz['status'],
                        'total_questions' => $quiz['total_questions'],
                        'total_points' => $quiz['total_points'],
                        'created_by' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
