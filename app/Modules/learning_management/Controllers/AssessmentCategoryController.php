<?php

namespace App\Modules\learning_management\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\learning_management\Models\AssessmentCategory;
use App\Modules\learning_management\Requests\StoreAssessmentCategoryRequest;
use App\Modules\learning_management\Requests\UpdateAssessmentCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class AssessmentCategoryController extends Controller
{
    /**
     * Display a listing of the assessment categories.
     */
    public function index(Request $request): View
    {
        $categories = AssessmentCategory::where('is_active', true)
            ->withCount(['quizzes' => function($query) {
                $query->where('status', '!=', 'draft');
            }])
            ->orderBy('category_name')
            ->paginate(12);

        // Map the count to the expected property name
        $categories->getCollection()->transform(function ($category) {
            $category->assessments_count = $category->quizzes_count;
            return $category;
        });

        // Determine which view to return based on the route
        $routeName = $request->route()->getName();
        
        if ($routeName === 'learning.assessment') {
            // Return the learning management assessment view
            return view('learning_management.assessment', compact('categories'));
        }
        
        // Default to learning_management index view
        return view('learning_management.index', compact('categories'));
    }

    /**
     * Show the form for creating a new assessment category.
     */
    public function create(): View
    {
        return view('learning_management.categories.create');
    }

    /**
     * Store a newly created assessment category in storage.
     */
    public function store(StoreAssessmentCategoryRequest $request): JsonResponse
    {
        try {
            DB::connection('learning_management')->beginTransaction();

            $category = AssessmentCategory::create([
                'category_name' => $request->category_name,
                'category_slug' => $request->category_slug,
                'category_icon' => $request->category_icon,
                'description' => $request->description,
                'color_theme' => $request->color_theme,
                'is_active' => $request->is_active ?? true
            ]);

            DB::connection('learning_management')->commit();

            return response()->json([
                'success' => true,
                'message' => 'Assessment category created successfully!',
                'data' => [
                    'id' => $category->id,
                    'name' => $category->category_name,
                    'slug' => $category->category_slug,
                    'redirect_url' => route('learning.assessment.categories.show', $category->id)
                ]
            ], 201);

        } catch (Exception $e) {
            DB::connection('learning_management')->rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create assessment category. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Display the specified assessment category.
     */
    public function show(AssessmentCategory $category): View
    {
        try {
            // Load assessments relationship if table exists
            $category->load(['assessments' => function($query) {
                $query->with('questions')->orderBy('title');
            }]);
        } catch (Exception $e) {
            // Skip loading assessments if table doesn't exist yet
            Log::info('Assessments table not found in show method', ['error' => $e->getMessage()]);
        }

        return view('learning_management.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified assessment category.
     */
    public function edit(AssessmentCategory $category): View
    {
        return view('learning_management.categories.edit', compact('category'));
    }

    /**
     * Update the specified assessment category in storage.
     */
    public function update(UpdateAssessmentCategoryRequest $request, AssessmentCategory $category): JsonResponse
    {
        try {
            DB::connection('learning_management')->beginTransaction();

            $category->update([
                'category_name' => $request->category_name,
                'category_slug' => $request->category_slug,
                'category_icon' => $request->category_icon,
                'description' => $request->description,
                'color_theme' => $request->color_theme,
                'is_active' => $request->is_active
            ]);

            DB::connection('learning_management')->commit();

            return response()->json([
                'success' => true,
                'message' => 'Assessment category updated successfully!',
                'data' => [
                    'id' => $category->id,
                    'name' => $category->category_name,
                    'slug' => $category->category_slug,
                    'redirect_url' => route('learning.assessment.categories.show', $category->id)
                ]
            ]);

        } catch (Exception $e) {
            DB::connection('learning_management')->rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update assessment category. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Remove the specified assessment category from storage.
     */
    public function destroy(AssessmentCategory $category): JsonResponse
    {
        // Log deletion attempt for debugging
        Log::info('Delete category attempt', ['category_id' => $category->id, 'category_name' => $category->category_name]);
        
        try {
            // Skip assessment count check until assessments table is implemented
            // This will be re-enabled when we create the assessments functionality

            DB::connection('learning_management')->beginTransaction();

            $categoryName = $category->category_name;
            $category->delete();

            DB::connection('learning_management')->commit();

            return response()->json([
                'success' => true,
                'message' => "Assessment category '{$categoryName}' has been deleted successfully.",
                'redirect_url' => route('learning.assessment')
            ]);

        } catch (Exception $e) {
            DB::connection('learning_management')->rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete assessment category. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Toggle the active status of an assessment category.
     */
    public function toggleStatus(AssessmentCategory $category): JsonResponse
    {
        try {
            DB::connection('learning_management')->beginTransaction();

            $category->update([
                'is_active' => !$category->is_active
            ]);

            DB::connection('learning_management')->commit();

            $status = $category->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "Assessment category has been {$status} successfully.",
                'data' => [
                    'is_active' => $category->is_active,
                    'status_text' => $category->is_active ? 'Active' : 'Inactive'
                ]
            ]);

        } catch (Exception $e) {
            DB::connection('learning_management')->rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category status. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get quizzes for a specific category (AJAX endpoint).
     */
    public function getCategoryQuizzes(Request $request, $categorySlug): JsonResponse
    {
        try {
            $category = AssessmentCategory::where('category_slug', $categorySlug)
                ->where('is_active', true)
                ->firstOrFail();

            $quizzes = $category->quizzes()
                ->where('status', '!=', 'draft')
                ->with(['competency:id,competency_name'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($quiz) {
                    return [
                        'id' => $quiz->id,
                        'quiz_title' => $quiz->quiz_title,
                        'description' => $quiz->description,
                        'competency_name' => $quiz->competency->competency_name ?? 'N/A',
                        'total_questions' => $quiz->total_questions,
                        'total_points' => $quiz->total_points,
                        'time_limit' => $quiz->time_limit,
                        'status' => $quiz->status,
                        'created_at' => $quiz->created_at->format('M d, Y'),
                        'show_url' => route('learning.quiz.show', $quiz->id)
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->category_name,
                        'slug' => $category->category_slug,
                        'description' => $category->description
                    ],
                    'quizzes' => $quizzes
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found or error fetching quizzes.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 404);
        }
    }

    /**
     * Get assessment categories for API/AJAX requests.
     */
    public function getCategories(Request $request): JsonResponse
    {
        $query = AssessmentCategory::query();

        // Filter by active status if requested
        if ($request->has('active_only')) {
            $query->where('is_active', true);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('category_name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'category_name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if (in_array($sortBy, ['category_name', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('category_name', 'asc');
        }

        // Pagination
        $perPage = min($request->get('per_page', 12), 50); // Max 50 items per page
        $categories = $query->paginate($perPage);

        // Add quiz counts to each category
        $categories->getCollection()->transform(function ($category) {
            $category->assessments_count = $category->quizzes()->where('status', '!=', 'draft')->count();
            return $category;
        });

        return response()->json([
            'success' => true,
            'data' => $categories->items(),
            'pagination' => [
                'total' => $categories->total(),
                'per_page' => $categories->perPage(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem()
            ]
        ]);
    }

    /**
     * Validate category slug uniqueness for AJAX requests.
     */
    public function validateSlug(Request $request): JsonResponse
    {
        $slug = $request->get('slug');
        $categoryId = $request->get('category_id'); // For updates

        if (empty($slug)) {
            return response()->json([
                'valid' => false,
                'message' => 'Slug is required.'
            ]);
        }

        $query = AssessmentCategory::where('category_slug', $slug);
        
        if ($categoryId) {
            $query->where('id', '!=', $categoryId);
        }

        $exists = $query->exists();

        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'This slug is already taken.' : 'Slug is available.'
        ]);
    }
}