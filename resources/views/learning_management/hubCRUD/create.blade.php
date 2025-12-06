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
                        <h1 class="text-3xl font-bold text-gray-900">
                            @if(request('reassessment'))
                                Reassign Assessment
                            @else
                                Assign Employee Assessment
                            @endif
                        </h1>
                        <p class="text-gray-600 mt-2">
                            @if(request('reassessment'))
                                Assign new assessment to employee who needs reassessment
                            @else
                                Select employees and assign them specific assessments for evaluation
                            @endif
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        @if(request('reassessment'))
                            <a href="{{ route('assessment.results') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                                <i class='bx bx-arrow-back mr-2'></i>
                                Back to Results
                            </a>
                        @else
                            <a href="{{ route('learning.hub') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                                <i class='bx bx-arrow-back mr-2'></i>
                                Back to Hub
                            </a>
                        @endif
                    </div>
                </div>

                @if(request('reassessment'))
                    <!-- Reassessment Banner -->
                    <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-6 rounded-r-lg">
                        <div class="flex items-center">
                            <i class='bx bx-refresh text-2xl mr-3'></i>
                            <div>
                                <p class="font-bold">Reassessment Mode</p>
                                <p class="text-sm">
                                    Employee <strong>{{ request('employee_name') }}</strong> has failed their previous assessment. 
                                    Please assign new assessments for them to retake.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('learning.assessment-assignments.store') }}" class="space-y-8">
                    @csrf
                    
                    <!-- Hidden field to track assignment source (for assignment type) -->
                    <input type="hidden" name="source" value="{{ request('source', 'self_request') }}">

                    <!-- Assignment Details Section -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Assessment Assignment Details</h2>
                        
                        <!-- Selected Assessments Display -->
                        <div id="selectedAssessmentsContainer" class="mb-6 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Selected Assessments <span class="text-green-600" id="selectedCount">(0)</span>
                            </label>
                            <div id="selectedAssessmentsList" class="space-y-2 max-h-48 overflow-y-auto border border-blue-200 rounded-lg p-3 bg-white">
                                <!-- Selected assessments will be added here -->
                            </div>
                            <p class="text-sm text-gray-500 mt-2">
                                <i class='bx bx-info-circle mr-1'></i>
                                Total duration will be calculated based on selected assessments
                            </p>
                        </div>

                        <!-- Add Assessment Section -->
                        <div class="border border-dashed border-blue-300 rounded-lg p-4 bg-blue-25">
                            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <i class='bx bx-plus-circle text-blue-500 mr-2'></i>
                                Add Assessment
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Assessment Category -->
                                <div>
                                    <label for="assessment_category" class="block text-sm font-medium text-gray-700 mb-2">
                                        Category
                                    </label>
                                    <select id="assessment_category" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                            onchange="loadQuizzesForCategory()">
                                        <option value="">Select a category</option>
                                        <!-- Categories will be populated by JavaScript -->
                                    </select>
                                </div>

                                <!-- Assessment/Quiz Selection -->
                                <div>
                                    <label for="quiz_selector" class="block text-sm font-medium text-gray-700 mb-2">
                                        Assessment
                                    </label>
                                    <select id="quiz_selector" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                            disabled>
                                        <option value="">Select a category first</option>
                                    </select>
                                </div>

                                <!-- Add Button -->
                                <div class="flex items-end">
                                    <button type="button" onclick="addSelectedAssessment()" 
                                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium transition-colors flex items-center justify-center">
                                        <i class='bx bx-plus mr-1'></i>
                                        Add Assessment
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden inputs for form submission -->
                        <div id="hiddenAssessmentInputs">
                            <!-- Hidden inputs will be dynamically added here -->
                        </div>

                        <!-- Total Duration Display -->
                        <div class="mt-4 p-3 bg-white rounded-lg border border-blue-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">
                                    <i class='bx bx-time text-blue-500 mr-1'></i>
                                    Total Duration:
                                </span>
                                <span id="totalDuration" class="text-lg font-bold text-blue-600">0 minutes</span>
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

                        <!-- Assigned Competencies Reference (Only shown when redirected from Gap Analysis) -->
                        <div id="assignedCompetenciesSection" class="mt-6 hidden">
                            <div class="border-t border-green-200 pt-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-800 flex items-center">
                                        <i class='bx bx-target-lock text-green-600 mr-2'></i>
                                        Assigned Competencies Reference
                                    </h4>
                                    <span class="text-xs text-gray-500 bg-green-100 px-2 py-1 rounded-full">
                                        <i class='bx bx-info-circle mr-1'></i>From Gap Analysis
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">
                                    These are the competencies assigned to this employee. Use this as a reference when selecting assessments.
                                </p>
                                <div id="competenciesLoadingState" class="flex items-center justify-center py-4">
                                    <i class='bx bx-loader-alt bx-spin text-green-600 text-xl mr-2'></i>
                                    <span class="text-gray-500 text-sm">Loading competencies...</span>
                                </div>
                                <div id="competenciesList" class="hidden">
                                    <!-- Competencies will be loaded here dynamically -->
                                </div>
                                <div id="noCompetenciesState" class="hidden text-center py-4">
                                    <i class='bx bx-info-circle text-gray-400 text-2xl'></i>
                                    <p class="text-gray-500 text-sm mt-1">No assigned competencies found for this employee.</p>
                                </div>
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
            
            // Store selected assessments
            let selectedAssessments = [];
            
            // Load employees from external API
            function loadEmployeesFromAPI() {
                const employeeSelect = document.getElementById('employee_id');
                employeeSelect.innerHTML = '<option value="">Loading employees from API...</option>';
                
                // Check if this is a reassessment with pre-selected employee
                const reassessmentEmployeeId = '{{ request("employee_id", "") }}';
                const isReassessment = '{{ request("reassessment", "") }}' === '1';
                
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
                            let preSelectedValue = null;
                            
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
                                
                                // Pre-select employee for reassessment
                                if (isReassessment && employee.employee_id === reassessmentEmployeeId) {
                                    option.selected = true;
                                    preSelectedValue = employee.id;
                                }
                                
                                employeeSelect.appendChild(option);
                            });
                            
                            // If reassessment and employee found, trigger change event to update any dependent fields
                            if (isReassessment && preSelectedValue) {
                                console.log(`Pre-selected employee for reassessment: ${reassessmentEmployeeId}`);
                            }
                            
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

            // Check for URL parameters to pre-select employee
            const urlParams = new URLSearchParams(window.location.search);
            const preSelectedEmployeeId = urlParams.get('employee_id');
            const preSelectedEmployeeName = urlParams.get('employee_name');

            if (preSelectedEmployeeId && preSelectedEmployeeName) {
                // Show notification that employee was pre-selected
                showApiStatus('success', `Employee pre-selected: ${decodeURIComponent(preSelectedEmployeeName)}`);
                
                // Show the assigned competencies section
                document.getElementById('assignedCompetenciesSection').classList.remove('hidden');
                
                // Load assigned competencies for this employee
                loadAssignedCompetencies(preSelectedEmployeeId);
                
                // Wait for employees to load, then select the employee
                const checkAndSelectEmployee = setInterval(function() {
                    const employeeSelect = document.getElementById('employee_id');
                    
                    // Check if employees are loaded (more than just the placeholder option)
                    if (employeeSelect.options.length > 1) {
                        // Find the employee by matching the employee_id in the stored data
                        let foundEmployeeId = null;
                        
                        for (let empId in employeeData) {
                            if (employeeData[empId].employee_id === preSelectedEmployeeId) {
                                foundEmployeeId = empId;
                                break;
                            }
                        }
                        
                        if (foundEmployeeId) {
                            employeeSelect.value = foundEmployeeId;
                            // Trigger change event to load employee info
                            employeeSelect.dispatchEvent(new Event('change'));
                            console.log('Auto-selected employee:', preSelectedEmployeeName);
                        } else {
                            console.warn('Could not find employee with ID:', preSelectedEmployeeId);
                        }
                        
                        clearInterval(checkAndSelectEmployee);
                    }
                }, 100); // Check every 100ms
                
                // Clear interval after 5 seconds if employee not found
                setTimeout(function() {
                    clearInterval(checkAndSelectEmployee);
                }, 5000);
            }
            
            // Function to load assigned competencies for an employee
            function loadAssignedCompetencies(employeeId) {
                const loadingState = document.getElementById('competenciesLoadingState');
                const competenciesList = document.getElementById('competenciesList');
                const noCompetenciesState = document.getElementById('noCompetenciesState');
                
                // Show loading state
                loadingState.classList.remove('hidden');
                competenciesList.classList.add('hidden');
                noCompetenciesState.classList.add('hidden');
                
                // Fetch assigned competencies from API
                fetch('/api/assigned-competencies')
                    .then(response => response.json())
                    .then(response => {
                        loadingState.classList.add('hidden');
                        
                        // Check if response is successful and has data
                        if (!response.success || !response.data) {
                            noCompetenciesState.classList.remove('hidden');
                            return;
                        }
                        
                        const data = response.data;
                        
                        // Filter competencies for the selected employee
                        const employeeCompetencies = data.find(emp => emp.employee_id === employeeId);
                        
                        if (employeeCompetencies && employeeCompetencies.competencies && employeeCompetencies.competencies.length > 0) {
                            competenciesList.classList.remove('hidden');
                            
                            let html = '<div class="space-y-2 max-h-64 overflow-y-auto pr-2">';
                            
                            employeeCompetencies.competencies.forEach(comp => {
                                const statusColors = {
                                    'assigned': 'bg-blue-100 text-blue-700 border-blue-200',
                                    'in_progress': 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    'completed': 'bg-green-100 text-green-700 border-green-200',
                                    'on_hold': 'bg-gray-100 text-gray-700 border-gray-200'
                                };
                                
                                const priorityIcons = {
                                    'critical': '🔴',
                                    'high': '🟠',
                                    'medium': '🟡',
                                    'low': '🔵'
                                };
                                
                                const statusClass = statusColors[comp.status] || 'bg-gray-100 text-gray-700 border-gray-200';
                                const priorityIcon = priorityIcons[comp.priority] || '⚪';
                                
                                html += `
                                    <div class="flex items-center justify-between p-3 ${statusClass} rounded-lg border">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-sm">${comp.competency_name}</span>
                                                <span class="text-xs">${priorityIcon}</span>
                                            </div>
                                            <div class="text-xs opacity-75 mt-1">
                                                ${comp.framework_name || 'General'} • Progress: ${comp.progress_percentage || 0}%
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs px-2 py-1 rounded-full bg-white bg-opacity-50 capitalize">
                                                ${comp.status.replace('_', ' ')}
                                            </span>
                                        </div>
                                    </div>
                                `;
                            });
                            
                            html += '</div>';
                            
                            // Add summary
                            const totalCount = employeeCompetencies.competencies.length;
                            const completedCount = employeeCompetencies.competencies.filter(c => c.status === 'completed').length;
                            const inProgressCount = employeeCompetencies.competencies.filter(c => c.status === 'in_progress').length;
                            
                            html += `
                                <div class="mt-3 pt-3 border-t border-green-200 flex items-center justify-between text-xs text-gray-600">
                                    <div class="flex items-center gap-4">
                                        <span><strong>${totalCount}</strong> Total</span>
                                        <span class="text-green-600"><strong>${completedCount}</strong> Completed</span>
                                        <span class="text-yellow-600"><strong>${inProgressCount}</strong> In Progress</span>
                                    </div>
                                    <a href="{{ route('competency.gap-analysis') }}?employee_id=${employeeId}" 
                                       class="text-green-600 hover:text-green-700 font-medium flex items-center">
                                        View Full Details <i class='bx bx-link-external ml-1'></i>
                                    </a>
                                </div>
                            `;
                            
                            competenciesList.innerHTML = html;
                        } else {
                            noCompetenciesState.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading assigned competencies:', error);
                        loadingState.classList.add('hidden');
                        noCompetenciesState.classList.remove('hidden');
                        noCompetenciesState.innerHTML = `
                            <i class='bx bx-error-circle text-red-400 text-2xl'></i>
                            <p class="text-red-500 text-sm mt-1">Error loading competencies. Please try again.</p>
                        `;
                    });
            }
            
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
                const quizSelect = document.getElementById('quiz_selector');
                
                const categoryId = categorySelect.value;
                
                if (!categoryId) {
                    quizSelect.innerHTML = '<option value="">Select a category first</option>';
                    quizSelect.disabled = true;
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
                        
                        data.data.quizzes.forEach(quiz => {
                            // Store quiz data with category info
                            quizzesData[quiz.id] = {
                                id: quiz.id,
                                quiz_title: quiz.quiz_title,
                                description: quiz.description,
                                time_limit: quiz.time_limit,
                                total_questions: quiz.total_questions,
                                total_points: quiz.total_points,
                                competency_name: quiz.competency_name,
                                category_id: categoryId,
                                category_name: category.category_name
                            };
                            
                            // Check if already selected
                            const isAlreadySelected = selectedAssessments.some(a => a.id === quiz.id);
                            
                            const option = document.createElement('option');
                            option.value = quiz.id;
                            option.textContent = `${quiz.quiz_title} (${quiz.total_questions} questions${quiz.time_limit ? `, ${quiz.time_limit} min` : ''})`;
                            if (isAlreadySelected) {
                                option.disabled = true;
                                option.textContent += ' ✓ Added';
                            }
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
            
            // Add selected assessment to the list
            window.addSelectedAssessment = function() {
                const quizSelect = document.getElementById('quiz_selector');
                const quizId = quizSelect.value;
                
                if (!quizId) {
                    alert('Please select an assessment first.');
                    return;
                }
                
                const quiz = quizzesData[quizId];
                if (!quiz) {
                    alert('Assessment data not found.');
                    return;
                }
                
                // Check if already added
                if (selectedAssessments.some(a => a.id === quiz.id)) {
                    alert('This assessment has already been added.');
                    return;
                }
                
                // Add to selected assessments
                selectedAssessments.push({
                    id: quiz.id,
                    quiz_title: quiz.quiz_title,
                    time_limit: quiz.time_limit || 0,
                    total_questions: quiz.total_questions,
                    category_name: quiz.category_name,
                    category_id: quiz.category_id
                });
                
                // Update UI
                renderSelectedAssessments();
                updateTotalDuration();
                updateHiddenInputs();
                
                // Reset selectors
                quizSelect.value = '';
                
                // Refresh the quiz list to show "Added" status
                loadQuizzesForCategory();
                
                console.log('Added assessment:', quiz.quiz_title);
            };
            
            // Remove assessment from list
            window.removeAssessment = function(quizId) {
                selectedAssessments = selectedAssessments.filter(a => a.id !== quizId);
                renderSelectedAssessments();
                updateTotalDuration();
                updateHiddenInputs();
                
                // Refresh the quiz list
                loadQuizzesForCategory();
            };
            
            // Render selected assessments list
            function renderSelectedAssessments() {
                const container = document.getElementById('selectedAssessmentsContainer');
                const list = document.getElementById('selectedAssessmentsList');
                const countSpan = document.getElementById('selectedCount');
                
                if (selectedAssessments.length === 0) {
                    container.classList.add('hidden');
                    return;
                }
                
                container.classList.remove('hidden');
                countSpan.textContent = `(${selectedAssessments.length})`;
                
                let html = '';
                selectedAssessments.forEach((assessment, index) => {
                    html += `
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full">${index + 1}</span>
                                    <span class="font-medium text-sm text-gray-900">${assessment.quiz_title}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1 ml-6">
                                    <span class="inline-flex items-center">
                                        <i class='bx bx-category mr-1'></i>${assessment.category_name}
                                    </span>
                                    <span class="mx-2">•</span>
                                    <span class="inline-flex items-center">
                                        <i class='bx bx-list-ol mr-1'></i>${assessment.total_questions} questions
                                    </span>
                                    <span class="mx-2">•</span>
                                    <span class="inline-flex items-center">
                                        <i class='bx bx-time mr-1'></i>${assessment.time_limit || 'No limit'} min
                                    </span>
                                </div>
                            </div>
                            <button type="button" onclick="removeAssessment(${assessment.id})" 
                                    class="ml-3 p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full transition-colors"
                                    title="Remove assessment">
                                <i class='bx bx-x text-lg'></i>
                            </button>
                        </div>
                    `;
                });
                
                list.innerHTML = html;
            }
            
            // Update total duration
            function updateTotalDuration() {
                const totalDurationSpan = document.getElementById('totalDuration');
                const total = selectedAssessments.reduce((sum, a) => sum + (a.time_limit || 0), 0);
                totalDurationSpan.textContent = `${total} minutes`;
            }
            
            // Update hidden inputs for form submission
            function updateHiddenInputs() {
                const container = document.getElementById('hiddenAssessmentInputs');
                
                let html = '';
                selectedAssessments.forEach((assessment, index) => {
                    html += `<input type="hidden" name="quiz_ids[]" value="${assessment.id}">`;
                    html += `<input type="hidden" name="category_ids[]" value="${assessment.category_id}">`;
                });
                
                container.innerHTML = html;
            }

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
                // Validate that at least one assessment is selected
                if (selectedAssessments.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one assessment.');
                    return;
                }
                
                // Validate employee selection
                const employeeSelect = document.getElementById('employee_id');
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