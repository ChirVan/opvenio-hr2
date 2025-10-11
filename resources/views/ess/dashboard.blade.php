<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Employee Portal - {{ config('app.name') }}</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .portal-header {
      background: linear-gradient(135deg, #198754, #157347);
      color: white;
      border-radius: 12px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .portal-header h1 {
      font-size: 1.75rem;
      font-weight: 600;
    }
    .portal-header p {
      opacity: 0.9;
    }
    .action-card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .action-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }
    .action-icon {
      width: 48px;
      height: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background-color: rgba(25,135,84,0.1);
    }
    .section-title {
      font-weight: 600;
      margin-bottom: 1rem;
      color: #198754;
    }
    .list-group-item {
      border: none;
      border-bottom: 1px solid #f0f0f0;
    }
    .table thead th {
  font-weight: 600;
  font-size: 0.9rem;
}
.badge.bg-success-subtle {
  background-color: #e6f4ea !important;
}
.badge.bg-secondary-subtle {
  background-color: #f0f0f0 !important;
}
.card {
  border-radius: 12px;
}
  </style>
</head>
<body>
  @include('layouts.ess-navbar-bootstrap')

  <div class="container py-4" style="margin-top: 80px;">

    <!-- Portal Header -->
    <div class="portal-header text-center">
      <h1>Welcome Back, {{ Auth::user()->name ?? 'Employee' }} 👋</h1>
      <p>Here’s your daily overview — manage your work, check schedules, and stay updated.</p>
    </div>

    <!-- Quick Action Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-6 col-lg-4">
        <div class="card action-card text-center p-3">
          <div class="action-icon mx-auto mb-3">
            <i class='bx bx-book-open text-success fs-4'></i>
          </div>
          <h5 class="fw-semibold mb-1">Learning</h5>
          <p class="text-muted small mb-3">Courses & Training</p>
          <a href="{{ route('ess.lms') }}" class="btn btn-success btn-sm w-100">View Courses</a>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card action-card text-center p-3">
          <div class="action-icon mx-auto mb-3">
            <i class='bx bx-calendar text-success fs-4'></i>
          </div>
          <h5 class="fw-semibold mb-1">Time Off</h5>
          <p class="text-muted small mb-3">Request Leave</p>
          <a href="{{ route('ess.leave') }}" class="btn btn-success btn-sm w-100">Request Leave</a>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card action-card text-center p-3">
          <div class="action-icon mx-auto mb-3">
            <i class='bx bx-receipt text-success fs-4'></i>
          </div>
          <h5 class="fw-semibold mb-1">Payslip</h5>
          <p class="text-muted small mb-3">View Salary Details</p>
          <a href="{{ route('ess.payslip') }}" class="btn btn-success btn-sm w-100">View Payslip</a>
        </div>
      </div>
    </div>

    <!-- Two-column section -->
    <div class="row g-4">
      <!-- Recent Activities -->
      <div class="col-lg-6">
        <div class="card h-100">
          <div class="card-body">
            <h4 class="section-title"><i class='bx bx-bell me-2'></i>Recent Activities</h4>
            <div class="list-group list-group-flush">
              <div class="list-group-item d-flex align-items-center">
                <div class="p-2 bg-success bg-opacity-10 rounded-circle me-3">
                  <i class='bx bx-check text-success'></i>
                </div>
                <div>
                  <p class="mb-1 fw-semibold">Completed Training Module</p>
                  <small class="text-muted">2 hours ago</small>
                </div>
              </div>
              <div class="list-group-item d-flex align-items-center">
                <div class="p-2 bg-primary bg-opacity-10 rounded-circle me-3">
                  <i class='bx bx-calendar-check text-primary'></i>
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
  <div class="card h-100 border-0 shadow-sm">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="section-title mb-0 d-flex align-items-center">
          <i class='bx bx-calendar-week me-2 text-success fs-4'></i>
          Work Schedule
        </h4>
        <span class="text-muted small"><i class='bx bx-calendar-event me-1'></i> October 2025</span>
      </div>

      <div class="table-responsive">
        <table class="table align-middle table-borderless">
          <thead class="table-light">
            <tr>
              <th scope="col" class="text-muted">Day</th>
              <th scope="col" class="text-muted">Shift Time</th>
              <th scope="col" class="text-muted">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr class="bg-white">
              <td><i class='bx bx-calendar text-success me-2'></i>Monday</td>
              <td>8:00 AM – 6:00 PM</td>
              <td><span class="badge bg-success-subtle text-success border border-success-subtle">On Duty</span></td>
            </tr>
            <tr>
              <td><i class='bx bx-calendar text-success me-2'></i>Tuesday</td>
              <td>8:00 AM – 6:00 PM</td>
              <td><span class="badge bg-success-subtle text-success border border-success-subtle">On Duty</span></td>
            </tr>
            <tr>
              <td><i class='bx bx-calendar text-success me-2'></i>Wednesday</td>
              <td>8:00 AM – 6:00 PM</td>
              <td><span class="badge bg-success-subtle text-success border border-success-subtle">On Duty</span></td>
            </tr>
            <tr>
              <td><i class='bx bx-calendar text-success me-2'></i>Thursday</td>
              <td>8:00 AM – 6:00 PM</td>
              <td><span class="badge bg-success-subtle text-success border border-success-subtle">On Duty</span></td>
            </tr>
            <tr>
              <td><i class='bx bx-calendar text-success me-2'></i>Friday</td>
              <td>8:00 AM – 6:00 PM</td>
              <td><span class="badge bg-success-subtle text-success border border-success-subtle">On Duty</span></td>
            </tr>
            <tr>
              <td><i class='bx bx-moon text-secondary me-2'></i>Saturday</td>
              <td>—</td>
              <td><span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Rest Day</span></td>
            </tr>
            <tr>
              <td><i class='bx bx-moon text-secondary me-2'></i>Sunday</td>
              <td>—</td>
              <td><span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Rest Day</span></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Optional Note -->
      <div class="mt-3 small text-muted">
        <i class='bx bx-info-circle me-1'></i>
        Work schedules are subject to approval and may vary during holidays.
      </div>
    </div>
  </div>
</div>
    </div>

  </div>

  

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function clockIn() {
      const now = new Date();
      const timeString = now.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit' });
      const dateString = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
      alert(`✅ Successfully Clocked In!\n\nTime: ${timeString}\nDate: ${dateString}`);
    }
  </script>
</body>
</html>
