<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li>
                        <a href="{{ route('competency.gapanalysis') }}" class="hover:text-green-600 transition-colors">
                            <svg class="h-4 w-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Gap Analysis
                        </a>
                    </li>
                    <li>
                        <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </li>
                    <li class="text-gray-900 font-medium">Create New Assessment</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Create Gap Analysis</h1>
                        <p class="text-gray-600">Assign employee role mapping and competency assessment</p>
                    </div>
                    <button onclick="refreshEmployees()" 
                            class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 rounded-lg shadow-sm transition-all duration-200 transform hover:scale-105" 
                            title="Refresh employee data">
                        <i class='bx bx-refresh mr-2'></i> Refresh Data
                    </button>
                </div>
            </div>

            <!-- API Status Alert -->
            @if(empty($employees) || count($employees) == 0)
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-4 rounded-lg mb-6 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-red-800">No Employee Data Available</h3>
                            <p class="mt-1 text-sm text-red-700">Unable to load employees from external API. Please try refreshing or contact the administrator.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg mb-6 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-green-800">Employee Data Successfully Loaded</h3>
                            <p class="mt-1 text-sm text-green-700">{{ count($employees) }} employees available from external API.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-4 rounded-lg mb-6 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-semibold text-red-800">Please correct the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Form -->
            <form action="{{ route('competency.gapanalysis.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Employee Selection Section -->
                <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Employee Information
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Select Employee <span class="text-red-500">*</span>
                            </label>
                            <select name="employee_id" id="employee_id" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900">
                                <option value="">-- Choose an Employee --</option>
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
                        </div>

                        <!-- Employee Details Card -->
                        <div id="employeeInfoCard" 
                             class="hidden bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-5 animate-fade-in">
                            <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                                Employee Details
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white rounded-lg p-3 shadow-sm">
                                    <span class="text-xs text-gray-500 uppercase tracking-wide">Employee ID</span>
                                    <p id="displayEmployeeId" class="text-sm font-semibold text-gray-900 mt-1"></p>
                                </div>
                                <div class="bg-white rounded-lg p-3 shadow-sm">
                                    <span class="text-xs text-gray-500 uppercase tracking-wide">Email Address</span>
                                    <p id="displayEmail" class="text-sm font-semibold text-gray-900 mt-1"></p>
                                </div>
                                <div class="bg-white rounded-lg p-3 shadow-sm">
                                    <span class="text-xs text-gray-500 uppercase tracking-wide">Job Title</span>
                                    <p id="displayJobTitle" class="text-sm font-semibold text-gray-900 mt-1"></p>
                                </div>
                                <div class="bg-white rounded-lg p-3 shadow-sm">
                                    <span class="text-xs text-gray-500 uppercase tracking-wide">Employment Status</span>
                                    <span id="displayEmploymentStatus" class="inline-flex mt-1 px-3 py-1 text-xs font-semibold rounded-full"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Competency Selection Section -->
                <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                            Competency Details
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Select Competency <span class="text-red-500">*</span>
                            </label>
                            <select name="competency_id" id="competency_id" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 text-gray-900">
                                <option value="">-- Choose a Competency --</option>
                                @foreach($competencies as $competency)
                                    <option value="{{ $competency->id }}" 
                                        data-framework="{{ $competency->framework->framework_name ?? '' }}"
                                        data-level="{{ $competency->proficiency_levels }}">
                                        {{ $competency->competency_name }}
                                    </option>
                                @endforeach
<script>
document.addEventListener('DOMContentLoaded', function () {
    const competencySelect = document.getElementById('competency_id');
    const frameworkInput = document.getElementById('framework');
    const levelInput = document.getElementById('proficiency_level');
    competencySelect.addEventListener('change', function () {
        const selected = competencySelect.options[competencySelect.selectedIndex];
        frameworkInput.value = selected.getAttribute('data-framework') || '';
        const level = selected.getAttribute('data-level');
        let levelLabel = '';
        if (level == '1') levelLabel = 'Basic';
        else if (level == '2') levelLabel = 'Intermediate';
        else if (level == '3') levelLabel = 'Expert';
        else levelLabel = '';
        levelInput.value = levelLabel;
    });
});
</script>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Framework</label>
                                <input type="text" name="framework" id="framework" readonly
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Proficiency Level</label>
                    <input type="text" name="proficiency_level" id="proficiency_level" readonly
                        value="@php
                         $level = old('proficiency_level');
                         if ($level == '1') echo 'Basic';
                         elseif ($level == '2') echo 'Intermediate';
                         elseif ($level == '3') echo 'Expert';
                        @endphp"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assessment Details Section -->
                <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            Assessment Information
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="4" placeholder="Add any additional notes about this gap analysis..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-gray-900">{{ old('notes') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Assessment Date</label>
                                <input type="date" name="assessment_date" value="{{ old('assessment_date', date('Y-m-d')) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-gray-900">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Assessment Status</label>
                                <select name="status" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-gray-900">
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <a href="{{ route('competency.gapanalysis') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-all duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Create Gap Analysis
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>

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
                    // Show employee info card with animation
                    employeeInfoCard.classList.remove('hidden');
                    
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
                        displayEmploymentStatus.className = 'inline-flex mt-1 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800';
                    } else {
                        displayEmploymentStatus.className = 'inline-flex mt-1 px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800';
                    }

                    // Filter competencies by job title
                    Array.from(competencySelect.options).forEach(option => {
                        if (!option.value) return; // Skip placeholder
                        option.style.display = '';
                    });
                } else {
                    // Hide employee info card
                    employeeInfoCard.classList.add('hidden');
                    
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
            refreshButton.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Refreshing...';
            
            // Redirect to same page with refresh parameter
            window.location.href = '{{ route("competency.gapanalysis.create") }}?refresh=1';
        }
    </script>
</x-app-layout>