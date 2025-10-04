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
                                <a href="{{ route('learning.quiz.show', $quiz) }}" class="ml-1 text-blue-600 hover:text-blue-700">
                                    {{ $quiz->quiz_title }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class='bx bx-chevron-right text-gray-400'></i>
                                <span class="ml-1 text-gray-900 font-medium">Edit</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <i class='bx bx-check-circle mr-2'></i>
                            </div>
                            <div>
                                <p class="font-bold">Success!</p>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <i class='bx bx-error-circle mr-2'></i>
                            </div>
                            <div>
                                <p class="font-bold">Please correct the following errors:</p>
                                <ul class="text-sm mt-2 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Quiz</h1>
                        <p class="text-gray-600 mt-2">Update the identification-based quiz for {{ $quiz->category->category_name }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('learning.quiz.show', $quiz) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Back to Quiz
                        </a>
                    </div>
                </div>

                @php
                    $currentQuestionCount = old('questions') ? count(old('questions')) : $quiz->questions->count();
                @endphp

                <!-- Form -->
                <form method="POST" action="{{ route('learning.quiz.update', $quiz) }}" class="space-y-8" id="quizForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Hidden category_id -->
                    <input type="hidden" name="category_id" value="{{ $quiz->category_id }}">

                    <!-- Quiz Details Section -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quiz Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Quiz Title -->
                            <div class="md:col-span-2">
                                <label for="quiz_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Quiz Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="quiz_title" name="quiz_title" value="{{ old('quiz_title', $quiz->quiz_title) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quiz_title') border-red-500 @enderror"
                                       placeholder="Enter quiz title..." required>
                                @error('quiz_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Competency Selection -->
                            <div>
                                <label for="competency" class="block text-sm font-medium text-gray-700 mb-2">
                                    Related Competency <span class="text-red-500">*</span>
                                </label>
                                <select id="competency" name="competency" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('competency') border-red-500 @enderror" required>
                                    <option value="">Select a competency...</option>
                                    @foreach($competencies as $competency)
                                        <option value="{{ $competency->id }}" 
                                                {{ old('competency', $quiz->competency_id) == $competency->id ? 'selected' : '' }}>
                                            {{ $competency->competency_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('competency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Time Limit -->
                            <div>
                                <label for="time_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                    Time Limit (minutes)
                                </label>
                                <input type="number" id="time_limit" name="time_limit" value="{{ old('time_limit', $quiz->time_limit) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('time_limit') border-red-500 @enderror"
                                       placeholder="0" min="0">
                                <p class="text-sm text-gray-600 mt-1">Leave empty or 0 for no time limit</p>
                                @error('time_limit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea id="description" name="description" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                          placeholder="Brief description of this quiz...">{{ old('description', $quiz->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Questions Section -->
                    <div class="bg-green-50 rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">Questions</h2>
                            <button type="button" id="addQuestionBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                                <i class='bx bx-plus mr-2'></i>
                                Add Question
                            </button>
                        </div>

                        <div id="questionsContainer" class="space-y-6">
                            @forelse($quiz->questions as $index => $question)
                                <div class="question-item bg-white p-6 rounded-lg border border-gray-200" data-question-index="{{ $index }}">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-medium text-gray-900">Question {{ $index + 1 }}</h3>
                                        <button type="button" class="remove-question text-red-600 hover:text-red-800">
                                            <i class='bx bx-trash text-xl'></i>
                                        </button>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Question Text <span class="text-red-500">*</span>
                                            </label>
                                            <textarea name="questions[{{ $index }}][question]" rows="3" required
                                                      class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('questions.'.$index.'.question') border-red-500 @enderror"
                                                      placeholder="Enter your identification question...">{{ old('questions.'.$index.'.question', $question->question) }}</textarea>
                                            @error('questions.'.$index.'.question')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Correct Answer <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" name="questions[{{ $index }}][answer]" value="{{ old('questions.'.$index.'.answer', $question->correct_answer) }}" required
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('questions.'.$index.'.answer') border-red-500 @enderror"
                                                   placeholder="Enter the correct answer...">
                                            @error('questions.'.$index.'.answer')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Points
                                            </label>
                                            <input type="number" name="questions[{{ $index }}][points]" value="{{ old('questions.'.$index.'.points', $question->points) }}" min="1"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('questions.'.$index.'.points') border-red-500 @enderror"
                                                   placeholder="5">
                                            @error('questions.'.$index.'.points')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <!-- This will show if no questions exist -->
                            @endforelse
                        </div>

                        <div id="noQuestionsMessage" class="text-center py-8 text-gray-500 {{ $currentQuestionCount > 0 ? 'hidden' : '' }}">
                            <i class='bx bx-help-circle text-4xl mb-2'></i>
                            <p>No questions added yet. Click "Add Question" to create your first question.</p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <i class='bx bx-info-circle mr-1'></i>
                            Questions: <span id="questionCount">{{ $currentQuestionCount }}</span>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="submit" name="action" value="draft" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                                <i class='bx bx-save mr-2'></i>
                                Save as Draft
                            </button>
                            <button type="submit" name="action" value="publish" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                                <i class='bx bx-check mr-2'></i>
                                Update & Publish
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let questionIndex = {{ $currentQuestionCount }};
            const questionsContainer = document.getElementById('questionsContainer');
            const addQuestionBtn = document.getElementById('addQuestionBtn');
            const noQuestionsMessage = document.getElementById('noQuestionsMessage');
            const questionCountSpan = document.getElementById('questionCount');

            // Add question functionality
            addQuestionBtn.addEventListener('click', function() {
                const questionHtml = `
                    <div class="question-item bg-white p-6 rounded-lg border border-gray-200" data-question-index="${questionIndex}">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Question ${questionIndex + 1}</h3>
                            <button type="button" class="remove-question text-red-600 hover:text-red-800">
                                <i class='bx bx-trash text-xl'></i>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Question Text <span class="text-red-500">*</span>
                                </label>
                                <textarea name="questions[${questionIndex}][question]" rows="3" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Enter your identification question..."></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Correct Answer <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="questions[${questionIndex}][answer]" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Enter the correct answer...">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Points
                                </label>
                                <input type="number" name="questions[${questionIndex}][points]" value="5" min="1"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="5">
                            </div>
                        </div>
                    </div>
                `;
                
                questionsContainer.insertAdjacentHTML('beforeend', questionHtml);
                questionIndex++;
                updateQuestionNumbers();
                updateQuestionCount();
                hideNoQuestionsMessage();
            });

            // Remove question functionality
            questionsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-question')) {
                    e.target.closest('.question-item').remove();
                    updateQuestionNumbers();
                    updateQuestionCount();
                    checkNoQuestionsMessage();
                }
            });

            // Update question numbers
            function updateQuestionNumbers() {
                const questions = questionsContainer.querySelectorAll('.question-item');
                questions.forEach((question, index) => {
                    const heading = question.querySelector('h3');
                    heading.textContent = `Question ${index + 1}`;
                    question.setAttribute('data-question-index', index);
                });
            }

            // Update question count
            function updateQuestionCount() {
                const count = questionsContainer.querySelectorAll('.question-item').length;
                questionCountSpan.textContent = count;
            }

            // Hide/show no questions message
            function hideNoQuestionsMessage() {
                noQuestionsMessage.classList.add('hidden');
            }

            function checkNoQuestionsMessage() {
                const count = questionsContainer.querySelectorAll('.question-item').length;
                if (count === 0) {
                    noQuestionsMessage.classList.remove('hidden');
                }
            }

            // Form submission with validation
            document.getElementById('quizForm').addEventListener('submit', function(e) {
                const questions = questionsContainer.querySelectorAll('.question-item');
                
                if (questions.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one question before saving the quiz.');
                    return false;
                }

                // Show loading state on the clicked submit button only
                const clickedButton = e.submitter;
                if (clickedButton && clickedButton.type === 'submit') {
                    clickedButton.disabled = true;
                    const originalText = clickedButton.innerHTML;
                    const actionValue = clickedButton.value;
                    
                    // Preserve the action value by adding a hidden input
                    const hiddenAction = document.createElement('input');
                    hiddenAction.type = 'hidden';
                    hiddenAction.name = 'action';
                    hiddenAction.value = actionValue;
                    this.appendChild(hiddenAction);
                    
                    clickedButton.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i>Saving...';
                    
                    // Re-enable after 10 seconds as fallback
                    setTimeout(() => {
                        clickedButton.disabled = false;
                        clickedButton.innerHTML = originalText;
                        if (hiddenAction.parentNode) {
                            hiddenAction.remove();
                        }
                    }, 10000);
                }
            });
        });
    </script>

    <style>
        /* Spinning animation for loading states */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Enhanced form styling */
        .question-item {
            transition: all 0.3s ease;
        }

        .question-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .remove-question:hover {
            transform: scale(1.1);
        }
    </style>
</x-app-layout>