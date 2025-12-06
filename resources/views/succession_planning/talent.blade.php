<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Succession Planning - Talent Pool</h1>
            <p class="text-gray-600 mt-1">Comprehensive talent assessment for strategic succession planning and leadership development.</p>
        </div>

        <div class="container-fluid">
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
                                                    @if(in_array($talent->employee_id, $promotedEmployeeIds))
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

                                <!-- Strategic Metrics Dashboard -->
                                <div class="row mt-4">
                                    <div class="col-md-2">
                                        <div class="card text-center" style="border-left: 4px solid #007bff;">
                                            <div class="card-body py-3">
                                                <h4 class="text-primary mb-1">{{ $processedTalentPool->count() }}</h4>
                                                <small class="text-muted">Total Candidates</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="card text-center" style="border-left: 4px solid #28a745;">
                                            <div class="card-body py-3">
                                                <h4 class="text-success mb-1">{{ $processedTalentPool->where('average_score', '>=', 4.5)->count() }}</h4>
                                                <small class="text-muted">Ready Now</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="card text-center" style="border-left: 4px solid #20c997;">
                                            <div class="card-body py-3">
                                                <h4 class="text-info mb-1">{{ $processedTalentPool->where('average_score', '>=', 4.0)->where('average_score', '<', 4.5)->count() }}</h4>
                                                <small class="text-muted">High Potential</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="card text-center" style="border-left: 4px solid #ffc107;">
                                            <div class="card-body py-3">
                                                <h4 class="text-warning mb-1">{{ $processedTalentPool->where('average_score', '>=', 3.0)->where('average_score', '<', 4.0)->count() }}</h4>
                                                <small class="text-muted">Developing</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="card text-center" style="border-left: 4px solid #17a2b8;">
                                            <div class="card-body py-3">
                                                <h4 class="text-info mb-1">{{ $processedTalentPool->unique('category_name')->count() }}</h4>
                                                <small class="text-muted">Skill Areas</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="card text-center" style="border-left: 4px solid #6c757d;">
                                            <div class="card-body py-3">
                                                <h4 class="text-secondary mb-1">{{ number_format($processedTalentPool->avg('average_score'), 1) }}</h4>
                                                <small class="text-muted">Avg Score</small>
                                            </div>
                                        </div>
                                    </div>
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
    </script>
</x-app-layout>