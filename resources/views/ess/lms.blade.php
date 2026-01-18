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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #d1fae5;
            --secondary: #64748b;
            --dark: #1e293b;
            --light: #f8fafc;
            --blue: #3b82f6;
            --blue-light: #dbeafe;
            --orange: #f59e0b;
            --orange-light: #fef3c7;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.1);
            --card-shadow-hover: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04);
        }

        body {
            background-color: var(--light);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--dark);
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 16px;
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.25);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .page-header p {
            opacity: 0.9;
            font-size: 0.875rem;
            margin-bottom: 0;
        }

        .btn-back {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }

        /* Tab Navigation */
        .tab-card {
            background: white;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .nav-tabs-custom {
            border: none;
            background: #f8fafc;
            padding: 0.75rem;
            gap: 0.5rem;
            display: flex;
            flex-wrap: wrap;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--secondary);
            background: white;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-tabs-custom .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .nav-tabs-custom .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .nav-tabs-custom .nav-link i {
            font-size: 1rem;
        }

        .tab-body {
            padding: 1.25rem;
        }

        /* Course Cards */
        .course-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,0,0,0.04);
            transition: all 0.2s ease;
            overflow: hidden;
            height: 100%;
            cursor: pointer;
        }

        .course-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
            border-color: var(--primary-light);
        }

        .course-card .card-body {
            padding: 1rem;
        }

        .course-card.due-soon {
            border-left: 3px solid var(--orange);
        }

        .course-card.assessment {
            border-left: 3px solid var(--blue);
        }

        .course-card.grant {
            border-left: 3px solid var(--orange);
        }

        .course-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .course-desc {
            font-size: 0.75rem;
            color: var(--secondary);
            margin-bottom: 0.75rem;
            line-height: 1.5;
        }

        .course-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.7rem;
            color: var(--secondary);
        }

        .course-meta i {
            font-size: 0.85rem;
            color: var(--secondary);
        }

        /* Badges */
        .badge-status {
            font-size: 0.65rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }

        .badge-due {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-overdue {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-success {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .badge-primary {
            background: var(--blue-light);
            color: #1d4ed8;
        }

        .badge-difficulty {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }

        /* Progress Bar */
        .progress-mini {
            height: 4px;
            border-radius: 2px;
            background: #e2e8f0;
            margin-top: 0.75rem;
        }

        .progress-mini .progress-bar {
            background: var(--primary);
            border-radius: 2px;
        }

        .progress-text {
            font-size: 0.65rem;
            color: var(--secondary);
            margin-top: 0.25rem;
        }

        /* View Button */
        .btn-view {
            font-size: 0.7rem;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            background: var(--primary);
            color: white;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.75rem;
            text-decoration: none;
        }

        .btn-view:hover {
            background: var(--primary-dark);
            color: white;
        }

        .btn-view.assessment {
            background: var(--blue);
        }

        .btn-view.assessment:hover {
            background: #2563eb;
        }

        .btn-view.grant {
            background: var(--orange);
        }

        .btn-view.grant:hover {
            background: #d97706;
        }

        .btn-view.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        /* Filter Section */
        .filter-section {
            background: #f8fafc;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .filter-section label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.35rem;
        }

        .filter-section .form-control,
        .filter-section .form-select {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .filter-section .form-control:focus,
        .filter-section .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .btn-clear-filter {
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--secondary);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .empty-state small {
            font-size: 0.75rem;
        }

        /* Course Detail View */
        .course-detail-header {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,0,0,0.04);
            margin-bottom: 1rem;
        }

        .course-detail-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .course-detail-desc {
            font-size: 0.85rem;
            color: var(--secondary);
            margin-bottom: 0.75rem;
        }

        .course-detail-meta {
            display: flex;
            gap: 1.5rem;
            font-size: 0.75rem;
            color: var(--secondary);
        }

        .btn-back-courses {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }

        .btn-back-courses:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        /* Lessons List */
        .lessons-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .lessons-header {
            background: var(--primary);
            color: white;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .lesson-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .lesson-item:last-child {
            border-bottom: none;
        }

        .lesson-item:hover {
            background: #f8fafc;
        }

        .lesson-item.active {
            background: var(--primary-light);
            border-left: 3px solid var(--primary);
        }

        .lesson-number {
            width: 24px;
            height: 24px;
            background: var(--primary);
            color: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .lesson-title {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 0;
        }

        .lesson-subtitle {
            font-size: 0.65rem;
            color: var(--secondary);
        }

        /* Lesson Content */
        .lesson-content-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,0,0,0.04);
        }

        .lesson-content-body {
            padding: 1.25rem;
        }

        .lesson-content-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .lesson-content-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin: 0;
        }

        .lesson-text {
            font-size: 0.9rem;
            line-height: 1.7;
            color: var(--dark);
        }

        .lesson-text h1, .lesson-text h2, .lesson-text h3,
        .lesson-text h4, .lesson-text h5, .lesson-text h6 {
            color: var(--primary-dark);
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .lesson-nav {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
        }

        .btn-lesson-nav {
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 14px;
            border: none;
        }

        .modal-header {
            border-radius: 14px 14px 0 0;
            padding: 1rem 1.25rem;
        }

        .modal-header.bg-warning {
            background: linear-gradient(135deg, var(--orange) 0%, #d97706 100%) !important;
            color: white;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .modal-body {
            padding: 1.25rem;
        }

        .modal-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid #f1f5f9;
        }

        /* Course Info Sidebar */
        .course-info-card {
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .course-info-header {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .course-info-body {
            padding: 1rem;
        }

        .course-info-item {
            margin-bottom: 1rem;
        }

        .course-info-item:last-child {
            margin-bottom: 0;
        }

        .course-info-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--secondary);
            margin-bottom: 0.25rem;
        }

        .course-info-value {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--dark);
        }

        /* Material Cards */
        .material-card {
            background: white;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 1rem;
            margin-bottom: 0.75rem;
        }

        .material-card:last-child {
            margin-bottom: 0;
        }

        .material-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .material-desc {
            font-size: 0.75rem;
            color: var(--secondary);
            margin-bottom: 0.75rem;
        }

        .material-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
        }

        .material-badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.25rem;
            }
            .page-header h2 {
                font-size: 1.25rem;
            }
            .tab-body {
                padding: 1rem;
            }
            .filter-section {
                padding: 0.75rem;
            }
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

    <div class="container py-4" style="margin-top: 76px; max-width: 1200px;">
        <!-- Header -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Learning Management</h2>
                    <p>Explore courses and enhance your skills</p>
                </div>
                <a href="{{ route('ess.dashboard') }}" class="btn btn-back">
                    <i class='bx bx-arrow-back me-1'></i>Back
                </a>
            </div>
        </div>

        <!-- Main Tab Card -->
        <div class="tab-card">
            <div class="nav-tabs-custom" id="mainTabs" role="tablist">
                <button class="nav-link active" id="courses-tab" data-bs-toggle="tab" data-bs-target="#courses-content" type="button" role="tab">
                    <i class='bx bx-book-open'></i>My Courses
                </button>
                <button class="nav-link" id="assessment-tab" data-bs-toggle="tab" data-bs-target="#assessment-content" type="button" role="tab">
                    <i class='bx bx-task'></i>Assessment
                </button>
                <button class="nav-link" id="course-grant-tab" data-bs-toggle="tab" data-bs-target="#course-grant-content" type="button" role="tab" onclick="loadAvailableCourses()">
                    <i class='bx bx-gift'></i>Course Grant
                </button>
            </div>

            <div class="tab-body">
                <div class="tab-content" id="mainTabsContent">
                    <!-- My Assigned Courses -->
                    <div class="tab-pane fade show active" id="courses-content" role="tabpanel">
                        <div class="row g-3">
                            @forelse($trainingAssignments as $training)
                            <div class="col-md-6 col-lg-4">
                                <div class="course-card {{ $training['is_due_soon'] ? 'due-soon' : '' }}"
                                     onclick="viewCourse({{ $training['id'] }}, '{{ addslashes($training['title']) }}', '{{ addslashes($training['description']) }}', '{{ $training['category'] }}', '{{ $training['due_date'] }}', '{{ $training['priority'] }}')">
                                    <div class="card-body">
                                        @if($training['is_due_soon'] || $training['time_until_due'] === 'Overdue')
                                        <div class="mb-2">
                                            @if($training['time_until_due'] === 'Overdue')
                                                <span class="badge-status badge-overdue">
                                                    <i class='bx bx-error-circle me-1'></i>Overdue
                                                </span>
                                            @else
                                                <span class="badge-status badge-due">
                                                    <i class='bx bx-time me-1'></i>
                                                    @if($training['days_until_due'] == 0)
                                                        Due Today
                                                    @elseif($training['days_until_due'] == 1)
                                                        Due Tomorrow
                                                    @else
                                                        Due in {{ $training['time_until_due'] }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                        @endif
                                        
                                        <h6 class="course-title">{{ $training['title'] }}</h6>
                                        <p class="course-desc">{{ Str::limit($training['description'], 80) }}</p>
                                        
                                        <div class="course-meta">
                                            <i class='bx bx-category'></i>
                                            <span>{{ $training['category'] }}</span>
                                        </div>
                                        <div class="course-meta">
                                            <i class='bx bx-calendar'></i>
                                            <span>Due: {{ $training['due_date'] ? \Carbon\Carbon::parse($training['due_date'])->format('M d, Y') : 'No due date' }}</span>
                                        </div>
                                        
                                        @if($training['progress'] > 0)
                                        <div class="progress-mini">
                                            <div class="progress-bar" style="width: {{ $training['progress'] }}%"></div>
                                        </div>
                                        <div class="progress-text">{{ $training['progress'] }}% Complete</div>
                                        @endif
                                        
                                        <span class="btn-view">
                                            <i class='bx bx-play-circle'></i>View Lesson
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class='bx bx-book-open'></i>
                                    <p>No Assigned Courses</p>
                                    <small>Check the Course Grant tab to request additional training</small>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Course Detail View -->
                    <div id="course-detail-view" style="display: none;">
                        <div class="course-detail-header">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="course-detail-title" id="course-detail-title">Course Title</h3>
                                    <p class="course-detail-desc" id="course-detail-description">Course description</p>
                                    <div class="course-detail-meta">
                                        <span><i class='bx bx-category me-1'></i><span id="course-detail-category">Category</span></span>
                                        <span><i class='bx bx-time me-1'></i>Due: <span id="course-detail-due">Due date</span></span>
                                    </div>
                                </div>
                                <button class="btn-back-courses" onclick="backToCourses()">
                                    <i class='bx bx-arrow-back me-1'></i>Back
                                </button>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="lessons-card">
                                    <div class="lessons-header">
                                        <i class='bx bx-list-ul me-2'></i>Course Lessons
                                    </div>
                                    <div id="lessons-list"></div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="lesson-content-card">
                                    <div class="lesson-content-body" id="lesson-content-area">
                                        <div class="empty-state">
                                            <i class='bx bx-book-open'></i>
                                            <p>Select a lesson to start</p>
                                            <small>Choose a lesson from the list to view its content</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assessments -->
                    <div class="tab-pane fade" id="assessment-content" role="tabpanel">
                        <div class="row g-3">
                            @forelse($assessmentAssignments as $assessment)
                            <div class="col-md-6 col-lg-4">
                                <div class="course-card assessment">
                                    <div class="card-body">
                                        <h6 class="course-title">{{ $assessment['title'] }}</h6>
                                        <p class="course-desc">{{ Str::limit($assessment['description'] ?? 'Assessment details not available', 80) }}</p>
                                        
                                        <div class="course-meta">
                                            <i class='bx bx-repeat'></i>
                                            <span>Attempts: {{ $assessment['attempts_used'] }}/{{ $assessment['max_attempts'] }}</span>
                                        </div>
                                        <div class="course-meta">
                                            <i class='bx bx-badge-check'></i>
                                            <span>Status: {{ ucfirst($assessment['status']) }}</span>
                                        </div>
                                        
                                        <a href="{{ route('ess.assessment.take', $assessment['id']) }}"
                                           class="btn-view assessment {{ ($assessment['status'] === 'completed' || $assessment['attempts_used'] >= $assessment['max_attempts']) ? 'disabled' : '' }}"
                                           {{ ($assessment['status'] === 'completed' || $assessment['attempts_used'] >= $assessment['max_attempts']) ? 'tabindex="-1" aria-disabled="true"' : '' }}>
                                            <i class='bx bx-play-circle'></i>
                                            {{ $assessment['status'] === 'completed' ? 'Completed' : 'Take Assessment' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class='bx bx-task'></i>
                                    <p>No Assessments Available</p>
                                    <small>Complete your courses to unlock assessments</small>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Course Grant -->
                    <div class="tab-pane fade" id="course-grant-content" role="tabpanel">
                        <div class="filter-section">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label>Search Courses</label>
                                    <input type="text" id="courseSearch" class="form-control" placeholder="Search titles..." onkeyup="filterCourses()">
                                </div>
                                <div class="col-md-3">
                                    <label>Category</label>
                                    <select id="categoryFilter" class="form-select" onchange="filterCourses()">
                                        <option value="">All Categories</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Difficulty</label>
                                    <select id="difficultyFilter" class="form-select" onchange="filterCourses()">
                                        <option value="">All Levels</option>
                                        <option value="Beginner">Beginner</option>
                                        <option value="Intermediate">Intermediate</option>
                                        <option value="Advanced">Advanced</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-secondary btn-clear-filter w-100" onclick="clearFilters()">
                                        <i class='bx bx-refresh me-1'></i>Clear
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="availableCoursesGrid">
                            <div class="row g-3" id="coursesContainer">
                                <div class="col-12" id="loadingIndicator">
                                    <div class="empty-state">
                                        <div class="spinner-border text-success" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3">Loading courses...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Details Modal -->
    <div class="modal fade" id="courseDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="courseDetailsTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div id="courseDetailsContent"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="course-info-card">
                                <div class="course-info-header">Course Information</div>
                                <div class="course-info-body" id="courseDetailsInfo"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning btn-sm" id="requestCourseBtn">
                        <i class='bx bx-plus-circle me-1'></i>Request Course
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function viewCourse(id, title, desc, category, due, priority) {
            document.getElementById('courses-content').style.display = 'none';
            document.getElementById('course-detail-view').style.display = 'block';
            
            document.getElementById('course-detail-title').innerText = title;
            document.getElementById('course-detail-description').innerText = desc;
            document.getElementById('course-detail-category').innerText = category || 'General';
            document.getElementById('course-detail-due').innerText = due || 'No due date';
            
            window.currentCourse = { id, title, desc, category, due, priority };
            showLessonsList(id);
        }

        function showLessonsList(courseId) {
            const lessonsList = document.getElementById('lessons-list');
            
            if (lessonContents[courseId] && lessonContents[courseId].length > 0) {
                let lessonsHtml = '';
                lessonContents[courseId].forEach((lesson, index) => {
                    lessonsHtml += `
                        <div class="lesson-item" onclick="viewLesson(${courseId}, ${index})" data-index="${index}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="lesson-number">${index + 1}</div>
                                <div>
                                    <p class="lesson-title">${lesson.title}</p>
                                    <span class="lesson-subtitle">Click to view</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
                lessonsList.innerHTML = lessonsHtml;
            } else {
                lessonsList.innerHTML = `
                    <div class="empty-state" style="padding: 2rem 1rem;">
                        <i class='bx bx-info-circle' style="font-size: 2rem;"></i>
                        <p>No lessons available</p>
                    </div>
                `;
            }
        }

        function viewLesson(courseId, lessonIndex) {
            const lesson = lessonContents[courseId][lessonIndex];
            const contentArea = document.getElementById('lesson-content-area');
            
            document.querySelectorAll('.lesson-item').forEach((item, index) => {
                item.classList.toggle('active', index === lessonIndex);
            });
            
            contentArea.innerHTML = `
                <div class="lesson-content-header">
                    <div class="lesson-number">${lessonIndex + 1}</div>
                    <h4 class="lesson-content-title">${lesson.title}</h4>
                </div>
                <div class="lesson-text">
                    ${lesson.content.replace(/\n/g, '<br>')}
                </div>
                <div class="lesson-nav">
                    <button class="btn btn-outline-success btn-lesson-nav" onclick="navigateLesson(${courseId}, ${lessonIndex - 1})" ${lessonIndex === 0 ? 'disabled' : ''}>
                        <i class='bx bx-chevron-left me-1'></i>Previous
                    </button>
                    <button class="btn btn-success btn-lesson-nav" onclick="navigateLesson(${courseId}, ${lessonIndex + 1})" ${lessonIndex === lessonContents[courseId].length - 1 ? 'disabled' : ''}>
                        Next<i class='bx bx-chevron-right ms-1'></i>
                    </button>
                </div>
            `;
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

        let availableCourses = [];
        let filteredCourses = [];
        let categories = [];

        function loadAvailableCourses() {
            if (availableCourses.length > 0) return;

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
                    <div class="empty-state">
                        <i class='bx bx-error'></i>
                        <p>Failed to load courses</p>
                        <button class="btn btn-outline-warning btn-sm mt-2" onclick="loadAvailableCourses()">
                            <i class='bx bx-refresh me-1'></i>Retry
                        </button>
                    </div>
                `;
            });
        }

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

        function displayCourses() {
            const container = document.getElementById('coursesContainer');
            
            if (filteredCourses.length === 0) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="empty-state">
                            <i class='bx bx-search'></i>
                            <p>No courses found</p>
                            <small>Try adjusting your filters</small>
                        </div>
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

                let statusBadge = '';
                if (course.request_status === 'pending') {
                    statusBadge = '<span class="badge-status badge-due"><i class="bx bx-clock me-1"></i>Pending</span>';
                } else if (course.request_status === 'approved') {
                    statusBadge = '<span class="badge-status badge-success"><i class="bx bx-check me-1"></i>Approved</span>';
                }

                coursesHtml += `
                    <div class="col-md-6 col-lg-4">
                        <div class="course-card grant">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="course-title mb-0" style="flex: 1;">${course.title}</h6>
                                    ${statusBadge}
                                </div>
                                <p class="course-desc">${course.description ? course.description.substring(0, 80) + '...' : 'No description'}</p>
                                
                                <div class="course-meta">
                                    <i class='bx bx-category'></i>
                                    <span>${course.category || 'Uncategorized'}</span>
                                </div>
                                <div class="course-meta">
                                    <i class='bx bx-time'></i>
                                    <span>${course.duration || 'Self-paced'}</span>
                                </div>
                                <div class="course-meta">
                                    <i class='bx bx-medal'></i>
                                    <span class="badge badge-difficulty bg-${difficultyColor}">${course.difficulty_level || 'N/A'}</span>
                                </div>
                                
                                <button type="button" class="btn-view grant" onclick="viewCourseDetails(${course.id}, '${course.title.replace(/'/g, "\\'")}')">
                                    <i class='bx bx-info-circle'></i>View Details
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = coursesHtml;
        }

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

        function clearFilters() {
            document.getElementById('courseSearch').value = '';
            document.getElementById('categoryFilter').value = '';
            document.getElementById('difficultyFilter').value = '';
            filteredCourses = [...availableCourses];
            displayCourses();
        }

        function viewCourseDetails(courseId, courseTitle) {
            const course = availableCourses.find(c => c.id === courseId);
            if (!course) return;

            document.getElementById('courseDetailsTitle').textContent = course.title;
            
            document.getElementById('courseDetailsContent').innerHTML = `
                <div class="empty-state" style="padding: 2rem;">
                    <div class="spinner-border text-warning" role="status"></div>
                    <p class="mt-2">Loading content...</p>
                </div>
            `;

            document.getElementById('courseDetailsInfo').innerHTML = `
                <div class="course-info-item">
                    <div class="course-info-label">Category</div>
                    <div class="course-info-value">${course.category || 'General Training'}</div>
                </div>
                <div class="course-info-item">
                    <div class="course-info-label">Difficulty</div>
                    <div class="course-info-value">${course.difficulty_level || 'Not specified'}</div>
                </div>
                <div class="course-info-item">
                    <div class="course-info-label">Duration</div>
                    <div class="course-info-value">${course.duration || 'Self-paced'}</div>
                </div>
                <div class="course-info-item">
                    <div class="course-info-label">Objectives</div>
                    <div class="course-info-value" style="font-size: 0.75rem; font-weight: 400;">${course.learning_objectives || 'Develop skills in this area.'}</div>
                </div>
            `;

            const requestBtn = document.getElementById('requestCourseBtn');
            if (course.request_status === 'pending') {
                requestBtn.className = 'btn btn-warning btn-sm';
                requestBtn.innerHTML = '<i class="bx bx-clock me-1"></i>Pending';
                requestBtn.disabled = true;
                requestBtn.onclick = null;
            } else if (course.request_status === 'approved') {
                requestBtn.className = 'btn btn-success btn-sm';
                requestBtn.innerHTML = '<i class="bx bx-check me-1"></i>Approved';
                requestBtn.disabled = true;
                requestBtn.onclick = null;
            } else {
                requestBtn.className = 'btn btn-warning btn-sm';
                requestBtn.innerHTML = '<i class="bx bx-plus-circle me-1"></i>Request Course';
                requestBtn.disabled = false;
                requestBtn.onclick = () => requestCourse(courseId, course.title);
            }
            
            new bootstrap.Modal(document.getElementById('courseDetailsModal')).show();
            loadTrainingMaterials(courseId);
        }

        function loadTrainingMaterials(courseId) {
            fetch(`/ess/training-catalog/${courseId}/materials`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.materials && data.materials.length > 0) {
                    displayTrainingMaterials(data.materials);
                } else {
                    document.getElementById('courseDetailsContent').innerHTML = `
                        <div class="empty-state" style="padding: 2rem;">
                            <i class='bx bx-info-circle'></i>
                            <p>No materials available yet</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('courseDetailsContent').innerHTML = `
                    <div class="empty-state" style="padding: 2rem;">
                        <i class='bx bx-error'></i>
                        <p>Failed to load materials</p>
                        <button class="btn btn-outline-warning btn-sm mt-2" onclick="loadTrainingMaterials(${courseId})">Retry</button>
                    </div>
                `;
            });
        }

        function displayTrainingMaterials(materials) {
            let html = `
                <div class="mb-3">
                    <h6 style="font-size: 0.9rem; font-weight: 600; color: var(--blue);">Course Content</h6>
                    <p style="font-size: 0.75rem; color: var(--secondary);">This course includes ${materials.length} lesson(s)</p>
                </div>
            `;

            materials.forEach((material, index) => {
                html += `
                    <div class="material-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="material-title">
                                <span class="badge bg-primary me-2" style="font-size: 0.65rem;">${index + 1}</span>
                                ${material.lesson_title}
                            </h6>
                            <span class="badge bg-info" style="font-size: 0.6rem;">${material.status}</span>
                        </div>
                        <p class="material-desc">${material.content_excerpt || 'No description'}</p>
                        <div class="material-badges">
                            <span class="material-badge bg-success bg-opacity-10 text-success">${material.proficiency_level}</span>
                            <span class="material-badge bg-secondary bg-opacity-10 text-secondary">${material.competency_name}</span>
                            <span class="material-badge bg-warning bg-opacity-10 text-warning">${material.framework_name}</span>
                        </div>
                    </div>
                `;
            });

            document.getElementById('courseDetailsContent').innerHTML = html;
        }

        function requestCourse(courseId, courseTitle) {
            Swal.fire({
                title: 'Request Course Access',
                html: `
                    <div class="text-start">
                        <p style="font-size: 0.9rem;">Request access to:</p>
                        <div class="alert alert-warning py-2">
                            <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">${courseTitle}</h6>
                        </div>
                        <label class="form-label" style="font-size: 0.8rem;">Reason (optional):</label>
                        <textarea id="requestReason" class="form-control" rows="3" style="font-size: 0.85rem;" placeholder="Why do you want this course?"></textarea>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bx bx-send me-1"></i>Send',
                cancelButtonText: 'Cancel',
                width: '450px',
                preConfirm: () => document.getElementById('requestReason').value
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Submitting...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });

                    fetch('/ess/training-catalog/request-course', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ course_id: courseId, reason: result.value || '' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Request Sent!',
                                html: `<p style="font-size: 0.9rem;">Your request has been submitted.</p>`,
                                confirmButtonColor: '#10b981'
                            }).then(() => {
                                const course = availableCourses.find(c => c.id === courseId);
                                if (course) course.request_status = 'pending';
                                displayCourses();
                                bootstrap.Modal.getInstance(document.getElementById('courseDetailsModal')).hide();
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: data.message || 'Please try again.' });
                        }
                    })
                    .catch(() => {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
                    });
                }
            });
        }
    </script>
</body>
</html>
