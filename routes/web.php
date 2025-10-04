<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modules\competency_management\Controllers\CompetencyFrameworkController;
use App\Modules\competency_management\Controllers\CompetencyController;
use App\Modules\competency_management\Controllers\RoleMappingController;
use App\Modules\competency_management\Controllers\GapAnalysisController;
use App\Modules\competency_management\Models\CompetencyFramework;
use App\Modules\training_management\Controllers\TrainingCatalogController;
use App\Modules\learning_management\Controllers\AssessmentCategoryController;
use App\Http\Controllers\Auth\TwoFactorController;


Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('index');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Two-factor authentication info page
    Route::get('/auth/two-factor-info', [TwoFactorController::class, 'show'])->name('two-factor.info');

    // Competency management routes with controller
    Route::get('/competency/frameworks', [CompetencyFrameworkController::class, 'index'])->name('competency.frameworks');
    Route::get('/competency/frameworks/create', [CompetencyFrameworkController::class, 'create'])->name('competency.frameworks.create');
    Route::post('/competency/frameworks', [CompetencyFrameworkController::class, 'store'])->name('competency.frameworks.store');
    Route::get('/competency/frameworks/{framework}', [CompetencyFrameworkController::class, 'show'])->name('competency.frameworks.show');
    Route::get('/competency/frameworks/{framework}/edit', [CompetencyFrameworkController::class, 'edit'])->name('competency.frameworks.edit');
    Route::put('/competency/frameworks/{framework}', [CompetencyFrameworkController::class, 'update'])->name('competency.frameworks.update');
    Route::delete('/competency/frameworks/{framework}', [CompetencyFrameworkController::class, 'destroy'])->name('competency.frameworks.destroy');

    // NEW: Competency routes
    Route::get('/competency/competencies', [CompetencyController::class, 'index'])->name('competency.competencies');
    Route::get('/competency/competencies/create', [CompetencyController::class, 'create'])->name('competency.competencies.create');
    Route::post('/competency/competencies', [CompetencyController::class, 'store'])->name('competency.competencies.store');
    Route::get('/competency/competencies/{competency}', [CompetencyController::class, 'show'])->name('competency.competencies.show');
    Route::get('/competency/competencies/{competency}/edit', [CompetencyController::class, 'edit'])->name('competency.competencies.edit');
    Route::put('/competency/competencies/{competency}', [CompetencyController::class, 'update'])->name('competency.competencies.update');
    Route::delete('/competency/competencies/{competency}', [CompetencyController::class, 'destroy'])->name('competency.competencies.destroy');


    // With these NEW role mapping routes:
    Route::get('/competency/rolemapping', [RoleMappingController::class, 'index'])->name('competency.rolemapping');
    Route::get('/competency/rolemapping/create', [RoleMappingController::class, 'create'])->name('competency.rolemapping.create');
    Route::post('/competency/rolemapping', [RoleMappingController::class, 'store'])->name('competency.rolemapping.store');
    Route::get('/competency/rolemapping/{roleMapping}', [RoleMappingController::class, 'show'])->name('competency.rolemapping.show');
    Route::get('/competency/rolemapping/{roleMapping}/edit', [RoleMappingController::class, 'edit'])->name('competency.rolemapping.edit');
    Route::put('/competency/rolemapping/{roleMapping}', [RoleMappingController::class, 'update'])->name('competency.rolemapping.update');
    Route::delete('/competency/rolemapping/{roleMapping}', [RoleMappingController::class, 'destroy'])->name('competency.rolemapping.destroy');

    Route::prefix('competency')->group(function () {
    Route::get('gapanalysis', [GapAnalysisController::class, 'index'])->name('competency.gapanalysis');
    Route::get('gapanalysis/create', [GapAnalysisController::class, 'create'])->name('competency.gapanalysis.create');
    Route::post('gapanalysis', [GapAnalysisController::class, 'store'])->name('competency.gapanalysis.store');
    Route::get('gapanalysis/{id}', [GapAnalysisController::class, 'show'])->name('competency.gapanalysis.show');
    Route::get('gapanalysis/{id}/edit', [GapAnalysisController::class, 'edit'])->name('competency.gapanalysis.edit');
    Route::put('gapanalysis/{id}', [GapAnalysisController::class, 'update'])->name('competency.gapanalysis.update');
    Route::delete('gapanalysis/{id}', [GapAnalysisController::class, 'destroy'])->name('competency.gapanalysis.destroy');
});

    // Training Management Routes
    Route::prefix('training')->group(function () {
        Route::resource('catalog', TrainingCatalogController::class)->names([
            'index' => 'training.catalog.index',
            'create' => 'training.catalog.create',
            'store' => 'training.catalog.store',
            'show' => 'training.catalog.show',
            'edit' => 'training.catalog.edit',
            'update' => 'training.catalog.update',
            'destroy' => 'training.catalog.destroy',
        ]);
        
        // Training catalog detail page with materials
        Route::get('catalog/{catalog}/detail', [TrainingCatalogController::class, 'detail'])->name('training.catalog.detail');
        
        // Training materials routes
        Route::get('catalog/{catalog}/material/create', [TrainingCatalogController::class, 'createMaterial'])->name('training.material.create');
        Route::prefix('catalog/{catalog}/materials')->group(function () {
            Route::get('/', [\App\Modules\training_management\Controllers\TrainingMaterialController::class, 'index'])->name('training.materials.index');
            Route::post('/', [\App\Modules\training_management\Controllers\TrainingMaterialController::class, 'store'])->name('training.materials.store');
            Route::get('/{material}', [\App\Modules\training_management\Controllers\TrainingMaterialController::class, 'show'])->name('training.materials.show');
            Route::get('/{material}/edit', [\App\Modules\training_management\Controllers\TrainingMaterialController::class, 'edit'])->name('training.materials.edit');
            Route::put('/{material}', [\App\Modules\training_management\Controllers\TrainingMaterialController::class, 'update'])->name('training.materials.update');
            Route::delete('/{material}', [\App\Modules\training_management\Controllers\TrainingMaterialController::class, 'destroy'])->name('training.materials.destroy');
            Route::post('/{material}/publish', [\App\Modules\training_management\Controllers\TrainingMaterialController::class, 'publish'])->name('training.materials.publish');
            Route::post('/{material}/archive', [\App\Modules\training_management\Controllers\TrainingMaterialController::class, 'archive'])->name('training.materials.archive');
            Route::post('/draft', [\App\Modules\training_management\Controllers\TrainingMaterialController::class, 'saveDraft'])->name('training.materials.draft');
        });
        
        // Training Assignment routes
        Route::prefix('assign')->name('training.assign.')->group(function () {
            // AJAX routes (must come first to avoid conflicts with resource routes)
            Route::get('/materials/{catalog}', function($catalog) {
                try {
                    \Log::info("Fetching materials for catalog: " . $catalog);
                    
                    $materials = \App\Modules\training_management\Models\TrainingMaterial::where('training_catalog_id', $catalog)
                        ->where('status', 'published')
                        ->get(['id', 'lesson_title']);
                    
                    \Log::info("Found materials: " . $materials->count());
                    
                    return response()->json($materials);
                } catch (\Exception $e) {
                    \Log::error("Error fetching materials: " . $e->getMessage());
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            })->name('materials');
            
            Route::get('/gap-analysis-employees', function() {
                try {
                    \Log::info("Fetching employees from gap_analyses with proper relationships");
                    
                    // Use the GapAnalysis model with proper eager loading
                    $gapAnalyses = \App\Modules\competency_management\Models\GapAnalysis::with(['competency'])
                        ->join('competency_management.employees', 'gap_analyses.employee_id', '=', 'employees.id')
                        ->leftJoin('competency_management.competencies', 'gap_analyses.competency_id', '=', 'competencies.id')
                        ->select([
                            'gap_analyses.id',
                            'gap_analyses.employee_id',
                            'employees.firstname as employee_firstname',
                            'employees.lastname as employee_lastname', 
                            'employees.job_role',
                            'gap_analyses.framework',
                            'gap_analyses.proficiency_level',
                            'gap_analyses.notes',
                            'gap_analyses.competency_id',
                            'competencies.competency_name'
                        ])
                        ->distinct('gap_analyses.employee_id')
                        ->orderBy('employees.lastname')
                        ->orderBy('employees.firstname')
                        ->get();
                    
                    // Map the results with proper null handling
                    $employees = $gapAnalyses->map(function($gap) {
                        return [
                            'id' => $gap->employee_id,
                            'employee_firstname' => $gap->employee_firstname,
                            'employee_lastname' => $gap->employee_lastname,
                            'job_role' => $gap->job_role,
                            'competency_name' => $gap->competency_name ?: 'No Competency',
                            'framework' => $gap->framework ?: 'No Framework',
                            'proficiency_level' => $gap->proficiency_level
                        ];
                    });
                    
                    \Log::info("Found " . $employees->count() . " unique employees in gap analysis");
                    
                    return response()->json($employees);
                    
                } catch (\Exception $e) {
                    \Log::error("Model query failed: " . $e->getMessage());
                    
                    // Fallback: Try raw query with proper joins
                    try {
                        $employees = \DB::connection('competency_management')
                            ->table('gap_analyses')
                            ->join('employees', 'gap_analyses.employee_id', '=', 'employees.id')
                            ->leftJoin('competencies', 'gap_analyses.competency_id', '=', 'competencies.id')
                            ->select([
                                'gap_analyses.employee_id as id',
                                'employees.firstname as employee_firstname',
                                'employees.lastname as employee_lastname',
                                'employees.job_role',
                                'gap_analyses.framework',
                                'gap_analyses.proficiency_level',
                                'competencies.competency_name'
                            ])
                            ->distinct()
                            ->orderBy('employees.lastname')
                            ->orderBy('employees.firstname')
                            ->get()
                            ->map(function($employee) {
                                return [
                                    'id' => $employee->id,
                                    'employee_firstname' => $employee->employee_firstname,
                                    'employee_lastname' => $employee->employee_lastname,
                                    'job_role' => $employee->job_role,
                                    'competency_name' => $employee->competency_name ?: 'No Competency',
                                    'framework' => $employee->framework ?: 'No Framework',
                                    'proficiency_level' => $employee->proficiency_level
                                ];
                            });
                        
                        \Log::info("Fallback query found " . $employees->count() . " employees");
                        return response()->json($employees);
                        
                    } catch (\Exception $e2) {
                        \Log::error("Fallback query also failed: " . $e2->getMessage());
                        
                        // Last resort: return empty array so frontend doesn't break
                        return response()->json([]);
                    }
                }
            })->name('gap-analysis-employees');
            
            // Controller routes (must come after AJAX routes)
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
    });
});

// Learning Management Routes
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::prefix('learning')->name('learning.')->group(function () {
        Route::get('/assessment', [AssessmentCategoryController::class, 'index'])->name('assessment');
        
        // Quiz Routes
        Route::get('/quiz', [\App\Modules\learning_management\Controllers\QuizController::class, 'create'])->name('quiz');
        Route::post('/quiz', [\App\Modules\learning_management\Controllers\QuizController::class, 'store'])->name('quiz.store');
        Route::get('/quiz/{quiz}', [\App\Modules\learning_management\Controllers\QuizController::class, 'show'])->name('quiz.show');
        Route::get('/quiz/{quiz}/edit', [\App\Modules\learning_management\Controllers\QuizController::class, 'edit'])->name('quiz.edit');
        Route::put('/quiz/{quiz}', [\App\Modules\learning_management\Controllers\QuizController::class, 'update'])->name('quiz.update');
        Route::delete('/quiz/{quiz}', [\App\Modules\learning_management\Controllers\QuizController::class, 'destroy'])->name('quiz.destroy');
        Route::patch('/quiz/{quiz}/toggle-status', [\App\Modules\learning_management\Controllers\QuizController::class, 'toggleStatus'])->name('quiz.toggle-status');
        
        // Assessment Category AJAX Routes (must come before resource routes)
        Route::post('/assessment/categories/validate-slug', [AssessmentCategoryController::class, 'validateSlug'])->name('assessment.categories.validate-slug');
        Route::patch('/assessment/categories/{category}/toggle-status', [AssessmentCategoryController::class, 'toggleStatus'])->name('assessment.categories.toggle-status');
        Route::get('/assessment/categories/api', [AssessmentCategoryController::class, 'getCategories'])->name('assessment.categories.api');
        Route::get('/assessment/categories/{categorySlug}/quizzes', [AssessmentCategoryController::class, 'getCategoryQuizzes'])->name('assessment.categories.quizzes');
        
        // Assessment Category Resource Routes
        Route::resource('assessment/categories', AssessmentCategoryController::class)->names([
            'index' => 'assessment.categories.index',
            'create' => 'assessment.categories.create',
            'store' => 'assessment.categories.store',
            'show' => 'assessment.categories.show',
            'edit' => 'assessment.categories.edit',
            'update' => 'assessment.categories.update',
            'destroy' => 'assessment.categories.destroy'
        ]);
    });

    // Keep old assessment-center routes for backward compatibility (redirect to new routes)
    Route::prefix('assessment-center')->name('assessment-center.')->group(function () {
        // Assessment Category AJAX Routes (must come before resource routes)
        Route::post('/categories/validate-slug', [AssessmentCategoryController::class, 'validateSlug'])->name('categories.validate-slug');
        Route::patch('/categories/{category}/toggle-status', [AssessmentCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        Route::get('/categories/api', [AssessmentCategoryController::class, 'getCategories'])->name('categories.api');
        
        // Assessment Category Resource Routes
        Route::resource('categories', AssessmentCategoryController::class)->except(['index']);
        
        // Assessment Center Index Route
        Route::get('/', [AssessmentCategoryController::class, 'index'])->name('index');
    });
});