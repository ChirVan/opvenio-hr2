<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Payslip - {{ config('app.name') }}</title>

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
    .salary-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      color: #333;
    }
    .salary-item strong {
      color: #157347;
    }
    .divider {
      border-top: 1px dashed #ccc;
      margin: 15px 0;
    }
    .btn-check-bank {
      background-color: #157347;
      color: white;
      border-radius: 8px;
    }
    .btn-check-bank:hover {
      background-color: #116d3b;
    }
  </style>
</head>
<body>
  @include('layouts.ess-navbar-bootstrap')

  <div class="container-fluid py-4" style="margin-top: 80px;">
    <!-- Header -->
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
      <div>
        <h2 class="mb-1">Payslip Overview</h2>
        <p class="mb-0 opacity-75">View your salary breakdown and payment details</p>
      </div>
      <a href="{{ route('ess.dashboard') }}" class="btn btn-light text-success fw-semibold">
        <i class='bx bx-arrow-back me-2'></i>Back to Dashboard
      </a>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-7">
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold text-success mb-0"><i class='bx bx-receipt me-2'></i>Payslip Summary</h5>
            <button class="btn btn-check-bank" data-bs-toggle="modal" data-bs-target="#bankDetailsModal">
              <i class='bx bx-bank me-1'></i>Check Bank Details
            </button>
          </div>

          <!-- Salary Overview -->
          <div class="salary-item"><span>Basic Salary</span><strong>₱40,000.00</strong></div>
          <div class="salary-item"><span>Allowances</span><strong>₱5,000.00</strong></div>
          <div class="salary-item"><span>Overtime Pay</span><strong>₱2,000.00</strong></div>
          <div class="divider"></div>
          <div class="salary-item"><span>SSS Deduction</span><strong>-₱500.00</strong></div>
          <div class="salary-item"><span>PhilHealth</span><strong>-₱300.00</strong></div>
          <div class="salary-item"><span>Pag-IBIG</span><strong>-₱200.00</strong></div>
          <div class="divider"></div>
          <div class="salary-item fw-bold fs-5">
            <span>Net Pay</span><strong>₱46,000.00</strong>
          </div>

          <!-- Footer -->
          <div class="text-end mt-3">
            <small class="text-muted">Payslip generated: Oct 10, 2025</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bank Details Modal -->
  <div class="modal fade" id="bankDetailsModal" tabindex="-1" aria-labelledby="bankDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="bankDetailsModalLabel"><i class='bx bx-bank me-2'></i>Bank Account Details</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center py-5">
          <i class='bx bx-error-circle text-warning' style="font-size: 4rem;"></i>
          <h5 class="mt-3">No Bank Account Details Found</h5>
          <p class="text-muted mb-4">You have not yet submitted your bank details. Please update your account to receive salary deposits directly.</p>
          <button class="btn btn-success px-4">
            <i class='bx bx-edit me-1'></i>Submit Bank Details
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
