

<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    @php
        // ── Pre-compute analytics data ──
        $allEmployees = collect()->merge($approvedEmployees)->merge($developmentNeeded ?? collect());
        $totalAll = $allEmployees->count();
        $totalExperts = $approvedEmployees->count();
        $totalDevelopment = isset($developmentNeeded) ? $developmentNeeded->count() : 0;

        // Readiness distribution (from approvedEmployees)
        $aReadyNow = 0; $aHighPotential = 0; $aModerate = 0; $aDeveloping = 0;
        // Risk distribution (all)
        $aLowRisk = 0; $aMedRisk = 0; $aHighRisk = 0;
        // Scatter data
        $aScatterData = [];
        // Job title distribution
        $jobDist = [];
        // Pipeline status
        $aInPipeline = 0; $aActionable = 0;
        // Score ranges for histogram
        $scoreRanges = ['90-100%' => 0, '80-89%' => 0, '70-79%' => 0, '60-69%' => 0, '<60%' => 0];

        foreach ($allEmployees as $emp) {
            $es = $emp->evaluation_score ?? 0;
            $esp = $emp->evaluation_score_percent ?? 0;
            $ls = $emp->leadership_score ?? 0;

            // Readiness
            if ($es >= 4.5) $aReadyNow++;
            elseif ($es >= 4.0) $aHighPotential++;
            elseif ($es >= 3.0) $aModerate++;
            else $aDeveloping++;

            // Risk
            $rl = $emp->risk_level ?? 'Medium Risk';
            if (str_contains($rl, 'Low')) $aLowRisk++;
            elseif (str_contains($rl, 'High')) $aHighRisk++;
            else $aMedRisk++;

            // Scatter
            $aScatterData[] = ['x' => round($es, 2), 'y' => round($esp), 'name' => $emp->full_name, 'expert' => $emp->is_expert ?? false];

            // Job distribution
            $jt = $emp->job_title ?? 'Unknown';
            $jobDist[$jt] = ($jobDist[$jt] ?? 0) + 1;

            // Pipeline
            if (in_array($emp->employee_id, $promotedIds)) $aInPipeline++;
            else $aActionable++;

            // Score ranges
            if ($esp >= 90) $scoreRanges['90-100%']++;
            elseif ($esp >= 80) $scoreRanges['80-89%']++;
            elseif ($esp >= 70) $scoreRanges['70-79%']++;
            elseif ($esp >= 60) $scoreRanges['60-69%']++;
            else $scoreRanges['<60%']++;
        }

        $avgEvalScore = $totalAll > 0 ? round($allEmployees->avg('evaluation_score'), 2) : 0;
        $avgLeadership = $totalAll > 0 ? round($allEmployees->avg('leadership_score'), 2) : 0;
        $expertRate = $totalAll > 0 ? round(($totalExperts / $totalAll) * 100, 1) : 0;
        $pipelineRate = $totalExperts > 0 ? round(($aInPipeline / $totalExperts) * 100, 1) : 0;

        // Top 5 by evaluation score
        $topSuccessors = $allEmployees->sortByDesc('evaluation_score')->take(5);
    @endphp

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <div class="py-6 px-4">
        <div class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-t-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2"><i class='bx bx-trophy mr-2'></i>Potential Successors - Expert Level</h1>
                    <p class="text-blue-100">Employees with 80%+ evaluation scores qualified for succession pipeline</p>
                </div>
                <div class="text-right">
                    <div class="bg-blue-800 bg-opacity-50 rounded-lg p-3 border border-blue-400">
                        <div class="text-2xl font-bold text-white">{{ $approvedEmployees->count() }}</div>
                        <div class="text-sm text-blue-100">Expert Candidates</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════
             ANALYTICS DASHBOARD
             ═══════════════════════════════════════════════════════════════ --}}
        @if($totalAll > 0)
        <div class="bg-white shadow-lg px-6 pt-5 pb-4">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="bx bx-bar-chart-alt-2 text-blue-600 text-xl"></i> Succession Analytics
                </h5>
                <button onclick="toggleSuccessionAnalytics()" id="saToggleBtn" class="px-3 py-1.5 text-xs font-semibold text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <i class="bx bx-chevron-up mr-1" id="saToggleIcon"></i>Collapse
                </button>
            </div>

            <div id="saContent">
                {{-- ── KPI CARDS ── --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-5">
                    <div class="rounded-xl p-3 text-white" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                        <div class="text-xs opacity-80 uppercase tracking-wide font-semibold">All Evaluated</div>
                        <div class="text-2xl font-extrabold mt-1">{{ $totalAll }}</div>
                        <div class="text-[10px] opacity-70 mt-0.5">{{ $totalExperts }} expert · {{ $totalDevelopment }} dev</div>
                    </div>
                    <div class="rounded-xl p-3 text-white" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                        <div class="text-xs opacity-80 uppercase tracking-wide font-semibold">Expert Rate</div>
                        <div class="text-2xl font-extrabold mt-1">{{ $expertRate }}%</div>
                        <div class="text-[10px] opacity-70 mt-0.5">≥80% eval score</div>
                    </div>
                    <div class="rounded-xl p-3 text-white" style="background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);">
                        <div class="text-xs opacity-80 uppercase tracking-wide font-semibold">Avg Eval Score</div>
                        <div class="text-2xl font-extrabold mt-1">{{ number_format($avgEvalScore, 1) }}</div>
                        <div class="text-[10px] opacity-70 mt-0.5">out of 5.0</div>
                    </div>
                    <div class="rounded-xl p-3 text-white" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
                        <div class="text-xs opacity-80 uppercase tracking-wide font-semibold">Avg Leadership</div>
                        <div class="text-2xl font-extrabold mt-1">{{ number_format($avgLeadership, 1) }}</div>
                        <div class="text-[10px] opacity-70 mt-0.5">out of 5.0</div>
                    </div>
                    <div class="rounded-xl p-3 text-white" style="background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);">
                        <div class="text-xs opacity-80 uppercase tracking-wide font-semibold">In Pipeline</div>
                        <div class="text-2xl font-extrabold mt-1">{{ $aInPipeline }}</div>
                        <div class="text-[10px] opacity-70 mt-0.5">{{ $pipelineRate }}% of experts</div>
                    </div>
                    <div class="rounded-xl p-3 text-white" style="background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);">
                        <div class="text-xs opacity-80 uppercase tracking-wide font-semibold">High Risk</div>
                        <div class="text-2xl font-extrabold mt-1">{{ $aHighRisk }}</div>
                        <div class="text-[10px] opacity-70 mt-0.5">need development</div>
                    </div>
                </div>

                {{-- ── CHARTS ROW 1 ── --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    {{-- Readiness Distribution (Doughnut) --}}
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <h6 class="text-xs font-bold text-gray-700 mb-3 flex items-center gap-1.5">
                            <i class="bx bx-pie-chart-alt-2 text-indigo-500"></i> Readiness Distribution
                        </h6>
                        <div style="position: relative; height: 210px;">
                            <canvas id="saReadinessChart"></canvas>
                        </div>
                    </div>

                    {{-- Score Distribution (Bar Histogram) --}}
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <h6 class="text-xs font-bold text-gray-700 mb-3 flex items-center gap-1.5">
                            <i class="bx bx-bar-chart text-blue-500"></i> Score Distribution
                        </h6>
                        <div style="position: relative; height: 210px;">
                            <canvas id="saScoreHistChart"></canvas>
                        </div>
                    </div>

                    {{-- Risk Assessment (Horizontal Bar) --}}
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <h6 class="text-xs font-bold text-gray-700 mb-3 flex items-center gap-1.5">
                            <i class="bx bx-shield-alt-2 text-red-500"></i> Risk Assessment
                        </h6>
                        <div style="position: relative; height: 210px;">
                            <canvas id="saRiskChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- ── CHARTS ROW 2 ── --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    {{-- Expert vs Development (Scatter) --}}
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <h6 class="text-xs font-bold text-gray-700 mb-3 flex items-center gap-1.5">
                            <i class="bx bx-scatter-chart text-green-500"></i> Eval Score vs Percentage
                        </h6>
                        <div style="position: relative; height: 210px;">
                            <canvas id="saScatterChart"></canvas>
                        </div>
                    </div>

                    {{-- Job Title Distribution (Horizontal Bar) --}}
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <h6 class="text-xs font-bold text-gray-700 mb-3 flex items-center gap-1.5">
                            <i class="bx bx-briefcase text-purple-500"></i> By Current Role
                        </h6>
                        <div style="position: relative; height: 210px;">
                            <canvas id="saJobChart"></canvas>
                        </div>
                    </div>

                    {{-- Top 5 Successor Candidates --}}
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <h6 class="text-xs font-bold text-gray-700 mb-3 flex items-center gap-1.5">
                            <i class="bx bx-trophy text-yellow-500"></i> Top 5 Candidates
                        </h6>
                        <div>
                            @foreach($topSuccessors as $idx => $ts)
                            @php
                                $rankColors = ['#FFD700', '#C0C0C0', '#CD7F32', '#6366f1', '#a78bfa'];
                                $tsEs = $ts->evaluation_score ?? 0;
                            @endphp
                            <div class="flex items-center py-2 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                <div class="flex items-center justify-center rounded-full mr-2.5" style="width: 26px; height: 26px; background: {{ $rankColors[$idx] ?? '#6b7280' }}; color: white; font-size: 11px; font-weight: 800; flex-shrink: 0;">
                                    {{ $idx + 1 }}
                                </div>
                                <div class="flex-grow min-w-0">
                                    <div class="text-xs font-bold text-gray-800 truncate">{{ $ts->full_name }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $ts->job_title }}</div>
                                </div>
                                <div class="ml-2 flex-shrink-0">
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-bold {{ $tsEs >= 4.5 ? 'bg-green-100 text-green-700' : ($tsEs >= 4.0 ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ number_format($tsEs, 1) }}/5
                                    </span>
                                </div>
                            </div>
                            @endforeach
                            @if($topSuccessors->isEmpty())
                                <div class="text-center text-gray-400 py-6 text-xs">No data available</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── PIPELINE HEALTH BAR ── --}}
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <h6 class="text-xs font-bold text-gray-700 mb-2 flex items-center gap-1.5">
                        <i class="bx bx-git-branch text-blue-600"></i> Succession Pipeline Overview
                    </h6>
                    <div class="flex items-center mb-2">
                        <div class="flex-grow bg-gray-200 rounded-full h-5 overflow-hidden">
                            @if($totalAll > 0)
                            <div class="h-full flex">
                                <div style="width: {{ ($aReadyNow/$totalAll)*100 }}%; background: #059669;" class="flex items-center justify-center text-white text-[9px] font-bold" title="Ready Now">
                                    @if(($aReadyNow/$totalAll)*100 > 8) {{ $aReadyNow }} @endif
                                </div>
                                <div style="width: {{ ($aHighPotential/$totalAll)*100 }}%; background: #2563eb;" class="flex items-center justify-center text-white text-[9px] font-bold" title="High Potential">
                                    @if(($aHighPotential/$totalAll)*100 > 8) {{ $aHighPotential }} @endif
                                </div>
                                <div style="width: {{ ($aModerate/$totalAll)*100 }}%; background: #f59e0b;" class="flex items-center justify-center text-white text-[9px] font-bold" title="Moderate">
                                    @if(($aModerate/$totalAll)*100 > 8) {{ $aModerate }} @endif
                                </div>
                                <div style="width: {{ ($aDeveloping/$totalAll)*100 }}%; background: #9ca3af;" class="flex items-center justify-center text-white text-[9px] font-bold" title="Developing">
                                    @if(($aDeveloping/$totalAll)*100 > 8) {{ $aDeveloping }} @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4 text-[11px] text-gray-600">
                        <span><span class="inline-block w-2 h-2 rounded-full mr-1" style="background:#059669;"></span>Ready Now ({{ $aReadyNow }})</span>
                        <span><span class="inline-block w-2 h-2 rounded-full mr-1" style="background:#2563eb;"></span>High Potential ({{ $aHighPotential }})</span>
                        <span><span class="inline-block w-2 h-2 rounded-full mr-1" style="background:#f59e0b;"></span>Moderate ({{ $aModerate }})</span>
                        <span><span class="inline-block w-2 h-2 rounded-full mr-1" style="background:#9ca3af;"></span>Developing ({{ $aDeveloping }})</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-b-lg shadow-lg">
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 shadow-sm" id="successorsTable">
                        <thead class="bg-gradient-to-r from-blue-50 to-purple-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Employee Profile</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Current Role</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Performance Metrics</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Leadership Readiness</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Succession Target</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Risk Assessment</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($approvedEmployees as $employee)
                                @php
                                    // Use pre-calculated values from route
                                    $performanceScore = $employee->evaluation_score_percent ?? 0;
                                    $leadershipReadiness = $employee->leadership_score ?? 0;
                                    
                                    // Use pre-calculated criteria from route
                                    $pipelineReadyCriteria = $employee->criteria ?? [];
                                    $pipelineReadyCount = $employee->ready_count ?? 0;
                                    $isPipelineReady = $pipelineReadyCount >= 3;
                                    
                                    // Determine readiness level based on evaluation score
                                    $readinessLevel = 'Developing';
                                    $readinessColor = 'bg-yellow-100 text-yellow-800';
                                    $readinessIcon = 'bx-trending-up';
                                    
                                    $evalScore = $employee->evaluation_score ?? 0;
                                    if ($evalScore >= 4.5) {
                                        $readinessLevel = 'Ready Now';
                                        $readinessColor = 'bg-green-100 text-green-800';
                                        $readinessIcon = 'bx-check-circle';
                                    } elseif ($evalScore >= 3.5) {
                                        $readinessLevel = 'High Potential';
                                        $readinessColor = 'bg-blue-100 text-blue-800';
                                        $readinessIcon = 'bx-star';
                                    } elseif ($evalScore >= 3.0) {
                                        $readinessLevel = 'Moderate Potential';
                                        $readinessColor = 'bg-indigo-100 text-indigo-800';
                                        $readinessIcon = 'bx-bar-chart-alt-2';
                                    }
                                    
                                    // Risk assessment from route
                                    $riskLevel = str_replace(' Risk', '', $employee->risk_level ?? 'Medium');
                                    $riskColor = 'bg-green-100 text-green-700';
                                    if ($riskLevel == 'High') {
                                        $riskColor = 'bg-red-100 text-red-700';
                                    } elseif ($riskLevel == 'Medium') {
                                        $riskColor = 'bg-yellow-100 text-yellow-700';
                                    }
                                    
                                    // Leadership stars
                                    $stars = floor($leadershipReadiness);
                                    $hasHalfStar = ($leadershipReadiness - $stars) >= 0.5;
                                @endphp
                                
                                <form method="POST" action="{{ route('succession.promote') }}">
                                    @csrf
                                    <tr class="hover:bg-blue-50 transition-all duration-200" data-score="{{ $performanceScore }}" data-category="{{ strtolower(str_replace(' ', '-', $readinessLevel)) }}">
                                        <!-- Employee Profile -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                                        {{ strtoupper(substr($employee->full_name, 0, 2)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900">{{ $employee->full_name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $employee->employee_id }}</div>
                                                    <div class="text-xs text-gray-500">{{ $employee->email ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
                                            <input type="hidden" name="employee_name" value="{{ $employee->full_name }}">
                                            <input type="hidden" name="employee_email" value="{{ $employee->email ?? '' }}">
                                        </td>

                                        <!-- Current Role -->
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $employee->job_title }}</div>
                                            <div class="text-xs text-gray-500">Current Position</div>
                                            <input type="hidden" name="job_title" value="{{ $employee->job_title }}">
                                        </td>

                                        <!-- Performance Metrics -->
                                        <td class="px-6 py-4">
                                            <div class="space-y-2">
                                                <div>
                                                    <div class="flex justify-between text-xs">
                                                        <span class="font-medium">Evaluation Score</span>
                                                        <span class="font-bold">{{ $employee->evaluation_score ?? 0 }}/5.0 ({{ $performanceScore }}%)</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="h-2 rounded-full {{ $performanceScore >= 80 ? 'bg-green-500' : ($performanceScore >= 70 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                             style="width: {{ $performanceScore }}%"></div>
                                                    </div>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    Assessments: 
                                                    <span class="font-medium text-blue-600">
                                                        {{ $employee->assessment_count ?? 1 }} completed
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Leadership Readiness -->
                                        <td class="px-6 py-4 text-center">
                                            <div class="space-y-2">
                                                <div class="flex justify-center space-x-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $stars)
                                                            <i class="bx bxs-star text-yellow-400 text-lg"></i>
                                                        @elseif($i == $stars + 1 && $hasHalfStar)
                                                            <i class="bx bxs-star-half text-yellow-400 text-lg"></i>
                                                        @else
                                                            <i class="bx bx-star text-gray-300 text-lg"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <div class="text-xs font-medium text-gray-600">{{ number_format($leadershipReadiness, 1) }}/5.0</div>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $readinessColor }}">
                                                    <i class="bx {{ $readinessIcon }} mr-1"></i>
                                                    {{ $readinessLevel }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Succession Target -->
                                        <td class="px-6 py-4">
                                            <div class="succession-target-container" data-employee-id="{{ $employee->employee_id }}">
                                                <select name="potential_job" class="job-select w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                                    <option value="">Loading jobs...</option>
                                                </select>
                                                <div class="job-details mt-2 text-xs text-gray-500 hidden"></div>
                                                <div class="ai-recommendation mt-2 hidden">
                                                    <div class="flex items-center gap-1 text-purple-600 text-xs font-medium">
                                                        <i class='bx bx-bot'></i>
                                                        <span>AI Recommended: </span>
                                                        <span class="ai-recommended-job font-bold">--</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="assessment_score" value="{{ number_format($leadershipReadiness, 1) }}">
                                            <input type="hidden" name="category" value="Leadership Development">
                                            <input type="hidden" name="strengths" value="High performance in assessments, demonstrated competency">
                                            <input type="hidden" name="recommendations" value="Recommended for succession planning based on comprehensive evaluation">
                                            <input type="hidden" name="status" value="pending">
                                        </td>

                                        <!-- Risk Assessment -->
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $riskColor }}">
                                                {{ $riskLevel }} Risk
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1">
                                                @if($riskLevel == 'Low')
                                                    Strong succession candidate
                                                @elseif($riskLevel == 'Medium')
                                                    Moderate development needed
                                                @else
                                                    Requires significant development
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Action -->
                                        <td class="px-6 py-4">
                                            <div class="space-y-2">
                                                @if(in_array($employee->employee_id, $promotedIds))
                                                    @php
                                                        $promoRecord = \App\Modules\succession_planning\Models\Promotion::where('employee_id', $employee->employee_id)->first();
                                                        $promoStatus = $promoRecord->status ?? 'approved';
                                                    @endphp
                                                    @if($promoStatus === 'pending_acceptance')
                                                        <button type="button" class="w-full px-4 py-2 bg-amber-500 text-white rounded-lg font-medium text-xs shadow transition flex items-center justify-center gap-2" disabled>
                                                            <i class='bx bx-time-five'></i> Awaiting Employee
                                                        </button>
                                                    @else
                                                        <button type="button" class="w-full px-4 py-2 bg-gray-400 text-white rounded-lg font-medium text-xs shadow transition flex items-center justify-center gap-2" disabled>
                                                            <i class='bx bx-check-circle'></i> In Pipeline
                                                        </button>
                                                    @endif
                                                @else
                                                    @if($isPipelineReady)
                                                        <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium text-xs shadow-md transition-all duration-200 flex items-center justify-center gap-2">
                                                            <i class='bx bx-trending-up'></i> Add to Pipeline
                                                        </button>
                                                    @elseif($performanceScore >= 60)
                                                        <button type="button" onclick="showPipelineReadiness('{{ $employee->employee_id }}', {{ json_encode($pipelineReadyCriteria) }}, {{ $pipelineReadyCount }})" class="w-full px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium text-xs shadow-md transition-all duration-200 flex items-center justify-center gap-2">
                                                            <i class='bx bx-time-five'></i> Pipeline Assessment
                                                        </button>
                                                    @else
                                                        <button type="button" onclick="showDevelopmentOptions('{{ $employee->employee_id }}', '{{ $employee->full_name }}', {{ $performanceScore }}, '{{ $employee->status }}')" class="w-full px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium text-xs shadow-md transition-all duration-200 flex items-center justify-center gap-2">
                                                            <i class='bx bx-trending-up'></i> Start Development
                                                        </button>
                                                    @endif
                                                @endif
                                                
                                                <button type="button" onclick="viewPipelineReadiness('{{ $employee->employee_id }}', '{{ $employee->full_name }}', {{ json_encode($pipelineReadyCriteria) }}, {{ $pipelineReadyCount }})" class="w-full px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-md font-medium text-xs transition-all duration-200 flex items-center justify-center gap-1">
                                                    <i class='bx bx-bar-chart-alt-2 text-sm'></i> Readiness Check
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-gray-400">
                                            <i class="bx bx-trophy text-6xl mb-4 text-yellow-400"></i>
                                            <div class="text-lg font-medium text-gray-500">No Expert-Level Candidates Yet</div>
                                            <div class="text-sm text-gray-400">Employees with 80%+ evaluation scores will appear here as potential successors</div>
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

    <!-- Development Needed Section -->
    @if(isset($developmentNeeded) && $developmentNeeded->count() > 0)
    <div class="py-6 px-4">
        <div class="bg-gradient-to-r from-orange-500 to-amber-600 rounded-t-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2"><i class='bx bx-trending-up mr-2'></i>Skills Development Required</h2>
                    <p class="text-orange-100">Employees who need skill improvement before becoming potential successors</p>
                </div>
                <div class="text-right">
                    <div class="bg-orange-800 bg-opacity-50 rounded-lg p-3 border border-orange-300">
                        <span class="text-3xl font-bold text-white">{{ $developmentNeeded->count() }}</span>
                        <span class="block text-sm text-orange-100">Needs Development</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-b-lg shadow-lg">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 shadow-sm" id="developmentTable">
                        <thead class="bg-gradient-to-r from-orange-50 to-amber-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Employee Profile</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Current Role</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Current Score</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Gap to Expert</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Development Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($developmentNeeded as $employee)
                                @php
                                    $evalScore = $employee->evaluation_score ?? 0;
                                    $evalPercent = $employee->evaluation_score_percent ?? 0;
                                    $gapToExpert = 80 - $evalPercent; // Expert threshold is 80%
                                    $gapScore = 4.0 - $evalScore; // Expert threshold is 4.0/5.0
                                    
                                    // Determine development priority
                                    if ($evalPercent >= 70) {
                                        $devPriority = 'Almost Ready';
                                        $devColor = 'bg-blue-100 text-blue-800';
                                        $devIcon = 'bx-target-lock';
                                    } elseif ($evalPercent >= 60) {
                                        $devPriority = 'Moderate Gap';
                                        $devColor = 'bg-yellow-100 text-yellow-800';
                                        $devIcon = 'bx-trending-up';
                                    } else {
                                        $devPriority = 'Significant Gap';
                                        $devColor = 'bg-orange-100 text-orange-800';
                                        $devIcon = 'bx-error-circle';
                                    }
                                @endphp
                                
                                <tr class="hover:bg-orange-50 transition-all duration-200">
                                    <!-- Employee Profile -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center text-white font-bold text-lg">
                                                    {{ strtoupper(substr($employee->full_name, 0, 2)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $employee->full_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $employee->employee_id }}</div>
                                                <div class="text-xs text-gray-500">{{ $employee->email ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Current Role -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $employee->job_title }}</div>
                                        <div class="text-xs text-gray-500">Current Position</div>
                                    </td>

                                    <!-- Current Score -->
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            <div>
                                                <div class="flex justify-between text-xs">
                                                    <span class="font-medium">Evaluation Score</span>
                                                    <span class="font-bold {{ $evalPercent >= 70 ? 'text-blue-600' : 'text-orange-600' }}">{{ $evalScore }}/5.0 ({{ $evalPercent }}%)</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                    <div class="h-2 rounded-full {{ $evalPercent >= 70 ? 'bg-blue-500' : ($evalPercent >= 60 ? 'bg-yellow-500' : 'bg-orange-500') }}" 
                                                         style="width: {{ $evalPercent }}%"></div>
                                                </div>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Level: <span class="font-medium {{ $evalPercent >= 70 ? 'text-blue-600' : 'text-orange-600' }}">{{ $employee->potential_level }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Gap to Expert -->
                                    <td class="px-6 py-4 text-center">
                                        <div class="space-y-2">
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-700">
                                                <i class='bx bx-minus-circle mr-1'></i> {{ number_format($gapScore, 1) }} pts
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Need <span class="font-bold text-green-600">{{ $gapToExpert }}%</span> more to reach Expert
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-1">
                                                <div class="h-1 rounded-full bg-green-500" style="width: {{ $evalPercent }}%"></div>
                                                <div class="text-xs text-gray-400 mt-1">Target: 80%</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Development Status -->
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $devColor }}">
                                            <i class='bx {{ $devIcon }} mr-1'></i>
                                            {{ $devPriority }}
                                        </span>
                                        <div class="text-xs text-gray-500 mt-2">
                                            Risk: <span class="font-medium {{ $employee->risk_level == 'High Risk' ? 'text-red-600' : 'text-yellow-600' }}">{{ $employee->risk_level }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Pipeline Readiness Assessment Modal -->
    <div id="pipelineModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-t-lg">
                <h3 class="text-xl font-bold">Pipeline Readiness Assessment</h3>
                <p class="text-blue-100 text-sm mt-1">Comprehensive evaluation for succession pipeline eligibility</p>
            </div>
            
            <div class="p-6">
                <div id="modalContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
                
                <div class="mt-6 flex gap-3">
                    <button onclick="closePipelineModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                        Close
                    </button>
                    <button id="addToPipelineBtn" onclick="addToPipeline()" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                        Add to Pipeline
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Development Modal -->
    <div id="developmentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 id="developmentTitle" class="text-xl font-semibold text-gray-800">Employee Development Plan</h3>
                        <button onclick="closeDevelopmentModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="bx bx-x text-2xl"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div id="developmentContent">
                        <!-- Development options will be populated here -->
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 bg-gray-50">
                    <div class="flex justify-end space-x-3">
                        <button onclick="closeDevelopmentModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                            Close
                        </button>
                        <button onclick="startDevelopmentProgram()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="bx bx-play mr-2"></i>Start Development Program
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <style>
        .active-filter {
            background: linear-gradient(135deg, #3b82f6, #6366f1) !important;
            color: white !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
        
        .succession-card {
            transition: all 0.3s ease;
        }
        
        .succession-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        /* Progress bar animations */
        .progress-bar {
            transition: width 0.8s ease-in-out;
        }
        
        /* Star rating hover effects */
        .bx-star:hover, .bxs-star:hover {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }
        
        /* Button hover effects */
        button:hover {
            transform: translateY(-1px);
        }
        
        /* Table row hover effects */
        tbody tr:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Line clamp for job descriptions */
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Disabled option styling */
        select option:disabled {
            color: #9ca3af;
            background-color: #f3f4f6;
        }

        /* Succession Target column - allow expansion */
        .succession-target-container {
            min-width: 280px;
            max-width: 350px;
        }

        .succession-target-container .job-select {
            width: 100%;
        }

        .succession-target-container .job-details {
            max-width: 100%;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .succession-target-container .job-details .grid {
            font-size: 11px;
        }

        .succession-target-container .job-details p {
            word-break: break-word;
            white-space: normal;
        }

        /* Table layout adjustments */
        #successorsTable {
            table-layout: auto;
        }

        #successorsTable th:nth-child(5),
        #successorsTable td:nth-child(5) {
            min-width: 280px;
            max-width: 350px;
            white-space: normal;
        }

        /* Responsive adjustments for job details card */
        .job-details > div {
            width: 100%;
        }

        .job-details .grid-cols-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px;
        }

        .job-details .grid-cols-2 > div {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* AI recommendation styling */
        .ai-recommendation {
            padding: 4px 8px;
            background-color: #f3e8ff;
            border-radius: 6px;
            border: 1px solid #c4b5fd;
        }
    </style>

    <!-- BoxIcons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const aiRecommendations = @json($aiRecommendations ?? []);

        // Filter candidates based on readiness level
        function filterCandidates(category) {
            const rows = document.querySelectorAll('#successorsTable tbody tr');
            const buttons = document.querySelectorAll('.p-6.bg-gray-50 button');
            
            // Update active button
            buttons.forEach(btn => {
                btn.classList.remove('active-filter', 'bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            event.target.classList.add('active-filter');
            event.target.classList.remove('bg-gray-200', 'text-gray-700');
            
            rows.forEach(row => {
                if (row.children.length < 2) return; // Skip empty rows
                
                const score = parseFloat(row.getAttribute('data-score') || 0);
                const rowCategory = row.getAttribute('data-category');
                let show = false;
                
                switch(category) {
                    case 'all':
                        show = true;
                        break;
                    case 'ready-now':
                        show = score >= 90;
                        break;
                    case 'high-potential':
                        show = score >= 80 && score < 90;
                        break;
                    case 'developing':
                        show = score < 80;
                        break;
                }
                
                if (show) {
                    row.style.display = '';
                    row.style.animation = 'fadeIn 0.5s ease-in-out';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Create development plan for low-scoring candidates
        function createDevelopmentPlan(employeeId) {
            if (confirm('Create a personalized development plan for this employee? This will identify skill gaps and recommend training programs.')) {
                // In real implementation, this would redirect to development planning module
                alert('Development planning module will create a comprehensive growth plan including:\n\n• Skill gap analysis\n• Training recommendations\n• Mentorship programs\n• Performance milestones\n• Career progression timeline');
            }
        }

        // View pipeline readiness assessment
        function viewPipelineReadiness(employeeId, employeeName, criteria, readyCount) {
            const modal = document.getElementById('pipelineModal');
            const content = document.getElementById('modalContent');
            const addBtn = document.getElementById('addToPipelineBtn');
            
            // Store current employee data
            window.currentEmployee = { id: employeeId, name: employeeName };
            
            const criteriaLabels = {
                'performance': 'Performance Score (≥75%)',
                'assessment_passed': 'Assessment Status (Passed)',
                'leadership_score': 'Leadership Readiness (≥3.5/5.0)'
            };
            
            const isReady = readyCount >= 3;
            
            let html = `
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800">${employeeName}</h4>
                    <p class="text-sm text-gray-600">Employee ID: ${employeeId}</p>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="font-medium text-gray-700">Pipeline Readiness Score</h5>
                        <div class="text-right">
                            <span class="text-2xl font-bold ${isReady ? 'text-green-600' : 'text-orange-600'}">${readyCount}/3</span>
                            <div class="text-xs text-gray-500">Criteria Met</div>
                        </div>
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                        <div class="h-3 rounded-full ${isReady ? 'bg-green-500' : 'bg-orange-500'}" style="width: ${(readyCount/3)*100}%"></div>
                    </div>
                    
                    <div class="text-center mb-4">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium ${isReady ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800'}">
                            <i class="bx ${isReady ? 'bx-check-circle' : 'bx-time-five'} mr-2"></i>
                            ${isReady ? 'Ready for Pipeline' : 'Needs Development'}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h5 class="font-medium text-gray-700 mb-3">Readiness Criteria</h5>
            `;
            
            Object.entries(criteria).forEach(([key, met]) => {
                html += `
                    <div class="flex items-center justify-between p-3 rounded-lg ${met ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'}">
                        <div class="flex items-center">
                            <i class="bx ${met ? 'bx-check-circle text-green-600' : 'bx-x-circle text-red-600'} text-xl mr-3"></i>
                            <span class="font-medium ${met ? 'text-green-800' : 'text-red-800'}">${criteriaLabels[key]}</span>
                        </div>
                        <span class="text-sm font-medium ${met ? 'text-green-600' : 'text-red-600'}">
                            ${met ? 'Met' : 'Not Met'}
                        </span>
                    </div>
                `;
            });
            
            if (!isReady) {
                html += `
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h6 class="font-medium text-blue-800 mb-2">Development Recommendations:</h6>
                        <ul class="text-sm text-blue-700 space-y-1">
                `;
                
                if (!criteria.performance) {
                    html += '<li>• Focus on improving assessment performance through targeted training</li>';
                }
                if (!criteria.assessment_passed) {
                    html += '<li>• Complete required assessments with passing scores</li>';
                }
                if (!criteria.leadership_score) {
                    html += '<li>• Participate in leadership development programs</li>';
                }
                
                html += `
                        </ul>
                        <div class="mt-4">
                            <button onclick="showDevelopmentPlan('${employeeId}', '${employeeName.replace(/'/g, "\\'")}', ${readyCount})" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="bx bx-book mr-2"></i>Create Development Plan
                            </button>
                        </div>
                    </div>
                `;
            }
            
            html += '</div>';
            
            content.innerHTML = html;
            addBtn.disabled = !isReady;
            addBtn.textContent = isReady ? 'Add to Pipeline' : 'Create Development Plan';
            
            // Change button behavior for non-ready employees
            if (!isReady) {
                addBtn.onclick = function() {
                    showDevelopmentPlan(window.currentEmployee.id, window.currentEmployee.name, readyCount);
                };
                addBtn.disabled = false;
                addBtn.className = addBtn.className.replace('bg-green-600', 'bg-blue-600').replace('hover:bg-green-700', 'hover:bg-blue-700');
            }
            
            modal.classList.remove('hidden');
        }

        // Show pipeline readiness for action button
        function showPipelineReadiness(employeeId, criteria, readyCount) {
            // This is a simplified version for the action button
            viewPipelineReadiness(employeeId, `Employee ${employeeId}`, criteria, readyCount);
        }

        // Close pipeline modal
        function closePipelineModal() {
            document.getElementById('pipelineModal').classList.add('hidden');
        }
        
        // Close development modal
        function closeDevelopmentModal() {
            document.getElementById('developmentModal').style.display = 'none';
        }
        
        function startDevelopmentProgram() {
            if (window.currentEmployee) {
                alert(`Development Program Started for ${window.currentEmployee.name}!\n\nProgram Initiated:\n• Comprehensive development plan created\n• Assessment retake opportunities scheduled\n• Training programs enrolled\n• Mentoring assignments made\n• Progress tracking activated\n\nThe employee will be notified of their development opportunities and can begin improving their pipeline readiness immediately.`);
                closeDevelopmentModal();
                // In real implementation, this would create development records and notify the employee
                // location.reload(); // Refresh to show updated development status
            }
        }

        function showDevelopmentPlan(employeeId, employeeName, criteriaCount) {
            const modal = document.getElementById('developmentModal');
            const content = modal.querySelector('#developmentContent');
            const title = modal.querySelector('#developmentTitle');
            
            title.textContent = `Development Plan for ${employeeName}`;
            
            let html = `
                <div class="mb-6">
                    <h4 class="font-semibold text-gray-800 mb-3">Available Development Options:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="selectDevelopmentOption('assessment', '${employeeId}')">
                            <div class="flex items-center mb-2">
                                <i class="bx bx-clipboard text-blue-600 text-xl mr-3"></i>
                                <h5 class="font-semibold">Retake Assessments</h5>
                            </div>
                            <p class="text-sm text-gray-600">Improve scores by retaking failed or low-scoring assessments</p>
                            <div class="mt-2">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">Immediate Impact</span>
                            </div>
                        </div>
                        
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="selectDevelopmentOption('training', '${employeeId}')">
                            <div class="flex items-center mb-2">
                                <i class="bx bx-book-open text-green-600 text-xl mr-3"></i>
                                <h5 class="font-semibold">Training Programs</h5>
                            </div>
                            <p class="text-sm text-gray-600">Enroll in targeted training programs to build missing skills</p>
                            <div class="mt-2">
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Skill Building</span>
                            </div>
                        </div>
                        
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="selectDevelopmentOption('mentoring', '${employeeId}')">
                            <div class="flex items-center mb-2">
                                <i class="bx bx-user-plus text-purple-600 text-xl mr-3"></i>
                                <h5 class="font-semibold">Mentoring Program</h5>
                            </div>
                            <p class="text-sm text-gray-600">Pair with senior employees for guidance and knowledge transfer</p>
                            <div class="mt-2">
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">Leadership Development</span>
                            </div>
                        </div>
                        
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="selectDevelopmentOption('project', '${employeeId}')">
                            <div class="flex items-center mb-2">
                                <i class="bx bx-briefcase text-orange-600 text-xl mr-3"></i>
                                <h5 class="font-semibold">Special Projects</h5>
                            </div>
                            <p class="text-sm text-gray-600">Assign challenging projects to develop leadership and technical skills</p>
                            <div class="mt-2">
                                <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded text-xs">Hands-on Experience</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h5 class="font-semibold text-yellow-800 mb-2">
                        <i class="bx bx-info-circle mr-2"></i>Recommended Development Path
                    </h5>
                    <div class="text-sm text-yellow-700">
                        <p class="mb-2">Based on current readiness criteria (${criteriaCount}/6 met), we recommend:</p>
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Start with <strong>Assessment Retakes</strong> for immediate score improvement</li>
                            <li>Enroll in <strong>Training Programs</strong> for skill gaps identified in assessments</li>
                            <li>Join <strong>Mentoring Program</strong> for leadership development</li>
                            <li>Take on <strong>Special Projects</strong> to demonstrate improved capabilities</li>
                        </ol>
                    </div>
                </div>
            `;
            
            content.innerHTML = html;
            modal.style.display = 'block';
        }
        
        function selectDevelopmentOption(option, employeeId) {
            switch(option) {
                case 'assessment':
                    showAssessmentRetakeOptions(employeeId);
                    break;
                case 'training':
                    showTrainingPrograms(employeeId);
                    break;
                case 'mentoring':
                    showMentoringOptions(employeeId);
                    break;
                case 'project':
                    showProjectAssignments(employeeId);
                    break;
            }
        }
        
        function showAssessmentRetakeOptions(employeeId) {
            // Close the development modal first
            document.getElementById('developmentModal').style.display = 'none';
            
            // Show assessment retake options
            alert(`Assessment Retake Options for Employee ${employeeId}\n\nAvailable Options:\n• Retake failed assessments (score < 70%)\n• Improve low-scoring assessments (70-80%)\n• Take additional assessments for skill gaps\n• Schedule comprehensive re-evaluation\n\nNext: This would redirect to assessment retake page where employee can:\n1. View their current assessment scores\n2. Select which assessments to retake\n3. Schedule retake sessions\n4. Track improvement progress`);
            
            // In real implementation, redirect to assessment retake page
            // window.location.href = `/learning-management/assessment-retake/${employeeId}`;
        }

        // Schedule re-assessment for employees needing development
        function scheduleReassessment(employeeId, employeeName) {
            Swal.fire({
                title: 'Schedule Re-Assessment',
                html: `
                    <div class="text-left">
                        <p class="text-sm text-gray-600 mb-4">Schedule a new assessment for <strong>${employeeName}</strong> to re-evaluate their competency level.</p>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assessment Type</label>
                            <select id="reassessmentType" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="competency">Competency Assessment</option>
                                <option value="skills">Skills Evaluation</option>
                                <option value="leadership">Leadership Assessment</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled Date</label>
                            <input type="date" id="reassessmentDate" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" min="${new Date().toISOString().split('T')[0]}">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea id="reassessmentNotes" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" rows="2" placeholder="Optional notes..."></textarea>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Schedule Assessment',
                confirmButtonColor: '#3B82F6',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const date = document.getElementById('reassessmentDate').value;
                    if (!date) {
                        Swal.showValidationMessage('Please select a date');
                        return false;
                    }
                    return {
                        type: document.getElementById('reassessmentType').value,
                        date: date,
                        notes: document.getElementById('reassessmentNotes').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Assessment Scheduled!',
                        html: `Re-assessment for <strong>${employeeName}</strong> has been scheduled for <strong>${result.value.date}</strong>.<br><br>The employee will be notified and can complete the assessment to improve their evaluation score.`,
                        icon: 'success',
                        confirmButtonColor: '#10B981'
                    });
                }
            });
        }
        
        function showTrainingPrograms(employeeId) {
            // Close the development modal first
            document.getElementById('developmentModal').style.display = 'none';
            
            alert(`Training Programs for Employee ${employeeId}\n\nAvailable Programs:\n• Leadership Development Course\n• Technical Skills Enhancement\n• Communication & Interpersonal Skills\n• Project Management Certification\n• Compliance & Safety Training\n\nNext: This would redirect to training enrollment page.`);
        }
        
        function showMentoringOptions(employeeId) {
            // Close the development modal first
            document.getElementById('developmentModal').style.display = 'none';
            
            alert(`Mentoring Program for Employee ${employeeId}\n\nProgram Features:\n• Pairing with senior leadership\n• Monthly mentoring sessions\n• Career development planning\n• Goal setting and tracking\n• Knowledge transfer sessions\n\nNext: This would show available mentors and enrollment options.`);
        }
        
        function showProjectAssignments(employeeId) {
            // Close the development modal first
            document.getElementById('developmentModal').style.display = 'none';
            
            alert(`Special Projects for Employee ${employeeId}\n\nAvailable Assignments:\n• Cross-functional team leadership\n• Process improvement initiatives\n• New technology implementation\n• Client relationship management\n• Training program development\n\nNext: This would show project assignment interface.`);
        }

        // Add employee to pipeline
        function addToPipeline() {
            if (window.currentEmployee) {
                if (confirm(`Add ${window.currentEmployee.name} to the succession pipeline? This will mark them as ready for leadership development.`)) {
                    // In real implementation, this would make an API call to update the employee's pipeline status
                    alert(`${window.currentEmployee.name} has been added to the succession pipeline!\n\nNext Steps:\n• Assigned to leadership development track\n• Scheduled for advanced training\n• Regular progress reviews\n• Mentorship program enrollment`);
                    closePipelineModal();
                    // Refresh the page to show updated status
                    location.reload();
                }
            }
        }

        // View detailed succession profile
        function viewSuccessionProfile(employeeId) {
            // In real implementation, this would open a detailed modal or redirect to profile page
            alert(`Detailed succession profile for Employee ${employeeId} will show:\n\n• Complete assessment history\n• Competency analysis\n• Leadership potential matrix\n• Development recommendations\n• Succession timeline\n• Risk assessment details`);
        }

        // Add loading states to forms
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="bx bx-loader-alt animate-spin"></i> Processing...';
                    }
                });
            });
        });

        // Add CSS animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .animate-spin {
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        // ========== Job API Functions ==========
        let jobsData = [];

        async function fetchJobs() {
            try {
                const response = await fetch('/api/jobs'); // Adjust API endpoint as needed
                const result = await response.json();
                
                if (result.status === 'success' && result.data) {
                    jobsData = result.data;
                    populateAllJobSelects();
                } else {
                    showJobLoadError('Failed to load jobs');
                }
            } catch (error) {
                console.error('Error fetching jobs:', error);
                showJobLoadError('Unable to connect to job service');
            }
        }

        function showJobLoadError(message) {
            document.querySelectorAll('.job-select').forEach(select => {
                select.innerHTML = `<option value="">⚠️ ${message}</option>`;
                select.disabled = true;
            });
        }

        function populateAllJobSelects() {
            document.querySelectorAll('.job-select').forEach(select => {
                populateJobSelect(select);
            });
        }

        function populateJobSelect(selectElement) {
            const container = selectElement.closest('.succession-target-container');
            const employeeId = container?.dataset.employeeId;
            
            // Check if this employee has an AI recommendation
            const aiRec = aiRecommendations[employeeId];
            const aiRecommendedJob = aiRec?.potential_job || null;
            
            // Group jobs by department
            const groupedJobs = {};
            
            jobsData.forEach(item => {
                const dept = item.department || 'Other';
                if (!groupedJobs[dept]) {
                    groupedJobs[dept] = [];
                }
                groupedJobs[dept].push(item);
            });

            let html = '<option value="">Select Target Role</option>';
            
            // AI Recommendation section
            if (aiRecommendedJob) {
                html += '<optgroup label="🤖 AI Recommended">';
                html += `<option value="${escapeHtml(aiRecommendedJob)}" class="ai-recommended-option" selected>⭐ ${escapeHtml(aiRecommendedJob)}</option>`;
                html += '</optgroup>';
            }

            // Group by department
            Object.keys(groupedJobs).sort().forEach(dept => {
                html += `<optgroup label="${escapeHtml(dept)}">`;
                
                groupedJobs[dept].forEach(item => {
                    const jobTitle = item.job?.job_title || 'Unknown Position';
                    const isVacant = item.status?.toLowerCase() === 'vacant';
                    const statusBadge = isVacant ? '🟢' : '🔴';
                    const disabled = !isVacant ? 'disabled' : '';
                    const isAiMatch = jobTitle === aiRecommendedJob;
                    
                    html += `<option value="${escapeHtml(jobTitle)}" 
                                data-job-id="${item.job_id}"
                                data-department="${escapeHtml(item.department || '')}"
                                data-location="${escapeHtml(item.location || '')}"
                                data-employment-type="${escapeHtml(item.employment_type || '')}"
                                data-status="${escapeHtml(item.status || '')}"
                                data-description="${escapeHtml(item.job?.job_description || 'No description available')}"
                                ${disabled}
                                ${isAiMatch ? 'class="ai-match"' : ''}>
                                ${statusBadge} ${escapeHtml(jobTitle)} ${!isVacant ? '(Filled)' : ''} ${isAiMatch ? '⭐' : ''}
                            </option>`;
                });
                
                html += '</optgroup>';
            });

            selectElement.innerHTML = html;
            selectElement.disabled = false;
            
            // Show AI recommendation details
            if (aiRecommendedJob && aiRec) {
                const aiDiv = container.querySelector('.ai-recommendation');
                const aiJobSpan = container.querySelector('.ai-recommended-job');
                
                if (aiDiv && aiJobSpan) {
                    aiJobSpan.textContent = aiRecommendedJob;
                    aiDiv.classList.remove('hidden');
                    
                    // Parse AI reasoning if available
                    try {
                        const recData = typeof aiRec.recommendations === 'string' 
                            ? JSON.parse(aiRec.recommendations) 
                            : aiRec.recommendations;
                        if (recData?.match_score) {
                            aiJobSpan.textContent = `${aiRecommendedJob} (${recData.match_score}% match)`;
                        }
                    } catch (e) {}
                }
                
                // Trigger details display for AI-selected option
                showJobDetails(selectElement);
            }
            
            // Add change listener to show details
            selectElement.addEventListener('change', function() {
                showJobDetails(this);
            });
        }

                function showJobDetails(selectElement) {
            const container = selectElement.closest('.succession-target-container');
            const detailsDiv = container.querySelector('.job-details');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            
            if (!selectedOption || !selectedOption.value) {
                detailsDiv.classList.add('hidden');
                return;
            }

            const dept = selectedOption.dataset.department;
            const location = selectedOption.dataset.location;
            const empType = selectedOption.dataset.employmentType;
            const status = selectedOption.dataset.status;
            const description = selectedOption.dataset.description;

            let html = `
                <div class="bg-gray-50 rounded-lg p-2 border border-gray-200 mt-2 text-xs">
                    <div class="space-y-1">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Dept:</span>
                            <span class="font-medium text-gray-700">${escapeHtml(dept)}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Location:</span>
                            <span class="font-medium text-gray-700">${escapeHtml(location)}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Type:</span>
                            <span class="font-medium text-gray-700">${escapeHtml(empType)}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status:</span>
                            <span class="font-medium ${status?.toLowerCase() === 'vacant' ? 'text-green-600' : 'text-red-600'}">${escapeHtml(status)}</span>
                        </div>
                    </div>
                    ${description && description !== 'No description available' ? `
                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <span class="text-gray-500 block mb-1">Description:</span>
                            <p class="text-gray-700 line-clamp-3">${escapeHtml(description)}</p>
                        </div>
                    ` : ''}
                </div>
            `;

            detailsDiv.innerHTML = html;
            detailsDiv.classList.remove('hidden');
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = String(text);
            return div.innerHTML;
        }

        // Set AI recommended job for an employee (placeholder for future AI integration)
        function setAiRecommendedJob(employeeId, recommendedJobTitle) {
            const container = document.querySelector(`.succession-target-container[data-employee-id="${employeeId}"]`);
            if (!container) return;

            const aiDiv = container.querySelector('.ai-recommendation');
            const aiJobSpan = container.querySelector('.ai-recommended-job');
            const select = container.querySelector('.job-select');

            if (recommendedJobTitle) {
                aiJobSpan.textContent = recommendedJobTitle;
                aiDiv.classList.remove('hidden');

                // Try to select the recommended job
                const options = select.querySelectorAll('option');
                options.forEach(opt => {
                    if (opt.value === recommendedJobTitle && !opt.disabled) {
                        opt.selected = true;
                        showJobDetails(select);
                    }
                });
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetchJobs();
        });

        // ─── ANALYTICS TOGGLE ───
        function toggleSuccessionAnalytics() {
            const content = document.getElementById('saContent');
            const btn = document.getElementById('saToggleBtn');
            if (content.style.display === 'none') {
                content.style.display = '';
                btn.innerHTML = '<i class="bx bx-chevron-up mr-1" id="saToggleIcon"></i>Collapse';
            } else {
                content.style.display = 'none';
                btn.innerHTML = '<i class="bx bx-chevron-down mr-1" id="saToggleIcon"></i>Expand';
            }
        }

        // ─── CHART.JS INITIALIZATION ───
        @if($totalAll > 0)
        (function() {
            const chartDefaults = { responsive: true, maintainAspectRatio: false };

            // 1. Readiness Distribution (Doughnut)
            new Chart(document.getElementById('saReadinessChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Ready Now', 'High Potential', 'Moderate', 'Developing'],
                    datasets: [{
                        data: [{{ $aReadyNow }}, {{ $aHighPotential }}, {{ $aModerate }}, {{ $aDeveloping }}],
                        backgroundColor: ['#059669', '#2563eb', '#f59e0b', '#9ca3af'],
                        borderWidth: 2, borderColor: '#ffffff', hoverOffset: 6
                    }]
                },
                options: {
                    ...chartDefaults, cutout: '62%',
                    plugins: { legend: { position: 'bottom', labels: { font: { size: 10, weight: '600' }, padding: 10, usePointStyle: true, pointStyleWidth: 8 } } }
                }
            });

            // 2. Score Distribution (Bar Histogram)
            const scoreRanges = @json($scoreRanges);
            new Chart(document.getElementById('saScoreHistChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(scoreRanges),
                    datasets: [{
                        label: 'Employees',
                        data: Object.values(scoreRanges),
                        backgroundColor: ['#059669', '#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                        borderRadius: 6, barThickness: 28
                    }]
                },
                options: {
                    ...chartDefaults,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { color: '#f3f4f6' } },
                        x: { ticks: { font: { size: 9, weight: '600' } }, grid: { display: false } }
                    }
                }
            });

            // 3. Risk Assessment (Horizontal Bar)
            new Chart(document.getElementById('saRiskChart'), {
                type: 'bar',
                data: {
                    labels: ['Low Risk', 'Medium Risk', 'High Risk'],
                    datasets: [{
                        data: [{{ $aLowRisk }}, {{ $aMedRisk }}, {{ $aHighRisk }}],
                        backgroundColor: ['#059669', '#f59e0b', '#ef4444'],
                        borderRadius: 6, barThickness: 28
                    }]
                },
                options: {
                    ...chartDefaults, indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { color: '#f3f4f6' } },
                        y: { ticks: { font: { size: 11, weight: '600' } }, grid: { display: false } }
                    }
                }
            });

            // 4. Eval Score vs Percentage (Scatter)
            const scatterData = @json($aScatterData);
            new Chart(document.getElementById('saScatterChart'), {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Employees',
                        data: scatterData,
                        backgroundColor: scatterData.map(d => d.expert ? '#059669' : '#f59e0b'),
                        borderColor: 'transparent',
                        pointRadius: 7, pointHoverRadius: 10
                    }]
                },
                options: {
                    ...chartDefaults,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => {
                                    const d = scatterData[ctx.dataIndex];
                                    return `${d.name}: ${d.x}/5.0 (${d.y}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { title: { display: true, text: 'Eval Score (0-5)', font: { size: 10 } }, min: 0, max: 5, ticks: { font: { size: 9 } }, grid: { color: '#f3f4f6' } },
                        y: { title: { display: true, text: 'Eval % (0-100)', font: { size: 10 } }, min: 0, max: 100, ticks: { font: { size: 9 } }, grid: { color: '#f3f4f6' } }
                    }
                }
            });

            // 5. Job Title Distribution (Horizontal Bar)
            const jobData = @json($jobDist);
            const jobLabels = Object.keys(jobData).sort((a, b) => jobData[b] - jobData[a]).slice(0, 8);
            const jobValues = jobLabels.map(k => jobData[k]);
            const jobColors = ['#6366f1', '#8b5cf6', '#a78bfa', '#3b82f6', '#2563eb', '#059669', '#f59e0b', '#ec4899'];
            new Chart(document.getElementById('saJobChart'), {
                type: 'bar',
                data: {
                    labels: jobLabels.map(l => l.length > 16 ? l.substring(0, 16) + '…' : l),
                    datasets: [{
                        data: jobValues,
                        backgroundColor: jobLabels.map((_, i) => jobColors[i % jobColors.length]),
                        borderRadius: 5, barThickness: 20
                    }]
                },
                options: {
                    ...chartDefaults, indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 9 } }, grid: { color: '#f3f4f6' } },
                        y: { ticks: { font: { size: 9, weight: '600' } }, grid: { display: false } }
                    }
                }
            });
        })();
        @endif
    </script>
</x-app-layout>