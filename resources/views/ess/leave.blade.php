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
        label {
            font-weight: 500;
            color: #444;
        }
        .badge {
            font-size: 0.8rem;
        }
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    @include('layouts.ess-navbar-bootstrap')

    <div class="container-fluid py-4" style="margin-top: 80px;">
        <!-- Header -->
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">Request Leave</h2>
                <p class="mb-0 opacity-75">Submit and track your leave applications</p>
            </div>
            <a href="{{ route('ess.dashboard') }}" class="btn btn-light text-success fw-semibold">
                <i class='bx bx-arrow-back me-2'></i>Back to Dashboard
            </a>
        </div>

        <div class="row g-4">
            <!-- Request Form -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="mb-0 fw-semibold text-success">
                            <i class='bx bx-edit-alt me-2'></i>New Leave Request
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="leaveForm">
                            <div class="mb-3">
                                <label for="leaveType" class="form-label">Leave Type</label>
                                <select class="form-select" id="leaveType" required>
                                    <option value="">Select Leave Type</option>
                                    <option value="Vacation">Vacation Leave</option>
                                    <option value="Sick">Sick Leave</option>
                                    <option value="Emergency">Emergency Leave</option>
                                    <option value="Personal">Personal Leave</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" required>
                            </div>

                            <div class="mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" required>
                            </div>

                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason</label>
                                <textarea class="form-control" id="reason" rows="3" placeholder="Briefly explain the reason for your leave..." required></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class='bx bx-send me-1'></i>Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Leave History -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="mb-0 fw-semibold text-success">
                            <i class='bx bx-history me-2'></i>Leave History
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Date Range</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody id="leaveHistory">
                                    <tr>
                                        <td>Vacation</td>
                                        <td>Oct 21 - Oct 23, 2025</td>
                                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                                        <td>Awaiting approval</td>
                                    </tr>
                                    <tr>
                                        <td>Sick</td>
                                        <td>Oct 10, 2025</td>
                                        <td><span class="badge bg-success">Approved</span></td>
                                        <td>Take care!</td>
                                    </tr>
                                    <tr>
                                        <td>Personal</td>
                                        <td>Sept 15, 2025</td>
                                        <td><span class="badge bg-danger">Rejected</span></td>
                                        <td>Exceeded limit</td>
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
        document.getElementById('leaveForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const type = document.getElementById('leaveType').value;
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;
            const reason = document.getElementById('reason').value;

            if (!type || !start || !end || !reason) {
                alert('⚠️ Please fill out all fields.');
                return;
            }

            const newRow = `
                <tr>
                    <td>${type}</td>
                    <td>${new Date(start).toLocaleDateString()} - ${new Date(end).toLocaleDateString()}</td>
                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                    <td>${reason}</td>
                </tr>
            `;

            document.getElementById('leaveHistory').insertAdjacentHTML('afterbegin', newRow);
            this.reset();

            alert('✅ Leave request submitted successfully!');
        });
    </script>
</body>
</html>
