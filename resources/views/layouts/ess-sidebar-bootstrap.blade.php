<!-- ESS Bootstrap Sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header bg-light">
        <h5 class="offcanvas-title d-flex align-items-center" id="sidebarLabel">
            <div class="bg-primary text-white rounded p-2 me-3">
                <i class="bx bx-user"></i>
            </div>
            <div>
                <div class="fw-semibold">Employee Portal</div>
                <small class="text-muted">{{ Auth::user()->name }}</small>
            </div>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body p-0">
        <nav class="nav flex-column">
            <!-- Dashboard -->
            <a class="nav-link d-flex align-items-center py-3 px-4 {{ request()->routeIs('ess.dashboard') ? 'active bg-primary text-white' : 'text-dark' }}" 
               href="{{ route('ess.dashboard') }}">
                <i class="bx bx-home fs-5 me-3"></i>
                <span>Dashboard</span>
            </a>

            <!-- My Profile -->
            <a class="nav-link d-flex align-items-center py-3 px-4 text-dark" href="#">
                <i class="bx bx-user fs-5 me-3"></i>
                <span>My Profile</span>
            </a>

            <!-- Learning & Development -->
            <div class="nav-item">
                <a class="nav-link d-flex align-items-center py-3 px-4 text-dark" 
                   data-bs-toggle="collapse" href="#learningMenu" role="button">
                    <i class="bx bx-book-open fs-5 me-3"></i>
                    <span>Learning & Development</span>
                    <i class="bx bx-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="learningMenu">
                    <div class="nav flex-column ms-4">
                        <a class="nav-link py-2 px-4 text-muted" href="#">My Courses</a>
                        <a class="nav-link py-2 px-4 text-muted" href="#">Training Calendar</a>
                        <a class="nav-link py-2 px-4 text-muted" href="#">Assessments</a>
                        <a class="nav-link py-2 px-4 text-muted" href="#">Certificates</a>
                    </div>
                </div>
            </div>

            <!-- Performance -->
            <div class="nav-item">
                <a class="nav-link d-flex align-items-center py-3 px-4 text-dark" 
                   data-bs-toggle="collapse" href="#performanceMenu" role="button">
                    <i class="bx bx-target-lock fs-5 me-3"></i>
                    <span>Performance</span>
                    <i class="bx bx-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="performanceMenu">
                    <div class="nav flex-column ms-4">
                        <a class="nav-link py-2 px-4 text-muted" href="#">My Goals</a>
                        <a class="nav-link py-2 px-4 text-muted" href="#">Performance Reviews</a>
                        <a class="nav-link py-2 px-4 text-muted" href="#">Feedback</a>
                        <a class="nav-link py-2 px-4 text-muted" href="#">Development Plan</a>
                    </div>
                </div>
            </div>

            <!-- Time & Attendance -->
            <div class="nav-item">
                <a class="nav-link d-flex align-items-center py-3 px-4 text-dark" 
                   data-bs-toggle="collapse" href="#timeMenu" role="button">
                    <i class="bx bx-time-five fs-5 me-3"></i>
                    <span>Time & Attendance</span>
                    <i class="bx bx-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="timeMenu">
                    <div class="nav flex-column ms-4">
                        <a class="nav-link py-2 px-4 text-muted" href="#">Clock In/Out</a>
                        <a class="nav-link py-2 px-4 text-muted" href="#">Timesheet</a>
                        <a class="nav-link py-2 px-4 text-muted" href="#">Overtime</a>
                    </div>
                </div>
            </div>

            <!-- Leave Management -->
            <div class="nav-item">
                <a class="nav-link d-flex align-items-center py-3 px-4 text-dark" 
                   data-bs-toggle="collapse" href="#leaveMenu" role="button">
                    <i class="bx bx-calendar-minus fs-5 me-3"></i>
                    <span>Leave Management</span>
                    <i class="bx bx-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="leaveMenu">
                    <div class="nav flex-column ms-4">
                        <a class="nav-link py-2 px-4 text-muted" href="#">Request Leave</a>
                        <a class="nav-link py-2 px-4 text-muted" href="#">Leave History</a>
                        <a class="nav-link py-2 px-4 text-muted" href="#">Leave Balance</a>
                        <a class="nav-link py-2 px-4 text-muted" href="#">Company Calendar</a>
                    </div>
                </div>
            </div>

            <!-- Payroll & Benefits -->
            <div class="nav-item">
                <a class="nav-link d-flex align-items-center py-3 px-4 text-dark" 
                   data-bs-toggle="collapse" href="#payrollMenu" role="button">
                    <i class="bx bx-money fs-5 me-3"></i>
                    <span>Payroll & Benefits</span>
                    <i class="bx bx-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="payrollMenu">
                    <div class="nav flex-column ms-4">
                        <a class="nav-link py-2 px-4 text-muted" href="#">Pay Stubs</a>
                        <a class="nav-link py-2 px-4 text-muted" href="#">Tax Documents</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>

<style>
    .nav-link:hover {
        background-color: #f8f9fa;
        color: #0d6efd !important;
    }
    
    .nav-link.active {
        background-color: #0d6efd !important;
        color: white !important;
    }
    
    .offcanvas {
        width: 280px !important;
    }
</style>