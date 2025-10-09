<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Portal - {{ config('app.name') }}</title>
    
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
        }
    </style>
</head>
<body>
    @include('layouts.ess-navbar-bootstrap')

    <div class="container-fluid py-4" style="margin-top: 80px;">

        <!-- Quick Actions -->
        <div class="row g-4 mb-4">

            <!-- Learning Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-3 bg-success bg-opacity-10 rounded-circle me-3">
                                <i class='bx bx-book-open fs-4 text-success'></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Learning</h5>
                                <p class="card-text small text-muted">Courses & Training</p>
                            </div>
                        </div>
                        <a href="{{ route('ess.lms') }}" class="btn btn-outline-success btn-sm">View Courses →</a>
                    </div>
                </div>
            </div>

            <!-- Time In Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-3 bg-success bg-opacity-10 rounded-circle me-3">
                                <i class='bx bx-time fs-4 text-success'></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Time In</h5>
                                <p class="card-text small text-muted">Clock In Attendance</p>
                            </div>
                        </div>
                        <button class="btn btn-outline-success btn-sm" onclick="clockIn()">
                            Clock In Now →
                        </button>
                    </div>
                </div>
            </div>

            <!-- Time Off Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-3 bg-success bg-opacity-10 rounded-circle me-3">
                                <i class='bx bx-calendar fs-4 text-success'></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Time Off</h5>
                                <p class="card-text small text-muted">Request Leave</p>
                            </div>
                        </div>
                        <a href="#" class="btn btn-outline-success btn-sm">Request Leave →</a>
                    </div>
                </div>
            </div>

            <!-- Payslip Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-3 bg-success bg-opacity-10 rounded-circle me-3">
                                <i class='bx bx-receipt fs-4 text-success'></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Payslip</h5>
                                <p class="card-text small text-muted">View Salary Details</p>
                            </div>
                        </div>
                        <a href="#" class="btn btn-outline-success btn-sm">View Payslip →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="row g-4">
            <!-- Recent Activities -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title h4 mb-4">Recent Activities</h2>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex align-items-center">
                                <div class="p-2 bg-primary bg-opacity-10 rounded-circle me-3">
                                    <i class='bx bx-check text-primary'></i>
                                </div>
                                <div>
                                    <p class="mb-1 fw-semibold">Completed Training Module</p>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <div class="p-2 bg-success bg-opacity-10 rounded-circle me-3">
                                    <i class='bx bx-calendar-check text-success'></i>
                                </div>
                                <div>
                                    <p class="mb-1 fw-semibold">Leave Request Approved</p>
                                    <small class="text-muted">Yesterday</small>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center">
                                <div class="p-2 bg-warning bg-opacity-10 rounded-circle me-3">
                                    <i class='bx bx-star text-warning'></i>
                                </div>
                                <div>
                                    <p class="mb-1 fw-semibold">Performance Review Scheduled</p>
                                    <small class="text-muted">3 days ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Schedule -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title h4 mb-4">
                            <i class='bx bx-calendar-week text-success me-2'></i>
                            Work Schedule
                        </h2>
                        
                        <!-- Weekly Schedule -->
                        <div class="schedule-container">
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-circle text-success me-2'></i>
                                    <span class="fw-semibold">Monday</span>
                                </div>
                                <span class="badge bg-success">8:00 AM - 6:00 PM</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-circle text-success me-2'></i>
                                    <span class="fw-semibold">Tuesday</span>
                                </div>
                                <span class="badge bg-success">8:00 AM - 6:00 PM</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-circle text-success me-2'></i>
                                    <span class="fw-semibold">Wednesday</span>
                                </div>
                                <span class="badge bg-success">8:00 AM - 6:00 PM</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-circle text-success me-2'></i>
                                    <span class="fw-semibold">Thursday</span>
                                </div>
                                <span class="badge bg-success">8:00 AM - 6:00 PM</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-circle text-success me-2'></i>
                                    <span class="fw-semibold">Friday</span>
                                </div>
                                <span class="badge bg-success">8:00 AM - 6:00 PM</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-circle text-muted me-2'></i>
                                    <span class="fw-semibold text-muted">Saturday</span>
                                </div>
                                <span class="badge bg-secondary">Rest Day</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center p-3">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-circle text-muted me-2'></i>
                                    <span class="fw-semibold text-muted">Sunday</span>
                                </div>
                                <span class="badge bg-secondary">Rest Day</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function clockIn() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: true, 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            const dateString = now.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            // Show success message
            alert(`✅ Successfully Clocked In!\n\nTime: ${timeString}\nDate: ${dateString}\n\nHave a great day at work!`);
            
            // Optional: You can add AJAX call here to save attendance to database
            // Example:
            /*
            fetch('/ess/clock-in', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    clock_in_time: now.toISOString()
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Successfully clocked in!');
                }
            });
            */
        }
    </script>
</body>
</html>