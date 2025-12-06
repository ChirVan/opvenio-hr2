<div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
    <!-- Competency Header -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900">{{ $item['competency']->competency_name }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $item['framework']->framework_name }}</p>
            @if($item['competency']->description)
                <p class="text-xs text-gray-500 mt-2">{{ Str::limit($item['competency']->description, 100) }}</p>
            @endif
        </div>
        <div class="ml-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                {{ match($item['gap_status']) {
                    'exceeds_requirement' => 'bg-green-100 text-green-800',
                    'meets_requirement' => 'bg-blue-100 text-blue-800',
                    'needs_improvement' => 'bg-red-100 text-red-800',
                    'no_assessment' => 'bg-yellow-100 text-yellow-800',
                    default => 'bg-gray-100 text-gray-800'
                } }}">
                @switch($item['gap_status'])
                    @case('exceeds_requirement')
                        🟢 Exceeds
                        @break
                    @case('meets_requirement')
                        🔵 Meets
                        @break
                    @case('needs_improvement')
                        🔴 Gap
                        @break
                    @case('no_assessment')
                        🟡 Not Assessed
                        @break
                @endswitch
            </span>
        </div>
    </div>

    <!-- Level Comparison -->
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="text-center">
            <p class="text-xs font-medium text-gray-600 mb-2">
                @if($item['is_role_required'])
                    Required Level
                @else
                    Target Level
                @endif
            </p>
            @if($item['required_level'])
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                    {{ $item['required_level'] }}
                </span>
            @else
                <span class="text-sm text-gray-400">Not specified</span>
            @endif
        </div>
        <div class="text-center">
            <p class="text-xs font-medium text-gray-600 mb-2">Current Level</p>
            @if($item['current_level'])
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                    {{ match($item['current_level']) {
                        'Expert' => 'bg-purple-100 text-purple-800',
                        'Advanced' => 'bg-green-100 text-green-800',
                        'Intermediate' => 'bg-yellow-100 text-yellow-800',
                        'Beginner' => 'bg-orange-100 text-orange-800',
                        'Novice' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800'
                    } }}">
                    {{ $item['current_level'] }}
                </span>
            @else
                <span class="text-sm text-gray-400">Not assessed</span>
            @endif
        </div>
    </div>

    <!-- Assessment Status -->
    @if($item['assessment_result'])
        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-800">✅ Assessment Completed</p>
                    <p class="text-xs text-green-600">{{ $item['assessment_result']['quiz_title'] }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-green-800">{{ number_format($item['assessment_result']['score'], 1) }}/5.0</p>
                    <p class="text-xs text-green-600">{{ \Carbon\Carbon::parse($item['assessment_result']['evaluated_at'])->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    @elseif($item['assigned_quiz'])
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-800">📝 Assessment Assigned</p>
                    <p class="text-xs text-blue-600">{{ $item['assigned_quiz']->quiz_title }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-blue-800">Due: {{ \Carbon\Carbon::parse($item['assigned_quiz']->due_date)->format('M d, Y') }}</p>
                    <p class="text-xs text-blue-600">Status: {{ ucfirst($item['assigned_quiz']->status) }}</p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4">
            <p class="text-sm text-gray-600">No assessment assigned or completed</p>
        </div>
    @endif

    <!-- Actions -->
    <div class="flex justify-end space-x-2">
        @if($item['assessment_result'])
            <button onclick="viewAssessmentDetails('{{ $employee->id }}', '{{ $item['competency']->id }}')" 
                    class="px-3 py-1 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded transition-colors">
                <i class='bx bx-show mr-1'></i> View Results
            </button>
        @elseif($item['quiz_available'] && !$item['assigned_quiz'])
            <button onclick="assignAssessment('{{ $employee->id }}', '{{ $item['competency']->id }}', '{{ $employee->full_name }}', '{{ $item['competency']->competency_name }}')" 
                    class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded transition-colors">
                <i class='bx bx-plus mr-1'></i> Assign Assessment
            </button>
        @elseif(!$item['quiz_available'])
            <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded">
                <i class='bx bx-x mr-1'></i> No Quiz Available
            </span>
        @endif
    </div>
</div>