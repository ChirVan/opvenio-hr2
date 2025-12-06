{{-- filepath: resources/views/competency_management/CompetencyCRUD/create.blade.php --}}
<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Assign Competency to Employee</h2>
                    <p class="text-sm text-gray-600 mt-1">Select an employee and assign one or multiple competencies with action type</p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-4">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('competency.skill-gaps.assign') }}" method="POST" id="assignCompetencyForm">
                    @csrf

                    <!-- Employee Selection -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">
                            Select Employee <span class="text-red-500">*</span>
                        </label>
                        <select name="employee_id" id="employee_id" required class="w-full border border-gray-300 rounded-md px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                            <option value="">-- Choose an Employee --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee['employee_id'] }}" 
                                    {{ (old('employee_id') == $employee['employee_id'] || ($selectedEmployee && $selectedEmployee['employee_id'] == $employee['employee_id'])) ? 'selected' : '' }}
                                    data-name="{{ $employee['full_name'] }}"
                                    data-title="{{ $employee['job_title'] ?? '' }}">
                                    {{ $employee['employee_id'] }} - {{ $employee['full_name'] }}
                                    @if(!empty($employee['job_title']))
                                        ({{ $employee['job_title'] }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Select the employee to assign competencies</p>
                    </div>

                    <!-- Current Skill Gaps Section -->
                    @if($selectedEmployee && count($employeeSkillGaps) > 0)
                        <div class="mb-6 bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <i class='bx bx-info-circle text-xl text-orange-600'></i>
                                <h3 class="font-semibold text-gray-800">Current Skill Gaps for {{ $selectedEmployee['full_name'] }}</h3>
                            </div>
                            <p class="text-xs text-gray-600 mb-3">These are the existing skill gap assignments. Consider these when assigning new competencies.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($employeeSkillGaps as $gap)
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
                                    @endphp
                                    <div class="bg-white border border-gray-200 rounded-md p-3">
                                        <div class="flex items-start gap-2">
                                            <i class='bx {{ $actionIcon }} text-lg mt-0.5'></i>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $actionBadge }}">
                                                        {{ ucfirst($gap['action_type']) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::parse($gap['created_at'])->format('M d, Y') }}
                                                    </span>
                                                </div>
                                                <div class="font-semibold text-sm text-gray-900">{{ $gap['competency_label'] }}</div>
                                                @if(!empty($gap['notes']))
                                                    <p class="text-xs text-gray-600 mt-1">{{ Str::limit($gap['notes'], 80) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Competencies Multi-Select -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-gray-700 font-medium">
                                Select Competencies <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <button type="button" id="selectAllBtn" class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                    <i class='bx bx-check-square'></i> Select All
                                </button>
                                <span class="text-gray-300">|</span>
                                <button type="button" id="deselectAllBtn" class="text-xs text-gray-600 hover:text-gray-800 font-medium transition-colors">
                                    <i class='bx bx-square'></i> Deselect All
                                </button>
                            </div>
                        </div>
                        
                        <!-- Search Box -->
                        <div class="mb-3">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="competencySearch" 
                                    placeholder="Search competencies..." 
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                                >
                                <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                            </div>
                        </div>

                        <!-- Category Filter Tabs -->
                        <div class="mb-3 flex items-center gap-2 flex-wrap">
                            <span class="text-xs font-medium text-gray-600">Filter by Framework:</span>
                            <button type="button" class="category-filter active px-3 py-1.5 text-xs font-medium rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors" data-category="all">
                                <i class='bx bx-layer text-sm'></i>
                                All Frameworks
                            </button>
                            @php
                                $frameworks = $competencies->groupBy('framework_id');
                                $frameworkColors = [
                                    'leadership' => ['icon' => 'bx-trophy', 'color' => 'amber'],
                                    'technical' => ['icon' => 'bx-code-alt', 'color' => 'indigo'],
                                    'customer service' => ['icon' => 'bx-user-voice', 'color' => 'purple'],
                                    'core' => ['icon' => 'bx-star', 'color' => 'green'],
                                    'functional' => ['icon' => 'bx-briefcase', 'color' => 'blue'],
                                    'behavioral' => ['icon' => 'bx-user', 'color' => 'pink'],
                                ];
                            @endphp
                            @foreach($frameworks as $frameworkId => $frameworkCompetencies)
                                @php
                                    $framework = $frameworkCompetencies->first()->framework ?? null;
                                    if ($framework) {
                                        $frameworkName = $framework->framework_name;
                                        $frameworkKey = strtolower(str_replace(' framework', '', $frameworkName));
                                        $config = $frameworkColors[$frameworkKey] ?? ['icon' => 'bx-category', 'color' => 'gray'];
                                    }
                                @endphp
                                @if($framework)
                                    <button type="button" class="category-filter px-3 py-1.5 text-xs font-medium rounded-full border-2 border-{{ $config['color'] }}-300 text-{{ $config['color'] }}-700 bg-white hover:bg-{{ $config['color'] }}-50 transition-colors" data-category="framework-{{ $frameworkId }}">
                                        <i class='bx {{ $config['icon'] }} text-sm'></i>
                                        {{ $framework->framework_name }}
                                        <span class="ml-1 px-1.5 py-0.5 bg-{{ $config['color'] }}-100 rounded-full text-xs">
                                            {{ $frameworkCompetencies->count() }}
                                        </span>
                                    </button>
                                @endif
                            @endforeach
                        </div>

                        <!-- Selected Count Badge -->
                        <div class="mb-2 flex items-center gap-2">
                            <span class="text-xs text-gray-600">Selected:</span>
                            <span id="selectedCount" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                0 competencies
                            </span>
                        </div>

                        <div class="border-2 border-gray-300 rounded-lg p-2 max-h-96 overflow-y-auto bg-white shadow-inner" id="competencyContainer">
                            @php
                                $groupedCompetencies = $competencies->groupBy('framework_id');
                            @endphp
                            
                            @forelse($groupedCompetencies as $frameworkId => $frameworkCompetencies)
                                @php
                                    $framework = $frameworkCompetencies->first()->framework ?? null;
                                    if ($framework) {
                                        $frameworkName = $framework->framework_name;
                                        $frameworkKey = strtolower(str_replace(' framework', '', $frameworkName));
                                        
                                        // Determine color scheme based on framework name
                                        $borderColor = 'border-gray-500';
                                        $icon = 'bx-category';
                                        
                                        if (str_contains(strtolower($frameworkName), 'leadership')) {
                                            $borderColor = 'border-amber-500';
                                            $icon = 'bx-trophy';
                                        } elseif (str_contains(strtolower($frameworkName), 'technical')) {
                                            $borderColor = 'border-indigo-500';
                                            $icon = 'bx-code-alt';
                                        } elseif (str_contains(strtolower($frameworkName), 'customer') || str_contains(strtolower($frameworkName), 'service')) {
                                            $borderColor = 'border-purple-500';
                                            $icon = 'bx-user-voice';
                                        } elseif (str_contains(strtolower($frameworkName), 'core')) {
                                            $borderColor = 'border-green-500';
                                            $icon = 'bx-star';
                                        } elseif (str_contains(strtolower($frameworkName), 'functional')) {
                                            $borderColor = 'border-blue-500';
                                            $icon = 'bx-briefcase';
                                        } elseif (str_contains(strtolower($frameworkName), 'behavioral')) {
                                            $borderColor = 'border-pink-500';
                                            $icon = 'bx-user';
                                        }
                                    }
                                @endphp
                                @if($framework)
                                    <div class="competency-category mb-4" data-category="framework-{{ $frameworkId }}">
                                        <!-- Category Header -->
                                        <div class="sticky top-0 bg-gradient-to-r from-gray-50 to-gray-100 border-l-4 {{ $borderColor }} px-3 py-2 mb-2 rounded shadow-sm z-10">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <i class='bx {{ $icon }} text-xl'></i>
                                                    <div>
                                                        <h3 class="text-sm font-bold text-gray-800">
                                                            {{ $framework->framework_name }}
                                                        </h3>
                                                        @if($framework->description)
                                                            <p class="text-xs text-gray-600 mt-0.5">{{ Str::limit($framework->description, 80) }}</p>
                                                        @endif
                                                    </div>
                                                    <span class="px-2 py-0.5 bg-white rounded-full text-xs font-medium text-gray-600 border border-gray-300">
                                                        {{ $frameworkCompetencies->count() }} {{ Str::plural('item', $frameworkCompetencies->count()) }}
                                                    </span>
                                                </div>
                                                <button type="button" class="category-select-all text-xs text-blue-600 hover:text-blue-800 font-medium" data-category="framework-{{ $frameworkId }}">
                                                    <i class='bx bx-check-square'></i> Select All
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Category Items -->
                                        @foreach($frameworkCompetencies as $competency)
                                            <label class="competency-item flex items-start p-3 mb-2 ml-2 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition-all group" 
                                                   data-name="{{ strtolower($competency->competency_name ?? '') }}" 
                                                   data-description="{{ strtolower($competency->description ?? '') }}"
                                                   data-framework="{{ strtolower($framework->framework_name ?? '') }}"
                                                   data-category="framework-{{ $frameworkId }}">
                                                <input 
                                                    type="checkbox" 
                                                    name="competencies[]" 
                                                    value="{{ $competency->id }}"
                                                    data-name="{{ $competency->competency_name }}"
                                                    {{ is_array(old('competencies')) && in_array($competency->id, old('competencies')) ? 'checked' : '' }}
                                                    class="competency-checkbox mt-1 h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                                                >
                                                <div class="ml-3 flex-1">
                                                    <div class="flex items-start justify-between gap-2">
                                                        <span class="text-sm font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">
                                                            {{ $competency->competency_name }}
                                                        </span>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full border bg-blue-50 text-blue-700 border-blue-200">
                                                            <i class='bx bx-layer text-sm mr-1'></i>
                                                            {{ $framework->framework_name }}
                                                        </span>
                                                    </div>
                                                    @if($competency->description)
                                                        <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                                                            {{ Str::limit($competency->description, 120) }}
                                                        </p>
                                                    @endif
                                                    <div class="flex items-center gap-3 mt-1.5">
                                                        @if($competency->status)
                                                            @php
                                                                $statusColors = [
                                                                    'active' => 'bg-green-100 text-green-700',
                                                                    'draft' => 'bg-yellow-100 text-yellow-700',
                                                                    'inactive' => 'bg-gray-100 text-gray-700',
                                                                    'archived' => 'bg-red-100 text-red-700',
                                                                ];
                                                                $statusColor = $statusColors[$competency->status] ?? 'bg-gray-100 text-gray-700';
                                                            @endphp
                                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded {{ $statusColor }}">
                                                                <i class='bx bx-circle text-xs mr-1'></i>
                                                                {{ ucfirst($competency->status) }}
                                                            </span>
                                                        @endif
                                                        @if($competency->id)
                                                            <span class="text-xs text-gray-400 font-mono">
                                                                ID: {{ $competency->id }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <i class='bx bx-info-circle text-4xl mb-2'></i>
                                    <p class="text-sm font-medium">No competencies available</p>
                                    <p class="text-xs mt-1">Please add competencies first to assign them to employees.</p>
                                </div>
                            @endforelse
                            
                            <!-- No Results Message (Hidden by default) -->
                            <div id="noResults" class="text-center py-8 text-gray-500 hidden">
                                <i class='bx bx-search-alt text-4xl mb-2'></i>
                                <p class="text-sm font-medium">No competencies found</p>
                                <p class="text-xs mt-1">Try adjusting your search terms or filters.</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                            <i class='bx bx-info-circle'></i>
                            Select one or more competencies to assign as skill gaps
                        </p>
                    </div>

                    <!-- Assignment Type -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">
                            Assignment Type <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all">
                                <input type="radio" name="assignment_type" value="development" required {{ old('assignment_type') == 'development' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-3">
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-trending-up text-xl text-blue-600'></i>
                                        <span class="font-semibold text-gray-900">Development</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">General skill growth</p>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-300 hover:bg-orange-50 transition-all">
                                <input type="radio" name="assignment_type" value="gap_closure" required {{ old('assignment_type') == 'gap_closure' ? 'checked' : '' }} class="h-4 w-4 text-orange-600 focus:ring-orange-500">
                                <div class="ml-3">
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-target-lock text-xl text-orange-600'></i>
                                        <span class="font-semibold text-gray-900">Gap Closure</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">Address skill gaps</p>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-300 hover:bg-purple-50 transition-all">
                                <input type="radio" name="assignment_type" value="skill_enhancement" required {{ old('assignment_type') == 'skill_enhancement' ? 'checked' : '' }} class="h-4 w-4 text-purple-600 focus:ring-purple-500">
                                <div class="ml-3">
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-star text-xl text-purple-600'></i>
                                        <span class="font-semibold text-gray-900">Enhancement</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">Improve existing skills</p>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-300 hover:bg-red-50 transition-all">
                                <input type="radio" name="assignment_type" value="mandatory" required {{ old('assignment_type') == 'mandatory' ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500">
                                <div class="ml-3">
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-error-circle text-xl text-red-600'></i>
                                        <span class="font-semibold text-gray-900">Mandatory</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">Required competency</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Priority Level -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">
                            Priority Level <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <label class="relative flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all">
                                <input type="radio" name="priority" value="low" required {{ old('priority') == 'low' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-3">
                                    <span class="font-medium text-sm text-gray-900">Low</span>
                                </div>
                            </label>

                            <label class="relative flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-yellow-300 hover:bg-yellow-50 transition-all">
                                <input type="radio" name="priority" value="medium" required {{ old('priority', 'medium') == 'medium' ? 'checked' : '' }} class="h-4 w-4 text-yellow-600 focus:ring-yellow-500">
                                <div class="ml-3">
                                    <span class="font-medium text-sm text-gray-900">Medium</span>
                                </div>
                            </label>

                            <label class="relative flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-300 hover:bg-orange-50 transition-all">
                                <input type="radio" name="priority" value="high" required {{ old('priority') == 'high' ? 'checked' : '' }} class="h-4 w-4 text-orange-600 focus:ring-orange-500">
                                <div class="ml-3">
                                    <span class="font-medium text-sm text-gray-900">High</span>
                                </div>
                            </label>

                            <label class="relative flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-300 hover:bg-red-50 transition-all">
                                <input type="radio" name="priority" value="critical" required {{ old('priority') == 'critical' ? 'checked' : '' }} class="h-4 w-4 text-red-600 focus:ring-red-500">
                                <div class="ml-3">
                                    <span class="font-medium text-sm text-gray-900">Critical</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Target Date -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">
                            Target Completion Date (Optional)
                        </label>
                        <input type="date" name="target_date" value="{{ old('target_date') }}" class="w-full border border-gray-300 rounded-md px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Set a target date for competency achievement</p>
                    </div>

                    <!-- Notes Field -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">
                            Notes (Optional)
                        </label>
                        <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent" placeholder="Add any additional notes or development plan details...">{{ old('notes') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Provide context or action items for the assignment</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('competency.frameworks') }}" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-md font-medium transition-colors">
                            <i class='bx bx-arrow-back'></i>
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-md font-medium transition-colors">
                            <i class='bx bx-check-circle'></i>
                            Assign Competencies
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Category Filter Functionality
        const categoryFilters = document.querySelectorAll('.category-filter');
        const competencyCategories = document.querySelectorAll('.competency-category');
        let currentCategory = 'all';

        categoryFilters.forEach(button => {
            button.addEventListener('click', function() {
                const category = this.dataset.category;
                currentCategory = category;
                
                // Update active state
                categoryFilters.forEach(btn => {
                    btn.classList.remove('active', 'bg-blue-600', 'text-white');
                    if (!btn.classList.contains('bg-blue-600')) {
                        btn.classList.add('bg-white');
                    }
                });
                
                this.classList.add('active', 'bg-blue-600', 'text-white');
                this.classList.remove('bg-white');
                
                // Filter categories
                if (category === 'all') {
                    competencyCategories.forEach(cat => {
                        cat.classList.remove('hidden');
                    });
                } else {
                    competencyCategories.forEach(cat => {
                        if (cat.dataset.category === category) {
                            cat.classList.remove('hidden');
                        } else {
                            cat.classList.add('hidden');
                        }
                    });
                }
                
                // Clear search when switching categories
                const searchInput = document.getElementById('competencySearch');
                if (searchInput) {
                    searchInput.value = '';
                }
                
                // Re-apply search if needed
                updateVisibility();
                updateSelectedCount();
            });
        });

        // Category Select All Buttons
        const categorySelectAllBtns = document.querySelectorAll('.category-select-all');
        categorySelectAllBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const category = this.dataset.category;
                const categoryItems = document.querySelectorAll(`.competency-item[data-category="${category}"]`);
                
                categoryItems.forEach(item => {
                    if (!item.classList.contains('hidden')) {
                        const checkbox = item.querySelector('.competency-checkbox');
                        if (checkbox) checkbox.checked = true;
                    }
                });
                
                updateSelectedCount();
            });
        });

        // Competency Search Functionality
        const searchInput = document.getElementById('competencySearch');
        const competencyItems = document.querySelectorAll('.competency-item');
        const noResults = document.getElementById('noResults');
        
        function updateVisibility() {
            const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
            let visibleCount = 0;
            
            competencyItems.forEach(item => {
                const itemCategory = item.dataset.category;
                const name = item.dataset.name || '';
                const framework = item.dataset.framework || '';
                const description = item.dataset.description || '';
                
                // Check if item matches search
                const matchesSearch = !searchTerm || 
                                    name.includes(searchTerm) || 
                                    framework.includes(searchTerm) || 
                                    description.includes(searchTerm);
                
                // Check if item matches category filter
                const matchesCategory = currentCategory === 'all' || itemCategory === currentCategory;
                
                if (matchesSearch && matchesCategory) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });
            
            // Show/hide categories based on visibility
            competencyCategories.forEach(category => {
                const visibleItems = category.querySelectorAll('.competency-item:not(.hidden)');
                if (visibleItems.length === 0) {
                    category.classList.add('hidden');
                } else {
                    if (currentCategory === 'all' || category.dataset.category === currentCategory) {
                        category.classList.remove('hidden');
                    }
                }
            });
            
            // Show/hide no results message
            if (noResults) {
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }
        }
        
        if (searchInput) {
            searchInput.addEventListener('input', updateVisibility);
        }
        
        // Select All / Deselect All
        const selectAllBtn = document.getElementById('selectAllBtn');
        const deselectAllBtn = document.getElementById('deselectAllBtn');
        const competencyCheckboxes = document.querySelectorAll('.competency-checkbox');
        
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                competencyCheckboxes.forEach(checkbox => {
                    if (!checkbox.closest('.competency-item').classList.contains('hidden')) {
                        checkbox.checked = true;
                    }
                });
                updateSelectedCount();
            });
        }
        
        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', function() {
                competencyCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateSelectedCount();
            });
        }
        
        // Update Selected Count
        function updateSelectedCount() {
            const selectedCount = document.querySelectorAll('.competency-checkbox:checked').length;
            const countBadge = document.getElementById('selectedCount');
            
            if (countBadge) {
                countBadge.textContent = `${selectedCount} competenc${selectedCount !== 1 ? 'ies' : 'y'}`;
                
                // Update badge color based on count
                if (selectedCount === 0) {
                    countBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
                } else if (selectedCount <= 3) {
                    countBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                } else if (selectedCount <= 6) {
                    countBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
                } else {
                    countBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800';
                }
            }
        }
        
        // Add event listeners to checkboxes
        competencyCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
        
        // Initial count update
        updateSelectedCount();
        
        // Form submission with validation
        document.getElementById('assignCompetencyForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = e.target;
            const employeeId = form.querySelector('[name="employee_id"]').value;
            const assignmentType = form.querySelector('[name="assignment_type"]:checked')?.value;
            const priority = form.querySelector('[name="priority"]:checked')?.value;
            const targetDate = form.querySelector('[name="target_date"]').value;
            const notes = form.querySelector('[name="notes"]').value;
            
            // Get selected competencies
            const selectedCompetencies = Array.from(form.querySelectorAll('[name="competencies[]"]:checked'));
            
            if (selectedCompetencies.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Competencies Selected',
                    text: 'Please select at least one competency to assign',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }
            
            if (!assignmentType) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Assignment Type Required',
                    text: 'Please select an assignment type',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }
            
            if (!priority) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Priority Required',
                    text: 'Please select a priority level',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }
            
            // Show loading
            Swal.fire({
                title: 'Assigning Competencies...',
                html: `Assigning ${selectedCompetencies.length} competenc${selectedCompetencies.length > 1 ? 'ies' : 'y'} to employee`,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            let successCount = 0;
            let errorCount = 0;
            const errors = [];
            
            // Submit each competency as a separate request
            for (const checkbox of selectedCompetencies) {
                const competencyId = checkbox.value;
                const competencyName = checkbox.dataset.name;
                
                try {
                    const response = await fetch('{{ route("competency.skill-gaps.assign") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            employee_id: employeeId,
                            competency_id: competencyId,
                            assignment_type: assignmentType,
                            priority: priority,
                            target_date: targetDate || null,
                            notes: notes
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        successCount++;
                    } else {
                        errorCount++;
                        errors.push(`${competencyName}: ${data.message}`);
                    }
                } catch (error) {
                    errorCount++;
                    errors.push(`${competencyName}: Network error`);
                }
            }
            
            // Show result
            if (successCount > 0 && errorCount === 0) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    html: `Successfully assigned ${successCount} competenc${successCount > 1 ? 'ies' : 'y'}`,
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    window.location.href = '{{ route("competency.gap-analysis") }}';
                });
            } else if (successCount > 0 && errorCount > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Partially Complete',
                    html: `<p>${successCount} succeeded, ${errorCount} failed</p><div class="text-left text-sm mt-2">${errors.join('<br>')}</div>`,
                    confirmButtonColor: '#f59e0b'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Assignment Failed',
                    html: `<div class="text-left text-sm">${errors.join('<br>')}</div>`,
                    confirmButtonColor: '#ef4444'
                });
            }
        });
    </script>
</x-app-layout>