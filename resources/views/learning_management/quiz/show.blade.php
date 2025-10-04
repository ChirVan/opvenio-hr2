<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <!-- Breadcrumb -->
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('learning.assessment') }}" class="text-blue-600 hover:text-blue-700">
                                <i class='bx bx-book-open mr-1'></i>
                                Assessment Center
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class='bx bx-chevron-right text-gray-400'></i>
                                <a href="{{ route('learning.assessment.categories.show', $quiz->category) }}" class="ml-1 text-blue-600 hover:text-blue-700">
                                    {{ $quiz->category->category_name }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class='bx bx-chevron-right text-gray-400'></i>
                                <span class="ml-1 text-gray-900 font-medium">{{ $quiz->quiz_title }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $quiz->quiz_title }}</h1>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $quiz->status_badge }}">
                                {{ ucfirst($quiz->status) }}
                            </span>
                            <span class="text-gray-600">
                                <i class='bx bx-time mr-1'></i>
                                {{ $quiz->formatted_time_limit }}
                            </span>
                            <span class="text-gray-600">
                                <i class='bx bx-list-ol mr-1'></i>
                                {{ $quiz->total_questions }} questions
                            </span>
                            <span class="text-gray-600">
                                <i class='bx bx-award mr-1'></i>
                                {{ $quiz->total_points }} points
                            </span>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('learning.quiz.edit', $quiz) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-edit mr-2'></i>
                            Edit Quiz
                        </a>
                        <a href="{{ route('learning.assessment.categories.show', $quiz->category) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Back to Category
                        </a>
                    </div>
                </div>

                <!-- Quiz Details -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Quiz Information -->
                    <div class="lg:col-span-2">
                        <div class="bg-blue-50 rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Quiz Information</h2>
                            
                            @if($quiz->description)
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-700 mb-2">Description</h3>
                                    <p class="text-gray-600">{{ $quiz->description }}</p>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 mb-1">Competency</h3>
                                    <p class="text-gray-900">{{ $quiz->competency->competency_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 mb-1">Time Limit</h3>
                                    <p class="text-gray-900">{{ $quiz->formatted_time_limit }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 mb-1">Total Questions</h3>
                                    <p class="text-gray-900">{{ $quiz->total_questions }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-700 mb-1">Total Points</h3>
                                    <p class="text-gray-900">{{ $quiz->total_points }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
                            <div class="space-y-3">
                                @if($quiz->status === 'draft')
                                    <button onclick="toggleStatus({{ $quiz->id }})" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md inline-flex items-center justify-center">
                                        <i class='bx bx-check mr-2'></i>
                                        Publish Quiz
                                    </button>
                                @else
                                    <button onclick="toggleStatus({{ $quiz->id }})" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md inline-flex items-center justify-center">
                                        <i class='bx bx-edit mr-2'></i>
                                        Move to Draft
                                    </button>
                                @endif
                                
                                <a href="{{ route('learning.quiz.edit', $quiz) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center justify-center">
                                    <i class='bx bx-edit mr-2'></i>
                                    Edit Quiz
                                </a>
                                
                                <button onclick="deleteQuiz({{ $quiz->id }}, '{{ $quiz->quiz_title }}')" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md inline-flex items-center justify-center">
                                    <i class='bx bx-trash mr-2'></i>
                                    Delete Quiz
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Questions -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Questions ({{ $quiz->questions->count() }})</h2>
                    
                    @forelse($quiz->questions as $question)
                        <div class="bg-white rounded-lg p-4 mb-4 border border-gray-200">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-lg font-medium text-gray-900">Question {{ $question->question_order }}</h3>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $question->points }} {{ $question->points == 1 ? 'point' : 'points' }}
                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-gray-800">{{ $question->question }}</p>
                            </div>
                            
                            <div class="bg-green-50 border-l-4 border-green-400 p-3">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class='bx bx-check-circle text-green-400'></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700">
                                            <strong>Correct Answer:</strong> {{ $question->correct_answer }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class='bx bx-question-mark text-gray-300 text-6xl mb-4'></i>
                            <h3 class="text-lg font-semibold text-gray-600 mb-2">No Questions Yet</h3>
                            <p class="text-gray-500 mb-4">This quiz doesn't have any questions yet.</p>
                            <a href="{{ route('learning.quiz.edit', $quiz) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                                <i class='bx bx-plus mr-2'></i>
                                Add Questions
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleStatus(quizId) {
            if (confirm('Are you sure you want to change the quiz status?')) {
                fetch(`/learning/quiz/${quizId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    alert('An error occurred while updating the quiz status');
                });
            }
        }

        function deleteQuiz(quizId, quizTitle) {
            if (confirm(`Are you sure you want to delete "${quizTitle}"? This action cannot be undone.`)) {
                fetch(`/learning/quiz/${quizId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect_url || '{{ route("learning.assessment") }}';
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    alert('An error occurred while deleting the quiz');
                });
            }
        }
    </script>
</x-app-layout>