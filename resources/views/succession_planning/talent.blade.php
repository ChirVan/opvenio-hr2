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
            <h1 class="text-3xl font-bold text-gray-900">Talent Pool</h1>
            <p class="text-gray-600 mt-1">High-performing employees eligible for succession planning and career advancement.</p>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users text-success"></i> Qualified Talent Pool
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-success" style="background-color: #28a745; color: white; padding: 8px 12px; font-size: 14px;">
                                    {{ $processedTalentPool->count() }} Qualified Employees
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($processedTalentPool->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th style="background-color: #343a40; color: white;">Employee Name</th>
                                                <th style="background-color: #343a40; color: white;">Email</th>
                                                <th style="background-color: #343a40; color: white;">Assessment Completed</th>
                                                <th style="background-color: #343a40; color: white;">Category</th>
                                                <th style="background-color: #343a40; color: white;">Performance Score</th>
                                                <th style="background-color: #343a40; color: white;">Qualified Date</th>
                                                <th style="background-color: #343a40; color: white;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($processedTalentPool as $talent)
                                            <tr style="background-color: white;">
                                                <td style="font-weight: 600; color: #495057;">
                                                    <i class="fas fa-star text-warning"></i> {{ $talent->employee_name }}
                                                </td>
                                                <td style="color: #6c757d;">{{ $talent->employee_email }}</td>
                                                <td style="color: #495057;">{{ $talent->quiz_title }}</td>
                                                <td style="color: #495057;">
                                                    <span class="badge" style="background-color: #17a2b8; color: white; padding: 4px 8px; font-size: 11px;">
                                                        {{ $talent->category_name }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $score = $talent->average_score ?? 0;
                                                        $scoreColor = $score >= 4.5 ? '#28a745' : ($score >= 4.0 ? '#20c997' : ($score >= 3.5 ? '#17a2b8' : '#ffc107'));
                                                        $scoreLabel = $score >= 4.5 ? 'Exceptional' : ($score >= 4.0 ? 'High' : ($score >= 3.5 ? 'Good' : 'Average'));
                                                    @endphp
                                                    <span class="badge" style="background-color: {{ $scoreColor }}; color: white; padding: 6px 12px; font-size: 12px; font-weight: bold;">
                                                        {{ number_format($score, 1) }}/5.0 - {{ $scoreLabel }}
                                                    </span>
                                                </td>
                                                <td style="color: #495057;">
                                                    {{ $talent->evaluated_at ? \Carbon\Carbon::parse($talent->evaluated_at)->format('M d, Y') : 'N/A' }}
                                                </td>
                                                <td>
                                                    @if(in_array($talent->employee_id, $promotedEmployeeIds))
                                                        <button class="btn btn-sm btn-secondary" disabled style="padding: 6px 12px; border-radius: 4px;">
                                                            <i class="fas fa-user-check"></i> Already Sent
                                                        </button>
                                                    @else
                                                        <a href="{{ route('succession.potential', $talent->employee_id) }}" class="btn btn-sm" style="background-color: #007bff; color: white; padding: 6px 12px; border-radius: 4px;">
                                                            <i class="fas fa-user-circle"></i> Send to Promotion
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Summary Cards -->
                                <div class="row mt-4">
                                    <div class="col-md-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ $processedTalentPool->count() }}</h4>
                                                <p class="mb-0">Total Talent</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ $processedTalentPool->where('average_score', '>=', 4.5)->count() }}</h4>
                                                <p class="mb-0">Exceptional Performers</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-info text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ $processedTalentPool->unique('category_name')->count() }}</h4>
                                                <p class="mb-0">Skill Categories</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ number_format($processedTalentPool->avg('average_score'), 1) }}</h4>
                                                <p class="mb-0">Avg Performance</p>
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