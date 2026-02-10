<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    @php
        // ── Pre-compute analytics data in PHP for charts ──
        $totalCandidates = $processedTalentPool->count();

        // Readiness distribution
        $readyNowCount = 0; $highPotentialCount = 0; $moderateCount = 0; $emergingCount = 0; $developingCount = 0;
        // Risk distribution
        $lowRiskCount = 0; $medRiskCount = 0; $highRiskCount = 0;
        // Score arrays for scatter / distribution
        $scatterData = [];
        // Category breakdown
        $categoryScores = [];
        // Competency rating distribution
        $compDist = ['exceptional' => 0, 'highly_effective' => 0, 'proficient' => 0, 'inconsistent' => 0, 'unsatisfactory' => 0];
        // Top performers
        $topPerformers = $processedTalentPool->sortByDesc('succession_readiness')->take(5);
        // Pipeline status
        $inPipelineCount = 0; $actionableCount = 0;

        foreach ($processedTalentPool as $t) {
            $cs = $t->average_score ?? 0;
            $ps = min(($t->score ?? 0) / 20, 5);
            $sr = ($cs * 0.7) + ($ps * 0.3);

            // Readiness
            if ($sr >= 4.5) $readyNowCount++;
            elseif ($sr >= 4.0) $highPotentialCount++;
            elseif ($sr >= 3.5) $moderateCount++;
            elseif ($sr >= 3.0) $emergingCount++;
            else $developingCount++;

            // Risk
            if ($sr < 3.0) $highRiskCount++;
            elseif ($sr < 3.5) $medRiskCount++;
            else $lowRiskCount++;

            // Scatter
            $scatterData[] = ['x' => round($cs, 2), 'y' => round($ps, 2), 'name' => $t->employee_name];

            // Category
            $cat = $t->category_name ?? 'General';
            if (!isset($categoryScores[$cat])) $categoryScores[$cat] = ['total' => 0, 'count' => 0];
            $categoryScores[$cat]['total'] += $sr;
            $categoryScores[$cat]['count']++;

            // Competency distribution
            if ($t->evaluation_data) {
                $comps = $t->evaluation_data['competencies'] ?? [];
                if (empty($comps)) {
                    for ($i = 1; $i <= 5; $i++) {
                        $k = "competency_{$i}";
                        if (isset($t->evaluation_data[$k])) $comps[$k] = $t->evaluation_data[$k];
                    }
                }
                foreach ($comps as $rating) {
                    $r = strtolower($rating);
                    if (isset($compDist[$r])) $compDist[$r]++;
                }
            }

            // Pipeline
            if ($t->status === 'approved' || in_array($t->employee_id, $promotedEmployeeIds)) $inPipelineCount++;
            else $actionableCount++;
        }

        $avgReadiness = $totalCandidates > 0 ? round($processedTalentPool->avg('succession_readiness'), 2) : 0;
        $benchStrength = $totalCandidates > 0 ? round((($readyNowCount + $highPotentialCount) / $totalCandidates) * 100, 1) : 0;
    @endphp

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <div class="py-3">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Succession Planning - Talent Pool</h1>
            <p class="text-gray-600 mt-1">Comprehensive talent assessment for strategic succession planning and leadership development.</p>
        </div>

        <div class="container-fluid">

            {{-- ═══════════════════════════════════════════════════════════════
                 ANALYTICS DASHBOARD
                 ═══════════════════════════════════════════════════════════════ --}}
            @if($totalCandidates > 0)
            <div id="analyticsSection" class="mb-4">
                <!-- Toggle Button -->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0" style="font-weight: 700; color: #1f2937;">
                        <i class="fas fa-chart-pie text-primary me-2"></i>Talent Analytics Dashboard
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary" onclick="toggleAnalytics()" id="analyticsToggleBtn" style="border-radius: 8px; font-size: 12px;">
                        <i class="fas fa-chevron-up me-1" id="analyticsToggleIcon"></i> Collapse
                    </button>
                </div>

                <div id="analyticsContent">
                    <!-- ── KPI Summary Cards ── -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3 col-xl">
                            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px;">
                                <div class="card-body text-white py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div style="font-size: 11px; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px;">Total Talent</div>
                                            <div style="font-size: 1.6rem; font-weight: 800; line-height: 1.2;">{{ $totalCandidates }}</div>
                                        </div>
                                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-users" style="font-size: 1.1rem;"></i>
                                        </div>
                                    </div>
                                    <div style="font-size: 10px; opacity: 0.75; margin-top: 4px;">{{ $inPipelineCount }} in pipeline</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-xl">
                            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 12px;">
                                <div class="card-body text-white py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div style="font-size: 11px; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px;">Ready Now</div>
                                            <div style="font-size: 1.6rem; font-weight: 800; line-height: 1.2;">{{ $readyNowCount }}</div>
                                        </div>
                                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-rocket" style="font-size: 1.1rem;"></i>
                                        </div>
                                    </div>
                                    <div style="font-size: 10px; opacity: 0.75; margin-top: 4px;">Score ≥ 4.5</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-xl">
                            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px;">
                                <div class="card-body text-white py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div style="font-size: 11px; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px;">Bench Strength</div>
                                            <div style="font-size: 1.6rem; font-weight: 800; line-height: 1.2;">{{ $benchStrength }}%</div>
                                        </div>
                                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-dumbbell" style="font-size: 1.1rem;"></i>
                                        </div>
                                    </div>
                                    <div style="font-size: 10px; opacity: 0.75; margin-top: 4px;">Ready + High Potential</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-xl">
                            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 12px;">
                                <div class="card-body text-white py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div style="font-size: 11px; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px;">Avg Readiness</div>
                                            <div style="font-size: 1.6rem; font-weight: 800; line-height: 1.2;">{{ number_format($avgReadiness, 1) }}</div>
                                        </div>
                                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-tachometer-alt" style="font-size: 1.1rem;"></i>
                                        </div>
                                    </div>
                                    <div style="font-size: 10px; opacity: 0.75; margin-top: 4px;">out of 5.0 scale</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-xl">
                            <div class="card border-0 h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px;">
                                <div class="card-body text-white py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div style="font-size: 11px; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px;">High Risk</div>
                                            <div style="font-size: 1.6rem; font-weight: 800; line-height: 1.2;">{{ $highRiskCount }}</div>
                                        </div>
                                        <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-exclamation-triangle" style="font-size: 1.1rem;"></i>
                                        </div>
                                    </div>
                                    <div style="font-size: 10px; opacity: 0.75; margin-top: 4px;">Need development</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── Charts Row 1: Readiness Distribution + Risk Pie + Performance vs Competency ── -->
                    <div class="row g-3 mb-4">
                        <!-- Readiness Distribution (Doughnut) -->
                        <div class="col-md-4">
                            <div class="card border-0 h-100" style="border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
                                <div class="card-body">
                                    <h6 style="font-weight: 700; font-size: 13px; color: #374151; margin-bottom: 16px;">
                                        <i class="fas fa-chart-pie text-primary me-1"></i> Readiness Distribution
                                    </h6>
                                    <div style="position: relative; height: 220px;">
                                        <canvas id="readinessChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Risk Assessment (Horizontal Bar) -->
                        <div class="col-md-4">
                            <div class="card border-0 h-100" style="border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
                                <div class="card-body">
                                    <h6 style="font-weight: 700; font-size: 13px; color: #374151; margin-bottom: 16px;">
                                        <i class="fas fa-shield-alt text-danger me-1"></i> Risk Assessment
                                    </h6>
                                    <div style="position: relative; height: 220px;">
                                        <canvas id="riskChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Performance vs Competency (Scatter) -->
                        <div class="col-md-4">
                            <div class="card border-0 h-100" style="border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
                                <div class="card-body">
                                    <h6 style="font-weight: 700; font-size: 13px; color: #374151; margin-bottom: 16px;">
                                        <i class="fas fa-braille text-info me-1"></i> Performance vs Competency
                                    </h6>
                                    <div style="position: relative; height: 220px;">
                                        <canvas id="scatterChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── Charts Row 2: Category Avg + Competency Ratings + Top Performers ── -->
                    <div class="row g-3 mb-4">
                        <!-- Category Average Scores (Bar) -->
                        <div class="col-md-4">
                            <div class="card border-0 h-100" style="border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
                                <div class="card-body">
                                    <h6 style="font-weight: 700; font-size: 13px; color: #374151; margin-bottom: 16px;">
                                        <i class="fas fa-tags text-success me-1"></i> Avg Score by Category
                                    </h6>
                                    <div style="position: relative; height: 220px;">
                                        <canvas id="categoryChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Competency Rating Distribution (Polar Area) -->
                        <div class="col-md-4">
                            <div class="card border-0 h-100" style="border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
                                <div class="card-body">
                                    <h6 style="font-weight: 700; font-size: 13px; color: #374151; margin-bottom: 16px;">
                                        <i class="fas fa-star-half-alt text-warning me-1"></i> Competency Ratings
                                    </h6>
                                    <div style="position: relative; height: 220px;">
                                        <canvas id="competencyRatingChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Performers List -->
                        <div class="col-md-4">
                            <div class="card border-0 h-100" style="border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
                                <div class="card-body">
                                    <h6 style="font-weight: 700; font-size: 13px; color: #374151; margin-bottom: 16px;">
                                        <i class="fas fa-trophy text-warning me-1"></i> Top 5 Performers
                                    </h6>
                                    <div class="top-performers-list">
                                        @foreach($topPerformers as $idx => $tp)
                                        @php
                                            $tpSr = ($tp->average_score * 0.7) + (min(($tp->score ?? 0)/20, 5) * 0.3);
                                            $rankColors = ['#FFD700', '#C0C0C0', '#CD7F32', '#4facfe', '#a78bfa'];
                                        @endphp
                                        <div class="d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: #f3f4f6 !important;">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle me-2" 
                                                 style="width: 28px; height: 28px; background: {{ $rankColors[$idx] ?? '#6b7280' }}; color: white; font-size: 11px; font-weight: 800; flex-shrink: 0;">
                                                {{ $idx + 1 }}
                                            </div>
                                            <div class="flex-grow-1" style="min-width: 0;">
                                                <div style="font-weight: 600; font-size: 12px; color: #374151; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $tp->employee_name }}
                                                </div>
                                                <div style="font-size: 10px; color: #9ca3af;">{{ $tp->category_name }}</div>
                                            </div>
                                            <div class="text-end ms-2" style="flex-shrink: 0;">
                                                <span class="badge" style="background: {{ $tpSr >= 4.5 ? '#ecfdf5' : ($tpSr >= 4.0 ? '#eff6ff' : '#fffbeb') }}; color: {{ $tpSr >= 4.5 ? '#059669' : ($tpSr >= 4.0 ? '#2563eb' : '#d97706') }}; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px;">
                                                    {{ number_format($tpSr, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        @endforeach
                                        @if($topPerformers->isEmpty())
                                            <div class="text-center text-muted py-4" style="font-size: 12px;">No data available</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── Pipeline Health Bar ── -->
                    <div class="card border-0 mb-3" style="border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
                        <div class="card-body py-3">
                            <h6 style="font-weight: 700; font-size: 13px; color: #374151; margin-bottom: 12px;">
                                <i class="fas fa-stream text-primary me-1"></i> Succession Pipeline Health
                            </h6>
                            <div class="d-flex align-items-center mb-2">
                                <div class="progress flex-grow-1" style="height: 24px; border-radius: 12px; background: #f3f4f6; overflow: hidden;">
                                    @if($totalCandidates > 0)
                                    <div class="progress-bar" style="width: {{ ($readyNowCount/$totalCandidates)*100 }}%; background: #059669; font-size: 10px; font-weight: 700;" title="Ready Now">
                                        @if(($readyNowCount/$totalCandidates)*100 > 8) Ready @endif
                                    </div>
                                    <div class="progress-bar" style="width: {{ ($highPotentialCount/$totalCandidates)*100 }}%; background: #10b981; font-size: 10px; font-weight: 700;" title="High Potential">
                                        @if(($highPotentialCount/$totalCandidates)*100 > 8) High @endif
                                    </div>
                                    <div class="progress-bar" style="width: {{ ($moderateCount/$totalCandidates)*100 }}%; background: #3b82f6; font-size: 10px; font-weight: 700;" title="Moderate">
                                        @if(($moderateCount/$totalCandidates)*100 > 8) Mod @endif
                                    </div>
                                    <div class="progress-bar" style="width: {{ ($emergingCount/$totalCandidates)*100 }}%; background: #f59e0b; font-size: 10px; font-weight: 700;" title="Emerging">
                                        @if(($emergingCount/$totalCandidates)*100 > 8) Emrg @endif
                                    </div>
                                    <div class="progress-bar" style="width: {{ ($developingCount/$totalCandidates)*100 }}%; background: #9ca3af; font-size: 10px; font-weight: 700;" title="Developing">
                                        @if(($developingCount/$totalCandidates)*100 > 8) Dev @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-3" style="font-size: 11px;">
                                <span><span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background: #059669;"></span>Ready Now ({{ $readyNowCount }})</span>
                                <span><span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background: #10b981;"></span>High Potential ({{ $highPotentialCount }})</span>
                                <span><span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background: #3b82f6;"></span>Moderate ({{ $moderateCount }})</span>
                                <span><span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background: #f59e0b;"></span>Emerging ({{ $emergingCount }})</span>
                                <span><span class="d-inline-block rounded-circle me-1" style="width: 8px; height: 8px; background: #9ca3af;"></span>Developing ({{ $developingCount }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════════════
                 TALENT TABLE (existing)
                 ═══════════════════════════════════════════════════════════════ --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line text-primary"></i> Strategic Talent Assessment
                            </h3>
                            <div class="card-tools">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary active" onclick="filterTalent('all')">
                                        All Candidates ({{ $processedTalentPool->count() }})
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="filterTalent('high-potential')">
                                        High Potential ({{ $processedTalentPool->where('succession_readiness', '>=', 4.0)->count() }})
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="filterTalent('ready-now')">
                                        Ready Now ({{ $processedTalentPool->where('succession_readiness', '>=', 4.5)->count() }})
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($processedTalentPool->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="talentTable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th style="background-color: #343a40; color: white;">Employee Profile</th>
                                                <th style="background-color: #343a40; color: white;">Performance Metrics</th>
                                                <th style="background-color: #343a40; color: white;">Competency Score</th>
                                                <th style="background-color: #343a40; color: white;">Leadership Potential</th>
                                                <th style="background-color: #343a40; color: white;">Succession Readiness</th>
                                                <th style="background-color: #343a40; color: white;">Risk Level</th>
                                                <th style="background-color: #343a40; color: white;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($processedTalentPool as $talent)
                                            @php
                                                // Calculate comprehensive metrics
                                                $competencyScore = $talent->average_score ?? 0;
                                                $performanceScore = min(($talent->score ?? 0) / 20, 5); // Convert to 5-point scale
                                                $leadershipPotential = ($competencyScore * 0.7) + ($performanceScore * 0.3);
                                                $successionReadiness = $leadershipPotential;
                                                
                                                // Determine risk level based on multiple factors
                                                $riskLevel = 'Low';
                                                $riskColor = '#28a745';
                                                if ($successionReadiness < 3.0) {
                                                    $riskLevel = 'High';
                                                    $riskColor = '#dc3545';
                                                } elseif ($successionReadiness < 3.5) {
                                                    $riskLevel = 'Medium';
                                                    $riskColor = '#ffc107';
                                                }
                                                
                                                // Succession readiness categories
                                                $readinessLabel = 'Developing';
                                                $readinessColor = '#6c757d';
                                                if ($successionReadiness >= 4.5) {
                                                    $readinessLabel = 'Ready Now';
                                                    $readinessColor = '#28a745';
                                                } elseif ($successionReadiness >= 4.0) {
                                                    $readinessLabel = 'High Potential';
                                                    $readinessColor = '#20c997';
                                                } elseif ($successionReadiness >= 3.5) {
                                                    $readinessLabel = 'Moderate Potential';
                                                    $readinessColor = '#17a2b8';
                                                } elseif ($successionReadiness >= 3.0) {
                                                    $readinessLabel = 'Emerging Talent';
                                                    $readinessColor = '#ffc107';
                                                }
                                            @endphp
                                            <tr style="background-color: white;" data-readiness="{{ $successionReadiness }}" data-category="{{ strtolower(str_replace(' ', '-', $readinessLabel)) }}">
                                                <!-- Employee Profile -->
                                                <td style="padding: 12px;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="employee-avatar mr-3">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                                 style="width: 45px; height: 45px; background-color: #007bff; color: white; font-weight: bold;">
                                                                {{ strtoupper(substr($talent->employee_name, 0, 2)) }}
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div style="font-weight: 600; color: #495057; font-size: 14px;">
                                                                {{ $talent->employee_name }}
                                                            </div>
                                                            <div style="color: #6c757d; font-size: 12px;">
                                                                {{ $talent->employee_email }}
                                                            </div>
                                                            <div style="color: #6c757d; font-size: 11px;">
                                                                ID: {{ $talent->employee_id }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Performance Metrics -->
                                                <td style="padding: 12px;">
                                                    <div class="mb-1">
                                                        <small><strong>Assessment Score:</strong></small>
                                                        <div class="progress" style="height: 8px;">
                                                            <div class="progress-bar bg-primary" style="width: {{ ($talent->score ?? 0) }}%"></div>
                                                        </div>
                                                        <small class="text-muted">{{ $talent->score ?? 0 }}%</small>
                                                    </div>
                                                    <div class="mb-1">
                                                        <small><strong>Category:</strong></small>
                                                        <span class="badge" style="background-color: #17a2b8; color: white; font-size: 10px; padding: 2px 6px;">
                                                            {{ $talent->category_name }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted">Evaluated: {{ $talent->evaluated_at ? \Carbon\Carbon::parse($talent->evaluated_at)->format('M d, Y') : 'N/A' }}</small>
                                                    </div>
                                                </td>

                                                <!-- Competency Score -->
                                                <td style="padding: 12px; text-align: center;">
                                                    <div class="competency-circle" style="position: relative; width: 60px; height: 60px; margin: 0 auto;">
                                                        <svg width="60" height="60" style="transform: rotate(-90deg);">
                                                            <circle cx="30" cy="30" r="25" fill="none" stroke="#e9ecef" stroke-width="4"/>
                                                            <circle cx="30" cy="30" r="25" fill="none" stroke="#007bff" stroke-width="4" 
                                                                    stroke-dasharray="{{ 2 * pi() * 25 }}" 
                                                                    stroke-dashoffset="{{ 2 * pi() * 25 * (1 - $competencyScore/5) }}"
                                                                    stroke-linecap="round"/>
                                                        </svg>
                                                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: bold; font-size: 12px;">
                                                            {{ number_format($competencyScore, 1) }}
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">out of 5.0</small>
                                                </td>

                                                <!-- Leadership Potential -->
                                                <td style="padding: 12px; text-align: center;">
                                                    @php
                                                        $potentialStars = floor($leadershipPotential);
                                                        $hasHalfStar = ($leadershipPotential - $potentialStars) >= 0.5;
                                                    @endphp
                                                    <div class="mb-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $potentialStars)
                                                                <i class="fas fa-star text-warning"></i>
                                                            @elseif($i == $potentialStars + 1 && $hasHalfStar)
                                                                <i class="fas fa-star-half-alt text-warning"></i>
                                                            @else
                                                                <i class="far fa-star text-muted"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <small class="text-muted">{{ number_format($leadershipPotential, 1) }}/5.0</small>
                                                </td>

                                                <!-- Succession Readiness -->
                                                <td style="padding: 12px; text-align: center;">
                                                    <span class="badge" style="background-color: {{ $readinessColor }}; color: white; padding: 8px 12px; font-size: 11px; font-weight: bold;">
                                                        {{ $readinessLabel }}
                                                    </span>
                                                    <div class="mt-1">
                                                        <small class="text-muted">Score: {{ number_format($successionReadiness, 1) }}</small>
                                                    </div>
                                                </td>

                                                <!-- Risk Level -->
                                                <td style="padding: 12px; text-align: center;">
                                                    <span class="badge" style="background-color: {{ $riskColor }}; color: {{ $riskLevel == 'Medium' ? '#212529' : 'white' }}; padding: 6px 10px; font-size: 10px;">
                                                        {{ $riskLevel }} Risk
                                                    </span>
                                                </td>

                                                <!-- Action -->
                                                <td style="padding: 12px;">
                                                    @if($talent->status === 'approved' || in_array($talent->employee_id, $promotedEmployeeIds))
                                                        <button class="btn btn-sm btn-secondary" disabled style="padding: 6px 12px; border-radius: 4px; font-size: 11px;">
                                                            <i class="fas fa-user-check"></i> In Pipeline
                                                        </button>
                                                    @else
                                                        <div class="btn-group-vertical" style="width: 100%;">
                                                            <button class="btn btn-sm btn-outline-primary mb-1" onclick="viewTalentProfile({{ json_encode($talent) }})" style="font-size: 10px; padding: 4px 8px;">
                                                                <i class="fas fa-eye"></i> Profile
                                                            </button>
                                                            @if($successionReadiness >= 3.5)
                                                                <a href="{{ route('succession.potential', $talent->employee_id) }}" class="btn btn-sm btn-success" style="font-size: 10px; padding: 4px 8px;">
                                                                    <i class="fas fa-arrow-up"></i> Promote
                                                                </a>
                                                            @else
                                                                <button class="btn btn-sm btn-warning" onclick="createDevelopmentPlan('{{ $talent->employee_id }}')" style="font-size: 10px; padding: 4px 8px;">
                                                                    <i class="fas fa-chart-line"></i> Develop
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Qualified Talent Yet</h5>
                                    <p class="text-muted">Employees who pass their assessments will appear in the talent pool.</p>
                                    <a href="{{ route('assessment.results') }}" class="btn btn-primary">
                                        <i class="fas fa-clipboard-check"></i> Review Pending Assessments
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Talent Profile -->
    <div class="modal fade" id="talentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-circle text-primary"></i> Talent Profile
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="talentProfileContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form id="promotionForm" method="POST" action="" style="display: inline;">
                        @csrf
                        <input type="hidden" name="employee_id" id="promotionEmployeeId">
                        <input type="hidden" name="employee_name" id="promotionEmployeeName">
                        <input type="hidden" name="employee_email" id="promotionEmployeeEmail">
                        <input type="hidden" name="assessment_score" id="promotionScore">
                        <input type="hidden" name="category" id="promotionCategory">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-arrow-up"></i> Send to Promotion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Fix modal z-index issue -->
    <style>
        .modal {
            z-index: 1055 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }
        .modal-dialog {
            z-index: 1056 !important;
        }
    </style>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Filter talent based on succession readiness
        function filterTalent(category) {
            const rows = document.querySelectorAll('#talentTable tbody tr');
            const buttons = document.querySelectorAll('.btn-group .btn');
            
            // Update active button
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            rows.forEach(row => {
                const readiness = parseFloat(row.getAttribute('data-readiness'));
                let show = false;
                
                switch(category) {
                    case 'all':
                        show = true;
                        break;
                    case 'high-potential':
                        show = readiness >= 4.0;
                        break;
                    case 'ready-now':
                        show = readiness >= 4.5;
                        break;
                }
                
                row.style.display = show ? '' : 'none';
            });
        }

        // Create development plan for emerging talent
        function createDevelopmentPlan(employeeId) {
            if (confirm('Create a development plan for this employee? This will identify skill gaps and recommend training programs.')) {
                // In a real implementation, this would redirect to a development planning module
                alert('Development planning feature will be implemented to create personalized growth paths.');
            }
        }

        function viewTalentProfile(talent) {
            const competencyLabels = {
                'competency_1': 'Skill and proficiency in carrying out assignment',
                'competency_2': 'Possesses skills and knowledge to perform job effectively',
                'competency_3': 'Skill at planning, organizing and prioritizing workload',
                'competency_4': 'Holds self accountable for assigned responsibilities',
                'competency_5': 'Proficiency at improving work methods and procedures'
            };

            const ratingColors = {
                'exceptional': '#28a745',
                'highly_effective': '#20c997',
                'proficient': '#17a2b8',
                'inconsistent': '#ffc107',
                'unsatisfactory': '#dc3545'
            };

            let html = `
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-user text-primary"></i> <strong>Employee Information</strong></h6>
                        <table class="table table-sm">
                            <tr><td><strong>Name:</strong></td><td>${talent.employee_name}</td></tr>
                            <tr><td><strong>Email:</strong></td><td>${talent.employee_email}</td></tr>
                            <tr><td><strong>Employee ID:</strong></td><td>${talent.employee_id}</td></tr>
                            <tr><td><strong>Current Job:</strong></td><td>${talent.current_job ?? 'N/A'}</td></tr>
                            <tr><td><strong>Potential Job:</strong></td><td>${talent.potential_job ?? 'N/A'}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-chart-line text-success"></i> <strong>Performance Summary</strong></h6>
                        <table class="table table-sm">
                            <tr><td><strong>Assessment:</strong></td><td>${talent.quiz_title}</td></tr>
                            <tr><td><strong>Category:</strong></td><td>${talent.category_name}</td></tr>
                            <tr><td><strong>Score:</strong></td><td><span class="badge badge-success">${talent.average_score}/5.0</span></td></tr>
                        </table>
                    </div>
                </div>
            `;

            if (talent.evaluation_data) {
                html += `<h6><i class="fas fa-tasks text-info"></i> <strong>Competency Ratings:</strong></h6><div class="row">`;
                
                Object.keys(competencyLabels).forEach(key => {
                    if (talent.evaluation_data[key]) {
                        const rating = talent.evaluation_data[key];
                        const color = ratingColors[rating] || '#6c757d';
                        
                        html += `
                            <div class="col-md-6 mb-2">
                                <small><strong>${competencyLabels[key]}</strong></small><br>
                                <span class="badge" style="background-color: ${color}; color: white; padding: 4px 8px;">
                                    ${rating.replace('_', ' ').toUpperCase()}
                                </span>
                            </div>
                        `;
                    }
                });
                
                html += `</div>`;

                if (talent.evaluation_data.strengths || talent.evaluation_data.recommendations) {
                    html += `<h6 class="mt-3"><i class="fas fa-comments text-warning"></i> <strong>Evaluation Comments:</strong></h6>`;
                    
                    if (talent.evaluation_data.strengths) {
                        html += `<div class="mb-2"><strong>Strengths:</strong><br><small class="text-muted">${talent.evaluation_data.strengths}</small></div>`;
                    }
                    
                    if (talent.evaluation_data.recommendations) {
                        html += `<div class="mb-2"><strong>Recommendations:</strong><br><small class="text-muted">${talent.evaluation_data.recommendations}</small></div>`;
                    }
                }
            }

            document.getElementById('talentProfileContent').innerHTML = html;
            
            // Populate hidden form fields for promotion
            document.getElementById('promotionEmployeeId').value = talent.employee_id;
            document.getElementById('promotionEmployeeName').value = talent.employee_name;
            document.getElementById('promotionEmployeeEmail').value = talent.employee_email;
            document.getElementById('promotionScore').value = talent.average_score;
            document.getElementById('promotionCategory').value = talent.category_name;
            
            // Set form action - UPDATE THIS ROUTE TO YOUR ACTUAL ROUTE
            document.getElementById('promotionForm').action = `/talent-pool/promote/${talent.employee_id}`;
            
            // Show modal with proper configuration
            const modalElement = document.getElementById('talentModal');
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: true,
                keyboard: true,
                focus: true
            });
            modal.show();
        }

        // Handle form submission with loading state
        document.getElementById('promotionForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        });

        // ─── ANALYTICS TOGGLE ───
        function toggleAnalytics() {
            const content = document.getElementById('analyticsContent');
            const icon = document.getElementById('analyticsToggleIcon');
            const btn = document.getElementById('analyticsToggleBtn');
            if (content.style.display === 'none') {
                content.style.display = '';
                icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                btn.innerHTML = '<i class="fas fa-chevron-up me-1" id="analyticsToggleIcon"></i> Collapse';
            } else {
                content.style.display = 'none';
                icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                btn.innerHTML = '<i class="fas fa-chevron-down me-1" id="analyticsToggleIcon"></i> Expand';
            }
        }

        // ─── CHART.JS INITIALISATION ───
        @if($totalCandidates > 0)
        document.addEventListener('DOMContentLoaded', function() {
            const brandColors = {
                green: '#059669', greenLight: '#10b981',
                blue: '#3b82f6', blueLight: '#60a5fa',
                amber: '#f59e0b', amberLight: '#fbbf24',
                red: '#ef4444', redLight: '#f87171',
                purple: '#8b5cf6', gray: '#9ca3af'
            };

            // ── 1. Readiness Distribution (Doughnut) ──
            new Chart(document.getElementById('readinessChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Ready Now', 'High Potential', 'Moderate', 'Emerging', 'Developing'],
                    datasets: [{
                        data: [{{ $readyNowCount }}, {{ $highPotentialCount }}, {{ $moderateCount }}, {{ $emergingCount }}, {{ $developingCount }}],
                        backgroundColor: ['#059669', '#10b981', '#3b82f6', '#f59e0b', '#9ca3af'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { size: 10, weight: '600' }, padding: 10, usePointStyle: true, pointStyleWidth: 8 } }
                    }
                }
            });

            // ── 2. Risk Assessment (Horizontal Bar) ──
            new Chart(document.getElementById('riskChart'), {
                type: 'bar',
                data: {
                    labels: ['Low Risk', 'Medium Risk', 'High Risk'],
                    datasets: [{
                        data: [{{ $lowRiskCount }}, {{ $medRiskCount }}, {{ $highRiskCount }}],
                        backgroundColor: ['#059669', '#f59e0b', '#ef4444'],
                        borderRadius: 6,
                        barThickness: 28,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { color: '#f3f4f6' } },
                        y: { ticks: { font: { size: 11, weight: '600' } }, grid: { display: false } }
                    }
                }
            });

            // ── 3. Performance vs Competency (Scatter) ──
            const scatterData = @json($scatterData);
            new Chart(document.getElementById('scatterChart'), {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Employees',
                        data: scatterData,
                        backgroundColor: scatterData.map(d => {
                            const sr = (d.x * 0.7) + (d.y * 0.3);
                            return sr >= 4.5 ? '#059669' : sr >= 4.0 ? '#10b981' : sr >= 3.5 ? '#3b82f6' : sr >= 3.0 ? '#f59e0b' : '#ef4444';
                        }),
                        borderColor: 'transparent',
                        pointRadius: 7,
                        pointHoverRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const d = scatterData[ctx.dataIndex];
                                    return `${d.name}: Comp ${d.x} | Perf ${d.y}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { title: { display: true, text: 'Competency (0-5)', font: { size: 10 } }, min: 0, max: 5, ticks: { font: { size: 9 } }, grid: { color: '#f3f4f6' } },
                        y: { title: { display: true, text: 'Performance (0-5)', font: { size: 10 } }, min: 0, max: 5, ticks: { font: { size: 9 } }, grid: { color: '#f3f4f6' } }
                    }
                }
            });

            // ── 4. Category Average Scores (Bar) ──
            const categoryData = @json($categoryScores);
            const catLabels = Object.keys(categoryData);
            const catValues = catLabels.map(k => (categoryData[k].total / categoryData[k].count).toFixed(2));
            const barColors = ['#3b82f6', '#8b5cf6', '#059669', '#f59e0b', '#ef4444', '#ec4899', '#14b8a6', '#f97316'];
            new Chart(document.getElementById('categoryChart'), {
                type: 'bar',
                data: {
                    labels: catLabels.map(l => l.length > 12 ? l.substring(0, 12) + '…' : l),
                    datasets: [{
                        label: 'Avg Readiness',
                        data: catValues,
                        backgroundColor: catLabels.map((_, i) => barColors[i % barColors.length]),
                        borderRadius: 6,
                        barThickness: 24,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, max: 5, ticks: { font: { size: 10 }, stepSize: 1 }, grid: { color: '#f3f4f6' } },
                        x: { ticks: { font: { size: 9 } }, grid: { display: false } }
                    }
                }
            });

            // ── 5. Competency Rating Distribution (Polar Area) ──
            const compData = @json($compDist);
            new Chart(document.getElementById('competencyRatingChart'), {
                type: 'polarArea',
                data: {
                    labels: ['Exceptional', 'Highly Effective', 'Proficient', 'Inconsistent', 'Unsatisfactory'],
                    datasets: [{
                        data: [compData.exceptional, compData.highly_effective, compData.proficient, compData.inconsistent, compData.unsatisfactory],
                        backgroundColor: ['rgba(5,150,105,0.6)', 'rgba(16,185,129,0.6)', 'rgba(59,130,246,0.6)', 'rgba(245,158,11,0.6)', 'rgba(239,68,68,0.6)'],
                        borderColor: ['#059669', '#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { size: 9, weight: '600' }, padding: 8, usePointStyle: true, pointStyleWidth: 8 } }
                    },
                    scales: {
                        r: { ticks: { font: { size: 9 }, stepSize: 1 }, grid: { color: '#e5e7eb' } }
                    }
                }
            });
        });
        @endif
    </script>
</x-app-layout>