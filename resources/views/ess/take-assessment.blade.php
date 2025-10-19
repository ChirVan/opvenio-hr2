<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Take Assessment - {{ $assessment->quiz_title }} - {{ config('app.name') }}</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .question-card {
            border-left: 4px solid #0d6efd;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        .question-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .timer-display {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc3545;
        }
        .header-card {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
        }
        .main-container {
            background-color: #ffffff;
            min-height: 100vh;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin: 20px;
        }
        .back-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Main Container -->
    <div class="main-container">
        <div class="container-fluid p-0">
            <!-- Header -->
            <div class="header-card card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('ess.lms') }}" class="back-btn btn me-3">
                                <i class='bx bx-arrow-back'></i>
                            </a>
                            <div>
                                <h4 class="mb-1">{{ $assessment->quiz_title }}</h4>
                                <p class="mb-0 opacity-75">{{ $assessment->category_name }} Assessment</p>
                            </div>
                        </div>
                        <div class="text-end">
                            @if($assessment->duration)
                            <div class="timer-display" id="timer">
                                {{ $assessment->duration }}:00
                            </div>
                            <small class="opacity-75">Time Remaining</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessment Content -->
            <div class="p-4">
            <form id="assessmentForm" method="POST" action="{{ route('ess.assessment.submit', $assessment->id) }}">
                @csrf
                @foreach($questions as $index => $question)
                <div class="card question-card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="text-primary">Question {{ $index + 1 }} of {{ count($questions) }}</h6>
                            <span class="badge bg-info">{{ $question->points ?? 1 }} Point{{ ($question->points ?? 1) > 1 ? 's' : '' }}</span>
                        </div>
                        
                        <p class="fs-6 mb-4">{{ $question->question }}</p>
                        
                        @if($question->question_type === 'identification')
                        <div class="mb-3">
                            <label for="answer_{{ $question->id }}" class="form-label">Your Answer:</label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   name="answers[{{ $question->id }}]" 
                                   id="answer_{{ $question->id }}"
                                   placeholder="Type your answer here..."
                                   autocomplete="off">
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
                
                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class='bx bx-check-circle me-2'></i>Submit Assessment
                    </button>
                </div>
            </form>
        </div>

        <!-- Assessment Info Footer -->
        <div class="p-4 border-top bg-light">
            <div class="row">
                <div class="col-md-6">
                    <h6>Assessment Information</h6>
                    <p class="small text-muted mb-1">
                        <i class='bx bx-refresh me-1'></i>
                        Attempt: {{ $assessment->attempts_used + 1 }} / {{ $assessment->max_attempts }}
                    </p>
                    @if($assessment->due_date)
                    <p class="small text-muted mb-0">
                        <i class='bx bx-calendar me-1'></i>
                        Due: {{ \Carbon\Carbon::parse($assessment->due_date)->format('M d, Y') }}
                    </p>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="alert alert-warning mb-0">
                        <i class='bx bx-info-circle me-2'></i>
                        <small>Make sure to answer all questions before submitting. You cannot change your answers once submitted.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Submit Confirmation Modal -->
<div class="modal fade" id="submitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submit Assessment?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to submit your assessment?</p>
                <div class="alert alert-info">
                    <i class='bx bx-info-circle me-2'></i>
                    You have answered <span id="answered-count">0</span> out of {{ count($questions) }} questions.
                </div>
                <p class="text-muted small">Once submitted, you cannot change your answers.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue Working</button>
                <button type="button" class="btn btn-success" id="confirmSubmit">Submit Now</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
let timeRemaining = {{ $assessment->duration ? ($assessment->duration * 60) : 0 }}; // Convert minutes to seconds
let timerInterval;

// Initialize timer if duration is set
if (timeRemaining > 0) {
    timerInterval = setInterval(updateTimer, 1000);
}

function updateTimer() {
    if (timeRemaining <= 0) {
        clearInterval(timerInterval);
        // Auto-submit when time runs out
        document.getElementById('assessmentForm').submit();
        return;
    }
    
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = timeRemaining % 60;
    document.getElementById('timer').textContent = 
        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    
    // Change color when time is running low
    if (timeRemaining <= 300) { // 5 minutes
        document.getElementById('timer').style.color = '#dc3545';
    } else if (timeRemaining <= 600) { // 10 minutes
        document.getElementById('timer').style.color = '#fd7e14';
    }
    
    timeRemaining--;
}

// Function to count answered questions
function updateAnsweredCount() {
    const totalQuestions = {{ count($questions) }};
    let answeredCount = 0;
    
    // Check all text inputs to see if they have values
    document.querySelectorAll('input[type="text"][name^="answers"]').forEach(input => {
        if (input.value.trim() !== '') {
            answeredCount++;
        }
    });
    
    // Update the modal display
    document.getElementById('answered-count').textContent = answeredCount;
    
    return answeredCount;
}

// Form submission handling
document.getElementById('assessmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Update answered count before showing modal
    updateAnsweredCount();
    
    // Show confirmation modal
    new bootstrap.Modal(document.getElementById('submitModal')).show();
});

document.getElementById('confirmSubmit').addEventListener('click', function() {
    // Clear timer
    if (timerInterval) {
        clearInterval(timerInterval);
    }
    
    // Show loading
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
    
    // Submit form via AJAX
    const form = document.getElementById('assessmentForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        // Check if the response is JSON
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // If not JSON, get text and throw error
            return response.text().then(text => {
                console.error('Non-JSON response:', text);
                throw new Error('Server returned non-JSON response');
            });
        }
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Show success message and redirect
            alert(`Assessment completed! Your score: ${data.score}%${data.passed ? ' - Passed!' : ' - Failed'}`);
            window.location.href = data.redirect;
        } else {
            alert('Error submitting assessment: ' + (data.message || 'Please try again.'));
            this.disabled = false;
            this.innerHTML = 'Submit Now';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting assessment: ' + error.message);
        this.disabled = false;
        this.innerHTML = 'Submit Now';
    });
});

// Optional: Add real-time tracking as user types (for better UX)
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all answer inputs for real-time tracking
    document.querySelectorAll('input[type="text"][name^="answers"]').forEach(input => {
        input.addEventListener('input', function() {
            // Update count in real-time (optional)
            updateAnsweredCount();
        });
    });
});
</script>
</body>
</html>