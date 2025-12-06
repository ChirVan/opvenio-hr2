<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all training catalogs
        $catalogs = DB::connection('training_management')->table('training_catalogs')->get();

        $materialsData = [
            // Leadership Development (catalog 1)
            'Leadership Development' => [
                [
                    'lesson_title' => 'Introduction to Leadership Fundamentals',
                    'competency_id' => 1,
                    'proficiency_level' => 1,
                    'lesson_content' => '<h2>Leadership Fundamentals</h2><p>This lesson covers the core principles of effective leadership, including understanding different leadership styles, the importance of emotional intelligence, and building trust within teams.</p><h3>Learning Objectives</h3><ul><li>Understand the difference between management and leadership</li><li>Identify your personal leadership style</li><li>Learn the fundamentals of servant leadership</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Strategic Decision Making',
                    'competency_id' => 1,
                    'proficiency_level' => 2,
                    'lesson_content' => '<h2>Strategic Decision Making</h2><p>Learn how to make informed decisions that align with organizational goals. This module covers decision-making frameworks, risk assessment, and stakeholder analysis.</p><h3>Key Topics</h3><ul><li>SWOT Analysis for decision making</li><li>Risk-benefit evaluation</li><li>Consensus building techniques</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Executive Leadership & Vision',
                    'competency_id' => 1,
                    'proficiency_level' => 3,
                    'lesson_content' => '<h2>Executive Leadership</h2><p>Advanced leadership concepts for senior executives, focusing on organizational vision, change management at scale, and building high-performance cultures.</p><h3>Advanced Topics</h3><ul><li>Creating and communicating organizational vision</li><li>Leading through transformation</li><li>Building executive presence</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
            ],

            // Technical Skills Training (catalog 2)
            'Technical Skills Training' => [
                [
                    'lesson_title' => 'Programming Basics with Python',
                    'competency_id' => 2,
                    'proficiency_level' => 1,
                    'lesson_content' => '<h2>Python Programming Basics</h2><p>Get started with Python programming. Learn syntax, data types, control structures, and basic problem-solving techniques.</p><h3>Topics Covered</h3><ul><li>Python installation and setup</li><li>Variables and data types</li><li>Loops and conditional statements</li><li>Functions and modules</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Web Development with Laravel',
                    'competency_id' => 2,
                    'proficiency_level' => 2,
                    'lesson_content' => '<h2>Laravel Web Development</h2><p>Build modern web applications using the Laravel PHP framework. Learn MVC architecture, routing, database management, and authentication.</p><h3>Course Content</h3><ul><li>Laravel installation and configuration</li><li>Routing and controllers</li><li>Eloquent ORM and migrations</li><li>Blade templating engine</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'System Architecture & Design Patterns',
                    'competency_id' => 2,
                    'proficiency_level' => 3,
                    'lesson_content' => '<h2>Advanced System Architecture</h2><p>Master enterprise-level software architecture, design patterns, and scalable system design principles.</p><h3>Advanced Concepts</h3><ul><li>Microservices architecture</li><li>Design patterns (SOLID, DRY, KISS)</li><li>Scalability and performance optimization</li><li>CI/CD pipelines</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
            ],

            // Communication Excellence (catalog 3)
            'Communication Excellence' => [
                [
                    'lesson_title' => 'Effective Business Writing',
                    'competency_id' => 3,
                    'proficiency_level' => 1,
                    'lesson_content' => '<h2>Business Writing Essentials</h2><p>Learn to write clear, concise, and professional business documents including emails, reports, and proposals.</p><h3>Writing Skills</h3><ul><li>Email etiquette and best practices</li><li>Structuring business documents</li><li>Grammar and punctuation review</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Presentation Skills Mastery',
                    'competency_id' => 3,
                    'proficiency_level' => 2,
                    'lesson_content' => '<h2>Presentation Skills</h2><p>Develop compelling presentation skills to engage audiences and deliver impactful messages. Learn storytelling techniques and visual design principles.</p><h3>Key Areas</h3><ul><li>Structuring presentations for impact</li><li>Using visual aids effectively</li><li>Handling Q&A sessions</li><li>Managing presentation anxiety</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Executive Communication & Influence',
                    'competency_id' => 3,
                    'proficiency_level' => 3,
                    'lesson_content' => '<h2>Executive Communication</h2><p>Advanced communication strategies for leaders including stakeholder management, crisis communication, and building organizational influence.</p><h3>Expert Topics</h3><ul><li>C-suite communication strategies</li><li>Crisis communication management</li><li>Influencing without authority</li></ul>',
                    'status' => 'draft',
                    'is_active' => true,
                ],
            ],

            // Project Management (catalog 4)
            'Project Management' => [
                [
                    'lesson_title' => 'Project Management Fundamentals',
                    'competency_id' => 4,
                    'proficiency_level' => 1,
                    'lesson_content' => '<h2>PM Fundamentals</h2><p>Introduction to project management concepts, methodologies, and tools. Learn the project lifecycle and basic planning techniques.</p><h3>Core Concepts</h3><ul><li>Project lifecycle phases</li><li>Work breakdown structures</li><li>Basic scheduling and Gantt charts</li><li>Stakeholder identification</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Agile & Scrum Methodology',
                    'competency_id' => 4,
                    'proficiency_level' => 2,
                    'lesson_content' => '<h2>Agile & Scrum</h2><p>Deep dive into Agile methodology and Scrum framework. Learn sprint planning, daily standups, retrospectives, and backlog management.</p><h3>Agile Practices</h3><ul><li>Scrum roles and ceremonies</li><li>User story writing</li><li>Sprint planning and execution</li><li>Velocity and burndown charts</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Program & Portfolio Management',
                    'competency_id' => 4,
                    'proficiency_level' => 3,
                    'lesson_content' => '<h2>Program & Portfolio Management</h2><p>Advanced concepts for managing multiple projects, resource allocation, and strategic alignment of project portfolios.</p><h3>Advanced Topics</h3><ul><li>Portfolio optimization</li><li>Resource capacity planning</li><li>Strategic alignment metrics</li><li>PMO governance</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
            ],

            // Compliance & Ethics (catalog 5)
            'Compliance & Ethics' => [
                [
                    'lesson_title' => 'Workplace Code of Conduct',
                    'competency_id' => 5,
                    'proficiency_level' => 1,
                    'lesson_content' => '<h2>Code of Conduct</h2><p>Understanding company policies, ethical standards, and expected workplace behaviors. This mandatory training covers harassment prevention and reporting procedures.</p><h3>Key Policies</h3><ul><li>Anti-harassment policy</li><li>Conflict of interest guidelines</li><li>Reporting mechanisms</li><li>Disciplinary procedures</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Data Privacy & GDPR Compliance',
                    'competency_id' => 5,
                    'proficiency_level' => 2,
                    'lesson_content' => '<h2>Data Privacy Compliance</h2><p>Learn about data protection regulations, GDPR requirements, and best practices for handling sensitive information.</p><h3>Compliance Areas</h3><ul><li>GDPR principles and rights</li><li>Data processing requirements</li><li>Breach notification procedures</li><li>Privacy by design</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Anti-Money Laundering (AML) Training',
                    'competency_id' => 5,
                    'proficiency_level' => 3,
                    'lesson_content' => '<h2>AML Compliance</h2><p>Advanced compliance training on anti-money laundering regulations, suspicious activity detection, and regulatory reporting requirements.</p><h3>AML Topics</h3><ul><li>KYC (Know Your Customer) procedures</li><li>Red flags and suspicious activities</li><li>SAR filing requirements</li><li>Regulatory framework overview</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
            ],

            // Customer Service Excellence (catalog 6)
            'Customer Service Excellence' => [
                [
                    'lesson_title' => 'Customer Service Basics',
                    'competency_id' => 6,
                    'proficiency_level' => 1,
                    'lesson_content' => '<h2>Customer Service Fundamentals</h2><p>Learn the basics of providing excellent customer service, including active listening, empathy, and problem resolution.</p><h3>Core Skills</h3><ul><li>Active listening techniques</li><li>Empathy in customer interactions</li><li>Professional phone etiquette</li><li>Basic problem resolution</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Handling Difficult Customers',
                    'competency_id' => 6,
                    'proficiency_level' => 2,
                    'lesson_content' => '<h2>Difficult Customer Management</h2><p>Strategies for de-escalating tense situations, managing complaints, and turning negative experiences into positive outcomes.</p><h3>De-escalation Techniques</h3><ul><li>Recognizing escalation triggers</li><li>Calm communication strategies</li><li>Complaint resolution framework</li><li>When to escalate to management</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Customer Experience Strategy',
                    'competency_id' => 6,
                    'proficiency_level' => 3,
                    'lesson_content' => '<h2>CX Strategy Development</h2><p>Design and implement customer experience strategies that drive loyalty and business growth. Learn journey mapping and NPS optimization.</p><h3>Strategic Topics</h3><ul><li>Customer journey mapping</li><li>NPS and satisfaction metrics</li><li>Voice of customer programs</li><li>Service design thinking</li></ul>',
                    'status' => 'draft',
                    'is_active' => true,
                ],
            ],

            // Data Analytics & Business Intelligence (catalog 7)
            'Data Analytics & Business Intelligence' => [
                [
                    'lesson_title' => 'Introduction to Data Analysis',
                    'competency_id' => 7,
                    'proficiency_level' => 1,
                    'lesson_content' => '<h2>Data Analysis Basics</h2><p>Get started with data analysis using spreadsheets and basic statistical concepts. Learn data cleaning and simple visualization techniques.</p><h3>Foundation Skills</h3><ul><li>Data types and structures</li><li>Basic Excel/Sheets formulas</li><li>Data cleaning best practices</li><li>Creating basic charts</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'SQL for Data Analysis',
                    'competency_id' => 7,
                    'proficiency_level' => 2,
                    'lesson_content' => '<h2>SQL Data Analysis</h2><p>Master SQL queries for extracting, transforming, and analyzing data from relational databases. Learn joins, aggregations, and subqueries.</p><h3>SQL Topics</h3><ul><li>SELECT statements and filtering</li><li>JOINs and table relationships</li><li>Aggregation functions</li><li>Window functions</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Advanced Analytics & Machine Learning',
                    'competency_id' => 7,
                    'proficiency_level' => 3,
                    'lesson_content' => '<h2>Advanced Analytics</h2><p>Explore predictive analytics, machine learning concepts, and advanced statistical modeling for business insights.</p><h3>Advanced Topics</h3><ul><li>Predictive modeling fundamentals</li><li>Regression and classification</li><li>A/B testing and experimentation</li><li>ML model deployment basics</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
            ],

            // Health & Safety (catalog 8)
            'Health & Safety' => [
                [
                    'lesson_title' => 'Workplace Safety Orientation',
                    'competency_id' => 8,
                    'proficiency_level' => 1,
                    'lesson_content' => '<h2>Safety Orientation</h2><p>Essential workplace safety training covering emergency procedures, hazard awareness, and personal protective equipment usage.</p><h3>Safety Basics</h3><ul><li>Emergency evacuation procedures</li><li>Fire safety and extinguisher use</li><li>PPE requirements</li><li>Incident reporting</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Ergonomics & Workplace Wellness',
                    'competency_id' => 8,
                    'proficiency_level' => 2,
                    'lesson_content' => '<h2>Ergonomics Training</h2><p>Learn proper workstation setup, posture, and wellness practices to prevent repetitive strain injuries and promote health.</p><h3>Wellness Topics</h3><ul><li>Workstation ergonomics</li><li>Stretching and movement breaks</li><li>Eye strain prevention</li><li>Mental health awareness</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Safety Management Systems',
                    'competency_id' => 8,
                    'proficiency_level' => 3,
                    'lesson_content' => '<h2>Safety Management</h2><p>Advanced training for safety managers on implementing and maintaining comprehensive safety management systems.</p><h3>Management Topics</h3><ul><li>OSHA compliance requirements</li><li>Safety audit procedures</li><li>Incident investigation methods</li><li>Safety culture development</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
            ],

            // Digital Transformation (catalog 9)
            'Digital Transformation' => [
                [
                    'lesson_title' => 'Digital Tools for Modern Work',
                    'competency_id' => 9,
                    'proficiency_level' => 1,
                    'lesson_content' => '<h2>Digital Tools Overview</h2><p>Introduction to modern digital collaboration tools, cloud services, and productivity applications for the digital workplace.</p><h3>Tools Covered</h3><ul><li>Cloud storage and sharing</li><li>Video conferencing best practices</li><li>Team collaboration platforms</li><li>Digital document management</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Process Automation Fundamentals',
                    'competency_id' => 9,
                    'proficiency_level' => 2,
                    'lesson_content' => '<h2>Process Automation</h2><p>Learn how to identify automation opportunities and implement workflow automation using low-code/no-code platforms.</p><h3>Automation Topics</h3><ul><li>Process mapping for automation</li><li>RPA (Robotic Process Automation) basics</li><li>Low-code automation tools</li><li>Measuring automation ROI</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Leading Digital Transformation',
                    'competency_id' => 9,
                    'proficiency_level' => 3,
                    'lesson_content' => '<h2>Digital Transformation Leadership</h2><p>Strategic approaches to leading organizational digital transformation initiatives, change management, and building digital-first cultures.</p><h3>Leadership Topics</h3><ul><li>Digital strategy development</li><li>Change management for digital initiatives</li><li>Building digital capabilities</li><li>Measuring transformation success</li></ul>',
                    'status' => 'draft',
                    'is_active' => true,
                ],
            ],

            // Human Resources Management (catalog 10)
            'Human Resources Management' => [
                [
                    'lesson_title' => 'HR Fundamentals for Managers',
                    'competency_id' => 10,
                    'proficiency_level' => 1,
                    'lesson_content' => '<h2>HR Basics for Managers</h2><p>Essential HR knowledge for people managers including hiring basics, performance conversations, and employment law fundamentals.</p><h3>Manager Essentials</h3><ul><li>Interview best practices</li><li>Performance feedback techniques</li><li>Basic employment law</li><li>Documentation requirements</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Talent Acquisition Strategies',
                    'competency_id' => 10,
                    'proficiency_level' => 2,
                    'lesson_content' => '<h2>Talent Acquisition</h2><p>Modern recruitment strategies, employer branding, and candidate experience optimization for attracting top talent.</p><h3>Recruitment Topics</h3><ul><li>Job description optimization</li><li>Sourcing strategies</li><li>Employer branding</li><li>Structured interviewing</li></ul>',
                    'status' => 'published',
                    'is_active' => true,
                ],
                [
                    'lesson_title' => 'Strategic HR Business Partnership',
                    'competency_id' => 10,
                    'proficiency_level' => 3,
                    'lesson_content' => '<h2>HR Business Partnership</h2><p>Advanced HR strategy for business partners including organizational development, workforce planning, and HR analytics.</p><h3>Strategic HR Topics</h3><ul><li>HR metrics and analytics</li><li>Workforce planning</li><li>Organizational design</li><li>Culture transformation</li></ul>',
                    'status' => 'archived',
                    'is_active' => false,
                ],
            ],
        ];

        foreach ($catalogs as $catalog) {
            if (isset($materialsData[$catalog->title])) {
                foreach ($materialsData[$catalog->title] as $material) {
                    DB::connection('training_management')->table('training_materials')->insert([
                        'lesson_title' => $material['lesson_title'],
                        'training_catalog_id' => $catalog->id,
                        'competency_id' => $material['competency_id'],
                        'proficiency_level' => $material['proficiency_level'],
                        'lesson_content' => $material['lesson_content'],
                        'status' => $material['status'],
                        'is_active' => $material['is_active'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
