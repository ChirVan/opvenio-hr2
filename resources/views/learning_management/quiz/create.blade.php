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
                                <a href="{{ route('learning.assessment.categories.show', $category) }}" class="ml-1 text-blue-600 hover:text-blue-700">
                                    {{ $category->category_name }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class='bx bx-chevron-right text-gray-400'></i>
                                <span class="ml-1 text-gray-900 font-medium">Create Quiz</span>
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
                        <h1 class="text-3xl font-bold text-gray-900">Create Quiz</h1>
                        <p class="text-gray-600 mt-2">Create a new identification-based quiz for {{ $category->category_name }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('learning.assessment.categories.show', $category) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Back to Category
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('learning.quiz.store', $category) }}" class="space-y-8" id="quizForm">
                    @csrf

                    <!-- Quiz Details Section -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quiz Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Quiz Title -->
                            <div class="md:col-span-2">
                                <label for="quiz_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Quiz Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="quiz_title" name="quiz_title" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quiz_title') border-red-500 @enderror"
                                       placeholder="Enter quiz title (e.g., Core Competency Assessment)"
                                       value="{{ old('quiz_title') }}" required>
                                @error('quiz_title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Competency Selection -->
                            <div>
                                <label for="competency" class="block text-sm font-medium text-gray-700 mb-2">
                                    Related Competency <span class="text-red-500">*</span>
                                </label>
                                <select id="competency" name="competency"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('competency') border-red-500 @enderror" required>
                                    <option value="">Select a competency</option>
                                    <option value="communication" {{ old('competency') == 'communication' ? 'selected' : '' }}>Communication Skills</option>
                                    <option value="leadership" {{ old('competency') == 'leadership' ? 'selected' : '' }}>Leadership</option>
                                    <option value="problem_solving" {{ old('competency') == 'problem_solving' ? 'selected' : '' }}>Problem Solving</option>
                                    <option value="teamwork" {{ old('competency') == 'teamwork' ? 'selected' : '' }}>Teamwork</option>
                                    <option value="time_management" {{ old('competency') == 'time_management' ? 'selected' : '' }}>Time Management</option>
                                    <option value="adaptability" {{ old('competency') == 'adaptability' ? 'selected' : '' }}>Adaptability</option>
                                    <option value="critical_thinking" {{ old('competency') == 'critical_thinking' ? 'selected' : '' }}>Critical Thinking</option>
                                    <option value="technical_skills" {{ old('competency') == 'technical_skills' ? 'selected' : '' }}>Technical Skills</option>
                                </select>
                                @error('competency')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Time Limit -->
                            <div>
                                <label for="time_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                    Time Limit (minutes) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="time_limit" name="time_limit" min="5" max="180"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('time_limit') border-red-500 @enderror"
                                       placeholder="30"
                                       value="{{ old('time_limit', 30) }}" required>
                                @error('time_limit')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quiz Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea id="description" name="description" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                          placeholder="Enter a brief description of this quiz...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Questions Section -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">Identification Questions</h2>
                            <button type="button" id="addQuestion" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                                <i class='bx bx-plus mr-2'></i>
                                Add Question
                            </button>
                        </div>

                        <div id="questionsContainer">
                            <!-- Initial Question -->
                            <div class="question-item bg-white rounded-lg p-4 mb-4 border border-gray-200" data-question-index="0">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-lg font-medium text-gray-900">Question 1</h3>
                                    <button type="button" class="remove-question text-red-600 hover:text-red-800 hidden">
                                        <i class='bx bx-trash text-xl'></i>
                                    </button>
                                </div>

                                <div class="space-y-4">
                                    <!-- Question Text -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Question <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="questions[0][question]" rows="3" 
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                  placeholder="Enter your identification question here..."
                                                  required>{{ old('questions.0.question') }}</textarea>
                                    </div>

                                    <!-- Correct Answer -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Correct Answer <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="questions[0][answer]"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="Enter the correct answer"
                                               value="{{ old('questions.0.answer') }}" required>
                                    </div>

                                    <!-- Points -->
                                    <div class="w-32">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Points <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="questions[0][points]" min="1" max="10"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               value="{{ old('questions.0.points', 1) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-sm text-gray-600 mt-2">
                            <i class='bx bx-info-circle mr-1'></i>
                            Add at least 5 questions for a comprehensive assessment
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('learning.assessment.categories.show', $category) }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-md font-medium transition-colors">
                            Cancel
                        </a>
                        <button type="submit" name="action" value="draft"
                                class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                            Save as Draft
                        </button>
                        <button type="submit" name="action" value="publish"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                            Create & Publish Quiz
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let questionIndex = 1;
            const questionsContainer = document.getElementById('questionsContainer');
            const addQuestionBtn = document.getElementById('addQuestion');

            // Add Question functionality
            addQuestionBtn.addEventListener('click', function() {
                const questionHtml = `
                    <div class="question-item bg-white rounded-lg p-4 mb-4 border border-gray-200" data-question-index="${questionIndex}">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-medium text-gray-900">Question ${questionIndex + 1}</h3>
                            <button type="button" class="remove-question text-red-600 hover:text-red-800">
                                <i class='bx bx-trash text-xl'></i>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <!-- Question Text -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Question <span class="text-red-500">*</span>
                                </label>
                                <textarea name="questions[${questionIndex}][question]" rows="3" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Enter your identification question here..."
                                          required></textarea>
                            </div>

                            <!-- Correct Answer -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Correct Answer <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="questions[${questionIndex}][answer]"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Enter the correct answer" required>
                            </div>

                            <!-- Points -->
                            <div class="w-32">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Points <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="questions[${questionIndex}][points]" min="1" max="10"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="1" required>
                            </div>
                        </div>
                    </div>
                `;

                questionsContainer.insertAdjacentHTML('beforeend', questionHtml);
                questionIndex++;
                updateRemoveButtons();
                updateQuestionNumbers();
            });

            // Remove Question functionality
            questionsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-question')) {
                    e.target.closest('.question-item').remove();
                    updateRemoveButtons();
                    updateQuestionNumbers();
                }
            });

            // Update remove button visibility
            function updateRemoveButtons() {
                const questions = questionsContainer.querySelectorAll('.question-item');
                questions.forEach((question, index) => {
                    const removeBtn = question.querySelector('.remove-question');
                    if (questions.length > 1) {
                        removeBtn.classList.remove('hidden');
                    } else {
                        removeBtn.classList.add('hidden');
                    }
                });
            }

            // Update question numbers
            function updateQuestionNumbers() {
                const questions = questionsContainer.querySelectorAll('.question-item');
                questions.forEach((question, index) => {
                    const title = question.querySelector('h3');
                    title.textContent = `Question ${index + 1}`;
                });
            }

            // Form submission with loading state
            const form = document.getElementById('quizForm');
            form.addEventListener('submit', function(e) {
                const submitButton = e.submitter;
                const originalText = submitButton.innerHTML;
                
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i>Creating...';
                
                // Re-enable after delay (in case of validation errors)
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }, 5000);
            });

            // Initialize remove buttons
            updateRemoveButtons();
        });
    </script>

    <style>
        /* Custom styling for question items */
        .question-item {
            transition: all 0.3s ease;
        }
        
        .question-item:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Animate question removal */
        .question-item.removing {
            opacity: 0;
            transform: translateX(-100%);
        }
        
        /* Focus states */
        input:focus, select:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</x-app-layout>