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
        <div class="mb-6">
            @if (isset($isSingleAssessment) && $isSingleAssessment)
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
                            @if (isset($isSingleAssessment) && $isSingleAssessment)
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
                            <!-- Employee / Assessment summary -->
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
                                        @if (isset($isSingleAssessment) && $isSingleAssessment)
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
                                        @endif
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    @if(isset($isSingleAssessment) && $isSingleAssessment)
                                        <h5>Assessment Details</h5>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Date Taken:</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($assessmentData[0]->completed_at)->format('M d, Y H:i A') }}</td>
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
                                    @endif
                                </div>
                            </div>

                            <!-- Interactive Assessment Form -->
                            @if(!(isset($isReadOnlyMode) && $isReadOnlyMode))
                                <form id="assessmentScoringForm" method="POST" action="{{ route('assessment.results.update-scoring') }}">
                                    @csrf
                                    <input type="hidden" name="employee_id" value="{{ $employeeData->employee_id }}">

                                    @foreach ($assessmentData as $assessmentIndex => $assessment)
                                        <input type="hidden" name="result_ids[]" value="{{ $assessment->result_id }}">

                                        <div class="card mb-4 border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="mb-0">{{ $assessment->quiz_title }} <small>({{ $assessment->category_name }})</small></h5>
                                            </div>
                                            <div class="card-body">
                                                @foreach ($assessment->questions_and_answers as $questionIndex => $qa)
                                                    <div class="card mb-3 question-card border-secondary" data-question-number="{{ $qa->question_number ?? ($loop->index+1) }}" data-user-answer-id="{{ $qa->user_answer_row_id ?? $qa->id }}">
                                                        <div class="card-header d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-0">Question {{ $qa->question_number ?? ($questionIndex+1) }}</h6>
                                                            <div class="scoring-controls">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input question-score border-secondary" type="radio" name="question_scores[{{ $qa->user_answer_row_id ?? $qa->id }}][is_correct]" id="correct_{{ $qa->user_answer_row_id ?? $qa->id }}" value="1">
                                                                    <label class="form-check-label text-success" for="correct_{{ $qa->user_answer_row_id ?? $qa->id }}"><i class="fas fa-check"></i> Correct</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input question-score border-secondary" type="radio" name="question_scores[{{ $qa->user_answer_row_id ?? $qa->id }}][is_correct]" id="incorrect_{{ $qa->user_answer_row_id ?? $qa->id }}" value="0">
                                                                    <label class="form-check-label text-danger" for="incorrect_{{ $qa->user_answer_row_id ?? $qa->id }}"><i class="fas fa-times"></i> Incorrect</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12 mb-3 border-secondary">
                                                                    <strong>Question:</strong>
                                                                    <p class="mt-2">{{ $qa->question_text }}</p>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <strong>Employee's Answer:</strong>
                                                                    <div class="p-2 bg-light border-secondary rounded mt-2">
                                                                        {{ $qa->employee_answer ?? $qa->user_answer ?? 'No answer provided' }}
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <strong>Correct Answer:</strong>
                                                                    <div class="p-2 bg-success text-white border rounded mt-2">
                                                                        {{ $qa->correct_answer ?? 'N/A' }}
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row mt-3">
                                                                <div class="col-md-12">
                                                                    <label class="form-label"><strong>Evaluator Comments:</strong></label>
                                                                    <textarea class="form-control form-control-sm border-secondary" name="question_scores[{{ $qa->user_answer_row_id ?? $qa->id }}][comments]" rows="2" placeholder="Optional comments...">{{ $qa->evaluator_comments ?? '' }}</textarea>
                                                                </div>
                                                            </div>

                                                            @if(!empty($qa->manually_graded) && $qa->manually_graded)
                                                                <div class="mt-2">
                                                                    <small class="text-info">Previously graded on {{ \Carbon\Carbon::parse($qa->graded_at ?? now())->format('M d, Y H:i A') }}</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="row mt-4">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-success btn-lg" id="saveScoringBtn"><i class="fas fa-check"></i> Approve</button>
                                            <button type="button" class="btn btn-danger btn-lg ml-2" onclick="rejectAssessment()"><i class="fas fa-times"></i> Reject</button>

                                            @if(isset($isSingleAssessment) && $isSingleAssessment && isset($resultId))
                                                <button id="ai-check-btn" class="btn btn-info btn-lg ml-2">֎ Auto-check with AI</button>
                                            @endif
                                        </div>
                                    </div>
                                </form>

                                <!-- Inline AI Result Panel -->
                                <div id="ai-result-panel" class="mt-4" style="display:none;">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="mb-0">AI Automatic Evaluation</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="ai-result-content" class="mb-3"></div>

                                            <div id="ai-result-actions" style="display:none;">
                                                <button id="aiApproveBtnInline" class="btn btn-success mr-2">Approve AI Result</button>
                                                <button id="aiRejectBtnInline" class="btn btn-secondary">Dismiss</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Read-only mode: display summary only -->
                                <div class="alert alert-info">Read-only mode — results displayed above.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Basic form submit handling (unchanged behavior)
        (function(){
            const form = document.getElementById('assessmentScoringForm');
            if (form) {
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    if (!confirm('Are you sure you want to approve this assessment?')) return;

                    const btn = document.getElementById('saveScoringBtn');
                    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(r => r.json()).then(json => {
                        if (json.success) {
                            alert('Assessment approved successfully!');
                            window.location.href = '{{ route("assessment.results") }}';
                        } else {
                            alert('Error: ' + (json.message || 'Unknown'));
                        }
                    }).catch(err => {
                        console.error(err); alert('Error processing request.');
                    }).finally(()=>{
                        btn.disabled = false; btn.innerHTML = '<i class="fas fa-check"></i> Approve';
                    });
                });
            }

            window.rejectAssessment = function(){
                if (!confirm('Are you sure you want to reject this assessment?')) return;
                const form = document.getElementById('assessmentScoringForm');
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}
                }).then(r=>r.json()).then(json=>{
                    if (json.success) { alert('Assessment rejected.'); window.location.href='{{ route("assessment.results") }}'; }
                    else alert('Error: ' + (json.message||'Unknown'));
                }).catch(err=>{ console.error(err); alert('Error rejecting.'); });
            };
        })();
    </script>

    <script>
        // AI integration: lightweight, defensive, and self-contained
        (function(){
            const aiBtn = document.getElementById('ai-check-btn');
            if (!aiBtn) return; // nothing to do

            const resultId = {!! json_encode($resultId ?? null) !!};
            const apiEvaluateUrl = `/api/ai/evaluate/${resultId}`;
            const apiApproveUrl = `/api/ai/evaluate/${resultId}/approve`;

            const panel = document.getElementById('ai-result-panel');
            const contentDiv = document.getElementById('ai-result-content');
            const actionsDiv = document.getElementById('ai-result-actions');
            const approveBtn = document.getElementById('aiApproveBtnInline');
            const rejectBtn = document.getElementById('aiRejectBtnInline');

            function showSpinner(btn){ btn.disabled = true; btn.dataset.orig = btn.innerHTML; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...'; }
            function hideSpinner(btn){ if (btn && btn.dataset && btn.dataset.orig) { btn.innerHTML = btn.dataset.orig; btn.disabled = false; delete btn.dataset.orig; } }

            function renderAiResult(ai){
                if (!ai) { contentDiv.innerHTML = '<div class="alert alert-warning">No AI data returned.</div>'; return; }
                if (ai.error) { contentDiv.innerHTML = `<div class="alert alert-danger">AI Error: ${ai.error}</div>`; return; }

                // Build a friendly HTML summary based on expected schema
                let html = '';
                if (typeof ai.overall_score !== 'undefined') {
                    html += `<p><strong>Overall Score:</strong> ${ai.overall_score} — <strong>Percentage:</strong> ${ai.percentage ?? 'N/A'}%</p>`;
                }

                if (Array.isArray(ai.per_question) && ai.per_question.length){
                    html += '<h6>Per-question details</h6><div class="table-responsive"><table class="table table-sm table-bordered"><thead><tr><th>#</th><th>Correct?</th><th>Reason</th></tr></thead><tbody>';
                    ai.per_question.forEach(q=>{
                        const num = q.question_number ?? q.question_id ?? '-';
                        const ok = q.is_correct ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>';
                        const reason = (q.reason && q.reason.length) ? q.reason : '';
                        html += `<tr><td>${num}</td><td>${ok}</td><td>${reason}</td></tr>`;
                    });
                    html += '</tbody></table></div>';
                }

                if (!html) html = `<pre>${JSON.stringify(ai, null, 2)}</pre>`;
                contentDiv.innerHTML = html;
            }

            aiBtn.addEventListener('click', async function(e){
                e.preventDefault();
                if (!confirm('Run automatic AI evaluation for this assessment?')) return;
                showSpinner(aiBtn);

                try {
                    const resp = await fetch(apiEvaluateUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({})
                    });

                    if (!resp.ok) {
                        const text = await resp.text();
                        contentDiv.innerHTML = `<div class="alert alert-danger">Server error: ${resp.status} — ${text}</div>`;
                        panel.style.display = 'block'; actionsDiv.style.display = 'none';
                        return;
                    }

                    const json = await resp.json();
                    if (!json.success) {
                        contentDiv.innerHTML = `<div class="alert alert-danger">AI Error: ${json.message || 'Unknown'}</div>`;
                        panel.style.display = 'block'; actionsDiv.style.display = 'none';
                        return;
                    }

                    renderAiResult(json.ai);
                    applyAiResultsToForm(json.ai);
                    panel.style.display = 'block'; actionsDiv.style.display = 'block';
                    panel.dataset.aiPayload = JSON.stringify(json.ai || {});

                } catch(err) {
                    console.error(err);
                    contentDiv.innerHTML = `<div class="alert alert-danger">Network or server error: ${err.message}</div>`;
                    panel.style.display = 'block'; actionsDiv.style.display = 'none';
                } finally {
                    hideSpinner(aiBtn);
                }
            });

            approveBtn.addEventListener('click', async function(e){
                if (!confirm('Approve AI grading? This will save the AI review.')) return;
                showSpinner(approveBtn);
                try {
                    const aiPayload = panel.dataset.aiPayload ? JSON.parse(panel.dataset.aiPayload) : null;
                    if (!aiPayload) { alert('No AI result to save.'); return; }

                    const resp = await fetch(apiApproveUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ ai_result: aiPayload })
                    });

                    const json = await resp.json();
                    if (json.success) { alert('AI review saved.'); window.location.reload(); }
                    else alert('Failed to save AI review: ' + (json.message || 'Unknown'));
                } catch(err){ console.error(err); alert('Error saving AI review: ' + err.message); }
                finally { hideSpinner(approveBtn); }
            });

            rejectBtn.addEventListener('click', function(){ panel.style.display='none'; contentDiv.innerHTML=''; actionsDiv.style.display='none'; delete panel.dataset.aiPayload; });

            function applyAiResultsToForm(ai) {
  if (!ai || !Array.isArray(ai.per_question)) return;

  ai.per_question.forEach(q => {
    const qnum = q.question_number; // AI must return question_number
    if (typeof qnum === 'undefined') return;

    // find the question card by number
    const card = document.querySelector(`.question-card[data-question-number="${qnum}"]`);
    if (!card) return;

    // set radio
    const correctInput = card.querySelector(`input[type="radio"][value="1"]`);
    const incorrectInput = card.querySelector(`input[type="radio"][value="0"]`);
    if (q.is_correct) {
      if (correctInput) correctInput.checked = true;
      if (incorrectInput) incorrectInput.checked = false;
    } else {
      if (incorrectInput) incorrectInput.checked = true;
      if (correctInput) correctInput.checked = false;
    }

    // optionally display AI reason inline (only when present)
    let reasonEl = card.querySelector('.ai-reason');
    if (!reasonEl) {
      reasonEl = document.createElement('div');
      reasonEl.className = 'ai-reason mt-2 text-muted small';
      card.appendChild(reasonEl);
    }
    reasonEl.textContent = q.reason ? `AI: ${q.reason}` : '';
    
    // If you want to update awarded points display (optional)
    let awardedEl = card.querySelector('.ai-awarded');
    if (!awardedEl) {
      awardedEl = document.createElement('div');
      awardedEl.className = 'ai-awarded mt-1';
      card.appendChild(awardedEl);
    }
    if (typeof q.awarded_points !== 'undefined') {
      awardedEl.innerHTML = `<small>AI awarded: ${q.awarded_points}/${q.points_possible}</small>`;
    } else {
      awardedEl.innerHTML = '';
    }
  });
}
        })();
        
    </script>

</x-app-layout>
