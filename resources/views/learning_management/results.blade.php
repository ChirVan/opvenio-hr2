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
            <h1 class="text-3xl font-bold text-gray-900">Assessment Results</h1>
            <p class="text-gray-600 mt-1">Review and evaluate employee assessment submissions.</p>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Assessment Results</h3>
                        </div>
                        <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover border-black">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="background-color: #343a40; color: white;">Employee Name</th>
                                    <th style="background-color: #343a40; color: white;">Employee Email</th>
                                    <th style="background-color: #343a40; color: white;">Assignment Type</th>
                                    <th style="background-color: #343a40; color: white;">Total Assessments</th>
                                    <th style="background-color: #343a40; color: white;">Completed</th>
                                    <th style="background-color: #343a40; color: white;">Progress</th>
                                    <th style="background-color: #343a40; color: white;">Overall Status</th>
                                    <th style="background-color: #343a40; color: white;">Evaluation Result</th>
                                    <th style="background-color: #343a40; color: white;">Assessment Categories</th>
                                    <th style="background-color: #343a40; color: white;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $employee)
                                @php
                                    // Set row background color based on group type
                                    if ($employee->is_passed_group ?? false) {
                                        $rowBgColor = '#f0fff4'; // Light green for passed
                                    } elseif ($employee->is_failed_group ?? false) {
                                        $rowBgColor = '#fff5f5'; // Light red for failed
                                    } else {
                                        $rowBgColor = 'white'; // White for pending
                                    }
                                @endphp
                                <tr style="background-color: {{ $rowBgColor }};">
                                    <td style="font-weight: 600; color: #495057;">
                                        {{ $employee->employee_name }}
                                        @if($employee->is_passed_group ?? false)
                                            <br><span class="badge" style="background-color: #28a745; color: white; font-size: 10px; padding: 3px 8px; margin-top: 4px;">{{ $employee->group_type }}</span>
                                        @elseif($employee->is_failed_group ?? false)
                                            <br><span class="badge" style="background-color: #dc3545; color: white; font-size: 10px; padding: 3px 8px; margin-top: 4px;">{{ $employee->group_type }}</span>
                                        @else
                                            <br><span class="badge" style="background-color: #ffc107; color: #212529; font-size: 10px; padding: 3px 8px; margin-top: 4px;">{{ $employee->group_type }}</span>
                                        @endif
                                    </td>
                                    <td style="color: #6c757d;">{{ $employee->employee_email }}</td>
                                    <td class="text-center">
                                        @if($employee->assignment_type == 'Self Assessment')
                                            <span class="badge" style="background-color: #6f42c1; color: white; padding: 6px 12px; font-size: 11px; font-weight: bold;">
                                                <i class="fas fa-user"></i> Self Assessment
                                            </span>
                                        @elseif($employee->assignment_type == 'Skill Gap Requirement')
                                            <span class="badge" style="background-color: #fd7e14; color: white; padding: 6px 12px; font-size: 11px; font-weight: bold;">
                                                <i class="fas fa-chart-line"></i> Skill Gap Requirement
                                            </span>
                                            <br><small class="text-muted" style="font-size: 10px;">by {{ $employee->assigned_by_name }}</small>
                                        @else
                                            <span class="badge" style="background-color: #6c757d; color: white; padding: 6px 12px; font-size: 11px; font-weight: bold;">
                                                <i class="fas fa-question-circle"></i> Unknown
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge" style="background-color: #17a2b8; color: white; padding: 6px 12px; font-size: 12px; font-weight: bold;">{{ $employee->total_assessments }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge" style="background-color: #28a745; color: white; padding: 6px 12px; font-size: 12px; font-weight: bold;">{{ $employee->completed_assessments }}</span>
                                    </td>
                                    <td style="width: 150px;">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" 
                                                 style="width: {{ $employee->total_assessments > 0 ? ($employee->completed_assessments / $employee->total_assessments) * 100 : 0 }}%; background-color: #28a745;"
                                                 role="progressbar">
                                                {{ $employee->total_assessments > 0 ? round(($employee->completed_assessments / $employee->total_assessments) * 100, 1) : 0 }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($employee->overall_status == 'completed')
                                            <span class="badge" style="background-color: #ffc107; color: #212529; padding: 6px 12px; font-size: 12px; font-weight: bold;">All Completed</span>
                                        @elseif($employee->overall_status == 'partial')
                                            <span class="badge" style="background-color: #fd7e14; color: white; padding: 6px 12px; font-size: 12px; font-weight: bold;">Partially Completed</span>
                                        @else
                                            <span class="badge" style="background-color: #6c757d; color: white; padding: 6px 12px; font-size: 12px; font-weight: bold;">Not Started</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $evaluationStatuses = $employee->assessment_results->pluck('evaluation_status')->unique();
                                            $totalResults = $employee->assessment_results->count();
                                            $passedCount = $employee->assessment_results->where('evaluation_status', 'passed')->count();
                                            $failedCount = $employee->assessment_results->where('evaluation_status', 'failed')->count();
                                            $pendingCount = $employee->assessment_results->where('evaluation_status', 'pending')->count();
                                            $notEvaluatedCount = $employee->assessment_results->whereNull('evaluation_status')->count();
                                            
                                            // All must be evaluated to show final result
                                            $allEvaluated = ($passedCount + $failedCount) == $totalResults;
                                        @endphp
                                        
                                        @if($allEvaluated && $failedCount == 0 && $passedCount > 0)
                                            {{-- All evaluated and all passed --}}
                                            <span class="badge" style="background-color: #28a745; color: white; padding: 8px 16px; font-size: 13px; font-weight: bold;">
                                                <i class="fas fa-check-circle"></i> PASSED
                                            </span>
                                        @elseif($allEvaluated && $failedCount > 0)
                                            {{-- All evaluated but has failures --}}
                                            <span class="badge" style="background-color: #dc3545; color: white; padding: 8px 16px; font-size: 13px; font-weight: bold;">
                                                <i class="fas fa-times-circle"></i> FAILED
                                            </span>
                                        @else
                                            {{-- Not all evaluated yet - show pending --}}
                                            <span class="badge" style="background-color: #ffc107; color: #212529; padding: 8px 16px; font-size: 13px; font-weight: bold;">
                                                <i class="fas fa-clock"></i> PENDING
                                            </span>
                                            <br><small class="text-muted">{{ $passedCount + $failedCount }}/{{ $totalResults }} evaluated</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($employee->assessment_results->count() > 0)
                                            <div class="assessment-categories">
                                                @foreach($employee->assessment_results->groupBy('category_name') as $category => $categoryResults)
                                                    <div class="category-item mb-1">
                                                        <small class="text-muted">{{ $category }}:</small>
                                                        @foreach($categoryResults as $result)
                                                            @if($result->evaluation_status == 'passed')
                                                                <span class="badge" style="background-color: #28a745; color: white; font-size: 10px; padding: 4px 8px;">{{ $result->quiz_title }} (✓ Passed)</span>
                                                            @elseif($result->evaluation_status == 'failed')
                                                                <span class="badge" style="background-color: #dc3545; color: white; font-size: 10px; padding: 4px 8px;">{{ $result->quiz_title }} (✗ Rejected)</span>
                                                            @elseif($result->evaluation_status == 'pending' && $result->status == 'completed')
                                                                <span class="badge" style="background-color: #ffc107; color: #212529; font-size: 10px; padding: 4px 8px;">{{ $result->quiz_title }} (⏳ Pending)</span>
                                                            @elseif($result->status == 'completed')
                                                                <span class="badge" style="background-color: #17a2b8; color: white; font-size: 10px; padding: 4px 8px;">{{ $result->quiz_title }} (📝 Ready for Review)</span>
                                                            @else
                                                                <span class="badge" style="background-color: #6c757d; color: white; font-size: 10px; padding: 4px 8px;">{{ $result->quiz_title }} ({{ ucfirst(str_replace('_', ' ', $result->status)) }})</span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <small class="text-muted">No assessments taken</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            // Check if ALL assessments are completed (submitted by employee)
                                            $allCompleted = $employee->assessment_results->every(function($result) {
                                                return $result->status === 'completed' || $result->status === 'passed' || $result->status === 'failed';
                                            });
                                            
                                            $allEvaluated = $employee->assessment_results->every(function($result) {
                                                return $result->evaluation_status === 'passed' || $result->evaluation_status === 'failed';
                                            });
                                            
                                            // Get pending evaluation result IDs (completed but not yet evaluated)
                                            $pendingResultIds = $employee->assessment_results->filter(function($result) {
                                                return ($result->status === 'completed' || $result->status === 'passed' || $result->status === 'failed') 
                                                    && ($result->evaluation_status === 'pending' || $result->evaluation_status === null);
                                            })->pluck('id')->toArray();
                                            
                                            // Get all result IDs
                                            $resultIds = $employee->assessment_results->pluck('id')->toArray();
                                            
                                            // Check if any assessment failed evaluation
                                            $hasFailed = $employee->assessment_results->contains('evaluation_status', 'failed');
                                            
                                            // Check if any assessment is pending evaluation
                                            $hasPending = count($pendingResultIds) > 0;
                                            
                                            // Count how many are still in progress (not submitted)
                                            $inProgressCount = $employee->assessment_results->filter(function($result) {
                                                return $result->status === 'in_progress' || $result->status === 'pending';
                                            })->count();
                                            
                                            $totalResults = $employee->assessment_results->count();
                                            $pendingEvaluationCount = count($pendingResultIds);
                                            $evaluatedCount = $employee->assessment_results->filter(function($result) {
                                                return $result->evaluation_status === 'passed' || $result->evaluation_status === 'failed';
                                            })->count();
                                            
                                            // All pending = none evaluated yet
                                            $allPendingEvaluation = $pendingEvaluationCount === $totalResults;
                                            
                                            // Check if Step 2 (hands-on evaluation) is completed
                                            // Step 2 is completed when all results have evaluation_data filled
                                            $step2Completed = $employee->assessment_results->every(function($result) {
                                                return !empty($result->evaluation_data);
                                            });
                                        @endphp
                                        
                                        @if($employee->completed_assessments > 0)
                                            @if(!$allCompleted && $inProgressCount > 0)
                                                {{-- Not all assessments are completed yet - disable evaluate button --}}
                                                <button class="btn btn-sm btn-secondary" disabled style="padding: 6px 12px; border-radius: 4px; margin-bottom: 2px; opacity: 0.6;">
                                                    <i class="fas fa-clock"></i> Waiting for Submissions
                                                </button>
                                                <br><small class="text-muted">{{ $inProgressCount }} assessment(s) not yet submitted</small>
                                            @elseif($hasPending && $allCompleted)
                                                {{-- All submitted but NOT all evaluated yet - DISABLED --}}
                                                <button class="btn btn-sm btn-secondary" disabled style="padding: 6px 12px; border-radius: 4px; margin-bottom: 2px; opacity: 0.6;">
                                                    <i class="fas fa-clock"></i> Pending Evaluation
                                                </button>
                                                <br><small class="text-muted">{{ $evaluatedCount }}/{{ $totalResults }} evaluated</small>
                                            @elseif($allEvaluated && $step2Completed)
                                                {{-- All evaluated AND Step 2 completed - show as complete --}}
                                                <button class="btn btn-sm btn-success" disabled style="padding: 6px 12px; border-radius: 4px; margin-bottom: 2px;">
                                                    <i class="fas fa-check-circle"></i> Evaluation Complete
                                                </button>
                                            @elseif($allEvaluated && !$step2Completed && !$hasFailed)
                                                {{-- All Step 1 evaluated, Step 2 not done yet, all passed - CAN PROCEED to Step 2 --}}
                                                <a href="{{ route('assessment.results.evaluate', ['employeeId' => $employee->employee_id, 'result_ids' => implode(',', $resultIds)]) }}" 
                                                   class="btn btn-sm btn-success" style="padding: 6px 12px; border-radius: 4px; text-decoration: none; margin-bottom: 2px;">
                                                    <i class="fas fa-clipboard-check"></i> Proceed to Step 2
                                                </a>
                                            @elseif($allEvaluated && !$step2Completed && $hasFailed)
                                                {{-- All Step 1 evaluated, Step 2 not done yet, has failures - CAN PROCEED to Step 2 --}}
                                                <a href="{{ route('assessment.results.evaluate', ['employeeId' => $employee->employee_id, 'result_ids' => implode(',', $resultIds)]) }}" 
                                                   class="btn btn-sm btn-warning" style="padding: 6px 12px; border-radius: 4px; text-decoration: none; margin-bottom: 2px;">
                                                    <i class="fas fa-clipboard-check"></i> Proceed to Step 2
                                                </a>
                                            @endif
                                            <br>
                                            @php
                                                // Create unique group identifier - use employee_id and assignment_type
                                                $groupId = $employee->employee_id . '_' . strtolower(str_replace(' ', '_', $employee->assignment_type));
                                            @endphp
                                            <button class="btn btn-sm" style="background-color: #6c757d; color: white; padding: 4px 8px; border-radius: 4px; border: none; font-size: 11px;" 
                                                    onclick="toggleAssessmentDetails('{{ $groupId }}')">
                                                <i class="fas fa-list"></i> View Details
                                            </button>
                                        @else
                                            <span class="text-muted">No actions available</span>
                                        @endif
                                    </td>
                                </tr>
                                
                                <!-- Expandable Assessment Details Row -->
                                <tr id="details-{{ $groupId ?? $employee->employee_id . '_' . strtolower(str_replace(' ', '_', $employee->assignment_type)) }}" style="display: none; background-color: #f8f9fa;">
                                    <td colspan="10">
                                        <div class="p-3">
                                            <h6 class="mb-3">Individual Assessment Details:</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead>
                                                        <tr style="background-color: #e9ecef;">
                                                            <th>Quiz Title</th>
                                                            <th>Category</th>
                                                            <th>Date Taken</th>
                                                            <th>Attempt</th>
                                                            <th>Score</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($employee->assessment_results as $result)
                                                        <tr>
                                                            <td>{{ $result->quiz_title }}</td>
                                                            <td>{{ $result->category_name }}</td>
                                                            <td>{{ $result->completed_at ? \Carbon\Carbon::parse($result->completed_at)->format('M d, Y H:i') : 'N/A' }}</td>
                                                            <td>{{ $result->attempt_number }}/{{ $result->max_attempts }}</td>
                                                            <td>{{ $result->score ?? 'N/A' }}%</td>
                                                            <td>
                                                                @if($result->evaluation_status == 'passed')
                                                                    <span class="badge badge-success" style="font-size: 11px; background-color: #28a745; color: white;">
                                                                        <i class="fas fa-check"></i> Passed
                                                                    </span>
                                                                @elseif($result->evaluation_status == 'failed')
                                                                    <span class="badge badge-danger" style="font-size: 11px; background-color: #dc3545; color: white;">
                                                                        <i class="fas fa-times"></i> Rejected
                                                                    </span>
                                                                @elseif($result->status == 'completed' && ($result->evaluation_status == 'pending' || $result->evaluation_status === null))
                                                                    <span class="badge badge-warning" style="font-size: 11px; background-color: #ffc107; color: #212529;">
                                                                        <i class="fas fa-clock"></i> Pending Evaluation
                                                                    </span>
                                                                @elseif($result->status == 'in_progress')
                                                                    <span class="badge badge-info" style="font-size: 11px; background-color: #17a2b8; color: white;">
                                                                        <i class="fas fa-spinner"></i> In Progress
                                                                    </span>
                                                                @else
                                                                    <span class="badge badge-secondary" style="font-size: 11px; background-color: #6c757d; color: white;">{{ ucfirst($result->status) }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('assessment.results.evaluate.single', $result->id) }}" 
                                                                   class="btn btn-xs btn-outline-primary" style="font-size: 10px; padding: 2px 6px;">
                                                                    <i class="fas fa-eye"></i> View
                                                                </a>
                                                                @if($result->evaluation_status == 'failed')
                                                                    <form action="{{ route('assessment.results.reassign') }}" method="POST" style="display: inline;" 
                                                                          onsubmit="return confirm('Are you sure you want to give another attempt for this assessment?');">
                                                                        @csrf
                                                                        <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
                                                                        <input type="hidden" name="result_ids" value="{{ $result->id }}">
                                                                        <button type="submit" class="btn btn-xs" style="background-color: #fd7e14; color: white; font-size: 10px; padding: 2px 6px; border: none;">
                                                                            <i class="fas fa-redo"></i> Retry
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">No assessment results found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 p-3">
                        {{ $results->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- JavaScript for toggling assessment details -->
    <script>
        function toggleAssessmentDetails(employeeId) {
            const detailsRow = document.getElementById('details-' + employeeId);
            if (detailsRow.style.display === 'none') {
                detailsRow.style.display = 'table-row';
            } else {
                detailsRow.style.display = 'none';
            }
        }
    </script>
</x-app-layout>