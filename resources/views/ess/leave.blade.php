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
                        <!-- Leave Balance Cards -->
                        <div class="balance-grid">
                            <div class="balance-card">
                                <div class="balance-icon vacation">
                                    <i class='bx bx-sun'></i>
                                </div>
                                <div class="balance-value">15</div>
                                <div class="balance-label">Vacation</div>
                            </div>
                            <div class="balance-card">
                                <div class="balance-icon sick">
                                    <i class='bx bx-plus-medical'></i>
                                </div>
                                <div class="balance-value">10</div>
                                <div class="balance-label">Sick</div>
                            </div>
                            <div class="balance-card">
                                <div class="balance-icon emergency">
                                    <i class='bx bx-error'></i>
                                </div>
                                <div class="balance-value">3</div>
                                <div class="balance-label">Emergency</div>
                            </div>
                            <div class="balance-card">
                                <div class="balance-icon personal">
                                    <i class='bx bx-user'></i>
                                </div>
                                <div class="balance-value">5</div>
                                <div class="balance-label">Personal</div>
                            </div>
                        </div>

                        <form id="leaveForm">
                            <div class="mb-3">
                                <label for="leaveType" class="form-label">Leave Type</label>
                                <select class="form-select" id="leaveType" required>
                                    <option value="">Select leave type...</option>
                                    <option value="Vacation">Vacation Leave</option>
                                    <option value="Sick">Sick Leave</option>
                                    <option value="Emergency">Emergency Leave</option>
                                    <option value="Personal">Personal Leave</option>
                                </select>
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
                                <label for="reason" class="form-label">Reason</label>
                                <textarea class="form-control" id="reason" rows="3" placeholder="Briefly explain your reason..." required></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-submit">
                                    <i class='bx bx-send me-1'></i>Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Leave History -->
            <div class="col-lg-7">
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class='bx bx-history'></i>Leave History
                        </h5>
                    </div>
                    <div class="section-body p-0">
                        <div class="table-container">
                            <table class="leave-table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Date Range</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody id="leaveHistory">
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state">
                                                <i class='bx bx-loader-alt bx-spin'></i>
                                                <p>Loading leave history...</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Load leave history on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadLeaveHistory();
            
            // Set min date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('startDate').min = today;
            document.getElementById('endDate').min = today;
        });

        // Get leave type icon class
        function getLeaveTypeIcon(type) {
            const icons = {
                'Vacation': { icon: 'bx-sun', class: 'vacation' },
                'Sick': { icon: 'bx-plus-medical', class: 'sick' },
                'Emergency': { icon: 'bx-error', class: 'emergency' },
                'Personal': { icon: 'bx-user', class: 'personal' }
            };
            return icons[type] || { icon: 'bx-calendar', class: 'vacation' };
        }

        // Fetch and display leave history
        async function loadLeaveHistory() {
            try {
                const userEmail = '{{ Auth::user()->email ?? "" }}';
                const response = await fetch(`/api/leaves?employee_email=${encodeURIComponent(userEmail)}`);
                const result = await response.json();

                if (result.success && result.data.length > 0) {
                    const tbody = document.getElementById('leaveHistory');
                    tbody.innerHTML = '';

                    result.data.forEach(leave => {
                        const startDate = new Date(leave.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                        const endDate = new Date(leave.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                        
                        const typeInfo = getLeaveTypeIcon(leave.leave_type);
                        
                        let statusBadge = '';
                        switch(leave.status) {
                            case 'approved':
                                statusBadge = '<span class="status-badge approved"><i class="bx bx-check"></i>Approved</span>';
                                break;
                            case 'rejected':
                                statusBadge = '<span class="status-badge rejected"><i class="bx bx-x"></i>Rejected</span>';
                                break;
                            default:
                                statusBadge = '<span class="status-badge pending"><i class="bx bx-time"></i>Pending</span>';
                        }

                        const row = `
                            <tr>
                                <td>
                                    <div class="leave-type">
                                        <span class="leave-type-icon ${typeInfo.class}">
                                            <i class="bx ${typeInfo.icon}"></i>
                                        </span>
                                        <span>${leave.leave_type}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-range">
                                        <span class="date">${startDate}</span> - <span class="date">${endDate}</span>
                                    </div>
                                </td>
                                <td>${statusBadge}</td>
                                <td>
                                    <span class="remarks-text" title="${leave.remarks || leave.reason || '-'}">${leave.remarks || leave.reason || '-'}</span>
                                </td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    document.getElementById('leaveHistory').innerHTML = `
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class='bx bx-calendar-x'></i>
                                    <p>No leave requests found</p>
                                </div>
                            </td>
                        </tr>
                    `;
                }
            } catch (error) {
                console.error('Error loading leave history:', error);
                document.getElementById('leaveHistory').innerHTML = `
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class='bx bx-error-circle'></i>
                                <p>Failed to load leave history</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
        }

        // Update end date min when start date changes
        document.getElementById('startDate').addEventListener('change', function() {
            document.getElementById('endDate').min = this.value;
            if (document.getElementById('endDate').value < this.value) {
                document.getElementById('endDate').value = this.value;
            }
        });

        // Submit leave request
        document.getElementById('leaveForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const type = document.getElementById('leaveType').value;
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;
            const reason = document.getElementById('reason').value;

            if (!type || !start || !end || !reason) {
                alert('⚠️ Please fill out all fields.');
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
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
                        employee_id: '{{ Auth::user()->employee_id ?? Auth::user()->id }}',
                        employee_name: '{{ Auth::user()->name ?? "Unknown" }}',
                        employee_email: '{{ Auth::user()->email ?? "" }}',
                        leave_type: type,
                        start_date: start,
                        end_date: end,
                        reason: reason
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert('✅ Leave request submitted successfully!');
                    this.reset();
                    loadLeaveHistory();
                } else {
                    alert('❌ Failed to submit: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error submitting leave:', error);
                alert('❌ An error occurred while submitting your request.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bx bx-send me-1"></i>Submit Request';
            }
        });
    </script>
</body>
</html>
