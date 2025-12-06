<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all quizzes
        $quizzes = DB::connection('learning_management')->table('quizzes')->get();

        $questionsData = [
            // Python Fundamentals Quiz
            'Python Fundamentals Quiz' => [
                ['question' => 'What keyword is used to define a function in Python?', 'correct_answer' => 'def', 'points' => 5],
                ['question' => 'What is the output of print(type([]))?', 'correct_answer' => 'list', 'points' => 5],
                ['question' => 'Which method is used to add an element to the end of a list?', 'correct_answer' => 'append', 'points' => 5],
                ['question' => 'What symbol is used for single-line comments in Python?', 'correct_answer' => '#', 'points' => 5],
                ['question' => 'What keyword is used to create a class in Python?', 'correct_answer' => 'class', 'points' => 5],
                ['question' => 'What is the default return value of a function that does not explicitly return anything?', 'correct_answer' => 'None', 'points' => 5],
                ['question' => 'Which built-in function returns the length of a list?', 'correct_answer' => 'len', 'points' => 5],
                ['question' => 'What keyword is used to handle exceptions in Python?', 'correct_answer' => 'try', 'points' => 5],
                ['question' => 'What data type is used to store key-value pairs in Python?', 'correct_answer' => 'dictionary', 'points' => 5],
                ['question' => 'What keyword is used to import a module in Python?', 'correct_answer' => 'import', 'points' => 5],
            ],

            // SQL Database Proficiency Test
            'SQL Database Proficiency Test' => [
                ['question' => 'What SQL keyword is used to retrieve data from a database?', 'correct_answer' => 'SELECT', 'points' => 4],
                ['question' => 'What clause is used to filter records in SQL?', 'correct_answer' => 'WHERE', 'points' => 4],
                ['question' => 'What SQL keyword is used to sort the result set?', 'correct_answer' => 'ORDER BY', 'points' => 4],
                ['question' => 'What type of JOIN returns all records from the left table?', 'correct_answer' => 'LEFT JOIN', 'points' => 4],
                ['question' => 'What SQL function returns the number of rows?', 'correct_answer' => 'COUNT', 'points' => 4],
                ['question' => 'What keyword is used to group rows that have the same values?', 'correct_answer' => 'GROUP BY', 'points' => 4],
                ['question' => 'What constraint ensures that a column cannot have NULL values?', 'correct_answer' => 'NOT NULL', 'points' => 4],
                ['question' => 'What SQL statement is used to insert new data into a database?', 'correct_answer' => 'INSERT', 'points' => 4],
                ['question' => 'What SQL statement is used to modify existing records?', 'correct_answer' => 'UPDATE', 'points' => 4],
                ['question' => 'What SQL statement is used to delete records from a table?', 'correct_answer' => 'DELETE', 'points' => 4],
            ],

            // Web Development Assessment
            'Web Development Assessment' => [
                ['question' => 'What does HTML stand for?', 'correct_answer' => 'HyperText Markup Language', 'points' => 5],
                ['question' => 'What does CSS stand for?', 'correct_answer' => 'Cascading Style Sheets', 'points' => 5],
                ['question' => 'What HTML tag is used to create a hyperlink?', 'correct_answer' => 'a', 'points' => 5],
                ['question' => 'What CSS property is used to change the text color?', 'correct_answer' => 'color', 'points' => 5],
                ['question' => 'What JavaScript keyword is used to declare a variable?', 'correct_answer' => 'let', 'points' => 5],
                ['question' => 'What HTTP method is used to send data to a server?', 'correct_answer' => 'POST', 'points' => 5],
                ['question' => 'What does DOM stand for?', 'correct_answer' => 'Document Object Model', 'points' => 5],
                ['question' => 'What HTML tag is used to define an unordered list?', 'correct_answer' => 'ul', 'points' => 5],
                ['question' => 'What CSS property is used to add space inside an element?', 'correct_answer' => 'padding', 'points' => 5],
                ['question' => 'What JavaScript method is used to select an element by its ID?', 'correct_answer' => 'getElementById', 'points' => 5],
            ],

            // Leadership Styles Assessment
            'Leadership Styles Assessment' => [
                ['question' => 'What leadership style involves making decisions without consulting team members?', 'correct_answer' => 'Autocratic', 'points' => 5],
                ['question' => 'What leadership style focuses on empowering and developing team members?', 'correct_answer' => 'Servant', 'points' => 5],
                ['question' => 'What leadership style involves sharing decision-making with the team?', 'correct_answer' => 'Democratic', 'points' => 5],
                ['question' => 'What leadership style focuses on inspiring and motivating through vision?', 'correct_answer' => 'Transformational', 'points' => 5],
                ['question' => 'What leadership style adapts based on the situation and team needs?', 'correct_answer' => 'Situational', 'points' => 5],
                ['question' => 'What leadership style uses rewards and punishments to motivate?', 'correct_answer' => 'Transactional', 'points' => 5],
                ['question' => 'What leadership style gives team members complete freedom to make decisions?', 'correct_answer' => 'Laissez-faire', 'points' => 5],
                ['question' => 'What quality is essential for building trust as a leader?', 'correct_answer' => 'Integrity', 'points' => 5],
                ['question' => 'What is the process of assigning tasks to team members called?', 'correct_answer' => 'Delegation', 'points' => 5],
                ['question' => 'What leadership concept involves leading by demonstrating desired behaviors?', 'correct_answer' => 'Leading by example', 'points' => 5],
            ],

            // Team Management Skills Quiz
            'Team Management Skills Quiz' => [
                ['question' => 'What is the process of resolving disagreements between team members called?', 'correct_answer' => 'Conflict resolution', 'points' => 5],
                ['question' => 'What term describes the collective spirit and morale of a team?', 'correct_answer' => 'Team morale', 'points' => 5],
                ['question' => 'What is the first stage of team development according to Tuckman?', 'correct_answer' => 'Forming', 'points' => 5],
                ['question' => 'What is the process of providing constructive criticism to team members?', 'correct_answer' => 'Feedback', 'points' => 5],
                ['question' => 'What term describes setting clear expectations for team performance?', 'correct_answer' => 'Goal setting', 'points' => 5],
                ['question' => 'What management style involves closely supervising employees?', 'correct_answer' => 'Micromanagement', 'points' => 5],
                ['question' => 'What is the process of helping team members develop their skills?', 'correct_answer' => 'Coaching', 'points' => 5],
                ['question' => 'What type of meeting focuses on daily progress updates?', 'correct_answer' => 'Stand-up', 'points' => 5],
                ['question' => 'What is the term for a team member who excels in their work and helps others?', 'correct_answer' => 'Role model', 'points' => 5],
                ['question' => 'What document outlines team roles, responsibilities, and expectations?', 'correct_answer' => 'Team charter', 'points' => 5],
            ],

            // Strategic Decision Making Test
            'Strategic Decision Making Test' => [
                ['question' => 'What analysis examines Strengths, Weaknesses, Opportunities, and Threats?', 'correct_answer' => 'SWOT', 'points' => 5],
                ['question' => 'What is the process of choosing between multiple alternatives called?', 'correct_answer' => 'Decision making', 'points' => 5],
                ['question' => 'What term describes the potential negative outcome of a decision?', 'correct_answer' => 'Risk', 'points' => 5],
                ['question' => 'What analysis evaluates the costs versus benefits of a decision?', 'correct_answer' => 'Cost-benefit analysis', 'points' => 5],
                ['question' => 'What is the tendency to favor information that confirms existing beliefs?', 'correct_answer' => 'Confirmation bias', 'points' => 5],
                ['question' => 'What term describes involving multiple stakeholders in decision making?', 'correct_answer' => 'Consensus', 'points' => 5],
                ['question' => 'What is the expected value of an uncertain outcome called?', 'correct_answer' => 'Expected value', 'points' => 5],
                ['question' => 'What decision-making approach relies on data and evidence?', 'correct_answer' => 'Data-driven', 'points' => 5],
                ['question' => 'What is the cost of the next best alternative given up called?', 'correct_answer' => 'Opportunity cost', 'points' => 5],
                ['question' => 'What type of thinking involves breaking down complex problems into parts?', 'correct_answer' => 'Analytical', 'points' => 5],
            ],

            // Business Writing Skills Test
            'Business Writing Skills Test' => [
                ['question' => 'What is the opening greeting in a business email called?', 'correct_answer' => 'Salutation', 'points' => 5],
                ['question' => 'What writing quality means being brief and to the point?', 'correct_answer' => 'Concise', 'points' => 5],
                ['question' => 'What tone is appropriate for professional business communication?', 'correct_answer' => 'Formal', 'points' => 5],
                ['question' => 'What part of a business letter contains your contact information?', 'correct_answer' => 'Letterhead', 'points' => 5],
                ['question' => 'What is the closing phrase before your signature in an email?', 'correct_answer' => 'Regards', 'points' => 5],
                ['question' => 'What writing error involves switching between verb tenses incorrectly?', 'correct_answer' => 'Tense inconsistency', 'points' => 5],
                ['question' => 'What is a brief summary at the beginning of a report called?', 'correct_answer' => 'Executive summary', 'points' => 5],
                ['question' => 'What type of writing aims to convince the reader?', 'correct_answer' => 'Persuasive', 'points' => 5],
                ['question' => 'What is the process of reviewing and correcting written work?', 'correct_answer' => 'Proofreading', 'points' => 5],
                ['question' => 'What writing principle means using simple words over complex ones?', 'correct_answer' => 'Clarity', 'points' => 5],
            ],

            // Presentation Skills Assessment
            'Presentation Skills Assessment' => [
                ['question' => 'What is the opening part of a presentation that captures attention called?', 'correct_answer' => 'Hook', 'points' => 5],
                ['question' => 'What visual aid software is commonly used for presentations?', 'correct_answer' => 'PowerPoint', 'points' => 5],
                ['question' => 'What is the fear of public speaking called?', 'correct_answer' => 'Glossophobia', 'points' => 5],
                ['question' => 'What refers to hand movements and facial expressions during a presentation?', 'correct_answer' => 'Body language', 'points' => 5],
                ['question' => 'What is a brief rehearsal of a presentation called?', 'correct_answer' => 'Dry run', 'points' => 5],
                ['question' => 'What section at the end allows the audience to ask questions?', 'correct_answer' => 'Q&A', 'points' => 5],
                ['question' => 'What is the main point or argument of your presentation called?', 'correct_answer' => 'Key message', 'points' => 5],
                ['question' => 'What technique involves telling a story to engage the audience?', 'correct_answer' => 'Storytelling', 'points' => 5],
                ['question' => 'What visual element uses images to represent data?', 'correct_answer' => 'Infographic', 'points' => 5],
                ['question' => 'What is the act of looking at different audience members called?', 'correct_answer' => 'Eye contact', 'points' => 5],
            ],

            // Active Listening Quiz
            'Active Listening Quiz' => [
                ['question' => 'What is repeating back what someone said in your own words called?', 'correct_answer' => 'Paraphrasing', 'points' => 5],
                ['question' => 'What type of questions cannot be answered with yes or no?', 'correct_answer' => 'Open-ended', 'points' => 5],
                ['question' => 'What non-verbal cue shows you are paying attention?', 'correct_answer' => 'Nodding', 'points' => 5],
                ['question' => 'What barrier to listening involves preparing your response while others speak?', 'correct_answer' => 'Rehearsing', 'points' => 5],
                ['question' => 'What is the ability to understand and share the feelings of others?', 'correct_answer' => 'Empathy', 'points' => 5],
                ['question' => 'What listening technique involves summarizing the main points?', 'correct_answer' => 'Summarizing', 'points' => 5],
                ['question' => 'What term describes giving your full attention to the speaker?', 'correct_answer' => 'Focus', 'points' => 5],
                ['question' => 'What is the opposite of active listening?', 'correct_answer' => 'Passive listening', 'points' => 5],
                ['question' => 'What response shows understanding without interrupting?', 'correct_answer' => 'Acknowledgment', 'points' => 5],
                ['question' => 'What is asking for more details about what was said called?', 'correct_answer' => 'Clarifying', 'points' => 5],
            ],

            // Company Policy Compliance Test
            'Company Policy Compliance Test' => [
                ['question' => 'What document outlines expected employee behavior standards?', 'correct_answer' => 'Code of conduct', 'points' => 4],
                ['question' => 'What term describes following rules and regulations?', 'correct_answer' => 'Compliance', 'points' => 4],
                ['question' => 'What is the process of reporting policy violations called?', 'correct_answer' => 'Whistleblowing', 'points' => 4],
                ['question' => 'What type of policy addresses computer and internet use?', 'correct_answer' => 'Acceptable use policy', 'points' => 4],
                ['question' => 'What document must employees sign acknowledging policy receipt?', 'correct_answer' => 'Acknowledgment form', 'points' => 4],
                ['question' => 'What is a situation where personal interests conflict with work duties?', 'correct_answer' => 'Conflict of interest', 'points' => 4],
                ['question' => 'What department typically handles policy violations?', 'correct_answer' => 'Human Resources', 'points' => 4],
                ['question' => 'What is the consequence for serious policy violations?', 'correct_answer' => 'Termination', 'points' => 4],
                ['question' => 'What policy protects sensitive company information?', 'correct_answer' => 'Confidentiality policy', 'points' => 4],
                ['question' => 'What is the process of reviewing policies regularly called?', 'correct_answer' => 'Policy review', 'points' => 4],
            ],

            // Data Privacy & GDPR Quiz
            'Data Privacy & GDPR Quiz' => [
                ['question' => 'What does GDPR stand for?', 'correct_answer' => 'General Data Protection Regulation', 'points' => 5],
                ['question' => 'What right allows individuals to request deletion of their data?', 'correct_answer' => 'Right to erasure', 'points' => 5],
                ['question' => 'What is obtaining permission before collecting personal data called?', 'correct_answer' => 'Consent', 'points' => 5],
                ['question' => 'What type of data includes health information and biometric data?', 'correct_answer' => 'Sensitive data', 'points' => 5],
                ['question' => 'What is a security incident involving unauthorized data access called?', 'correct_answer' => 'Data breach', 'points' => 5],
                ['question' => 'What role is responsible for ensuring GDPR compliance in an organization?', 'correct_answer' => 'Data Protection Officer', 'points' => 5],
                ['question' => 'What is the principle of collecting only necessary data called?', 'correct_answer' => 'Data minimization', 'points' => 5],
                ['question' => 'What right allows individuals to obtain a copy of their data?', 'correct_answer' => 'Right of access', 'points' => 5],
                ['question' => 'What is the maximum GDPR fine as a percentage of annual turnover?', 'correct_answer' => '4%', 'points' => 5],
                ['question' => 'What document explains how an organization uses personal data?', 'correct_answer' => 'Privacy policy', 'points' => 5],
            ],

            // Anti-Harassment Training Assessment
            'Anti-Harassment Training Assessment' => [
                ['question' => 'What is unwelcome conduct based on protected characteristics called?', 'correct_answer' => 'Harassment', 'points' => 5],
                ['question' => 'What type of harassment involves requests for sexual favors?', 'correct_answer' => 'Quid pro quo', 'points' => 5],
                ['question' => 'What term describes a work environment that is intimidating or hostile?', 'correct_answer' => 'Hostile work environment', 'points' => 5],
                ['question' => 'What should you do first if you witness harassment?', 'correct_answer' => 'Report it', 'points' => 5],
                ['question' => 'What policy protects employees who report harassment from retaliation?', 'correct_answer' => 'Anti-retaliation policy', 'points' => 5],
                ['question' => 'What department should harassment complaints be reported to?', 'correct_answer' => 'Human Resources', 'points' => 5],
                ['question' => 'What is treating someone unfairly based on their characteristics called?', 'correct_answer' => 'Discrimination', 'points' => 5],
                ['question' => 'What type of harassment occurs online or through digital communication?', 'correct_answer' => 'Cyberbullying', 'points' => 5],
                ['question' => 'What is the responsibility of managers when they learn of harassment?', 'correct_answer' => 'Investigate', 'points' => 5],
                ['question' => 'What training helps prevent harassment in the workplace?', 'correct_answer' => 'Sensitivity training', 'points' => 5],
            ],

            // Customer Service Fundamentals
            'Customer Service Fundamentals' => [
                ['question' => 'What is the first thing you should do when greeting a customer?', 'correct_answer' => 'Smile', 'points' => 5],
                ['question' => 'What quality means putting yourself in the customer\'s shoes?', 'correct_answer' => 'Empathy', 'points' => 5],
                ['question' => 'What is the process of solving customer problems called?', 'correct_answer' => 'Troubleshooting', 'points' => 5],
                ['question' => 'What metric measures customer satisfaction with a single question?', 'correct_answer' => 'NPS', 'points' => 5],
                ['question' => 'What type of customer is unhappy but doesn\'t complain?', 'correct_answer' => 'Silent customer', 'points' => 5],
                ['question' => 'What is following up after resolving an issue called?', 'correct_answer' => 'Follow-up', 'points' => 5],
                ['question' => 'What skill involves staying calm under pressure?', 'correct_answer' => 'Patience', 'points' => 5],
                ['question' => 'What is going beyond customer expectations called?', 'correct_answer' => 'Exceeding expectations', 'points' => 5],
                ['question' => 'What should you do when you don\'t know the answer?', 'correct_answer' => 'Escalate', 'points' => 5],
                ['question' => 'What type of language should be avoided with customers?', 'correct_answer' => 'Jargon', 'points' => 5],
            ],

            // Handling Difficult Customers Quiz
            'Handling Difficult Customers Quiz' => [
                ['question' => 'What is the first step when dealing with an angry customer?', 'correct_answer' => 'Listen', 'points' => 5],
                ['question' => 'What technique involves acknowledging the customer\'s feelings?', 'correct_answer' => 'Validation', 'points' => 5],
                ['question' => 'What should you avoid doing when a customer is upset?', 'correct_answer' => 'Arguing', 'points' => 5],
                ['question' => 'What is the process of calming down an upset customer?', 'correct_answer' => 'De-escalation', 'points' => 5],
                ['question' => 'What phrase shows you take responsibility for the issue?', 'correct_answer' => 'I apologize', 'points' => 5],
                ['question' => 'What should you do if a customer becomes abusive?', 'correct_answer' => 'End the call', 'points' => 5],
                ['question' => 'What is offering alternatives to solve a problem called?', 'correct_answer' => 'Providing options', 'points' => 5],
                ['question' => 'What tone of voice is best when handling complaints?', 'correct_answer' => 'Calm', 'points' => 5],
                ['question' => 'What is documenting the interaction for future reference called?', 'correct_answer' => 'Logging', 'points' => 5],
                ['question' => 'What is the goal of handling a difficult customer?', 'correct_answer' => 'Resolution', 'points' => 5],
            ],

            // Customer Experience Strategy Assessment
            'Customer Experience Strategy Assessment' => [
                ['question' => 'What is a visual representation of the customer\'s journey called?', 'correct_answer' => 'Journey map', 'points' => 5],
                ['question' => 'What does CX stand for?', 'correct_answer' => 'Customer Experience', 'points' => 5],
                ['question' => 'What metric measures likelihood to recommend?', 'correct_answer' => 'Net Promoter Score', 'points' => 5],
                ['question' => 'What is every interaction a customer has with a company called?', 'correct_answer' => 'Touchpoint', 'points' => 5],
                ['question' => 'What strategy focuses on keeping existing customers?', 'correct_answer' => 'Customer retention', 'points' => 5],
                ['question' => 'What is listening to customer feedback across channels called?', 'correct_answer' => 'Voice of customer', 'points' => 5],
                ['question' => 'What type of experience is consistent across all channels?', 'correct_answer' => 'Omnichannel', 'points' => 5],
                ['question' => 'What is tailoring experiences to individual customers called?', 'correct_answer' => 'Personalization', 'points' => 5],
                ['question' => 'What is the emotional connection customers have with a brand?', 'correct_answer' => 'Brand loyalty', 'points' => 5],
                ['question' => 'What design approach puts customers at the center?', 'correct_answer' => 'Customer-centric', 'points' => 5],
            ],

            // Data Analysis Fundamentals Quiz
            'Data Analysis Fundamentals Quiz' => [
                ['question' => 'What is the process of removing errors from data called?', 'correct_answer' => 'Data cleaning', 'points' => 5],
                ['question' => 'What type of chart shows proportions of a whole?', 'correct_answer' => 'Pie chart', 'points' => 5],
                ['question' => 'What statistical measure represents the middle value?', 'correct_answer' => 'Median', 'points' => 5],
                ['question' => 'What is the average of a set of numbers called?', 'correct_answer' => 'Mean', 'points' => 5],
                ['question' => 'What type of analysis looks at past data to understand trends?', 'correct_answer' => 'Descriptive', 'points' => 5],
                ['question' => 'What is a value that is significantly different from other data points?', 'correct_answer' => 'Outlier', 'points' => 5],
                ['question' => 'What chart type is best for showing trends over time?', 'correct_answer' => 'Line chart', 'points' => 5],
                ['question' => 'What is the relationship between two variables called?', 'correct_answer' => 'Correlation', 'points' => 5],
                ['question' => 'What is organizing data into categories called?', 'correct_answer' => 'Classification', 'points' => 5],
                ['question' => 'What type of data has numerical values?', 'correct_answer' => 'Quantitative', 'points' => 5],
            ],

            // Excel & Spreadsheet Skills Test
            'Excel & Spreadsheet Skills Test' => [
                ['question' => 'What Excel function calculates the sum of a range?', 'correct_answer' => 'SUM', 'points' => 4],
                ['question' => 'What Excel function looks up a value in a table?', 'correct_answer' => 'VLOOKUP', 'points' => 4],
                ['question' => 'What feature allows you to summarize large amounts of data?', 'correct_answer' => 'Pivot table', 'points' => 4],
                ['question' => 'What Excel function counts cells that meet a condition?', 'correct_answer' => 'COUNTIF', 'points' => 4],
                ['question' => 'What is the keyboard shortcut to copy in Excel?', 'correct_answer' => 'Ctrl+C', 'points' => 4],
                ['question' => 'What Excel function returns the current date?', 'correct_answer' => 'TODAY', 'points' => 4],
                ['question' => 'What is a cell reference that doesn\'t change when copied called?', 'correct_answer' => 'Absolute reference', 'points' => 4],
                ['question' => 'What Excel function combines text from multiple cells?', 'correct_answer' => 'CONCATENATE', 'points' => 4],
                ['question' => 'What feature automatically fills cells with a pattern?', 'correct_answer' => 'AutoFill', 'points' => 4],
                ['question' => 'What Excel function returns the average of a range?', 'correct_answer' => 'AVERAGE', 'points' => 4],
            ],

            // Statistical Analysis Assessment
            'Statistical Analysis Assessment' => [
                ['question' => 'What is a testable prediction about the relationship between variables?', 'correct_answer' => 'Hypothesis', 'points' => 5],
                ['question' => 'What is the probability of making a Type I error called?', 'correct_answer' => 'Alpha', 'points' => 5],
                ['question' => 'What statistical test compares means of two groups?', 'correct_answer' => 't-test', 'points' => 5],
                ['question' => 'What measure shows how spread out data is from the mean?', 'correct_answer' => 'Standard deviation', 'points' => 5],
                ['question' => 'What is a smaller group selected from a population called?', 'correct_answer' => 'Sample', 'points' => 5],
                ['question' => 'What type of error occurs when you reject a true null hypothesis?', 'correct_answer' => 'Type I error', 'points' => 5],
                ['question' => 'What statistical measure ranges from -1 to 1 for correlation?', 'correct_answer' => 'Correlation coefficient', 'points' => 5],
                ['question' => 'What is the hypothesis that there is no effect called?', 'correct_answer' => 'Null hypothesis', 'points' => 5],
                ['question' => 'What value indicates statistical significance if below 0.05?', 'correct_answer' => 'p-value', 'points' => 5],
                ['question' => 'What type of distribution is bell-shaped?', 'correct_answer' => 'Normal distribution', 'points' => 5],
            ],

            // Project Management Basics Quiz
            'Project Management Basics Quiz' => [
                ['question' => 'What document defines the project scope, objectives, and stakeholders?', 'correct_answer' => 'Project charter', 'points' => 5],
                ['question' => 'What chart shows tasks and their durations in a timeline?', 'correct_answer' => 'Gantt chart', 'points' => 5],
                ['question' => 'What is the longest path through a project network called?', 'correct_answer' => 'Critical path', 'points' => 5],
                ['question' => 'What is a hierarchical breakdown of project work called?', 'correct_answer' => 'WBS', 'points' => 5],
                ['question' => 'What project constraint includes scope, time, and cost?', 'correct_answer' => 'Triple constraint', 'points' => 5],
                ['question' => 'What is a significant event or achievement in a project?', 'correct_answer' => 'Milestone', 'points' => 5],
                ['question' => 'What type of dependency means one task must finish before another starts?', 'correct_answer' => 'Finish-to-start', 'points' => 5],
                ['question' => 'What is the process of identifying potential problems in a project?', 'correct_answer' => 'Risk identification', 'points' => 5],
                ['question' => 'What document tracks changes to project scope?', 'correct_answer' => 'Change log', 'points' => 5],
                ['question' => 'What is a formal request to change project scope called?', 'correct_answer' => 'Change request', 'points' => 5],
            ],

            // Agile & Scrum Certification Prep
            'Agile & Scrum Certification Prep' => [
                ['question' => 'What is a time-boxed iteration in Scrum called?', 'correct_answer' => 'Sprint', 'points' => 5],
                ['question' => 'Who is responsible for maximizing the value of the product?', 'correct_answer' => 'Product Owner', 'points' => 5],
                ['question' => 'What is the prioritized list of work in Scrum called?', 'correct_answer' => 'Product backlog', 'points' => 5],
                ['question' => 'What daily meeting does the Scrum team hold?', 'correct_answer' => 'Daily standup', 'points' => 5],
                ['question' => 'Who facilitates Scrum events and removes impediments?', 'correct_answer' => 'Scrum Master', 'points' => 5],
                ['question' => 'What meeting is held at the end of a sprint to demonstrate work?', 'correct_answer' => 'Sprint review', 'points' => 5],
                ['question' => 'What meeting focuses on improving team processes?', 'correct_answer' => 'Retrospective', 'points' => 5],
                ['question' => 'What is a short description of a feature from the user\'s perspective?', 'correct_answer' => 'User story', 'points' => 5],
                ['question' => 'What unit is used to estimate the effort for user stories?', 'correct_answer' => 'Story points', 'points' => 5],
                ['question' => 'What Agile principle values working software over comprehensive what?', 'correct_answer' => 'Documentation', 'points' => 5],
            ],

            // Risk Management Assessment
            'Risk Management Assessment' => [
                ['question' => 'What is a potential event that could negatively impact a project?', 'correct_answer' => 'Risk', 'points' => 5],
                ['question' => 'What is the process of reducing the probability or impact of a risk?', 'correct_answer' => 'Mitigation', 'points' => 5],
                ['question' => 'What risk response involves accepting the consequences?', 'correct_answer' => 'Acceptance', 'points' => 5],
                ['question' => 'What matrix plots probability versus impact of risks?', 'correct_answer' => 'Risk matrix', 'points' => 5],
                ['question' => 'What is a risk that could have a positive impact called?', 'correct_answer' => 'Opportunity', 'points' => 5],
                ['question' => 'What document lists all identified risks and their responses?', 'correct_answer' => 'Risk register', 'points' => 5],
                ['question' => 'What risk response involves shifting risk to a third party?', 'correct_answer' => 'Transfer', 'points' => 5],
                ['question' => 'What is a risk that remains after implementing responses?', 'correct_answer' => 'Residual risk', 'points' => 5],
                ['question' => 'What is a new risk that arises from a risk response?', 'correct_answer' => 'Secondary risk', 'points' => 5],
                ['question' => 'What risk response eliminates the threat entirely?', 'correct_answer' => 'Avoidance', 'points' => 5],
            ],

            // Workplace Safety Orientation Quiz
            'Workplace Safety Orientation Quiz' => [
                ['question' => 'What does PPE stand for?', 'correct_answer' => 'Personal Protective Equipment', 'points' => 5],
                ['question' => 'What is the designated meeting point after an evacuation called?', 'correct_answer' => 'Assembly point', 'points' => 5],
                ['question' => 'What color is typically used for fire extinguishers?', 'correct_answer' => 'Red', 'points' => 5],
                ['question' => 'What document lists all hazards of a chemical?', 'correct_answer' => 'Safety Data Sheet', 'points' => 5],
                ['question' => 'What is the first step if you discover a fire?', 'correct_answer' => 'Raise the alarm', 'points' => 5],
                ['question' => 'What type of hazard involves electricity?', 'correct_answer' => 'Electrical hazard', 'points' => 5],
                ['question' => 'What is the process of identifying workplace hazards called?', 'correct_answer' => 'Risk assessment', 'points' => 5],
                ['question' => 'What sign shape indicates a warning?', 'correct_answer' => 'Triangle', 'points' => 5],
                ['question' => 'What should you do if you see an unsafe condition?', 'correct_answer' => 'Report it', 'points' => 5],
                ['question' => 'What type of injury is caused by repetitive motions?', 'correct_answer' => 'RSI', 'points' => 5],
            ],

            // Fire Safety & Emergency Response
            'Fire Safety & Emergency Response' => [
                ['question' => 'What word helps remember how to use a fire extinguisher?', 'correct_answer' => 'PASS', 'points' => 5],
                ['question' => 'What type of fire extinguisher is used for electrical fires?', 'correct_answer' => 'CO2', 'points' => 5],
                ['question' => 'What should you do if your clothing catches fire?', 'correct_answer' => 'Stop drop roll', 'points' => 5],
                ['question' => 'What is the maximum number of people in your evacuation route?', 'correct_answer' => 'Capacity', 'points' => 5],
                ['question' => 'What color is an emergency exit sign?', 'correct_answer' => 'Green', 'points' => 5],
                ['question' => 'What should you avoid using during a fire evacuation?', 'correct_answer' => 'Elevator', 'points' => 5],
                ['question' => 'What is the emergency phone number in most countries?', 'correct_answer' => '911', 'points' => 5],
                ['question' => 'What should you do if there is smoke in the air?', 'correct_answer' => 'Stay low', 'points' => 5],
                ['question' => 'What person ensures everyone has evacuated an area?', 'correct_answer' => 'Fire warden', 'points' => 5],
                ['question' => 'What is regular practice of emergency procedures called?', 'correct_answer' => 'Fire drill', 'points' => 5],
            ],

            // Ergonomics & Workplace Wellness
            'Ergonomics & Workplace Wellness' => [
                ['question' => 'What is the study of people\'s efficiency in their working environment?', 'correct_answer' => 'Ergonomics', 'points' => 5],
                ['question' => 'What angle should your elbows be at when typing?', 'correct_answer' => '90 degrees', 'points' => 5],
                ['question' => 'What is the recommended distance between eyes and monitor?', 'correct_answer' => 'Arms length', 'points' => 5],
                ['question' => 'What should the top of your monitor be level with?', 'correct_answer' => 'Eye level', 'points' => 5],
                ['question' => 'What rule helps prevent eye strain from screens?', 'correct_answer' => '20-20-20 rule', 'points' => 5],
                ['question' => 'What type of chair supports proper posture?', 'correct_answer' => 'Ergonomic chair', 'points' => 5],
                ['question' => 'What should your feet be doing when sitting properly?', 'correct_answer' => 'Flat on floor', 'points' => 5],
                ['question' => 'What activity helps prevent stiffness from sitting?', 'correct_answer' => 'Stretching', 'points' => 5],
                ['question' => 'What type of lighting reduces eye strain?', 'correct_answer' => 'Natural light', 'points' => 5],
                ['question' => 'How often should you take breaks from your screen?', 'correct_answer' => 'Every hour', 'points' => 5],
            ],

            // Core Products Overview Quiz
            'Core Products Overview Quiz' => [
                ['question' => 'What makes our product different from competitors?', 'correct_answer' => 'Unique value proposition', 'points' => 4],
                ['question' => 'What is the primary benefit our customers receive?', 'correct_answer' => 'Value', 'points' => 4],
                ['question' => 'What group of customers is our product designed for?', 'correct_answer' => 'Target market', 'points' => 4],
                ['question' => 'What is a key capability of our main product?', 'correct_answer' => 'Core feature', 'points' => 4],
                ['question' => 'What pricing model does our product use?', 'correct_answer' => 'Subscription', 'points' => 4],
                ['question' => 'What is our product\'s main competitive advantage?', 'correct_answer' => 'Differentiation', 'points' => 4],
                ['question' => 'What level of support do premium customers receive?', 'correct_answer' => 'Priority support', 'points' => 4],
                ['question' => 'What is the process of improving products based on feedback?', 'correct_answer' => 'Iteration', 'points' => 4],
                ['question' => 'What document describes product features for customers?', 'correct_answer' => 'Product brochure', 'points' => 4],
                ['question' => 'What is the path customers take to purchase called?', 'correct_answer' => 'Sales funnel', 'points' => 4],
            ],

            // Competitive Analysis Assessment
            'Competitive Analysis Assessment' => [
                ['question' => 'What is the process of evaluating competitors called?', 'correct_answer' => 'Competitive analysis', 'points' => 5],
                ['question' => 'What are companies offering similar products called?', 'correct_answer' => 'Competitors', 'points' => 5],
                ['question' => 'What is the percentage of market a company holds?', 'correct_answer' => 'Market share', 'points' => 5],
                ['question' => 'What analysis examines industry competitive forces?', 'correct_answer' => 'Porter\'s Five Forces', 'points' => 5],
                ['question' => 'What is a feature that competitors cannot easily copy?', 'correct_answer' => 'Competitive advantage', 'points' => 5],
                ['question' => 'What is setting prices based on competitor prices called?', 'correct_answer' => 'Competitive pricing', 'points' => 5],
                ['question' => 'What is a comparison of products side by side called?', 'correct_answer' => 'Feature comparison', 'points' => 5],
                ['question' => 'What is the threat of new companies entering the market?', 'correct_answer' => 'New entrants', 'points' => 5],
                ['question' => 'What strategy focuses on being the lowest-cost producer?', 'correct_answer' => 'Cost leadership', 'points' => 5],
                ['question' => 'What is a company that could potentially become a competitor?', 'correct_answer' => 'Potential competitor', 'points' => 5],
            ],

            // New Product Launch Certification
            'New Product Launch Certification' => [
                ['question' => 'What is the process of introducing a new product to market?', 'correct_answer' => 'Product launch', 'points' => 5],
                ['question' => 'What is testing a product with a small group before launch?', 'correct_answer' => 'Beta testing', 'points' => 5],
                ['question' => 'What document outlines the launch strategy and timeline?', 'correct_answer' => 'Launch plan', 'points' => 5],
                ['question' => 'What is the target date for product availability?', 'correct_answer' => 'Launch date', 'points' => 5],
                ['question' => 'What training prepares sales teams for a new product?', 'correct_answer' => 'Sales enablement', 'points' => 5],
                ['question' => 'What is marketing content that explains the product called?', 'correct_answer' => 'Product collateral', 'points' => 5],
                ['question' => 'What is the initial group of customers for a new product?', 'correct_answer' => 'Early adopters', 'points' => 5],
                ['question' => 'What metric measures interest before launch?', 'correct_answer' => 'Pre-orders', 'points' => 5],
                ['question' => 'What is a soft introduction before full launch called?', 'correct_answer' => 'Soft launch', 'points' => 5],
                ['question' => 'What feedback is gathered after launch to improve?', 'correct_answer' => 'Post-launch feedback', 'points' => 5],
            ],

            // Company Culture & Values Quiz
            'Company Culture & Values Quiz' => [
                ['question' => 'What is the company\'s reason for existing called?', 'correct_answer' => 'Mission', 'points' => 5],
                ['question' => 'What describes where the company wants to be in the future?', 'correct_answer' => 'Vision', 'points' => 5],
                ['question' => 'What are the guiding principles of the organization called?', 'correct_answer' => 'Core values', 'points' => 5],
                ['question' => 'What describes the shared beliefs and behaviors in an organization?', 'correct_answer' => 'Culture', 'points' => 5],
                ['question' => 'What is treating all employees fairly and equally called?', 'correct_answer' => 'Equity', 'points' => 5],
                ['question' => 'What value involves being honest and transparent?', 'correct_answer' => 'Integrity', 'points' => 5],
                ['question' => 'What is including people from diverse backgrounds called?', 'correct_answer' => 'Inclusion', 'points' => 5],
                ['question' => 'What value focuses on achieving results?', 'correct_answer' => 'Excellence', 'points' => 5],
                ['question' => 'What is working together toward common goals called?', 'correct_answer' => 'Collaboration', 'points' => 5],
                ['question' => 'What value involves trying new approaches?', 'correct_answer' => 'Innovation', 'points' => 5],
            ],

            // New Employee Orientation Test
            'New Employee Orientation Test' => [
                ['question' => 'What department handles payroll and benefits?', 'correct_answer' => 'Human Resources', 'points' => 4],
                ['question' => 'What is the period when new employees learn about the company?', 'correct_answer' => 'Onboarding', 'points' => 4],
                ['question' => 'What document outlines your job duties?', 'correct_answer' => 'Job description', 'points' => 4],
                ['question' => 'What is the person who supervises your work called?', 'correct_answer' => 'Manager', 'points' => 4],
                ['question' => 'What system is used to track work hours?', 'correct_answer' => 'Time tracking', 'points' => 4],
                ['question' => 'What is the process of requesting time off called?', 'correct_answer' => 'Leave request', 'points' => 4],
                ['question' => 'What document contains all company policies?', 'correct_answer' => 'Employee handbook', 'points' => 4],
                ['question' => 'What is regular feedback on performance called?', 'correct_answer' => 'Performance review', 'points' => 4],
                ['question' => 'What channel is used for company announcements?', 'correct_answer' => 'Internal communications', 'points' => 4],
                ['question' => 'What is the experienced employee who guides new hires called?', 'correct_answer' => 'Mentor', 'points' => 4],
            ],

            // Systems & Tools Training Quiz
            'Systems & Tools Training Quiz' => [
                ['question' => 'What is the main communication tool used for instant messaging?', 'correct_answer' => 'Slack', 'points' => 5],
                ['question' => 'What platform is used for video conferencing?', 'correct_answer' => 'Zoom', 'points' => 5],
                ['question' => 'What is the cloud storage platform for documents?', 'correct_answer' => 'Google Drive', 'points' => 5],
                ['question' => 'What tool is used for project management and task tracking?', 'correct_answer' => 'Jira', 'points' => 5],
                ['question' => 'What is the process of logging into company systems?', 'correct_answer' => 'Authentication', 'points' => 5],
                ['question' => 'What security feature requires two verification methods?', 'correct_answer' => 'Two-factor authentication', 'points' => 5],
                ['question' => 'What is the company\'s internal website for employees?', 'correct_answer' => 'Intranet', 'points' => 5],
                ['question' => 'What system manages customer information and interactions?', 'correct_answer' => 'CRM', 'points' => 5],
                ['question' => 'What tool is used for collaborative document editing?', 'correct_answer' => 'Google Docs', 'points' => 5],
                ['question' => 'What is the ticket system for IT support requests?', 'correct_answer' => 'Help desk', 'points' => 5],
            ],
        ];

        foreach ($quizzes as $quiz) {
            if (isset($questionsData[$quiz->quiz_title])) {
                $order = 1;
                foreach ($questionsData[$quiz->quiz_title] as $question) {
                    DB::connection('learning_management')->table('quiz_questions')->insert([
                        'quiz_id' => $quiz->id,
                        'question' => $question['question'],
                        'correct_answer' => $question['correct_answer'],
                        'points' => $question['points'],
                        'question_order' => $order,
                        'question_type' => 'identification',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $order++;
                }
            }
        }
    }
}
