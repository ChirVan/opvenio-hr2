<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Competency Gap Analysis</h1>
                <p class="mt-2 text-sm text-gray-600">Real-time analysis of employee competencies against role requirements based on completed and evaluated assessments</p>
                <div class="mt-3 flex items-center text-sm">
                    <div class="flex items-center text-blue-600">
                        <i class='bx bx-info-circle mr-1'></i>
                        <span class="font-medium">Integration Notice:</span>
                    </div>
                    <span class="ml-2 text-gray-600">This page shows the same real-time data as the Skill Gap Analysis (Role Mapping) with evaluated assessment results</span>
                </div>
            </div>

            @if(!$apiStatus)
                <!-- API Error -->
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-red-800 font-medium">{{ $errorMessage ?? 'Unable to connect to employee API' }}</span>
                    </div>
                </div>
            @else

            <!-- Employee Competency Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Total Employees</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $summary['total_employees'] ?? 0 }}</p>
                        </div>
                        <div class="h-12 w-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class='bx bx-user text-white text-2xl'></i>
                        </div>
                    </div>
                    <p class="text-xs text-blue-600 mt-2">All employees</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600">Completed</p>
                            <p class="text-2xl font-bold text-green-900">{{ $summary['assessment_status_counts']['completed'] ?? 0 }}</p>
                        </div>
                        <div class="h-12 w-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class='bx bx-check-circle text-white text-2xl'></i>
                        </div>
                    </div>
                    <p class="text-xs text-green-600 mt-2">Assessments done</p>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border border-yellow-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-600">Pending</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $summary['assessment_status_counts']['pending'] ?? 0 }}</p>
                        </div>
                        <div class="h-12 w-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <i class='bx bx-time text-white text-2xl'></i>
                        </div>
                    </div>
                    <p class="text-xs text-yellow-600 mt-2">In progress</p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Need Assignment</p>
                            <p class="text-2xl font-bold text-purple-900">{{ $summary['assessment_status_counts']['not_assigned'] ?? 0 }}</p>
                        </div>
                        <div class="h-12 w-12 bg-purple-500 rounded-lg flex items-center justify-center">
                            <i class='bx bx-plus-circle text-white text-2xl'></i>
                        </div>
                    </div>
                    <p class="text-xs text-purple-600 mt-2">Can assign competencies</p>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border border-red-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-red-600">Gaps Identified</p>
                            <p class="text-2xl font-bold text-red-900">{{ $summary['gap_counts']['needs_improvement'] ?? 0 }}</p>
                        </div>
                        <div class="h-12 w-12 bg-red-500 rounded-lg flex items-center justify-center">
                            <i class='bx bx-trending-down text-white text-2xl'></i>
                        </div>
                    </div>
                    <p class="text-xs text-red-600 mt-2">Need improvement</p>
                </div>
                
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-orange-600">Active Plans</p>
                            <p class="text-2xl font-bold text-orange-900">
                                @php
                                    $activeGaps = collect($gapAnalysisResults ?? [])->where('has_active_gaps', true)->count();
                                @endphp
                                {{ $activeGaps }}
                            </p>
                        </div>
                        <div class="h-12 w-12 bg-orange-500 rounded-lg flex items-center justify-center">
                            <i class='bx bx-target-lock text-white text-2xl'></i>
                        </div>
                    </div>
                    <p class="text-xs text-orange-600 mt-2">Skill gap plans</p>
                </div>
            </div>

            <!-- Navigation Banner -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class='bx bx-transfer-alt text-2xl text-blue-600'></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-blue-800">Switch to Detailed Skill Gap Analysis</h3>
                            <p class="text-xs text-blue-700">View the same data with detailed competency breakdowns and development recommendations</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('competency.rolemapping') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition-all duration-200">
                            <i class='bx bx-chart-line mr-2'></i>
                            Detailed Analysis View
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <form method="GET" action="{{ route('competency.gap-analysis') }}" class="flex flex-wrap items-center gap-4">
                    
                    <!-- Employee Filter -->
                    <div class="flex flex-col">
                        <label for="employee_id" class="text-xs font-medium text-gray-700 mb-1">Employee:</label>
                        <select name="employee_id" id="employee_id" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }} - {{ $employee->job_title ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gap Status Filter -->
                    <div class="flex flex-col">
                        <label for="gap_status" class="text-xs font-medium text-gray-700 mb-1">Gap Status:</label>
                        <select name="gap_status" id="gap_status" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400">
                            <option value="">All Statuses</option>
                            <option value="needs_improvement" {{ request('gap_status') == 'needs_improvement' ? 'selected' : '' }}>Needs Improvement</option>
                            <option value="meets_requirement" {{ request('gap_status') == 'meets_requirement' ? 'selected' : '' }}>Meets Requirement</option>
                            <option value="exceeds_requirement" {{ request('gap_status') == 'exceeds_requirement' ? 'selected' : '' }}>Exceeds Requirement</option>
                            <option value="no_assessment" {{ request('gap_status') == 'no_assessment' ? 'selected' : '' }}>No Assessment</option>
                            <option value="needs_assignment" {{ request('gap_status') == 'needs_assignment' ? 'selected' : '' }}>Needs Assignment</option>
                            <option value="no_role_mapping" {{ request('gap_status') == 'no_role_mapping' ? 'selected' : '' }}>No Role Mapping</option>
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                            <i class='bx bx-filter-alt mr-1'></i> Filter
                        </button>
                        <a href="{{ route('competency.gap-analysis') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition-colors">
                            <i class='bx bx-reset mr-1'></i> Reset
                        </a>
                    </div>

                </form>
            </div>

            <!-- Gap Analysis Results -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class='bx bx-chart-pie mr-2 text-blue-600'></i>
                        Competency Gap Analysis Results
                    </h2>
                </div>

                @if($gapAnalysisResults->isEmpty())
                    <!-- Pre-Assessment Required State -->
                    <div class="text-center py-12">
                        <div class="mb-8">
                            <i class='bx bx-clipboard-minus text-6xl text-blue-300 mb-4'></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Pre-Assessment Required</h3>
                            <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                                Before we can analyze competency gaps, employees need to take pre-assessment tests to establish their current skill levels. 
                                This creates a baseline for accurate gap analysis against role requirements.
                            </p>
                        </div>

                        <!-- Pre-Assessment Workflow -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-8">
                            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                                <div class="flex items-center justify-center mb-4">
                                    <div class="h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg">1</div>
                                </div>
                                <h4 class="text-lg font-semibold text-blue-900 mb-2">Assign Pre-Assessment</h4>
                                <p class="text-sm text-blue-700">Assign diagnostic competency tests to employees to evaluate their current skill levels</p>
                            </div>
                            
                            <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                                <div class="flex items-center justify-center mb-4">
                                    <div class="h-12 w-12 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-lg">2</div>
                                </div>
                                <h4 class="text-lg font-semibold text-yellow-900 mb-2">Employee Takes Test</h4>
                                <p class="text-sm text-yellow-700">Employees complete pre-assessment tests to demonstrate their current competency levels</p>
                            </div>
                            
                            <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                                <div class="flex items-center justify-center mb-4">
                                    <div class="h-12 w-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-lg">3</div>
                                </div>
                                <h4 class="text-lg font-semibold text-green-900 mb-2">Analyze Gaps</h4>
                                <p class="text-sm text-green-700">Compare assessment results against role requirements to identify skill gaps</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a href="{{ route('competency.gap-analysis.pre-assessment') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                                <i class='bx bx-plus-circle mr-2 text-xl'></i>
                                Assign Pre-Assessment Tests
                            </a>
                            <a href="{{ route('learning-management.assessment-results.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                <i class='bx bx-chart-line mr-2'></i>
                                View Assessment Results
                            </a>
                        </div>

                        <!-- Help Text -->
                        <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200 max-w-3xl mx-auto">
                            <h5 class="font-semibold text-gray-900 mb-2">How Pre-Assessment Works:</h5>
                            <ul class="text-sm text-gray-600 text-left space-y-1">
                                <li>• <strong>Step 1:</strong> Create competency-based quizzes for each skill area</li>
                                <li>• <strong>Step 2:</strong> Assign these diagnostic tests to employees based on their job roles</li>
                                <li>• <strong>Step 3:</strong> Employees take the tests to demonstrate current competency levels</li>
                                <li>• <strong>Step 4:</strong> System compares results against role requirements to identify gaps</li>
                                <li>• <strong>Step 5:</strong> Recommend targeted training to close identified skill gaps</li>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table id="gapAnalysisTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-800 uppercase tracking-wider">Employee</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-800 uppercase tracking-wider">Job Title</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-800 uppercase tracking-wider">Competency</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">
                                        <div class="flex flex-col items-center">
                                            <span>Action Plan</span>
                                            <span class="text-[10px] font-normal text-gray-500 normal-case mt-0.5">(or Required Level)</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Current Level</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Gap Status</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Assessment Status</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="gapAnalysisTableBody" class="bg-white divide-y divide-gray-100">
                                @foreach($gapAnalysisResults as $result)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                    {{ substr($result['employee_name'], 0, 2) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $result['employee_name'] }}</div>
                                                    <div class="text-xs text-gray-500">ID: {{ $result['employee_id'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $result['job_title'] }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $result['competency_name'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $result['framework_name'] }}</div>
                                            @if(isset($result['total_competencies']) && $result['total_competencies'] > 1)
                                                <div class="text-xs text-blue-600 mt-1">
                                                    <i class='bx bx-list-ul mr-1'></i>
                                                    {{ $result['total_competencies'] }} total competencies
                                                    ({{ $result['completed_competencies'] }} completed)
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if(isset($result['has_active_gaps']) && $result['has_active_gaps'])
                                                <!-- Show skill gap action plans -->
                                                <div class="flex flex-col gap-2">
                                                    @foreach($result['skill_gap_assignments'] as $gap)
                                                        @php
                                                            $actionColors = [
                                                                'critical' => 'bg-red-100 text-red-700 border-red-300',
                                                                'training' => 'bg-blue-100 text-blue-700 border-blue-300',
                                                                'mentoring' => 'bg-green-100 text-green-700 border-green-300'
                                                            ];
                                                            $actionColor = $actionColors[$gap['action_type']] ?? 'bg-gray-100 text-gray-700 border-gray-300';
                                                            
                                                            $competencyLabels = [
                                                                'assignment_skills' => 'Assignment Skills',
                                                                'job_knowledge' => 'Job Knowledge',
                                                                'planning_organizing' => 'Planning & Organizing',
                                                                'accountability' => 'Accountability',
                                                                'efficiency_improvement' => 'Process Improvement'
                                                            ];
                                                            $compLabel = $competencyLabels[$gap['competency_key']] ?? $gap['competency_key'];
                                                        @endphp
                                                        <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-md text-xs font-medium border {{ $actionColor }}" 
                                                              title="{{ $gap['notes'] ?? 'No notes' }}">
                                                            <i class='bx bx-target-lock mr-1.5'></i>
                                                            <span class="font-semibold">{{ ucfirst($gap['action_type']) }}:</span>
                                                            <span class="ml-1">{{ $compLabel }}</span>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <!-- Show required level if available -->
                                                @if($result['required_level'])
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                        {{ $result['required_level'] }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($result['current_level'])
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                                    {{ match($result['current_level']) {
                                                        'Expert' => 'bg-purple-100 text-purple-800',
                                                        'Advanced' => 'bg-green-100 text-green-800',
                                                        'Intermediate' => 'bg-yellow-100 text-yellow-800',
                                                        'Beginner' => 'bg-orange-100 text-orange-800',
                                                        'Novice' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    } }}">
                                                    {{ $result['current_level'] }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">No assessment</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                {{ match($result['gap_status']) {
                                                    'exceeds_requirement' => 'bg-green-100 text-green-800',
                                                    'meets_requirement' => 'bg-blue-100 text-blue-800',
                                                    'needs_improvement' => 'bg-red-100 text-red-800',
                                                    'no_assessment' => 'bg-yellow-100 text-yellow-800',
                                                    'needs_assignment' => 'bg-purple-100 text-purple-800',
                                                    'no_role_mapping' => 'bg-orange-100 text-orange-800',
                                                    'critical_action' => 'bg-red-100 text-red-800',
                                                    'training_assigned' => 'bg-blue-100 text-blue-800',
                                                    'mentoring_assigned' => 'bg-green-100 text-green-800',
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    default => 'bg-gray-100 text-gray-800'
                                                } }}">
                                                @switch($result['gap_status'])
                                                    @case('exceeds_requirement')
                                                        🟢 Exceeds
                                                        @break
                                                    @case('meets_requirement')
                                                        🔵 Meets
                                                        @break
                                                    @case('needs_improvement')
                                                        🔴 Gap Identified
                                                        @break
                                                    @case('no_assessment')
                                                        🟡 No Assessment
                                                        @break
                                                    @case('needs_assignment')
                                                        🟣 Needs Assignment
                                                        @break
                                                    @case('no_role_mapping')
                                                        🟠 No Role Mapping
                                                        @break
                                                    @case('critical_action')
                                                        🔴 Critical Action Plan
                                                        @break
                                                    @case('training_assigned')
                                                        🔵 Training Plan Active
                                                        @break
                                                    @case('mentoring_assigned')
                                                        🟢 Mentoring Plan Active
                                                        @break
                                                    @case('completed')
                                                        ✅ Completed
                                                        @break
                                                    @default
                                                        Unknown
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @switch($result['assessment_status'])
                                                @case('completed')
                                                    <div class="text-xs">
                                                        <div class="font-medium text-green-600">✅ Completed</div>
                                                        @if($result['assessment_data'] && isset($result['assessment_data']['score']))
                                                            <div class="text-gray-500">Score: {{ number_format($result['assessment_data']['score'], 1) }}/5.0</div>
                                                        @endif
                                                        @if($result['assessment_data'] && isset($result['assessment_data']['evaluated_at']))
                                                            <div class="text-gray-400">{{ \Carbon\Carbon::parse($result['assessment_data']['evaluated_at'])->format('M d, Y') }}</div>
                                                        @endif
                                                    </div>
                                                    @break
                                                @case('pending')
                                                    <div class="text-xs">
                                                        <div class="font-medium text-yellow-600">⏳ Pending</div>
                                                        <div class="text-gray-500">Not started</div>
                                                    </div>
                                                    @break
                                                @case('in_progress')
                                                    <div class="text-xs">
                                                        <div class="font-medium text-blue-600">🔄 In Progress</div>
                                                        <div class="text-gray-500">Taking assessment</div>
                                                    </div>
                                                    @break
                                                @case('awaiting_evaluation')
                                                    <div class="text-xs">
                                                        <div class="font-medium text-orange-600">� Awaiting Evaluation</div>
                                                        <div class="text-gray-500">Needs review</div>
                                                    </div>
                                                    @break
                                                @case('not_assigned')
                                                    <div class="text-xs">
                                                        <div class="font-medium text-purple-600">➕ Not Assigned</div>
                                                        <div class="text-gray-500">Can assign competency</div>
                                                    </div>
                                                    @break
                                                @default
                                                    <div class="text-xs text-gray-400">Unknown Status</div>
                                            @endswitch
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                @if($result['assessment_status'] === 'completed')
                                                    <button onclick="viewAssessmentDetails('{{ $result['employee_id'] }}', '{{ $result['competency_id'] }}')" 
                                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 hover:text-green-900 transition-all duration-200" 
                                                            title="View Assessment Results">
                                                        <i class='bx bx-show text-sm'></i>
                                                    </button>
                                                @elseif($result['can_assign'] && $result['competency_id'])
                                                    <button onclick="assignAssessment('{{ $result['employee_id'] }}', '{{ $result['competency_id'] }}', '{{ $result['employee_name'] }}', '{{ $result['competency_name'] }}')" 
                                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 hover:text-blue-900 transition-all duration-200" 
                                                            title="Assign Assessment">
                                                        <i class='bx bx-plus text-sm'></i>
                                                    </button>
                                                @elseif($result['can_assign'] && !$result['competency_id'])
                                                    <button onclick="assignCompetency('{{ $result['employee_id'] }}', '{{ $result['employee_name'] }}')" 
                                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200 hover:text-purple-900 transition-all duration-200" 
                                                            title="Assign Competency">
                                                        <i class='bx bx-cog text-sm'></i>
                                                    </button>
                                                @elseif(in_array($result['assessment_status'], ['pending', 'in_progress', 'awaiting_evaluation']))
                                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-yellow-100 text-yellow-600" 
                                                          title="Assessment In Progress">
                                                        <i class='bx bx-time text-sm'></i>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400" 
                                                          title="No Action Available">
                                                        <i class='bx bx-x text-sm'></i>
                                                    </span>
                                                @endif
                                                
                                                @if($result['assessment_status'] === 'completed')
                                                    <a href="{{ route('competency.rolemapping.employee', $result['employee_id']) }}" 
                                                       class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 hover:text-indigo-900 transition-all duration-200" 
                                                       title="View Employee Skill Gap Details">
                                                        <i class='bx bx-user text-sm'></i>
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400" 
                                                          title="Employee Details - Assessment Required">
                                                        <i class='bx bx-user text-sm opacity-50'></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Gap Analysis Pagination -->
                    <div id="gapAnalysisPagination" class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Showing <span id="gapShowingFrom">0</span> to <span id="gapShowingTo">0</span> of <span id="gapTotalRecords">0</span> results
                        </div>
                        <div class="flex items-center gap-2">
                            <select id="gapRowsPerPage" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400">
                                <option value="10">10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                                <option value="100">100 per page</option>
                            </select>
                            <div id="gapPaginationButtons" class="flex gap-1">
                                <!-- Pagination buttons will be generated here -->
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @endif

            <!-- Assigned Competencies Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mt-8">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class='bx bx-check-shield mr-2 text-green-600'></i>
                                Assigned Competencies
                            </h2>
                            <p class="text-xs text-gray-600 mt-1">Track employees with assigned competencies and manage their training progress</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 bg-white rounded-full text-sm font-medium text-gray-700 border border-gray-200">
                                <i class='bx bx-user-check mr-1'></i>
                                <span id="totalAssigned">0</span> Assigned
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Status Filter -->
                        <select id="statusFilter" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-400">
                            <option value="">All Statuses</option>
                            <option value="assigned">Assigned</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="on_hold">On Hold</option>
                        </select>

                        <!-- Priority Filter -->
                        <select id="priorityFilter" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-400">
                            <option value="">All Priorities</option>
                            <option value="critical">Critical</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>

                        <!-- Assignment Type Filter -->
                        <select id="typeFilter" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-400">
                            <option value="">All Types</option>
                            <option value="development">Development</option>
                            <option value="gap_closure">Gap Closure</option>
                            <option value="skill_enhancement">Skill Enhancement</option>
                            <option value="mandatory">Mandatory</option>
                        </select>

                        <!-- Search -->
                        <input type="text" id="assignedSearch" placeholder="Search employee or competency..." 
                               class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-400 flex-1 min-w-[200px]">

                        <button onclick="refreshAssignedTable()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                            <i class='bx bx-refresh mr-1'></i> Refresh
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-800 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Total Competencies</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Status Breakdown</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Avg Progress</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Overall Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Training Room</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Next Deadline</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="assignedTableBody" class="bg-white divide-y divide-gray-100">
                            <!-- Will be populated via JavaScript -->
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class='bx bx-loader-alt bx-spin text-4xl text-gray-400 mb-2'></i>
                                        <p class="text-sm text-gray-500">Loading assigned competencies...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div id="paginationContainer" class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Showing <span id="showingFrom">0</span> to <span id="showingTo">0</span> of <span id="totalRecords">0</span> results
                    </div>
                    <div class="flex items-center gap-2">
                        <select id="assignedRowsPerPage" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-green-400">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                        <div id="paginationButtons" class="flex gap-1">
                            <!-- Pagination buttons will be generated here -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Assignment Modal -->
    <div id="assignmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 mb-4">Assign Assessment</h3>
                    <p id="assignmentText" class="text-gray-600 mb-6"></p>
                    <div id="competencySelector" class="hidden mb-4">
                        <label for="competencySelect" class="block text-sm font-medium text-gray-700 mb-2">Select Competency:</label>
                        <select id="competencySelect" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-400">
                            <option value="">Choose a competency...</option>
                            @if(isset($availableCompetencies))
                                @foreach($availableCompetencies as $competency)
                                    <option value="{{ $competency->id }}">{{ $competency->competency_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button onclick="closeAssignmentModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors">
                            Cancel
                        </button>
                        <button id="confirmAssignment" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                            Assign
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Training Materials Modal -->
    <div id="trainingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Assign Training Materials</h3>
                            <p id="trainingEmployeeInfo" class="text-sm text-gray-600 mt-1"></p>
                        </div>
                        <button onclick="closeTrainingModal()" class="text-gray-400 hover:text-gray-600">
                            <i class='bx bx-x text-2xl'></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Training Materials</label>
                        <input type="text" id="trainingSearch" placeholder="Search courses, videos, documents..." 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400">
                    </div>

                    <div id="trainingMaterialsList" class="space-y-3">
                        <!-- Training materials will be loaded here -->
                        <div class="text-center py-8">
                            <i class='bx bx-loader-alt bx-spin text-4xl text-gray-400 mb-2'></i>
                            <p class="text-sm text-gray-500">Loading available training materials...</p>
                        </div>
                    </div>
                </div>

                <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end space-x-3">
                    <button onclick="closeTrainingModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors">
                        Cancel
                    </button>
                    <button id="assignTrainingBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                        <i class='bx bx-check mr-1'></i>
                        Assign Selected
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Learning Assessment Modal -->
    <div id="assessmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Schedule Learning Assessment</h3>
                            <p id="assessmentEmployeeInfo" class="text-sm text-gray-600 mt-1"></p>
                        </div>
                        <button onclick="closeAssessmentModal()" class="text-gray-400 hover:text-gray-600">
                            <i class='bx bx-x text-2xl'></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assessment Type <span class="text-red-500">*</span></label>
                        <select id="assessmentType" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-purple-400">
                            <option value="">Select assessment type...</option>
                            <option value="quiz">Quiz</option>
                            <option value="practical">Practical Test</option>
                            <option value="interview">Interview Assessment</option>
                            <option value="project">Project-Based</option>
                            <option value="observation">Observation Assessment</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Quiz/Assessment <span class="text-red-500">*</span></label>
                        <select id="quizSelect" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-purple-400">
                            <option value="">Choose an assessment...</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                        <input type="date" id="assessmentDueDate" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-purple-400">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea id="assessmentNotes" rows="3" placeholder="Add any instructions or notes..." 
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-purple-400"></textarea>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-start">
                            <i class='bx bx-info-circle text-blue-600 text-lg mr-2 mt-0.5'></i>
                            <div class="text-xs text-blue-800">
                                <p class="font-medium mb-1">Assessment Guidelines:</p>
                                <ul class="space-y-1 ml-4 list-disc">
                                    <li>Employee will receive notification about the assessment</li>
                                    <li>Assessment results will be automatically recorded</li>
                                    <li>Progress will update the competency status</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end space-x-3">
                    <button onclick="closeAssessmentModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors">
                        Cancel
                    </button>
                    <button id="scheduleAssessmentBtn" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition-colors">
                        <i class='bx bx-calendar-check mr-1'></i>
                        Schedule Assessment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let assignmentData = {};
        let assignmentType = '';
        let currentAssignmentId = null;
        let currentEmployeeData = null;
        let assignedCompetenciesData = [];
        let currentPage = 1;
        let itemsPerPage = 10;

        // Gap Analysis Table Pagination Variables
        let gapCurrentPage = 1;
        let gapItemsPerPage = 10;
        let gapTableRows = [];

        // Load assigned competencies on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAssignedCompetencies();
            
            // Initialize Gap Analysis Table Pagination
            initGapAnalysisPagination();
            
            // Add filter event listeners
            document.getElementById('statusFilter').addEventListener('change', filterAssignedTable);
            document.getElementById('priorityFilter').addEventListener('change', filterAssignedTable);
            document.getElementById('typeFilter').addEventListener('change', filterAssignedTable);
            document.getElementById('assignedSearch').addEventListener('input', filterAssignedTable);
        });

        // ========== GAP ANALYSIS TABLE PAGINATION ==========
        function initGapAnalysisPagination() {
            const tableBody = document.getElementById('gapAnalysisTableBody');
            if (!tableBody) return;
            
            // Get all rows and store them
            gapTableRows = Array.from(tableBody.querySelectorAll('tr'));
            
            if (gapTableRows.length === 0) return;
            
            // Add event listener for rows per page change
            const rowsPerPageSelect = document.getElementById('gapRowsPerPage');
            if (rowsPerPageSelect) {
                rowsPerPageSelect.addEventListener('change', function() {
                    gapItemsPerPage = parseInt(this.value);
                    gapCurrentPage = 1;
                    renderGapAnalysisTable();
                });
            }
            
            // Initial render
            renderGapAnalysisTable();
        }

        function renderGapAnalysisTable() {
            const tableBody = document.getElementById('gapAnalysisTableBody');
            if (!tableBody || gapTableRows.length === 0) return;
            
            const totalItems = gapTableRows.length;
            const totalPages = Math.ceil(totalItems / gapItemsPerPage);
            const startIndex = (gapCurrentPage - 1) * gapItemsPerPage;
            const endIndex = Math.min(startIndex + gapItemsPerPage, totalItems);
            
            // Hide all rows first
            gapTableRows.forEach(row => row.style.display = 'none');
            
            // Show only rows for current page
            for (let i = startIndex; i < endIndex; i++) {
                gapTableRows[i].style.display = '';
            }
            
            // Update pagination info
            document.getElementById('gapShowingFrom').textContent = totalItems > 0 ? startIndex + 1 : 0;
            document.getElementById('gapShowingTo').textContent = endIndex;
            document.getElementById('gapTotalRecords').textContent = totalItems;
            
            // Generate pagination buttons
            generateGapPaginationButtons(totalPages);
        }

        function generateGapPaginationButtons(totalPages) {
            const container = document.getElementById('gapPaginationButtons');
            if (!container) return;
            
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }
            
            let buttons = '';
            
            // Previous button
            buttons += `
                <button onclick="changeGapPage(${gapCurrentPage - 1})" 
                        class="px-3 py-1 rounded ${gapCurrentPage === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'} text-sm transition-colors"
                        ${gapCurrentPage === 1 ? 'disabled' : ''}>
                    <i class='bx bx-chevron-left'></i>
                </button>
            `;
            
            // Page number buttons (show max 5 pages)
            let startPage = Math.max(1, gapCurrentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }
            
            if (startPage > 1) {
                buttons += `
                    <button onclick="changeGapPage(1)" class="px-3 py-1 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm transition-colors">1</button>
                `;
                if (startPage > 2) {
                    buttons += `<span class="px-2 text-gray-400">...</span>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                buttons += `
                    <button onclick="changeGapPage(${i})" 
                            class="px-3 py-1 rounded ${i === gapCurrentPage ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'} text-sm transition-colors">
                        ${i}
                    </button>
                `;
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    buttons += `<span class="px-2 text-gray-400">...</span>`;
                }
                buttons += `
                    <button onclick="changeGapPage(${totalPages})" class="px-3 py-1 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm transition-colors">${totalPages}</button>
                `;
            }
            
            // Next button
            buttons += `
                <button onclick="changeGapPage(${gapCurrentPage + 1})" 
                        class="px-3 py-1 rounded ${gapCurrentPage === totalPages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'} text-sm transition-colors"
                        ${gapCurrentPage === totalPages ? 'disabled' : ''}>
                    <i class='bx bx-chevron-right'></i>
                </button>
            `;
            
            container.innerHTML = buttons;
        }

        function changeGapPage(page) {
            const totalPages = Math.ceil(gapTableRows.length / gapItemsPerPage);
            if (page < 1 || page > totalPages) return;
            gapCurrentPage = page;
            renderGapAnalysisTable();
        }

        function loadAssignedCompetencies() {
            fetch('/api/assigned-competencies')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        assignedCompetenciesData = data.data;
                        renderAssignedTable();
                        updateTotalCount();
                        
                        // Add event listener for rows per page change
                        const assignedRowsPerPage = document.getElementById('assignedRowsPerPage');
                        if (assignedRowsPerPage) {
                            assignedRowsPerPage.addEventListener('change', function() {
                                itemsPerPage = parseInt(this.value);
                                currentPage = 1;
                                filterAssignedTable();
                            });
                        }
                    } else {
                        showEmptyState('Failed to load data');
                    }
                })
                .catch(error => {
                    console.error('Error loading assigned competencies:', error);
                    showEmptyState('Error loading data');
                });
        }

        function renderAssignedTable(filteredData = null) {
            const data = filteredData || assignedCompetenciesData;
            const tbody = document.getElementById('assignedTableBody');
            
            if (data.length === 0) {
                showEmptyState();
                return;
            }

            // Pagination
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const paginatedData = data.slice(startIndex, endIndex);
            
            tbody.innerHTML = paginatedData.map(item => `
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <!-- Employee Column -->
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                ${item.employee_name.substring(0, 2).toUpperCase()}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-semibold text-gray-900">${item.employee_name}</div>
                                <div class="text-xs text-gray-500">${item.job_title || 'N/A'}</div>
                            </div>
                        </div>
                    </td>
                    
                    <!-- Total Competencies -->
                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200">
                            <i class='bx bx-badge-check mr-1'></i>
                            ${item.total_competencies}
                        </div>
                    </td>
                    
                    <!-- Status Breakdown -->
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-1 flex-wrap">
                            ${item.completed_count > 0 ? `<span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium" title="Completed">✓ ${item.completed_count}</span>` : ''}
                            ${item.in_progress_count > 0 ? `<span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-medium" title="In Progress">⟳ ${item.in_progress_count}</span>` : ''}
                            ${item.assigned_count > 0 ? `<span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium" title="Assigned">◉ ${item.assigned_count}</span>` : ''}
                            ${item.on_hold_count > 0 ? `<span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full font-medium" title="On Hold">⏸ ${item.on_hold_count}</span>` : ''}
                        </div>
                    </td>
                    
                    <!-- Priority -->
                    <td class="px-6 py-4 text-center">
                        <div class="flex flex-col items-center gap-1">
                            ${getPriorityBadge(item.highest_priority)}
                            ${(item.critical_count + item.high_count) > 0 ? `<span class="text-xs text-gray-600">${item.critical_count + item.high_count} urgent</span>` : ''}
                        </div>
                    </td>
                    
                    <!-- Average Progress -->
                    <td class="px-6 py-4">
                        <div class="flex flex-col items-center">
                            ${item.has_evaluated_assessment ? `
                                <div class="w-full max-w-[120px] bg-gray-200 rounded-full h-2 mb-1">
                                    <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-2 rounded-full transition-all duration-300" style="width: ${item.evaluation_score_percent}%"></div>
                                </div>
                                <span class="text-xs font-bold text-emerald-700">${item.evaluation_score_percent}%</span>
                                <span class="text-[10px] text-gray-500">Score: ${item.evaluation_score}/5.0</span>
                            ` : `
                                <div class="w-full max-w-[120px] bg-gray-200 rounded-full h-2 mb-1">
                                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full transition-all duration-300" style="width: ${item.average_progress}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-700">${item.average_progress}%</span>
                            `}
                        </div>
                    </td>
                    
                    <!-- Overall Status -->
                    <td class="px-6 py-4 text-center">
                        ${item.has_evaluated_assessment ? 
                            `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-300">
                                <i class="bx bx-check-double mr-1"></i>Evaluated
                            </span>` : 
                            getStatusBadge(item.overall_status)
                        }
                    </td>
                    
                    <!-- Training Room -->
                    <td class="px-6 py-4 text-center">
                        ${item.training_count > 0 ? `
                            <div class="flex flex-col items-center gap-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <i class='bx bx-check-circle mr-1'></i>${item.training_count} Assigned
                                </span>
                                <a href="{{ route('training.room.index') }}" 
                                   class="inline-flex items-center text-xs text-emerald-600 hover:text-emerald-800 font-medium transition-colors">
                                    <i class='bx bx-tv mr-1'></i>Enter Room
                                </a>
                            </div>
                        ` : `
                            <div class="flex flex-col items-center gap-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    <i class='bx bx-minus-circle mr-1'></i>Not Assigned
                                </span>
                                <button onclick="bulkAssignTraining('${item.employee_id}', '${item.employee_name}')" 
                                        class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                    <i class='bx bx-plus mr-1'></i>Assign Training
                                </button>
                            </div>
                        `}
                    </td>
                    
                    <!-- Next Deadline -->
                    <td class="px-6 py-4 text-center">
                        <div class="text-sm text-gray-900">${item.earliest_target_date ? formatDate(item.earliest_target_date) : '-'}</div>
                        ${item.earliest_target_date ? getDateStatus(item.earliest_target_date) : ''}
                    </td>
                    
                    <!-- Actions -->
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center space-x-2">
                            <button onclick="viewEmployeeDetails('${item.employee_id}', '${item.employee_name}')" 
                                    class="inline-flex items-center justify-center h-8 px-3 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition-all duration-200 text-xs font-medium" 
                                    title="View All Competencies">
                                <i class='bx bx-show mr-1'></i>
                                View Details
                            </button>
                            <button onclick="manageEmployee('${item.employee_id}', '${item.employee_name}')" 
                                    class="inline-flex items-center justify-center h-8 px-3 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition-all duration-200 text-xs font-medium" 
                                    title="Manage Training & Assessments">
                                <i class='bx bx-cog mr-1'></i>
                                Manage
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');

            updatePagination(data.length);
        }

        function getAssignmentTypeBadge(type) {
            const badges = {
                'development': '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700"><i class="bx bx-trending-up mr-1"></i>Development</span>',
                'gap_closure': '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700"><i class="bx bx-target-lock mr-1"></i>Gap Closure</span>',
                'skill_enhancement': '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700"><i class="bx bx-star mr-1"></i>Enhancement</span>',
                'mandatory': '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700"><i class="bx bx-error-circle mr-1"></i>Mandatory</span>'
            };
            return badges[type] || '<span class="text-xs text-gray-400">Unknown</span>';
        }

        function getPriorityBadge(priority) {
            const badges = {
                'critical': '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-300">🔴 Critical</span>',
                'high': '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">🟠 High</span>',
                'medium': '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">🟡 Medium</span>',
                'low': '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">🔵 Low</span>'
            };
            return badges[priority] || '<span class="text-xs text-gray-400">-</span>';
        }

        function getStatusBadge(status) {
            const badges = {
                'assigned': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><i class="bx bx-check-circle mr-1"></i>Assigned</span>',
                'in_progress': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="bx bx-time mr-1"></i>In Progress</span>',
                'completed': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="bx bx-check-double mr-1"></i>Completed</span>',
                'on_hold': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><i class="bx bx-pause-circle mr-1"></i>On Hold</span>',
                'cancelled': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="bx bx-x-circle mr-1"></i>Cancelled</span>'
            };
            return badges[status] || '<span class="text-xs text-gray-400">Unknown</span>';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        }

        function getDateStatus(targetDate) {
            const today = new Date();
            const target = new Date(targetDate);
            const diffDays = Math.ceil((target - today) / (1000 * 60 * 60 * 24));
            
            if (diffDays < 0) {
                return '<div class="text-xs text-red-600 font-medium mt-1">⚠️ Overdue</div>';
            } else if (diffDays <= 7) {
                return '<div class="text-xs text-orange-600 font-medium mt-1">⏰ Due soon</div>';
            }
            return '';
        }

        function filterAssignedTable() {
            const statusFilter = document.getElementById('statusFilter').value;
            const priorityFilter = document.getElementById('priorityFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;
            const searchTerm = document.getElementById('assignedSearch').value.toLowerCase();

            let filtered = assignedCompetenciesData.filter(item => {
                const matchesStatus = !statusFilter || item.status === statusFilter;
                const matchesPriority = !priorityFilter || item.priority === priorityFilter;
                const matchesType = !typeFilter || item.assignment_type === typeFilter;
                const matchesSearch = !searchTerm || 
                    item.employee_name.toLowerCase().includes(searchTerm) ||
                    item.competency_name.toLowerCase().includes(searchTerm) ||
                    item.job_title.toLowerCase().includes(searchTerm);
                
                return matchesStatus && matchesPriority && matchesType && matchesSearch;
            });

            currentPage = 1;
            renderAssignedTable(filtered);
        }

        function refreshAssignedTable() {
            Swal.fire({
                title: 'Refreshing...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            loadAssignedCompetencies();
            setTimeout(() => {
                Swal.close();
                Swal.fire({
                    icon: 'success',
                    title: 'Refreshed!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
            }, 1000);
        }

        function showEmptyState(message = 'No assigned competencies found') {
            document.getElementById('assignedTableBody').innerHTML = `
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class='bx bx-folder-open text-6xl text-gray-300 mb-3'></i>
                            <p class="text-sm font-medium text-gray-500 mb-1">${message}</p>
                            <p class="text-xs text-gray-400">Assigned competencies will appear here</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        function updateTotalCount() {
            document.getElementById('totalAssigned').textContent = assignedCompetenciesData.length;
        }

        function updatePagination(totalItems) {
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const startItem = totalItems > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0;
            const endItem = Math.min(currentPage * itemsPerPage, totalItems);

            document.getElementById('showingFrom').textContent = startItem;
            document.getElementById('showingTo').textContent = endItem;
            document.getElementById('totalRecords').textContent = totalItems;

            const paginationContainer = document.getElementById('paginationButtons');
            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }

            let buttons = '';
            
            // Previous button
            buttons += `
                <button onclick="changePage(${currentPage - 1})" 
                        class="px-3 py-1 rounded ${currentPage === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'} text-sm transition-colors"
                        ${currentPage === 1 ? 'disabled' : ''}>
                    <i class='bx bx-chevron-left'></i>
                </button>
            `;
            
            // Page number buttons (show max 5 pages)
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }
            
            if (startPage > 1) {
                buttons += `
                    <button onclick="changePage(1)" class="px-3 py-1 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm transition-colors">1</button>
                `;
                if (startPage > 2) {
                    buttons += `<span class="px-2 text-gray-400">...</span>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                buttons += `
                    <button onclick="changePage(${i})" 
                            class="px-3 py-1 rounded ${i === currentPage ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'} text-sm transition-colors">
                        ${i}
                    </button>
                `;
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    buttons += `<span class="px-2 text-gray-400">...</span>`;
                }
                buttons += `
                    <button onclick="changePage(${totalPages})" class="px-3 py-1 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm transition-colors">${totalPages}</button>
                `;
            }
            
            // Next button
            buttons += `
                <button onclick="changePage(${currentPage + 1})" 
                        class="px-3 py-1 rounded ${currentPage === totalPages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'} text-sm transition-colors"
                        ${currentPage === totalPages ? 'disabled' : ''}>
                    <i class='bx bx-chevron-right'></i>
                </button>
            `;
            
            paginationContainer.innerHTML = buttons;
        }

        function changePage(page) {
            const totalPages = Math.ceil(assignedCompetenciesData.length / itemsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            filterAssignedTable();
        }

        // Training Modal Functions
        function openTrainingModal(assignmentId, employeeName, competencyName) {
            currentAssignmentId = assignmentId;
            document.getElementById('trainingEmployeeInfo').textContent = `${employeeName} - ${competencyName}`;
            document.getElementById('trainingModal').classList.remove('hidden');
            loadTrainingMaterials();
        }

        function closeTrainingModal() {
            document.getElementById('trainingModal').classList.add('hidden');
            document.getElementById('trainingSearch').value = '';
        }

        function loadTrainingMaterials() {
            // TODO: Replace with actual API call
            document.getElementById('trainingMaterialsList').innerHTML = `
                <div class="space-y-3">
                    <label class="flex items-start p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition-all">
                        <input type="checkbox" class="training-material-checkbox mt-1 h-5 w-5 text-blue-600 rounded" value="1">
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Introduction to Leadership</span>
                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">Video</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">Duration: 45 minutes</p>
                        </div>
                    </label>
                    <label class="flex items-start p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition-all">
                        <input type="checkbox" class="training-material-checkbox mt-1 h-5 w-5 text-blue-600 rounded" value="2">
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Team Management Best Practices</span>
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded">Document</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">12 pages PDF</p>
                        </div>
                    </label>
                    <label class="flex items-start p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition-all">
                        <input type="checkbox" class="training-material-checkbox mt-1 h-5 w-5 text-blue-600 rounded" value="3">
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Leadership Skills Course</span>
                                <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded">Course</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">8 modules, 4 hours</p>
                        </div>
                    </label>
                </div>
            `;
        }

        document.getElementById('assignTrainingBtn').addEventListener('click', function() {
            const selected = document.querySelectorAll('.training-material-checkbox:checked');
            if (selected.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Materials Selected',
                    text: 'Please select at least one training material',
                    confirmButtonColor: '#3B82F6'
                });
                return;
            }

            Swal.fire({
                icon: 'success',
                title: 'Training Materials Assigned!',
                text: `Successfully assigned ${selected.length} training material(s)`,
                confirmButtonColor: '#10B981'
            });
            closeTrainingModal();
        });

        // Learning Assessment Modal Functions
        function openLearningAssessment(assignmentId, employeeName, competencyName, employeeId, competencyId) {
            currentAssignmentId = assignmentId;
            currentEmployeeData = { employeeId, competencyId };
            document.getElementById('assessmentEmployeeInfo').textContent = `${employeeName} - ${competencyName}`;
            document.getElementById('assessmentModal').classList.remove('hidden');
            loadAvailableQuizzes();
        }

        function closeAssessmentModal() {
            document.getElementById('assessmentModal').classList.add('hidden');
            document.getElementById('assessmentType').value = '';
            document.getElementById('quizSelect').value = '';
            document.getElementById('assessmentDueDate').value = '';
            document.getElementById('assessmentNotes').value = '';
        }

        function loadAvailableQuizzes() {
            // TODO: Replace with actual API call
            document.getElementById('quizSelect').innerHTML = `
                <option value="">Choose an assessment...</option>
                <option value="1">Leadership Competency Quiz</option>
                <option value="2">Technical Skills Assessment</option>
                <option value="3">Communication Skills Test</option>
                <option value="4">Problem Solving Evaluation</option>
            `;
        }

        document.getElementById('scheduleAssessmentBtn').addEventListener('click', function() {
            const assessmentType = document.getElementById('assessmentType').value;
            const quizId = document.getElementById('quizSelect').value;
            const dueDate = document.getElementById('assessmentDueDate').value;
            const notes = document.getElementById('assessmentNotes').value;

            if (!assessmentType || !quizId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please fill in all required fields',
                    confirmButtonColor: '#F59E0B'
                });
                return;
            }

            // TODO: Send to API
            Swal.fire({
                icon: 'success',
                title: 'Assessment Scheduled!',
                text: 'The employee will be notified about the assessment',
                confirmButtonColor: '#8B5CF6'
            });
            closeAssessmentModal();
        });

        // Additional Action Functions
        function updateProgress(assignmentId) {
            Swal.fire({
                title: 'Update Progress',
                input: 'range',
                inputLabel: 'Progress Percentage',
                inputAttributes: {
                    min: 0,
                    max: 100,
                    step: 5
                },
                inputValue: 50,
                showCancelButton: true,
                confirmButtonColor: '#10B981'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Progress Updated!',
                        text: `Progress set to ${result.value}%`,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        }

        function changeStatus(assignmentId) {
            Swal.fire({
                title: 'Change Status',
                input: 'select',
                inputOptions: {
                    'assigned': 'Assigned',
                    'in_progress': 'In Progress',
                    'completed': 'Completed',
                    'on_hold': 'On Hold'
                },
                inputPlaceholder: 'Select status',
                showCancelButton: true,
                confirmButtonColor: '#3B82F6'
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        }

        function viewDetails(assignmentId) {
            Swal.fire({
                title: 'Assignment Details',
                html: '<p class="text-sm text-gray-600">Detailed view will be implemented</p>',
                confirmButtonColor: '#3B82F6'
            });
        }

        function removeAssignment(assignmentId) {
            Swal.fire({
                title: 'Remove Assignment?',
                text: "This action cannot be undone",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, remove it'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Removed!',
                        text: 'Assignment has been removed',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        }

        // View employee details with all competencies
        function viewEmployeeDetails(employeeId, employeeName) {
            const employeeData = assignedCompetenciesData.find(emp => emp.employee_id === employeeId);
            
            if (!employeeData) {
                Swal.fire('Error', 'Employee data not found', 'error');
                return;
            }

            // Compact competencies table - check for evaluated status
            let competenciesHtml = `
                <table class="w-full text-xs">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-2 py-1.5 text-left font-semibold text-gray-700">Competency</th>
                            <th class="px-2 py-1.5 text-center font-semibold text-gray-700">Status</th>
                            <th class="px-2 py-1.5 text-center font-semibold text-gray-700">Priority</th>
                            <th class="px-2 py-1.5 text-center font-semibold text-gray-700">Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        ${employeeData.competencies.map(comp => {
                            // Check if this competency has been evaluated
                            const isEvaluated = comp.is_evaluated || comp.evaluation_score || comp.status === 'completed';
                            const displayStatus = isEvaluated ? 'completed' : comp.status;
                            const score = comp.evaluation_score || comp.score || null;
                            
                            return `
                            <tr class="hover:bg-gray-50 ${isEvaluated ? 'bg-green-50/50' : ''}">
                                <td class="px-2 py-1.5">
                                    <div class="font-medium text-gray-900">${comp.competency_name}</div>
                                </td>
                                <td class="px-2 py-1.5 text-center">
                                    ${isEvaluated ? 
                                        '<span class="px-1.5 py-0.5 text-[10px] rounded bg-emerald-100 text-emerald-700">✓ Evaluated</span>' : 
                                        getCompactStatusBadge(comp.status)
                                    }
                                </td>
                                <td class="px-2 py-1.5 text-center">${getCompactPriorityBadge(comp.priority)}</td>
                                <td class="px-2 py-1.5 text-center">
                                    ${score ? 
                                        `<span class="font-bold text-emerald-700">${score}/5.0</span>` : 
                                        `<span class="text-gray-400">-</span>`
                                    }
                                </td>
                            </tr>
                        `}).join('')}
                    </tbody>
                </table>
            `;

            // Training & Assessment summary counts
            const trainingCount = employeeData.training_assignments?.length || 0;
            const assessmentCount = employeeData.assessment_assignments?.length || 0;

            Swal.fire({
                title: `<div class="flex items-center gap-2 text-base"><i class='bx bx-user-circle text-green-600'></i>${employeeName}</div>`,
                html: `
                    <div class="text-left">
                        <!-- Stats Row -->
                        <div class="grid grid-cols-6 gap-2 mb-3 text-center">
                            <div class="bg-gray-50 rounded p-2">
                                <div class="text-lg font-bold text-gray-900">${employeeData.total_competencies}</div>
                                <div class="text-[10px] text-gray-500">Total</div>
                            </div>
                            <div class="bg-green-50 rounded p-2">
                                <div class="text-lg font-bold text-green-600">${employeeData.completed_count}</div>
                                <div class="text-[10px] text-gray-500">Evaluated</div>
                            </div>
                            <div class="bg-yellow-50 rounded p-2">
                                <div class="text-lg font-bold text-yellow-600">${employeeData.in_progress_count}</div>
                                <div class="text-[10px] text-gray-500">Progress</div>
                            </div>
                            <div class="bg-blue-50 rounded p-2">
                                <div class="text-lg font-bold text-blue-600">${employeeData.assigned_count}</div>
                                <div class="text-[10px] text-gray-500">Assigned</div>
                            </div>
                            <div class="bg-indigo-50 rounded p-2">
                                <div class="text-lg font-bold text-indigo-600">${trainingCount}</div>
                                <div class="text-[10px] text-gray-500">Training</div>
                            </div>
                            <div class="bg-purple-50 rounded p-2">
                                <div class="text-lg font-bold text-purple-600">${assessmentCount}</div>
                                <div class="text-[10px] text-gray-500">Assess.</div>
                            </div>
                        </div>
                        
                        <!-- Competencies Table -->
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            ${competenciesHtml}
                        </div>
                    </div>
                `,
                width: '550px',
                showCloseButton: true,
                showConfirmButton: false
            });
        }

        // Compact status badge for table
        function getCompactStatusBadge(status) {
            const badges = {
                'assigned': '<span class="px-1.5 py-0.5 text-[10px] rounded bg-blue-100 text-blue-700">Assigned</span>',
                'in_progress': '<span class="px-1.5 py-0.5 text-[10px] rounded bg-yellow-100 text-yellow-700">In Progress</span>',
                'completed': '<span class="px-1.5 py-0.5 text-[10px] rounded bg-green-100 text-green-700">Completed</span>',
                'on_hold': '<span class="px-1.5 py-0.5 text-[10px] rounded bg-gray-100 text-gray-700">On Hold</span>',
                'cancelled': '<span class="px-1.5 py-0.5 text-[10px] rounded bg-red-100 text-red-700">Cancelled</span>'
            };
            return badges[status] || '<span class="text-[10px] text-gray-400">-</span>';
        }

        // Compact priority badge for table
        function getCompactPriorityBadge(priority) {
            const badges = {
                'critical': '<span class="px-1.5 py-0.5 text-[10px] rounded bg-red-100 text-red-700">Critical</span>',
                'high': '<span class="px-1.5 py-0.5 text-[10px] rounded bg-orange-100 text-orange-700">High</span>',
                'medium': '<span class="px-1.5 py-0.5 text-[10px] rounded bg-yellow-100 text-yellow-700">Medium</span>',
                'low': '<span class="px-1.5 py-0.5 text-[10px] rounded bg-blue-100 text-blue-700">Low</span>'
            };
            return badges[priority] || '<span class="text-[10px] text-gray-400">-</span>';
        }

        // Helper function for training status badge
        function getTrainingStatusBadge(status) {
            const badges = {
                'assigned': '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Assigned</span>',
                'in_progress': '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">In Progress</span>',
                'completed': '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Completed</span>',
                'overdue': '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Overdue</span>',
                'cancelled': '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Cancelled</span>'
            };
            return badges[status] || badges['assigned'];
        }

        // Helper function for assessment status badge
        function getAssessmentStatusBadge(status) {
            const badges = {
                'pending': '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Pending</span>',
                'in_progress': '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">In Progress</span>',
                'completed': '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Completed</span>',
                'overdue': '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Overdue</span>',
                'cancelled': '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Cancelled</span>'
            };
            return badges[status] || badges['pending'];
        }

        // Manage employee training and assessments
        function manageEmployee(employeeId, employeeName) {
            const employeeData = assignedCompetenciesData.find(emp => emp.employee_id === employeeId);
            
            if (!employeeData) {
                Swal.fire('Error', 'Employee data not found', 'error');
                return;
            }

            // Get total competencies count
            const totalCompetencies = employeeData.total_competencies || (employeeData.competencies ? employeeData.competencies.length : 0);
            
            // Check training assignments - only disable if ALL competencies have training assigned
            const trainingCount = employeeData.training_count || 0;
            const allTrainingAssigned = trainingCount >= totalCompetencies && totalCompetencies > 0;
            
            // Check assessment assignments - only disable if ALL competencies have assessments assigned
            const assessmentCount = employeeData.assessment_count || 0;
            const allAssessmentsAssigned = assessmentCount >= totalCompetencies && totalCompetencies > 0;

            let actionsHtml = `
                <div class="grid grid-cols-2 gap-4">
                    <button onclick="bulkAssignTraining('${employeeId}', '${employeeName}')" 
                            class="p-4 ${allTrainingAssigned ? 'bg-gray-100 cursor-not-allowed' : 'bg-blue-50 hover:bg-blue-100'} rounded-lg border-2 ${allTrainingAssigned ? 'border-gray-200' : 'border-blue-200'} transition-all"
                            ${allTrainingAssigned ? 'disabled' : ''}>
                        <i class='bx bx-book-content text-3xl ${allTrainingAssigned ? 'text-gray-400' : 'text-blue-600'} mb-2'></i>
                        <div class="font-semibold ${allTrainingAssigned ? 'text-gray-500' : 'text-gray-900'}">Assign Training</div>
                        <div class="text-xs ${allTrainingAssigned ? 'text-gray-400' : 'text-gray-600'} mt-1">
                            ${trainingCount > 0 ? `Already assigned (${trainingCount}/${totalCompetencies})` : 'For all competencies'}
                        </div>
                    </button>
                    <button onclick="bulkScheduleAssessments('${employeeId}', '${employeeName}')" 
                            class="p-4 ${allAssessmentsAssigned ? 'bg-gray-100 cursor-not-allowed' : 'bg-purple-50 hover:bg-purple-100'} rounded-lg border-2 ${allAssessmentsAssigned ? 'border-gray-200' : 'border-purple-200'} transition-all"
                            ${allAssessmentsAssigned ? 'disabled' : ''}>
                        <i class='bx bx-clipboard text-3xl ${allAssessmentsAssigned ? 'text-gray-400' : 'text-purple-600'} mb-2'></i>
                        <div class="font-semibold ${allAssessmentsAssigned ? 'text-gray-500' : 'text-gray-900'}">Schedule Assessments</div>
                        <div class="text-xs ${allAssessmentsAssigned ? 'text-gray-400' : 'text-gray-600'} mt-1">
                            ${assessmentCount > 0 ? `Already assigned (${assessmentCount}/${totalCompetencies})` : 'For all competencies'}
                        </div>
                    </button>
                    <button onclick="bulkUpdateProgress('${employeeId}', '${employeeName}')" 
                            class="p-4 bg-green-50 hover:bg-green-100 rounded-lg border-2 border-green-200 transition-all">
                        <i class='bx bx-trending-up text-3xl text-green-600 mb-2'></i>
                        <div class="font-semibold text-gray-900">Update Progress</div>
                        <div class="text-xs text-gray-600 mt-1">Bulk progress update</div>
                    </button>
                    <button onclick="viewEmployeeDetails('${employeeId}', '${employeeName}')" 
                            class="p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border-2 border-gray-200 transition-all">
                        <i class='bx bx-show text-3xl text-gray-600 mb-2'></i>
                        <div class="font-semibold text-gray-900">View Details</div>
                        <div class="text-xs text-gray-600 mt-1">See all competencies</div>
                    </button>
                </div>
            `;

            Swal.fire({
                title: `<div class="flex items-center gap-2"><i class='bx bx-cog text-green-600'></i>Manage ${employeeName}</div>`,
                html: actionsHtml,
                width: '600px',
                showCloseButton: true,
                showConfirmButton: false
            });
        }

        // Placeholder functions for bulk operations
        function bulkAssignTraining(employeeId, employeeName) {
            // Redirect to training assignment creation page with employee pre-selected
            window.location.href = `{{ route('training.assign.create') }}?employee_id=${employeeId}&employee_name=${encodeURIComponent(employeeName)}&source=gap_analysis`;
        }

        function bulkScheduleAssessments(employeeId, employeeName) {
            // Redirect to assessment assignment creation page with employee pre-selected
            // Include source=gap_analysis to indicate this is a skill gap requirement
            window.location.href = `{{ route('learning.hub.create') }}?employee_id=${employeeId}&employee_name=${encodeURIComponent(employeeName)}&source=gap_analysis`;
        }

        function bulkUpdateProgress(employeeId, employeeName) {
            Swal.fire({
                icon: 'info',
                title: 'Bulk Progress Update',
                text: `Feature coming soon! This will allow you to update progress for multiple competencies for ${employeeName}.`
            });
        }

        // Original functions for gap analysis
        function assignAssessment(employeeId, competencyId, employeeName, competencyName) {
            assignmentData = { employeeId, competencyId };
            assignmentType = 'assessment';
            document.getElementById('modalTitle').textContent = 'Assign Assessment';
            document.getElementById('assignmentText').textContent = 
                `Assign a competency assessment for "${competencyName}" to ${employeeName}?`;
            document.getElementById('competencySelector').classList.add('hidden');
            document.getElementById('confirmAssignment').textContent = 'Assign Assessment';
            document.getElementById('assignmentModal').classList.remove('hidden');
        }

        function assignCompetency(employeeId, employeeName) {
            assignmentData = { employeeId };
            assignmentType = 'competency';
            document.getElementById('modalTitle').textContent = 'Assign Competency';
            document.getElementById('assignmentText').textContent = 
                `Select a competency to assign to ${employeeName}:`;
            document.getElementById('competencySelector').classList.remove('hidden');
            document.getElementById('confirmAssignment').textContent = 'Assign Competency';
            document.getElementById('assignmentModal').classList.remove('hidden');
        }

        function closeAssignmentModal() {
            document.getElementById('assignmentModal').classList.add('hidden');
            document.getElementById('competencySelect').value = '';
        }

        document.getElementById('confirmAssignment').addEventListener('click', function() {
            if (assignmentType === 'competency') {
                const competencyId = document.getElementById('competencySelect').value;
                if (!competencyId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Please Select Competency',
                        text: 'You must select a competency to assign.',
                        confirmButtonColor: '#F59E0B'
                    });
                    return;
                }
                assignmentData.competencyId = competencyId;
            }

            fetch('{{ route("competency.gap-analysis.assign") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    employee_id: assignmentData.employeeId,
                    competency_id: assignmentData.competencyId,
                    assignment_type: assignmentType
                })
            })
            .then(response => response.json())
            .then(data => {
                closeAssignmentModal();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: assignmentType === 'competency' ? 'Competency Assigned!' : 'Assessment Assigned!',
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
                    text: 'Failed to assign. Please try again.',
                    confirmButtonColor: '#EF4444'
                });
            });
        });

        function viewAssessmentDetails(employeeId, competencyId) {
            // This could open a modal or redirect to assessment details
            Swal.fire({
                icon: 'info',
                title: 'Assessment Details',
                text: 'Feature to view detailed assessment results will be implemented.',
                confirmButtonColor: '#3B82F6'
            });
        }
    </script>
</x-app-layout>