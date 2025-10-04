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
                                <option value="">Loading employees from gap analysis...</option>
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Only employees with gap analysis data are shown.</p>
                        </div>

                        <!-- Job Role (Read-only) -->
                        <div>
                            <label for="job_role" class="block text-sm font-medium text-gray-700 mb-2">
                                Job Role
                            </label>
                            <input type="text" id="job_role" name="job_role" readonly
                                   class="w-full px-4 py-3 border border-gray-300 rounded-md bg-gray-50 text-gray-600 cursor-not-allowed"
                                   placeholder="Select an employee to view their job role"
                                   value="">
                            <p class="text-sm text-gray-500 mt-1">Job role is automatically populated based on selected employee.</p>
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
            // Store employee data for job role lookup
            let employeeData = {};
            
            // Load employees from gap analysis
            function loadEmployeesFromGapAnalysis() {
                const employeeSelect = document.getElementById('employee_id');
                employeeSelect.innerHTML = '<option value="">Loading employees from gap analysis...</option>';
                
                fetch('/training/assign/gap-analysis-employees')
                    .then(response => response.json())
                    .then(employees => {
                        employeeSelect.innerHTML = '<option value="">Select an employee</option>';
                        if (employees.length > 0) {
                            // Store employee data for job role lookup
                            employeeData = {};
                            employees.forEach(employee => {
                                // Store employee data
                                employeeData[employee.id] = {
                                    job_role: employee.job_role,
                                    firstname: employee.employee_firstname,
                                    lastname: employee.employee_lastname,
                                    framework: employee.framework,
                                    competency_name: employee.competency_name
                                };
                                
                                const option = document.createElement('option');
                                option.value = employee.id;
                                option.textContent = `${employee.employee_lastname}, ${employee.employee_firstname} - ${employee.framework} (${employee.competency_name})`;
                                employeeSelect.appendChild(option);
                            });
                        } else {
                            employeeSelect.innerHTML = '<option value="">No employees found in gap analysis</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading employees:', error);
                        employeeSelect.innerHTML = '<option value="">Error loading employees from gap analysis</option>';
                    });
            }

            // Handle employee selection change to update job role
            document.getElementById('employee_id').addEventListener('change', function() {
                const selectedEmployeeId = this.value;
                const jobRoleInput = document.getElementById('job_role');
                
                if (selectedEmployeeId && employeeData[selectedEmployeeId]) {
                    jobRoleInput.value = employeeData[selectedEmployeeId].job_role || 'No job role specified';
                } else {
                    jobRoleInput.value = '';
                }
            });

            // Load employees on page load
            loadEmployeesFromGapAnalysis();

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
                    fetch(`/training/assign/materials/${catalogId}`)
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
