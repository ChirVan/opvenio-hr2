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
                            <a href="{{ route('training.assign.index') }}" class="text-gray-500 hover:text-gray-700">
                                <i class='bx bx-user-plus mr-1'></i>
                                Assign Training
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class='bx bx-chevron-right text-gray-400'></i>
                                <span class="ml-1 text-gray-900 font-medium">Create Assignment</span>
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
                        <h1 class="text-3xl font-bold text-gray-900">Create Training Assignment</h1>
                        <p class="text-gray-600 mt-2">Assign training materials to employees and set completion requirements</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('training.assign.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                            <i class='bx bx-arrow-back mr-2'></i>
                            Back to Assignments
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('training.assign.store') }}" class="space-y-8">
                    @csrf

                    <!-- Employee Selection Section -->
                    <div class="bg-green-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Employee Selection</h2>
                        
                        <!-- Employee Selection -->
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Employee <span class="text-red-500">*</span>
                            </label>
                            <select id="employee_id" name="employee_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('employee_id') border-red-500 @enderror">
                                <option value="">Loading employees from API...</option>
                            </select>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-sm text-gray-500">Employees loaded from external HR system.</p>
                                <button type="button" onclick="refreshEmployees()" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded" title="Refresh employee data">
                                    <i class='bx bx-refresh'></i> Refresh
                                </button>
                            </div>
                        </div>

                        <!-- Employee Information Display -->
                        <div class="mt-4 p-4 bg-gray-50 rounded-md hidden" id="employeeInfoCard">
                            <h4 class="font-medium text-gray-800 mb-2">Employee Information</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Employee ID:</span>
                                    <span id="displayEmployeeId" class="font-medium ml-2"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Email:</span>
                                    <span id="displayEmail" class="font-medium ml-2"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Job Title:</span>
                                    <span id="displayJobTitle" class="font-medium ml-2"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Employment Status:</span>
                                    <span id="displayEmploymentStatus" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ml-2"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Job Title (Hidden field for form submission) -->
                        <input type="hidden" id="job_title" name="job_title" value="">

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
                                    These are the competencies assigned to this employee. Use this as a reference when selecting training materials.
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

                        <!-- Assigned By (Read-only) -->
                        <div>
                            <label for="assigned_by" class="block text-sm font-medium text-gray-700 mb-2">
                                Assigned By
                            </label>
                            <input type="text" id="assigned_by" name="assigned_by" readonly
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md bg-gray-50 text-gray-600 cursor-not-allowed"
                                   value="{{ Auth::user()->name ?? Auth::user()->email ?? 'Current User' }}">
                            <input type="hidden" name="assigned_by_id" value="{{ Auth::id() }}">
                            <p class="text-sm text-gray-500 mt-1">This assignment will be created by the currently logged-in user.</p>
                        </div>
                    </div>

                    <!-- Assignment Details Section -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Assignment Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Assignment Title -->
                            <div class="md:col-span-2">
                                <label for="assignment_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Assignment Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="assignment_title" name="assignment_title" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('assignment_title') border-red-500 @enderror"
                                       placeholder="Enter assignment title (e.g., Q4 Compliance Training)"
                                       value="{{ old('assignment_title') }}">
                            </div>

                            <!-- Training Catalog -->
                            <div>
                                <label for="training_catalog_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Training Catalog <span class="text-red-500">*</span>
                                </label>
                                <select id="training_catalog_id" name="training_catalog_id" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('training_catalog_id') border-red-500 @enderror">
                                    <option value="">Select a training catalog</option>
                                    @foreach($trainingCatalogs as $catalog)
                                        <option value="{{ $catalog->id }}" {{ old('training_catalog_id') == $catalog->id ? 'selected' : '' }}>
                                            {{ $catalog->title }}
                                            @if($catalog->framework)
                                                ({{ $catalog->framework->framework_name }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Training Materials -->
                            <div>
                                <label for="training_materials" class="block text-sm font-medium text-gray-700 mb-2">
                                    Training Materials <span class="text-red-500">*</span>
                                </label>
                                <select id="training_materials" name="training_materials[]" multiple
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('training_materials') border-red-500 @enderror"
                                        size="4">
                                    <option value="">Select training catalog first</option>
                                </select>
                                <p class="text-sm text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple materials</p>
                            </div>

                            <!-- Priority Level -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                    Priority Level <span class="text-red-500">*</span>
                                </label>
                                <select id="priority" name="priority" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('priority') border-red-500 @enderror">
                                    <option value="">Select priority</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low Priority</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium Priority</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High Priority</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>

                            <!-- Assignment Type -->
                            <div>
                                <label for="assignment_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Assignment Type <span class="text-red-500">*</span>
                                </label>
                                <select id="assignment_type" name="assignment_type" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('assignment_type') border-red-500 @enderror">
                                    <option value="">Select type</option>
                                    <option value="mandatory" {{ old('assignment_type') == 'mandatory' ? 'selected' : '' }}>Mandatory Training</option>
                                    <option value="optional" {{ old('assignment_type') == 'optional' ? 'selected' : '' }}>Optional Training</option>
                                    <option value="development" {{ old('assignment_type') == 'development' ? 'selected' : '' }}>Professional Development</option>
                                </select>
                            </div>


                        </div>
                    </div>

                    

                    <!-- Timeline & Requirements Section -->
                    <div class="bg-yellow-50 rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Timeline & Requirements</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="start_date" name="start_date" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_date') border-red-500 @enderror"
                                       value="{{ old('start_date', date('Y-m-d')) }}">
                            </div>

                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Due Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="due_date" name="due_date" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('due_date') border-red-500 @enderror"
                                       value="{{ old('due_date') }}">
                            </div>

                            
                        </div>

                        <!-- Instructions -->
                        <div class="mt-6">
                            <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">
                                Instructions for Employees
                            </label>
                            <textarea id="instructions" name="instructions" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('instructions') border-red-500 @enderror"
                                      placeholder="Enter any special instructions or notes for the assigned employees...">{{ old('instructions') }}</textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('training.assign.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-md font-medium transition-colors">
                            Cancel
                        </a>
                        <button type="submit" name="action" value="draft"
                                class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                            Save as Draft
                        </button>
                        <button type="submit" name="action" value="assign"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                            Create Assignment
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
                                    employment_status: employee.employment_status
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
                            console.warn('No employees returned from API');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading employees:', error);
                        employeeSelect.innerHTML = '<option value="">Error loading employees from API</option>';
                        showApiStatus('error', 'Unable to load employees from external API. Please try refreshing or contact administrator.');
                    });
            }

            // Handle employee selection change
            document.getElementById('employee_id').addEventListener('change', function() {
                const selectedEmployeeId = this.value;
                const employeeInfoCard = document.getElementById('employeeInfoCard');
                const jobTitleInput = document.getElementById('job_title');
                
                // Display elements
                const displayEmployeeId = document.getElementById('displayEmployeeId');
                const displayEmail = document.getElementById('displayEmail');
                const displayJobTitle = document.getElementById('displayJobTitle');
                const displayEmploymentStatus = document.getElementById('displayEmploymentStatus');
                
                if (selectedEmployeeId && employeeData[selectedEmployeeId]) {
                    const employee = employeeData[selectedEmployeeId];
                    
                    // Show employee info card
                    employeeInfoCard.classList.remove('hidden');
                    
                    // Update display elements
                    displayEmployeeId.textContent = employee.employee_id || 'N/A';
                    displayEmail.textContent = employee.email || 'N/A';
                    displayJobTitle.textContent = employee.job_title || 'N/A';
                    displayEmploymentStatus.textContent = employee.employment_status || 'N/A';
                    
                    // Update hidden job title field for form submission
                    jobTitleInput.value = employee.job_title || '';
                    
                    // Style employment status badge
                    if (employee.employment_status === 'Active') {
                        displayEmploymentStatus.className = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 ml-2';
                    } else {
                        displayEmploymentStatus.className = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 ml-2';
                    }
                } else {
                    // Hide employee info card
                    employeeInfoCard.classList.add('hidden');
                    jobTitleInput.value = '';
                }
            });

            // Refresh employees function
            window.refreshEmployees = function() {
                loadEmployeesFromAPI();
            };

            // Load employees on page load
            loadEmployeesFromAPI();

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

            // Training catalog change handler (for loading materials)
            const catalogSelect = document.getElementById('training_catalog_id');
            const materialsSelect = document.getElementById('training_materials');

            catalogSelect.addEventListener('change', function() {
                const catalogId = this.value;
                materialsSelect.innerHTML = '<option value="">Loading materials...</option>';
                materialsSelect.disabled = true;

                if (catalogId) {
                    // Fetch materials via AJAX
                    console.log('Fetching materials for catalog ID:', catalogId);
                    fetch(`{{ url('/training/assign/materials') }}/${catalogId}`)
                        .then(response => {
                            console.log('Response status:', response.status);
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(materials => {
                            console.log('Materials received:', materials);
                            materialsSelect.innerHTML = '<option value="">Select training materials</option>';
                            
                            if (materials && materials.length > 0) {
                                materials.forEach(material => {
                                    const option = document.createElement('option');
                                    option.value = material.id;
                                    option.textContent = material.lesson_title;
                                    materialsSelect.appendChild(option);
                                });
                                materialsSelect.disabled = false;
                                console.log(`Loaded ${materials.length} materials`);
                            } else {
                                materialsSelect.innerHTML = '<option value="">No published materials available for this catalog</option>';
                                materialsSelect.disabled = true;
                                console.log('No materials found for this catalog');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching materials:', error);
                            materialsSelect.innerHTML = '<option value="">Error loading materials - Check console</option>';
                            materialsSelect.disabled = true;
                        });
                } else {
                    materialsSelect.innerHTML = '<option value="">Select training catalog first</option>';
                    materialsSelect.disabled = true;
                }
            });

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
                // Validate employee selection
                const selectedEmployee = document.getElementById('employee_id').value;
                if (!selectedEmployee) {
                    e.preventDefault();
                    alert('Please select an employee from the gap analysis.');
                    return;
                }

                // Validate training materials selection
                const selectedMaterials = document.querySelectorAll('#training_materials option:checked');
                if (selectedMaterials.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one training material.');
                    return;
                }
            });
        });
    </script>

    <style>
        .form-radio {
            color: #3b82f6;
        }
        
        .form-radio:focus {
            ring-color: #3b82f6;
            ring-opacity: 0.5;
        }
        
        select[multiple] {
            background-image: none;
        }
        
        select[multiple] option:checked {
            background: #3b82f6;
            color: white;
        }
    </style>
</x-app-layout>
