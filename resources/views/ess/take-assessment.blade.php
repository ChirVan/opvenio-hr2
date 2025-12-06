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
    <!-- SweetAlert2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-light: #6366f1;
            --primary-dark: #4338ca;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 0;
            margin: 0;
        }

        .assessment-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
        }

        /* Header Styles */
        .assessment-header {
            background: white;
            border-radius: 20px;
            padding: 24px 32px;
            margin-bottom: 24px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            position: sticky;
            top: 20px;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-button {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--gray-100);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-button:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .assessment-title {
            flex: 1;
            margin-left: 20px;
        }

        .assessment-title h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-800);
            margin: 0;
        }

        .assessment-title p {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin: 4px 0 0 0;
        }

        .timer-box {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            padding: 16px 24px;
            border-radius: 16px;
            text-align: center;
            min-width: 120px;
        }

        .timer-box.warning {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
        }

        .timer-box.safe {
            background: linear-gradient(135deg, var(--success-color), #059669);
        }

        .timer-display {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            line-height: 1;
        }

        .timer-label {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.8);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        /* Progress Bar */
        .progress-section {
            background: white;
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .progress-text {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
        }

        .progress-count {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .progress-bar-container {
            height: 8px;
            background: var(--gray-200);
            border-radius: 100px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            border-radius: 100px;
            transition: width 0.5s ease;
        }

        /* Question Card Styles */
        .questions-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .question-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .question-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .question-card.answered {
            border-color: var(--success-color);
        }

        .question-card.answered .question-header {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        }

        .question-card.answered .question-number {
            background: var(--success-color);
        }

        .question-header {
            background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--gray-200);
        }

        .question-number {
            background: var(--primary-color);
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .question-meta {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .question-label {
            font-size: 0.8rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        .points-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .question-body {
            padding: 24px;
        }

        .question-text {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--gray-800);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .answer-section {
            background: var(--gray-50);
            border-radius: 12px;
            padding: 20px;
        }

        .answer-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .answer-input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .answer-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .answer-input::placeholder {
            color: var(--gray-400);
        }

        /* Submit Section */
        .submit-section {
            background: white;
            border-radius: 20px;
            padding: 32px;
            margin-top: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            text-align: center;
        }

        .submit-info {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--gray-600);
            font-size: 0.9rem;
        }

        .info-item i {
            font-size: 1.25rem;
            color: var(--primary-color);
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--success-color), #059669);
            border: none;
            padding: 18px 48px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(16, 185, 129, 0.5);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .submit-warning {
            margin-top: 16px;
            padding: 12px 20px;
            background: #fef3c7;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #92400e;
            font-size: 0.85rem;
        }

        /* Footer Info */
        .assessment-footer {
            background: white;
            border-radius: 20px;
            padding: 24px 32px;
            margin-top: 24px;
            margin-bottom: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
        }

        .footer-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .footer-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        .footer-text h6 {
            font-size: 0.75rem;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .footer-text p {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 2px 0 0 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .assessment-container {
                padding: 12px;
            }

            .assessment-header {
                padding: 16px 20px;
                position: relative;
                top: 0;
            }

            .header-content {
                flex-wrap: wrap;
                gap: 16px;
            }

            .assessment-title h1 {
                font-size: 1.25rem;
            }

            .timer-box {
                width: 100%;
                order: 3;
            }

            .question-body {
                padding: 16px;
            }

            .submit-info {
                gap: 20px;
            }
        }

        /* SweetAlert Custom Styles */
        .swal2-popup {
            border-radius: 20px !important;
            padding: 32px !important;
        }

        .swal2-title {
            font-family: 'Inter', sans-serif !important;
        }

        .swal2-html-container {
            font-family: 'Inter', sans-serif !important;
        }
    </style>
</head>
<body>
    <div class="assessment-container">
        <!-- Header -->
        <div class="assessment-header">
            <div class="header-content">
                <a href="{{ route('ess.lms') }}" class="back-button">
                    <i class='bx bx-arrow-back bx-sm'></i>
                </a>
                <div class="assessment-title">
                    <h1>{{ $assessment->quiz_title }}</h1>
                    <p><i class='bx bx-category me-1'></i>{{ $assessment->category_name }} Assessment</p>
                </div>
                @if($assessment->duration)
                <div class="timer-box safe" id="timerBox">
                    <div class="timer-display" id="timer">--:--</div>
                    <div class="timer-label">Time Left</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Progress Section -->
        <div class="progress-section">
            <div class="progress-info">
                <span class="progress-text"><i class='bx bx-list-check me-2'></i>Your Progress</span>
                <span class="progress-count"><span id="answeredCount">0</span> of {{ count($questions) }} answered</span>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill" id="progressBar" style="width: 0%"></div>
            </div>
        </div>

        <!-- Questions Form -->
        <form id="assessmentForm" method="POST" action="{{ route('ess.assessment.submit', $assessment->id) }}">
            @csrf
            <div class="questions-container">
                @foreach($questions as $index => $question)
                <div class="question-card" id="questionCard{{ $question->id }}">
                    <div class="question-header">
                        <div class="question-meta">
                            <div class="question-number">{{ $index + 1 }}</div>
                            <span class="question-label">Question {{ $index + 1 }} of {{ count($questions) }}</span>
                        </div>
                        <div class="points-badge">
                            <i class='bx bx-star'></i>
                            {{ $question->points ?? 1 }} {{ ($question->points ?? 1) > 1 ? 'Points' : 'Point' }}
                        </div>
                    </div>
                    <div class="question-body">
                        <p class="question-text">{{ $question->question }}</p>
                        
                        @if($question->question_type === 'identification')
                        <div class="answer-section">
                            <label class="answer-label" for="answer_{{ $question->id }}">
                                <i class='bx bx-edit'></i>
                                Your Answer
                            </label>
                            <input type="text" 
                                   class="answer-input" 
                                   name="answers[{{ $question->id }}]" 
                                   id="answer_{{ $question->id }}"
                                   data-question-id="{{ $question->id }}"
                                   placeholder="Type your answer here..."
                                   autocomplete="off">
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Submit Section -->
            <div class="submit-section">
                <div class="submit-info">
                    <div class="info-item">
                        <i class='bx bx-help-circle'></i>
                        <span>{{ count($questions) }} Questions</span>
                    </div>
                    <div class="info-item">
                        <i class='bx bx-refresh'></i>
                        <span>Attempt {{ $assessment->attempts_used + 1 }} of {{ $assessment->max_attempts }}</span>
                    </div>
                    @if($assessment->due_date)
                    <div class="info-item">
                        <i class='bx bx-calendar'></i>
                        <span>Due {{ \Carbon\Carbon::parse($assessment->due_date)->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
                
                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class='bx bx-check-circle bx-sm'></i>
                    Submit Assessment
                </button>
                
                <div class="submit-warning">
                    <i class='bx bx-info-circle'></i>
                    <span>Once submitted, you cannot change your answers.</span>
                </div>
            </div>
        </form>

        <!-- Footer Info -->
        <div class="assessment-footer">
            <div class="footer-grid">
                <div class="footer-item">
                    <div class="footer-icon">
                        <i class='bx bx-time-five'></i>
                    </div>
                    <div class="footer-text">
                        <h6>Duration</h6>
                        <p>{{ $assessment->duration ?? 'No Limit' }} {{ $assessment->duration ? 'Minutes' : '' }}</p>
                    </div>
                </div>
                <div class="footer-item">
                    <div class="footer-icon">
                        <i class='bx bx-target-lock'></i>
                    </div>
                    <div class="footer-text">
                        <h6>Total Points</h6>
                        <p>{{ $questions->sum('points') ?? count($questions) }} Points</p>
                    </div>
                </div>
                <div class="footer-item">
                    <div class="footer-icon">
                        <i class='bx bx-check-shield'></i>
                    </div>
                    <div class="footer-text">
                        <h6>Question Type</h6>
                        <p>Identification</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const totalQuestions = {{ count($questions) }};
let timeRemaining = {{ (int) $remainingSeconds }};
let timerInterval;

// Initialize timer if duration is set
if (timeRemaining > 0) {
    // Display initial time immediately
    const initMinutes = Math.floor(timeRemaining / 60);
    const initSeconds = timeRemaining % 60;
    document.getElementById('timer').textContent = 
        `${initMinutes.toString().padStart(2, '0')}:${initSeconds.toString().padStart(2, '0')}`;
    
    // Update timer color based on initial time
    updateTimerColor(timeRemaining);
    
    timerInterval = setInterval(updateTimer, 1000);
}

function updateTimerColor(seconds) {
    const timerBox = document.getElementById('timerBox');
    if (!timerBox) return;
    
    if (seconds <= 300) { // 5 minutes - danger
        timerBox.className = 'timer-box';
    } else if (seconds <= 600) { // 10 minutes - warning
        timerBox.className = 'timer-box warning';
    } else {
        timerBox.className = 'timer-box safe';
    }
}

function updateTimer() {
    if (timeRemaining <= 0) {
        clearInterval(timerInterval);
        // Auto-submit when time runs out
        Swal.fire({
            icon: 'warning',
            title: 'Time\'s Up!',
            text: 'Your assessment will be submitted automatically.',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        }).then(() => {
            submitAssessment();
        });
        return;
    }
    
    timeRemaining--;
    
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = timeRemaining % 60;
    document.getElementById('timer').textContent = 
        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    
    // Update timer color based on remaining time
    updateTimerColor(timeRemaining);
}

// Update progress and answered count
function updateProgress() {
    let answeredCount = 0;
    
    document.querySelectorAll('.answer-input').forEach(input => {
        const card = document.getElementById('questionCard' + input.dataset.questionId);
        if (input.value.trim() !== '') {
            answeredCount++;
            card.classList.add('answered');
        } else {
            card.classList.remove('answered');
        }
    });
    
    document.getElementById('answeredCount').textContent = answeredCount;
    
    const progressPercent = (answeredCount / totalQuestions) * 100;
    document.getElementById('progressBar').style.width = progressPercent + '%';
    
    return answeredCount;
}

// Add input event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.answer-input').forEach(input => {
        input.addEventListener('input', updateProgress);
    });
});

// Form submission
document.getElementById('assessmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const answeredCount = updateProgress();
    
    Swal.fire({
        title: 'Submit Assessment?',
        html: `
            <div style="text-align: left; padding: 10px 0;">
                <p style="color: #6b7280; margin-bottom: 16px;">Please review your answers before submitting.</p>
                <div style="background: #f3f4f6; padding: 16px; border-radius: 12px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #6b7280;">Questions Answered</span>
                        <span style="font-weight: 600; color: ${answeredCount === totalQuestions ? '#10b981' : '#f59e0b'}">
                            ${answeredCount} of ${totalQuestions}
                        </span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #6b7280;">Unanswered</span>
                        <span style="font-weight: 600; color: ${totalQuestions - answeredCount > 0 ? '#ef4444' : '#10b981'}">
                            ${totalQuestions - answeredCount}
                        </span>
                    </div>
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bx bx-check me-1"></i> Submit Now',
        cancelButtonText: 'Continue Working',
        reverseButtons: true,
        customClass: {
            confirmButton: 'swal-confirm-btn',
            cancelButton: 'swal-cancel-btn'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            submitAssessment();
        }
    });
});

function submitAssessment() {
    // Clear timer
    if (timerInterval) {
        clearInterval(timerInterval);
    }
    
    // Show loading
    Swal.fire({
        title: 'Submitting...',
        html: 'Please wait while we process your assessment.',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
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
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            return response.text().then(text => {
                throw new Error('Server returned non-JSON response');
            });
        }
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Assessment Submitted!',
                html: `
                    <div style="padding: 10px 0;">
                        <p style="color: #6b7280; margin-bottom: 20px;">Your assessment has been submitted successfully.</p>
                        <div style="background: linear-gradient(135deg, #ecfdf5, #d1fae5); padding: 20px; border-radius: 12px; text-align: center;">
                            <i class='bx bx-check-circle' style="font-size: 48px; color: #10b981;"></i>
                            <p style="margin: 12px 0 0 0; font-weight: 600; color: #065f46;">Thank you for completing this assessment!</p>
                        </div>
                    </div>
                `,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Continue',
                allowOutsideClick: false
            }).then(() => {
                window.location.href = data.redirect;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: data.message || 'Please try again.',
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred. Please try again.',
            confirmButtonColor: '#ef4444'
        });
    });
}
</script>
</body>
</html>