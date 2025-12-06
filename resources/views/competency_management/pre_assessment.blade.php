<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li>
                        <a href="{{ route('competency.gap-analysis') }}" class="hover:text-blue-600 transition-colors">
                            Gap Analysis
                        </a>
                    </li>
                    <li>
                        <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </li>
                    <li class="text-gray-900 font-medium">Pre-Assessment Assignment</li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Pre-Assessment Assignment</h1>
                <p class="mt-2 text-sm text-gray-600">Assign diagnostic competency tests to employees to establish their current skill baseline</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
                    <div class="flex items-center">
                        <div class="h-12 w-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                            <i class='bx bx-user-group text-white text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-600">Total Employees</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $employees->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                    <div class="flex items-center">
                        <div class="h-12 w-12 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                            <i class='bx bx-clipboard text-white text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-green-600">Available Tests</p>
                            <p class="text-2xl font-bold text-green-900">{{ $quizzes->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-6 border border-yellow-200">
                    <div class="flex items-center">
                        <div class="h-12 w-12 bg-yellow-500 rounded-lg flex items-center justify-center mr-4">
                            <i class='bx bx-time text-white text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-yellow-600">Pending Assignments</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $existingAssignments->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Methods -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Bulk Assignment by Role -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class='bx bx-group mr-2 text-blue-600'></i>
                            Bulk Assignment by Job Role
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Assign competency tests to all employees in specific job roles</p>
                    </div>
                    
                    <div class="p-6">
                        @if($roleMappings->isNotEmpty())
                            @foreach($roleMappings as $roleName => $requirements)
                                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="font-semibold text-gray-900">{{ $roleName }}</h3>
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                            {{ $requirements->count() }} competencies
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <p class="text-sm text-gray-600 mb-2">Required Competencies:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($requirements as $requirement)
                                                <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                                    {{ $requirement->competency ? $requirement->competency->competency_name : 'Unknown Competency' }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    @php
                                        $roleEmployees = $employees->where('job_title', $roleName);
                                    @endphp
                                    
                                    @if($roleEmployees->isNotEmpty())
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">
                                                {{ $roleEmployees->count() }} employees with this role
                                            </span>
                                            <button onclick="assignToRole('{{ $roleName }}')" 
                                                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">
                                                <i class='bx bx-plus mr-1'></i>
                                                Assign Tests
                                            </button>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-400">No employees found with this role</p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <i class='bx bx-info-circle text-4xl text-gray-300 mb-2'></i>
                                <p class="text-gray-500">No role mappings defined</p>
                                <a href="{{ route('competency.rolemapping.create') }}" class="text-blue-600 hover:text-blue-700 text-sm">
                                    Create role mappings first
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Individual Assignment -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class='bx bx-user mr-2 text-green-600'></i>
                            Individual Assignment
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Assign specific tests to individual employees</p>
                    </div>
                    
                    <div class="p-6">
                        <form id="individualAssignmentForm" class="space-y-4">
                            @csrf
                            
                            <!-- Employee Selection -->
                            <div>
                                <label for="employee_select" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Employee
                                </label>
                                <select id="employee_select" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                                    <option value="">Choose an employee...</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" data-job-title="{{ $employee->job_title }}">
                                            {{ $employee->full_name }} - {{ $employee->job_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Test Selection Options -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Select Assessment Tests
                                    </label>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="selectAllTests()" class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200">
                                            Select All
                                        </button>
                                        <button type="button" onclick="selectRoleBasedTests()" class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded hover:bg-green-200">
                                            Role-Based Only
                                        </button>
                                        <button type="button" onclick="clearAllTests()" class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded hover:bg-gray-200">
                                            Clear All
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="max-h-64 overflow-y-auto border border-gray-300 rounded-md">
                                    @php
                                        $competencyGroups = $quizzes->groupBy(function($quiz) {
                                            return $quiz->competency ? $quiz->competency->competency_name : 'Other';
                                        });
                                    @endphp
                                    
                                    @foreach($competencyGroups as $competencyName => $competencyQuizzes)
                                        <div class="border-b border-gray-200 last:border-b-0">
                                            <div class="bg-gray-50 px-3 py-2 text-sm font-medium text-gray-700 sticky top-0">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" class="competency-group-checkbox mr-2" 
                                                           data-competency="{{ $competencyName }}" 
                                                           onchange="toggleCompetencyGroup(this)">
                                                    {{ $competencyName }} ({{ $competencyQuizzes->count() }} tests)
                                                </label>
                                            </div>
                                            
                                            @foreach($competencyQuizzes as $quiz)
                                                <div class="px-6 py-2 hover:bg-gray-50">
                                                    <label class="flex items-start cursor-pointer">
                                                        <input type="checkbox" name="quiz_ids[]" value="{{ $quiz->id }}" 
                                                               class="quiz-checkbox mt-1 mr-3" 
                                                               data-competency="{{ $competencyName }}"
                                                               data-category="{{ $quiz->category ? $quiz->category->category_name : 'No Category' }}"
                                                               onchange="updateCompetencyGroupStatus()">
                                                        <div class="flex-1">
                                                            <div class="text-sm font-medium text-gray-900">{{ $quiz->quiz_title }}</div>
                                                            <div class="text-xs text-gray-500">
                                                                {{ $quiz->category ? $quiz->category->category_name : 'No Category' }}
                                                                @if($quiz->questions_count ?? 0)
                                                                    • {{ $quiz->questions_count }} questions
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-2 text-xs text-gray-500">
                                    <i class='bx bx-info-circle mr-1'></i>
                                    Select multiple tests to create a comprehensive skill assessment profile. 
                                    The system will analyze results across all competencies to identify strengths and gaps.
                                </div>
                            </div>

                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Due Date
                                </label>
                                <input type="date" id="due_date" 
                                       value="{{ now()->addDays(7)->format('Y-m-d') }}"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Notes (Optional)
                                </label>
                                <textarea id="notes" rows="3" 
                                          placeholder="Pre-assessment for competency gap analysis..."
                                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"></textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                <i class='bx bx-plus mr-2'></i>
                                Assign Test
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quick Access Links -->
            <div class="mt-8 bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Access</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('learning-management.assessment-results.index') }}" 
                       class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 transition-colors">
                        <i class='bx bx-chart-line text-blue-600 text-xl mr-3'></i>
                        <div>
                            <div class="font-medium text-gray-900">View Results</div>
                            <div class="text-sm text-gray-500">Check assessment results</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('learning.quiz') }}" 
                       class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-green-300 transition-colors">
                        <i class='bx bx-plus-circle text-green-600 text-xl mr-3'></i>
                        <div>
                            <div class="font-medium text-gray-900">Create Quiz</div>
                            <div class="text-sm text-gray-500">Add new competency test</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('competency.rolemapping') }}" 
                       class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-purple-300 transition-colors">
                        <i class='bx bx-sitemap text-purple-600 text-xl mr-3'></i>
                        <div>
                            <div class="font-medium text-gray-900">Role Mapping</div>
                            <div class="text-sm text-gray-500">Define role requirements</div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Individual assignment form submission
        document.getElementById('individualAssignmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const employeeId = document.getElementById('employee_select').value;
            const quizId = document.getElementById('quiz_select').value;
            const dueDate = document.getElementById('due_date').value;
            const notes = document.getElementById('notes').value;
            
            if (!employeeId || !quizId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please select both an employee and a test.',
                    confirmButtonColor: '#EF4444'
                });
                return;
            }
            
            // This would normally submit to your assessment assignment endpoint
            fetch('{{ route("competency.gap-analysis.assign") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    employee_id: employeeId,
                    quiz_id: quizId,
                    due_date: dueDate,
                    notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Assessment Assigned!',
                        text: data.message,
                        confirmButtonColor: '#10B981'
                    }).then(() => {
                        this.reset();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Assignment Failed',
                        text: data.message,
                        confirmButtonColor: '#EF4444'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to assign assessment. Please try again.',
                    confirmButtonColor: '#EF4444'
                });
            });
        });

        // Bulk role assignment
        function assignToRole(roleName) {
            Swal.fire({
                title: `Assign Tests to ${roleName}?`,
                text: 'This will assign all required competency tests to employees with this job role.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3B82F6',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, assign tests'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Implementation for bulk role assignment
                    Swal.fire({
                        icon: 'info',
                        title: 'Bulk Assignment',
                        text: 'Bulk role assignment feature will be implemented next.',
                        confirmButtonColor: '#3B82F6'
                    });
                }
            });
        }
    </script>
</x-app-layout>