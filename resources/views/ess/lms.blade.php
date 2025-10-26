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
        .assessment-card { border-left: 5px solid #0d6efd; }
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
                        <button class="nav-link active" id="courses-tab" data-bs-toggle="tab" data-bs-target="#courses-content" type="button" role="tab">📘 Courses</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="assessment-tab" data-bs-toggle="tab" data-bs-target="#assessment-content" type="button" role="tab">🧩 Assessment</button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="mainTabsContent">
                    <!-- Courses -->
                    <div class="tab-pane fade show active" id="courses-content" role="tabpanel">
                        <div class="row g-4">
                            @forelse($trainingAssignments as $training)
                            <div class="col-md-6 col-lg-4">
                                <div class="card course-card h-100" style="cursor:pointer;"
                                     onclick="viewCourse({{ $training['id'] }}, '{{ addslashes($training['title']) }}', '{{ addslashes($training['description']) }}', '{{ $training['category'] }}', '{{ $training['due_date'] }}', '{{ $training['priority'] }}')">
                                    <div class="card-body">
                                        <h6 class="fw-bold">{{ $training['title'] }}</h6>
                                        <p class="text-muted small">{{ Str::limit($training['description'], 100) }}</p>
                                        <span class="badge bg-success">View Lesson</span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center text-muted py-5">
                                <i class='bx bx-book-open fs-1 mb-2'></i>
                                <p>No Courses Available</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Assessments -->
                    <div class="tab-pane fade" id="assessment-content" role="tabpanel">
                        <div class="row g-4">
                            @forelse($assessmentAssignments as $assessment)
                            <div class="col-md-6 col-lg-4">
                                <div class="card assessment-card h-100">
                                    <div class="card-body">
                                        <h6 class="fw-bold">{{ $assessment['title'] }}</h6>
                                        <p class="text-muted small">{{ Str::limit($assessment['description'] ?? 'Assessment details not available', 100) }}</p>
                                        <span class="badge bg-primary mb-2">View Assessment</span>
                                        <div class="d-grid gap-2 mt-2">
                                            <a href="{{ route('ess.assessment.take', $assessment['id']) }}"
                                               class="btn btn-success btn-sm{{ ($assessment['status'] === 'completed' || $assessment['attempts_used'] >= $assessment['max_attempts']) ? ' disabled' : '' }}"
                                               {{ ($assessment['status'] === 'completed' || $assessment['attempts_used'] >= $assessment['max_attempts']) ? 'tabindex="-1" aria-disabled="true"' : '' }}>
                                                <i class='bx bx-play-circle me-1'></i> Attempt Quiz
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

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function viewCourse(id, title, desc, category, due, priority) {
            document.getElementById('courseTitle').innerText = title;
            document.getElementById('courseDescription').innerText = desc;

            let content = '';
            if (lessonContents[id] && lessonContents[id].length > 0) {
                lessonContents[id].forEach((lesson, index) => {
                    content += `<h6 class="text-success">${index + 1}. ${lesson.title}</h6><p>${lesson.content}</p><hr>`;
                });
            } else {
                content = `<p class="text-muted">No lessons available.</p>`;
            }
            document.getElementById('lessonContent').innerHTML = content;

            new bootstrap.Modal(document.getElementById('courseModal')).show();
        }

        function viewAssessment(id, title, desc, status, used, max, duration, score) {
            document.getElementById('assessmentTitle').innerText = title;
            document.getElementById('assessmentDescription').innerText = desc;
            document.getElementById('assessmentStatus').innerText = status;
            document.getElementById('assessmentAttempts').innerText = `${used}/${max}`;
            document.getElementById('assessmentDuration').innerText = duration;
            document.getElementById('assessmentScore').innerText = score ?? 'N/A';

            new bootstrap.Modal(document.getElementById('assessmentModal')).show();
        }
    </script>
</body>
</html>
