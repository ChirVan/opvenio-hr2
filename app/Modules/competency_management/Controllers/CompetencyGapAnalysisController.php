<?php

namespace App\Modules\competency_management\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\competency_management\Models\RoleMapping;
use App\Modules\competency_management\Models\Competency;
use App\Modules\learning_management\Models\AssessmentResult;
use App\Modules\learning_management\Models\AssessmentAssignment;
use App\Modules\learning_management\Models\Quiz;
use App\Modules\training_management\Models\TrainingAssignment;
use App\Modules\training_management\Models\TrainingAssignmentEmployee;
use App\Services\EmployeeApiService;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class CompetencyGapAnalysisController extends Controller
{
    protected $employeeApiService;

    public function __construct(EmployeeApiService $employeeApiService)
    {
        $this->employeeApiService = $employeeApiService;
    }

    /**
     * Display comprehensive employee competency management dashboard
     */
    public function index(Request $request)
    {
        // Debug: Start analysis
        \Log::info('Competency Gap Analysis - Starting with updated filter (passed + completed assessments)');
        
        // Get ALL external employees
        $externalEmployees = $this->employeeApiService->getEmployees();
        if (!$externalEmployees) {
            return view('competency_management.competency_gap_analysis', [
                'gapAnalysis' => collect(),
                'apiStatus' => false,
                'errorMessage' => 'Unable to fetch employee data from external API.',
                'summary' => $this->getEmptySummary(),
                'employees' => collect()
            ]);
        }

        // Get ALL assessment results for employees (not just latest) to capture all competencies
        // We'll process all assessments to build comprehensive competency profile
        $allAssessmentResults = DB::connection('ess')
            ->table('assessment_results as ar')
            ->join('hr2_opvenio_hr2.users as u', 'ar.employee_id', '=', 'u.employee_id')
            ->leftJoin('hr2_learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
            ->leftJoin('hr2_learning_management.quizzes as q', 'aa.quiz_id', '=', 'q.id')
            ->leftJoin('hr2_learning_management.assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
            ->whereIn('ar.status', ['passed', 'completed']) // Include both passed and completed assessments
            ->where(function($query) {
                $query->whereNotNull('ar.evaluation_data') // Has evaluation data
                      ->orWhereNotNull('ar.evaluated_by'); // OR has been evaluated (even without eval data)
            })
            ->select([
                'ar.id',
                'ar.employee_id',
                'u.name as employee_name',
                'u.email as employee_email',
                'q.quiz_title',
                'ac.category_name',
                'ar.evaluation_data',
                'ar.score',
                'ar.status',
                'ar.evaluated_at',
                'ar.completed_at',
                'ar.evaluated_by',
                'q.competency_id'
            ])
            ->orderBy('ar.completed_at', 'desc')
            ->get()
            ->groupBy('employee_id');

        // Debug: Log assessment results found
        \Log::info('Found ' . $allAssessmentResults->count() . ' employees with completed assessments');
        
        // Check if Juan is in the results
        $juanFound = false;
        foreach ($allAssessmentResults as $empId => $assessments) {
            $empName = $assessments->first()->employee_name ?? 'Unknown';
            if (stripos($empName, 'Juan') !== false) {
                $juanFound = true;
                \Log::info("JUAN FOUND in assessment results - Employee ID: {$empId}, Assessments: " . $assessments->count());
                break;
            }
        }
        if (!$juanFound) {
            \Log::info("JUAN NOT FOUND in filtered assessment results - he may not meet the updated criteria (passed/completed + evaluated)");
        }



        // Create employee lookup from external API
        $employeeLookup = [];
        foreach ($externalEmployees as $employee) {
            $employeeLookup[$employee['employee_id']] = $employee;
        }

        // Get all available competencies and role mappings
        $availableCompetencies = DB::connection('competency_management')
            ->table('competencies')
            ->where('status', 'active')
            ->select('id', 'competency_name', 'description')
            ->get();
        
        // Get all skill gap assignments for employees
        $skillGapAssignments = DB::connection('competency_management')
            ->table('skill_gap_assignments')
            ->whereIn('status', ['pending', 'in_progress'])
            ->select('employee_id', 'competency_key', 'action_type', 'notes', 'status', 'assigned_at', 'created_at')
            ->get()
            ->map(function($item) {
                return (array) $item; // Convert stdClass to array
            })
            ->groupBy('employee_id');

        $gapAnalysisResults = collect();
        
        // Debug: Check if Juan is in external employees
        $juanInExternal = false;
        foreach ($externalEmployees as $emp) {
            if (stripos($emp['full_name'], 'Juan') !== false) {
                $juanInExternal = true;
                \Log::info("JUAN in external API - ID: {$emp['employee_id']}, Name: {$emp['full_name']}, Job: " . ($emp['job_title'] ?? 'N/A'));
                break;
            }
        }
        if (!$juanInExternal) {
            \Log::info('JUAN NOT FOUND in external employee API');
        }

        // Process ALL employees and create consolidated records (one per employee)
        $employeeSummaries = collect();
        
        foreach ($externalEmployees as $employee) {
            $employeeId = $employee['employee_id'];
            $employeeName = $employee['full_name'];
            $jobTitle = $employee['job_title'] ?? 'Unknown';
            
            // Get employee's assessment results if any
            $employeeAssessments = $allAssessmentResults->get($employeeId, collect());
            
            // Debug Juan specifically
            if (stripos($employeeName, 'Juan') !== false) {
                \Log::info("PROCESSING JUAN - Assessments found: " . $employeeAssessments->count());
            }
            
            // TEMPORARILY DISABLED: Role mapping logic
            // We're now only showing competencies based on actual assessments
            $roleRequirements = collect(); // Empty collection - disregard role mappings

            // Collect all competencies for this employee (based on assessments only)
            $allCompetencies = collect();
            $hasCompletedAssessments = false;
            $overallAssessmentStatus = 'not_assigned';
            $primaryCompetency = null;
            $primaryGapStatus = 'needs_assignment';
            
            // Process all assessments (no distinction between role-required and additional)
            // Process all assessments (no distinction between role-required and additional)
            if ($employeeAssessments->isNotEmpty()) {
                foreach ($employeeAssessments as $assessment) {
                    $hasCompletedAssessments = true;
                    $currentLevel = null;
                    $currentScore = 0;
                    $assessmentStatus = 'completed';
                    
                    // Try to get score from multiple sources
                    // First try evaluation_data
                    if (!empty($assessment->evaluation_data)) {
                        $evaluationData = json_decode($assessment->evaluation_data, true);
                        if ($evaluationData && is_array($evaluationData)) {
                            $scores = array_values(array_filter($evaluationData, 'is_numeric'));
                            if (!empty($scores)) {
                                $currentScore = array_sum($scores) / count($scores);
                                $currentLevel = $this->mapEvaluationScoreToLevel($currentScore);
                            }
                        }
                    }
                    
                    // If no evaluation data, try direct score field
                    if ($currentScore === 0 && !empty($assessment->score)) {
                        $currentScore = $assessment->score;
                        $currentLevel = $this->mapEvaluationScoreToLevel($currentScore);
                    }
                    
                    // If still no score but assessment is completed/passed, assign default level
                    if ($currentScore === 0 && in_array($assessment->status, ['completed', 'passed'])) {
                        $currentScore = 3.0; // Default passing score
                        $currentLevel = 'Intermediate'; // Default level for completed assessments
                    }
                    
                    $assessmentData = [
                        'score' => $currentScore,
                        'status' => $assessment->status,
                        'evaluated_at' => $assessment->evaluated_at,
                        'completed_at' => $assessment->completed_at,
                        'quiz_title' => $assessment->quiz_title,
                        'category_name' => $assessment->category_name
                    ];
                    
                    // Get competency name
                    $competencyName = 'Unknown Competency';
                    $frameworkName = 'Assessment-Based';
                    if ($assessment->competency_id) {
                        $competency = DB::connection('competency_management')
                            ->table('competencies')
                            ->where('id', $assessment->competency_id)
                            ->first();
                        if ($competency) {
                            $competencyName = $competency->competency_name;
                        }
                    }
                    
                    $gapStatus = 'completed'; // All assessments are considered completed
                    
                    $allCompetencies->push([
                        'competency_id' => $assessment->competency_id,
                        'competency_name' => $competencyName,
                        'framework_name' => $frameworkName,
                        'required_level' => null, // No role requirement
                        'current_level' => $currentLevel,
                        'assessment_data' => $assessmentData,
                        'assessment_status' => $assessmentStatus,
                        'gap_status' => $gapStatus,
                        'is_role_required' => false // All assessments are now "additional"
                    ]);
                    
                    // Set primary competency (first completed assessment)
                    if (!$primaryCompetency) {
                        $primaryCompetency = [
                            'competency_id' => $assessment->competency_id,
                            'competency_name' => $competencyName,
                            'framework_name' => $frameworkName,
                            'required_level' => null,
                            'current_level' => $currentLevel,
                            'assessment_data' => $assessmentData,
                            'assessment_status' => $assessmentStatus,
                            'gap_status' => $gapStatus
                        ];
                        $primaryGapStatus = $gapStatus;
                        $overallAssessmentStatus = 'completed';
                    }
                }
            }
            
            // If employee has no competencies at all
            if ($allCompetencies->isEmpty()) {
                $primaryCompetency = [
                    'competency_id' => null,
                    'competency_name' => 'No Competencies Assigned',
                    'framework_name' => 'N/A',
                    'required_level' => null,
                    'current_level' => null,
                    'assessment_data' => null,
                    'assessment_status' => 'not_assigned',
                    'gap_status' => 'needs_assignment'
                ];
                $primaryGapStatus = 'needs_assignment';
                $overallAssessmentStatus = 'not_assigned';
            }
            
            // Create single employee record for the table
            $employeeSkillGaps = $skillGapAssignments->get($employeeId, collect());
            
            // If employee has active skill gap assignments, update gap status
            if ($employeeSkillGaps->isNotEmpty()) {
                // Prioritize critical gaps
                $hasCritical = $employeeSkillGaps->where('action_type', 'critical')->isNotEmpty();
                $hasTraining = $employeeSkillGaps->where('action_type', 'training')->isNotEmpty();
                
                if ($hasCritical) {
                    $primaryGapStatus = 'critical_action';
                } elseif ($hasTraining) {
                    $primaryGapStatus = 'training_assigned';
                } else {
                    $primaryGapStatus = 'mentoring_assigned';
                }
            }
            
            $gapAnalysisResults->push([
                'employee_id' => $employeeId,
                'employee_name' => $employeeName,
                'job_title' => $jobTitle,
                'competency_id' => $primaryCompetency['competency_id'],
                'competency_name' => $primaryCompetency['competency_name'],
                'framework_name' => $primaryCompetency['framework_name'],
                'required_level' => $primaryCompetency['required_level'],
                'current_level' => $primaryCompetency['current_level'],
                'has_assessment' => $hasCompletedAssessments,
                'assessment_data' => $primaryCompetency['assessment_data'],
                'assessment_status' => $overallAssessmentStatus,
                'gap_status' => $primaryGapStatus,
                'quiz_available' => true,
                'assigned_quiz' => null,
                'can_assign' => $overallAssessmentStatus === 'not_assigned',
                'total_competencies' => $allCompetencies->count(),
                'completed_competencies' => $allCompetencies->where('assessment_status', 'completed')->count(),
                'all_competencies' => $allCompetencies->toArray(), // Store for detailed view
                'skill_gap_assignments' => $employeeSkillGaps->toArray(), // Add skill gap assignments
                'has_active_gaps' => $employeeSkillGaps->isNotEmpty() // Flag for active gap assignments
            ]);
        }

        // Filter results if requested
        if ($request->filled('employee_id')) {
            $gapAnalysisResults = $gapAnalysisResults->where('employee_id', $request->employee_id);
        }

        if ($request->filled('gap_status')) {
            $gapAnalysisResults = $gapAnalysisResults->where('gap_status', $request->gap_status);
        }

        // Calculate summary statistics
        $summary = [
            'total_employees' => $gapAnalysisResults->groupBy('employee_id')->count(),
            'total_competencies' => $gapAnalysisResults->count(),
            'gap_counts' => [
                'needs_improvement' => $gapAnalysisResults->where('gap_status', 'needs_improvement')->count(),
                'meets_requirement' => $gapAnalysisResults->where('gap_status', 'meets_requirement')->count(),
                'exceeds_requirement' => $gapAnalysisResults->where('gap_status', 'exceeds_requirement')->count(),
                'no_assessment' => $gapAnalysisResults->where('gap_status', 'no_assessment')->count(),
                'needs_assignment' => $gapAnalysisResults->where('gap_status', 'needs_assignment')->count(),
                'no_role_mapping' => $gapAnalysisResults->where('gap_status', 'no_role_mapping')->count(),
            ],
            'assessment_status_counts' => [
                'completed' => $gapAnalysisResults->where('assessment_status', 'completed')->count(),
                'pending' => $gapAnalysisResults->where('assessment_status', 'pending')->count(),
                'not_assigned' => $gapAnalysisResults->where('assessment_status', 'not_assigned')->count(),
                'awaiting_evaluation' => $gapAnalysisResults->where('assessment_status', 'awaiting_evaluation')->count(),
                'in_progress' => $gapAnalysisResults->where('assessment_status', 'in_progress')->count(),
            ]
        ];

        return view('competency_management.competency_gap_analysis', [
            'gapAnalysisResults' => $gapAnalysisResults,
            'employees' => collect($externalEmployees)->map(fn($emp) => (object)$emp),
            'summary' => $summary,
            'availableCompetencies' => $availableCompetencies,
            'apiStatus' => true,
        ]);
    }

    /**
     * Get employee's latest assessment result for a specific competency
     */
    private function getEmployeeCompetencyAssessment($employeeId, $competencyId)
    {
        // First, try to find quizzes that test this specific competency
        $competencyQuizzes = Quiz::on('learning_management')
            ->where('competency_id', $competencyId)
            ->where('status', 'published')
            ->pluck('id');

        // If no specific competency quiz exists, look for any completed assessment with evaluation data
        // This allows employees with completed assessments to show up in gap analysis even if
        // the competency mapping isn't perfect
        if ($competencyQuizzes->isEmpty()) {
            // Look for any completed assessment for this employee with evaluation data
            $latestResult = DB::connection('ess')
                ->table('assessment_results as ar')
                ->join('hr2_learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
                ->join('hr2_learning_management.quizzes as q', 'ar.quiz_id', '=', 'q.id')
                ->where('ar.employee_id', $employeeId)
                ->where(function($query) {
                    $query->where('ar.status', 'passed')
                          ->orWhere(function($subQuery) {
                              $subQuery->where('ar.status', 'completed')
                                       ->whereNotNull('ar.evaluation_data')
                                       ->whereNotNull('ar.evaluated_by');
                          });
                })
                ->orderBy('ar.completed_at', 'desc')
                ->select([
                    'ar.id as result_id',
                    'ar.score',
                    'ar.total_questions',
                    'ar.correct_answers', 
                    'ar.status',
                    'ar.completed_at',
                    'ar.evaluation_data',
                    'ar.evaluated_by',
                    'q.quiz_title',
                    'aa.employee_name'
                ])
                ->first();

            return $latestResult ? (array) $latestResult : null;
        }

        // Get the latest assessment result for this employee and competency
        $latestResult = DB::connection('ess')
            ->table('assessment_results as ar')
            ->join('hr2_learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
            ->join('hr2_learning_management.quizzes as q', 'ar.quiz_id', '=', 'q.id')
            ->where('ar.employee_id', $employeeId)
            ->whereIn('ar.quiz_id', $competencyQuizzes)
            ->where(function($query) {
                $query->where('ar.status', 'passed')
                      ->orWhere(function($subQuery) {
                          $subQuery->where('ar.status', 'completed')
                                   ->whereNotNull('ar.evaluation_data')
                                   ->whereNotNull('ar.evaluated_by');
                      });
            })
            ->orderBy('ar.completed_at', 'desc')
            ->select([
                'ar.id as result_id',
                'ar.score',
                'ar.total_questions',
                'ar.correct_answers', 
                'ar.status',
                'ar.completed_at',
                'q.quiz_title',
                'aa.employee_name'
            ])
            ->first();

        return $latestResult ? (array) $latestResult : null;
    }

    /**
     * Convert assessment result to competency proficiency level
     */
    private function convertScoreToCompetencyLevel($assessmentResult)
    {
        // Handle both array and numeric score inputs for backward compatibility
        if (is_array($assessmentResult)) {
            // Check if this assessment has evaluation data (competency ratings)
            if (isset($assessmentResult['evaluation_data']) && !empty($assessmentResult['evaluation_data'])) {
                try {
                    $evaluationData = json_decode($assessmentResult['evaluation_data'], true);
                    if (is_array($evaluationData) && !empty($evaluationData)) {
                        // Calculate average competency score from evaluation data
                        $totalScore = 0;
                        $competencyCount = 0;
                        
                        foreach ($evaluationData as $competencyKey => $rating) {
                            if (is_numeric($rating) && $rating > 0) {
                                $totalScore += $rating;
                                $competencyCount++;
                            }
                        }
                        
                        if ($competencyCount > 0) {
                            $averageScore = $totalScore / $competencyCount;
                            return $this->mapCompetencyScoreToLevel($averageScore);
                        }
                    }
                } catch (Exception $e) {
                    // Fall through to use regular score if evaluation data is invalid
                }
            }
            
            // Use regular score if no evaluation data
            $score = $assessmentResult['score'] ?? 0;
        } else {
            $score = $assessmentResult;
        }
        
        // Convert numeric score to competency level
        if ($score >= 4.5) return 'Expert';
        if ($score >= 3.5) return 'Advanced'; 
        if ($score >= 2.5) return 'Intermediate';
        if ($score >= 1.5) return 'Beginner';
        return 'Novice';
    }
    
    /**
     * Map competency evaluation score (1-5) to proficiency level
     */
    private function mapCompetencyScoreToLevel($score)
    {
        if ($score >= 4.5) return 'Expert';      // Exceptional (5)
        if ($score >= 3.5) return 'Advanced';    // Highly Effective (4) 
        if ($score >= 2.5) return 'Intermediate'; // Proficient (3)
        if ($score >= 1.5) return 'Beginner';    // Inconsistent (2)
        return 'Novice';                         // Unsatisfactory (1)
    }

    /**
     * Map evaluation score to level - alias for consistency
     */
    private function mapEvaluationScoreToLevel($score)
    {
        return $this->mapCompetencyScoreToLevel($score);
    }

    /**
     * Determine gap status by comparing current vs required level
     */
    private function determineGapStatus($currentLevel, $requiredLevel)
    {
        // Handle case where no competencies are assigned to the role
        if (!$requiredLevel) {
            return 'needs_assignment';
        }
        
        if (!$currentLevel) {
            return 'no_assessment';
        }

        $levelValues = [
            'Novice' => 1,
            'Beginner' => 2,
            'Intermediate' => 3,
            'Advanced' => 4,
            'Expert' => 5
        ];

        $currentValue = $levelValues[$currentLevel] ?? 0;
        $requiredValue = $levelValues[$requiredLevel] ?? 0;

        if ($currentValue < $requiredValue) {
            return 'needs_improvement';
        } elseif ($currentValue > $requiredValue) {
            return 'exceeds_requirement';
        } else {
            return 'meets_requirement';
        }
    }

    /**
     * Check if there's a quiz available for this competency
     */
    private function hasQuizForCompetency($competencyId)
    {
        return Quiz::on('learning_management')
            ->where('competency_id', $competencyId)
            ->where('status', 'published')
            ->exists();
    }

    /**
     * Get assigned quiz for employee and competency
     */
    private function getAssignedQuiz($employeeId, $competencyId)
    {
        return DB::connection('learning_management')
            ->table('assessment_assignments as aa')
            ->join('quizzes as q', 'aa.quiz_id', '=', 'q.id')
            ->where('aa.employee_id', $employeeId)
            ->where('q.competency_id', $competencyId)
            ->whereNotIn('aa.status', ['completed', 'cancelled'])
            ->select([
                'aa.id as assignment_id',
                'aa.status',
                'aa.due_date',
                'q.quiz_title',
                'q.id as quiz_id'
            ])
            ->first();
    }

    /**
     * Assign assessment to employee for a specific competency gap
     */
    public function assignAssessment(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer',
            'competency_id' => 'required|exists:competency_management.competencies,id'
        ]);

        // Find a suitable quiz for this competency
        $quiz = Quiz::on('learning_management')
            ->where('competency_id', $request->competency_id)
            ->where('status', 'published')
            ->first();

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'No published quiz available for this competency.'
            ], 404);
        }

        // Get employee details
        $employees = $this->employeeApiService->getEmployees();
        $employee = collect($employees ?? [])->firstWhere('id', $request->employee_id);
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.'
            ], 404);
        }

        // Check if already assigned
        $existingAssignment = AssessmentAssignment::on('learning_management')
            ->where('employee_id', $request->employee_id)
            ->where('quiz_id', $quiz->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->exists();

        if ($existingAssignment) {
            return response()->json([
                'success' => false,
                'message' => 'Employee already has an active assignment for this competency.'
            ], 400);
        }

        // Create assignment
        try {
            $assignment = AssessmentAssignment::on('learning_management')->create([
                'assessment_category_id' => $quiz->category_id,
                'quiz_id' => $quiz->id,
                'employee_id' => $request->employee_id,
                'employee_name' => $employee['full_name'],
                'employee_email' => $employee['email'] ?? null,
                'duration' => $quiz->time_limit,
                'start_date' => now(),
                'due_date' => now()->addDays(7), // 7 days to complete
                'max_attempts' => 3,
                'status' => 'pending',
                'notes' => 'Auto-assigned based on competency gap analysis',
                'assigned_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Assessment '{$quiz->quiz_title}' assigned to {$employee['full_name']} successfully.",
                'assignment_id' => $assignment->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign assessment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed gap analysis for a specific employee
     */
    public function employeeDetail($employeeId)
    {
        $employee = collect($this->employeeApiService->getEmployees() ?? [])
            ->firstWhere('employee_id', $employeeId); // Use employee_id from external API

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        $jobTitle = $employee['job_title'] ?? 'Unknown';
        
        // Get ALL assessment results for this employee
        $allAssessmentResults = DB::connection('ess')
            ->table('assessment_results as ar')
            ->join('hr2_opvenio_hr2.users as u', 'ar.employee_id', '=', 'u.employee_id')
            ->leftJoin('hr2_learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
            ->leftJoin('hr2_learning_management.quizzes as q', 'aa.quiz_id', '=', 'q.id')
            ->leftJoin('hr2_learning_management.assessment_categories as ac', 'aa.assessment_category_id', '=', 'ac.id')
            ->where('ar.employee_id', $employeeId)
            ->where('ar.status', 'passed')
            ->whereNotNull('ar.evaluation_data')
            ->whereNotNull('ar.evaluated_by')
            ->select([
                'ar.id',
                'ar.employee_id',
                'u.name as employee_name',
                'u.email as employee_email',
                'q.quiz_title',
                'ac.category_name',
                'ar.evaluation_data',
                'ar.score',
                'ar.status',
                'ar.evaluated_at',
                'ar.completed_at',
                'ar.evaluated_by',
                'q.competency_id'
            ])
            ->orderBy('ar.completed_at', 'desc')
            ->get();
        
        // Get role requirements for this job title
        $roleRequirements = RoleMapping::with(['competency', 'framework'])
            ->where('role_name', $jobTitle)
            ->where('status', 'active')
            ->get();

        $detailedAnalysis = collect();

        // Process role-required competencies
        foreach ($roleRequirements as $requirement) {
            $relevantAssessment = $allAssessmentResults->where('competency_id', $requirement->competency_id)->first();
            $currentLevel = null;
            $currentScore = 0;
            $assessmentData = null;
            $assessmentStatus = 'not_assigned';
            
            if ($relevantAssessment) {
                $assessmentStatus = 'completed';
                
                // Parse evaluation data
                $evaluationData = json_decode($relevantAssessment->evaluation_data, true);
                if ($evaluationData && is_array($evaluationData)) {
                    $scores = array_values(array_filter($evaluationData, 'is_numeric'));
                    if (!empty($scores)) {
                        $currentScore = array_sum($scores) / count($scores);
                        $currentLevel = $this->mapEvaluationScoreToLevel($currentScore);
                    }
                }
                
                $assessmentData = [
                    'score' => $currentScore,
                    'status' => $relevantAssessment->status,
                    'evaluated_at' => $relevantAssessment->evaluated_at,
                    'completed_at' => $relevantAssessment->completed_at,
                    'quiz_title' => $relevantAssessment->quiz_title,
                    'category_name' => $relevantAssessment->category_name
                ];
            }
            
            $detailedAnalysis->push([
                'competency_id' => $requirement->competency_id,
                'competency_name' => $requirement->competency->competency_name ?? 'Unknown Competency',
                'framework_name' => $requirement->framework->framework_name ?? 'Unknown Framework',
                'required_level' => $requirement->proficiency_level,
                'current_level' => $currentLevel,
                'assessment_data' => $assessmentData,
                'assessment_status' => $assessmentStatus,
                'gap_status' => $this->determineGapStatus($currentLevel, $requirement->proficiency_level),
                'quiz_available' => $this->hasQuizForCompetency($requirement->competency_id),
                'assigned_quiz' => $this->getAssignedQuiz($employeeId, $requirement->competency_id),
                'is_role_required' => true
            ]);
        }
        
        // Process additional competencies (not role-required)
        $requiredCompetencyIds = $roleRequirements->pluck('competency_id')->toArray();
        $additionalAssessments = $allAssessmentResults->whereNotIn('competency_id', $requiredCompetencyIds);
        
        foreach ($additionalAssessments as $assessment) {
            $currentLevel = null;
            $currentScore = 0;
            
            // Parse evaluation data
            $evaluationData = json_decode($assessment->evaluation_data, true);
            if ($evaluationData && is_array($evaluationData)) {
                $scores = array_values(array_filter($evaluationData, 'is_numeric'));
                if (!empty($scores)) {
                    $currentScore = array_sum($scores) / count($scores);
                    $currentLevel = $this->mapEvaluationScoreToLevel($currentScore);
                }
            }
            
            $assessmentData = [
                'score' => $currentScore,
                'status' => $assessment->status,
                'evaluated_at' => $assessment->evaluated_at,
                'completed_at' => $assessment->completed_at,
                'quiz_title' => $assessment->quiz_title,
                'category_name' => $assessment->category_name
            ];
            
            // Get competency name
            $competencyName = 'Unknown Competency';
            if ($assessment->competency_id) {
                $competency = DB::connection('competency_management')
                    ->table('competencies')
                    ->where('id', $assessment->competency_id)
                    ->first();
                if ($competency) {
                    $competencyName = $competency->competency_name;
                }
            }
            
            $detailedAnalysis->push([
                'competency_id' => $assessment->competency_id,
                'competency_name' => $competencyName,
                'framework_name' => 'Additional Competency',
                'required_level' => null,
                'current_level' => $currentLevel,
                'assessment_data' => $assessmentData,
                'assessment_status' => 'completed',
                'gap_status' => 'no_role_mapping',
                'quiz_available' => true,
                'assigned_quiz' => null,
                'is_role_required' => false
            ]);
        }

        return view('competency_management.employee_gap_detail', [
            'employee' => (object) $employee,
            'analysis' => $detailedAnalysis,
            'summary' => [
                'total_competencies' => $detailedAnalysis->count(),
                'role_required' => $detailedAnalysis->where('is_role_required', true)->count(),
                'additional_competencies' => $detailedAnalysis->where('is_role_required', false)->count(),
                'completed_assessments' => $detailedAnalysis->where('assessment_status', 'completed')->count(),
                'needs_improvement' => $detailedAnalysis->where('gap_status', 'needs_improvement')->count(),
                'meets_requirement' => $detailedAnalysis->where('gap_status', 'meets_requirement')->count(),
                'exceeds_requirement' => $detailedAnalysis->where('gap_status', 'exceeds_requirement')->count(),
                'no_assessment' => $detailedAnalysis->where('gap_status', 'no_assessment')->count(),
            ]
        ]);
    }

    /**
     * Show pre-assessment assignment page
     */
    public function preAssessment(Request $request)
    {
        // Get all employees
        $externalEmployees = $this->employeeApiService->getEmployees();
        
        if (!$externalEmployees) {
            return redirect()->route('competency.gap-analysis')
                ->with('error', 'Unable to fetch employee data from external API.');
        }

        // Get all published quizzes linked to competencies
        $availableQuizzes = Quiz::on('learning_management')
            ->with(['competency', 'category'])
            ->where('status', 'published')
            ->whereNotNull('competency_id')
            ->orderBy('quiz_title')
            ->get()
            ->filter(function($quiz) {
                // Filter out quizzes where competency relationship failed to load
                return $quiz->competency !== null;
            });

        // Get role mappings to suggest relevant assessments
        $roleMappings = RoleMapping::with(['competency', 'framework'])
            ->where('status', 'active')
            ->get()
            ->groupBy('role_name');

        // Get existing assignments to avoid duplicates
        $existingAssignments = AssessmentAssignment::on('learning_management')
            ->whereIn('status', ['pending', 'in_progress'])
            ->get()
            ->groupBy('employee_id');

        return view('competency_management.pre_assessment', [
            'employees' => collect($externalEmployees)->map(fn($emp) => (object)$emp),
            'quizzes' => $availableQuizzes,
            'roleMappings' => $roleMappings,
            'existingAssignments' => $existingAssignments,
            'apiStatus' => true
        ]);
    }

    /**
     * Get completed pre-assessments to determine if gap analysis is possible
     */
    private function getCompletedPreAssessments()
    {
        return DB::connection('ess')
            ->table('assessment_results as ar')
            ->join('hr2_learning_management.assessment_assignments as aa', 'ar.assignment_id', '=', 'aa.id')
            ->join('hr2_learning_management.quizzes as q', 'ar.quiz_id', '=', 'q.id')
            ->where('ar.status', 'passed')
            ->whereNotNull('q.competency_id')
            ->select([
                'ar.employee_id',
                'q.competency_id',
                'ar.score',
                'ar.completed_at'
            ])
            ->get();
    }

    /**
     * Get summary for pre-assessment workflow
     */
    private function getPreAssessmentSummary()
    {
        // Count available quizzes linked to competencies
        $availableTests = Quiz::on('learning_management')
            ->where('status', 'published')
            ->whereNotNull('competency_id')
            ->count();

        // Count pending assignments
        $pendingAssignments = AssessmentAssignment::on('learning_management')
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();

        // Count completed assessments
        $completedAssessments = $this->getCompletedPreAssessments()->count();

        return [
            'total_assessments' => $availableTests,
            'no_assessment' => $pendingAssignments,
            'meets_requirement' => $completedAssessments,
            'needs_improvement' => 0 // Will be calculated after gap analysis
        ];
    }

    /**
     * Get empty summary when no data available
     */
    private function getEmptySummary()
    {
        return [
            'total_assessments' => 0,
            'needs_improvement' => 0,
            'meets_requirement' => 0,
            'no_assessment' => 0
        ];
    }

    /**
     * Get list of all active competencies for dropdown
     */
    public function getCompetenciesList()
    {
        try {
            $competencies = DB::connection('competency_management')
                ->table('competencies')
                ->where('status', 'active')
                ->select('id', 'competency_name', 'description')
                ->orderBy('competency_name')
                ->get();

            return response()->json($competencies);
        } catch (\Exception $e) {
            \Log::error('Error fetching competencies list: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch competencies'], 500);
        }
    }

    /**
     * Assign competency to employee
     */
    public function assignSkillGap(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'competency_id' => 'required|exists:competency_management.competencies,id',
            'assignment_type' => 'required|in:development,gap_closure,skill_enhancement,mandatory',
            'priority' => 'nullable|in:low,medium,high,critical',
            'target_date' => 'nullable|date',
            'proficiency_level' => 'nullable|in:beginner,intermediate,advanced,expert',
            'notes' => 'nullable|string'
        ]);

        try {
            // Get external employee data
            $allEmployees = $this->employeeApiService->getEmployees();
            $employee = collect($allEmployees)->firstWhere('employee_id', $validated['employee_id']);
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            // Get competency details
            $competency = \App\Modules\competency_management\Models\Competency::with('framework')->find($validated['competency_id']);
            
            if (!$competency) {
                return response()->json([
                    'success' => false,
                    'message' => 'Competency not found'
                ], 404);
            }

            // Check if already assigned
            $existingAssignment = \App\Modules\competency_management\Models\AssignedCompetency::where('employee_id', $validated['employee_id'])
                ->where('competency_id', $validated['competency_id'])
                ->whereIn('status', ['assigned', 'in_progress'])
                ->first();

            if ($existingAssignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'This competency is already assigned to the employee'
                ], 422);
            }

            // Store the competency assignment
            $assignment = \App\Modules\competency_management\Models\AssignedCompetency::create([
                'employee_id' => $validated['employee_id'],
                'employee_name' => $employee['full_name'],
                'job_title' => $employee['job_title'] ?? 'Unknown',
                'competency_id' => $validated['competency_id'],
                'framework_id' => $competency->framework_id,
                'assignment_type' => $validated['assignment_type'],
                'priority' => $validated['priority'] ?? 'medium',
                'target_date' => $validated['target_date'] ?? null,
                'proficiency_level' => $validated['proficiency_level'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'assigned',
                'progress_percentage' => 0,
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user() ? Auth::user()->name : 'System',
                'activity' => 'assign_competency',
                'details' => "Assigned competency '{$competency->competency_name}' to {$employee['full_name']} - Type: {$validated['assignment_type']}, Priority: " . ($validated['priority'] ?? 'medium'),
                'status' => 'Success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Competency assigned successfully',
                'data' => [
                    'assignment_id' => $assignment->id,
                    'employee' => $employee['full_name'],
                    'competency' => $competency->competency_name,
                    'framework' => $competency->framework ? $competency->framework->framework_name : 'N/A',
                    'assignment_type' => $validated['assignment_type'],
                    'priority' => $validated['priority'] ?? 'medium'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error assigning competency: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign competency: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update skill gap assignment status
     */
    public function updateSkillGapStatus(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'competency_key' => 'required|string',
            'assignment_type' => 'required|in:development,gap_closure,skill_enhancement,mandatory',
            'priority' => 'nullable|in:low,medium,high,critical',
            'notes' => 'nullable|string',
            'status' => 'required|in:assigned,in_progress,completed,cancelled'
        ]);

        try {
            // Get external employee data
            $allEmployees = $this->employeeApiService->getEmployees();
            $employee = collect($allEmployees)->firstWhere('employee_id', $validated['employee_id']);
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            // Try to update in assigned_competencies table first (new table)
            $updatedNew = DB::connection('competency_management')
                ->table('assigned_competencies')
                ->where('employee_id', $validated['employee_id'])
                ->where(function($query) use ($validated) {
                    // Match by competency name (from join with competencies table)
                    $query->whereExists(function($subquery) use ($validated) {
                        $subquery->select(DB::raw(1))
                            ->from('competencies')
                            ->whereColumn('competencies.id', 'assigned_competencies.competency_id')
                            ->where('competencies.competency_name', $validated['competency_key']);
                    });
                })
                ->update([
                    'assignment_type' => $validated['assignment_type'],
                    'priority' => $validated['priority'] ?? 'medium',
                    'notes' => $validated['notes'],
                    'status' => $validated['status'],
                    'updated_at' => now()
                ]);

            // Also try to update in skill_gap_assignments table (old table)
            $updatedOld = DB::connection('competency_management')
                ->table('skill_gap_assignments')
                ->where('employee_id', $validated['employee_id'])
                ->where('competency_key', $validated['competency_key'])
                ->update([
                    'action_type' => $validated['assignment_type'],
                    'notes' => $validated['notes'],
                    'status' => $validated['status'] === 'assigned' ? 'pending' : $validated['status'],
                    'updated_at' => now()
                ]);

            if (!$updatedNew && !$updatedOld) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching skill gap assignment found'
                ], 404);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user() ? Auth::user()->name : 'System',
                'activity' => 'update_skill_gap_status',
                'details' => "Updated skill gap status for {$employee['full_name']} - Competency: {$validated['competency_key']}, Status: {$validated['status']}",
                'status' => 'Success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Skill gap assignment updated successfully',
                'data' => [
                    'employee' => $employee['full_name'],
                    'competency' => $validated['competency_key'],
                    'status' => $validated['status'],
                    'updated_tables' => [
                        'assigned_competencies' => $updatedNew > 0,
                        'skill_gap_assignments' => $updatedOld > 0
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating skill gap status: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update skill gap status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create development plan for employee
     */
    public function createDevelopmentPlan(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'goals' => 'required|string',
            'action_items' => 'required|string',
            'success_metrics' => 'nullable|string'
        ]);

        try {
            // Get external employee data
            $allEmployees = $this->employeeApiService->getEmployees();
            $employee = collect($allEmployees)->firstWhere('employee_id', $validated['employee_id']);
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            // Store the development plan
            $planId = DB::connection('competency_management')->table('development_plans')->insertGetId([
                'employee_id' => $validated['employee_id'],
                'employee_name' => $employee['full_name'],
                'job_title' => $employee['job_title'] ?? 'Unknown',
                'title' => $validated['title'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'goals' => $validated['goals'],
                'action_items' => $validated['action_items'],
                'success_metrics' => $validated['success_metrics'] ?? null,
                'status' => 'active',
                'progress' => 0,
                'created_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user() ? Auth::user()->name : 'System',
                'activity' => 'create_development_plan',
                'details' => "Created development plan for {$employee['full_name']}: {$validated['title']}",
                'status' => 'Success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Development plan created successfully',
                'data' => [
                    'plan_id' => $planId,
                    'employee' => $employee['full_name'],
                    'title' => $validated['title']
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating development plan: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create development plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Schedule assessment for employee
     */
    public function scheduleAssessment(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'assessment_type' => 'required|in:competency_retake,skill_validation,comprehensive_eval,progress_check',
            'scheduled_date' => 'required|date|after:now',
            'notes' => 'nullable|string'
        ]);

        try {
            // Get external employee data
            $allEmployees = $this->employeeApiService->getEmployees();
            $employee = collect($allEmployees)->firstWhere('employee_id', $validated['employee_id']);
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            // Store the assessment schedule
            $scheduleId = DB::connection('competency_management')->table('assessment_schedules')->insertGetId([
                'employee_id' => $validated['employee_id'],
                'employee_name' => $employee['full_name'],
                'job_title' => $employee['job_title'] ?? 'Unknown',
                'assessment_type' => $validated['assessment_type'],
                'scheduled_date' => $validated['scheduled_date'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'scheduled',
                'scheduled_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user() ? Auth::user()->name : 'System',
                'activity' => 'schedule_assessment',
                'details' => "Scheduled {$validated['assessment_type']} assessment for {$employee['full_name']} on {$validated['scheduled_date']}",
                'status' => 'Success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assessment scheduled successfully',
                'data' => [
                    'schedule_id' => $scheduleId,
                    'employee' => $employee['full_name'],
                    'date' => $validated['scheduled_date'],
                    'type' => $validated['assessment_type']
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error scheduling assessment: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule assessment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all assigned competencies
     */
    public function getAssignedCompetencies(Request $request)
    {
        try {
            $assignedCompetencies = \App\Modules\competency_management\Models\AssignedCompetency::with(['competency', 'framework'])
                ->orderBy('assigned_at', 'desc')
                ->get();

            // Get all training assignments grouped by employee_id (numeric API id)
            $trainingAssignmentsByEmployee = TrainingAssignmentEmployee::with(['trainingAssignment.trainingMaterials'])
                ->get()
                ->groupBy('employee_id');

            // Get all assessment assignments grouped by employee_id (numeric API id)
            $assessmentAssignmentsByEmployee = AssessmentAssignment::with(['quiz', 'assessmentCategory'])
                ->get()
                ->groupBy('employee_id');

            // Fetch all employees from API to create a mapping between employee_id code (EMP-001) and numeric id (1)
            $allEmployees = $this->employeeApiService->getEmployees() ?? [];
            $employeeCodeToNumericId = [];
            foreach ($allEmployees as $emp) {
                if (isset($emp['employee_id']) && isset($emp['id'])) {
                    $employeeCodeToNumericId[$emp['employee_id']] = $emp['id'];
                }
            }

            // Get all passed assessment results with evaluation data (from ESS database)
            $passedAssessmentResults = DB::connection('ess')
                ->table('assessment_results')
                ->where('status', 'passed')
                ->whereNotNull('evaluation_data')
                ->select('employee_id', 'evaluation_data', 'evaluated_at')
                ->orderBy('evaluated_at', 'desc')
                ->get()
                ->groupBy('employee_id');

            // Group by employee and aggregate data
            $groupedData = $assignedCompetencies->groupBy('employee_id')->map(function ($employeeAssignments, $employeeId) use ($trainingAssignmentsByEmployee, $assessmentAssignmentsByEmployee, $employeeCodeToNumericId, $passedAssessmentResults) {
                $firstAssignment = $employeeAssignments->first();
                
                // Get the numeric employee ID from the mapping
                // $employeeId here is the code like "EMP-001", we need the numeric id for training/assessment lookups
                $numericEmployeeId = $employeeCodeToNumericId[$employeeId] ?? null;

                // Check if employee has passed assessments with evaluation data
                $employeePassedResults = $passedAssessmentResults->get($employeeId, collect());
                $hasEvaluatedAssessment = $employeePassedResults->isNotEmpty();
                $evaluationScore = 0;
                $evaluationScorePercent = 0;
                
                if ($hasEvaluatedAssessment) {
                    // Get the latest evaluation data
                    $latestResult = $employeePassedResults->first();
                    $evalData = json_decode($latestResult->evaluation_data, true);
                    if ($evalData && isset($evalData['average_score'])) {
                        $evaluationScore = $evalData['average_score'];
                        // Convert 5-point scale to percentage: (score / 5) * 100
                        $evaluationScorePercent = round(($evaluationScore / 5) * 100);
                    }
                }
                
                // Calculate aggregate statistics - but override if has evaluated assessment
                $totalCompetencies = $employeeAssignments->count();
                
                // If employee has evaluated assessments, mark all competencies as completed
                if ($hasEvaluatedAssessment && $evaluationScorePercent > 0) {
                    $completedCount = $totalCompetencies;
                    $inProgressCount = 0;
                    $assignedCount = 0;
                    $onHoldCount = 0;
                } else {
                    $completedCount = $employeeAssignments->where('status', 'completed')->count();
                    $inProgressCount = $employeeAssignments->where('status', 'in_progress')->count();
                    $assignedCount = $employeeAssignments->where('status', 'assigned')->count();
                    $onHoldCount = $employeeAssignments->where('status', 'on_hold')->count();
                }
                
                // Calculate average progress - use evaluation score if available
                $averageProgress = $hasEvaluatedAssessment && $evaluationScorePercent > 0 
                    ? $evaluationScorePercent 
                    : round($employeeAssignments->avg('progress_percentage'), 2);
                
                // Get priority breakdown
                $criticalCount = $employeeAssignments->where('priority', 'critical')->count();
                $highCount = $employeeAssignments->where('priority', 'high')->count();
                $mediumCount = $employeeAssignments->where('priority', 'medium')->count();
                $lowCount = $employeeAssignments->where('priority', 'low')->count();
                
                // Determine highest priority - if completed, priority becomes low
                if ($hasEvaluatedAssessment && $evaluationScorePercent > 0) {
                    $highestPriority = 'low'; // Completed assessments have low priority
                } elseif ($criticalCount > 0) {
                    $highestPriority = 'critical';
                } elseif ($highCount > 0) {
                    $highestPriority = 'high';
                } elseif ($mediumCount > 0) {
                    $highestPriority = 'medium';
                } else {
                    $highestPriority = 'low';
                }
                
                // Get earliest target date
                $earliestTargetDate = $employeeAssignments
                    ->filter(function ($assignment) {
                        return $assignment->target_date !== null;
                    })
                    ->min('target_date');
                
                // Get most recent assigned date
                $latestAssignedDate = $employeeAssignments->max('assigned_at');
                
                // Determine overall status - if has evaluated assessment, status is completed
                if ($hasEvaluatedAssessment && $evaluationScorePercent > 0) {
                    $overallStatus = 'completed';
                } else {
                    $statusCounts = [
                        'completed' => $completedCount,
                        'in_progress' => $inProgressCount,
                        'assigned' => $assignedCount,
                        'on_hold' => $onHoldCount
                    ];
                    $overallStatus = array_search(max($statusCounts), $statusCounts);
                }
                
                // Get all competency details for expandable view
                $competencies = $employeeAssignments->map(function ($assignment) use ($hasEvaluatedAssessment, $evaluationScore) {
                    return [
                        'id' => $assignment->id,
                        'competency_name' => $assignment->competency ? $assignment->competency->competency_name : 'N/A',
                        'framework_name' => $assignment->framework ? $assignment->framework->framework_name : 'N/A',
                        'assignment_type' => $assignment->assignment_type,
                        'priority' => $assignment->priority,
                        'status' => $hasEvaluatedAssessment ? 'completed' : $assignment->status,
                        'progress_percentage' => $assignment->progress_percentage,
                        'target_date' => $assignment->target_date ? $assignment->target_date->format('Y-m-d') : null,
                        'assigned_at' => $assignment->assigned_at ? $assignment->assigned_at->format('Y-m-d H:i:s') : null,
                        // Add evaluation data to each competency
                        'is_evaluated' => $hasEvaluatedAssessment,
                        'evaluation_score' => $hasEvaluatedAssessment ? $evaluationScore : null,
                    ];
                })->toArray();

                // Get training assignments for this employee using numeric ID
                $employeeTrainingAssignments = $numericEmployeeId ? $trainingAssignmentsByEmployee->get($numericEmployeeId, collect()) : collect();
                $trainingMaterials = $employeeTrainingAssignments->map(function ($trainAssign) {
                    $training = $trainAssign->trainingAssignment;
                    $materials = $training ? $training->trainingMaterials->map(function ($material) {
                        return [
                            'id' => $material->id,
                            'material_title' => $material->material_title,
                            'material_type' => $material->material_type ?? 'Document',
                        ];
                    })->toArray() : [];
                    
                    return [
                        'id' => $trainAssign->id,
                        'training_assignment_id' => $trainAssign->training_assignment_id,
                        'assignment_title' => $training ? $training->assignment_title : 'N/A',
                        'status' => $trainAssign->status,
                        'progress_percentage' => $trainAssign->progress_percentage ?? 0,
                        'assigned_at' => $trainAssign->assigned_at ? $trainAssign->assigned_at->format('Y-m-d') : null,
                        'due_date' => $training && $training->due_date ? $training->due_date->format('Y-m-d') : null,
                        'materials' => $materials,
                    ];
                })->toArray();

                // Get assessment assignments for this employee using numeric ID
                $employeeAssessmentAssignments = $numericEmployeeId ? $assessmentAssignmentsByEmployee->get($numericEmployeeId, collect()) : collect();
                $assessments = $employeeAssessmentAssignments->map(function ($assessment) {
                    return [
                        'id' => $assessment->id,
                        'quiz_id' => $assessment->quiz_id,
                        'quiz_title' => $assessment->quiz ? $assessment->quiz->quiz_title : 'N/A',
                        'category' => $assessment->assessmentCategory ? $assessment->assessmentCategory->category_name : 'N/A',
                        'status' => $assessment->status,
                        'score' => $assessment->score,
                        'max_attempts' => $assessment->max_attempts,
                        'attempts_used' => $assessment->attempts_used ?? 0,
                        'duration' => $assessment->duration,
                        'start_date' => $assessment->start_date ? $assessment->start_date->format('Y-m-d') : null,
                        'due_date' => $assessment->due_date ? $assessment->due_date->format('Y-m-d') : null,
                        'completed_at' => $assessment->completed_at ? $assessment->completed_at->format('Y-m-d H:i:s') : null,
                    ];
                })->toArray();

                return [
                    'employee_id' => $employeeId,
                    'employee_name' => $firstAssignment->employee_name,
                    'job_title' => $firstAssignment->job_title,
                    'total_competencies' => $totalCompetencies,
                    'completed_count' => $completedCount,
                    'in_progress_count' => $inProgressCount,
                    'assigned_count' => $assignedCount,
                    'on_hold_count' => $onHoldCount,
                    'average_progress' => $averageProgress,
                    'highest_priority' => $highestPriority,
                    'critical_count' => $criticalCount,
                    'high_count' => $highCount,
                    'medium_count' => $mediumCount,
                    'low_count' => $lowCount,
                    'overall_status' => $overallStatus,
                    'earliest_target_date' => $earliestTargetDate ? \Carbon\Carbon::parse($earliestTargetDate)->format('Y-m-d') : null,
                    'latest_assigned_date' => $latestAssignedDate ? \Carbon\Carbon::parse($latestAssignedDate)->format('Y-m-d H:i:s') : null,
                    'competencies' => $competencies,
                    // New: Training and Assessment data
                    'has_training_assignments' => count($trainingMaterials) > 0,
                    'training_assignments' => $trainingMaterials,
                    'training_count' => count($trainingMaterials),
                    'has_assessment_assignments' => count($assessments) > 0,
                    'assessment_assignments' => $assessments,
                    'assessment_count' => count($assessments),
                    // Evaluation data from assessment results
                    'has_evaluated_assessment' => $hasEvaluatedAssessment,
                    'evaluation_score' => $evaluationScore,
                    'evaluation_score_percent' => $evaluationScorePercent,
                ];
            })->values(); // Reset array keys

            return response()->json([
                'success' => true,
                'data' => $groupedData,
                'count' => $groupedData->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching assigned competencies: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch assigned competencies: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}