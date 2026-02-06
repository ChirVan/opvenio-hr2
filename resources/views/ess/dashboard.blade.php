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
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary: #10b981;
      --primary-dark: #059669;
      --primary-light: #d1fae5;
      --secondary: #64748b;
      --dark: #1e293b;
      --light: #f8fafc;
      --card-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.1);
      --card-shadow-hover: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04);
    }
    
    body {
      background-color: var(--light);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      color: var(--dark);
    }
    
    .portal-header {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      border-radius: 16px;
      padding: 1.75rem 2rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 20px rgba(16, 185, 129, 0.25);
      position: relative;
      overflow: hidden;
    }
    
    .portal-header::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -20%;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      border-radius: 50%;
    }
    
    .portal-header h1 {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
    }
    
    .portal-header p {
      opacity: 0.9;
      font-size: 0.9rem;
      margin-bottom: 0;
    }
    
    .quick-actions {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 0.75rem;
      margin-bottom: 1.5rem;
    }
    
    .quick-action-item {
      background: white;
      border-radius: 12px;
      padding: 1rem;
      text-align: center;
      text-decoration: none;
      color: var(--dark);
      box-shadow: var(--card-shadow);
      transition: all 0.2s ease;
      border: 1px solid rgba(0,0,0,0.04);
    }
    
    .quick-action-item:hover {
      transform: translateY(-2px);
      box-shadow: var(--card-shadow-hover);
      color: var(--dark);
      border-color: var(--primary-light);
    }
    
    .quick-action-icon {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
      margin: 0 auto 0.5rem;
      font-size: 1.25rem;
    }
    
    .quick-action-icon.learning { background: #dbeafe; color: #2563eb; }
    .quick-action-icon.leave { background: #fef3c7; color: #d97706; }
    .quick-action-icon.payslip { background: #d1fae5; color: #059669; }
    
    .quick-action-title {
      font-size: 0.8rem;
      font-weight: 500;
      margin-bottom: 0;
    }
    
    .quick-action-subtitle {
      font-size: 0.7rem;
      color: var(--secondary);
      margin-bottom: 0;
    }
    
    .section-card {
      background: white;
      border-radius: 14px;
      box-shadow: var(--card-shadow);
      border: 1px solid rgba(0,0,0,0.04);
      overflow: hidden;
    }
    
    .section-header {
      padding: 1rem 1.25rem;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .section-title {
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--dark);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .section-title i {
      color: var(--primary);
    }
    
    .section-body {
      padding: 0;
    }
    
    .activity-item {
      display: flex;
      align-items: center;
      padding: 0.875rem 1.25rem;
      border-bottom: 1px solid #f8fafc;
      transition: background 0.15s ease;
    }
    
    .activity-item:last-child {
      border-bottom: none;
    }
    
    .activity-item:hover {
      background: #fafbfc;
    }
    
    .activity-icon {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 0.75rem;
      font-size: 0.9rem;
    }
    
    .activity-icon.success { background: #d1fae5; color: #059669; }
    .activity-icon.primary { background: #dbeafe; color: #2563eb; }
    .activity-icon.warning { background: #fef3c7; color: #d97706; }
    
    .activity-content h6 {
      font-size: 0.825rem;
      font-weight: 500;
      margin-bottom: 0.125rem;
      color: var(--dark);
    }
    
    .activity-content small {
      font-size: 0.7rem;
      color: var(--secondary);
    }
    
    .schedule-table {
      margin: 0;
    }
    
    .schedule-table thead th {
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--secondary);
      padding: 0.75rem 1rem;
      background: #f8fafc;
      border: none;
    }
    
    .schedule-table tbody td {
      padding: 0.625rem 1rem;
      font-size: 0.8rem;
      border: none;
      border-bottom: 1px solid #f8fafc;
      vertical-align: middle;
    }
    
    .schedule-table tbody tr:last-child td {
      border-bottom: none;
    }
    
    .day-label {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: 500;
    }
    
    .day-icon {
      width: 24px;
      height: 24px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
    }
    
    .day-icon.active { background: #d1fae5; color: #059669; }
    .day-icon.rest { background: #f1f5f9; color: #94a3b8; }
    
    .status-badge {
      font-size: 0.675rem;
      font-weight: 500;
      padding: 0.25rem 0.5rem;
      border-radius: 6px;
    }
    
    .status-badge.on-duty {
      background: #d1fae5;
      color: #059669;
    }
    
    .status-badge.rest-day {
      background: #f1f5f9;
      color: #64748b;
    }
    
    .section-footer {
      padding: 0.75rem 1.25rem;
      background: #fafbfc;
      border-top: 1px solid #f1f5f9;
    }
    
    .section-footer p {
      font-size: 0.7rem;
      color: var(--secondary);
      margin: 0;
    }
    
    .info-card {
      background: white;
      border-radius: 12px;
      padding: 1rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      box-shadow: var(--card-shadow);
      border: 1px solid rgba(0,0,0,0.04);
    }
    
    .info-icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      flex-shrink: 0;
    }
    
    .info-icon.blue { background: #dbeafe; color: #2563eb; }
    .info-icon.green { background: #d1fae5; color: #059669; }
    .info-icon.purple { background: #f3e8ff; color: #7c3aed; }
    
    .info-content {
      display: flex;
      flex-direction: column;
      min-width: 0;
    }
    
    .info-label {
      font-size: 0.7rem;
      color: var(--secondary);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .info-value {
      font-size: 0.875rem;
      font-weight: 600;
      color: var(--dark);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    @media (max-width: 768px) {
      .quick-actions {
        grid-template-columns: repeat(3, 1fr);
      }
      .portal-header {
        padding: 1.25rem;
      }
      .portal-header h1 {
        font-size: 1.25rem;
      }
    }
  </style>
</head>
<body>
  @include('layouts.ess-navbar-bootstrap')

  <div class="container py-4" style="margin-top: 76px; max-width: 1100px;">

    <!-- Portal Header -->
    <div class="portal-header">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <h1>Welcome back, {{ Auth::user()->name ?? 'Employee' }} 👋</h1>
          <p>Manage your work, check schedules, and stay updated.</p>
        </div>
        <div class="d-none d-md-block">
          <span class="badge bg-white bg-opacity-25 text-white px-3 py-2">
            <i class='bx bx-calendar me-1'></i>{{ now()->format('l, M d') }}
          </span>
        </div>
      </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="quick-actions">
      <a href="{{ route('ess.lms') }}" class="quick-action-item">
        <div class="quick-action-icon learning">
          <i class='bx bx-book-open'></i>
        </div>
        <p class="quick-action-title">Learning</p>
        <p class="quick-action-subtitle">Courses</p>
      </a>

      <a href="{{ route('ess.leave') }}" class="quick-action-item">
        <div class="quick-action-icon leave">
          <i class='bx bx-calendar-event'></i>
        </div>
        <p class="quick-action-title">Time Off</p>
        <p class="quick-action-subtitle">Request</p>
      </a>

      <a href="{{ route('ess.payslip') }}" class="quick-action-item">
        <div class="quick-action-icon payslip">
          <i class='bx bx-wallet'></i>
        </div>
        <p class="quick-action-title">Payslip</p>
        <p class="quick-action-subtitle">Salary</p>
      </a>
    </div>

    <!-- Info Cards Row -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="info-card">
          <div class="info-icon blue">
            <i class='bx bx-briefcase'></i>
          </div>
          <div class="info-content">
            <span class="info-label">Job Title</span>
            <span class="info-value" id="emp-job-title">Loading...</span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="info-card">
          <div class="info-icon green">
            <i class='bx bx-id-card'></i>
          </div>
          <div class="info-content">
            <span class="info-label">Employee ID</span>
            <span class="info-value">{{ Auth::user()->employee_id ?? 'N/A' }}</span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="info-card">
          <div class="info-icon purple">
            <i class='bx bx-map'></i>
          </div>
          <div class="info-content">
            <span class="info-label">Work Location</span>
            <span class="info-value" id="emp-location">Loading...</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Two-column section -->
    <div class="row g-3">
      <!-- Recent Activities -->
      <div class="col-lg-6">
        <div class="section-card h-100">
          <div class="section-header">
            <h5 class="section-title"><i class='bx bx-bell'></i>Recent Activities</h5>
          </div>
          <div class="section-body" id="activities-container">
            <!-- Loading state -->
            <div class="activity-item" id="activities-loading">
              <div class="activity-icon primary">
                <i class='bx bx-loader-alt bx-spin'></i>
              </div>
              <div class="activity-content">
                <h6>Loading activities...</h6>
                <small>Please wait</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Work Schedule -->
      <div class="col-lg-6">
        <div class="section-card h-100">
          <div class="section-header">
            <h5 class="section-title"><i class='bx bx-calendar-week'></i>Work Schedule</h5>
          </div>
          <div class="section-body">
            <table class="table schedule-table">
              <thead>
                <tr>
                  <th>Day</th>
                  <th>Shift</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="schedule-body">
                <tr>
                  <td colspan="3" class="text-center py-4">
                    <i class='bx bx-loader-alt bx-spin' style="font-size: 1.5rem; color: var(--secondary);"></i>
                    <p class="mb-0 mt-2" style="font-size: 0.8rem; color: var(--secondary);">Loading schedule...</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="section-footer">
            <p><i class='bx bx-info-circle me-1'></i>Schedules may vary during holidays.</p>
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

    // Fetch employee data from HR4 API
    document.addEventListener('DOMContentLoaded', async function() {
      const userEmail = '{{ Auth::user()->email ?? "" }}';
      const userEmployeeId = '{{ Auth::user()->employee_id ?? "" }}';
      console.log('Fetching employee data for:', userEmail);
      console.log('Employee ID:', userEmployeeId);
      
      if (!userEmail) {
        document.getElementById('emp-job-title').textContent = 'Not Assigned';
        document.getElementById('emp-location').textContent = 'Not Assigned';
        return;
      }

      try {
        const response = await fetch(`/api/ess/employee/by-email/${encodeURIComponent(userEmail)}`);
        const result = await response.json();
        console.log('API Response:', result);

        if (result.success && result.employee) {
          const emp = result.employee;
          console.log('Employee data:', emp);
          console.log('Job object:', emp.job);
          // job_title is nested inside emp.job object
          const jobTitle = emp.job?.job_title || emp.job_title || 'Not Assigned';
          document.getElementById('emp-job-title').textContent = jobTitle;
          document.getElementById('emp-location').textContent = emp.work_location || 'Main Office';
        } else {
          console.log('Employee not found or API error');
          document.getElementById('emp-job-title').textContent = 'Not Assigned';
          document.getElementById('emp-location').textContent = 'Main Office';
        }
      } catch (error) {
        console.error('Error fetching employee data:', error);
        document.getElementById('emp-job-title').textContent = 'Not Assigned';
        document.getElementById('emp-location').textContent = 'Main Office';
      }

      // Fetch work schedule from HR3 API
      await loadWorkSchedule(userEmployeeId);

      // Fetch recent activities
      await loadRecentActivities(userEmail);
    });

    // Load recent activities from API
    async function loadRecentActivities(email) {
      const container = document.getElementById('activities-container');
      
      if (!email) {
        renderEmptyActivities(container);
        return;
      }

      try {
        const response = await fetch(`/api/ess/activities/${encodeURIComponent(email)}`);
        const result = await response.json();
        console.log('Activities API Response:', result);

        if (result.success && result.activities && result.activities.length > 0) {
          renderActivities(container, result.activities);
        } else {
          renderEmptyActivities(container);
        }
      } catch (error) {
        console.error('Error fetching activities:', error);
        renderEmptyActivities(container);
      }
    }

    // Render activities from API data
    function renderActivities(container, activities) {
      let html = '';
      
      activities.forEach(activity => {
        html += `
          <div class="activity-item">
            <div class="activity-icon ${activity.icon_class}">
              <i class='bx ${activity.icon}'></i>
            </div>
            <div class="activity-content">
              <h6>${activity.title}</h6>
              <small>${activity.description}</small>
              <small class="d-block text-muted" style="font-size: 0.65rem;">${activity.time_ago}</small>
            </div>
          </div>
        `;
      });
      
      container.innerHTML = html;
    }

    // Render empty state for activities
    function renderEmptyActivities(container) {
      container.innerHTML = `
        <div class="activity-item">
          <div class="activity-icon" style="background: #f1f5f9; color: #94a3b8;">
            <i class='bx bx-info-circle'></i>
          </div>
          <div class="activity-content">
            <h6>No recent activities</h6>
            <small>Your activities will appear here as you complete trainings and assessments.</small>
          </div>
        </div>
      `;
    }

    // Load work schedule from HR3 API
    async function loadWorkSchedule(employeeId) {
      const scheduleBody = document.getElementById('schedule-body');
      
      if (!employeeId) {
        renderDefaultSchedule(scheduleBody);
        return;
      }

      try {
        const response = await fetch(`/api/ess/attendance/${encodeURIComponent(employeeId)}`);
        const result = await response.json();
        console.log('Schedule API Response:', result);

        if (result.success && result.attendance) {
          const att = result.attendance;
          renderSchedule(scheduleBody, att);
        } else {
          // No schedule found, show default
          renderDefaultSchedule(scheduleBody);
        }
      } catch (error) {
        console.error('Error fetching schedule:', error);
        renderDefaultSchedule(scheduleBody);
      }
    }

    // Format time from 24h to 12h format
    function formatTime(timeStr) {
      if (!timeStr) return null;
      const [hours, minutes] = timeStr.split(':');
      const h = parseInt(hours);
      const ampm = h >= 12 ? 'PM' : 'AM';
      const hour12 = h % 12 || 12;
      return `${hour12}:${minutes} ${ampm}`;
    }

    // Render schedule based on API data
    function renderSchedule(tbody, attendance) {
      const days = [
        { name: 'Monday', key: 'works_on_monday' },
        { name: 'Tuesday', key: 'works_on_tuesday' },
        { name: 'Wednesday', key: 'works_on_wednesday' },
        { name: 'Thursday', key: 'works_on_thursday' },
        { name: 'Friday', key: 'works_on_friday' },
        { name: 'Saturday', key: 'works_on_saturday' },
        { name: 'Sunday', key: 'works_on_sunday' }
      ];

      const timeIn = attendance.schedule_time_in ? formatTime(attendance.schedule_time_in) : '8:00 AM';
      const timeOut = attendance.schedule_time_out ? formatTime(attendance.schedule_time_out) : '5:00 PM';
      const shiftText = `${timeIn} – ${timeOut}`;

      let html = '';
      days.forEach(day => {
        // If works_on_X is null, default to work days Mon-Fri
        const isWorkDay = attendance[day.key] !== null 
          ? attendance[day.key] === 1 
          : !['Saturday', 'Sunday'].includes(day.name);

        const iconClass = isWorkDay ? 'active' : 'rest';
        const iconName = isWorkDay ? 'bx-sun' : 'bx-moon';
        const statusClass = isWorkDay ? 'on-duty' : 'rest-day';
        const statusText = isWorkDay ? 'On Duty' : 'Rest Day';
        const shift = isWorkDay ? shiftText : '—';

        html += `
          <tr>
            <td>
              <div class="day-label">
                <span class="day-icon ${iconClass}"><i class='bx ${iconName}'></i></span>
                ${day.name}
              </div>
            </td>
            <td>${shift}</td>
            <td><span class="status-badge ${statusClass}">${statusText}</span></td>
          </tr>
        `;
      });

      tbody.innerHTML = html;
    }

    // Render default schedule (Mon-Fri work, Sat-Sun rest)
    function renderDefaultSchedule(tbody) {
      const days = [
        { name: 'Monday', isWork: true },
        { name: 'Tuesday', isWork: true },
        { name: 'Wednesday', isWork: true },
        { name: 'Thursday', isWork: true },
        { name: 'Friday', isWork: true },
        { name: 'Saturday', isWork: false },
        { name: 'Sunday', isWork: false }
      ];

      let html = '';
      days.forEach(day => {
        const iconClass = day.isWork ? 'active' : 'rest';
        const iconName = day.isWork ? 'bx-sun' : 'bx-moon';
        const statusClass = day.isWork ? 'on-duty' : 'rest-day';
        const statusText = day.isWork ? 'On Duty' : 'Rest Day';
        const shift = day.isWork ? '8:00 AM – 5:00 PM' : '—';

        html += `
          <tr>
            <td>
              <div class="day-label">
                <span class="day-icon ${iconClass}"><i class='bx ${iconName}'></i></span>
                ${day.name}
              </div>
            </td>
            <td>${shift}</td>
            <td><span class="status-badge ${statusClass}">${statusText}</span></td>
          </tr>
        `;
      });

      tbody.innerHTML = html;
    }
  </script>
</body>
</html>
