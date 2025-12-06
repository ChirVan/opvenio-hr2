<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Learning Management System - {{ config('app.name') }}</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f5f7f6;
            font-family: 'Segoe UI', sans-serif;
        }
        .page-header {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            color: white;
            border-radius: 12px;
            padding: 25px 30px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }
        .card {
            border: none;
            border-radius: 14px;
            transition: all 0.25s ease;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.1);
        }
        .course-card { border-left: 5px solid #198754; }
        .course-card.border-warning { border-left: 5px solid #ffc107; }
        .assessment-card { border-left: 5px solid #0d6efd; }
        .grant-course-card { 
            border-left: 5px solid #fd7e14; 
            position: relative;
        }
        .grant-course-card .card-body {
            padding-bottom: 60px; /* Make room for the button */
        }
        .grant-course-card .grant-button {
            position: absolute;
            bottom: 15px;
            right: 15px;
            left: 15px;
        }
        .grant-course-card { 
            border-left: 5px solid #fd7e14; 
            position: relative;
        }
        .grant-course-card .card-body {
            padding-bottom: 60px; /* Make room for the button */
        }
        .grant-course-card .grant-button {
            position: absolute;
            bottom: 15px;
            right: 15px;
            left: 15px;
        }
        .nav-tabs .nav-link.active {
            background-color: #198754 !important;
            color: #fff !important;
            border-radius: 6px;
        }
        .nav-tabs .nav-link {
            color: #198754;
            font-weight: 500;
            border: none;
            background-color: #f1f1f1;
            margin-right: 6px;
        }
        .nav-tabs .nav-link:hover {
            background-color: #198754;
            color: white;
        }
        .modal-content { border-radius: 12px; }
        
        .course-info {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }
        .course-info i {
            color: #6c757d;
        }
        
        .difficulty-badge {
            font-size: 0.7rem;
            padding: 2px 8px;
        }
        
        .filter-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .course-info {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }
        .course-info i {
            color: #6c757d;
        }
        
        .difficulty-badge {
            font-size: 0.7rem;
            padding: 2px 8px;
        }
        
        .filter-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        /* Lesson Navigation Styles */
        .lesson-item {
            transition: all 0.2s ease;
        }
        .lesson-item:hover {
            background-color: #f8f9fa !important;
        }
        .lesson-item.active {
            background-color: #e8f5e8 !important;
            border-left: 4px solid #198754 !important;
        }
        .lesson-number .badge {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .lesson-content {
            line-height: 1.6;
            font-size: 1rem;
        }
        .lesson-content h1, .lesson-content h2, .lesson-content h3, 
        .lesson-content h4, .lesson-content h5, .lesson-content h6 {
            color: #198754;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }
        .lesson-content p {
            margin-bottom: 1rem;
            text-align: justify;
        }
    </style>

    @php
        $lessonContents = [];
        foreach ($trainingAssignments as $training) {
            $lessonContents[$training['id']] = [];
            foreach ($training['materials'] as $material) {
                $lessonContents[$training['id']][] = [
                    'title' => $material->lesson_title,
                    'content' => $material->lesson_content
                ];
            }
        }
    @endphp
    <script>
        const lessonContents = @json($lessonContents);
    </script>
</head>
<body>
    @include('layouts.ess-navbar-bootstrap')

    <div class="container-fluid py-4" style="margin-top: 80px;">
        <!-- Header -->
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">Learning Management System</h2>
                <p class="mb-0 opacity-75">Explore courses and enhance your skills</p>
            </div>
            <a href="{{ route('ess.dashboard') }}" class="btn btn-light text-success fw-semibold">
                <i class='bx bx-arrow-back me-2'></i>Back to Dashboard
            </a>
        </div>

        <!-- Tabs -->
        <div class="card mb-4">
            <div class="card-header bg-white border-0 pb-0">
                <ul class="nav nav-tabs" id="mainTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="courses-tab" data-bs-toggle="tab" data-bs-target="#courses-content" type="button" role="tab">
                            <i class='bx bx-book-open me-1'></i>My Courses
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="assessment-tab" data-bs-toggle="tab" data-bs-target="#assessment-content" type="button" role="tab">
                            <i class='bx bx-task me-1'></i>Assessment
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="course-grant-tab" data-bs-toggle="tab" data-bs-target="#course-grant-content" type="button" role="tab" onclick="loadAvailableCourses()">
                            <i class='bx bx-gift me-1'></i>Course Grant
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="mainTabsContent">
                    <!-- My Assigned Courses -->
                    <div class="tab-pane fade show active" id="courses-content" role="tabpanel">
                        <div class="row g-4">
                            @forelse($trainingAssignments as $training)
                            <div class="col-md-6 col-lg-4">
                                <div class="card course-card h-100 {{ $training['is_due_soon'] ? 'border-warning' : '' }}" style="cursor:pointer;"
                                     onclick="viewCourse({{ $training['id'] }}, '{{ addslashes($training['title']) }}', '{{ addslashes($training['description']) }}', '{{ $training['category'] }}', '{{ $training['due_date'] }}', '{{ $training['priority'] }}')">
                                    <div class="card-body">
                                        @if($training['is_due_soon'] || $training['time_until_due'] === 'Overdue')
                                        <div class="mb-2">
                                            @if($training['time_until_due'] === 'Overdue')
                                                <span class="badge bg-danger">
                                                    <i class='bx bx-exclamation-triangle me-1'></i>
                                                    Overdue!
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class='bx bx-time me-1'></i>
                                                    @if($training['days_until_due'] == 0)
                                                        Due Today! ({{ $training['time_until_due'] }})
                                                    @elseif($training['days_until_due'] == 1)
                                                        Due Tomorrow ({{ $training['time_until_due'] }})
                                                    @else
                                                        Due in {{ $training['time_until_due'] }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                        @endif
                                        
                                        <h6 class="fw-bold text-success">{{ $training['title'] }}</h6>
                                        <p class="text-muted small mb-2">{{ Str::limit($training['description'], 100) }}</p>
                                        <div class="course-info">
                                            <i class='bx bx-category'></i>
                                            <span class="small text-muted">{{ $training['category'] }}</span>
                                        </div>
                                        <div class="course-info">
                                            <i class='bx bx-calendar'></i>
                                            <span class="small text-muted {{ $training['is_due_soon'] ? 'text-warning fw-bold' : '' }}">
                                                Due: {{ $training['due_date'] ? \Carbon\Carbon::parse($training['due_date'])->format('M d, Y') : 'No due date' }}
                                            </span>
                                        </div>
                                        
                                        @if($training['progress'] > 0)
                                        <div class="mt-2">
                                            <div class="progress mb-1" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: {{ $training['progress'] }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $training['progress'] }}% Complete</small>
                                        </div>
                                        @endif
                                        
                                        <span class="badge bg-success mt-2">
                                            <i class='bx bx-play-circle me-1'></i>View Lesson
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center text-muted py-5">
                                <i class='bx bx-book-open fs-1 mb-2'></i>
                                <p>No Assigned Courses</p>
                                <small>Check the Course Grant tab to request additional training</small>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Course Detail View (Initially Hidden) -->
                    <div id="course-detail-view" style="display: none;">
                        <!-- Course Header -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h3 class="text-success mb-2" id="course-detail-title">Course Title</h3>
                                        <p class="text-muted mb-2" id="course-detail-description">Course description</p>
                                        <div class="d-flex gap-3">
                                            <small class="text-muted"><i class='bx bx-category me-1'></i><span id="course-detail-category">Category</span></small>
                                            <small class="text-muted"><i class='bx bx-time me-1'></i>Due: <span id="course-detail-due">Due date</span></small>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-success" onclick="backToCourses()">
                                        <i class='bx bx-arrow-back me-1'></i>Back to Courses
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Lessons List -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class='bx bx-list-ul me-2'></i>Course Lessons</h6>
                                    </div>
                                    <div class="card-body p-0" id="lessons-list">
                                        <!-- Lessons will be loaded here -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body" id="lesson-content-area">
                                        <div class="text-center text-muted py-5">
                                            <i class='bx bx-book-open fs-1 mb-2'></i>
                                            <h5>Select a lesson to start learning</h5>
                                            <p class="small">Choose a lesson from the list on the left to view its content.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assessments -->
                    <div class="tab-pane fade" id="assessment-content" role="tabpanel">
                        <div class="row g-4">
                            @forelse($assessmentAssignments as $assessment)
                            <div class="col-md-6 col-lg-4">
                                <div class="card assessment-card h-100">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-primary">{{ $assessment['title'] }}</h6>
                                        <p class="text-muted small mb-2">{{ Str::limit($assessment['description'] ?? 'Assessment details not available', 100) }}</p>
                                        <div class="course-info">
                                            <i class='bx bx-time'></i>
                                            <span class="small text-muted">Attempts: {{ $assessment['attempts_used'] }}/{{ $assessment['max_attempts'] }}</span>
                                        </div>
                                        <div class="course-info">
                                            <i class='bx bx-badge-check'></i>
                                            <span class="small text-muted">Status: {{ ucfirst($assessment['status']) }}</span>
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <a href="{{ route('ess.assessment.take', $assessment['id']) }}"
                                               class="btn btn-primary btn-sm{{ ($assessment['status'] === 'completed' || $assessment['attempts_used'] >= $assessment['max_attempts']) ? ' disabled' : '' }}"
                                               {{ ($assessment['status'] === 'completed' || $assessment['attempts_used'] >= $assessment['max_attempts']) ? 'tabindex="-1" aria-disabled="true"' : '' }}>
                                                <i class='bx bx-play-circle me-1'></i> 
                                                {{ $assessment['status'] === 'completed' ? 'Completed' : 'Take Assessment' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center text-muted py-5">
                                <i class='bx bx-task fs-1 mb-2'></i>
                                <p>No Assessments Available</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Course Grant - Available Courses -->
                    <div class="tab-pane fade" id="course-grant-content" role="tabpanel">
                        <!-- Filter Section -->
                        <div class="filter-section">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Search Courses</label>
                                    <input type="text" id="courseSearch" class="form-control" placeholder="Search course titles..." onkeyup="filterCourses()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Category</label>
                                    <select id="categoryFilter" class="form-select" onchange="filterCourses()">
                                        <option value="">All Categories</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Difficulty</label>
                                    <select id="difficultyFilter" class="form-select" onchange="filterCourses()">
                                        <option value="">All Levels</option>
                                        <option value="Beginner">Beginner</option>
                                        <option value="Intermediate">Intermediate</option>
                                        <option value="Advanced">Advanced</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                        <i class='bx bx-refresh me-1'></i>Clear Filters
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Available Courses Grid -->
                        <div id="availableCoursesGrid">
                            <div class="row g-4" id="coursesContainer">
                                <!-- Loading indicator -->
                                <div class="col-12 text-center py-5" id="loadingIndicator">
                                    <div class="spinner-border text-success" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="text-muted mt-2">Loading available courses...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Modal -->
    <div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="courseTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="courseDescription"></p>
                    <hr>
                    <div id="lessonContent"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assessment Modal -->
    <div class="modal fade" id="assessmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="assessmentTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="assessmentDescription"></p>
                    <p><strong>Status:</strong> <span id="assessmentStatus"></span></p>
                    <p><strong>Attempts:</strong> <span id="assessmentAttempts"></span></p>
                    <p><strong>Duration:</strong> <span id="assessmentDuration"></span></p>
                    <p><strong>Score:</strong> <span id="assessmentScore"></span></p>
                </div>
            </div>
        </div>
    </div>



    <!-- Course Details Modal for Course Grant -->
    <div class="modal fade" id="courseDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="courseDetailsTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div id="courseDetailsContent">
                                <!-- Course details and training materials will be loaded here -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">Course Information</h6>
                                </div>
                                <div class="card-body" id="courseDetailsInfo">
                                    <!-- Course info will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" id="requestCourseBtn">
                        <i class='bx bx-plus-circle me-1'></i>Request This Course
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function viewCourse(id, title, desc, category, due, priority) {
            // Hide the main courses view and show course detail view
            document.getElementById('courses-content').style.display = 'none';
            document.getElementById('course-detail-view').style.display = 'block';
            
            // Update course header
            document.getElementById('course-detail-title').innerText = title;
            document.getElementById('course-detail-description').innerText = desc;
            document.getElementById('course-detail-category').innerText = category || 'General';
            document.getElementById('course-detail-due').innerText = due || 'No due date';
            
            // Store current course info
            window.currentCourse = { id, title, desc, category, due, priority };
            
            // Show lessons list
            showLessonsList(id);
        }

        function viewAssessment(id, title, desc, status, used, max, duration, score) {
            document.getElementById('assessmentTitle').innerText = title;
            document.getElementById('assessmentDescription').innerText = desc;
            document.getElementById('assessmentStatus').innerText = status;
            document.getElementById('assessmentAttempts').innerText = `${used}/${max}`;
            document.getElementById('assessmentDuration').innerText = duration;
            document.getElementById('assessmentScore').innerText = score ?? 'N/A';

            new bootstrap.Modal(document.getElementById('courseModal')).show();
        }

        // New functions for lesson-based navigation
        function showLessonsList(courseId) {
            const lessonsList = document.getElementById('lessons-list');
            
            if (lessonContents[courseId] && lessonContents[courseId].length > 0) {
                let lessonsHtml = '';
                lessonContents[courseId].forEach((lesson, index) => {
                    lessonsHtml += `
                        <div class="lesson-item p-3 border-bottom" style="cursor: pointer;" onclick="viewLesson(${courseId}, ${index})">
                            <div class="d-flex align-items-center">
                                <div class="lesson-number me-3">
                                    <span class="badge bg-success rounded-circle">${index + 1}</span>
                                </div>
                                <div class="lesson-info flex-grow-1">
                                    <h6 class="mb-1 lesson-title">${lesson.title}</h6>
                                    <small class="text-muted">Click to view content</small>
                                </div>
                                <div class="lesson-icon">
                                    <i class='bx bx-chevron-right text-muted'></i>
                                </div>
                            </div>
                        </div>
                    `;
                });
                lessonsList.innerHTML = lessonsHtml;
            } else {
                lessonsList.innerHTML = `
                    <div class="p-3 text-center text-muted">
                        <i class='bx bx-info-circle fs-4 mb-2'></i>
                        <p class="mb-0">No lessons available</p>
                    </div>
                `;
            }
        }

        function viewLesson(courseId, lessonIndex) {
            const lesson = lessonContents[courseId][lessonIndex];
            const contentArea = document.getElementById('lesson-content-area');
            
            // Update active lesson styling
            const lessonItems = document.querySelectorAll('.lesson-item');
            lessonItems.forEach((item, index) => {
                if (index === lessonIndex) {
                    item.classList.add('bg-light');
                    item.style.borderLeft = '4px solid #198754';
                } else {
                    item.classList.remove('bg-light');
                    item.style.borderLeft = 'none';
                }
            });
            
            // Display lesson content
            contentArea.innerHTML = `
                <div class="lesson-header mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-success me-2">${lessonIndex + 1}</span>
                        <h4 class="text-success mb-0">${lesson.title}</h4>
                    </div>
                    <hr>
                </div>
                <div class="lesson-content">
                    ${lesson.content.replace(/\n/g, '<br>')}
                </div>
                <div class="lesson-navigation mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-outline-success" onclick="navigateLesson(${courseId}, ${lessonIndex - 1})" ${lessonIndex === 0 ? 'disabled' : ''}>
                            <i class='bx bx-chevron-left me-1'></i>Previous Lesson
                        </button>
                        <button class="btn btn-success" onclick="navigateLesson(${courseId}, ${lessonIndex + 1})" ${lessonIndex === lessonContents[courseId].length - 1 ? 'disabled' : ''}>
                            Next Lesson<i class='bx bx-chevron-right ms-1'></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Scroll to top of content area
            contentArea.scrollTop = 0;
        }

        function navigateLesson(courseId, lessonIndex) {
            if (lessonIndex >= 0 && lessonIndex < lessonContents[courseId].length) {
                viewLesson(courseId, lessonIndex);
            }
        }

        function backToCourses() {
            document.getElementById('course-detail-view').style.display = 'none';
            document.getElementById('courses-content').style.display = 'block';
        }

        // Course Grant functionality
        let availableCourses = [];
        let filteredCourses = [];
        let categories = [];

        // Load available courses from training catalog
        function loadAvailableCourses() {
            if (availableCourses.length > 0) return; // Already loaded

            fetch('/ess/training-catalog/available-courses', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                availableCourses = data.courses || [];
                categories = data.categories || [];
                filteredCourses = [...availableCourses];
                
                populateCategoryFilter();
                displayCourses();
            })
            .catch(error => {
                console.error('Error loading courses:', error);
                document.getElementById('loadingIndicator').innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class='bx bx-error fs-1 mb-2'></i>
                        <p>Failed to load available courses</p>
                        <button class="btn btn-outline-warning btn-sm" onclick="loadAvailableCourses()">
                            <i class='bx bx-refresh me-1'></i>Retry
                        </button>
                    </div>
                `;
            });
        }

        // Populate category filter dropdown
        function populateCategoryFilter() {
            const categorySelect = document.getElementById('categoryFilter');
            categorySelect.innerHTML = '<option value="">All Categories</option>';
            
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                categorySelect.appendChild(option);
            });
        }

        // Display courses in grid
        function displayCourses() {
            const container = document.getElementById('coursesContainer');
            
            if (filteredCourses.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center text-muted py-5">
                        <i class='bx bx-search fs-1 mb-2'></i>
                        <p>No courses found</p>
                        <small>Try adjusting your search filters</small>
                    </div>
                `;
                return;
            }

            let coursesHtml = '';
            filteredCourses.forEach(course => {
                const difficultyColor = {
                    'Beginner': 'success',
                    'Intermediate': 'warning',
                    'Advanced': 'danger'
                }[course.difficulty_level] || 'secondary';

                // Determine request status styling
                let requestStatusBadge = '';
                if (course.request_status === 'pending') {
                    requestStatusBadge = '<span class="badge bg-warning text-dark mb-2"><i class="bx bx-clock me-1"></i>Request Pending</span>';
                } else if (course.request_status === 'approved') {
                    requestStatusBadge = '<span class="badge bg-success mb-2"><i class="bx bx-check me-1"></i>Request Approved</span>';
                }

                coursesHtml += `
                    <div class="col-md-6 col-lg-4">
                        <div class="card grant-course-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold text-warning mb-0">${course.title}</h6>
                                    ${requestStatusBadge}
                                </div>
                                <p class="text-muted small mb-2">${course.description ? course.description.substring(0, 120) + '...' : 'No description available'}</p>
                                
                                <div class="course-info">
                                    <i class='bx bx-category'></i>
                                    <span class="small text-muted">${course.category || 'Uncategorized'}</span>
                                </div>
                                
                                <div class="course-info">
                                    <i class='bx bx-time'></i>
                                    <span class="small text-muted">${course.duration || 'Self-paced'}</span>
                                </div>
                                
                                <div class="course-info">
                                    <i class='bx bx-medal'></i>
                                    <span class="badge difficulty-badge bg-${difficultyColor}">${course.difficulty_level || 'Not specified'}</span>
                                </div>
                                
                                <div class="grant-button">
                                    <button type="button" class="btn btn-warning btn-sm w-100" 
                                            onclick="viewCourseDetails(${course.id}, '${course.title.replace(/'/g, "\\'")}')">
                                        <i class='bx bx-info-circle me-1'></i>View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = coursesHtml;
        }

        // Filter courses based on search and filters
        function filterCourses() {
            const searchTerm = document.getElementById('courseSearch').value.toLowerCase();
            const categoryFilter = document.getElementById('categoryFilter').value;
            const difficultyFilter = document.getElementById('difficultyFilter').value;

            filteredCourses = availableCourses.filter(course => {
                const matchesSearch = !searchTerm || course.title.toLowerCase().includes(searchTerm) || 
                                    (course.description && course.description.toLowerCase().includes(searchTerm));
                const matchesCategory = !categoryFilter || course.category === categoryFilter;
                const matchesDifficulty = !difficultyFilter || course.difficulty_level === difficultyFilter;

                return matchesSearch && matchesCategory && matchesDifficulty;
            });

            displayCourses();
        }

        // Clear all filters
        function clearFilters() {
            document.getElementById('courseSearch').value = '';
            document.getElementById('categoryFilter').value = '';
            document.getElementById('difficultyFilter').value = '';
            filteredCourses = [...availableCourses];
            displayCourses();
        }

        // View course details in modal
        function viewCourseDetails(courseId, courseTitle) {
            const course = availableCourses.find(c => c.id === courseId);
            if (!course) return;

            // Set modal title
            document.getElementById('courseDetailsTitle').textContent = course.title;
            
            // Show loading state
            document.getElementById('courseDetailsContent').innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading training materials...</span>
                    </div>
                    <p class="text-muted mt-2">Loading course content...</p>
                </div>
            `;

            // Set course info sidebar
            document.getElementById('courseDetailsInfo').innerHTML = `
                <div class="mb-3">
                    <small class="text-muted">Category</small>
                    <div class="fw-semibold">${course.category || 'General Training'}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Framework</small>
                    <div class="fw-semibold">${course.difficulty_level || 'Not specified'}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Duration</small>
                    <div class="fw-semibold">${course.duration || 'Self-paced'}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Learning Objectives</small>
                    <div class="small">${course.learning_objectives || 'Develop skills and knowledge in this area.'}</div>
                </div>
            `;

            // Set up request button based on request status
            const requestBtn = document.getElementById('requestCourseBtn');
            if (requestBtn) {
                console.log('Setting up request button for course:', courseId, course.title, 'Status:', course.request_status);
                
                if (course.request_status === 'pending') {
                    // Request is pending
                    requestBtn.className = 'btn btn-warning';
                    requestBtn.innerHTML = '<i class="bx bx-clock me-1"></i>Request Pending';
                    requestBtn.disabled = true;
                    requestBtn.onclick = null;
                } else if (course.request_status === 'approved') {
                    // Request is approved
                    requestBtn.className = 'btn btn-success';
                    requestBtn.innerHTML = '<i class="bx bx-check me-1"></i>Request Approved';
                    requestBtn.disabled = true;
                    requestBtn.onclick = null;
                } else {
                    // No request yet - allow requesting
                    requestBtn.className = 'btn btn-warning';
                    requestBtn.innerHTML = '<i class="bx bx-plus-circle me-1"></i>Request This Course';
                    requestBtn.disabled = false;
                    requestBtn.onclick = () => {
                        console.log('Request button clicked for course:', courseId);
                        requestCourse(courseId, course.title);
                    };
                }
            } else {
                console.error('Request button not found!');
            }
            
            // Show modal
            new bootstrap.Modal(document.getElementById('courseDetailsModal')).show();

            // Load training materials for this course
            loadTrainingMaterials(courseId);
        }

        // Load training materials for a specific course
        function loadTrainingMaterials(courseId) {
            console.log('Loading training materials for course ID:', courseId);
            
            fetch(`/ess/training-catalog/${courseId}/materials`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('API Response:', data);
                if (data.success && data.materials && data.materials.length > 0) {
                    displayTrainingMaterials(data.materials);
                } else {
                    document.getElementById('courseDetailsContent').innerHTML = `
                        <div class="text-center py-4">
                            <i class='bx bx-info-circle fs-1 text-muted mb-2'></i>
                            <p class="text-muted">No training materials available for this course yet.</p>
                            <small class="text-muted">(Course ID: ${courseId})</small>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading training materials:', error);
                document.getElementById('courseDetailsContent').innerHTML = `
                    <div class="text-center py-4">
                        <i class='bx bx-error fs-1 text-danger mb-2'></i>
                        <p class="text-muted">Failed to load course materials</p>
                        <button class="btn btn-outline-warning btn-sm" onclick="loadTrainingMaterials(${courseId})">
                            <i class='bx bx-refresh me-1'></i>Retry
                        </button>
                    </div>
                `;
            });
        }

        // Display training materials
        function displayTrainingMaterials(materials) {
            if (!materials || materials.length === 0) {
                document.getElementById('courseDetailsContent').innerHTML = `
                    <div class="text-center py-4">
                        <i class='bx bx-info-circle fs-1 text-muted mb-2'></i>
                        <p class="text-muted">No training materials available for this course yet.</p>
                    </div>
                `;
                return;
            }

            let materialsHtml = `
                <div class="mb-3">
                    <h6 class="text-primary">Course Content</h6>
                    <p class="text-muted small">This course includes ${materials.length} training material(s):</p>
                </div>
            `;

            materials.forEach((material, index) => {
                materialsHtml += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">
                                    <span class="badge bg-primary me-2">${index + 1}</span>
                                    ${material.lesson_title}
                                </h6>
                                <span class="badge bg-info">${material.status}</span>
                            </div>
                            <p class="card-text text-muted small">${material.content_excerpt || 'No description available'}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <span class="badge bg-success bg-opacity-10 text-success">${material.proficiency_level}</span>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">${material.competency_name}</span>
                                    <span class="badge bg-warning bg-opacity-10 text-warning">${material.framework_name}</span>
                                </div>
                                <small class="text-muted">Added ${material.created_at}</small>
                            </div>
                        </div>
                    </div>
                `;
            });

            document.getElementById('courseDetailsContent').innerHTML = materialsHtml;
        }

        // Request access to a course
        function requestCourse(courseId, courseTitle) {
            console.log('requestCourse called with:', courseId, courseTitle);
            Swal.fire({
                title: 'Request Course Access',
                html: `
                    <div class="text-start">
                        <p>Do you want to request access to:</p>
                        <div class="alert alert-warning">
                            <h6 class="fw-bold mb-1">${courseTitle}</h6>
                            <small class="text-muted">This will grant you access to all training materials in this course</small>
                        </div>
                        <label for="requestReason" class="form-label">Reason for request (optional):</label>
                        <textarea id="requestReason" class="form-control" rows="3" placeholder="Why do you want to take this course? How will it help your role?"></textarea>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#fd7e14',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bx bx-send me-1"></i>Send Request',
                cancelButtonText: 'Cancel',
                width: '500px',
                preConfirm: () => {
                    return document.getElementById('requestReason').value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Submitting Request...',
                        html: 'Please wait while we process your course request.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Send course request
                    console.log('Sending course request:', { course_id: courseId, reason: result.value });
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    console.log('CSRF Token:', csrfToken);
                    
                    fetch('/ess/training-catalog/request-course', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            course_id: courseId,
                            reason: result.value || ''
                        })
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Request Sent Successfully!',
                                html: `
                                    <p>Your course access request has been submitted.</p>
                                    <div class="alert alert-info">
                                        <small><strong>Request ID:</strong> ${data.request_id}</small><br>
                                        <small><strong>Status:</strong> Pending Review</small>
                                    </div>
                                    <p class="small text-muted">You will be notified once your request is reviewed by the training team.</p>
                                `,
                                confirmButtonColor: '#198754',
                                confirmButtonText: 'Got it!'
                            }).then(() => {
                                // Update the course status in local data
                                const course = availableCourses.find(c => c.id === courseId);
                                if (course) {
                                    course.request_status = 'pending';
                                    course.can_request = false;
                                }
                                
                                // Refresh the courses display
                                displayCourses();
                                
                                // Close the course details modal
                                bootstrap.Modal.getInstance(document.getElementById('courseDetailsModal')).hide();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Request Failed',
                                text: data.message || 'Failed to submit course request. Please try again.',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error requesting course:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Request Failed',
                            html: `
                                <p>An error occurred while submitting your request.</p>
                                <p class="small text-muted">Please try again or contact IT support if the problem persists.</p>
                            `,
                            confirmButtonColor: '#dc3545'
                        });
                    });
                }
            });
        }
    </script>
</body>
</html>
