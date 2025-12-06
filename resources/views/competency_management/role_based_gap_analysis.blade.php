<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg mb-6 shadow-sm animate-fade-in">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-green-800 font-medium">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700 transition-colors">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Role-Based Gap Analysis</h1>
                <p class="mt-2 text-sm text-gray-600">Compare employee current skills against role requirements to identify competency gaps</p>
            </div>

            <!-- Action Bar -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <h3 class="text-lg font-semibold text-gray-900">Gap Analysis Overview</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $gapAnalysisResults->count() }} Records
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('learning.hub.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow transition-all duration-200">
                            <i class='bx bx-plus mr-2'></i>
                            Assign Assessment
                        </a>
                        <button onclick="window.location.reload()" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition-all duration-200">
                            <i class='bx bx-refresh mr-2'></i>
                            Refresh Data
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            @php
                $totalGaps = $gapAnalysisResults->count();
                $needsImprovement = $gapAnalysisResults->where('gap_status', 'needs_improvement')->count();
                $meetsRequirement = $gapAnalysisResults->where('gap_status', 'meets_requirement')->count();
                $exceedsRequirement = $gapAnalysisResults->where('gap_status', 'exceeds_requirement')->count();
                $noAssessment = $gapAnalysisResults->where('gap_status', 'no_assessment')->count();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class='bx bx-chart-pie text-2xl text-blue-600'></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">Total Gaps</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $totalGaps }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-red-50 to-pink-50 p-4 rounded-lg border border-red-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class='bx bx-trend-down text-2xl text-red-600'></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">Needs Improvement</p>
                            <p class="text-2xl font-bold text-red-900">{{ $needsImprovement }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class='bx bx-check-circle text-2xl text-green-600'></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">Meets Requirement</p>
                            <p class="text-2xl font-bold text-green-900">{{ $meetsRequirement }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class='bx bx-trending-up text-2xl text-purple-600'></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-purple-800">Exceeds</p>
                            <p class="text-2xl font-bold text-purple-900">{{ $exceedsRequirement }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-4 rounded-lg border border-yellow-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class='bx bx-help-circle text-2xl text-yellow-600'></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-800">No Assessment</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $noAssessment }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gap Analysis Results Table -->
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-800 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-800 uppercase tracking-wider">Job Title</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-800 uppercase tracking-wider">Competency</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-800 uppercase tracking-wider">Framework</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Required Level</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Current Level</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Gap Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($gapAnalysisResults as $result)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $result['employee_name'] }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $result['employee_id'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $result['job_title'] }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $result['competency_name'] }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $result['framework_name'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $requiredLevel = $result['required_level'];
                                            $requiredColor = match($requiredLevel) {
                                                'Beginner' => 'bg-red-100 text-red-800',
                                                'Intermediate' => 'bg-yellow-100 text-yellow-800',
                                                'Advanced' => 'bg-blue-100 text-blue-800',
                                                'Expert' => 'bg-purple-100 text-purple-800',
                                                'Master' => 'bg-green-100 text-green-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $requiredColor }}">
                                            {{ $requiredLevel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($result['current_level'])
                                            @php
                                                $currentLevel = $result['current_level'];
                                                $currentColor = match($currentLevel) {
                                                    'Beginner' => 'bg-red-100 text-red-800',
                                                    'Intermediate' => 'bg-yellow-100 text-yellow-800',
                                                    'Advanced' => 'bg-blue-100 text-blue-800',
                                                    'Expert' => 'bg-purple-100 text-purple-800',
                                                    'Master' => 'bg-green-100 text-green-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $currentColor }}">
                                                {{ $currentLevel }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                                Not Assessed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $gapStatus = $result['gap_status'];
                                            $statusConfig = match($gapStatus) {
                                                'needs_improvement' => ['bg-red-100 text-red-800', 'Needs Improvement', 'bx-trend-down'],
                                                'meets_requirement' => ['bg-green-100 text-green-800', 'Meets Requirement', 'bx-check-circle'],
                                                'exceeds_requirement' => ['bg-purple-100 text-purple-800', 'Exceeds Requirement', 'bx-trending-up'],
                                                'no_assessment' => ['bg-yellow-100 text-yellow-800', 'No Assessment', 'bx-help-circle'],
                                                default => ['bg-gray-100 text-gray-800', 'Unknown', 'bx-question-mark']
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusConfig[0] }}">
                                            <i class='bx {{ $statusConfig[2] }} mr-1'></i>
                                            {{ $statusConfig[1] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            @if($result['gap_status'] === 'no_assessment')
                                                <a href="{{ route('learning.hub.create') }}" 
                                                   class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 hover:text-green-900 transition-all duration-200" 
                                                   title="Assign Assessment">
                                                    <i class='bx bx-plus text-sm'></i>
                                                </a>
                                            @else
                                                <button onclick="viewAssessmentDetails('{{ $result['employee_id'] }}', '{{ $result['competency_id'] }}')" 
                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 hover:text-blue-900 transition-all duration-200" 
                                                        title="View Assessment">
                                                    <i class='bx bx-show text-sm'></i>
                                                </button>
                                            @endif
                                            
                                            @if($result['gap_status'] === 'needs_improvement')
                                                <button onclick="suggestTraining('{{ $result['employee_id'] }}', '{{ $result['competency_id'] }}')" 
                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200 hover:text-purple-900 transition-all duration-200" 
                                                        title="Suggest Training">
                                                    <i class='bx bx-book text-sm'></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-16">
                                        <div class="text-center">
                                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No gap analysis data found</h3>
                                            <p class="text-gray-500 mb-4">Either no role mappings are defined or no employees match the defined roles</p>
                                            <div class="flex justify-center space-x-4">
                                                <a href="{{ route('competency.rolemapping.create') }}" 
                                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition-colors">
                                                    <i class='bx bx-plus text-lg mr-2'></i>
                                                    Add Role Mapping
                                                </a>
                                                <a href="{{ route('learning.hub.create') }}" 
                                                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow transition-colors">
                                                    <i class='bx bx-plus text-lg mr-2'></i>
                                                    Assign Assessment
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

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

    <!-- JavaScript for enhanced functionality -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function viewAssessmentDetails(employeeId, competencyId) {
            // This would open a modal or navigate to assessment details
            console.log('View assessment for employee:', employeeId, 'competency:', competencyId);
            // TODO: Implement assessment details view
        }

        function suggestTraining(employeeId, competencyId) {
            Swal.fire({
                title: 'Training Recommendations',
                text: 'This feature will suggest relevant training programs based on the identified skill gap.',
                icon: 'info',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Understood'
            });
            // TODO: Implement training suggestions based on competency gaps
        }
    </script>
</x-app-layout>