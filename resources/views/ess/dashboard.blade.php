<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Employee Portal — {{ config('app.name') }}</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --primary: #0ea5a6; /* teal-ish */
      --muted: #6b7280;
      --bg: #f8fafc;
      --card-shadow: 0 6px 18px rgba(15,23,42,0.06);
      --glass: rgba(255,255,255,0.8);
    }

    html,body{height:100%;}
    body{
      font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: var(--bg);
      color: #0f172a;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
    }

    

    /* Main */
    .main{flex:1;padding:1.5rem 2rem}
    .navbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.25rem}
    .search-input{max-width:420px}

    /* Header card */
    .hero-card{background:linear-gradient(90deg,var(--primary),#16a34a);color:white;padding:1.25rem;border-radius:.75rem;box-shadow:var(--card-shadow);}
    .hero-card h2{font-size:1.25rem;margin:0}
    .hero-card p{margin:0.25rem 0 0;color:rgba(255,255,255,0.9)}

    /* Quick actions */
    .quick-actions{display:grid;grid-template-columns:repeat(4,1fr);gap:0.875rem;margin-top:1rem}
    .qa-card{background:white;border-radius:.6rem;padding:0.9rem;display:flex;align-items:center;gap:.75rem;border:1px solid #eef2f7;box-shadow:0 4px 12px rgba(2,6,23,0.03)}
    .qa-icon{width:44px;height:44px;border-radius:.6rem;display:flex;align-items:center;justify-content:center;font-size:1.25rem}
    .qa-title{font-weight:600}
    .qa-sub{color:var(--muted);font-size:0.85rem}

    /* Cards */
    .card-clean{border-radius:.75rem;border:0;box-shadow:var(--card-shadow)}
    .activities .list-group-item{border:none;padding:.85rem 1rem;border-bottom:1px solid #f1f5f9}
    .activities .list-group-item:last-child{border-bottom:0}

    .schedule-table thead th{background:#fbfdfe;border-bottom:1px solid #f1f5f9;color:var(--muted);font-weight:600;font-size:.78rem}
    .schedule-table tbody td{vertical-align:middle}

    /* Responsive */
    @media (max-width: 992px){
      .sidebar{display:none}
      .quick-actions{grid-template-columns:repeat(2,1fr)}
    }

    @media (max-width: 576px){
      .quick-actions{grid-template-columns:repeat(1,1fr)}
    }
  </style>
</head>
<body>
  <div class="app-shell">
    <!-- Sidebar -->
    @include('layouts.ess-aside')
    <!-- <aside class="sidebar d-none d-lg-block">
      <div class="brand">
        <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,var(--primary),#059669);display:flex;align-items:center;justify-content:center;color:white;font-weight:700">EP</div>
        <div>
          <div style="font-weight:700">{{ config('app.name', 'Employee Portal') }}</div>
          <small style="color:var(--muted)">{{ Auth::user()->name ?? 'Employee' }}</small>
        </div>
      </div>

      <nav class="nav flex-column">
        <a href="{{ route('ess.dashboard') }}" class="nav-link active"><i class='bx bx-home'></i> Dashboard</a>
        <a href="{{ route('ess.lms') }}" class="nav-link"><i class='bx bx-book-open'></i> Learning</a>
        <a href="{{ route('ess.leave') }}" class="nav-link"><i class='bx bx-calendar-event'></i> Time Off</a>
        <a href="{{ route('ess.payslip') }}" class="nav-link"><i class='bx bx-wallet'></i> Payslip</a>
        <a href="{{ route('ess.promotion-offers') }}" class="nav-link"><i class='bx bx-rocket'></i> Promotions</a>
        <hr style="margin:1rem 0;border-color:#f3f6f9">
        <a href="#" class="nav-link"><i class='bx bx-help-circle'></i> Help & Support</a>
        <a href="#" class="nav-link"><i class='bx bx-cog'></i> Settings</a>
      </nav>

      <div style="position:absolute;bottom:1.25rem;left:1.25rem;right:1.25rem">
        <small class="text-muted">Need help? <a href="#">Contact HR</a></small>
      </div>
    </aside> -->

    <!-- Main content -->
    <main class="main">
      @include('layouts.ess-navbar')
      <!-- <div class="navbar">
        <div class="d-flex align-items-center gap-3">
          <button class="btn btn-light d-lg-none" id="mobileMenuBtn"><i class='bx bx-menu'></i></button>
          <form class="search-input d-none d-md-block">
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0"><i class='bx bx-search'></i></span>
              <input class="form-control border-start-0" placeholder="Search trainings, payslips, requests..." />
            </div>
          </form>
        </div>

        <div class="d-flex align-items-center gap-3">
          <div class="d-none d-md-block text-muted small">{{ now()->format('l, M d, Y') }}</div>
          <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
              <div style="width:44px;height:44px;border-radius:8px;background:#eef2f7;display:flex;align-items:center;justify-content:center;margin-right:.5rem">
                <i class='bx bx-user'></i>
              </div>
              <div class="d-none d-sm-block text-end">
                <div style="font-weight:700">{{ Auth::user()->name ?? 'Employee' }}</div>
                <small class="text-muted">{{ Auth::user()->employee_id ?? '' }}</small>
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><a class="dropdown-item" href="#">Settings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">@csrf
                  <button class="dropdown-item">Sign out</button>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div> -->

      <!-- Header / Hero -->
      <div class="hero-card d-flex justify-content-between align-items-center mb-3">
        <div>
          <h2>Welcome back, {{ Auth::user()->name ?? 'Employee' }} 👋</h2>
          <p>Quick overview of your training, schedule and recent activity.</p>
        </div>
        <div class="text-end d-none d-md-block">
          <button class="btn btn-outline-light me-2" onclick="clockIn()"><i class='bx bx-walk'></i> Clock In</button>
          <a href="{{ route('ess.lms') }}" class="btn btn-white">View Learning</a>
        </div>
      </div>

      <!-- Quick actions -->
      <div class="quick-actions">
        <a href="{{ route('ess.lms') }}" class="qa-card text-decoration-none">
          <div class="qa-icon" style="background:#eff6ff;color:#1e3a8a"><i class='bx bx-book-open'></i></div>
          <div>
            <div class="qa-title">Learning</div>
            <div class="qa-sub">Courses & quizzes</div>
          </div>
        </a>

        <a href="{{ route('ess.leave') }}" class="qa-card text-decoration-none">
          <div class="qa-icon" style="background:#fff7ed;color:#92400e"><i class='bx bx-calendar-event'></i></div>
          <div>
            <div class="qa-title">Time off</div>
            <div class="qa-sub">Apply / balances</div>
          </div>
        </a>

        <a href="{{ route('ess.payslip') }}" class="qa-card text-decoration-none">
          <div class="qa-icon" style="background:#ecfdf5;color:#066a50"><i class='bx bx-wallet'></i></div>
          <div>
            <div class="qa-title">Payslip</div>
            <div class="qa-sub">Salary & history</div>
          </div>
        </a>

        <a href="{{ route('ess.promotion-offers') }}" class="qa-card text-decoration-none">
          <div class="qa-icon" style="background:#f8edff;color:#6b21a8"><i class='bx bx-rocket'></i></div>
          <div>
            <div class="qa-title">Promotions</div>
            <div class="qa-sub">Opportunities</div>
          </div>
        </a>
      </div>

      <!-- Info row -->
      <div class="row mt-3 g-3">
        <div class="col-md-4">
          <div class="card card-clean p-3">
            <div class="d-flex align-items-center gap-3">
              <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#eef2ff,#e0f2fe);display:flex;align-items:center;justify-content:center"><i class='bx bx-briefcase' style="font-size:1.25rem;color:#075985"></i></div>
              <div>
                <div class="text-muted small">Job Title</div>
                <div id="emp-job-title" style="font-weight:700">Loading…</div>
              </div>
            </div>
            <hr style="margin:12px 0 0;border-color:#f1f5f9">
            <div class="d-flex justify-content-between mt-2">
              <small class="text-muted">Employment ID</small>
              <small style="font-weight:600">{{ Auth::user()->employee_id ?? 'N/A' }}</small>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-clean p-3">
            <div class="d-flex align-items-center gap-3">
              <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#ecfdf5,#d1fae5);display:flex;align-items:center;justify-content:center"><i class='bx bx-id-card' style="font-size:1.25rem;color:#065f46"></i></div>
              <div>
                <div class="text-muted small">Work Location</div>
                <div id="emp-location" style="font-weight:700">Loading…</div>
              </div>
            </div>
            <hr style="margin:12px 0 0;border-color:#f1f5f9">
            <div class="d-flex justify-content-between mt-2">
              <small class="text-muted">Status</small>
              <small class="text-success" style="font-weight:600">Active</small>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-clean p-3">
            <div class="d-flex align-items-center gap-3">
              <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#f8edff,#f3e8ff);display:flex;align-items:center;justify-content:center"><i class='bx bx-calendar' style="font-size:1.25rem;color:#6b21a8"></i></div>
              <div>
                <div class="text-muted small">Upcoming Shift</div>
                <div id="next-shift" style="font-weight:700">8:00 AM — 5:00 PM</div>
              </div>
            </div>
            <hr style="margin:12px 0 0;border-color:#f1f5f9">
            <div class="d-flex justify-content-between mt-2">
              <small class="text-muted">Next Day</small>
              <small style="font-weight:600">{{ now()->addDay()->format('D, M d') }}</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Two column content -->
      <div class="row mt-3 g-3">
        <div class="col-lg-6">
          <div class="card card-clean h-100">
            <div class="card-body activities p-0">
              <h5 class="mb-0 p-3">Recent Activities</h5>
              <div id="activities-container" class="list-group list-group-flush">
                <div class="list-group-item d-flex align-items-start">
                  <div style="width:36px;height:36px;border-radius:8px;background:#eef2ff;display:flex;align-items:center;justify-content:center;margin-right:.75rem"><i class='bx bx-loader-alt bx-spin' style="color:#2563eb"></i></div>
                  <div>
                    <div style="font-weight:600">Loading activities…</div>
                    <small class="text-muted">Please wait</small>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer bg-white text-center" style="border-top:1px solid #f1f5f9">
              <a href="{{ route('ess.lms') }}" class="text-decoration-none">View all activities</a>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card card-clean h-100">
            <div class="card-body p-0">
              <h5 class="mb-0 p-3">Work Schedule</h5>
              <div class="table-responsive">
                <table class="table schedule-table mb-0">
                  <thead>
                    <tr>
                      <th>Day</th>
                      <th>Shift</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody id="schedule-body">
                    <tr>
                      <td colspan="3" class="text-center py-4 text-muted">Loading schedule…</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer bg-white" style="border-top:1px solid #f1f5f9">
              <small class="text-muted">Schedules are maintained by HR. Contact your manager to request changes.</small>
            </div>
          </div>
        </div>
      </div>

      <footer class="mt-4 text-center text-muted small">© {{ date('Y') }} {{ config('app.name') }} — All rights reserved.</footer>

    </main>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- =========================
       SAFE LAYOUT SCRIPT
       ========================= -->
  <script>
    /*
      Wired API for ESS Dashboard
      - Drop this in at the bottom of resources/views/ess/dashboard.blade.php
      - Uses endpoints:
          /api/ess/employee/by-email/:email
          /api/ess/attendance/:employeeId
          /api/ess/activities/:email
      - Defensive, logs useful debug info, and provides safe fallbacks
    */

    (function () {
      const userEmail = '{{ Auth::user()->email ?? "" }}';
      const userEmployeeId = '{{ Auth::user()->employee_id ?? "" }}';
      const empJobTitleEl = document.getElementById('emp-job-title');
      const empLocationEl = document.getElementById('emp-location');
      const activitiesContainer = document.getElementById('activities-container');
      const scheduleBody = document.getElementById('schedule-body');
      const QUICK_ACTION_SELECTOR = '.quick-action-item';

      // Safe setter helpers
      function setText(el, text) { if (!el) return; el.textContent = text; }
      function showLoadingActivities() {
        activitiesContainer.innerHTML = `
          <div class="activity-item" id="activities-loading">
            <div class="activity-icon primary"><i class='bx bx-loader-alt bx-spin'></i></div>
            <div class="activity-content">
              <h6>Loading activities...</h6>
              <small>Please wait</small>
            </div>
          </div>`;
      }
      function renderEmptyActivities() {
        activitiesContainer.innerHTML = `
          <div class="activity-item">
            <div class="activity-icon" style="background: #f1f5f9; color: #94a3b8;">
              <i class='bx bx-info-circle'></i>
            </div>
            <div class="activity-content">
              <h6>No recent activities</h6>
              <small>Your activities will appear here as you complete trainings and assessments.</small>
            </div>
          </div>`;
      }

      // Convert 24-hour time "HH:MM:SS" or "HH:MM" to "h:mm AM/PM"
      function formatTime(timeStr) {
        if (!timeStr) return null;
        const parts = timeStr.split(':');
        if (parts.length < 2) return timeStr;
        const hours = parseInt(parts[0], 10);
        const minutes = parts[1];
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const h12 = hours % 12 || 12;
        return `${h12}:${minutes} ${ampm}`;
      }

      // Render schedule table rows
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

        const timeIn = attendance?.schedule_time_in ? formatTime(attendance.schedule_time_in) : '8:00 AM';
        const timeOut = attendance?.schedule_time_out ? formatTime(attendance.schedule_time_out) : '5:00 PM';
        const shiftText = `${timeIn} – ${timeOut}`;

        let html = '';
        days.forEach(day => {
          // Null means unknown — default Mon-Fri work, Sat/Sun rest
          const isWorkDay = (attendance && attendance[day.key] !== null && attendance[day.key] !== undefined)
            ? Number(attendance[day.key]) === 1
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

      // Render activities list
      function renderActivities(container, activities) {
        if (!Array.isArray(activities) || !activities.length) {
          renderEmptyActivities();
          return;
        }
        const html = activities.map(a => `
          <div class="activity-item">
            <div class="activity-icon ${a.icon_class || 'primary'}"><i class='bx ${a.icon || 'bx-check'}'></i></div>
            <div class="activity-content">
              <h6>${a.title || 'Activity'}</h6>
              <small>${a.description || ''}</small>
              <small class="d-block text-muted" style="font-size: 0.65rem;">${a.time_ago || ''}</small>
            </div>
          </div>`).join('');
        container.innerHTML = html;
      }

      // API calls
      async function fetchEmployeeByEmail(email) {
        if (!email) return null;
        try {
          const res = await fetch(`/api/ess/employee/by-email/${encodeURIComponent(email)}`, { credentials: 'same-origin' });
          if (!res.ok) { console.warn('employee API status', res.status); return null; }
          const json = await res.json();
          return json.success && json.employee ? json.employee : null;
        } catch (err) {
          console.error('fetchEmployeeByEmail error', err);
          return null;
        }
      }

      async function fetchAttendance(employeeId) {
        if (!employeeId) return null;
        try {
          const res = await fetch(`/api/ess/attendance/${encodeURIComponent(employeeId)}`, { credentials: 'same-origin' });
          if (!res.ok) { console.warn('attendance API status', res.status); return null; }
          const json = await res.json();
          return json.success && json.attendance ? json.attendance : null;
        } catch (err) {
          console.error('fetchAttendance error', err);
          return null;
        }
      }

      async function fetchActivities(email) {
        if (!email) return [];
        try {
          const res = await fetch(`/api/ess/activities/${encodeURIComponent(email)}`, { credentials: 'same-origin' });
          if (!res.ok) { console.warn('activities API status', res.status); return []; }
          const json = await res.json();
          return json.success && Array.isArray(json.activities) ? json.activities : [];
        } catch (err) {
          console.error('fetchActivities error', err);
          return [];
        }
      }

      // Wire quick action items to routes — keep them usable and safe
      function wireQuickActions() {
        document.querySelectorAll(QUICK_ACTION_SELECTOR).forEach(el => {
          el.addEventListener('click', (ev) => {
            // Default anchor behavior if element is an <a>
            if (el.tagName.toLowerCase() === 'a') return;
            const href = el.getAttribute('data-href');
            if (href) window.location.href = href;
          });
        });
      }

      // Main init
      async function initDashboard() {
        // sensible defaults
        setText(empJobTitleEl, 'Loading...');
        setText(empLocationEl, 'Loading...');
        showLoadingActivities();
        renderDefaultSchedule(scheduleBody);

        // 1) employee details
        const emp = await fetchEmployeeByEmail(userEmail);
        if (emp) {
          // job may be nested or top-level — be tolerant
          const jobTitle = emp.job?.job_title || emp.job_title || emp.position || 'Not Assigned';
          setText(empJobTitleEl, jobTitle);
          setText(empLocationEl, emp.work_location || emp.office || 'Main Office');
        } else {
          setText(empJobTitleEl, 'Not Assigned');
          setText(empLocationEl, 'Main Office');
        }

        // 2) schedule/attendance
        const attendance = await fetchAttendance(userEmployeeId || (emp && (emp.employee_id || emp.id)) || '');
        if (attendance) {
          renderSchedule(scheduleBody, attendance);
        } else {
          renderDefaultSchedule(scheduleBody);
        }

        // 3) recent activities
        const activities = await fetchActivities(userEmail || (emp && (emp.email || '')));
        if (activities && activities.length) {
          renderActivities(activitiesContainer, activities);
        } else {
          renderEmptyActivities(activitiesContainer);
        }

        // wire quick actions (if any were not <a> but divs)
        wireQuickActions();
      }

      // run
      document.addEventListener('DOMContentLoaded', initDashboard);
    })();
  </script>
</body>
</html>