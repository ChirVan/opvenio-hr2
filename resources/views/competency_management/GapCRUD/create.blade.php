<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Assign Employee Role Mapping</h2>
                    <button onclick="refreshEmployees()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded text-sm" title="Refresh employee data">
                        <i class='bx bx-refresh'></i> Refresh Employees
                    </button>
                </div>

                <!-- API Status Indicator -->
                @if(empty($employees) || count($employees) == 0)
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class='bx bx-x-circle text-red-400 text-xl'></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    <strong>No Employee Data:</strong> Unable to load employees from external API. Please try refreshing or contact administrator.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class='bx bx-check-circle text-green-400 text-xl'></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    <strong>Employee Data Loaded:</strong> {{ count($employees) }} employees available from external API.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('competency.gapanalysis.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Employee</label>
                        <select name="employee_id" id="employee_id" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" 
                                        data-job-title="{{ $employee->job_title }}"
                                        data-employment-status="{{ $employee->employment_status }}"
                                        data-email="{{ $employee->email }}"
                                        data-employee-id="{{ $employee->employee_id }}">
                                    {{ $employee->full_name }} ({{ $employee->employee_id }})
                                </option>
                            @endforeach
                        </select>
                        <div id="employeeDetails" class="mt-2 text-sm text-gray-600 space-y-1"></div>
                    </div>

                    <!-- Employee Information Display -->
                    <div class="mb-4 p-4 bg-gray-50 rounded-md" id="employeeInfoCard" style="display: none;">
                        <h4 class="font-medium text-gray-800 mb-2">Employee Information</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Employee ID:</span>
                                <span id="displayEmployeeId" class="font-medium"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Email:</span>
                                <span id="displayEmail" class="font-medium"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Job Title:</span>
                                <span id="displayJobTitle" class="font-medium"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Employment Status:</span>
                                <span id="displayEmploymentStatus" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Competency</label>
                        <select name="competency_id" id="competency_id" required class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Select Competency</option>
                            @foreach($roleMappings as $mapping)
                                <option value="{{ $mapping->competency->id ?? '' }}"
                                    data-framework="{{ $mapping->framework->framework_name ?? '' }}"
                                    data-level="{{ $mapping->proficiency_level ?? '' }}"
                                    data-role-name="{{ $mapping->role_name }}">
                                    {{ $mapping->competency->competency_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Framework</label>
                        <input type="text" name="framework" id="framework" class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Level</label>
                        <input type="text" name="proficiency_level" id="proficiency_level" class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Notes</label>
                        <textarea name="notes" class="w-full border border-gray-300 rounded-md px-3 py-2" rows="3" placeholder="Add any additional notes about this gap analysis...">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Assessment Date -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Assessment Date</label>
                        <input type="date" name="assessment_date" value="{{ old('assessment_date', date('Y-m-d')) }}" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>

                    <!-- Assessment Status -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Assessment Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        </select>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('competency.gapanalysis') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                            <i class='bx bx-arrow-back'></i> Back to Gap Analysis
                        </a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                            <i class='bx bx-check'></i> Create Gap Analysis
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const employeeSelect = document.getElementById('employee_id');
            const employeeInfoCard = document.getElementById('employeeInfoCard');
            const competencySelect = document.getElementById('competency_id');
            const frameworkInput = document.getElementById('framework');
            const levelInput = document.getElementById('proficiency_level');

            // Elements for displaying employee info
            const displayEmployeeId = document.getElementById('displayEmployeeId');
            const displayEmail = document.getElementById('displayEmail');
            const displayJobTitle = document.getElementById('displayJobTitle');
            const displayEmploymentStatus = document.getElementById('displayEmploymentStatus');

            function updateEmployeeDisplay() {
                const selectedEmployee = employeeSelect.options[employeeSelect.selectedIndex];
                
                if (selectedEmployee.value) {
                    // Show employee info card
                    employeeInfoCard.style.display = 'block';
                    
                    // Get employee data from option attributes
                    const employeeId = selectedEmployee.getAttribute('data-employee-id');
                    const email = selectedEmployee.getAttribute('data-email');
                    const jobTitle = selectedEmployee.getAttribute('data-job-title');
                    const employmentStatus = selectedEmployee.getAttribute('data-employment-status');
                    
                    // Update display elements
                    displayEmployeeId.textContent = employeeId || 'N/A';
                    displayEmail.textContent = email || 'N/A';
                    displayJobTitle.textContent = jobTitle || 'N/A';
                    displayEmploymentStatus.textContent = employmentStatus || 'N/A';
                    
                    // Style employment status badge
                    if (employmentStatus === 'Active') {
                        displayEmploymentStatus.className = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800';
                    } else {
                        displayEmploymentStatus.className = 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800';
                    }

                    // Filter competencies by job title (using job_title instead of job_role)
                    Array.from(competencySelect.options).forEach(option => {
                        if (!option.value) return; // Skip placeholder
                        const roleNameMatch = option.getAttribute('data-role-name');
                        // Show all competencies for now, or implement job title matching logic here
                        option.style.display = '';
                    });
                } else {
                    // Hide employee info card
                    employeeInfoCard.style.display = 'none';
                    
                    // Reset competency options
                    Array.from(competencySelect.options).forEach(option => {
                        option.style.display = '';
                    });
                }
                
                // Reset dependent fields
                competencySelect.value = '';
                frameworkInput.value = '';
                levelInput.value = '';
            }

            function updateFrameworkAndLevel() {
                const selected = competencySelect.options[competencySelect.selectedIndex];
                frameworkInput.value = selected.getAttribute('data-framework') || '';
                levelInput.value = selected.getAttribute('data-level') || '';
            }

            employeeSelect.addEventListener('change', updateEmployeeDisplay);
            competencySelect.addEventListener('change', updateFrameworkAndLevel);

            // On page load, if old input exists, trigger filtering
            if (employeeSelect.value) updateEmployeeDisplay();
            if (competencySelect.value) updateFrameworkAndLevel();
        });

        // Refresh employees function
        function refreshEmployees() {
            const refreshButton = document.querySelector('button[onclick="refreshEmployees()"]');
            
            // Show loading state
            refreshButton.disabled = true;
            refreshButton.innerHTML = '<i class="bx bx-loader-alt animate-spin"></i> Refreshing...';
            
            // Redirect to same page with refresh parameter
            window.location.href = '{{ route("competency.gapanalysis.create") }}?refresh=1';
        }
    </script>
</x-app-layout>