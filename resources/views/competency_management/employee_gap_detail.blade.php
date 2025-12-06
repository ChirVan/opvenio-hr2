<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
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
                    <li class="text-gray-900 font-medium">{{ $employee->full_name }}</li>
                </ol>
            </nav>

            <!-- Employee Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-6 text-white mb-8">
                <div class="flex items-center">
                    <div class="h-16 w-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-2xl font-bold">
                        {{ substr($employee->full_name, 0, 2) }}
                    </div>
                    <div class="ml-6">
                        <h1 class="text-2xl font-bold">{{ $employee->full_name }}</h1>
                        <p class="text-blue-100">{{ $employee->job_title }} • Employee ID: {{ $employee->employee_id }}</p>
                        @if($employee->email)
                            <p class="text-blue-100 text-sm">{{ $employee->email }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Total Competencies</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $summary['total_competencies'] }}</p>
                        </div>
                        <div class="h-12 w-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class='bx bx-target-lock text-white text-2xl'></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Role Required</p>
                            <p class="text-2xl font-bold text-purple-900">{{ $summary['role_required'] ?? 0 }}</p>
                        </div>
                        <div class="h-12 w-12 bg-purple-500 rounded-lg flex items-center justify-center">
                            <i class='bx bx-briefcase text-white text-2xl'></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6 border border-indigo-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-indigo-600">Additional</p>
                            <p class="text-2xl font-bold text-indigo-900">{{ $summary['additional_competencies'] ?? 0 }}</p>
                        </div>
                        <div class="h-12 w-12 bg-indigo-500 rounded-lg flex items-center justify-center">
                            <i class='bx bx-plus-circle text-white text-2xl'></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600">Completed</p>
                            <p class="text-2xl font-bold text-green-900">{{ $summary['completed_assessments'] ?? 0 }}</p>
                        </div>
                        <div class="h-12 w-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class='bx bx-check text-white text-2xl'></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border border-yellow-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-600">No Assessment</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $summary['no_assessment'] }}</p>
                        </div>
                        <div class="h-12 w-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <i class='bx bx-question-mark text-white text-2xl'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Competency Analysis -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class='bx bx-chart-pie mr-2 text-indigo-600'></i>
                        Competency Analysis for {{ $employee->full_name }}
                    </h2>
                </div>

                @if($analysis->isEmpty())
                    <div class="text-center py-12">
                        <i class='bx bx-info-circle text-6xl text-gray-300 mb-4'></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Competencies Found</h3>
                        <p class="text-gray-500">No competency data available for {{ $employee->full_name }}.</p>
                    </div>
                @else
                    <!-- Role Required Competencies -->
                    @php
                        $roleRequired = $analysis->where('is_role_required', true);
                        $additional = $analysis->where('is_role_required', false);
                    @endphp

                    @if($roleRequired->isNotEmpty())
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class='bx bx-briefcase mr-2 text-purple-600'></i>
                                Role Required Competencies
                                <span class="ml-2 text-sm bg-purple-100 text-purple-800 px-2 py-1 rounded-full">{{ $roleRequired->count() }}</span>
                            </h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                                @foreach($roleRequired as $item)
                                    @include('competency_management.partials.competency_card', ['item' => $item, 'employee' => $employee])
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($additional->isNotEmpty())
                        <div class="p-6 {{ $roleRequired->isNotEmpty() ? 'border-t border-gray-200' : '' }}">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class='bx bx-plus-circle mr-2 text-indigo-600'></i>
                                Additional Competencies
                                <span class="ml-2 text-sm bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full">{{ $additional->count() }}</span>
                            </h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach($additional as $item)
                                    @include('competency_management.partials.competency_card', ['item' => $item, 'employee' => $employee])
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Back Button -->
            <div class="mt-8">
                <a href="{{ route('competency.gap-analysis') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                    <i class='bx bx-arrow-back mr-2'></i>
                    Back to Gap Analysis
                </a>
            </div>

        </div>
    </div>

    <!-- Assessment Assignment Modal -->
    <div id="assignmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Assign Assessment</h3>
                    <p id="assignmentText" class="text-gray-600 mb-6"></p>
                    <div class="flex justify-end space-x-3">
                        <button onclick="closeAssignmentModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors">
                            Cancel
                        </button>
                        <button id="confirmAssignment" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                            Assign Assessment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let assignmentData = {};

        function assignAssessment(employeeId, competencyId, employeeName, competencyName) {
            assignmentData = { employeeId, competencyId };
            document.getElementById('assignmentText').textContent = 
                `Assign a competency assessment for "${competencyName}" to ${employeeName}?`;
            document.getElementById('assignmentModal').classList.remove('hidden');
        }

        function closeAssignmentModal() {
            document.getElementById('assignmentModal').classList.add('hidden');
        }

        document.getElementById('confirmAssignment').addEventListener('click', function() {
            fetch('{{ route("competency.gap-analysis.assign") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    employee_id: assignmentData.employeeId,
                    competency_id: assignmentData.competencyId
                })
            })
            .then(response => response.json())
            .then(data => {
                closeAssignmentModal();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Assessment Assigned!',
                        text: data.message,
                        confirmButtonColor: '#3B82F6'
                    }).then(() => {
                        location.reload();
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
                closeAssignmentModal();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to assign assessment. Please try again.',
                    confirmButtonColor: '#EF4444'
                });
            });
        });

        function viewAssessmentDetails(employeeId, competencyId) {
            Swal.fire({
                icon: 'info',
                title: 'Assessment Details',
                text: 'Feature to view detailed assessment results will be implemented.',
                confirmButtonColor: '#3B82F6'
            });
        }
    </script>
</x-app-layout>