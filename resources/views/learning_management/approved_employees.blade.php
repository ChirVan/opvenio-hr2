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
            <h1 class="text-3xl font-bold text-gray-900">Approved Employees Report</h1>
            <p class="text-gray-600 mt-1">View all employees who have successfully passed their assessments.</p>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Approved Employee Assessments</h3>
                            <div class="card-tools">
                                <a href="{{ route('assessment.results') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Assessment Results
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($approvedEmployees->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th style="background-color: #28a745; color: white;">Employee Name</th>
                                                <th style="background-color: #28a745; color: white;">Email</th>
                                                <th style="background-color: #28a745; color: white;">Assessment</th>
                                                <th style="background-color: #28a745; color: white;">Category</th>
                                                <th style="background-color: #28a745; color: white;">Approved Date</th>
                                                <th style="background-color: #28a745; color: white;">Average Score</th>
                                                <th style="background-color: #28a745; color: white;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($approvedEmployees as $employee)
                                            <tr style="background-color: white;">
                                                <td style="font-weight: 600; color: #495057;">{{ $employee['employee_name'] }}</td>
                                                <td style="color: #6c757d;">{{ $employee['employee_email'] }}</td>
                                                <td style="color: #495057;">{{ $employee['quiz_title'] }}</td>
                                                <td style="color: #495057;">{{ $employee['category_name'] }}</td>
                                                <td style="color: #495057;">
                                                    {{ $employee['evaluated_at'] ? \Carbon\Carbon::parse($employee['evaluated_at'])->format('M d, Y H:i') : 'N/A' }}
                                                </td>
                                                <td>
                                                    @if($employee['average_score'])
                                                        <span class="badge" style="background-color: #28a745; color: white; padding: 6px 12px; font-size: 12px; font-weight: bold;">
                                                            {{ number_format($employee['average_score'], 1) }}/5.0
                                                        </span>
                                                    @else
                                                        <span class="badge" style="background-color: #6c757d; color: white; padding: 6px 12px; font-size: 12px; font-weight: bold;">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm" style="background-color: #17a2b8; color: white; padding: 6px 12px; border-radius: 4px;" onclick="showEvaluationDetails({{ json_encode($employee) }})">
                                                        <i class="fas fa-eye"></i> View Details
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Summary Statistics -->
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ $approvedEmployees->count() }}</h4>
                                                <p class="mb-0">Total Approved</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-info text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ number_format($approvedEmployees->avg('average_score'), 1) }}</h4>
                                                <p class="mb-0">Average Rating</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body text-center">
                                                <h4>{{ $approvedEmployees->unique('category_name')->count() }}</h4>
                                                <p class="mb-0">Categories Covered</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Approved Employees Yet</h5>
                                    <p class="text-muted">Employees who pass their assessments will appear here.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Evaluation Details -->
    <div class="modal fade" id="evaluationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Employee Evaluation Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="evaluationDetails">
                    <!-- Content will be populated by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showEvaluationDetails(employee) {
            const competencyLabels = {
                'assignment_skills': 'Skill and proficiency in carrying out assignment',
                'job_knowledge': 'Possesses skills and knowledge to perform job effectively',
                'planning_organizing': 'Skill at planning, organizing and prioritizing workload',
                'accountability': 'Holds self accountable for assigned responsibilities',
                'efficiency_improvement': 'Proficiency at improving work methods and procedures'
            };

            const ratingColors = {
                'exceptional': '#28a745',
                'highly_effective': '#20c997',
                'proficient': '#17a2b8',
                'inconsistent': '#ffc107',
                'unsatisfactory': '#dc3545'
            };

            let html = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Employee:</strong> ${employee.employee_name}<br>
                        <strong>Email:</strong> ${employee.employee_email}
                    </div>
                    <div class="col-md-6">
                        <strong>Assessment:</strong> ${employee.quiz_title}<br>
                        <strong>Category:</strong> ${employee.category_name}
                    </div>
                </div>
                
                <h6>Competency Ratings:</h6>
                <div class="mb-3">
            `;

            Object.keys(employee.competency_ratings).forEach(key => {
                const rating = employee.competency_ratings[key];
                const label = competencyLabels[key];
                const color = ratingColors[rating] || '#6c757d';
                
                html += `
                    <div class="mb-2">
                        <small><strong>${label}:</strong></small><br>
                        <span class="badge" style="background-color: ${color}; color: white; padding: 4px 8px;">
                            ${rating ? rating.replace('_', ' ').toUpperCase() : 'Not Rated'}
                        </span>
                    </div>
                `;
            });

            html += `</div>`;

            if (employee.evaluation_comments.strengths || employee.evaluation_comments.areas_for_improvement || employee.evaluation_comments.recommendations) {
                html += `<h6>Evaluation Comments:</h6>`;
                
                if (employee.evaluation_comments.strengths) {
                    html += `<div class="mb-2"><strong>Strengths:</strong><br><small>${employee.evaluation_comments.strengths}</small></div>`;
                }
                
                if (employee.evaluation_comments.areas_for_improvement) {
                    html += `<div class="mb-2"><strong>Areas for Improvement:</strong><br><small>${employee.evaluation_comments.areas_for_improvement}</small></div>`;
                }
                
                if (employee.evaluation_comments.recommendations) {
                    html += `<div class="mb-2"><strong>Recommendations:</strong><br><small>${employee.evaluation_comments.recommendations}</small></div>`;
                }
            }

            document.getElementById('evaluationDetails').innerHTML = html;
            $('#evaluationModal').modal('show');
        }
    </script>
</x-app-layout>