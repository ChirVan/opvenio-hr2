<?php

namespace App\Modules\learning_management\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\learning_management\Models\Quiz;
use App\Modules\learning_management\Models\QuizQuestion;
use App\Modules\learning_management\Models\AssessmentCategory;
use App\Modules\learning_management\Requests\StoreQuizRequest;
use App\Modules\learning_management\Requests\UpdateQuizRequest;
use App\Modules\competency_management\Models\Competency;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class QuizController extends Controller
{
    /**
     * Display a listing of quizzes.
     */
    public function index(Request $request): View
    {
        $quizzes = Quiz::with(['category', 'competency', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('learning_management.quiz.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create(Request $request): View
    {
        // Get category if specified
        $category = null;
        if ($request->has('category_id')) {
            $category = AssessmentCategory::findOrFail($request->category_id);
        }

        // Get active competencies
        $competencies = Competency::active()
            ->orderBy('competency_name')
            ->get(['id', 'competency_name', 'description']);

        return view('learning_management.quiz', compact('competencies', 'category'));
    }

    /**
     * Store a newly created quiz in storage.
     */
    public function store(StoreQuizRequest $request): JsonResponse
    {
        try {
            DB::connection('learning_management')->beginTransaction();

            // Determine status based on action
            $status = $request->action === 'publish' ? 'published' : 'draft';

            // Debug: Log what we're receiving
            Log::info('Quiz Store Debug', [
                'category_id_from_request' => $request->category_id,
                'all_request_data' => $request->all(),
                'has_category_id' => $request->has('category_id'),
                'category_id_filled' => $request->filled('category_id')
            ]);

            // Get the category ID, with proper fallback
            $categoryId = $request->filled('category_id') ? $request->category_id : null;
            
            if (!$categoryId) {
                $firstCategory = AssessmentCategory::first();
                $categoryId = $firstCategory ? $firstCategory->id : 1;
                Log::info('Quiz Store: Using fallback category', ['fallback_category_id' => $categoryId]);
            } else {
                Log::info('Quiz Store: Using provided category', ['provided_category_id' => $categoryId]);
            }

            // Create the quiz
            $quiz = Quiz::create([
                'quiz_title' => $request->quiz_title,
                'category_id' => $categoryId,
                'competency_id' => $request->competency,
                'description' => $request->description,
                'time_limit' => $request->time_limit ?? 30,
                'status' => $status,
                'created_by' => Auth::id() ?? 1,
            ]);

            // Create questions
            foreach ($request->questions as $index => $questionData) {
                QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question' => $questionData['question'],
                    'correct_answer' => $questionData['answer'],
                    'points' => $questionData['points'],
                    'question_order' => $index + 1,
                    'question_type' => 'identification',
                ]);
            }

            // Update quiz totals
            $quiz->updateTotals();

            DB::connection('learning_management')->commit();

            return response()->json([
                'success' => true,
                'message' => $status === 'published' 
                    ? 'Quiz created and published successfully!' 
                    : 'Quiz saved as draft successfully!',
                'data' => [
                    'id' => $quiz->id,
                    'title' => $quiz->quiz_title,
                    'status' => $quiz->status,
                    'redirect_url' => route('learning.quiz.show', $quiz->id)
                ]
            ], 201);

        } catch (Exception $e) {
            DB::connection('learning_management')->rollBack();
            Log::error('Quiz creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create quiz. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Display the specified quiz.
     */
    public function show(Quiz $quiz): View
    {
        $quiz->load(['questions', 'category', 'competency']);

        return view('learning_management.quiz.show', compact('quiz'));
    }

    /**
     * Show the form for editing the specified quiz.
     */
    public function edit(Quiz $quiz): View
    {
        $quiz->load(['questions']);
        
        $competencies = Competency::active()
            ->orderBy('competency_name')
            ->get(['id', 'competency_name', 'description']);

        return view('learning_management.quiz.edit', compact('quiz', 'competencies'));
    }

    /**
     * Update the specified quiz in storage.
     */
    public function update(UpdateQuizRequest $request, Quiz $quiz)
    {
        try {
            DB::connection('learning_management')->beginTransaction();

            // Determine status based on action
            $status = $request->action === 'publish' ? 'published' : 'draft';

            // Update the quiz
            $quiz->update([
                'quiz_title' => $request->quiz_title,
                'competency_id' => $request->competency,
                'description' => $request->description,
                'time_limit' => $request->time_limit ?? 30,
                'status' => $status,
            ]);

            // Delete existing questions and create new ones
            $quiz->questions()->delete();

            // Create updated questions
            foreach ($request->questions as $index => $questionData) {
                QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question' => $questionData['question'],
                    'correct_answer' => $questionData['answer'],
                    'points' => $questionData['points'],
                    'question_order' => $index + 1,
                    'question_type' => 'identification',
                ]);
            }

            // Update quiz totals
            $quiz->updateTotals();

            DB::connection('learning_management')->commit();

            // Check if this is an AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Quiz updated successfully!',
                    'data' => [
                        'id' => $quiz->id,
                        'title' => $quiz->quiz_title,
                        'status' => $quiz->status,
                        'redirect_url' => route('learning.quiz.show', $quiz->id)
                    ]
                ]);
            }

            // For regular form submissions, redirect back to the assessment page
            return redirect()->route('learning.assessment')
                ->with('success', 'Quiz updated successfully!');

        } catch (Exception $e) {
            DB::connection('learning_management')->rollBack();
            
            // Check if this is an AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update quiz. Please try again.',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }

            // For regular form submissions, redirect back with error
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update quiz. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Remove the specified quiz from storage.
     */
    public function destroy(Quiz $quiz): JsonResponse
    {
        try {
            Log::info('Quiz deletion started', [
                'quiz_id' => $quiz->id,
                'quiz_title' => $quiz->quiz_title,
                'user_id' => Auth::id(),
                'request' => request()->all()
            ]);

            DB::connection('learning_management')->beginTransaction();

            // Check if quiz has related data that might prevent deletion
            $questionCount = $quiz->questions()->count();
            Log::info('Quiz has questions', ['count' => $questionCount]);

            $quizTitle = $quiz->quiz_title;
            
            // Try to delete the quiz
            $deleted = $quiz->delete();
            Log::info('Quiz deletion attempt', ['deleted' => $deleted]);

            DB::connection('learning_management')->commit();

            Log::info('Quiz deleted successfully', [
                'quiz_title' => $quizTitle,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Quiz '{$quizTitle}' has been deleted successfully."
            ]);

        } catch (Exception $e) {
            DB::connection('learning_management')->rollBack();
            
            Log::error('Quiz deletion failed', [
                'quiz_id' => $quiz->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request' => request()->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete quiz. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'debug_info' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage()
                ] : null
            ], 500);
        }
    }

    /**
     * Toggle the status of a quiz.
     */
    public function toggleStatus(Quiz $quiz): JsonResponse
    {
        try {
            $newStatus = $quiz->status === 'published' ? 'draft' : 'published';
            
            // Check if quiz can be published
            if ($newStatus === 'published' && !$quiz->canBePublished()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quiz cannot be published. Please ensure it has at least one question and a title.'
                ], 400);
            }

            $quiz->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => "Quiz status changed to {$newStatus}.",
                'data' => [
                    'status' => $quiz->status,
                    'status_badge' => $quiz->status_badge
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quiz status.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}