<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modules\training_management\Models\TrainingAssignment;
use App\Modules\training_management\Models\TrainingAssignmentEmployee;
use App\Modules\learning_management\Models\AssessmentAssignment;
use App\Services\EmployeeApiService;

class ESSController extends Controller
{
    protected $employeeApiService;

    public function __construct(EmployeeApiService $employeeApiService)
    {
        $this->employeeApiService = $employeeApiService;
    }

    /**
     * Show the ESS dashboard
     */
    public function dashboard()
    {
        return view('ess.dashboard');
    }

    /**
     * Show the LMS page with user-specific data
     */
    public function lms()
    {
        $user = Auth::user();
        
        // Get user's training assignments
        $trainingAssignments = $this->getUserTrainingAssignments($user);
        
        // Get user's assessment assignments
        $assessmentAssignments = $this->getUserAssessmentAssignments($user);
        
        // Get user's learning statistics
        $learningStats = $this->getUserLearningStats($user, $trainingAssignments, $assessmentAssignments);

        return view('ess.lms', compact('trainingAssignments', 'assessmentAssignments', 'learningStats'));
    }

    /**
     * Get user's training assignments
     */
    private function getUserTrainingAssignments($user)
    {
        try {
            // Get employee data from API
            $employees = $this->employeeApiService->getEmployees();
            $employeeData = collect($employees)->firstWhere('email', $user->email);
            
            if (!$employeeData) {
                \Log::info("No employee data found for email: " . $user->email);
                return collect([]);
            }

            \Log::info("Employee data found", $employeeData);

            // Get training assignments for this employee using API id (not employee_id)
            $assignments = DB::connection('training_management')
                ->table('training_assignment_employees as tae')
                ->join('training_assignments as ta', 'tae.training_assignment_id', '=', 'ta.id')
                ->join('training_catalogs as tc', 'ta.training_catalog_id', '=', 'tc.id')
                ->where('tae.employee_id', $employeeData['id']) // Use API 'id' not 'employee_id'
                ->select([
                    'ta.id as assignment_id',
                    'ta.assignment_title',
                    'ta.priority',
                    'ta.due_date',
                    'ta.status as assignment_status',
                    'tc.title as course_title', // Correct field name
                    'tc.description as description', // Correct field name
                    'tc.label as category', // Using label as category
                    'tae.status as completion_status', // Correct field name
                    'tae.progress_percentage',
                    'tae.completed_at',
                    'tae.started_at'
                ])
                ->get();

            \Log::info("Training assignments found: " . $assignments->count());
            \Log::info("Training assignments data", $assignments->toArray());

            return $assignments->map(function ($assignment) {
                return [
                    'id' => $assignment->assignment_id,
                    'title' => $assignment->course_title,
                    'description' => $assignment->description,
                    'category' => $assignment->category,
                    'duration' => 'Self-paced', // Since no duration field available
                    'progress' => $assignment->progress_percentage ?? 0,
                    'status' => $this->mapTrainingStatus($assignment->completion_status, $assignment->assignment_status),
                    'due_date' => $assignment->due_date,
                    'priority' => $assignment->priority,
                    'started_at' => $assignment->started_at,
                    'completed_at' => $assignment->completed_at,
                ];
            });

        } catch (\Exception $e) {
            \Log::error('Error fetching user training assignments: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Get user's assessment assignments
     */
    private function getUserAssessmentAssignments($user)
    {
        try {
            // Get employee data from API
            $employees = $this->employeeApiService->getEmployees();
            $employeeData = collect($employees)->firstWhere('email', $user->email);
            
            if (!$employeeData) {
                \Log::info("No employee data found for email: " . $user->email);
                return collect([]);
            }

            // Get assessment assignments for this employee using API id as string
            $assignments = DB::connection('learning_management')
                ->table('assessment_assignments as aa')
                ->join('assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
                ->join('quizzes as q', 'aa.quiz_id', '=', 'q.id')
                ->where('aa.employee_id', (string)$employeeData['id']) // Use API 'id' as string
                ->select([
                    'aa.id',
                    'aa.due_date',
                    'aa.status',
                    'aa.attempts_used',
                    'aa.max_attempts',
                    'aa.score',
                    'aa.started_at',
                    'aa.completed_at',
                    'aa.duration',
                    'ac.category_name as category_name', // Correct field name
                    'ac.category_slug as category_slug',
                    'q.quiz_title as quiz_title' // Correct field name
                ])
                ->get();

            \Log::info("Assessment assignments found: " . $assignments->count());
            \Log::info("Assessment assignments data", $assignments->toArray());

            return $assignments->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->quiz_title,
                    'description' => 'Assessment for ' . $assignment->category_name, // Generate description
                    'category' => $assignment->category_name,
                    'category_slug' => $assignment->category_slug,
                    'duration' => $assignment->duration ? $assignment->duration . ' minutes' : 'No limit',
                    'status' => $assignment->status,
                    'due_date' => $assignment->due_date,
                    'attempts_used' => $assignment->attempts_used ?? 0,
                    'max_attempts' => $assignment->max_attempts ?? 1,
                    'score' => $assignment->score,
                    'started_at' => $assignment->started_at,
                    'completed_at' => $assignment->completed_at,
                ];
            });

        } catch (\Exception $e) {
            \Log::error('Error fetching user assessment assignments: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Get user's learning statistics
     */
    private function getUserLearningStats($user, $trainingAssignments, $assessmentAssignments)
    {
        $totalCourses = $trainingAssignments->count();
        $completedCourses = $trainingAssignments->where('status', 'completed')->count();
        $inProgressCourses = $trainingAssignments->where('status', 'in_progress')->count();
        
        $totalAssessments = $assessmentAssignments->count();
        $completedAssessments = $assessmentAssignments->where('status', 'completed')->count();
        $pendingAssessments = $assessmentAssignments->where('status', 'assigned')->count();

        return [
            'total_courses' => $totalCourses,
            'completed_courses' => $completedCourses,
            'in_progress_courses' => $inProgressCourses,
            'pending_courses' => $totalCourses - $completedCourses - $inProgressCourses,
            'total_assessments' => $totalAssessments,
            'completed_assessments' => $completedAssessments,
            'pending_assessments' => $pendingAssessments,
            'certificates' => $completedCourses + $completedAssessments, // Simplified calculation
        ];
    }

    /**
     * Map training status to user-friendly status
     */
    private function mapTrainingStatus($completionStatus, $assignmentStatus)
    {
        if ($completionStatus === 'completed') {
            return 'completed';
        } elseif ($completionStatus === 'in_progress') {
            return 'in_progress';
        } elseif ($completionStatus === 'assigned') {
            return 'assigned';
        } else {
            return 'pending';
        }
    }

    /**
     * Show assessment taking page
     */
    public function takeAssessment($id)
    {
        $user = Auth::user();
        
        // Get assessment details
        $assessment = DB::connection('learning_management')
            ->table('assessment_assignments as aa')
            ->join('assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
            ->join('quizzes as q', 'aa.quiz_id', '=', 'q.id')
            ->where('aa.id', $id)
            ->select([
                'aa.id',
                'aa.due_date',
                'aa.status',
                'aa.attempts_used',
                'aa.max_attempts',
                'aa.score',
                'aa.duration',
                'ac.category_name',
                'q.quiz_title',
                'q.id as quiz_id'
            ])
            ->first();

        if (!$assessment) {
            return redirect()->route('ess.lms')->with('error', 'Assessment not found.');
        }

        // Check if user can take this assessment
        if ($assessment->attempts_used >= $assessment->max_attempts && $assessment->status !== 'completed') {
            return redirect()->route('ess.lms')->with('error', 'Maximum attempts reached for this assessment.');
        }

        // Get quiz questions directly from quiz_questions table
        $questions = DB::connection('learning_management')
            ->table('quiz_questions')
            ->where('quiz_id', $assessment->quiz_id)
            ->select([
                'id',
                'question',
                'question_type',
                'points',
                'question_order'
            ])
            ->orderBy('question_order')
            ->get();

        return view('ess.take-assessment', compact('assessment', 'questions'));
    }

    /**
     * Submit assessment answers
     */
    public function submitAssessment(Request $request, $id)
    {
        try {
            \Log::info('Assessment submission started', ['id' => $id, 'user_id' => Auth::id()]);
            
            $user = Auth::user();
            \Log::info('User authenticated', ['user_email' => $user->email]);
            
            // Get assessment assignment details (same logic as takeAssessment)
            \Log::info('Getting assessment assignment', ['id' => $id]);
            
            $assignment = DB::connection('learning_management')
                ->table('assessment_assignments as aa')
                ->join('quizzes as q', 'aa.quiz_id', '=', 'q.id')
                ->join('assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
                ->where('aa.id', $id)
                ->select('aa.*', 'q.quiz_title', 'ac.category_name')
                ->first();
                
            if (!$assignment) {
                \Log::error('Assessment assignment not found', ['id' => $id]);
                return response()->json(['success' => false, 'message' => 'Assessment not found'], 404);
            }
            
            \Log::info('Assessment assignment found', ['assignment_id' => $assignment->id, 'quiz_id' => $assignment->quiz_id]);
            
            // Get questions for this quiz
            \Log::info('Getting quiz questions', ['quiz_id' => $assignment->quiz_id]);
            
            $questions = DB::connection('learning_management')
                ->table('quiz_questions')
                ->where('quiz_id', $assignment->quiz_id)
                ->orderBy('question_order')
                ->get();
                
            \Log::info('Quiz questions found', ['count' => count($questions)]);
                
            // Get the correct employee_id code from users table using email
            $userRecord = DB::table('users')->where('email', $user->email)->first();
            $employeeIdCode = $userRecord && $userRecord->employee_id ? $userRecord->employee_id : (string)$assignment->employee_id;

            // Create assessment result record in ESS database
            $resultId = DB::connection('ess')->table('assessment_results')->insertGetId([
                'assignment_id' => $assignment->id,
                'employee_id' => $employeeIdCode,
                'quiz_id' => $assignment->quiz_id,
                'total_questions' => count($questions),
                'attempt_number' => $assignment->attempts_used + 1,
                'started_at' => now(),
                'completed_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            $correctAnswers = 0;
            $totalPoints = 0;
            $earnedPoints = 0;
            
            \Log::info('Form data received', ['answers' => $request->input('answers', [])]);
            
            // Process each question's answer
            foreach ($questions as $question) {
                $userAnswer = $request->input("answers.{$question->id}", '');
                $correctAnswer = $question->correct_answer;
                $points = $question->points ?? 1;
                
                \Log::info('Processing answer', [
                    'question_id' => $question->id,
                    'user_answer' => $userAnswer,
                    'correct_answer' => $correctAnswer,
                    'points' => $points
                ]);
                
                // Check if answer is correct (case-insensitive)
                $isCorrect = strtolower(trim($userAnswer)) === strtolower(trim($correctAnswer));
                
                if ($isCorrect) {
                    $correctAnswers++;
                    $earnedPoints += $points;
                }
                
                $totalPoints += $points;
                
                // Store individual answer in ESS database
                DB::connection('ess')->table('user_answers')->insert([
                    'result_id' => $resultId,
                    'question_id' => $question->id,
                    'user_answer' => $userAnswer,
                    'correct_answer' => $correctAnswer,
                    'is_correct' => $isCorrect,
                    'points_earned' => $isCorrect ? $points : 0,
                    'points_possible' => $points,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Calculate score percentage
            $scorePercentage = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100) : 0;
            $passed = $scorePercentage >= 70; // 70% passing grade
            
            // Update assessment result with final score in ESS database
            DB::connection('ess')->table('assessment_results')
                ->where('id', $resultId)
                ->update([
                    'score' => $scorePercentage,
                    'correct_answers' => $correctAnswers,
                    'status' => 'completed' // Always completed since the assessment was taken
                ]);
                
            // Update assessment assignment in learning_management database
            DB::connection('learning_management')->table('assessment_assignments')
                ->where('id', $assignment->id)
                ->update([
                    'attempts_used' => $assignment->attempts_used + 1,
                    'score' => $scorePercentage,
                    'status' => 'completed', // Always completed since the assessment was taken
                    'completed_at' => now(), // Always set completion time
                    'updated_at' => now()
                ]);
            
            \Log::info('Assessment submitted successfully', [
                'user_id' => $user->id,
                'assignment_id' => $assignment->id,
                'result_id' => $resultId,
                'score' => $scorePercentage,
                'correct_answers' => $correctAnswers,
                'total_questions' => count($questions)
            ]);
            
            return response()->json([
                'success' => true,
                'score' => $scorePercentage,
                'passed' => $passed,
                'correct_answers' => $correctAnswers,
                'total_questions' => count($questions),
                'redirect' => route('ess.lms')
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Assessment submission error: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['success' => false, 'message' => 'Error submitting assessment: ' . $e->getMessage()], 500);
        }
    }
}