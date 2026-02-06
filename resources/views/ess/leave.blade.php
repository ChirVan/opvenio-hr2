<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Request Leave - {{ config('app.name') }}</title>

    <!-- Bootstrap 5 -->
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

        /* Cards */
        .section-card {
            background: white;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,0,0,0.04);
            overflow: hidden;
            height: 100%;
        }

        .section-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
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
            font-size: 1.1rem;
        }

        .section-body {
            padding: 1.25rem;
        }

        /* Form Styles */
        .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.35rem;
        }

        .form-control,
        .form-select {
            font-size: 0.85rem;
            padding: 0.6rem 0.875rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-size: 0.8rem;
        }

        textarea.form-control {
            resize: none;
        }

        .btn-submit {
            background: var(--primary);
            border: none;
            color: white;
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: var(--primary-dark);
            color: white;
        }

        .btn-submit:disabled {
            opacity: 0.7;
        }

        /* Leave Balance Cards */
        .balance-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .balance-card {
            background: var(--light);
            border-radius: 10px;
            padding: 0.875rem;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .balance-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-size: 1rem;
        }

        .balance-icon.vacation { background: #dbeafe; color: #2563eb; }
        .balance-icon.sick { background: #fee2e2; color: #dc2626; }
        .balance-icon.emergency { background: #fef3c7; color: #d97706; }
        .balance-icon.personal { background: #f3e8ff; color: #7c3aed; }
        .balance-icon.bereavement { background: #fce7f3; color: #db2777; }
        .balance-icon.maternity { background: #fdf2f8; color: #ec4899; }
        .balance-icon.paternity { background: #e0f2fe; color: #0284c7; }
        .balance-icon.solo-parent { background: #f0fdf4; color: #16a34a; }
        .balance-icon.incentive { background: #fef9c3; color: #ca8a04; }
        .balance-icon.lwop { background: #f1f5f9; color: #64748b; }

        .leave-type-icon.bereavement { background: #fce7f3; color: #db2777; }
        .leave-type-icon.maternity { background: #fdf2f8; color: #ec4899; }
        .leave-type-icon.paternity { background: #e0f2fe; color: #0284c7; }
        .leave-type-icon.solo-parent { background: #f0fdf4; color: #16a34a; }
        .leave-type-icon.incentive { background: #fef9c3; color: #ca8a04; }
        .leave-type-icon.lwop { background: #f1f5f9; color: #64748b; }

        .balance-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
            line-height: 1;
        }

        .balance-label {
            font-size: 0.65rem;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.25rem;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
        }

        .leave-table {
            width: 100%;
            margin: 0;
        }

        .leave-table thead th {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--secondary);
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border: none;
            white-space: nowrap;
        }

        .leave-table tbody td {
            font-size: 0.8rem;
            padding: 0.75rem 1rem;
            border: none;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .leave-table tbody tr:last-child td {
            border-bottom: none;
        }

        .leave-table tbody tr:hover {
            background: #fafbfc;
        }

        /* Status Badges */
        .status-badge {
            font-size: 0.65rem;
            font-weight: 500;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .status-badge.approved {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Leave Type Badge */
        .leave-type {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .leave-type-icon {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }

        .leave-type-icon.vacation { background: #dbeafe; color: #2563eb; }
        .leave-type-icon.sick { background: #fee2e2; color: #dc2626; }
        .leave-type-icon.emergency { background: #fef3c7; color: #d97706; }
        .leave-type-icon.personal { background: #f3e8ff; color: #7c3aed; }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--secondary);
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 0.85rem;
            margin-bottom: 0;
        }

        /* Date Range */
        .date-range {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        .date-range .date {
            color: var(--dark);
            font-weight: 500;
        }

        /* Remarks */
        .remarks-text {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 0.75rem;
            color: var(--secondary);
        }

        /* Calendar Styles */
        .leave-calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
        }

        .calendar-header {
            text-align: center;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--secondary);
            padding: 0.75rem 0;
            text-transform: uppercase;
            background: #f1f5f9;
            border-radius: 6px;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 0.35rem;
            font-size: 0.8rem;
            border-radius: 8px;
            background: #f8fafc;
            position: relative;
            min-height: 55px;
            transition: all 0.2s ease;
        }

        .calendar-day.today {
            background: var(--primary-light);
            font-weight: 700;
            border: 2px solid var(--primary);
        }

        .calendar-day.other-month {
            opacity: 0.3;
            background: transparent;
        }

        .calendar-day .day-number {
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 3px;
        }

        .calendar-day .leave-badge {
            font-size: 0.55rem;
            font-weight: 600;
            padding: 0.15rem 0.35rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .calendar-day .leave-badge.approved {
            background: #d1fae5;
            color: #065f46;
        }

        .calendar-day .leave-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .calendar-day .leave-badge.rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .calendar-day.has-leave {
            cursor: pointer;
        }

        .calendar-day.has-leave:hover {
            transform: scale(1.08);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10;
        }

        .legend-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .calendar-tooltip {
            position: fixed;
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.18);
            padding: 1rem;
            font-size: 0.8rem;
            z-index: 1000;
            max-width: 220px;
            pointer-events: none;
            border: 1px solid #e2e8f0;
        }

        .calendar-tooltip .tooltip-title {
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 0.35rem;
            color: var(--dark);
        }

        .calendar-tooltip .tooltip-dates {
            color: var(--secondary);
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .calendar-tooltip .tooltip-status {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .calendar-tooltip .tooltip-status.approved {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .calendar-tooltip .tooltip-status.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .calendar-tooltip .tooltip-status.rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .balance-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 1.25rem;
            }
            .page-header h2 {
                font-size: 1.25rem;
            }
            .balance-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    @include('layouts.ess-navbar-bootstrap')

    <div class="container py-4" style="margin-top: 76px; max-width: 1200px;">
        <!-- Header -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Request Leave</h2>
                    <p>Submit and track your leave applications</p>
                </div>
                <a href="{{ route('ess.dashboard') }}" class="btn btn-back">
                    <i class='bx bx-arrow-back me-1'></i>Back
                </a>
            </div>
        </div>

        <div class="row g-3">
            <!-- Request Form -->
            <div class="col-lg-5">
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class='bx bx-edit-alt'></i>New Leave Request
                        </h5>
                    </div>
                    <div class="section-body">
                        <!-- Leave Balance Cards (Dynamic) -->
                        <div class="balance-grid" id="balanceGrid">
                            <div class="balance-card">
                                <div class="balance-icon vacation">
                                    <i class='bx bx-loader-alt bx-spin'></i>
                                </div>
                                <div class="balance-value">--</div>
                                <div class="balance-label">Loading...</div>
                            </div>
                        </div>

                        <form id="leaveForm">
                            <div class="mb-3">
                                <label for="leaveType" class="form-label">Leave Type</label>
                                <select class="form-select" id="leaveType" required>
                                    <option value="">Loading leave types...</option>
                                </select>
                                <div id="leaveTypeInfo" class="form-text text-muted" style="font-size: 0.7rem; margin-top: 0.25rem;"></div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label for="startDate" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" required>
                                </div>
                                <div class="col-6">
                                    <label for="endDate" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="endDate" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="isHalfDay">
                                    <label class="form-check-label" for="isHalfDay" style="font-size: 0.8rem;">
                                        Half-day leave
                                    </label>
                                </div>
                                <div id="halfDayOptions" class="mt-2" style="display: none;">
                                    <div class="btn-group btn-group-sm w-100" role="group">
                                        <input type="radio" class="btn-check" name="halfDayPeriod" id="halfDayAM" value="AM">
                                        <label class="btn btn-outline-primary" for="halfDayAM">Morning (AM)</label>
                                        <input type="radio" class="btn-check" name="halfDayPeriod" id="halfDayPM" value="PM">
                                        <label class="btn btn-outline-primary" for="halfDayPM">Afternoon (PM)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3" id="daysInfoBox" style="display: none;">
                                <div class="alert alert-info py-2 mb-0" style="font-size: 0.75rem;">
                                    <i class='bx bx-info-circle me-1'></i>
                                    <span id="daysRequestedText">0 day(s) will be deducted</span>
                                </div>
                            </div>

                            <div class="mb-3" id="validationWarnings" style="display: none;">
                                <div class="alert alert-warning py-2 mb-0" style="font-size: 0.75rem;">
                                    <i class='bx bx-error me-1'></i>
                                    <span id="validationWarningsText"></span>
                                </div>
                            </div>

                            <div class="mb-3" id="validationErrors" style="display: none;">
                                <div class="alert alert-danger py-2 mb-0" style="font-size: 0.75rem;">
                                    <i class='bx bx-x-circle me-1'></i>
                                    <span id="validationErrorsText"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason</label>
                                <textarea class="form-control" id="reason" rows="3" placeholder="Briefly explain your reason..." required></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-submit" id="submitBtn">
                                    <i class='bx bx-send me-1'></i>Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Leave Calendar -->
            <div class="col-lg-7">
                <div class="section-card">
                    <div class="section-header d-flex justify-content-between align-items-center">
                        <h5 class="section-title">
                            <i class='bx bx-calendar'></i>My Leave Calendar
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" id="pendingBadge" style="font-size: 0.7rem;">No Pending</span>
                            <button class="btn btn-sm btn-outline-secondary" id="prevMonth" style="padding: 0.25rem 0.5rem;">
                                <i class='bx bx-chevron-left'></i>
                            </button>
                            <span id="currentMonthYear" style="font-size: 0.85rem; font-weight: 600; min-width: 120px; text-align: center;"></span>
                            <button class="btn btn-sm btn-outline-secondary" id="nextMonth" style="padding: 0.25rem 0.5rem;">
                                <i class='bx bx-chevron-right'></i>
                            </button>
                        </div>
                    </div>
                    <div class="section-body p-3">
                        <div id="leaveCalendar" class="leave-calendar"></div>
                        <div class="calendar-legend mt-3 d-flex gap-4 justify-content-center flex-wrap" style="font-size: 0.7rem;">
                            <span><span class="legend-dot" style="background: #10b981;"></span> Approved</span>
                            <span><span class="legend-dot" style="background: #f59e0b;"></span> Pending</span>
                            <span><span class="legend-dot" style="background: #ef4444;"></span> Rejected</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const userEmail = '{{ Auth::user()->email ?? "" }}';
        const employeeId = '{{ Auth::user()->employee_id ?? Auth::user()->id }}';
        const employeeName = '{{ Auth::user()->name ?? "Unknown" }}';
        
        let leaveBalances = [];
        let selectedLeaveType = null;
        let calendarMonth = new Date().getMonth() + 1;
        let calendarYear = new Date().getFullYear();
        let calendarEvents = [];

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadLeaveBalances();
            loadCalendar();

            // Calendar navigation
            document.getElementById('prevMonth').addEventListener('click', function() {
                calendarMonth--;
                if (calendarMonth < 1) {
                    calendarMonth = 12;
                    calendarYear--;
                }
                loadCalendar();
            });

            document.getElementById('nextMonth').addEventListener('click', function() {
                calendarMonth++;
                if (calendarMonth > 12) {
                    calendarMonth = 1;
                    calendarYear++;
                }
                loadCalendar();
            });
            
            // Set min date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('startDate').min = today;
            document.getElementById('endDate').min = today;

            // Half-day checkbox toggle
            document.getElementById('isHalfDay').addEventListener('change', function() {
                document.getElementById('halfDayOptions').style.display = this.checked ? 'block' : 'none';
                if (this.checked) {
                    document.getElementById('endDate').value = document.getElementById('startDate').value;
                    document.getElementById('endDate').disabled = true;
                    document.getElementById('halfDayAM').checked = true;
                } else {
                    document.getElementById('endDate').disabled = false;
                }
                updateDaysInfo();
            });

            // Date change listeners
            document.getElementById('startDate').addEventListener('change', function() {
                document.getElementById('endDate').min = this.value;
                if (document.getElementById('endDate').value < this.value) {
                    document.getElementById('endDate').value = this.value;
                }
                if (document.getElementById('isHalfDay').checked) {
                    document.getElementById('endDate').value = this.value;
                }
                updateDaysInfo();
                validateRequest();
            });

            document.getElementById('endDate').addEventListener('change', function() {
                updateDaysInfo();
                validateRequest();
            });

            // Leave type change listener
            document.getElementById('leaveType').addEventListener('change', function() {
                const typeId = this.value;
                selectedLeaveType = leaveBalances.find(b => b.leave_type_id == typeId);
                updateLeaveTypeInfo();
                validateRequest();
            });
        });

        // Load leave balances from API
        async function loadLeaveBalances() {
            try {
                const response = await fetch(`/api/leaves/balances/${encodeURIComponent(userEmail)}`);
                const result = await response.json();

                if (result.success && result.data) {
                    leaveBalances = result.data;
                    renderBalanceCards(result.data);
                    renderLeaveTypeOptions(result.data);
                } else {
                    renderFallbackBalances();
                }
            } catch (error) {
                console.error('Error loading balances:', error);
                renderFallbackBalances();
            }
        }

        // Render balance cards dynamically
        function renderBalanceCards(balances) {
            const grid = document.getElementById('balanceGrid');
            
            // Show only main leave types (VL, SL, EL, SIL)
            const mainTypes = ['VL', 'SL', 'EL', 'SIL'];
            const mainBalances = balances.filter(b => mainTypes.includes(b.code));
            
            let html = '';
            mainBalances.forEach(balance => {
                html += `
                    <div class="balance-card" title="${balance.name}">
                        <div class="balance-icon ${balance.color_class}">
                            <i class='bx ${balance.icon}'></i>
                        </div>
                        <div class="balance-value">${balance.available_credits}</div>
                        <div class="balance-label">${balance.code}</div>
                    </div>
                `;
            });
            
            grid.innerHTML = html;
        }

        // Render leave type dropdown options
        function renderLeaveTypeOptions(balances) {
            const select = document.getElementById('leaveType');
            let html = '<option value="">Select leave type...</option>';
            
            balances.forEach(balance => {
                const disabled = balance.available_credits <= 0 ? 'disabled' : '';
                const credits = balance.available_credits > 0 
                    ? `(${balance.available_credits} days available)` 
                    : '(No credits)';
                    
                html += `<option value="${balance.leave_type_id}" ${disabled}>
                    ${balance.name} ${credits}
                </option>`;
            });
            
            select.innerHTML = html;
        }

        // Fallback for when API fails
        function renderFallbackBalances() {
            const grid = document.getElementById('balanceGrid');
            grid.innerHTML = `
                <div class="balance-card">
                    <div class="balance-icon" style="background: #f1f5f9; color: #94a3b8;">
                        <i class='bx bx-info-circle'></i>
                    </div>
                    <div class="balance-value">--</div>
                    <div class="balance-label">Unavailable</div>
                </div>
            `;
            
            const select = document.getElementById('leaveType');
            select.innerHTML = `
                <option value="">Select leave type...</option>
                <option value="Vacation Leave">Vacation Leave</option>
                <option value="Sick Leave">Sick Leave</option>
                <option value="Emergency Leave">Emergency Leave</option>
            `;
        }

        // Load calendar from API
        async function loadCalendar() {
            try {
                const response = await fetch(`/api/leaves/calendar/${encodeURIComponent(userEmail)}?month=${calendarMonth}&year=${calendarYear}`);
                const result = await response.json();

                if (result.success) {
                    calendarEvents = result.events || [];
                    renderCalendar();
                    
                    // Update pending badge
                    const badge = document.getElementById('pendingBadge');
                    const hasPending = result.pending_count > 0;
                    const pendingClass = hasPending ? 'bg-warning text-dark' : 'bg-success';
                    badge.className = `badge ${pendingClass}`;
                    badge.style.fontSize = '0.7rem';
                    badge.textContent = hasPending ? 'Pending Request' : 'No Pending';
                    
                    // Disable/enable form based on pending status
                    const form = document.getElementById('leaveForm');
                    const submitBtn = document.getElementById('submitBtn');
                    const formInputs = form.querySelectorAll('input, select, textarea');
                    
                    if (hasPending) {
                        formInputs.forEach(input => input.disabled = true);
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="bx bx-lock me-1"></i>Pending Request Active';
                        
                        // Show notice in validation area
                        const warningsDiv = document.getElementById('validationWarnings');
                        warningsDiv.innerHTML = `
                            <div class="d-flex align-items-start gap-2">
                                <i class='bx bx-info-circle text-warning' style="font-size: 1rem; margin-top: 2px;"></i>
                                <div>
                                    <strong>Leave request pending</strong><br>
                                    <small>You cannot submit a new request while you have a pending one. Please wait for your current request to be approved or rejected.</small>
                                </div>
                            </div>
                        `;
                        warningsDiv.style.display = 'block';
                    } else {
                        formInputs.forEach(input => {
                            // Don't re-enable end date if half-day is checked
                            if (input.id === 'endDate' && document.getElementById('isHalfDay').checked) {
                                return;
                            }
                            input.disabled = false;
                        });
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bx bx-send me-1"></i>Submit Request';
                        document.getElementById('validationWarnings').style.display = 'none';
                    }
                } else {
                    renderCalendar();
                }
            } catch (error) {
                console.error('Error loading calendar:', error);
                renderCalendar();
            }
        }

        // Get leave type code from full name
        function getLeaveTypeCode(leaveType) {
            const codes = {
                'Vacation Leave': 'VL',
                'Sick Leave': 'SL',
                'Emergency Leave': 'EL',
                'Bereavement Leave': 'BL',
                'Maternity Leave': 'ML',
                'Paternity Leave': 'PL',
                'Solo Parent Leave': 'SPL',
                'Service Incentive Leave': 'SIL',
                'Leave Without Pay': 'LWOP'
            };
            return codes[leaveType] || leaveType.substring(0, 2).toUpperCase();
        }

        // Render the calendar
        function renderCalendar() {
            const container = document.getElementById('leaveCalendar');
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                               'July', 'August', 'September', 'October', 'November', 'December'];
            const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            
            // Update month/year display
            document.getElementById('currentMonthYear').textContent = `${monthNames[calendarMonth - 1]} ${calendarYear}`;
            
            // Get first day of month and number of days
            const firstDay = new Date(calendarYear, calendarMonth - 1, 1);
            const lastDay = new Date(calendarYear, calendarMonth, 0);
            const daysInMonth = lastDay.getDate();
            const startDayOfWeek = firstDay.getDay();
            
            // Get today for highlighting
            const today = new Date();
            const todayStr = today.toISOString().split('T')[0];
            
            let html = '';
            
            // Day headers
            dayNames.forEach(day => {
                html += `<div class="calendar-header">${day}</div>`;
            });
            
            // Previous month days
            const prevMonth = new Date(calendarYear, calendarMonth - 1, 0);
            const prevMonthDays = prevMonth.getDate();
            for (let i = startDayOfWeek - 1; i >= 0; i--) {
                const dayNum = prevMonthDays - i;
                html += `<div class="calendar-day other-month"><span class="day-number">${dayNum}</span></div>`;
            }
            
            // Current month days
            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = `${calendarYear}-${String(calendarMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isToday = dateStr === todayStr;
                
                // Find events for this day
                const dayEvents = calendarEvents.filter(event => {
                    const start = event.start;
                    const end = event.end;
                    return dateStr >= start && dateStr < end;
                });
                
                const hasLeave = dayEvents.length > 0;
                let classes = ['calendar-day'];
                if (isToday) classes.push('today');
                if (hasLeave) classes.push('has-leave');
                
                // Show leave type badge instead of dots
                let leaveBadge = '';
                if (hasLeave) {
                    const event = dayEvents[0]; // Show first event's type
                    const statusClass = event.status === 'approved' ? 'approved' : 
                                       event.status === 'rejected' ? 'rejected' : 'pending';
                    // Get leave type code (e.g., VL, SL, EL)
                    const typeCode = getLeaveTypeCode(event.leave_type);
                    leaveBadge = `<span class="leave-badge ${statusClass}">${typeCode}</span>`;
                }
                
                const dataEvents = hasLeave ? `data-events='${JSON.stringify(dayEvents)}'` : '';
                
                html += `<div class="${classes.join(' ')}" ${dataEvents} data-date="${dateStr}">
                    <span class="day-number">${day}</span>
                    ${leaveBadge}
                </div>`;
            }
            
            // Next month days
            const totalCells = startDayOfWeek + daysInMonth;
            const remainingCells = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
            for (let i = 1; i <= remainingCells; i++) {
                html += `<div class="calendar-day other-month"><span class="day-number">${i}</span></div>`;
            }
            
            container.innerHTML = html;
            
            // Add hover tooltips for leave days
            container.querySelectorAll('.calendar-day.has-leave').forEach(day => {
                day.addEventListener('mouseenter', showTooltip);
                day.addEventListener('mouseleave', hideTooltip);
            });
        }

        // Show tooltip on hover
        function showTooltip(e) {
            const events = JSON.parse(e.target.closest('.calendar-day').dataset.events || '[]');
            if (events.length === 0) return;
            
            let tooltipHtml = '';
            events.forEach(event => {
                const statusClass = event.status === 'approved' ? 'approved' : 
                                   event.status === 'rejected' ? 'rejected' : 'pending';
                const startDate = new Date(event.start).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                const endDate = new Date(event.end);
                endDate.setDate(endDate.getDate() - 1);
                const endDateStr = endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                
                tooltipHtml += `
                    <div class="tooltip-title">${event.leave_type}</div>
                    <div class="tooltip-dates">${startDate} - ${endDateStr} (${event.days} day${event.days > 1 ? 's' : ''})</div>
                    <span class="tooltip-status ${statusClass}">${event.status.charAt(0).toUpperCase() + event.status.slice(1)}</span>
                `;
            });
            
            // Create tooltip
            const tooltip = document.createElement('div');
            tooltip.className = 'calendar-tooltip';
            tooltip.innerHTML = tooltipHtml;
            document.body.appendChild(tooltip);
            
            // Position tooltip
            const rect = e.target.getBoundingClientRect();
            tooltip.style.left = `${rect.left + rect.width / 2 - tooltip.offsetWidth / 2}px`;
            tooltip.style.top = `${rect.bottom + 8}px`;
        }

        // Hide tooltip
        function hideTooltip() {
            document.querySelectorAll('.calendar-tooltip').forEach(t => t.remove());
        }

        // Update leave type info display
        function updateLeaveTypeInfo() {
            const infoDiv = document.getElementById('leaveTypeInfo');
            
            if (!selectedLeaveType) {
                infoDiv.innerHTML = '';
                return;
            }

            let info = [];
            if (selectedLeaveType.advance_notice_days > 0) {
                info.push(`Requires ${selectedLeaveType.advance_notice_days} day(s) advance notice`);
            }
            if (selectedLeaveType.max_days_per_request) {
                info.push(`Max ${selectedLeaveType.max_days_per_request} days per request`);
            }
            if (selectedLeaveType.max_per_month) {
                info.push(`Max ${selectedLeaveType.max_per_month} request(s)/month`);
            }
            if (selectedLeaveType.requires_medical_cert) {
                info.push(`⚠️ Requires medical certificate`);
            }
            if (!selectedLeaveType.is_paid) {
                info.push(`⚠️ Unpaid leave`);
            }
            
            infoDiv.innerHTML = info.join(' • ');
        }

        // Update days info box
        function updateDaysInfo() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const isHalfDay = document.getElementById('isHalfDay').checked;
            const daysBox = document.getElementById('daysInfoBox');
            const daysText = document.getElementById('daysRequestedText');

            if (!startDate || !endDate) {
                daysBox.style.display = 'none';
                return;
            }

            const start = new Date(startDate);
            const end = new Date(endDate);
            let days = isHalfDay ? 0.5 : Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;

            daysText.textContent = `${days} day(s) will be deducted from your balance`;
            daysBox.style.display = 'block';
        }

        // Validate leave request against rules
        async function validateRequest() {
            const typeId = document.getElementById('leaveType').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const isHalfDay = document.getElementById('isHalfDay').checked;
            const halfDayPeriod = document.querySelector('input[name="halfDayPeriod"]:checked')?.value;

            const warningsDiv = document.getElementById('validationWarnings');
            const errorsDiv = document.getElementById('validationErrors');
            const submitBtn = document.getElementById('submitBtn');

            // Reset
            warningsDiv.style.display = 'none';
            errorsDiv.style.display = 'none';
            submitBtn.disabled = false;

            if (!typeId || !startDate || !endDate) {
                return;
            }

            try {
                const response = await fetch('/api/leaves/validate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        employee_email: userEmail,
                        leave_type_id: typeId,
                        start_date: startDate,
                        end_date: endDate,
                        is_half_day: isHalfDay,
                        half_day_period: halfDayPeriod
                    })
                });

                const result = await response.json();

                if (result.warnings && result.warnings.length > 0) {
                    document.getElementById('validationWarningsText').textContent = result.warnings.join(' ');
                    warningsDiv.style.display = 'block';
                }

                if (result.errors && result.errors.length > 0) {
                    document.getElementById('validationErrorsText').textContent = result.errors.join(' ');
                    errorsDiv.style.display = 'block';
                    submitBtn.disabled = true;
                }

            } catch (error) {
                console.error('Validation error:', error);
            }
        }

        // Get leave type icon class
        function getLeaveTypeIcon(typeName) {
            const balance = leaveBalances.find(b => b.name === typeName);
            if (balance) {
                return { icon: balance.icon, class: balance.color_class };
            }
            // Fallback icons
            const icons = {
                'Vacation Leave': { icon: 'bx-sun', class: 'vacation' },
                'Sick Leave': { icon: 'bx-plus-medical', class: 'sick' },
                'Emergency Leave': { icon: 'bx-error', class: 'emergency' },
                'Bereavement Leave': { icon: 'bx-heart', class: 'bereavement' },
                'Maternity Leave': { icon: 'bx-female', class: 'maternity' },
                'Paternity Leave': { icon: 'bx-male', class: 'paternity' },
                'Solo Parent Leave': { icon: 'bx-user-check', class: 'solo-parent' },
                'Service Incentive Leave': { icon: 'bx-gift', class: 'incentive' },
                'Leave Without Pay': { icon: 'bx-wallet', class: 'lwop' },
            };
            return icons[typeName] || { icon: 'bx-calendar', class: 'vacation' };
        }

        // Submit leave request
        document.getElementById('leaveForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const typeId = document.getElementById('leaveType').value;
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;
            const reason = document.getElementById('reason').value;
            const isHalfDay = document.getElementById('isHalfDay').checked;
            const halfDayPeriod = document.querySelector('input[name="halfDayPeriod"]:checked')?.value;

            if (!typeId || !start || !end || !reason) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Fields',
                    text: 'Please fill out all required fields.',
                    confirmButtonColor: '#10b981'
                });
                return;
            }

            if (isHalfDay && !halfDayPeriod) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Select Period',
                    text: 'Please select AM or PM for half-day leave.',
                    confirmButtonColor: '#10b981'
                });
                return;
            }

            // Get leave type name
            const leaveTypeName = selectedLeaveType?.name || 'Leave';

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Submitting...';

            try {
                const response = await fetch('/api/leaves', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        employee_id: employeeId,
                        employee_name: employeeName,
                        employee_email: userEmail,
                        leave_type: leaveTypeName,
                        leave_type_id: typeId,
                        start_date: start,
                        end_date: end,
                        is_half_day: isHalfDay,
                        half_day_period: halfDayPeriod,
                        reason: reason
                    })
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Request Submitted!',
                        text: 'Your leave request has been submitted successfully and is awaiting approval.',
                        confirmButtonColor: '#10b981'
                    });
                    this.reset();
                    document.getElementById('halfDayOptions').style.display = 'none';
                    document.getElementById('daysInfoBox').style.display = 'none';
                    document.getElementById('validationWarnings').style.display = 'none';
                    document.getElementById('validationErrors').style.display = 'none';
                    document.getElementById('leaveTypeInfo').innerHTML = '';
                    loadLeaveBalances(); // Refresh balances
                    loadCalendar(); // Refresh calendar
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Submission Failed',
                        text: result.message || 'An unknown error occurred.',
                        confirmButtonColor: '#10b981'
                    });
                }
            } catch (error) {
                console.error('Error submitting leave:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while submitting your request. Please try again.',
                    confirmButtonColor: '#10b981'
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bx bx-send me-1"></i>Submit Request';
            }
        });
    </script>
</body>
</html>
