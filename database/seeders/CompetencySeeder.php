<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all frameworks
        $frameworks = DB::connection('competency_management')->table('competency_frameworks')->get();

        $competenciesData = [
            // Leadership & Management (framework 1)
            'Leadership & Management' => [
                [
                    'competency_name' => 'Strategic Thinking',
                    'description' => 'Ability to develop long-term strategies, anticipate future trends, and align organizational goals with business objectives.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Analyzes market trends; Creates actionable strategic plans; Aligns team objectives with company vision; Identifies growth opportunities',
                    'assessment_criteria' => 'Strategic plan development; Business case creation; Long-term goal achievement rate',
                    'notes' => 'Core competency for senior leadership roles',
                ],
                [
                    'competency_name' => 'Team Leadership',
                    'description' => 'Ability to lead, motivate, and develop team members while fostering a positive and productive work environment.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Provides regular feedback; Delegates effectively; Resolves team conflicts; Mentors team members',
                    'assessment_criteria' => 'Team performance metrics; Employee engagement scores; Retention rates',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Decision Making',
                    'description' => 'Ability to make timely, well-informed decisions by analyzing data, evaluating risks, and considering stakeholder impact.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Gathers relevant information; Weighs pros and cons; Makes timely decisions; Takes accountability for outcomes',
                    'assessment_criteria' => 'Decision quality assessment; Time to decision; Stakeholder satisfaction',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Change Management',
                    'description' => 'Ability to lead organizational change initiatives, manage resistance, and ensure successful adoption of new processes.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Communicates change vision; Addresses resistance; Supports transition; Measures adoption',
                    'assessment_criteria' => 'Change adoption rates; Employee feedback; Project success metrics',
                    'notes' => null,
                ],
            ],

            // Technical Skills (framework 2)
            'Technical Skills' => [
                [
                    'competency_name' => 'Software Development',
                    'description' => 'Proficiency in programming languages, software design patterns, and development methodologies.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Writes clean, maintainable code; Follows coding standards; Participates in code reviews; Implements best practices',
                    'assessment_criteria' => 'Code quality metrics; Bug rates; Peer review feedback; Technical interviews',
                    'notes' => 'Core competency for developers and engineers',
                ],
                [
                    'competency_name' => 'Database Management',
                    'description' => 'Skills in database design, optimization, querying, and administration across various database systems.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Designs efficient schemas; Writes optimized queries; Implements backup strategies; Monitors performance',
                    'assessment_criteria' => 'Query performance; Data integrity; System uptime; Technical assessments',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Cloud Computing',
                    'description' => 'Knowledge of cloud platforms, services, deployment strategies, and cloud architecture best practices.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Deploys cloud solutions; Manages cloud resources; Implements security best practices; Optimizes costs',
                    'assessment_criteria' => 'Cloud certifications; Project implementations; Cost optimization metrics',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'System Architecture',
                    'description' => 'Ability to design scalable, reliable, and secure system architectures that meet business requirements.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Creates architecture diagrams; Evaluates technology options; Ensures scalability; Documents designs',
                    'assessment_criteria' => 'Architecture reviews; System performance; Technical documentation quality',
                    'notes' => null,
                ],
            ],

            // Communication & Interpersonal (framework 3)
            'Communication & Interpersonal' => [
                [
                    'competency_name' => 'Verbal Communication',
                    'description' => 'Ability to express ideas clearly and effectively in spoken communication across various settings.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Speaks clearly; Adapts communication style; Engages audience; Handles Q&A effectively',
                    'assessment_criteria' => 'Presentation feedback; Meeting effectiveness; Peer assessments',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Written Communication',
                    'description' => 'Proficiency in creating clear, professional written documents including emails, reports, and proposals.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Writes clearly; Uses appropriate tone; Proofreads work; Structures documents logically',
                    'assessment_criteria' => 'Document quality reviews; Writing samples; Feedback from stakeholders',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Active Listening',
                    'description' => 'Ability to fully focus on, understand, and respond appropriately to verbal and non-verbal communication.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Maintains eye contact; Asks clarifying questions; Paraphrases for understanding; Shows empathy',
                    'assessment_criteria' => '360-degree feedback; Stakeholder surveys; Observation assessments',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Conflict Resolution',
                    'description' => 'Skills in managing and resolving conflicts constructively while maintaining professional relationships.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Identifies conflict sources; Mediates discussions; Finds common ground; Follows up on resolutions',
                    'assessment_criteria' => 'Conflict resolution outcomes; Team feedback; HR incident tracking',
                    'notes' => null,
                ],
            ],

            // Customer Service (framework 4)
            'Customer Service' => [
                [
                    'competency_name' => 'Customer Relationship Management',
                    'description' => 'Ability to build and maintain strong, positive relationships with customers throughout their journey.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Builds rapport quickly; Maintains regular contact; Anticipates needs; Handles escalations professionally',
                    'assessment_criteria' => 'Customer satisfaction scores; Retention rates; NPS scores',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Problem Resolution',
                    'description' => 'Skills in identifying, analyzing, and resolving customer issues efficiently and effectively.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Identifies root causes; Provides timely solutions; Follows up; Documents issues',
                    'assessment_criteria' => 'First call resolution rate; Resolution time; Customer feedback',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Service Excellence',
                    'description' => 'Commitment to delivering exceptional service that exceeds customer expectations consistently.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Goes above and beyond; Personalizes service; Maintains professionalism; Seeks feedback',
                    'assessment_criteria' => 'Service quality metrics; Customer testimonials; Mystery shopper scores',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Product Knowledge',
                    'description' => 'Deep understanding of company products, services, features, and how they benefit customers.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Explains features clearly; Recommends appropriate solutions; Stays updated on changes; Trains others',
                    'assessment_criteria' => 'Product knowledge tests; Sales conversion rates; Customer feedback',
                    'notes' => null,
                ],
            ],

            // Project Management (framework 5)
            'Project Management' => [
                [
                    'competency_name' => 'Project Planning',
                    'description' => 'Ability to create comprehensive project plans including scope, timeline, resources, and risk management.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Defines clear objectives; Creates detailed timelines; Allocates resources effectively; Identifies risks',
                    'assessment_criteria' => 'Plan quality reviews; Project success rates; Stakeholder feedback',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Stakeholder Management',
                    'description' => 'Skills in identifying, engaging, and managing expectations of all project stakeholders.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Identifies all stakeholders; Communicates regularly; Manages expectations; Addresses concerns',
                    'assessment_criteria' => 'Stakeholder satisfaction surveys; Communication effectiveness; Conflict frequency',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Agile Methodology',
                    'description' => 'Proficiency in Agile frameworks including Scrum, Kanban, and iterative development practices.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Facilitates ceremonies; Manages backlog; Tracks velocity; Promotes continuous improvement',
                    'assessment_criteria' => 'Sprint completion rates; Team velocity; Retrospective outcomes',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Risk Management',
                    'description' => 'Ability to identify, assess, and mitigate project risks throughout the project lifecycle.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Conducts risk assessments; Creates mitigation plans; Monitors risks; Escalates appropriately',
                    'assessment_criteria' => 'Risk identification accuracy; Mitigation effectiveness; Issue occurrence rates',
                    'notes' => null,
                ],
            ],

            // Data & Analytics (framework 6)
            'Data & Analytics' => [
                [
                    'competency_name' => 'Data Analysis',
                    'description' => 'Skills in collecting, processing, and analyzing data to extract meaningful insights and support decision-making.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Cleans data effectively; Applies appropriate methods; Interprets results accurately; Communicates findings',
                    'assessment_criteria' => 'Analysis quality; Insight accuracy; Business impact of recommendations',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Data Visualization',
                    'description' => 'Ability to create compelling visual representations of data that communicate insights effectively.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Chooses appropriate chart types; Creates clear dashboards; Tells data stories; Uses tools proficiently',
                    'assessment_criteria' => 'Dashboard usage metrics; Stakeholder feedback; Visualization best practices adherence',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Statistical Methods',
                    'description' => 'Knowledge of statistical techniques for data analysis, hypothesis testing, and predictive modeling.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Applies correct statistical tests; Interprets significance; Validates assumptions; Documents methodology',
                    'assessment_criteria' => 'Statistical accuracy; Model performance; Peer reviews',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Business Intelligence',
                    'description' => 'Proficiency in BI tools and techniques to transform data into actionable business insights.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Builds effective reports; Automates data pipelines; Maintains data quality; Supports self-service analytics',
                    'assessment_criteria' => 'Report accuracy; User adoption; Data freshness',
                    'notes' => null,
                ],
            ],

            // Compliance & Risk (framework 7)
            'Compliance & Risk' => [
                [
                    'competency_name' => 'Regulatory Knowledge',
                    'description' => 'Understanding of relevant laws, regulations, and industry standards that apply to the organization.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Stays current with regulations; Interprets requirements; Advises on compliance; Monitors changes',
                    'assessment_criteria' => 'Compliance audit results; Regulatory knowledge tests; Advisory quality',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Risk Assessment',
                    'description' => 'Ability to identify, evaluate, and prioritize organizational risks across various domains.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Conducts thorough assessments; Quantifies risks; Prioritizes appropriately; Documents findings',
                    'assessment_criteria' => 'Risk identification accuracy; Assessment quality; Audit findings',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Policy Development',
                    'description' => 'Skills in creating, implementing, and maintaining organizational policies and procedures.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Drafts clear policies; Ensures stakeholder input; Communicates changes; Reviews regularly',
                    'assessment_criteria' => 'Policy compliance rates; User understanding; Audit compliance',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Ethics & Integrity',
                    'description' => 'Commitment to ethical conduct, integrity, and promoting a culture of compliance.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Models ethical behavior; Reports violations; Supports investigations; Promotes awareness',
                    'assessment_criteria' => 'Ethics training completion; Incident reports; Culture surveys',
                    'notes' => null,
                ],
            ],

            // Human Resources (framework 8)
            'Human Resources' => [
                [
                    'competency_name' => 'Talent Acquisition',
                    'description' => 'Skills in attracting, evaluating, and hiring top talent that fits organizational needs and culture.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Sources effectively; Conducts structured interviews; Evaluates cultural fit; Manages candidate experience',
                    'assessment_criteria' => 'Time to hire; Quality of hire; Candidate satisfaction; Diversity metrics',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Performance Management',
                    'description' => 'Ability to implement and manage performance evaluation systems that drive employee development.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Sets clear expectations; Provides regular feedback; Conducts fair evaluations; Supports improvement',
                    'assessment_criteria' => 'Appraisal completion rates; Employee satisfaction; Performance improvement outcomes',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Employee Relations',
                    'description' => 'Skills in maintaining positive employee relations, handling grievances, and fostering workplace harmony.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Handles complaints fairly; Mediates disputes; Maintains confidentiality; Promotes positive culture',
                    'assessment_criteria' => 'Employee engagement scores; Grievance resolution rates; Turnover rates',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Learning & Development',
                    'description' => 'Ability to design, implement, and evaluate training programs that develop employee skills.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Identifies training needs; Designs effective programs; Measures impact; Supports career development',
                    'assessment_criteria' => 'Training effectiveness; Skill development metrics; Career progression rates',
                    'notes' => null,
                ],
            ],

            // Finance & Accounting (framework 9)
            'Finance & Accounting' => [
                [
                    'competency_name' => 'Financial Analysis',
                    'description' => 'Ability to analyze financial data, identify trends, and provide insights for business decisions.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Interprets financial statements; Performs ratio analysis; Identifies variances; Provides recommendations',
                    'assessment_criteria' => 'Analysis accuracy; Insight quality; Decision support effectiveness',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Budgeting & Forecasting',
                    'description' => 'Skills in developing accurate budgets and financial forecasts that support strategic planning.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Creates realistic budgets; Tracks variances; Updates forecasts; Collaborates with stakeholders',
                    'assessment_criteria' => 'Budget accuracy; Forecast reliability; Variance explanations',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Accounting Standards',
                    'description' => 'Knowledge of accounting principles, standards (GAAP/IFRS), and proper financial reporting.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Applies standards correctly; Ensures accurate reporting; Stays current with changes; Documents properly',
                    'assessment_criteria' => 'Audit results; Reporting accuracy; Compliance rates',
                    'notes' => null,
                ],
                [
                    'competency_name' => 'Internal Controls',
                    'description' => 'Ability to design, implement, and monitor internal controls to safeguard assets and ensure accuracy.',
                    'proficiency_levels' => 5,
                    'status' => 'active',
                    'behavioral_indicators' => 'Identifies control needs; Implements procedures; Tests effectiveness; Reports deficiencies',
                    'assessment_criteria' => 'Control testing results; Audit findings; Risk mitigation effectiveness',
                    'notes' => null,
                ],
            ],

            // Digital Transformation (framework 10)
            'Digital Transformation' => [
                [
                    'competency_name' => 'Digital Strategy',
                    'description' => 'Ability to develop and execute digital transformation strategies aligned with business objectives.',
                    'proficiency_levels' => 5,
                    'status' => 'draft',
                    'behavioral_indicators' => 'Identifies digital opportunities; Creates roadmaps; Aligns with business goals; Measures success',
                    'assessment_criteria' => 'Strategy execution; Digital adoption rates; Business impact metrics',
                    'notes' => 'Under development',
                ],
                [
                    'competency_name' => 'Innovation Management',
                    'description' => 'Skills in fostering innovation, evaluating new technologies, and driving continuous improvement.',
                    'proficiency_levels' => 5,
                    'status' => 'draft',
                    'behavioral_indicators' => 'Encourages experimentation; Evaluates innovations; Implements improvements; Manages failures constructively',
                    'assessment_criteria' => 'Innovation pipeline; Implementation success; ROI on innovations',
                    'notes' => 'Under development',
                ],
                [
                    'competency_name' => 'Technology Adoption',
                    'description' => 'Ability to evaluate, implement, and drive adoption of new technologies across the organization.',
                    'proficiency_levels' => 5,
                    'status' => 'draft',
                    'behavioral_indicators' => 'Assesses technology fit; Plans implementations; Trains users; Monitors adoption',
                    'assessment_criteria' => 'Adoption rates; User proficiency; Technology utilization',
                    'notes' => 'Under development',
                ],
                [
                    'competency_name' => 'Digital Literacy',
                    'description' => 'Foundational skills in using digital tools, platforms, and technologies effectively.',
                    'proficiency_levels' => 5,
                    'status' => 'draft',
                    'behavioral_indicators' => 'Uses digital tools proficiently; Adapts to new platforms; Supports others; Maintains security awareness',
                    'assessment_criteria' => 'Tool proficiency assessments; Productivity metrics; Security compliance',
                    'notes' => 'Under development',
                ],
            ],
        ];

        $counter = 1;
        foreach ($frameworks as $framework) {
            if (isset($competenciesData[$framework->framework_name])) {
                foreach ($competenciesData[$framework->framework_name] as $competency) {
                    DB::connection('competency_management')->table('competencies')->insert([
                        'competency_id' => 'CMP-' . str_pad($counter, 3, '0', STR_PAD_LEFT),
                        'competency_name' => $competency['competency_name'],
                        'description' => $competency['description'],
                        'framework_id' => $framework->id,
                        'proficiency_levels' => $competency['proficiency_levels'],
                        'status' => $competency['status'],
                        'behavioral_indicators' => $competency['behavioral_indicators'],
                        'assessment_criteria' => $competency['assessment_criteria'],
                        'notes' => $competency['notes'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $counter++;
                }
            }
        }
    }
}
