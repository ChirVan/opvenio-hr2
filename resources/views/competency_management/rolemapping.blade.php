<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Compact Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow p-4 mb-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold">
                            @if($employeeGapAnalysis->count() === 1)
                                Employee Skill Gap Analysis
                            @else
                                Skill Gap Analysis
                            @endif
                        </h1>
                        @if($employeeGapAnalysis->count() === 1)
                            <p class="text-blue-100 text-xs mt-1">{{ $employeeGapAnalysis->first()['employee_name'] }} | {{ $employeeGapAnalysis->first()['job_title'] }}</p>
                        @else
                            <p class="text-blue-100 text-xs mt-1">Compare competencies against role requirements</p>
                        @endif
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg px-3 py-2 text-center">
                        <div class="text-lg font-bold">{{ $employeeGapAnalysis->count() }}</div>
                        <div class="text-[10px]">
                            @if($employeeGapAnalysis->count() === 1)
                                Detailed View
                            @else
                                Employees
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($employeeGapAnalysis->count() === 1)
                <!-- Compact Back Navigation -->
                <div class="bg-white border border-gray-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class='bx bx-arrow-back text-gray-600'></i>
                            <span class="text-xs text-gray-700 font-medium">Individual employee view</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('competency.rolemapping') }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded transition-colors">
                                <i class='bx bx-grid-alt mr-1'></i>
                                Overview
                            </a>
                            <a href="{{ route('competency.gap-analysis') }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors">
                                <i class='bx bx-table mr-1'></i>
                                Gap Analysis
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if($employeeGapAnalysis->count() > 1)
                <!-- Compact Navigation Banner -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class='bx bx-transfer-alt text-lg text-green-600'></i>
                            <div>
                                <h3 class="text-sm font-semibold text-green-900">Switch to Tabular View</h3>
                                <p class="text-xs text-green-700">View data in comprehensive table format</p>
                            </div>
                        </div>
                        <a href="{{ route('competency.gap-analysis') }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors">
                            <i class='bx bx-table mr-1'></i>
                            Table View
                        </a>
                    </div>
                </div>
            @endif

            <!-- Employee Selection -->
            <div class="bg-white shadow rounded-lg p-4 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-semibold text-gray-800">
                        @if($employeeGapAnalysis->count() === 1)
                            Analysis Results
                        @else
                            Select Employee
                        @endif
                    </h2>
                    <button onclick="refreshAnalysis()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded flex items-center gap-1.5 text-xs transition-colors">
                        <i class="bx bx-refresh"></i>
                        Refresh
                    </button>
                </div>
                
                @if($employeeGapAnalysis->count() === 1)
                    <!-- Single Employee Display - Compact -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                        @php
                            $employee = $employeeGapAnalysis->first();
                            $scoreColor = $employee['overall_score'] >= 90 ? 'text-blue-600' : 
                                        ($employee['overall_score'] >= 80 ? 'text-green-600' : 'text-orange-600');
                            $statusColor = $employee['status'] === 'High Performer' ? 'bg-blue-100 text-blue-800' :
                                         ($employee['status'] === 'Pipeline Ready' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800');
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($employee['employee_name'], 0, 2) }}
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-800">{{ $employee['employee_name'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $employee['job_title'] }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $employee['employee_id'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold {{ $scoreColor }}">{{ $employee['overall_score'] }}%</div>
                                <span class="inline-block px-2 py-1 {{ $statusColor }} rounded text-xs font-medium mt-1">{{ $employee['status'] }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Multiple Employee Selection - Compact -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @if($employeeGapAnalysis->count() > 0)
                            @foreach($employeeGapAnalysis->take(3) as $index => $employee)
                                @php
                                    $scoreColor = $employee['overall_score'] >= 90 ? 'text-blue-600' : 
                                                ($employee['overall_score'] >= 80 ? 'text-green-600' : 'text-orange-600');
                                    $statusColor = $employee['status'] === 'High Performer' ? 'bg-blue-100 text-blue-800' :
                                                 ($employee['status'] === 'Pipeline Ready' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800');
                                    $isActive = $index === 0 ? 'active' : '';
                                    $borderColor = $index === 0 ? 'border-blue-400' : 'border-gray-200';
                                @endphp
                                <div class="border-2 {{ $borderColor }} rounded-lg p-3 cursor-pointer hover:border-blue-400 transition-colors employee-card {{ $isActive }}" 
                                     onclick="selectEmployee('{{ $employee['employee_id'] }}', '{{ $employee['employee_name'] }}', {{ $employee['overall_score'] }}, '{{ $employee['status'] }}')">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-sm font-semibold text-gray-800">{{ $employee['employee_name'] }}</h3>
                                            <p class="text-xs text-gray-600">{{ $employee['job_title'] }}</p>
                                            <p class="text-[10px] text-gray-500">ID: {{ $employee['employee_id'] }}</p>
                                        </div>
                                        <div class="text-right ml-2">
                                            <div class="text-base font-bold {{ $scoreColor }}">{{ $employee['overall_score'] }}%</div>
                                            <span class="inline-block px-1.5 py-0.5 {{ $statusColor }} rounded text-[10px] mt-1">{{ $employee['status'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-3 text-center py-6">
                                <i class="bx bx-user-x text-3xl text-gray-400 mb-2"></i>
                                <h3 class="text-sm font-semibold text-gray-600 mb-1">No Qualified Employees</h3>
                                <p class="text-xs text-gray-500 mb-3">No employees with evaluated assessments found</p>
                                <a href="{{ route('assessment.results') }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 transition-colors">
                                    <i class="bx bx-clipboard-check mr-1"></i>
                                    Review Assessments
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Gap Analysis Results -->
            <div id="gapAnalysisResults" class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-semibold text-gray-800 flex items-center">
                            <i class="bx bx-analysis text-blue-600 mr-1.5"></i>
                            <span id="selectedEmployeeName">{{ $employeeGapAnalysis->first()['employee_name'] ?? 'No Employee Selected' }}</span>
                        </h2>
                        <p class="text-xs text-gray-600 mt-0.5">Competencies vs requirements</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="bg-blue-50 rounded px-2 py-1.5 text-center">
                            <div class="text-sm font-bold text-blue-600" id="overallScore">{{ $employeeGapAnalysis->first()['overall_score'] ?? 0 }}%</div>
                            <div class="text-[9px] text-gray-600">Score</div>
                        </div>
                        <div class="bg-orange-50 rounded px-2 py-1.5 text-center">
                            <div class="text-sm font-bold text-orange-600" id="gapCount">
                                @if($employeeGapAnalysis->first())
                                    @php
                                        $firstEmployee = $employeeGapAnalysis->first();
                                        $gapCount = 0;
                                        foreach($firstEmployee['competencies'] as $comp) {
                                            if($comp['current'] < $comp['required']) $gapCount++;
                                        }
                                    @endphp
                                    {{ $gapCount }}
                                @else
                                    0
                                @endif
                            </div>
                            <div class="text-[9px] text-gray-600">Gaps</div>
                        </div>
                        <div class="bg-green-50 rounded px-2 py-1.5 text-center">
                            <div class="text-sm font-bold text-green-600" id="activePlansCount">
                                @if($employeeGapAnalysis->first() && isset($employeeGapAnalysis->first()['skill_gap_assignments']))
                                    {{ count($employeeGapAnalysis->first()['skill_gap_assignments']) }}
                                @else
                                    0
                                @endif
                            </div>
                            <div class="text-[9px] text-gray-600">Plans</div>
                        </div>
                    </div>
                </div>

                <!-- Active Skill Gap Improvement Plans - Table Format -->
                <div id="activeSkillGapsSection">
                    @if($employeeGapAnalysis->first() && isset($employeeGapAnalysis->first()['has_active_gaps']) && $employeeGapAnalysis->first()['has_active_gaps'])
                        <div class="mb-6 bg-white border border-orange-200 rounded-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-orange-50 to-red-50 px-4 py-3 border-b border-orange-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class='bx bx-target-lock text-xl text-orange-600 mr-2'></i>
                                        <h3 class="text-sm font-semibold text-orange-900">Active Skill Gap Improvement Plans</h3>
                                        <span class="ml-2 px-2 py-0.5 bg-orange-600 text-white text-xs rounded-full">
                                            {{ count($employeeGapAnalysis->first()['skill_gap_assignments']) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('competency.competencies.create', ['employee_id' => $employeeGapAnalysis->first()['employee_id']]) }}" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-xs font-medium transition-colors">
                                        <i class='bx bx-plus-circle text-sm'></i>
                                        Assign Competency
                                    </a>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action Type</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Competency</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Assigned Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($employeeGapAnalysis->first()['skill_gap_assignments'] as $gap)
                                            @php
                                                $actionBadges = [
                                                    'critical' => 'bg-red-100 text-red-800 border-red-300',
                                                    'training' => 'bg-blue-100 text-blue-800 border-blue-300',
                                                    'mentoring' => 'bg-green-100 text-green-800 border-green-300'
                                                ];
                                                $actionBadge = $actionBadges[$gap['action_type']] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                                                
                                                $actionIcons = [
                                                    'critical' => 'bx-error-circle',
                                                    'training' => 'bx-book-reader',
                                                    'mentoring' => 'bx-user-voice'
                                                ];
                                                $actionIcon = $actionIcons[$gap['action_type']] ?? 'bx-target-lock';
                                                
                                                $competencyLabels = [
                                                    'assignment_skills' => 'Assignment Skills',
                                                    'job_knowledge' => 'Job Knowledge',
                                                    'planning_organizing' => 'Planning & Organizing',
                                                    'accountability' => 'Accountability',
                                                    'efficiency_improvement' => 'Process Improvement'
                                                ];
                                                $compLabel = $competencyLabels[$gap['competency_key']] ?? $gap['competency_key'];
                                                
                                                $statusBadges = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                                    'completed' => 'bg-green-100 text-green-800'
                                                ];
                                                $statusBadge = $statusBadges[$gap['status']] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium border {{ $actionBadge }}">
                                                        <i class='bx {{ $actionIcon }} mr-1'></i>
                                                        {{ ucfirst($gap['action_type']) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $compLabel }}</div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="text-sm text-gray-600">
                                                        {{ !empty($gap['notes']) ? $gap['notes'] : '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusBadge }}">
                                                        {{ ucfirst(str_replace('_', ' ', $gap['status'])) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                                    {{ \Carbon\Carbon::parse($gap['created_at'])->format('M d, Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="bg-gray-50 px-4 py-2 border-t border-gray-200">
                                <p class="text-xs text-gray-600">
                                    <i class='bx bx-info-circle mr-1'></i>
                                    These plans indicate competencies that need targeted development. Use this information to assign relevant training or assessments.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Skill Gap Table - Compact -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-700">Skill Gap</th>
                                <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-700">Current</th>
                                <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-700">Required</th>
                                <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-700">Status</th>
                                <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-700">Priority</th>
                                <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-700">Assign</th>
                            </tr>
                        </thead>
                        <tbody id="competencyTableBody" class="bg-white divide-y divide-gray-100">
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Modals -->
    <!-- Assign Skill Gap Modal -->
    <div id="assignSkillGapModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-900">Assign Skill Gap</h3>
                        <button onclick="closeSkillGapModal()" class="text-gray-400 hover:text-gray-600">
                            <i class='bx bx-x text-xl'></i>
                        </button>
                    </div>
                    
                    <form id="skillGapForm">
                        <input type="hidden" id="sgEmployeeId" name="employee_id">
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Employee</label>
                                <input type="text" id="sgEmployeeName" readonly 
                                       class="w-full px-2 py-1.5 border border-gray-300 rounded bg-gray-50 text-xs">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Competency *</label>
                                <select id="sgCompetency" name="competency_id" required 
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs">
                                    <option value="">Select Competency</option>
                                    @php
                                        $competencies = \App\Modules\competency_management\Models\Competency::orderBy('competency_name')->get();
                                    @endphp
                                    @foreach($competencies as $competency)
                                        <option value="{{ $competency->id }}">{{ $competency->competency_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Assignment Type *</label>
                                <select id="sgActionType" name="assignment_type" required 
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs">
                                    <option value="">Select Assignment Type</option>
                                    <option value="gap_closure">Gap Closure (Immediate)</option>
                                    <option value="development">Development (Training)</option>
                                    <option value="skill_enhancement">Skill Enhancement (Mentoring)</option>
                                    <option value="mandatory">Mandatory</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Priority</label>
                                <select id="sgPriority" name="priority" 
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs">
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                                <textarea id="sgNotes" name="notes" rows="2" 
                                          class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs"
                                          placeholder="Additional notes..."></textarea>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex justify-end gap-2">
                            <button type="button" onclick="closeSkillGapModal()" 
                                    class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-xs transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs transition-colors">
                                <i class='bx bx-save mr-1'></i>Assign
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Development Plan Modal -->
    <div id="developmentPlanModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-900">Create Development Plan</h3>
                        <button onclick="closeDevelopmentPlanModal()" class="text-gray-400 hover:text-gray-600">
                            <i class='bx bx-x text-xl'></i>
                        </button>
                    </div>
                    
                    <form id="developmentPlanForm">
                        <input type="hidden" id="dpEmployeeId" name="employee_id">
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Employee</label>
                                <input type="text" id="dpEmployeeName" readonly 
                                       class="w-full px-2 py-1.5 border border-gray-300 rounded bg-gray-50 text-xs">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Plan Title *</label>
                                <input type="text" id="dpTitle" name="title" required 
                                       class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs"
                                       placeholder="e.g., Q1 2025 Development Plan">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Start Date *</label>
                                    <input type="date" id="dpStartDate" name="start_date" required 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">End Date *</label>
                                    <input type="date" id="dpEndDate" name="end_date" required 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Goals & Objectives *</label>
                                <textarea id="dpGoals" name="goals" rows="2" required 
                                          class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs"
                                          placeholder="Target outcomes and improvements..."></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Action Items *</label>
                                <textarea id="dpActions" name="action_items" rows="3" required 
                                          class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs"
                                          placeholder="Training, mentoring, specific actions..."></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Success Metrics</label>
                                <textarea id="dpMetrics" name="success_metrics" rows="2" 
                                          class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs"
                                          placeholder="How to measure success..."></textarea>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex justify-end gap-2">
                            <button type="button" onclick="closeDevelopmentPlanModal()" 
                                    class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-xs transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded text-xs transition-colors">
                                <i class='bx bx-save mr-1'></i>Create Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Assessment Modal -->
    <div id="scheduleAssessmentModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-gray-900">Schedule Assessment</h3>
                        <button onclick="closeScheduleAssessmentModal()" class="text-gray-400 hover:text-gray-600">
                            <i class='bx bx-x text-xl'></i>
                        </button>
                    </div>
                    
                    <form id="scheduleAssessmentForm">
                        <input type="hidden" id="saEmployeeId" name="employee_id">
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Employee</label>
                                <input type="text" id="saEmployeeName" readonly 
                                       class="w-full px-2 py-1.5 border border-gray-300 rounded bg-gray-50 text-xs">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Assessment Type *</label>
                                <select id="saType" name="assessment_type" required 
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs">
                                    <option value="">Select Type</option>
                                    <option value="competency_retake">Competency Retake</option>
                                    <option value="skill_validation">Skill Validation</option>
                                    <option value="comprehensive_eval">Comprehensive Evaluation</option>
                                    <option value="progress_check">Progress Check</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Scheduled Date *</label>
                                <input type="datetime-local" id="saDate" name="scheduled_date" required 
                                       class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                                <textarea id="saNotes" name="notes" rows="2" 
                                          class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 text-xs"
                                          placeholder="Instructions or notes..."></textarea>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex justify-end gap-2">
                            <button type="button" onclick="closeScheduleAssessmentModal()" 
                                    class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-xs transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded text-xs transition-colors">
                                <i class='bx bx-calendar-check mr-1'></i>Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .employee-card.active {
            border-color: #3b82f6 !important;
            background-color: #eff6ff;
        }
        
        .competency-progress {
            height: 6px;
            background-color: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .competency-progress-bar {
            height: 100%;
            transition: width 0.3s ease;
        }
        
        .gap-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .gap-indicator::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        
        .gap-meets::before { background-color: #10b981; }
        .gap-below::before { background-color: #f59e0b; }
        .gap-critical::before { background-color: #ef4444; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Competency name to ID mapping from database
        const competencyNameToId = {
            @php
                $allCompetencies = \App\Modules\competency_management\Models\Competency::all();
            @endphp
            @foreach($allCompetencies as $comp)
                {!! json_encode($comp->competency_name) !!}: {{ $comp->id }}{{ !$loop->last ? ',' : '' }}
            @endforeach
        };

        // Competency key to ID mapping (maps frontend keys to database IDs)
        const competencyKeyToId = {
            @foreach($allCompetencies as $comp)
                @php
                    // Create a key from competency name (lowercase, replace spaces with underscores)
                    $key = strtolower(str_replace([' ', '&', '-'], ['_', '', '_'], $comp->competency_name));
                    $key = preg_replace('/[^a-z0-9_]/', '', $key);
                    $key = preg_replace('/_+/', '_', $key);
                @endphp
                {!! json_encode($key) !!}: {{ $comp->id }},
            @endforeach
            // Map hardcoded frontend keys to closest matching database competencies
            // These are approximations - adjust IDs based on your actual competency database
            'assignment_skills': competencyNameToId['Project Planning'] || competencyNameToId['Stakeholder Management'] || Object.values(competencyNameToId)[0],
            'job_knowledge': competencyNameToId['Product Knowledge'] || competencyNameToId['Regulatory Knowledge'] || Object.values(competencyNameToId)[0],
            'planning_organizing': competencyNameToId['Project Planning'] || competencyNameToId['Strategic Thinking'] || Object.values(competencyNameToId)[0],
            'accountability': competencyNameToId['Ethics & Integrity'] || competencyNameToId['Performance Management'] || Object.values(competencyNameToId)[0],
            'efficiency_improvement': competencyNameToId['Innovation Management'] || competencyNameToId['Technology Adoption'] || Object.values(competencyNameToId)[0],
            'process_improvement': competencyNameToId['Innovation Management'] || competencyNameToId['Technology Adoption'] || Object.values(competencyNameToId)[0]
        };

        // Function to find competency ID by key or label
        function findCompetencyId(key, label) {
            // Try direct key lookup first
            if (competencyKeyToId[key]) {
                return competencyKeyToId[key];
            }
            
            // Try label lookup
            if (competencyNameToId[label]) {
                return competencyNameToId[label];
            }
            
            // Try to find by partial match in competency names
            const labelLower = label.toLowerCase();
            for (const [name, id] of Object.entries(competencyNameToId)) {
                if (name.toLowerCase().includes(labelLower) || labelLower.includes(name.toLowerCase())) {
                    return id;
                }
            }
            
            // Try normalized key match
            const normalizedKey = key.replace(/_/g, '').toLowerCase();
            for (const [name, id] of Object.entries(competencyNameToId)) {
                const normalizedName = name.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
                if (normalizedName.includes(normalizedKey) || normalizedKey.includes(normalizedName)) {
                    return id;
                }
            }
            
            return null;
        }

        // Real data from backend
        const employeeData = {
            @foreach($employeeGapAnalysis as $employee)
                '{{ $employee['employee_id'] }}': {
                    employee_id: '{{ $employee['employee_id'] }}',
                    name: {!! json_encode($employee['employee_name']) !!},
                    role: {!! json_encode($employee['job_title']) !!},
                    score: {{ $employee['overall_score'] }},
                    status: {!! json_encode($employee['status']) !!},
                    competencies: {
                        @foreach($employee['competencies'] as $key => $competency)
                            '{{ $key }}': {
                                current: {{ $competency['current'] }},
                                required: {{ $competency['required'] }},
                                description: {!! json_encode($competency['description']) !!}
                            }{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    },
                    skill_gap_assignments: {!! json_encode($employee['skill_gap_assignments'] ?? []) !!},
                    has_active_gaps: {{ isset($employee['has_active_gaps']) && $employee['has_active_gaps'] ? 'true' : 'false' }}
                }{{ !$loop->last ? ',' : '' }}
            @endforeach
        };

        let currentEmployeeId = '{{ $employeeGapAnalysis->first()['employee_id'] ?? '1' }}';

        function selectEmployee(employeeId, name, score, status) {
            document.querySelectorAll('.employee-card').forEach(card => card.classList.remove('active'));
            event.currentTarget.classList.add('active');
            
            currentEmployeeId = employeeId;
            document.getElementById('selectedEmployeeName').textContent = name;
            document.getElementById('overallScore').textContent = score + '%';
            
            updateGapAnalysis(employeeId);
        }

        function updateGapAnalysis(employeeId) {
            const employee = employeeData[employeeId];
            if (!employee) return;

            const tableBody = document.getElementById('competencyTableBody');
            const developmentSummary = document.getElementById('developmentSummary');
            
            const activePlansCount = employee.skill_gap_assignments ? employee.skill_gap_assignments.length : 0;
            document.getElementById('activePlansCount').textContent = activePlansCount;
            
            let gapCount = 0;
            let tableHTML = '';
            let summaryHTML = '';
            
            const competencyLabels = {
                'assignment_skills': 'Assignment Skills',
                'job_knowledge': 'Job Knowledge',
                'planning_organizing': 'Planning & Organizing',
                'accountability': 'Accountability',
                'efficiency_improvement': 'Process Improvement'
            };
            
            const actionColors = {
                'critical': 'border-red-300 bg-red-50 text-red-800',
                'training': 'border-blue-300 bg-blue-50 text-blue-800',
                'mentoring': 'border-green-300 bg-green-50 text-green-800',
                'gap_closure': 'border-red-300 bg-red-50 text-red-800',
                'development': 'border-blue-300 bg-blue-50 text-blue-800',
                'skill_enhancement': 'border-purple-300 bg-purple-50 text-purple-800',
                'mandatory': 'border-orange-300 bg-orange-50 text-orange-800'
            };
            
            const actionIcons = {
                'critical': 'bx-error-circle',
                'training': 'bx-book-reader',
                'mentoring': 'bx-user-voice',
                'gap_closure': 'bx-error-circle',
                'development': 'bx-book-reader',
                'skill_enhancement': 'bx-trending-up',
                'mandatory': 'bx-badge-check'
            };
            
            const actionLabels = {
                'critical': 'Critical',
                'training': 'Training',
                'mentoring': 'Mentoring',
                'gap_closure': 'Gap Closure',
                'development': 'Development',
                'skill_enhancement': 'Enhancement',
                'mandatory': 'Mandatory'
            };

            // Update active skill gaps section
            const activeSkillGapsSection = document.getElementById('activeSkillGapsSection');
            if (employee.has_active_gaps && employee.skill_gap_assignments && employee.skill_gap_assignments.length > 0) {
                const actionBadges = {
                    'critical': 'bg-red-100 text-red-800 border-red-300',
                    'training': 'bg-blue-100 text-blue-800 border-blue-300',
                    'mentoring': 'bg-green-100 text-green-800 border-green-300',
                    'gap_closure': 'bg-red-100 text-red-800 border-red-300',
                    'development': 'bg-blue-100 text-blue-800 border-blue-300',
                    'skill_enhancement': 'bg-purple-100 text-purple-800 border-purple-300',
                    'mandatory': 'bg-orange-100 text-orange-800 border-orange-300',
                    'assigned': 'bg-yellow-100 text-yellow-800 border-yellow-300'
                };
                
                const statusBadges = {
                    'pending': 'bg-yellow-100 text-yellow-800',
                    'in_progress': 'bg-blue-100 text-blue-800',
                    'completed': 'bg-green-100 text-green-800',
                    'assigned': 'bg-yellow-100 text-yellow-800'
                };
                
                let tableRows = '';
                employee.skill_gap_assignments.forEach((gap, index) => {
                    const actionBadge = actionBadges[gap.action_type] || 'bg-gray-100 text-gray-800 border-gray-300';
                    const actionIcon = actionIcons[gap.action_type] || 'bx-target-lock';
                    const actionLabel = actionLabels[gap.action_type] || gap.action_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    const compLabel = competencyLabels[gap.competency_key] || gap.competency_key;
                    const assignedDate = new Date(gap.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    const statusBadge = statusBadges[gap.status] || 'bg-gray-100 text-gray-800';
                    const statusLabel = gap.status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    
                    // Encode gap data for the assign button
                    const gapData = encodeURIComponent(JSON.stringify({
                        competency_key: gap.competency_key,
                        competency_label: compLabel,
                        action_type: gap.action_type,
                        notes: gap.notes,
                        status: gap.status
                    }));
                    
                    tableRows += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium border ${actionBadge}">
                                    <i class='bx ${actionIcon} mr-1'></i>
                                    ${actionLabel}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">${compLabel}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-600 max-w-xs truncate" title="${gap.notes || '-'}">${gap.notes || '-'}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${statusBadge}">
                                    ${statusLabel}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                ${assignedDate}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <button onclick="assignTrainingToGap('${employee.employee_id}', '${employee.employee_name}', '${gapData}')" 
                                    class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-2.5 py-1.5 rounded-md text-xs font-medium transition-colors">
                                    <i class='bx bx-edit text-sm'></i>
                                    Update
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                let skillGapsHTML = `
                    <div class="mb-6 bg-white border border-orange-200 rounded-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-50 to-red-50 px-4 py-3 border-b border-orange-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class='bx bx-target-lock text-xl text-orange-600 mr-2'></i>
                                    <h3 class="text-sm font-semibold text-orange-900">Active Skill Gap Improvement Plans</h3>
                                    <span class="ml-2 px-2 py-0.5 bg-orange-600 text-white text-xs rounded-full">
                                        ${employee.skill_gap_assignments.length}
                                    </span>
                                </div>
                                <a href="{{ route('competency.competencies.create') }}?employee_id=${employee.employee_id}" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-xs font-medium transition-colors">
                                    <i class='bx bx-plus-circle text-sm'></i>
                                    Assign Competency
                                </a>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action Type</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Competency</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Assigned Date</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    ${tableRows}
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-gray-50 px-4 py-2 border-t border-gray-200">
                            <p class="text-xs text-gray-600">
                                <i class='bx bx-info-circle mr-1'></i>
                                These plans indicate competencies that need targeted development. Use this information to assign relevant training or assessments.
                            </p>
                        </div>
                    </div>
                `;
                
                activeSkillGapsSection.innerHTML = skillGapsHTML;
            } else {
                activeSkillGapsSection.innerHTML = '';
            }

            // Generate table rows
            Object.keys(employee.competencies).forEach(key => {
                const comp = employee.competencies[key];
                const gap = comp.required - comp.current;
                const gapPercent = (comp.current / comp.required) * 100;
                
                let gapStatus, gapClass, priority;
                
                if (gap <= 0) {
                    gapStatus = 'Meets';
                    gapClass = 'gap-meets';
                    priority = 'Low';
                } else if (gap <= 0.5) {
                    gapStatus = 'Minor Gap';
                    gapClass = 'gap-below';
                    priority = 'Medium';
                    gapCount++;
                } else {
                    gapStatus = 'Major Gap';
                    gapClass = 'gap-critical';
                    priority = 'High';
                    gapCount++;
                }

                const progressColor = gapPercent >= 100 ? '#10b981' : gapPercent >= 80 ? '#f59e0b' : '#ef4444';
                const hasGap = gap > 0;

                tableHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="text-xs font-medium text-gray-900">${competencyLabels[key]}</div>
                            <div class="text-[10px] text-gray-500">${comp.description}</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="text-xs font-semibold text-gray-900">${comp.current.toFixed(1)}/5.0</div>
                            <div class="competency-progress mt-1 mx-auto" style="max-width: 60px;">
                                <div class="competency-progress-bar" style="width: ${(comp.current/5)*100}%; background-color: ${progressColor};"></div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="text-xs font-semibold text-gray-900">${comp.required.toFixed(1)}/5.0</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="gap-indicator ${gapClass} px-2 py-0.5 bg-gray-100 rounded-full text-[10px] font-medium">
                                ${gapStatus}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-medium ${
                                priority === 'High' ? 'bg-red-100 text-red-700' :
                                priority === 'Medium' ? 'bg-yellow-100 text-yellow-700' :
                                'bg-green-100 text-green-700'
                            }">${priority}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            ${hasGap ? `
                                <button onclick="assignSkillGapDirect('${employeeId}', '${employee.name}', '${key}', '${competencyLabels[key]}', '${priority}')" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-[10px] font-medium transition-colors">
                                    <i class='bx bx-plus-circle mr-0.5'></i> Assign
                                </button>
                            ` : `
                                <span class="text-[10px] text-gray-400">-</span>
                            `}
                        </td>
                    </tr>
                `;
            });

            tableBody.innerHTML = tableHTML;
            document.getElementById('gapCount').textContent = gapCount;
        }

        // Direct skill gap assignment with confirmation - adds to improvement plans
        function assignSkillGapDirect(employeeId, employeeName, skillGapKey, skillGapLabel, priority) {
            // Map priority to valid assignment type (gap_closure, development, skill_enhancement, mandatory)
            const actionType = priority === 'High' ? 'gap_closure' : 'development';
            const actionLabel = priority === 'High' ? 'Gap Closure (Immediate)' : 'Development (Training)';
            
            Swal.fire({
                title: 'Add to Improvement Plan',
                html: `
                    <div class="text-left text-sm">
                        <p class="mb-3">Add this skill gap to the employee's improvement plan:</p>
                        <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Employee:</span>
                                <span class="font-medium">${employeeName}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Skill Gap:</span>
                                <span class="font-medium text-orange-600">${skillGapLabel}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Priority:</span>
                                <span class="font-medium ${priority === 'High' ? 'text-red-600' : 'text-yellow-600'}">${priority}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Action Type:</span>
                                <span class="font-medium">${actionLabel}</span>
                            </div>
                        </div>
                        <p class="mt-3 text-xs text-gray-500">
                            <i class='bx bx-info-circle mr-1'></i>
                            This will be added to the Active Skill Gap Improvement Plans above.
                        </p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="bx bx-check mr-1"></i> Add to Plan',
                cancelButtonText: 'Cancel',
                customClass: { 
                    popup: 'text-sm',
                    htmlContainer: 'text-left'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Adding to Plan...',
                        html: 'Please wait while we process your request.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
                    
                    // Get competency ID using the smart lookup function
                    const competencyId = findCompetencyId(skillGapKey, skillGapLabel);
                    
                    if (!competencyId) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: `Could not find competency ID for "${skillGapLabel}" (key: ${skillGapKey}). Please use the modal form instead.`,
                            confirmButtonColor: '#ef4444',
                            customClass: { popup: 'text-sm' }
                        });
                        return;
                    }
                    
                    fetch('/competency/skill-gaps/assign', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            employee_id: employeeId,
                            competency_id: competencyId,
                            assignment_type: actionType,
                            notes: `${skillGapLabel} - ${priority} priority skill gap identified from Role Mapping analysis`
                        })
                    })
                    .then(async response => {
                        const text = await response.text();
                        try {
                            const jsonData = JSON.parse(text);
                            if (!response.ok) {
                                if (jsonData.errors) {
                                    const errorMessages = Object.values(jsonData.errors).flat().join('\n');
                                    throw new Error(errorMessages);
                                }
                                throw new Error(jsonData.message || `HTTP error! status: ${response.status}`);
                            }
                            return jsonData;
                        } catch (e) {
                            if (e.message) throw e;
                            throw new Error('Invalid response from server');
                        }
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Added to Plan!',
                                html: `
                                    <div class="text-sm">
                                        <p><strong>${skillGapLabel}</strong> has been added to <strong>${employeeName}</strong>'s improvement plan.</p>
                                    </div>
                                `,
                                confirmButtonColor: '#3b82f6',
                                customClass: { popup: 'text-sm' }
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to Add',
                                text: data.message || 'An error occurred.',
                                confirmButtonColor: '#ef4444',
                                customClass: { popup: 'text-sm' }
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'An error occurred.',
                            confirmButtonColor: '#ef4444',
                            customClass: { popup: 'text-sm' }
                        });
                    });
                }
            });
        }

        function assignTrainingToGap(employeeId, employeeName, gapDataEncoded) {
            const gapData = JSON.parse(decodeURIComponent(gapDataEncoded));
            const actionTypeLabels = {
                'gap_closure': 'Gap Closure (Immediate)',
                'development': 'Development (Training)',
                'skill_enhancement': 'Skill Enhancement',
                'mandatory': 'Mandatory Compliance',
                'critical': 'Critical',
                'training': 'Training',
                'mentoring': 'Mentoring'
            };
            const actionLabel = actionTypeLabels[gapData.action_type] || gapData.action_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            
            Swal.fire({
                title: '<i class="bx bx-target-lock text-green-600 mr-2"></i> Assign Competency to Skill Gap',
                html: `
                    <div class="text-left space-y-4">
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <span class="text-gray-500">Employee:</span>
                                    <p class="font-semibold text-gray-900">${employeeName}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Skill Gap:</span>
                                    <p class="font-semibold text-gray-900">${gapData.competency_label}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Action Type:</span>
                                    <p class="font-semibold text-orange-600">${actionLabel}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Status:</span>
                                    <p class="font-semibold text-yellow-600">${gapData.status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t pt-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">
                                <i class='bx bx-check-circle text-green-500 mr-1'></i>
                                Confirm Assignment Details
                            </h4>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Assignment Type</label>
                                    <select id="gapAssignmentType" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="gap_closure" ${gapData.action_type === 'gap_closure' ? 'selected' : ''}>Gap Closure (Immediate Action)</option>
                                        <option value="development" ${gapData.action_type === 'development' ? 'selected' : ''}>Development (Training)</option>
                                        <option value="skill_enhancement" ${gapData.action_type === 'skill_enhancement' ? 'selected' : ''}>Skill Enhancement</option>
                                        <option value="mandatory" ${gapData.action_type === 'mandatory' ? 'selected' : ''}>Mandatory Compliance</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Priority</label>
                                    <select id="gapPriority" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="critical">Critical</option>
                                        <option value="high" selected>High</option>
                                        <option value="medium">Medium</option>
                                        <option value="low">Low</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Additional Notes</label>
                                    <textarea id="gapNotes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Add any additional notes...">${gapData.notes || ''}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-xs text-gray-500 bg-gray-50 p-2 rounded">
                            <i class='bx bx-info-circle mr-1'></i>
                            This will update the skill gap assignment and mark it for competency development tracking.
                        </p>
                    </div>
                `,
                icon: null,
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="bx bx-check mr-1"></i> Confirm Assignment',
                cancelButtonText: 'Cancel',
                customClass: { 
                    popup: 'text-sm',
                    htmlContainer: 'text-left'
                },
                preConfirm: () => {
                    return {
                        assignment_type: document.getElementById('gapAssignmentType').value,
                        priority: document.getElementById('gapPriority').value,
                        notes: document.getElementById('gapNotes').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = result.value;
                    
                    // Show loading
                    Swal.fire({
                        title: 'Updating Assignment...',
                        html: 'Please wait while we process your request.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
                    
                    // Update the skill gap assignment status
                    fetch('/competency/skill-gaps/update-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            employee_id: employeeId,
                            competency_key: gapData.competency_key,
                            assignment_type: formData.assignment_type,
                            priority: formData.priority,
                            notes: formData.notes,
                            status: 'in_progress'
                        })
                    })
                    .then(async response => {
                        const text = await response.text();
                        try {
                            const jsonData = JSON.parse(text);
                            if (!response.ok) {
                                if (jsonData.errors) {
                                    const errorMessages = Object.values(jsonData.errors).flat().join('\n');
                                    throw new Error(errorMessages);
                                }
                                throw new Error(jsonData.message || `HTTP error! status: ${response.status}`);
                            }
                            return jsonData;
                        } catch (e) {
                            if (e.message) throw e;
                            throw new Error('Invalid response from server');
                        }
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Assignment Updated!',
                                html: `
                                    <div class="text-sm">
                                        <p><strong>${gapData.competency_label}</strong> skill gap for <strong>${employeeName}</strong> has been updated.</p>
                                        <p class="mt-2 text-gray-600">Status changed to: <span class="font-semibold text-blue-600">In Progress</span></p>
                                    </div>
                                `,
                                confirmButtonColor: '#10b981',
                                customClass: { popup: 'text-sm' }
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Update Failed',
                                text: data.message || 'An error occurred.',
                                confirmButtonColor: '#ef4444',
                                customClass: { popup: 'text-sm' }
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'An error occurred.',
                            confirmButtonColor: '#ef4444',
                            customClass: { popup: 'text-sm' }
                        });
                    });
                }
            });
        }

        function addressCriticalGaps() {
            const employee = employeeData[currentEmployeeId];
            if (employee) {
                openSkillGapModal(currentEmployeeId, employee.name, 'critical');
            }
        }

        function createTrainingPlan() {
            const employee = employeeData[currentEmployeeId];
            if (employee) {
                openDevelopmentPlanModal(currentEmployeeId, employee.name);
            }
        }

        function startDevelopmentJourney() {
            const employee = employeeData[currentEmployeeId];
            if (employee) {
                openDevelopmentPlanModal(currentEmployeeId, employee.name);
            }
        }

        function createDevelopmentPlan() {
            const employee = employeeData[currentEmployeeId];
            if (employee) {
                openDevelopmentPlanModal(currentEmployeeId, employee.name);
            }
        }

        function scheduleAssessment() {
            const employee = employeeData[currentEmployeeId];
            if (employee) {
                openScheduleAssessmentModal(currentEmployeeId, employee.name);
            }
        }

        // Modal Functions
        function openSkillGapModal(employeeId, employeeName, defaultActionType = '', defaultCompetency = '') {
            document.getElementById('sgEmployeeId').value = employeeId;
            document.getElementById('sgEmployeeName').value = employeeName;
            if (defaultActionType) {
                document.getElementById('sgActionType').value = defaultActionType;
            }
            if (defaultCompetency) {
                document.getElementById('sgCompetency').value = defaultCompetency;
            } else {
                // Auto-select first competency that has a gap based on action type
                const employee = employeeData[employeeId];
                if (employee && employee.competencies) {
                    let targetCompetency = '';
                    Object.keys(employee.competencies).forEach(key => {
                        if (!targetCompetency) {
                            const comp = employee.competencies[key];
                            const gap = comp.required - comp.current;
                            if (defaultActionType === 'critical' && gap > 0.5) {
                                targetCompetency = key;
                            } else if (defaultActionType === 'training' && gap > 0 && gap <= 0.5) {
                                targetCompetency = key;
                            } else if (gap > 0) {
                                targetCompetency = key;
                            }
                        }
                    });
                    if (targetCompetency) {
                        document.getElementById('sgCompetency').value = targetCompetency;
                    }
                }
            }
            document.getElementById('assignSkillGapModal').classList.remove('hidden');
        }

        function closeSkillGapModal() {
            document.getElementById('assignSkillGapModal').classList.add('hidden');
            document.getElementById('skillGapForm').reset();
        }

        function openDevelopmentPlanModal(employeeId, employeeName) {
            document.getElementById('dpEmployeeId').value = employeeId;
            document.getElementById('dpEmployeeName').value = employeeName;
            
            const today = new Date().toISOString().split('T')[0];
            const threeMonthsLater = new Date(Date.now() + 90 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            document.getElementById('dpStartDate').value = today;
            document.getElementById('dpEndDate').value = threeMonthsLater;
            
            document.getElementById('developmentPlanModal').classList.remove('hidden');
        }

        function closeDevelopmentPlanModal() {
            document.getElementById('developmentPlanModal').classList.add('hidden');
            document.getElementById('developmentPlanForm').reset();
        }

        function openScheduleAssessmentModal(employeeId, employeeName) {
            document.getElementById('saEmployeeId').value = employeeId;
            document.getElementById('saEmployeeName').value = employeeName;
            
            const nextWeek = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000);
            document.getElementById('saDate').value = nextWeek.toISOString().slice(0, 16);
            
            document.getElementById('scheduleAssessmentModal').classList.remove('hidden');
        }

        function closeScheduleAssessmentModal() {
            document.getElementById('scheduleAssessmentModal').classList.add('hidden');
            document.getElementById('scheduleAssessmentForm').reset();
        }

        // Form Submissions
        document.getElementById('skillGapForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Validate required fields before submitting
            if (!data.employee_id) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Employee ID is required.',
                    confirmButtonColor: '#ef4444',
                    customClass: { popup: 'text-sm' }
                });
                return;
            }
            
            if (!data.competency_id) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select a competency.',
                    confirmButtonColor: '#ef4444',
                    customClass: { popup: 'text-sm' }
                });
                return;
            }
            
            if (!data.assignment_type) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select an assignment type.',
                    confirmButtonColor: '#ef4444',
                    customClass: { popup: 'text-sm' }
                });
                return;
            }
            
            console.log('Submitting skill gap data:', data);
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            
            fetch('/competency/skill-gaps/assign', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(async response => {
                const text = await response.text();
                console.log('Server response:', text);
                try {
                    const jsonData = JSON.parse(text);
                    if (!response.ok) {
                        // Handle validation errors from Laravel
                        if (jsonData.errors) {
                            const errorMessages = Object.values(jsonData.errors).flat().join('\n');
                            throw new Error(errorMessages);
                        }
                        throw new Error(jsonData.message || `HTTP error! status: ${response.status}`);
                    }
                    return jsonData;
                } catch (e) {
                    if (e.message) throw e;
                    throw new Error('Invalid response from server');
                }
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Assigned!',
                        text: 'Skill gap assigned successfully.',
                        confirmButtonColor: '#3b82f6',
                        customClass: { popup: 'text-sm' }
                    }).then(() => {
                        closeSkillGapModal();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to assign.',
                        confirmButtonColor: '#ef4444',
                        customClass: { popup: 'text-sm' }
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'An error occurred.',
                    confirmButtonColor: '#ef4444',
                    customClass: { popup: 'text-sm' }
                });
            });
        });

        document.getElementById('developmentPlanForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            fetch('/competency/development-plans/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Created!',
                        text: 'Development plan created successfully.',
                        confirmButtonColor: '#10b981',
                        customClass: { popup: 'text-sm' }
                    }).then(() => {
                        closeDevelopmentPlanModal();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to create.',
                        confirmButtonColor: '#ef4444',
                        customClass: { popup: 'text-sm' }
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred.',
                    confirmButtonColor: '#ef4444',
                    customClass: { popup: 'text-sm' }
                });
            });
        });

        document.getElementById('scheduleAssessmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            fetch('/competency/assessments/schedule', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Scheduled!',
                        text: 'Assessment scheduled successfully.',
                        confirmButtonColor: '#8b5cf6',
                        customClass: { popup: 'text-sm' }
                    }).then(() => {
                        closeScheduleAssessmentModal();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to schedule.',
                        confirmButtonColor: '#ef4444',
                        customClass: { popup: 'text-sm' }
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred.',
                    confirmButtonColor: '#ef4444',
                    customClass: { popup: 'text-sm' }
                });
            });
        });

        function exportGapAnalysis() {
            const employee = employeeData[currentEmployeeId];
            Swal.fire({
                icon: 'info',
                title: 'Exporting Report',
                html: `Preparing report for <strong>${employee.name}</strong>`,
                confirmButtonColor: '#3b82f6',
                customClass: { popup: 'text-sm' }
            });
        }

        function refreshAnalysis() {
            Swal.fire({
                icon: 'info',
                title: 'Refresh Analysis',
                text: 'This will update all data and recalculate gaps.',
                confirmButtonColor: '#3b82f6',
                showCancelButton: true,
                confirmButtonText: 'Refresh',
                customClass: { popup: 'text-sm' }
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            if (Object.keys(employeeData).length > 0) {
                const firstEmployeeId = Object.keys(employeeData)[0];
                updateGapAnalysis(firstEmployeeId);
            }
        });
    </script>
</x-app-layout>