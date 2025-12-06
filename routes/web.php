<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Modules\competency_management\Controllers\CompetencyFrameworkController;
use App\Modules\competency_management\Controllers\CompetencyController;
use App\Modules\competency_management\Controllers\RoleMappingController;
use App\Modules\competency_management\Controllers\GapAnalysisController;
use App\Modules\competency_management\Controllers\CompetencyGapAnalysisController;
use App\Modules\training_management\Controllers\TrainingCatalogController;
use App\Modules\learning_management\Controllers\AssessmentCategoryController;
use App\Modules\learning_management\Controllers\AssessmentAssignmentController;
use App\Modules\learning_management\Controllers\QuizController;
use App\Modules\learning_management\Controllers\SelfAssessController;
use App\Modules\training_management\Controllers\TrainingMaterialController;
use App\Modules\training_management\Controllers\GrantRequestController;
use App\Http\Controllers\Auth\TwoFactorController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('index');
});

Route::middleware('auth')->group(function () {
    // ============================================================
    // =============== Learning Routes (for Assessment Center) ====
    // ============================================================
    Route::prefix('learning')->name('learning.')->group(function () {
    Route::get('/assessment-assignments', [\App\Modules\learning_management\Controllers\AssessmentAssignmentController::class, 'index'])->name('assessment-assignments.index');
        Route::get('/hub/create', function () {
            return view('learning_management.hubCRUD.create');
        })->name('hub.create');
    Route::get('/quiz', [\App\Modules\learning_management\Controllers\QuizController::class, 'create'])->name('quiz');
    Route::post('/quiz', [\App\Modules\learning_management\Controllers\QuizController::class, 'store'])->name('quiz.store');
    Route::get('/assessment/categories/create', [AssessmentCategoryController::class, 'create'])->name('assessment.categories.create');
    Route::post('/assessment/categories', [AssessmentCategoryController::class, 'store'])->name('assessment.categories.store');
    Route::get('/hub', [AssessmentAssignmentController::class, 'index'])->name('hub');
        Route::get('/assessment', [AssessmentCategoryController::class, 'index'])->name('assessment');
        // API route for assessment categories
        Route::get('/assessment/categories/api', [AssessmentCategoryController::class, 'apiCategories'])->name('assessment.categories.api');
        // Add other learning routes here as needed

        // Store assessment assignment
        Route::post('/assessment-assignments', [\App\Modules\learning_management\Controllers\AssessmentAssignmentController::class, 'store'])->name('assessment-assignments.store');
    Route::get('/assessment-assignments/{assignment}', [\App\Modules\learning_management\Controllers\AssessmentAssignmentController::class, 'show'])->name('assessment-assignments.show');
    Route::get('/assessment-assignments/{assignment}/edit', [\App\Modules\learning_management\Controllers\AssessmentAssignmentController::class, 'edit'])->name('assessment-assignments.edit');
    Route::delete('/assessment-assignments/{assignment}', [\App\Modules\learning_management\Controllers\AssessmentAssignmentController::class, 'destroy'])->name('assessment-assignments.destroy');
        Route::get('/assessment/categories/{category}', [AssessmentCategoryController::class, 'show'])->name('assessment.categories.show');
        Route::get('/assessment/categories/{category}/edit', [AssessmentCategoryController::class, 'edit'])->name('assessment.categories.edit');
        Route::put('/assessment/categories/{category}', [AssessmentCategoryController::class, 'update'])->name('assessment.categories.update');
        Route::get('/quiz/{quiz}', [\App\Modules\learning_management\Controllers\QuizController::class, 'show'])->name('quiz.show');
        Route::get('/quiz/{quiz}/edit', [\App\Modules\learning_management\Controllers\QuizController::class, 'edit'])->name('quiz.edit');
        Route::get('/assessment/categories/{category}/quizzes', [\App\Modules\learning_management\Controllers\AssessmentCategoryController::class, 'quizzes'])->name('assessment.categories.quizzes');
        
        // Self Assessment routes
        Route::get('/self-assess', [\App\Modules\learning_management\Controllers\SelfAssessController::class, 'index'])->name('self-assess');
        Route::get('/self-assess/create', [\App\Modules\learning_management\Controllers\SelfAssessController::class, 'create'])->name('self-assess.create');
        Route::post('/self-assess/assign', [\App\Modules\learning_management\Controllers\SelfAssessController::class, 'assignAssessments'])->name('self-assess.assign');
        Route::post('/self-assess/request', [\App\Modules\learning_management\Controllers\SelfAssessController::class, 'store'])->name('self-assess.request');
        
        // API endpoint for loading available courses
        Route::get('/api/training-catalogs', function () {
            try {
                $courses = DB::connection('training_management')->table('training_catalogs')
                    ->where('is_active', 1)
                    ->select(['id', 'title', 'description'])
                    ->orderBy('title')
                    ->get();
                
                return response()->json(['courses' => $courses]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        });
    });

    // Audit Logs page
    Route::get('/audit-logs', [App\Http\Controllers\HistoryController::class, 'index'])->name('audit.logs');

    Route::get('/dashboard', function () {
        $totalCourses = DB::connection('training_management')->table('training_catalogs')->count();
        $availableCourses = DB::connection('training_management')->table('training_materials')->count();
        $assignedEmployees = DB::connection('learning_management')->table('assessment_assignments')->count();
        $identifiedSuccessors = DB::connection('succession_planning')->table('promotions')->count();

        // Fetch approved employees for potential successors table
        $results = DB::connection('ess')->table('assessment_results')->where('status', 'passed')->get();
        $apiResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
            ->get('https://hr4.microfinancial-1.com/allemployees');
        $employeesApi = $apiResponse->json();
        if (isset($employeesApi['employees'])) {
            $employeesApi = $employeesApi['employees'];
        }
        $employeeMap = collect($employeesApi)
            ->filter(function ($employee) {
                return isset($employee['employee_id']);
            })
            ->mapWithKeys(function ($employee) {
                return [trim((string)$employee['employee_id']) => $employee];
            });
        $approvedEmployees = $results->map(function ($item) use ($employeeMap) {
            $details = $employeeMap->get(trim((string)$item->employee_id));
            return (object) array(
                'employee_id' => $item->employee_id,
                'full_name' => isset($details['full_name']) ? $details['full_name'] : '-',
                'job_title' => isset($details['job_title']) ? $details['job_title'] : '-',
                'status' => $item->status,
            );
        });
        $promotedIds = DB::connection('succession_planning')->table('promotions')->pluck('employee_id')->toArray();

        return view('dashboard', compact(
            'totalCourses',
            'availableCourses',
            'assignedEmployees',
            'identifiedSuccessors',
            'approvedEmployees',
            'promotedIds'
        ));
    })->name('dashboard');

    // ============================================================
    // =============== ESS (Employee Self Service) ================
    // ============================================================
    Route::prefix('ess')->name('ess.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\ESSController::class, 'dashboard'])->name('dashboard');
        Route::get('/lms', [App\Http\Controllers\ESSController::class, 'lms'])->name('lms');
        Route::get('/assessment/{id}/take', [App\Http\Controllers\ESSController::class, 'takeAssessment'])->name('assessment.take');
        Route::post('/assessment/{id}/submit', [App\Http\Controllers\ESSController::class, 'submitAssessment'])->name('assessment.submit');
        Route::get('/leave', fn() => view('ess.leave'))->name('leave');
        Route::get('/payslip', fn() => view('ess.payslip'))->name('payslip');
        
        // Course Grant functionality
        Route::get('/training-catalog/available-courses', [App\Http\Controllers\TrainingCatalogController::class, 'getAvailableCourses'])->name('training-catalog.available-courses');
        Route::get('/training-catalog/{courseId}/materials', [App\Http\Controllers\TrainingCatalogController::class, 'getCourseTrainingMaterials'])->name('training-catalog.materials');
        Route::post('/training-catalog/request-course', [App\Http\Controllers\TrainingCatalogController::class, 'requestCourse'])->name('training-catalog.request-course');
    });

    // ============================================================
    // ======= Assessment Results Management (Admin/HR) ===========
    // ============================================================
    Route::middleware(['role:admin,hr'])->group(function () {
        Route::get('/assessment-results', [App\Http\Controllers\AssessmentResultsController::class, 'index'])->name('assessment.results');
        Route::get('/assessment-results/employee/{employeeId}/evaluate', [App\Http\Controllers\AssessmentResultsController::class, 'evaluate'])->name('assessment.results.evaluate');
        Route::get('/assessment-results/single/{resultId}/evaluate', [App\Http\Controllers\AssessmentResultsController::class, 'evaluateSingle'])->name('assessment.results.evaluate.single');
        Route::get('/assessment-results/{id}/evaluate/step2', [App\Http\Controllers\AssessmentResultsController::class, 'evaluateStep2'])->name('assessment.results.evaluate.step2');
        Route::get('/assessment-results/employee/{employeeId}/step2-evaluation', [App\Http\Controllers\AssessmentResultsController::class, 'employeeStep2Evaluation'])->name('assessment.results.employee.step2');
        Route::post('/assessment-results/{id}/submit-evaluation', [App\Http\Controllers\AssessmentResultsController::class, 'submitEvaluation'])->name('assessment.results.submit-evaluation');
        Route::post('/assessment-results/{id}/approve', [App\Http\Controllers\AssessmentResultsController::class, 'approve'])->name('assessment.results.approve');
        Route::post('/assessment-results/{id}/reject', [App\Http\Controllers\AssessmentResultsController::class, 'reject'])->name('assessment.results.reject');
        Route::post('/assessment-results/update-question-scoring', [App\Http\Controllers\AssessmentResultsController::class, 'updateQuestionScoring'])->name('assessment.results.update-scoring');
        Route::get('/approved-employees', [App\Http\Controllers\AssessmentResultsController::class, 'approvedEmployeesReport'])->name('assessment.approved-employees');
        Route::post('/assessment-results/reassign', [App\Modules\learning_management\Controllers\AssessmentAssignmentController::class, 'reassign'])->name('assessment.results.reassign');
    });

    // ============================================================
    // =========== Succession Planning (Talent Pool) ==============
    // ============================================================
    Route::middleware(['role:admin,hr'])->group(function () {

        Route::get('/talent-pool', [App\Modules\succession_planning\Controllers\TalentPoolController::class, 'index'])->name('succession.talent-pool');
        Route::get('/talent-pool/potential/{employee_id}', [App\Modules\succession_planning\Controllers\TalentPoolController::class, 'showPotential'])->name('succession.potential');
        Route::post('/talent-pool/promote', [App\Modules\succession_planning\Controllers\PromotionController::class, 'store'])->name('succession.promote');
        
        // Execute promotion - Update job title in external HR API
        Route::post('/promotion/{id}/execute', [App\Modules\succession_planning\Controllers\PromotionController::class, 'executePromotion'])->name('succession.execute-promotion');
        Route::get('/promotion/{id}/status', [App\Modules\succession_planning\Controllers\PromotionController::class, 'getPromotionStatus'])->name('succession.promotion-status');

        Route::get('/successors', fn() => view('succession_planning.successors'))->name('succession.successors');
        Route::get('/talent-pool/promote', fn() => redirect()->route('succession.talent-pool'));

        Route::get('/potential-successors', function () {
            // Get passed assessment results with evaluation data (similar to gap analysis)
            $results = DB::connection('ess')
                ->table('assessment_results')
                ->where('status', 'passed')
                ->whereNotNull('evaluation_data')
                ->select('employee_id', 'score', 'status', 'evaluation_data', 'evaluated_at', 'completed_at')
                ->orderBy('evaluated_at', 'desc')
                ->get();

            // Fetch employees from API
            $apiResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                ->get('https://hr4.microfinancial-1.com/allemployees');
            $employeesApi = $apiResponse->json();
            if (isset($employeesApi['employees'])) {
                $employeesApi = $employeesApi['employees'];
            }
            $employeeMap = collect($employeesApi)
                ->filter(function ($employee) {
                    return isset($employee['employee_id']);
                })
                ->mapWithKeys(function ($employee) {
                    return [trim((string)$employee['employee_id']) => $employee];
                });

            // Group by employee_id and aggregate data (take latest/best scores)
            $groupedResults = $results->groupBy('employee_id')->map(function ($employeeResults, $employeeId) use ($employeeMap) {
                $details = $employeeMap->get(trim((string)$employeeId));
                
                // Get the latest result with evaluation data
                $latestResult = $employeeResults->first(); // Already ordered by evaluated_at desc
                
                // Calculate average score from all assessments
                $avgScore = $employeeResults->avg('score');
                
                // Get evaluation data from latest result
                $evalData = json_decode($latestResult->evaluation_data, true);
                $evaluationScore = isset($evalData['average_score']) ? $evalData['average_score'] : 0;
                $evaluationScorePercent = round(($evaluationScore / 5) * 100);
                
                // Calculate leadership readiness from competency scores
                $leadershipScore = $evaluationScore; // Use evaluation average as leadership indicator
                
                // Determine potential level based on evaluation score
                // Expert level = evaluation score >= 4.0 (80%+)
                if ($evaluationScore >= 4.5) {
                    $potentialLevel = 'Ready Now';
                    $riskLevel = 'Low Risk';
                    $isExpert = true;
                } elseif ($evaluationScore >= 4.0) {
                    $potentialLevel = 'High Potential';
                    $riskLevel = 'Low Risk';
                    $isExpert = true;
                } elseif ($evaluationScore >= 3.5) {
                    $potentialLevel = 'Moderate Potential';
                    $riskLevel = 'Medium Risk';
                    $isExpert = false; // Needs development
                } elseif ($evaluationScore >= 3.0) {
                    $potentialLevel = 'Development Needed';
                    $riskLevel = 'Medium Risk';
                    $isExpert = false;
                } else {
                    $potentialLevel = 'Skill Improvement Required';
                    $riskLevel = 'High Risk';
                    $isExpert = false;
                }
                
                // Check pipeline readiness criteria (Expert = 80%+ score)
                $criteria = [
                    'performance' => $evaluationScorePercent >= 80, // Expert threshold
                    'assessment_passed' => true, // Already filtered by passed status
                    'leadership_score' => $leadershipScore >= 4.0 // Expert leadership threshold
                ];
                $readyCount = count(array_filter($criteria));
                
                return (object) [
                    'employee_id' => $employeeId,
                    'full_name' => $details['full_name'] ?? 'Unknown',
                    'email' => $details['email'] ?? '-',
                    'job_title' => $details['job_title'] ?? '-',
                    'assessment_count' => $employeeResults->count(),
                    'avg_score' => round($avgScore, 1),
                    'evaluation_score' => $evaluationScore,
                    'evaluation_score_percent' => $evaluationScorePercent,
                    'leadership_score' => round($leadershipScore, 1),
                    'potential_level' => $potentialLevel,
                    'risk_level' => $riskLevel,
                    'is_expert' => $isExpert,
                    'criteria' => $criteria,
                    'ready_count' => $readyCount,
                    'status' => 'Passed',
                    'last_evaluated' => $latestResult->evaluated_at,
                ];
            })->values();

            // Separate into two groups:
            // 1. Potential Successors (Expert level - 80%+ evaluation score)
            $approvedEmployees = $groupedResults->filter(fn($e) => $e->is_expert)->sortByDesc('evaluation_score')->values();
            
            // 2. Development Needed (Below Expert - needs skill improvement)
            $developmentNeeded = $groupedResults->filter(fn($e) => !$e->is_expert)->sortByDesc('evaluation_score')->values();

            $promotedIds = DB::connection('succession_planning')->table('promotions')->pluck('employee_id')->toArray();
            return view('succession_planning.potential_successor', compact('approvedEmployees', 'developmentNeeded', 'promotedIds'));
        })->name('succession.potential-successors');
    });

    // ============================================================
    // ============ Learning Management (Admin/HR) ================
    // ============================================================
    Route::prefix('learning-management')->name('learning-management.')->middleware(['role:admin,hr'])->group(function () {
        Route::get('/assessment-results', [App\Modules\learning_management\Controllers\AssessmentResultsController::class, 'index'])->name('assessment-results.index');
        Route::get('/assessment-results/{id}/evaluate', [App\Modules\learning_management\Controllers\AssessmentResultsController::class, 'evaluate'])->name('assessment-results.evaluate');
        Route::put('/assessment-results/{id}/evaluation', [App\Modules\learning_management\Controllers\AssessmentResultsController::class, 'updateEvaluation'])->name('assessment-results.update-evaluation');
        Route::get('/assessment-results/statistics', [App\Modules\learning_management\Controllers\AssessmentResultsController::class, 'statistics'])->name('assessment-results.statistics');
    });

    // ============================================================
    // =============== Competency Management ======================
    // ============================================================
    Route::get('/competency/frameworks', [CompetencyFrameworkController::class, 'index'])->name('competency.frameworks');
    Route::get('/competency/frameworks/create', [CompetencyFrameworkController::class, 'create'])->name('competency.frameworks.create');
    Route::post('/competency/frameworks', [CompetencyFrameworkController::class, 'store'])->name('competency.frameworks.store');
    Route::get('/competency/frameworks/{framework}', [CompetencyFrameworkController::class, 'show'])->name('competency.frameworks.show');
    Route::get('/competency/frameworks/{framework}/edit', [CompetencyFrameworkController::class, 'edit'])->name('competency.frameworks.edit');
    Route::put('/competency/frameworks/{framework}', [CompetencyFrameworkController::class, 'update'])->name('competency.frameworks.update');
    Route::delete('/competency/frameworks/{framework}', [CompetencyFrameworkController::class, 'destroy'])->name('competency.frameworks.destroy');

    Route::get('/materials/{catalog}', [\App\Modules\training_management\Controllers\TrainingAssignmentController::class, 'getMaterials'])->name('materials');
    Route::get('/gap-analysis-employees', [\App\Modules\training_management\Controllers\TrainingAssignmentController::class, 'getGapAnalysisEmployees'])->name('gap-analysis-employees');

    Route::get('/competency/competencies', [CompetencyController::class, 'index'])->name('competency.competencies.index');
    Route::get('/competency/competencies/create', [CompetencyController::class, 'create'])->name('competency.competencies.create');
    Route::post('/competency/competencies', [CompetencyController::class, 'store'])->name('competency.competencies.store');
    Route::get('/competency/competencies/{competency}', [CompetencyController::class, 'show'])->name('competency.competencies.show');
    Route::get('/competency/competencies/{competency}/edit', [CompetencyController::class, 'edit'])->name('competency.competencies.edit');
    Route::put('/competency/competencies/{competency}', [CompetencyController::class, 'update'])->name('competency.competencies.update');
    Route::delete('/competency/competencies/{competency}', [CompetencyController::class, 'destroy'])->name('competency.competencies.destroy');

    Route::get('/competency/rolemapping/employee/{employee}', [GapAnalysisController::class, 'skillGapAnalysis'])->name('competency.rolemapping.employee');
    Route::get('/competency/rolemapping', [GapAnalysisController::class, 'skillGapAnalysis'])->name('competency.rolemapping');
    
    // Skill Gap Management Actions - Using CompetencyGapAnalysisController for new assigned_competencies table
    Route::post('/competency/skill-gaps/assign', [CompetencyGapAnalysisController::class, 'assignSkillGap'])->name('competency.skill-gaps.assign');
    Route::post('/competency/development-plan/create', [GapAnalysisController::class, 'createDevelopmentPlan'])->name('competency.development-plan.create');
    Route::post('/competency/assessment/schedule', [GapAnalysisController::class, 'scheduleAssessment'])->name('competency.assessment.schedule');
    Route::get('/competency/gap-analysis/export/{employee}', [GapAnalysisController::class, 'exportGapAnalysis'])->name('competency.gap-analysis.export');
    
    Route::get('/competency/rolemapping/create', [RoleMappingController::class, 'create'])->name('competency.rolemapping.create');
    Route::post('/competency/rolemapping', [RoleMappingController::class, 'store'])->name('competency.rolemapping.store');
    Route::get('/competency/rolemapping/{roleMapping}', [RoleMappingController::class, 'show'])->name('competency.rolemapping.show');
    Route::get('/competency/rolemapping/{roleMapping}/edit', [RoleMappingController::class, 'edit'])->name('competency.rolemapping.edit');
    Route::put('/competency/rolemapping/{roleMapping}', [RoleMappingController::class, 'update'])->name('competency.rolemapping.update');
    Route::delete('/competency/rolemapping/{roleMapping}', [RoleMappingController::class, 'destroy'])->name('competency.rolemapping.destroy');

    // Gap Analysis
    Route::prefix('competency')->group(function () {
        Route::get('gapanalysis', [GapAnalysisController::class, 'index'])->name('competency.gapanalysis');
        Route::get('gapanalysis/create', [GapAnalysisController::class, 'create'])->name('competency.gapanalysis.create');
        Route::post('gapanalysis', [GapAnalysisController::class, 'store'])->name('competency.gapanalysis.store');
        Route::get('gapanalysis/{id}', [GapAnalysisController::class, 'show'])->name('competency.gapanalysis.show');
        Route::get('gapanalysis/{id}/edit', [GapAnalysisController::class, 'edit'])->name('competency.gapanalysis.edit');
        Route::put('gapanalysis/{id}', [GapAnalysisController::class, 'update'])->name('competency.gapanalysis.update');
        Route::delete('gapanalysis/{id}', [GapAnalysisController::class, 'destroy'])->name('competency.gapanalysis.destroy');
    });

    // Competency Gap Analysis Routes (Pre-Assessment Based)
    Route::prefix('competency')->group(function () {
        Route::get('gap-analysis', [\App\Modules\competency_management\Controllers\CompetencyGapAnalysisController::class, 'index'])->name('competency.gap-analysis');
        Route::get('gap-analysis/pre-assessment', [\App\Modules\competency_management\Controllers\CompetencyGapAnalysisController::class, 'preAssessment'])->name('competency.gap-analysis.pre-assessment');
        Route::get('gap-analysis/employee/{employeeId}', [\App\Modules\competency_management\Controllers\CompetencyGapAnalysisController::class, 'employeeDetail'])->name('competency.gap-analysis.employee');
        Route::post('gap-analysis/assign', [\App\Modules\competency_management\Controllers\CompetencyGapAnalysisController::class, 'assignAssessment'])->name('competency.gap-analysis.assign');
        
        // Skill Gap Management Routes (main assign route is defined above outside the prefix group)
        Route::get('competencies-list', [\App\Modules\competency_management\Controllers\CompetencyGapAnalysisController::class, 'getCompetenciesList'])->name('competency.list');
        Route::post('development-plans/create', [\App\Modules\competency_management\Controllers\CompetencyGapAnalysisController::class, 'createDevelopmentPlan'])->name('competency.development-plans.create');
        Route::post('assessments/schedule', [\App\Modules\competency_management\Controllers\CompetencyGapAnalysisController::class, 'scheduleAssessment'])->name('competency.assessments.schedule');
    });

    // ============================================================
    // =============== Training Management Routes =================
    // ============================================================
    Route::prefix('training')->group(function () {
    // Destroy a single training material (for route('training.materials.destroy'))
    Route::delete('catalog/{catalog}/material/{material}', [TrainingMaterialController::class, 'destroy'])->name('training.materials.destroy');
    // Edit a single training material (for route('training.materials.edit'))
    Route::get('catalog/{catalog}/material/{material}/edit', [TrainingMaterialController::class, 'edit'])->name('training.materials.edit');
    // Show a single training material (for route('training.materials.show'))
    Route::get('catalog/{catalog}/material/{material}', [TrainingMaterialController::class, 'show'])->name('training.materials.show');
    // Publish a training material (for route('training.materials.publish'))
    Route::post('catalog/{catalog}/material/{material}/publish', [TrainingMaterialController::class, 'publish'])->name('training.materials.publish');
    // Archive a training material (for route('training.materials.archive'))
    Route::post('catalog/{catalog}/material/{material}/archive', [TrainingMaterialController::class, 'archive'])->name('training.materials.archive');
        Route::resource('catalog', TrainingCatalogController::class)->names([
            'index' => 'training.catalog.index',
            'create' => 'training.catalog.create',
            'store' => 'training.catalog.store',
            'show' => 'training.catalog.show',
            'edit' => 'training.catalog.edit',
            'update' => 'training.catalog.update',
            'destroy' => 'training.catalog.destroy'
        ]);

        Route::get('catalog/{catalog}/detail', [TrainingCatalogController::class, 'detail'])->name('training.catalog.detail');

        Route::get('catalog/{catalog}/material/create', [TrainingCatalogController::class, 'createMaterial'])->name('training.material.create');

        // ✅ FIXED BLOCK BELOW
        Route::prefix('assign')->name('training.assign.')->group(function () {
            Route::get('/materials/{catalog}', function ($catalog) {
                try {
                    Log::info("Fetching materials for catalog: " . $catalog);

                    $materials = \App\Modules\training_management\Models\TrainingMaterial::where('training_catalog_id', $catalog)
                        ->where('status', 'published')
                        ->get(['id', 'lesson_title']);

                    Log::info("Found materials: " . $materials->count());

                    return response()->json($materials);
                } catch (\Exception $e) {
                    Log::error("Error fetching materials: " . $e->getMessage());
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            });
                // API route for employees (for training assignment)
                Route::get('/api-employees', function () {
                    try {
                        // Try to get employees from cache first
                        $employees = \Illuminate\Support\Facades\Cache::remember('external_employees', 60 * 10, function () {
                            $apiResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                                ->get('https://hr4.microfinancial-1.com/allemployees');
                            $employeesApi = $apiResponse->json();
                            // The API returns array directly, not wrapped in 'employees' key
                            return is_array($employeesApi) ? $employeesApi : ($employeesApi['employees'] ?? []);
                        });
                        return response()->json([
                            'success' => true,
                            'employees' => $employees
                        ]);
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Error fetching employees: " . $e->getMessage());
                        return response()->json([
                            'success' => false,
                            'error' => $e->getMessage(),
                            'employees' => []
                        ], 500);
                    }
                })->name('api-employees');

            Route::get('/', [\App\Modules\training_management\Controllers\TrainingAssignmentController::class, 'index'])->name('index');
            Route::get('/create', [\App\Modules\training_management\Controllers\TrainingAssignmentController::class, 'create'])->name('create');
            Route::post('/', [\App\Modules\training_management\Controllers\TrainingAssignmentController::class, 'store'])->name('store');
            Route::get('/{assignment}', [\App\Modules\training_management\Controllers\TrainingAssignmentController::class, 'show'])->name('show');
            Route::get('/{assignment}/edit', [\App\Modules\training_management\Controllers\TrainingAssignmentController::class, 'edit'])->name('edit');
            Route::put('/{assignment}', [\App\Modules\training_management\Controllers\TrainingAssignmentController::class, 'update'])->name('update');
            Route::delete('/{assignment}', [\App\Modules\training_management\Controllers\TrainingAssignmentController::class, 'destroy'])->name('destroy');
            Route::post('/{assignment}/activate', [\App\Modules\training_management\Controllers\TrainingAssignmentController::class, 'activate'])->name('activate');
            Route::post('/{assignment}/cancel', [\App\Modules\training_management\Controllers\TrainingAssignmentController::class, 'cancel'])->name('cancel');
        });

        // Grant Request Management Routes
        Route::prefix('grant-request')->name('training.grant-request.')->group(function () {
            Route::get('/', [\App\Modules\training_management\Controllers\GrantRequestController::class, 'index'])->name('index');
            Route::get('/get-related-assessments/{courseId}/{employeeId}', [\App\Modules\training_management\Controllers\GrantRequestController::class, 'getRelatedAssessments'])->name('get-related-assessments');
            Route::post('/assign-assessment', [\App\Modules\training_management\Controllers\GrantRequestController::class, 'assignAssessment'])->name('assign-assessment');
            Route::get('/{id}', [\App\Modules\training_management\Controllers\GrantRequestController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [\App\Modules\training_management\Controllers\GrantRequestController::class, 'approve'])->name('approve');
            Route::post('/{id}/deny', [\App\Modules\training_management\Controllers\GrantRequestController::class, 'deny'])->name('deny');
            Route::post('/bulk-action', [\App\Modules\training_management\Controllers\GrantRequestController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/api/stats', [\App\Modules\training_management\Controllers\GrantRequestController::class, 'getStats'])->name('stats');
        });
    });
});

// ============================================================
// =================== Profile & Debug Routes =================
// ============================================================
Route::get('/profile/security', fn() => view('profile.security-settings'))->name('profile.security');

// Clear employee cache route
Route::get('/clear-employee-cache', function () {
    \Illuminate\Support\Facades\Cache::forget('external_employees');
    return "Employee cache cleared! <a href='/debug-employee-api'>Test API again</a>";
})->middleware('auth');

Route::get('/profile/2fa-ess', function () {
    return view('profile.2fa-ess');
})->name('profile.2fa-ess');

// Debug route to check assessment assignments (temporary)
Route::get('/debug/assessments', function () {
    $assignments = DB::connection('learning_management')->table('assessment_assignments')->get();
    return response()->json([
        'count' => $assignments->count(),
        'assignments' => $assignments->toArray()
    ]);
});
