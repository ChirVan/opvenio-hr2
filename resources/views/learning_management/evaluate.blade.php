<x-app-layout>
    <x-slot name="header">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>
    
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <!-- Page Header -->
        <div class="mb-6">
            @if(isset($isSingleAssessment) && $isSingleAssessment)
                <h1 class="text-3xl font-bold text-gray-900">Evaluate Single Assessment</h1>
                <p class="text-gray-600 mt-1">Review individual assessment answers and provide manual scoring.</p>
            @elseif(isset($isReadOnlyMode) && $isReadOnlyMode)
                <h1 class="text-3xl font-bold text-gray-900">Assessment Results Summary</h1>
                <p class="text-gray-600 mt-1">Review completed assessment scores and proceed to hands-on evaluation.</p>
            @else
                <h1 class="text-3xl font-bold text-gray-900">Evaluate Employee Assessments</h1>
                <p class="text-gray-600 mt-1">Review all assessment answers and provide manual scoring.</p>
            @endif
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @if(isset($isSingleAssessment) && $isSingleAssessment)
                                <h3 class="card-title">Single Assessment Evaluation</h3>
                            @elseif(isset($isReadOnlyMode) && $isReadOnlyMode)
                                <h3 class="card-title">Assessment Results Summary - Step 1</h3>
                            @else
                                <h3 class="card-title">Employee Assessment Evaluation</h3>
                            @endif
                            <div class="card-tools">
                                <a href="{{ route('assessment.results') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Results
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Employee Information -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5>Employee Information</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Name:</strong></td>
                                            <td>{{ $employeeData->employee_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $employeeData->employee_email }}</td>
                                        </tr>
                                        @if(isset($isSingleAssessment) && $isSingleAssessment)
                                        <tr>
                                            <td><strong>Assessment Type:</strong></td>
                                            <td>Single Assessment Evaluation</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Quiz:</strong></td>
                                            <td>{{ $assessmentData[0]->quiz_title }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Category:</strong></td>
                                            <td>{{ $assessmentData[0]->category_name }}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td><strong>Total Assessments:</strong></td>
                                            <td>{{ $employeeData->total_assessments }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Completed:</strong></td>
                                            <td>{{ $employeeData->completed_assessments }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    @if(isset($isReadOnlyMode) && $isReadOnlyMode)
                                    <h5>Next Steps</h5>
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle"></i> Step 1 Complete</h6>
                                        <p class="mb-0">All assessments have been evaluated. Use the "Step 2 Evaluation" buttons below to conduct hands-on evaluations for each assessment individually.</p>
                                    </div>
                                    @elseif(isset($isSingleAssessment) && $isSingleAssessment)
                                    <h5>Assessment Details</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Date Taken:</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($assessmentData[0]->completed_at)->format('M d, Y H:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Attempt:</strong></td>
                                            <td>{{ $assessmentData[0]->attempt_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Current Score:</strong></td>
                                            <td>{{ $assessmentData[0]->score ?? 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Questions:</strong></td>
                                            <td>{{ count($assessmentData[0]->questions_and_answers) }}</td>
                                        </tr>
                                    </table>
                                    @else
                                    <h5>Overall Status</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($employeeData->overall_status == 'completed')
                                                    <span class="badge badge-success">All Completed</span>
                                                @elseif($employeeData->overall_status == 'partial')
                                                    <span class="badge badge-warning">Partially Completed</span>
                                                @else
                                                    <span class="badge badge-secondary">Not Started</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Progress:</strong></td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: {{ ($employeeData->completed_assessments / $employeeData->total_assessments) * 100 }}%"></div>
                                                </div>
                                                {{ round(($employeeData->completed_assessments / $employeeData->total_assessments) * 100, 1) }}%
                                            </td>
                                        </tr>
                                    </table>
                                    @endif
                                </div>
                            </div>

                            <!-- Assessment Results Display -->
                            @if(isset($isReadOnlyMode) && $isReadOnlyMode)
                                <!-- Read-only Assessment Summary -->
                                <div class="row">
                                    <div class="col-12">
                                        <h5><i class="fas fa-chart-bar"></i> Assessment Summary</h5>
                                        <p class="text-muted">Below are the completed assessment results for this employee:</p>
                                    </div>
                                </div>
                                
                                @foreach($assessmentData as $assessmentIndex => $assessment)
                                    <div class="card mb-4 border-success">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0">
                                                <i class="fas fa-clipboard-check"></i>
                                                {{ $assessment->quiz_title }} 
                                                <small>({{ $assessment->category_name }})</small>
                                            </h5>
                                            <div class="row mt-2">
                                                <div class="col-md-3">
                                                    <small><strong>Date Taken:</strong> {{ \Carbon\Carbon::parse($assessment->completed_at)->format('M d, Y H:i A') }}</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <small><strong>Total Questions:</strong> {{ $assessment->total_questions }}</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <small><strong>Correct Answers:</strong> {{ $assessment->correct_answers }}</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <small><strong>Final Score:</strong> {{ $assessment->score_percentage }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="progress mb-2">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $assessment->score_percentage }}%" aria-valuenow="{{ $assessment->score_percentage }}" aria-valuemin="0" aria-valuemax="100">
                                                            {{ $assessment->score_percentage }}%
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">
                                                        Assessment Status: 
                                                        @if($assessment->manually_graded == $assessment->total_questions)
                                                            <span class="badge badge-success">Fully Evaluated</span>
                                                        @elseif($assessment->manually_graded > 0)
                                                            <span class="badge badge-warning">Partially Evaluated ({{ $assessment->manually_graded }}/{{ $assessment->total_questions }})</span>
                                                        @else
                                                            <span class="badge badge-secondary">Not Evaluated</span>
                                                        @endif
                                                    </small>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <div class="score-display">
                                                        <h4 class="text-success mb-0">{{ $assessment->correct_answers }}/{{ $assessment->total_questions }}</h4>
                                                        <small class="text-muted">Correct Answers</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                <!-- Hands-on Evaluation Section -->
                                <div class="row mt-5">
                                    <div class="col-12">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-hand-paper"></i> Hands-on Evaluation
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <h6><strong>Step 2: Practical Skills Assessment</strong></h6>
                                                        <p class="text-muted mb-3">
                                                            After reviewing the quiz results above, proceed to evaluate the employee's practical skills and competencies through hands-on assessment.
                                                        </p>
                                                        <div class="alert alert-info">
                                                            <h6><i class="fas fa-info-circle"></i> What's Next?</h6>
                                                            <ul class="mb-0">
                                                                <li>Evaluate 5 core competency areas</li>
                                                                <li>Provide detailed feedback and comments</li>
                                                                <li>Make final pass/fail decision</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <div class="evaluation-action">
                                                            <h6 class="text-primary"><strong>Ready to Proceed?</strong></h6>
                                                            <button type="button" class="btn btn-primary btn-lg" onclick="proceedToHandsOnEvaluation()">
                                                                <i class="fas fa-clipboard-check"></i> Start Hands-on Evaluation
                                                            </button>
                                                            <small class="d-block mt-2 text-muted">This will take you to the competency evaluation form</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Interactive Assessment Form -->
                                <form id="assessmentScoringForm" method="POST" action="{{ route('assessment.results.update-scoring') }}">
                                    @csrf
                                    <input type="hidden" name="employee_id" value="{{ $employeeData->employee_id }}">
                                    
                                    @foreach($assessmentData as $assessmentIndex => $assessment)
                                    <input type="hidden" name="result_ids[]" value="{{ $assessment->result_id }}">
                                    
                                    <!-- Assessment Card -->
                                    <div class="card mb-4 border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">
                                                <i class="fas fa-clipboard-check"></i>
                                                {{ $assessment->quiz_title }} 
                                                <small>({{ $assessment->category_name }})</small>
                                            </h5>
                                            <div class="row mt-2">
                                                <div class="col-md-4">
                                                    <small><strong>Date Taken:</strong> {{ \Carbon\Carbon::parse($assessment->completed_at)->format('M d, Y H:i A') }}</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <small><strong>Questions:</strong> {{ count($assessment->questions_and_answers) }}</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <small><strong>Current Score:</strong> <span id="assessment_score_{{ $assessmentIndex }}">{{ $assessment->score ?? 0 }}%</span></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Questions and Manual Scoring -->
                                            @foreach($assessment->questions_and_answers as $questionIndex => $qa)
                                                <div class="card mb-3 question-card" data-assessment="{{ $assessmentIndex }}" data-question="{{ $questionIndex }}">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0">Question {{ $questionIndex + 1 }}</h6>
                                                        <div class="scoring-controls">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input question-score" 
                                                                       type="radio" 
                                                                       name="question_scores[{{ $qa->id }}][is_correct]" 
                                                                       id="correct_{{ $qa->id }}" 
                                                                       value="1"
                                                                       {{ $qa->manually_graded && $qa->is_correct ? 'checked' : ($qa->is_correct && !$qa->manually_graded ? 'checked' : '') }}
                                                                       onchange="updateQuestionScore({{ $assessmentIndex }}, {{ $questionIndex }}, '{{ $qa->id }}')">
                                                                <label class="form-check-label text-success" for="correct_{{ $qa->id }}">
                                                                    <i class="fas fa-check"></i> Correct
                                                                </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input question-score" 
                                                                       type="radio" 
                                                                       name="question_scores[{{ $qa->id }}][is_correct]" 
                                                                       id="incorrect_{{ $qa->id }}" 
                                                                       value="0"
                                                                       {{ $qa->manually_graded && !$qa->is_correct ? 'checked' : (!$qa->is_correct && !$qa->manually_graded ? 'checked' : '') }}
                                                                       onchange="updateQuestionScore({{ $assessmentIndex }}, {{ $questionIndex }}, '{{ $qa->id }}')">
                                                                <label class="form-check-label text-danger" for="incorrect_{{ $qa->id }}">
                                                                    <i class="fas fa-times"></i> Incorrect
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-12 mb-3">
                                                                <strong>Question:</strong>
                                                                <p class="mt-2">{{ $qa->question_text }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Employee's Answer:</strong>
                                                                <div class="p-2 bg-light border rounded mt-2">
                                                                    {{ $qa->user_answer ?: 'No answer provided' }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Correct Answer:</strong>
                                                                <div class="p-2 bg-success text-white border rounded mt-2">
                                                                    {{ $qa->correct_answer }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Manual Comments -->
                                                        <div class="row mt-3">
                                                            <div class="col-md-12">
                                                                <label class="form-label"><strong>Evaluator Comments:</strong></label>
                                                                <textarea class="form-control form-control-sm" 
                                                                          name="question_scores[{{ $qa->id }}][comments]" 
                                                                          rows="2" 
                                                                          placeholder="Optional comments...">{{ $qa->evaluator_comments }}</textarea>
                                                            </div>
                                                        </div>

                                                        @if($qa->manually_graded)
                                                            <div class="mt-2">
                                                                <small class="text-info">
                                                                    <i class="fas fa-info-circle"></i> 
                                                                    Previously graded by {{ $qa->grader_name ?? 'Admin' }} on {{ \Carbon\Carbon::parse($qa->graded_at)->format('M d, Y H:i A') }}
                                                                </small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Action Buttons -->
                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-success btn-lg" id="saveScoringBtn">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-danger btn-lg ml-2" onclick="rejectAssessment()">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Dynamic Scoring -->
    <script>
        function updateQuestionScore(assessmentIndex, questionIndex, answerId) {
            // This function can be used for real-time score calculation
            // For now, we'll handle scoring on form submission
            console.log('Updating score for assessment:', assessmentIndex, 'question:', questionIndex, 'answer:', answerId);
        }

        document.getElementById('assessmentScoringForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to approve this assessment?')) {
                return;
            }
            
            const formData = new FormData(this);
            formData.append('action', 'approve');
            const saveScoringBtn = document.getElementById('saveScoringBtn');
            
            saveScoringBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            saveScoringBtn.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('Assessment approved successfully!');
                    // Redirect back to results page
                    window.location.href = '{{ route("assessment.results") }}';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing the assessment.');
            })
            .finally(() => {
                saveScoringBtn.innerHTML = '<i class="fas fa-check"></i> Approve';
                saveScoringBtn.disabled = false;
            });
        });

        function rejectAssessment() {
            if (confirm('Are you sure you want to reject this assessment?')) {
                const form = document.getElementById('assessmentScoringForm');
                const formData = new FormData(form);
                formData.append('action', 'reject');
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Assessment rejected.');
                        window.location.href = '{{ route("assessment.results") }}';
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while rejecting the assessment.');
                });
            }
        }

        function proceedToHandsOnEvaluation() {
            if (confirm('Proceed to hands-on evaluation? This will assess the employee\'s practical skills and competencies.')) {
                // For hands-on evaluation, we'll use the first assessment result ID as a reference
                // since the competency evaluation is employee-level, not assessment-specific
                @if(isset($assessmentData) && count($assessmentData) > 0)
                    const firstResultId = '{{ $assessmentData[0]->result_id }}';
                    // Pass all result IDs for this group so we only update these specific assessments
                    const resultIds = '{{ implode(",", array_column($assessmentData, "result_id")) }}';
                    window.location.href = `/assessment-results/${firstResultId}/evaluate/step2?result_ids=${resultIds}`;
                @else
                    alert('No assessment data available for hands-on evaluation.');
                @endif
            }
        }
    </script>
</x-app-layout>