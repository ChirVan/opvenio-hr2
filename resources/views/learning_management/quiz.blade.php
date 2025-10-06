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
                        @if(isset($category))
                        <li>
                            <div class="flex items-center">
                                <i class='bx bx-chevron-right text-gray-400'></i>
                                <a href="{{ route('learning.assessment.categories.show', $category) }}" class="ml-1 text-blue-600 hover:text-blue-700">
                                    {{ $category->category_name }}
                                </a>
                            </div>
                        </li>
                        @endif
                        <li>
                            <div class="flex items-center">
                                <i class='bx bx-chevron-right text-gray-400'></i>
                                <span class="ml-1 text-gray-900 font-medium">Create Quiz</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Create Quiz</h1>
                        <p class="text-gray-600 mt-2">
                            Create a new identification-based quiz
                            @if(isset($category))
                                for {{ $category->category_name }}
                            @endif
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ isset($category) ? route('learning.assessment.categories.show', $category) : route('learning.assessment') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-arrow-back mr-2'></i>
                            @if(isset($category))
                                Back to {{ $category->category_name }}
                            @else
                                Back to Assessment Center
                            @endif
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('learning.quiz.store') }}" class="space-y-8" id="quizForm">
                    @csrf
                    
                    <!-- Hidden Category ID -->
                    @if(isset($category))
                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                    @endif

                    <!-- Category Indicator -->
                    @if(isset($category))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class='bx bx-check-circle text-green-600 text-xl'></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Creating Quiz for Category</h3>
                                <p class="text-sm text-green-700 mt-1">
                                    <strong>{{ $category->category_name }}</strong> (ID: {{ $category->id }})
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

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
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Enter quiz title (e.g., Core Competency Assessment)"
                                       required>
                            </div>

                            <!-- Competency Selection -->
                            <div>
                                <label for="competency" class="block text-sm font-medium text-gray-700 mb-2">
                                    What Competency <span class="text-red-500">*</span>
                                </label>
                                <select id="competency" name="competency"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    <option value="">Select a competency</option>
                                    @forelse($competencies as $competency)
                                        <option value="{{ $competency->id }}" title="{{ $competency->description }}">
                                            {{ $competency->competency_name }}
                                        </option>
                                    @empty
                                        <option value="" disabled>No competencies available</option>
                                    @endforelse
                                </select>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class='bx bx-info-circle mr-1'></i>
                                    Hover over options to see descriptions
                                </p>
                            </div>

                            <!-- Time Limit -->
                            <div>
                                <label for="time_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                    Time Limit (minutes)
                                </label>
                                <input type="number" id="time_limit" name="time_limit" min="5" max="180"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="30" value="30">
                            </div>

                            <!-- Quiz Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea id="description" name="description" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Enter a brief description of this quiz..."></textarea>
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
                                                  required></textarea>
                                    </div>

                                    <!-- Correct Answer -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Correct Answer <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="questions[0][answer]"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="Enter the correct answer" required>
                                    </div>

                                    <!-- Points -->
                                    <div class="w-32">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Points <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="questions[0][points]" min="1" max="10"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               value="1" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-sm text-gray-600 mt-2">
                            <i class='bx bx-info-circle mr-1'></i>
                            Add at least 3 questions for a comprehensive assessment
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ isset($category) ? route('learning.assessment.categories.show', $category) : route('learning.assessment') }}" 
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

            // Initialize remove buttons
            updateRemoveButtons();

            // Form submission with AJAX
            const form = document.getElementById('quizForm');
            let clickedAction = 'draft'; // default action
            
            // Track which submit button was clicked
            form.addEventListener('click', function(e) {
                if (e.target.type === 'submit' && e.target.name === 'action') {
                    clickedAction = e.target.value;
                }
            });

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Clear previous errors
                document.querySelectorAll('.error-message').forEach(el => el.remove());
                document.querySelectorAll('.border-red-500').forEach(el => {
                    el.classList.remove('border-red-500');
                    el.classList.add('border-gray-300');
                });

                // Create form data manually
                const formData = new FormData();
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                formData.append('quiz_title', document.getElementById('quiz_title').value);
                formData.append('competency', document.getElementById('competency').value);
                formData.append('time_limit', document.getElementById('time_limit').value);
                formData.append('description', document.getElementById('description').value);
                formData.append('action', clickedAction);
                
                // Add category_id if present (from hidden input)
                const categoryIdInput = document.querySelector('input[name="category_id"]');
                if (categoryIdInput) {
                    formData.append('category_id', categoryIdInput.value);
                    console.log('Debug: Adding category_id to form data:', categoryIdInput.value);
                }

                // Add questions
                const questions = document.querySelectorAll('.question-item');
                questions.forEach((questionEl, index) => {
                    const questionText = questionEl.querySelector(`textarea[name*="[question]"]`).value;
                    const answer = questionEl.querySelector(`input[name*="[answer]"]`).value;
                    const points = questionEl.querySelector(`input[name*="[points]"]`).value;
                    
                    formData.append(`questions[${index}][question]`, questionText);
                    formData.append(`questions[${index}][answer]`, answer);
                    formData.append(`questions[${index}][points]`, points);
                });

                const submitButton = e.submitter || form.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;

                try {
                    // Disable submit button
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i>Creating...';

                    const response = await fetch('{{ route("learning.quiz.store") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Show success message
                        showMessage('success', data.message);
                        
                        // Redirect after short delay
                        setTimeout(() => {
                            window.location.href = data.data.redirect_url || '{{ route("learning.assessment") }}';
                        }, 1500);
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                const errorElement = document.querySelector(`[name="${key}"]`);
                                if (errorElement) {
                                    errorElement.classList.add('border-red-500');
                                    errorElement.classList.remove('border-gray-300');
                                    
                                    const errorMsg = document.createElement('p');
                                    errorMsg.className = 'text-red-500 text-sm mt-1 error-message';
                                    errorMsg.textContent = data.errors[key][0];
                                    errorElement.parentNode.insertBefore(errorMsg, errorElement.nextSibling);
                                }
                            });
                        }
                        showMessage('error', data.message || 'Please correct the errors and try again.');
                    }
                } catch (error) {
                    console.error('Quiz submission error:', error);
                    showMessage('error', 'An unexpected error occurred. Please try again.');
                }

                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });

            // Helper function to show messages
            function showMessage(type, message) {
                const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
                const iconClass = type === 'success' ? 'bx-check-circle' : 'bx-error-circle';
                
                const alertDiv = document.createElement('div');
                alertDiv.className = `${alertClass} px-4 py-3 rounded mb-6 border`;
                alertDiv.innerHTML = `
                    <div class="flex">
                        <div class="py-1">
                            <i class='bx ${iconClass} mr-2'></i>
                        </div>
                        <div>
                            <p class="font-bold">${type === 'success' ? 'Success!' : 'Error!'}</p>
                            <p class="text-sm">${message}</p>
                        </div>
                    </div>
                `;
                
                // Insert at the top of the form container
                const container = document.querySelector('.bg-white.shadow.rounded-lg.p-6');
                const breadcrumb = container.querySelector('nav');
                container.insertBefore(alertDiv, breadcrumb.nextSibling);
                
                // Auto-remove success messages
                if (type === 'success') {
                    setTimeout(() => alertDiv.remove(), 3000);
                }
            }
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
        
        /* Focus states */
        input:focus, select:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</x-app-layout>
