<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Learning Management System - {{ config('app.name') }}</title>
    
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
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
        }
        .course-card {
            border-left: 4px solid #198754;
        }
        .progress-bar {
            background-color: #198754;
        }
        .badge-success {
            background-color: #198754;
        }
        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }
        .btn-success:hover {
            background-color: #157347;
            border-color: #146c43;
        }
    </style>
</head>
<body>
    @include('layouts.ess-navbar-bootstrap')

    <div class="container-fluid py-4" style="margin-top: 80px;">
        
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">Learning Management System</h2>
                        <p class="text-muted mb-0">Explore courses and enhance your skills</p>
                    </div>
                    <a href="{{ route('ess.dashboard') }}" class="btn btn-outline-success">
                        <i class='bx bx-arrow-back me-2'></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Learning Progress Overview -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class='bx bx-book-open fs-1 text-success'></i>
                        </div>
                        <h4 class="mb-1">{{ $learningStats['total_courses'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Total Courses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class='bx bx-play-circle fs-1 text-primary'></i>
                        </div>
                        <h4 class="mb-1">{{ $learningStats['in_progress_courses'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">In Progress</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class='bx bx-check-circle fs-1 text-success'></i>
                        </div>
                        <h4 class="mb-1">{{ $learningStats['completed_courses'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class='bx bx-trophy fs-1 text-warning'></i>
                        </div>
                        <h4 class="mb-1">{{ $learningStats['certificates'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Certificates</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Tabs: Courses & Assessment -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <ul class="nav nav-tabs card-header-tabs" id="mainTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="courses-tab" data-bs-toggle="tab" data-bs-target="#courses-content" type="button" role="tab" aria-controls="courses-content" aria-selected="true">
                                    <i class='bx bx-book-open me-2'></i>Courses
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="assessment-tab" data-bs-toggle="tab" data-bs-target="#assessment-content" type="button" role="tab" aria-controls="assessment-content" aria-selected="false">
                                    <i class='bx bx-task me-2'></i>Assessment
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="mainTabsContent">
                            <!-- Courses Tab Content -->
                            <div class="tab-pane fade show active" id="courses-content" role="tabpanel" aria-labelledby="courses-tab">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-2">
                                        <button class="btn btn-success w-100 course-filter active" data-category="all">All Courses</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-success w-100 course-filter" data-category="hr">HR Policies</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-success w-100 course-filter" data-category="safety">Safety Training</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-success w-100 course-filter" data-category="skills">Skills Development</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-success w-100 course-filter" data-category="compliance">Compliance</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-success w-100 course-filter" data-category="leadership">Leadership</button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Assessment Tab Content -->
                            <div class="tab-pane fade" id="assessment-content" role="tabpanel" aria-labelledby="assessment-tab">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-2">
                                        <button class="btn btn-primary w-100 assessment-filter active" data-category="all">All Assessments</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-primary w-100 assessment-filter" data-category="skills">Skills Test</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-primary w-100 assessment-filter" data-category="knowledge">Knowledge Check</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-primary w-100 assessment-filter" data-category="certification">Certification</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-primary w-100 assessment-filter" data-category="performance">Performance</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-outline-primary w-100 assessment-filter" data-category="feedback">Feedback</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" id="content-title">Available Courses</h5>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="sort-select" style="width: auto;">
                                <option>Sort by: Latest</option>
                                <option>Sort by: Name</option>
                                <option>Sort by: Progress</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Courses Content -->
                        <div id="courses-grid" class="row g-4">
                            @forelse($trainingAssignments as $training)
                            <div class="col-md-6 col-lg-4">
                                <div class="card course-card h-100" style="cursor: pointer;" onclick="viewCourse({{ $training['id'] }}, '{{ addslashes($training['title']) }}', '{{ addslashes($training['description']) }}', '{{ $training['category'] }}', '{{ $training['due_date'] }}', '{{ $training['priority'] }}')">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="p-2 bg-success bg-opacity-10 rounded me-3">
                                                <i class='bx bx-book-open fs-5 text-success'></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="card-title mb-1">{{ $training['title'] }}</h6>
                                                <small class="text-muted">{{ $training['category'] }}</small>
                                            </div>
                                            @if($training['priority'] === 'urgent')
                                                <span class="badge bg-danger">Urgent</span>
                                            @elseif($training['priority'] === 'high')
                                                <span class="badge bg-warning">High Priority</span>
                                            @else
                                                <span class="badge bg-info">Normal</span>
                                            @endif
                                        </div>
                                        <p class="card-text small text-muted mb-3">{{ Str::limit($training['description'], 100) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                @if($training['due_date'])
                                                    <i class='bx bx-calendar me-1'></i>Due: {{ \Carbon\Carbon::parse($training['due_date'])->format('M d') }}
                                                @else
                                                    <i class='bx bx-time me-1'></i>{{ $training['duration'] }}
                                                @endif
                                            </small>
                                            <span class="badge bg-success">
                                                <i class='bx bx-play-circle me-1'></i>View Lesson
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class='bx bx-book-open fs-1 text-muted mb-3'></i>
                                    <h5 class="text-muted">No Training Assignments</h5>
                                    <p class="text-muted">You don't have any training assignments at the moment.</p>
                                </div>
                            </div>
                            @endforelse
                        </div>
                        
                        <!-- Assessment Content (Hidden by default) -->
                        <div id="assessment-grid" class="row g-4 d-none">
                            @forelse($assessmentAssignments as $assessment)
                            <div class="col-md-6 col-lg-4">
                                <div class="card course-card h-100" style="cursor: pointer; border-left-color: #0d6efd;" onclick="viewAssessment({{ $assessment['id'] }}, '{{ addslashes($assessment['title']) }}', '{{ addslashes($assessment['description'] ?? 'Complete this assessment to test your knowledge.') }}', '{{ $assessment['status'] }}', {{ $assessment['attempts_used'] }}, {{ $assessment['max_attempts'] }}, '{{ $assessment['duration'] }}', {{ $assessment['score'] ?? 'null' }})">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="p-2 bg-primary bg-opacity-10 rounded me-3">
                                                <i class='bx bx-task fs-5 text-primary'></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="card-title mb-1">{{ $assessment['title'] }}</h6>
                                                <small class="text-muted">Assessment</small>
                                            </div>
                                            @if($assessment['status'] === 'published')
                                                <span class="badge bg-success">Available</span>
                                            @elseif($assessment['status'] === 'draft')
                                                <span class="badge bg-warning">Draft</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </div>
                                        <p class="card-text small text-muted mb-3">{{ Str::limit($assessment['description'] ?? 'Complete this assessment to test your knowledge.', 100) }}</p>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class='bx bx-refresh me-1'></i>Attempts: {{ $assessment['attempts_used'] }}/{{ $assessment['max_attempts'] }}
                                                @if($assessment['score'])
                                                    | Score: {{ $assessment['score'] }}%
                                                @endif
                                            </small>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class='bx bx-time me-1'></i>{{ $assessment['duration'] ?? '30 min' }}
                                            </small>
                                            @if($assessment['attempts_used'] >= $assessment['max_attempts'] && $assessment['status'] !== 'completed')
                                                <span class="badge bg-secondary">
                                                    <i class='bx bx-x-circle me-1'></i>Max Attempts
                                                </span>
                                            @elseif($assessment['status'] === 'completed')
                                                <span class="badge bg-success">
                                                    <i class='bx bx-check-circle me-1'></i>Completed
                                                </span>
                                            @else
                                                <span class="badge bg-primary">
                                                    <i class='bx bx-play-circle me-1'></i>Take Quiz
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class='bx bx-task fs-1 text-muted mb-3'></i>
                                    <h5 class="text-muted">No Assessment Assignments</h5>
                                    <p class="text-muted">You don't have any assessment assignments at the moment.</p>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Tab switching functionality
        document.getElementById('courses-tab').addEventListener('click', function() {
            document.getElementById('content-title').textContent = 'Available Courses';
            document.getElementById('courses-grid').classList.remove('d-none');
            document.getElementById('assessment-grid').classList.add('d-none');
            
            // Update sort options
            const sortSelect = document.getElementById('sort-select');
            sortSelect.innerHTML = `
                <option>Sort by: Latest</option>
                <option>Sort by: Name</option>
                <option>Sort by: Progress</option>
            `;
        });

        document.getElementById('assessment-tab').addEventListener('click', function() {
            document.getElementById('content-title').textContent = 'Available Assessments';
            document.getElementById('courses-grid').classList.add('d-none');
            document.getElementById('assessment-grid').classList.remove('d-none');
            
            // Update sort options for assessments
            const sortSelect = document.getElementById('sort-select');
            sortSelect.innerHTML = `
                <option>Sort by: Due Date</option>
                <option>Sort by: Name</option>
                <option>Sort by: Status</option>
            `;
        });

        // Course filter functionality
        document.querySelectorAll('.course-filter').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all course filter buttons
                document.querySelectorAll('.course-filter').forEach(btn => {
                    btn.classList.remove('btn-success', 'active');
                    btn.classList.add('btn-outline-success');
                });
                
                // Add active class to clicked button
                this.classList.remove('btn-outline-success');
                this.classList.add('btn-success', 'active');
            });
        });

        // Assessment filter functionality
        document.querySelectorAll('.assessment-filter').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all assessment filter buttons
                document.querySelectorAll('.assessment-filter').forEach(btn => {
                    btn.classList.remove('btn-primary', 'active');
                    btn.classList.add('btn-outline-primary');
                });
                
                // Add active class to clicked button
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary', 'active');
            });
        });
        
        // Course and Assessment viewing functions
        function viewCourse(id, title, description, category, dueDate, priority) {
            document.getElementById('courseModalLabel').textContent = title;
            document.getElementById('courseDescription').textContent = description;
            document.getElementById('courseCategoryBadge').textContent = category;
            document.getElementById('coursePriorityBadge').textContent = priority + ' Priority';
            
            if (dueDate && dueDate !== '') {
                document.getElementById('courseDueDateSection').style.display = 'block';
                document.getElementById('courseDueDate').textContent = new Date(dueDate).toLocaleDateString();
            } else {
                document.getElementById('courseDueDateSection').style.display = 'none';
            }
            
            // Show the modal
            new bootstrap.Modal(document.getElementById('courseModal')).show();
        }
        
        function viewAssessment(id, title, description, status, attemptsUsed, maxAttempts, duration, score) {
            document.getElementById('assessmentModalLabel').textContent = title;
            document.getElementById('assessmentDescription').textContent = description;
            document.getElementById('assessmentStatusBadge').textContent = status;
            
            // Update attempt information
            document.getElementById('assessmentAttempts').textContent = attemptsUsed + '/' + maxAttempts;
            document.getElementById('assessmentDuration').textContent = duration;
            
            // Update score if available
            const scoreElement = document.getElementById('assessmentScore');
            if (score && score !== 'null') {
                scoreElement.textContent = score + '%';
                document.getElementById('scoreSection').style.display = 'block';
            } else {
                document.getElementById('scoreSection').style.display = 'none';
            }
            
            // Update button based on attempts and status
            const startButton = document.getElementById('startAssessmentBtn');
            if (attemptsUsed >= maxAttempts && status !== 'completed') {
                startButton.textContent = 'Max Attempts Reached';
                startButton.className = 'btn btn-secondary';
                startButton.disabled = true;
            } else if (status === 'completed') {
                startButton.textContent = 'View Results';
                startButton.className = 'btn btn-success';
                startButton.disabled = false;
            } else {
                startButton.textContent = attemptsUsed > 0 ? 'Retake Assessment' : 'Start Assessment';
                startButton.className = 'btn btn-primary';
                startButton.disabled = false;
            }
            
            // Store assessment ID for taking quiz
            startButton.setAttribute('data-assessment-id', id);
            
            // Show the modal
            new bootstrap.Modal(document.getElementById('assessmentModal')).show();
        }
        
        function takeAssessment(assessmentId) {
            // Redirect to quiz taking page
            window.location.href = '/ess/assessment/' + assessmentId + '/take';
        }
    </script>
    
    <!-- Course Content Modal -->
    <div class="modal fade" id="courseModal" tabindex="-1" aria-labelledby="courseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="courseModalLabel">Course Content</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="badge bg-secondary" id="courseCategoryBadge"></span>
                        <span class="badge bg-warning ms-2" id="coursePriorityBadge"></span>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-muted">Course Description</h6>
                        <p id="courseDescription"></p>
                    </div>
                    <div class="mb-4" id="courseDueDateSection">
                        <h6 class="text-muted">Due Date</h6>
                        <p><i class='bx bx-calendar me-2'></i><span id="courseDueDate"></span></p>
                    </div>
                    <div class="border rounded p-4 bg-light">
                        <h6 class="mb-3"><i class='bx bx-book-open me-2'></i>Lesson Content</h6>
                        <div id="lessonContent">
                            <p><strong>Introduction to the Course</strong></p>
                            <p>This training module covers essential concepts and practical applications in your designated field. The course is designed to enhance your professional skills and knowledge base.</p>
                            
                            <h6 class="mt-4 mb-2">Learning Objectives:</h6>
                            <ul>
                                <li>Understand core principles and concepts</li>
                                <li>Apply knowledge to real-world scenarios</li>
                                <li>Develop practical skills for workplace implementation</li>
                                <li>Meet compliance and certification requirements</li>
                            </ul>
                            
                            <h6 class="mt-4 mb-2">Course Materials:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-2">
                                        <div class="card-body py-2">
                                            <i class='bx bx-file-blank me-2'></i>Module 1: Introduction
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-2">
                                        <div class="card-body py-2">
                                            <i class='bx bx-video me-2'></i>Video Tutorial
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-2">
                                        <div class="card-body py-2">
                                            <i class='bx bx-file-blank me-2'></i>Module 2: Practical Application
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-2">
                                        <div class="card-body py-2">
                                            <i class='bx bx-download me-2'></i>Reference Materials
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success">Mark as Completed</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Assessment Content Modal -->
    <div class="modal fade" id="assessmentModal" tabindex="-1" aria-labelledby="assessmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="assessmentModalLabel">Assessment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="badge bg-info" id="assessmentStatusBadge"></span>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-muted">Assessment Description</h6>
                        <p id="assessmentDescription"></p>
                    </div>
                    <div id="scoreSection" class="mb-4" style="display: none;">
                        <div class="alert alert-success">
                            <i class='bx bx-trophy me-2'></i>
                            <strong>Previous Score:</strong> <span id="assessmentScore"></span>
                        </div>
                    </div>
                    <div class="border rounded p-4 bg-light">
                        <h6 class="mb-3"><i class='bx bx-task me-2'></i>Assessment Overview</h6>
                        <div id="assessmentContent">
                            <p><strong>Assessment Instructions</strong></p>
                            <p>This assessment is designed to evaluate your understanding of the training material. Please read each question carefully and select the best answer.</p>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <i class='bx bx-time fs-2 text-primary'></i>
                                            <h6 class="mt-2">Duration</h6>
                                            <p class="text-muted" id="assessmentDuration">30 minutes</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <i class='bx bx-list-ol fs-2 text-success'></i>
                                            <h6 class="mt-2">Questions</h6>
                                            <p class="text-muted">15 questions</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <i class='bx bx-refresh fs-2 text-warning'></i>
                                            <h6 class="mt-2">Attempts</h6>
                                            <p class="text-muted" id="assessmentAttempts">0/3</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class='bx bx-info-circle me-2'></i>
                                <strong>Important:</strong> Make sure you understand the material before starting. Once you begin, the timer will start counting down.
                            </div>
                    
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="startAssessmentBtn" onclick="takeAssessment(this.getAttribute('data-assessment-id'))">Start Assessment</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
