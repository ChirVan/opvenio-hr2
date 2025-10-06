<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <!-- Breadcrumb -->
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('learning.hub') }}" class="text-gray-500 hover:text-gray-700">
                                <i class='bx bx-home mr-1'></i>
                                Assessment Hub
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class='bx bx-chevron-right text-gray-400'></i>
                                <span class="ml-1 text-gray-900 font-medium">Assign Assessment</span>
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

                <!-- API Status Indicator -->
                <div id="apiStatusIndicator" class="mb-4" style="display: none;">
                    <!-- This will be populated by JavaScript -->
                </div>

                <!-- Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Assign Employee Assessment</h1>
                        <p class="text-gray-600 mt-2">Select employees and assign them specific assessments for evaluation</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('learning.hub') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Back to Hub
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('learning.assessment-assignments.store') }}" class="space-y-8">
                    @csrf

                    <!-- Assignment Details Section -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Assessment Assignment Details</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Assessment Category -->
                            <div>
                                <label for="assessment_category" class="block text-sm font-medium text-gray-700 mb-2">
                                    Assessment Category <span class="text-red-500">*</span>
                                </label>
                                <select id="assessment_category" name="assessment_category" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('assessment_category') border-red-500 @enderror"
                                        onchange="loadQuizzesForCategory()">
                                    <option value="">Select a category</option>
                                    <!-- Categories will be populated by JavaScript -->
                                </select>
                            </div>

                            <!-- Assessment/Quiz Selection -->
                            <div>
                                <label for="quiz_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Assessment <span class="text-red-500">*</span>
                                </label>
                                <select id="quiz_id" name="quiz_id" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quiz_id') border-red-500 @enderror"
                                        onchange="updateQuizDetails()" disabled>
                                    <option value="">Select a category first</option>
                                </select>
                            </div>

                            <!-- Assessment Duration -->
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                                    Duration (minutes) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="duration" name="duration" min="1" max="480"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-100 @error('duration') border-red-500 @enderror"
                                       placeholder="Select an assessment first"
                                       value="{{ old('duration') }}" readonly>
                                <p class="text-sm text-gray-500 mt-1">Duration is automatically set based on the selected assessment</p>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Selection Section -->
                    <div class="bg-green-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Employee Selection</h2>
                        
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Employee <span class="text-red-500">*</span>
                            </label>
                            <select id="employee_id" name="employee_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('employee_id') border-red-500 @enderror">
                                <option value="">Loading employees from API...</option>
                            </select>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-sm text-gray-500">Select one employee to assign this assessment to. Employees loaded from external HR system.</p>
                                <button type="button" onclick="refreshEmployees()" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded" title="Refresh employee data">
                                    <i class='bx bx-refresh'></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Section -->
                    <div class="bg-yellow-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Assessment Timeline</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Available From <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="start_date" name="start_date" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_date') border-red-500 @enderror"
                                       value="{{ old('start_date', now()->format('Y-m-d\TH:i')) }}">
                            </div>

                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Due Date <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" id="due_date" name="due_date" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('due_date') border-red-500 @enderror"
                                       value="{{ old('due_date') }}">
                            </div>
                        </div>

                    </div>

                    <!-- Assessment Configuration Section -->
                    <div class="bg-purple-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Assessment Configuration</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Max Attempts -->
                            <div>
                                <label for="max_attempts" class="block text-sm font-medium text-gray-700 mb-2">
                                    Maximum Attempts <span class="text-red-500">*</span>
                                </label>
                                <select id="max_attempts" name="max_attempts" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('max_attempts') border-red-500 @enderror">
                                    <option value="1" {{ old('max_attempts') == '1' ? 'selected' : '' }}>1 Attempt</option>
                                    <option value="2" {{ old('max_attempts') == '2' ? 'selected' : '' }}>2 Attempts</option>
                                    <option value="3" {{ old('max_attempts', '3') == '3' ? 'selected' : '' }}>3 Attempts</option>
                                    <option value="unlimited" {{ old('max_attempts') == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
                                </select>
                            </div>
                        </div>

                        
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('learning.hub') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-md font-medium transition-colors">
                            Cancel
                        </a>
                        <button type="submit" name="action" value="assign"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                            Assign Assessment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Store employee data for lookup
            let employeeData = {};
            
            // Store categories and quiz data
            let categoriesData = {};
            let quizzesData = {};
            
            // Load employees from external API
            function loadEmployeesFromAPI() {
                const employeeSelect = document.getElementById('employee_id');
                employeeSelect.innerHTML = '<option value="">Loading employees from API...</option>';
                
                fetch('{{ route("training.assign.api-employees") }}')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.employees && data.employees.length > 0) {
                            employeeSelect.innerHTML = '<option value="">Select an employee</option>';
                            
                            // Show success status
                            showApiStatus('success', `Successfully loaded ${data.employees.length} employees from external API.`);
                            
                            // Store employee data for lookup
                            employeeData = {};
                            data.employees.forEach(employee => {
                                // Store employee data
                                employeeData[employee.id] = {
                                    employee_id: employee.employee_id,
                                    full_name: employee.full_name,
                                    email: employee.email,
                                    job_title: employee.job_title,
                                    employment_status: employee.employment_status,
                                    department: employee.department || 'N/A'
                                };
                                
                                const option = document.createElement('option');
                                option.value = employee.id;
                                option.textContent = `${employee.full_name} (${employee.employee_id}) - ${employee.job_title}`;
                                employeeSelect.appendChild(option);
                            });
                            
                            console.log(`Loaded ${data.employees.length} employees from API`);
                        } else {
                            employeeSelect.innerHTML = '<option value="">No employees found</option>';
                            showApiStatus('warning', 'No employees returned from external API.');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading employees:', error);
                        employeeSelect.innerHTML = '<option value="">Error loading employees from API</option>';
                        showApiStatus('error', 'Unable to load employees from external API. Please try refreshing or contact administrator.');
                    });
            }

            // Function to show API status
            function showApiStatus(type, message) {
                const statusIndicator = document.getElementById('apiStatusIndicator');
                statusIndicator.style.display = 'block';
                
                let className, icon;
                switch(type) {
                    case 'success':
                        className = 'bg-green-50 border-l-4 border-green-400 p-4';
                        icon = 'bx-check-circle text-green-400';
                        break;
                    case 'warning':
                        className = 'bg-yellow-50 border-l-4 border-yellow-400 p-4';
                        icon = 'bx-error-circle text-yellow-400';
                        break;
                    case 'error':
                        className = 'bg-red-50 border-l-4 border-red-400 p-4';
                        icon = 'bx-x-circle text-red-400';
                        break;
                }
                
                statusIndicator.innerHTML = `
                    <div class="${className}">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class='bx ${icon} text-xl'></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm ${type === 'success' ? 'text-green-700' : type === 'warning' ? 'text-yellow-700' : 'text-red-700'}">
                                    <strong>Employee API Status:</strong> ${message}
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            }

            // No need for complex selection logic with single dropdown

            // Refresh employees function
            window.refreshEmployees = function() {
                loadEmployeesFromAPI();
            };

            // Load employees and categories on page load
            loadEmployeesFromAPI();
            loadAssessmentCategories();
            
            // Load assessment categories from API
            function loadAssessmentCategories() {
                const categorySelect = document.getElementById('assessment_category');
                categorySelect.innerHTML = '<option value="">Loading categories...</option>';
                
                console.log('Attempting to fetch categories from API...');
                
                // First try the dedicated API endpoint
                fetch('{{ route("learning.assessment.categories.api") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('API Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('API Response data:', data);
                    
                    if (data.success && data.data && data.data.length > 0) {
                        categorySelect.innerHTML = '<option value="">Select a category</option>';
                        
                        // Store categories data
                        categoriesData = {};
                        data.data.forEach(category => {
                            categoriesData[category.id] = {
                                id: category.id,
                                category_name: category.category_name,
                                category_slug: category.category_slug,
                                description: category.description
                            };
                            
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.category_name;
                            categorySelect.appendChild(option);
                        });
                        
                        console.log('Successfully loaded', data.data.length, 'categories');
                    } else {
                        console.log('No categories found in response or unsuccessful response');
                        categorySelect.innerHTML = '<option value="">No categories found</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading categories from API endpoint:', error);
                    
                    // Fallback: Try fetching from the main assessment page
                    console.log('Trying fallback method...');
                    fetch('{{ route("learning.assessment") }}', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        console.log('Fallback API Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Fallback API Response data:', data);
                        
                        if (data.success && data.data && data.data.length > 0) {
                            categorySelect.innerHTML = '<option value="">Select a category</option>';
                            
                            // Store categories data
                            categoriesData = {};
                            data.data.forEach(category => {
                                categoriesData[category.id] = {
                                    id: category.id,
                                    category_name: category.category_name,
                                    category_slug: category.category_slug,
                                    description: category.description
                                };
                                
                                const option = document.createElement('option');
                                option.value = category.id;
                                option.textContent = category.category_name;
                                categorySelect.appendChild(option);
                            });
                            
                            console.log('Successfully loaded', data.data.length, 'categories via fallback');
                        } else {
                            console.log('Fallback also failed - no categories found');
                            categorySelect.innerHTML = '<option value="">No categories available</option>';
                        }
                    })
                    .catch(fallbackError => {
                        console.error('Fallback method also failed:', fallbackError);
                        categorySelect.innerHTML = '<option value="">Error loading categories</option>';
                    });
                });
            }
            
            // Load quizzes for selected category
            window.loadQuizzesForCategory = function() {
                const categorySelect = document.getElementById('assessment_category');
                const quizSelect = document.getElementById('quiz_id');
                const durationInput = document.getElementById('duration');
                
                const categoryId = categorySelect.value;
                
                if (!categoryId) {
                    quizSelect.innerHTML = '<option value="">Select a category first</option>';
                    quizSelect.disabled = true;
                    durationInput.value = '';
                    return;
                }
                
                const category = categoriesData[categoryId];
                if (!category) {
                    console.error('Category not found:', categoryId);
                    return;
                }
                
                quizSelect.innerHTML = '<option value="">Loading assessments...</option>';
                quizSelect.disabled = false;
                
                fetch(`{{ url('/learning/assessment/categories') }}/${category.category_slug}/quizzes`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.quizzes && data.data.quizzes.length > 0) {
                        quizSelect.innerHTML = '<option value="">Select an assessment</option>';
                        
                        // Store quizzes data
                        quizzesData = {};
                        data.data.quizzes.forEach(quiz => {
                            quizzesData[quiz.id] = {
                                id: quiz.id,
                                quiz_title: quiz.quiz_title,
                                description: quiz.description,
                                time_limit: quiz.time_limit,
                                total_questions: quiz.total_questions,
                                total_points: quiz.total_points,
                                competency_name: quiz.competency_name
                            };
                            
                            const option = document.createElement('option');
                            option.value = quiz.id;
                            option.textContent = `${quiz.quiz_title} (${quiz.total_questions} questions${quiz.time_limit ? `, ${quiz.time_limit} min` : ''})`;
                            quizSelect.appendChild(option);
                        });
                    } else {
                        quizSelect.innerHTML = '<option value="">No assessments found in this category</option>';
                        quizSelect.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error loading quizzes:', error);
                    quizSelect.innerHTML = '<option value="">Error loading assessments</option>';
                    quizSelect.disabled = true;
                });
            };
            
            // Update quiz details when quiz is selected
            window.updateQuizDetails = function() {
                const quizSelect = document.getElementById('quiz_id');
                const durationInput = document.getElementById('duration');
                
                const quizId = quizSelect.value;
                
                if (!quizId) {
                    durationInput.value = '';
                    return;
                }
                
                const quiz = quizzesData[quizId];
                if (quiz) {
                    // Set duration from quiz time limit
                    if (quiz.time_limit) {
                        durationInput.value = quiz.time_limit;
                    } else {
                        durationInput.value = '';
                        durationInput.placeholder = 'No time limit set for this assessment';
                    }
                }
            };

            // Date validation
            const startDateInput = document.getElementById('start_date');
            const dueDateInput = document.getElementById('due_date');

            function validateDates() {
                const startDate = new Date(startDateInput.value);
                const dueDate = new Date(dueDateInput.value);

                if (startDate && dueDate && dueDate <= startDate) {
                    dueDateInput.setCustomValidity('Due date must be after start date');
                } else {
                    dueDateInput.setCustomValidity('');
                }
            }

            startDateInput.addEventListener('change', validateDates);
            dueDateInput.addEventListener('change', validateDates);

            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                // Validate assessment category and quiz selection
                const categorySelect = document.getElementById('assessment_category');
                const quizSelect = document.getElementById('quiz_id');
                const employeeSelect = document.getElementById('employee_id');
                
                if (!categorySelect.value) {
                    e.preventDefault();
                    alert('Please select an assessment category.');
                    return;
                }
                
                if (!quizSelect.value) {
                    e.preventDefault();
                    alert('Please select an assessment.');
                    return;
                }
                
                if (!employeeSelect.value) {
                    e.preventDefault();
                    alert('Please select an employee for the assessment.');
                    return;
                }
            });
        });
    </script>

    <style>
        /* Custom styling for form elements */
        .form-radio {
            color: #3b82f6;
        }
        
        .form-radio:focus {
            ring-color: #3b82f6;
            ring-opacity: 0.5;
        }
        
        .form-checkbox {
            color: #3b82f6;
        }
        
        select[multiple] {
            background-image: none;
        }
        
        select[multiple] option:checked {
            background: #3b82f6;
            color: white;
        }

        /* Selection method content sections */
        .selection-method-content {
            transition: all 0.3s ease;
        }

        /* Smooth animations */
        .transition-colors {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</x-app-layout>