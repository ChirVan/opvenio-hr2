
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
    .salary-item.deduction strong {
      color: #dc3545;
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
      color: white;
    }
    .status-badge {
      font-size: 0.75rem;
      padding: 4px 10px;
      border-radius: 20px;
    }
    .status-pending {
      background-color: #fff3cd;
      color: #856404;
    }
    .status-approved {
      background-color: #d4edda;
      color: #155724;
    }
    .status-rejected {
      background-color: #f8d7da;
      color: #721c24;
    }
    .status-none {
      background-color: #e2e3e5;
      color: #383d41;
    }
    .bank-info-item {
      display: flex;
      justify-content: space-between;
      padding: 8px 0;
      border-bottom: 1px solid #eee;
    }
    .bank-info-item:last-child {
      border-bottom: none;
    }
    .bank-info-label {
      color: #6c757d;
      font-size: 0.9rem;
    }
    .bank-info-value {
      font-weight: 600;
      color: #333;
    }
    .payslip-locked {
      opacity: 0.5;
      pointer-events: none;
      position: relative;
    }
    .lock-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255,255,255,0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 14px;
      z-index: 10;
    }
    .period-selector {
      background: #f8f9fa;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 20px;
    }
    /* Payroll Registration Form Styles */
    .payment-method-option .payment-label {
      padding: 8px 16px;
      border: 2px solid #dee2e6;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.2s ease;
      display: inline-block;
    }
    .payment-method-option .form-check-input:checked + .payment-label {
      border-color: #198754;
      background-color: #d1e7dd;
      color: #0f5132;
    }
    .payment-method-option .form-check-input {
      display: none;
    }
    .upload-area {
      transition: all 0.2s ease;
      cursor: pointer;
    }
    .upload-area:hover {
      border-color: #198754 !important;
      background-color: #f8fff9 !important;
    }
    .upload-area.dragover {
      border-color: #198754 !important;
      background-color: #d1e7dd !important;
    }
    .detail-card {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 12px 15px;
    }
    .detail-label {
      font-size: 0.75rem;
      color: #6c757d;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 4px;
    }
    .detail-value {
      font-size: 1rem;
      font-weight: 600;
      color: #212529;
    }
    .status-icon-wrapper {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }
    .status-icon-wrapper.pending {
      background-color: #fff3cd;
      color: #856404;
    }
    .status-icon-wrapper.approved {
      background-color: #d1e7dd;
      color: #0f5132;
    }
    .status-icon-wrapper.rejected {
      background-color: #f8d7da;
      color: #842029;
    }
    #bankDetailsModal .modal-dialog {
      max-width: 700px;
    }
    #bankDetailsModal .modal-body {
      max-height: 75vh;
      overflow-y: auto;
    }
    .form-label {
      margin-bottom: 0.4rem;
    }
    .form-control, .form-select {
      padding: 0.6rem 0.875rem;
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

    <!-- Loading State -->
    <div id="loadingState" class="text-center py-5">
      <div class="spinner-border text-success" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-3 text-muted">Loading payslip information...</p>
    </div>

    <!-- Main Content -->
    <div id="mainContent" class="d-none">
      <div class="row justify-content-center">
        <!-- Bank Details Status Card -->
        <div class="col-lg-7 mb-4">
          <div class="card p-4" id="bankStatusCard">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="fw-semibold mb-1"><i class='bx bx-bank me-2 text-success'></i>Bank Account Status</h6>
                <span id="bankStatusBadge" class="status-badge status-none">Not Submitted</span>
              </div>
              <button class="btn btn-check-bank" data-bs-toggle="modal" data-bs-target="#bankDetailsModal">
                <i class='bx bx-show me-1'></i>View Details
              </button>
            </div>
            <div id="bankStatusMessage" class="mt-3 small text-muted"></div>
          </div>
        </div>

        <!-- Paid Payslip Information Card (Only shown when status is 'paid') -->
        <div class="col-lg-7 mb-4 d-none" id="paidPayslipCard">
          <div class="card p-4 border-success">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h6 class="fw-semibold mb-1"><i class='bx bx-check-circle me-2 text-success'></i>Payment Confirmed</h6>
                <span class="status-badge status-approved">Paid</span>
              </div>
              <span class="badge bg-success" id="paidPayPeriodBadge">--</span>
            </div>
            
            <div class="bg-success bg-opacity-10 rounded p-3 mb-3">
              <div class="row text-center">
                <div class="col-4">
                  <small class="text-muted d-block">Net Pay</small>
                  <h5 class="text-success fw-bold mb-0" id="paidNetPay">₱0.00</h5>
                </div>
                <div class="col-4">
                  <small class="text-muted d-block">Pay Date</small>
                  <p class="fw-semibold mb-0" id="paidPayDate">--</p>
                </div>
                <div class="col-4">
                  <small class="text-muted d-block">Reference No.</small>
                  <p class="fw-semibold mb-0" id="paidReferenceNo">--</p>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-6">
                <div class="detail-card mb-2">
                  <div class="detail-label">Gross Pay</div>
                  <div class="detail-value text-success" id="paidGrossPay">₱0.00</div>
                </div>
              </div>
              <div class="col-6">
                <div class="detail-card mb-2">
                  <div class="detail-label">Total Deductions</div>
                  <div class="detail-value text-danger" id="paidTotalDeductions">-₱0.00</div>
                </div>
              </div>
            </div>

            <div class="mt-3 pt-3 border-top">
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                  <i class='bx bx-bank me-1'></i>Deposited to: <span id="paidBankInfo" class="fw-semibold">--</span>
                </small>
                <button class="btn btn-sm btn-outline-success" onclick="downloadPaidPayslip()">
                  <i class='bx bx-download me-1'></i>Download Receipt
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Payslip Card -->
        <div class="col-lg-7">
          <div class="card p-4 position-relative" id="payslipCard">
            <!-- Lock Overlay (shown when bank details not approved) -->
            <div class="lock-overlay d-none" id="payslipLockOverlay">
              <div class="text-center">
                <i class='bx bx-lock-alt text-warning' style="font-size: 3rem;"></i>
                <h5 class="mt-2">Payslip Locked</h5>
                <p class="text-muted mb-3">Please submit and get your bank details approved to view payslips.</p>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bankDetailsModal">
                  <i class='bx bx-bank me-1'></i>Submit Bank Details
                </button>
              </div>
            </div>

            <!-- Period Selector -->
            <div class="period-selector" id="periodSelector">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-muted">Select Pay Period</label>
                  <select class="form-select" id="payPeriodSelect">
                    <option value="">Select period...</option>
                    <!-- Will be populated dynamically -->
                  </select>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                  <button class="btn btn-outline-success" id="downloadPayslipBtn" disabled>
                    <i class='bx bx-download me-1'></i>Download PDF
                  </button>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="fw-semibold text-success mb-0"><i class='bx bx-receipt me-2'></i>Payslip Summary</h5>
              <span id="payPeriodLabel" class="badge bg-success">--</span>
            </div>

            <!-- Employee Info -->
            <div class="bg-light rounded p-3 mb-3">
              <div class="row">
                <div class="col-6">
                  <small class="text-muted">Employee ID</small>
                  <p class="mb-0 fw-semibold" id="empIdDisplay">--</p>
                </div>
                <div class="col-6">
                  <small class="text-muted">Employee Name</small>
                  <p class="mb-0 fw-semibold" id="empNameDisplay">--</p>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-6">
                  <small class="text-muted">Department</small>
                  <p class="mb-0 fw-semibold" id="empDeptDisplay">--</p>
                </div>
                <div class="col-6">
                  <small class="text-muted">Position</small>
                  <p class="mb-0 fw-semibold" id="empPositionDisplay">--</p>
                </div>
              </div>
            </div>

            <!-- Earnings Section -->
            <h6 class="text-success fw-semibold mt-3 mb-2"><i class='bx bx-plus-circle me-1'></i>Earnings</h6>
            <div class="salary-item"><span>Basic Salary</span><strong id="basicSalary">₱0.00</strong></div>
            <div class="salary-item"><span>Transport Allowance</span><strong id="transportAllowance">₱0.00</strong></div>
            <div class="salary-item"><span>Meal Allowance</span><strong id="mealAllowance">₱0.00</strong></div>
            <div class="salary-item"><span>Overtime Pay</span><strong id="overtimePay">₱0.00</strong></div>
            <div class="salary-item"><span>Other Earnings</span><strong id="otherEarnings">₱0.00</strong></div>
            <div class="salary-item fw-semibold bg-light rounded px-2 py-1">
              <span>Gross Pay</span><strong id="grossPay" class="text-success">₱0.00</strong>
            </div>

            <div class="divider"></div>

            <!-- Deductions Section -->
            <h6 class="text-danger fw-semibold mb-2"><i class='bx bx-minus-circle me-1'></i>Deductions</h6>
            <div class="salary-item deduction"><span>SSS Contribution</span><strong id="sssDeduction">-₱0.00</strong></div>
            <div class="salary-item deduction"><span>PhilHealth</span><strong id="philhealthDeduction">-₱0.00</strong></div>
            <div class="salary-item deduction"><span>Pag-IBIG</span><strong id="pagibigDeduction">-₱0.00</strong></div>
            <div class="salary-item deduction"><span>Withholding Tax</span><strong id="taxDeduction">-₱0.00</strong></div>
            <div class="salary-item deduction"><span>Other Deductions</span><strong id="otherDeductions">-₱0.00</strong></div>
            <div class="salary-item fw-semibold bg-light rounded px-2 py-1">
              <span>Total Deductions</span><strong id="totalDeductions" class="text-danger">-₱0.00</strong>
            </div>

            <div class="divider"></div>

            <!-- Net Pay -->
            <div class="salary-item fw-bold fs-5 bg-success bg-opacity-10 rounded px-3 py-2">
              <span>Net Pay</span><strong id="netPay" class="text-success">₱0.00</strong>
            </div>

            <!-- Footer -->
            <div class="d-flex justify-content-between align-items-center mt-3">
              <small class="text-muted">Payslip generated: <span id="payslipGeneratedDate">--</span></small>
              <small class="text-muted">Pay Date: <span id="payDate">--</span></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bank Details Modal -->
  <div class="modal fade" id="bankDetailsModal" tabindex="-1" aria-labelledby="bankDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="bankDetailsModalLabel"><i class='bx bx-bank me-2'></i>Payroll Account Registration</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-0">
          <!-- No Bank Details View -->
          <div id="noBankDetailsView" class="text-center py-5 px-4">
            <div class="mb-4">
              <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <i class='bx bx-wallet text-warning' style="font-size: 2.5rem;"></i>
              </div>
            </div>
            <h5 class="fw-bold">Payroll Account Not Registered</h5>
            <p class="text-muted mb-4 px-3">To receive your salary through direct deposit, please register your bank account or e-wallet details. This is a one-time setup.</p>
            <button class="btn btn-success btn-lg px-4" id="showBankFormBtn">
              <i class='bx bx-plus-circle me-2'></i>Register Payroll Account
            </button>
          </div>

          <!-- Bank Details Form -->
          <div id="bankDetailsFormView" class="d-none">
            <form id="bankDetailsForm">
              <!-- Form Header -->
              <div class="bg-light border-bottom px-4 py-3">
                <div class="d-flex align-items-center">
                  <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                    <i class='bx bx-file text-success fs-4'></i>
                  </div>
                  <div>
                    <h6 class="mb-0 fw-bold">Payroll Account Registration Form</h6>
                    <small class="text-muted">Please fill out all required fields accurately</small>
                  </div>
                </div>
              </div>

              <div class="p-4">
                <!-- Section 1: Payment Method -->
                <div class="mb-4">
                  <h6 class="fw-semibold text-success mb-3 d-flex align-items-center">
                    <span class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 12px;">1</span>
                    Payment Method
                  </h6>
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label class="form-label fw-medium">Preferred Payment Method <span class="text-danger">*</span></label>
                      <div class="d-flex flex-wrap gap-2">
                        <div class="form-check form-check-inline payment-method-option">
                          <input class="form-check-input" type="radio" name="payment_method" id="methodBank" value="Bank Transfer" checked>
                          <label class="form-check-label payment-label" for="methodBank">
                            <i class='bx bx-building me-1'></i>Bank Transfer
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Divider -->
                <hr class="my-4 border-2">

                <!-- Section 2: Bank/E-Wallet Information -->
                <div class="mb-4" id="bankInfoSection">
                  <h6 class="fw-semibold text-success mb-3 d-flex align-items-center">
                    <span class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 12px;">2</span>
                    <span id="sectionTitle">Bank Information</span>
                  </h6>
                  
                  <!-- Bank Selection (shown for Bank Transfer) -->
                  <div id="bankSelectionFields">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Bank Name <span class="text-danger">*</span></label>
                        <select class="form-select" id="bankName" name="bank_name">
                          <option value="">-- Select Your Bank --</option>
                          <option value="Land Bank of the Philippines">Land Bank of the Philippines</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Bank Branch <span class="text-muted fw-normal">(Optional)</span></label>
                        <input type="text" class="form-control" id="bankBranch" name="bank_branch" placeholder="e.g., SM Makati Branch">
                        <small class="text-muted">Enter the branch where your account was opened</small>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="form-label fw-medium"><span id="accountNameLabel">Account Holder Name</span> <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="accountName" name="account_name" placeholder="Enter full name as registered" required>
                      <small class="text-muted">Must match the name on your bank account/e-wallet</small>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="form-label fw-medium"><span id="accountNumberLabel">Account Number</span> <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="accountNumber" name="account_number" placeholder="Enter account number" required>
                      <small class="text-muted" id="accountNumberHint">Enter your bank account number</small>
                    </div>
                  </div>

                  <!-- Account Type (shown for Bank Transfer) -->
                  <div class="row" id="accountTypeRow">
                    <div class="col-md-6 mb-3">
                      <label class="form-label fw-medium">Account Type <span class="text-danger">*</span></label>
                      <select class="form-select" id="accountType" name="account_type">
                        <option value="Payroll" default>Payroll Account</option>
                      </select>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="form-label fw-medium">Currency</label>
                      <select class="form-select" id="currency" name="currency">
                        <option value="PHP" selected>PHP - Philippine Peso</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Divider -->
                <hr class="my-4 border-2">

                <!-- Section 3: Verification -->
                <div class="mb-4">
                  <h6 class="fw-semibold text-success mb-3 d-flex align-items-center">
                    <span class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 12px;">3</span>
                    Document Verification
                  </h6>

                  <!-- Valid ID Upload -->
                  <div class="border rounded-3 p-3 bg-light mb-3">
                    <label class="form-label fw-medium mb-2">
                      <i class='bx bx-id-card me-1'></i>Upload Valid ID <span class="text-danger">*</span>
                    </label>
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <label class="form-label small text-muted">Select ID Type</label>
                        <select class="form-select" id="idType" name="id_type" required>
                          <option value="">-- Select ID Type --</option>
                          <optgroup label="Primary IDs">
                            <option value="Philippine Passport">Philippine Passport</option>
                            <option value="Driver's License">Driver's License (LTO)</option>
                            <option value="PhilSys ID (National ID)">PhilSys ID (National ID)</option>
                            <option value="SSS ID">SSS ID</option>
                            <option value="GSIS ID">GSIS ID</option>
                            <option value="PRC ID">PRC ID (Professional License)</option>
                            <option value="UMID">UMID (Unified Multi-Purpose ID)</option>
                          </optgroup>
                          <optgroup label="Secondary IDs">
                            <option value="Postal ID">Postal ID</option>
                            <option value="Voter's ID">Voter's ID / COMELEC Registration</option>
                            <option value="PhilHealth ID">PhilHealth ID</option>
                            <option value="TIN ID">TIN ID</option>
                            <option value="Pag-IBIG ID">Pag-IBIG ID</option>
                            <option value="Senior Citizen ID">Senior Citizen ID</option>
                            <option value="OFW ID">OFW ID</option>
                            <option value="Company ID">Company ID</option>
                          </optgroup>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label small text-muted">ID Number</label>
                        <input type="text" class="form-control" id="idNumber" name="id_number" placeholder="Enter ID number">
                      </div>
                    </div>
                    <div class="upload-area border border-2 border-dashed rounded-3 p-4 text-center bg-white" id="uploadAreaId">
                      <input type="file" class="d-none" id="validIdDocument" name="valid_id_document" accept=".jpg,.jpeg,.png,.pdf">
                      <div id="uploadPlaceholderId">
                        <i class='bx bx-id-card text-muted' style="font-size: 2.5rem;"></i>
                        <p class="mb-1 mt-2">Drag & drop your ID here or <span class="text-success fw-medium" style="cursor: pointer;" onclick="document.getElementById('validIdDocument').click()">browse</span></p>
                        <small class="text-muted">Upload front side of your ID (JPG, PNG, PDF - Max 5MB)</small>
                      </div>
                      <div id="uploadedFileId" class="d-none">
                        <i class='bx bx-check-circle text-success' style="font-size: 2rem;"></i>
                        <p class="mb-1 mt-2 fw-medium" id="uploadedFileNameId">filename.jpg</p>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeUploadedFileId()">
                          <i class='bx bx-trash me-1'></i>Remove
                        </button>
                      </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                      <i class='bx bx-info-circle me-1'></i>
                      Upload a clear, readable photo of a valid government-issued ID. Make sure your name and photo are visible.
                    </small>
                  </div>

                  <!-- Proof of Bank Account Upload -->
                  <div class="border rounded-3 p-3 bg-light">
                    <label class="form-label fw-medium mb-2">
                      <i class='bx bx-upload me-1'></i>Upload Proof of Bank Account <span class="text-muted fw-normal">(Recommended)</span>
                    </label>
                    <div class="upload-area border border-2 border-dashed rounded-3 p-4 text-center bg-white" id="uploadArea">
                      <input type="file" class="d-none" id="proofDocument" name="proof_document" accept=".jpg,.jpeg,.png,.pdf">
                      <div id="uploadPlaceholder">
                        <i class='bx bx-cloud-upload text-muted' style="font-size: 2.5rem;"></i>
                        <p class="mb-1 mt-2">Drag & drop your file here or <span class="text-success fw-medium" style="cursor: pointer;" onclick="document.getElementById('proofDocument').click()">browse</span></p>
                        <small class="text-muted">Accepted formats: JPG, PNG, PDF (Max 5MB)</small>
                      </div>
                      <div id="uploadedFile" class="d-none">
                        <i class='bx bx-file text-success' style="font-size: 2rem;"></i>
                        <p class="mb-1 mt-2 fw-medium" id="uploadedFileName">filename.jpg</p>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeUploadedFile()">
                          <i class='bx bx-trash me-1'></i>Remove
                        </button>
                      </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                      <i class='bx bx-info-circle me-1'></i>
                      Upload a clear photo/screenshot of your bank passbook, ATM card, bank statement, or e-wallet account page showing your name and account number.
                    </small>
                  </div>
                </div>

                <!-- Divider -->
                <hr class="my-4 border-2">

                <!-- Section 4: Declaration -->
                <div class="mb-4">
                  <h6 class="fw-semibold text-success mb-3 d-flex align-items-center">
                    <span class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 12px;">4</span>
                    Declaration
                  </h6>
                  
                  <div class="bg-warning bg-opacity-10 border border-warning rounded-3 p-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="declarationCheck" required>
                      <label class="form-check-label" for="declarationCheck">
                        <strong>I hereby certify that:</strong>
                        <ul class="mb-0 mt-2 ps-3">
                          <li>The bank/e-wallet account information provided above is accurate and belongs to me.</li>
                          <li>I authorize the company to deposit my salary and other compensation to this account.</li>
                          <li>I will notify the Payroll Department immediately of any changes to my account details.</li>
                        </ul>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Divider -->
                <hr class="my-4 border-2">

                <!-- Important Notice -->
                <div class="alert alert-info d-flex align-items-start mb-4">
                  <i class='bx bx-info-circle fs-4 me-2 mt-1'></i>
                  <div>
                    <strong>Processing Information:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                      <li>Your submission will be reviewed by the Payroll Department within 1-2 business days.</li>
                      <li>You will receive a notification once your account is verified and approved.</li>
                      <li>Salary will be deposited to your registered account starting from the next pay period after approval.</li>
                    </ul>
                  </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                  <button type="button" class="btn btn-outline-secondary px-4" id="cancelBankFormBtn">
                    <i class='bx bx-x me-1'></i>Cancel
                  </button>
                  <div>
                    <button type="button" class="btn btn-outline-success me-2" onclick="saveDraft()">
                      <i class='bx bx-save me-1'></i>Save as Draft
                    </button>
                    <button type="submit" class="btn btn-success px-4" id="submitBtn">
                      <i class='bx bx-send me-1'></i>Submit for Approval
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>

          <!-- Existing Bank Details View -->
          <div id="existingBankDetailsView" class="d-none">
            <!-- Status Header -->
            <div class="px-4 py-3 border-bottom" id="statusHeader">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <div class="status-icon-wrapper me-3" id="statusIconWrapper">
                    <i class='bx bx-time-five' id="statusIcon"></i>
                  </div>
                  <div>
                    <span id="bankDetailStatusBadge" class="status-badge">--</span>
                    <p class="mb-0 small text-muted mt-1" id="statusMessage">Status message here</p>
                  </div>
                </div>
                <button class="btn btn-sm btn-outline-success" id="editBankDetailsBtn">
                  <i class='bx bx-edit me-1'></i>Edit Details
                </button>
              </div>
            </div>

            <!-- Account Details -->
            <div class="p-4">
              <h6 class="fw-semibold mb-3 text-muted">
                <i class='bx bx-credit-card me-1'></i>Registered Account Information
              </h6>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="detail-card mb-3">
                    <div class="detail-label">Payment Method</div>
                    <div class="detail-value" id="viewPaymentMethod">--</div>
                  </div>
                  <div class="detail-card mb-3">
                    <div class="detail-label">Bank/E-Wallet Name</div>
                    <div class="detail-value" id="viewBankName">--</div>
                  </div>
                  <div class="detail-card mb-3">
                    <div class="detail-label">Branch</div>
                    <div class="detail-value" id="viewBankBranch">--</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="detail-card mb-3">
                    <div class="detail-label">Account Holder Name</div>
                    <div class="detail-value" id="viewAccountName">--</div>
                  </div>
                  <div class="detail-card mb-3">
                    <div class="detail-label">Account Number</div>
                    <div class="detail-value font-monospace" id="viewAccountNumber">--</div>
                  </div>
                  <div class="detail-card mb-3">
                    <div class="detail-label">Account Type</div>
                    <div class="detail-value" id="viewAccountType">--</div>
                  </div>
                </div>
              </div>

              <!-- Valid ID Information -->
              <hr class="my-4" style="border-color: #dee2e6;">
              <h6 class="fw-semibold mb-3 text-muted">
                <i class='bx bx-id-card me-1'></i>Valid ID Information
              </h6>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="detail-card mb-3">
                    <div class="detail-label">ID Type</div>
                    <div class="detail-value" id="viewIdType">--</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="detail-card mb-3">
                    <div class="detail-label">ID Number</div>
                    <div class="detail-value font-monospace" id="viewIdNumber">--</div>
                  </div>
                </div>
              </div>

              <!-- Submission Info -->
              <div class="bg-light rounded-3 p-3 mt-3">
                <div class="row">
                  <div class="col-md-4">
                    <small class="text-muted d-block">Submitted Date</small>
                    <span class="fw-medium" id="viewSubmittedDate">--</span>
                  </div>
                  <div class="col-md-4" id="approvalInfoSection">
                    <small class="text-muted d-block">Approved Date</small>
                    <span class="fw-medium" id="viewApprovedDate">--</span>
                  </div>
                  <div class="col-md-4" id="approvedBySection">
                    <small class="text-muted d-block">Approved By</small>
                    <span class="fw-medium" id="viewApprovedBy">--</span>
                  </div>
                </div>
              </div>

              <!-- Rejection Info -->
              <div id="rejectionInfoSection" class="d-none mt-3">
                <div class="alert alert-danger mb-0">
                  <div class="d-flex">
                    <i class='bx bx-error-circle fs-4 me-2'></i>
                    <div>
                      <strong>Reason for Rejection:</strong>
                      <p class="mb-0 mt-1" id="viewRejectionReason">--</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Configuration - Use local API proxy to avoid CORS issues
    const API_BASE_URL = '/api/ess';
    const USER_EMAIL = '{{ Auth::user()->email ?? "" }}';
    const USER_NAME = '{{ Auth::user()->name ?? "" }}';

    // State
    let employeeData = null;
    let bankDetails = null;
    let salaryDetails = null;
    let payslipData = null;
    let hasPaidPayslip = false;

    // DOM Elements
    const loadingState = document.getElementById('loadingState');
    const mainContent = document.getElementById('mainContent');
    const bankStatusBadge = document.getElementById('bankStatusBadge');
    const bankStatusMessage = document.getElementById('bankStatusMessage');
    const payslipLockOverlay = document.getElementById('payslipLockOverlay');
    
    // Modal Views
    const noBankDetailsView = document.getElementById('noBankDetailsView');
    const bankDetailsFormView = document.getElementById('bankDetailsFormView');
    const existingBankDetailsView = document.getElementById('existingBankDetailsView');

    // Initialize
    document.addEventListener('DOMContentLoaded', async function() {
      await loadEmployeeAndPayslipData();
    });

    // Load employee data and payslip information
    async function loadEmployeeAndPayslipData() {
      try {
        // Fetch employee by email using local API proxy
        const response = await fetch(`${API_BASE_URL}/employee/by-email/${encodeURIComponent(USER_EMAIL)}`);
        const result = await response.json();
        
        if (!result.success || !result.employee) {
          console.error('Employee not found for email:', USER_EMAIL);
          loadingState.innerHTML = `
            <div class="text-center py-5">
              <i class='bx bx-error-circle text-warning' style="font-size: 4rem;"></i>
              <h5 class="mt-3">Employee Record Not Found</h5>
              <p class="text-muted">Your employee record could not be found in the system.<br><small class="text-secondary">Email: ${USER_EMAIL}</small></p>
              <a href="{{ route('ess.dashboard') }}" class="btn btn-success mt-2">
                <i class='bx bx-arrow-back me-1'></i>Back to Dashboard
              </a>
            </div>
          `;
          return;
        }

        // Set employee data
        employeeData = result.employee;
        salaryDetails = employeeData.salary_details || null;
        
        // Load bank details from API
        await loadBankDetailsFromAPI();
        await loadPaidPayslipFromAPI();

        // Update UI
        updateEmployeeInfo(employeeData);
        updateBankStatus();
        updatePayslipDisplay();
        generatePayPeriods();

        loadingState.classList.add('d-none');
        mainContent.classList.remove('d-none');
      } catch (error) {
        console.error('Error loading payslip data:', error);
        loadingState.innerHTML = `
          <div class="text-center py-5">
            <i class='bx bx-error-circle text-danger' style="font-size: 4rem;"></i>
            <h5 class="mt-3">Error Loading Data</h5>
            <p class="text-muted">Unable to connect to the payroll system. Please try again later.</p>
            <p class="small text-secondary">${error.message || 'Connection error'}</p>
            <button class="btn btn-success mt-2" onclick="location.reload()">
              <i class='bx bx-refresh me-1'></i>Retry
            </button>
          </div>
        `;
      }
    }

    // Load all payslip related data (kept for compatibility)
    async function loadPayslipData() {
      await loadEmployeeAndPayslipData();
    }

    // Load bank details from API
    async function loadBankDetailsFromAPI() {
      try {
        const userEmail = '{{ Auth::user()->email }}';
        const response = await fetch(`/api/ess/payroll-registration/${encodeURIComponent(userEmail)}`);
        const result = await response.json();
        
        if (result.success && result.data) {
          bankDetails = result.data;
        } else {
          bankDetails = null;
        }
      } catch (error) {
        console.error('Error loading bank details:', error);
        bankDetails = null;
      }
    }
    
    // Load payslip data from our local API (which proxies to external HR system)
    // Load payslip data from our local API (which proxies to external HR system)
    async function loadPaidPayslipFromAPI() {
      try {
        const employeeId = employeeData?.employee_id;
        
        if (!employeeId) {
          console.log('No employee ID available for payslip lookup');
          return null;
        }

        console.log('Fetching payslips for employee:', employeeId);
        
        const response = await fetch(`/api/ess/payslips/${encodeURIComponent(employeeId)}`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        });

        if (!response.ok) {
          console.log('Payslip API returned error:', response.status);
          return null;
        }

        const result = await response.json();
        console.log('Payslip API response:', result);

        if (!result.success) {
          console.log('Payslip fetch unsuccessful:', result.message);
          return null;
        }

        // Get paid payrolls from the response
        const paidPayrolls = result.paid_payrolls || [];
        
        if (paidPayrolls.length > 0) {
          // Get the most recent paid payroll (first one, assuming sorted by date)
          const latestPayroll = paidPayrolls[0];
          displayPaidPayslip(latestPayroll, result.employee);
          return latestPayroll;
        }

        console.log('No paid payslips found for employee');
        return null;
        
      } catch (error) {
        console.error('Error loading payslip from API:', error);
        return null;
      }
    }

    // Display paid payslip information
    function displayPaidPayslip(payroll, employee) {
      const paidCard = document.getElementById('paidPayslipCard');
      if (!paidCard || !payroll) return;

      console.log('Displaying paid payroll:', payroll);

      // Show the card
      paidCard.classList.remove('d-none');
      hasPaidPayslip = true;

      // Hide the bank status card since payslip is already paid
      const bankStatusCard = document.getElementById('bankStatusCard');
      if (bankStatusCard) bankStatusCard.closest('.col-lg-7').classList.add('d-none');

      // Parse values from the payroll object
      const netPay = parseFloat(payroll.net_pay) || 0;
      const grossPay = parseFloat(payroll.gross_pay) || 0;
      const baseSalary = parseFloat(payroll.base_salary) || 0;
      const totalDeductions = parseFloat(payroll.total_deductions) || 0;
      
      // Format pay period from start_date and end_date
      const startDate = payroll.start_date ? new Date(payroll.start_date) : null;
      const endDate = payroll.end_date ? new Date(payroll.end_date) : null;
      let payPeriod = '--';
      if (startDate && endDate) {
        const startMonth = startDate.toLocaleString('default', { month: 'short' });
        const endMonth = endDate.toLocaleString('default', { month: 'short' });
        const year = endDate.getFullYear();
        payPeriod = `${startMonth} ${startDate.getDate()} - ${endMonth} ${endDate.getDate()}, ${year}`;
      }
      
      // Pay date (computed_at or end_date)
      const payDate = payroll.computed_at || payroll.end_date;
      
      // Reference number (use payroll ID)
      const referenceNo = payroll.id ? `PAY-${payroll.id}` : '--';

      // Populate the data
      document.getElementById('paidPayPeriodBadge').textContent = payPeriod;
      document.getElementById('paidNetPay').textContent = formatCurrency(netPay);
      document.getElementById('paidPayDate').textContent = payDate ? formatDate(payDate) : '--';
      document.getElementById('paidReferenceNo').textContent = referenceNo;
      document.getElementById('paidGrossPay').textContent = formatCurrency(grossPay > 0 ? grossPay : baseSalary);
      document.getElementById('paidTotalDeductions').textContent = '-' + formatCurrency(totalDeductions);
      
      // Bank info from local bank details (if available)
      if (bankDetails && bankDetails.status === 'approved') {
        const bankName = bankDetails.bank_name || 'Bank';
        const accountNumber = bankDetails.account_number || '';
        const maskedAccount = accountNumber ? maskAccountNumber(accountNumber) : '****';
        document.getElementById('paidBankInfo').textContent = `${bankName} - ${maskedAccount}`;
      } else {
        document.getElementById('paidBankInfo').textContent = 'Direct Deposit';
      }

      // Also unlock the payslip view if paid
      document.getElementById('payslipLockOverlay').classList.add('d-none');

      // Populate the Payslip Summary card with the paid payroll data
      const allowances = parseFloat(payroll.allowances) || 0;
      const overtimeHours = parseFloat(payroll.overtime_hours) || 0;
      const claims = parseFloat(payroll.claims_amount) || 0;
      const sss = parseFloat(payroll.sss) || 0;
      const philhealth = parseFloat(payroll.philhealth) || 0;
      const pagibig = parseFloat(payroll.pagibig) || 0;
      const incomeTax = parseFloat(payroll.income_tax) || 0;
      const otherDed = parseFloat(payroll.other_deductions) || 0;

      // Earnings
      document.getElementById('basicSalary').textContent = formatCurrency(baseSalary);
      document.getElementById('transportAllowance').textContent = formatCurrency(allowances);
      document.getElementById('mealAllowance').textContent = formatCurrency(claims);
      document.getElementById('overtimePay').textContent = formatCurrency(grossPay - baseSalary - allowances - claims > 0 ? grossPay - baseSalary - allowances - claims : 0);
      document.getElementById('otherEarnings').textContent = formatCurrency(0);
      document.getElementById('grossPay').textContent = formatCurrency(grossPay);

      // Deductions
      document.getElementById('sssDeduction').textContent = '-' + formatCurrency(sss);
      document.getElementById('philhealthDeduction').textContent = '-' + formatCurrency(philhealth);
      document.getElementById('pagibigDeduction').textContent = '-' + formatCurrency(pagibig);
      document.getElementById('taxDeduction').textContent = '-' + formatCurrency(incomeTax);
      document.getElementById('otherDeductions').textContent = '-' + formatCurrency(otherDed);
      document.getElementById('totalDeductions').textContent = '-' + formatCurrency(totalDeductions);

      // Net Pay
      document.getElementById('netPay').textContent = formatCurrency(netPay);

      // Period badge & footer
      document.getElementById('payPeriodLabel').textContent = payPeriod;
      document.getElementById('payslipGeneratedDate').textContent = payroll.computed_at ? formatDate(payroll.computed_at) : formatDate(new Date());
      document.getElementById('payDate').textContent = payDate ? formatDate(payDate) : '--';

      // Fill employee info from API response if available
      if (employee) {
        document.getElementById('empDeptDisplay').textContent = employee.department || '--';
        document.getElementById('empPositionDisplay').textContent = employee.position || '--';
      }
    }

    // Download paid payslip receipt
    function downloadPaidPayslip() {
      showToast('Downloading payslip receipt...', 'info');
      // TODO: Implement actual PDF download
    }


    // Update employee info display
    function updateEmployeeInfo(employee) {
      document.getElementById('empIdDisplay').textContent = employee.employee_id;
      document.getElementById('empNameDisplay').textContent = employee.full_name;
      document.getElementById('empDeptDisplay').textContent = employee.department;
      document.getElementById('empPositionDisplay').textContent = employee.job_title;
    }

    // Update bank status badge and message
    function updateBankStatus() {
      // If there's a paid payslip, always unlock regardless of bank details status
      if (hasPaidPayslip) {
        payslipLockOverlay.classList.add('d-none');
        return;
      }

      if (!bankDetails) {
        bankStatusBadge.className = 'status-badge status-none';
        bankStatusBadge.textContent = 'Not Submitted';
        bankStatusMessage.innerHTML = '<i class="bx bx-info-circle me-1"></i>Please submit your bank account details to receive salary payments.';
        payslipLockOverlay.classList.remove('d-none');
      } else {
        switch (bankDetails.status) {
          case 'pending':
            bankStatusBadge.className = 'status-badge status-pending';
            bankStatusBadge.textContent = 'Pending Approval';
            bankStatusMessage.innerHTML = '<i class="bx bx-time me-1"></i>Your bank details are being reviewed by the Payroll Department.';
            payslipLockOverlay.classList.remove('d-none');
            break;
          case 'approved':
            bankStatusBadge.className = 'status-badge status-approved';
            bankStatusBadge.textContent = 'Approved';
            bankStatusMessage.innerHTML = '<i class="bx bx-check-circle me-1 text-success"></i>Your bank details have been verified. You can now view your payslips.';
            payslipLockOverlay.classList.add('d-none');
            break;
          case 'rejected':
            bankStatusBadge.className = 'status-badge status-rejected';
            bankStatusBadge.textContent = 'Rejected';
            bankStatusMessage.innerHTML = '<i class="bx bx-x-circle me-1 text-danger"></i>Your bank details were rejected. Please review and resubmit.';
            payslipLockOverlay.classList.remove('d-none');
            break;
        }
      }
    }

    // Update payslip display with salary details
    function updatePayslipDisplay() {
      if (!salaryDetails) return;

      const baseSalary = parseFloat(salaryDetails.base_salary) || 0;
      let allowances = {};
      
      try {
        allowances = typeof salaryDetails.allowance === 'string' 
          ? JSON.parse(salaryDetails.allowance) 
          : salaryDetails.allowance || {};
      } catch (e) {
        allowances = {};
      }

      const transportAllowance = parseFloat(allowances.transport) || 0;
      const mealAllowance = parseFloat(allowances.meal) || 0;
      const overtimePay = 0; // Would come from attendance/payroll calculation
      const otherEarnings = 0;

      const grossPay = baseSalary + transportAllowance + mealAllowance + overtimePay + otherEarnings;

      // Calculate deductions (simplified - actual would be from payroll system)
      const sssDeduction = calculateSSS(baseSalary);
      const philhealthDeduction = calculatePhilHealth(baseSalary);
      const pagibigDeduction = 100; // Standard Pag-IBIG
      const taxDeduction = calculateWithholdingTax(grossPay - sssDeduction - philhealthDeduction - pagibigDeduction, salaryDetails.tax_status);
      const otherDeductions = 0;

      const totalDeductions = sssDeduction + philhealthDeduction + pagibigDeduction + taxDeduction + otherDeductions;
      const netPay = grossPay - totalDeductions;

      // Update display
      document.getElementById('basicSalary').textContent = formatCurrency(baseSalary);
      document.getElementById('transportAllowance').textContent = formatCurrency(transportAllowance);
      document.getElementById('mealAllowance').textContent = formatCurrency(mealAllowance);
      document.getElementById('overtimePay').textContent = formatCurrency(overtimePay);
      document.getElementById('otherEarnings').textContent = formatCurrency(otherEarnings);
      document.getElementById('grossPay').textContent = formatCurrency(grossPay);

      document.getElementById('sssDeduction').textContent = '-' + formatCurrency(sssDeduction);
      document.getElementById('philhealthDeduction').textContent = '-' + formatCurrency(philhealthDeduction);
      document.getElementById('pagibigDeduction').textContent = '-' + formatCurrency(pagibigDeduction);
      document.getElementById('taxDeduction').textContent = '-' + formatCurrency(taxDeduction);
      document.getElementById('otherDeductions').textContent = '-' + formatCurrency(otherDeductions);
      document.getElementById('totalDeductions').textContent = '-' + formatCurrency(totalDeductions);

      document.getElementById('netPay').textContent = formatCurrency(netPay);
    }

    // Generate pay periods for selection
    function generatePayPeriods() {
      const select = document.getElementById('payPeriodSelect');
      const currentDate = new Date();
      const periods = [];

      // Generate last 12 pay periods (semi-monthly)
      for (let i = 0; i < 12; i++) {
        const date = new Date(currentDate);
        date.setMonth(date.getMonth() - Math.floor(i / 2));
        
        const month = date.toLocaleString('default', { month: 'long' });
        const year = date.getFullYear();
        
        if (i % 2 === 0) {
          periods.push({ value: `${year}-${date.getMonth() + 1}-2`, label: `${month} 16-${new Date(year, date.getMonth() + 1, 0).getDate()}, ${year}` });
        } else {
          periods.push({ value: `${year}-${date.getMonth() + 1}-1`, label: `${month} 1-15, ${year}` });
        }
      }

      periods.forEach(period => {
        const option = document.createElement('option');
        option.value = period.value;
        option.textContent = period.label;
        select.appendChild(option);
      });
    }

    // SSS Contribution Calculator (2024 table simplified)
    function calculateSSS(salary) {
      if (salary <= 4250) return 180;
      if (salary <= 4749.99) return 202.50;
      if (salary <= 5249.99) return 225;
      if (salary <= 5749.99) return 247.50;
      if (salary <= 6249.99) return 270;
      if (salary <= 6749.99) return 292.50;
      // ... simplified for demo
      if (salary >= 29750) return 1350;
      return Math.min(salary * 0.045, 1350);
    }

    // PhilHealth Contribution Calculator
    function calculatePhilHealth(salary) {
      const rate = 0.05; // 5% total, employee pays half
      const contribution = salary * rate / 2;
      return Math.min(Math.max(contribution, 200), 1800);
    }

    // Simplified Withholding Tax Calculator
    function calculateWithholdingTax(taxableIncome, status) {
      // Very simplified - actual implementation would use BIR tax tables
      if (taxableIncome <= 20833) return 0;
      if (taxableIncome <= 33332) return (taxableIncome - 20833) * 0.15;
      if (taxableIncome <= 66666) return 1875 + (taxableIncome - 33333) * 0.20;
      if (taxableIncome <= 166666) return 8541.80 + (taxableIncome - 66667) * 0.25;
      if (taxableIncome <= 666666) return 33541.80 + (taxableIncome - 166667) * 0.30;
      return 183541.80 + (taxableIncome - 666667) * 0.35;
    }

    // Format currency
    function formatCurrency(amount) {
      return '₱' + amount.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Show Bank Form
    document.getElementById('showBankFormBtn').addEventListener('click', function() {
      noBankDetailsView.classList.add('d-none');
      bankDetailsFormView.classList.remove('d-none');
      
      // Pre-fill account name
      if (employeeData) {
        document.getElementById('accountName').value = employeeData.full_name;
      }
      
      // Reset form and checkbox
      document.getElementById('bankDetailsForm').reset();
      document.getElementById('accountName').value = employeeData?.full_name || '';
      document.getElementById('declarationCheck').checked = false;
    });

    // Cancel Bank Form
    document.getElementById('cancelBankFormBtn').addEventListener('click', function() {
      bankDetailsFormView.classList.add('d-none');
      if (bankDetails) {
        existingBankDetailsView.classList.remove('d-none');
      } else {
        noBankDetailsView.classList.remove('d-none');
      }
    });

    // Payment Method Switching
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
      radio.addEventListener('change', function() {
        const method = this.value;
        const bankSelectionFields = document.getElementById('bankSelectionFields');
        const accountTypeRow = document.getElementById('accountTypeRow');
        const sectionTitle = document.getElementById('sectionTitle');
        const accountNumberLabel = document.getElementById('accountNumberLabel');
        const accountNumberHint = document.getElementById('accountNumberHint');
        const accountNameLabel = document.getElementById('accountNameLabel');
        const bankNameSelect = document.getElementById('bankName');

        if (method === 'Bank Transfer') {
          bankSelectionFields.classList.remove('d-none');
          accountTypeRow.classList.remove('d-none');
          sectionTitle.textContent = 'Bank Information';
          accountNumberLabel.textContent = 'Account Number';
          accountNumberHint.textContent = 'Enter your bank account number';
          accountNameLabel.textContent = 'Account Holder Name';
          bankNameSelect.required = true;
        } else if (method === 'GCash') {
          bankSelectionFields.classList.add('d-none');
          accountTypeRow.classList.add('d-none');
          sectionTitle.textContent = 'GCash Information';
          accountNumberLabel.textContent = 'GCash Mobile Number';
          accountNumberHint.textContent = 'Enter your registered GCash mobile number (e.g., 09171234567)';
          accountNameLabel.textContent = 'GCash Registered Name';
          bankNameSelect.required = false;
        } else if (method === 'Maya') {
          bankSelectionFields.classList.add('d-none');
          accountTypeRow.classList.add('d-none');
          sectionTitle.textContent = 'Maya Information';
          accountNumberLabel.textContent = 'Maya Mobile Number';
          accountNumberHint.textContent = 'Enter your registered Maya mobile number (e.g., 09171234567)';
          accountNameLabel.textContent = 'Maya Registered Name';
          bankNameSelect.required = false;
        }
      });
    });

    // File Upload Handling
    const uploadArea = document.getElementById('uploadArea');
    const proofDocument = document.getElementById('proofDocument');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const uploadedFile = document.getElementById('uploadedFile');
    const uploadedFileName = document.getElementById('uploadedFileName');

    uploadArea.addEventListener('click', () => proofDocument.click());
    
    uploadArea.addEventListener('dragover', (e) => {
      e.preventDefault();
      uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', () => {
      uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadArea.classList.remove('dragover');
      const files = e.dataTransfer.files;
      if (files.length > 0) {
        handleFileSelect(files[0]);
      }
    });

    proofDocument.addEventListener('change', function() {
      if (this.files.length > 0) {
        handleFileSelect(this.files[0]);
      }
    });

    function handleFileSelect(file) {
      const maxSize = 5 * 1024 * 1024; // 5MB
      const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
      
      if (!allowedTypes.includes(file.type)) {
        showToast('Invalid file type. Please upload JPG, PNG, or PDF files only.', 'error');
        return;
      }
      
      if (file.size > maxSize) {
        showToast('File is too large. Maximum size is 5MB.', 'error');
        return;
      }
      
      uploadPlaceholder.classList.add('d-none');
      uploadedFile.classList.remove('d-none');
      uploadedFileName.textContent = file.name;
    }

    function removeUploadedFile() {
      proofDocument.value = '';
      uploadPlaceholder.classList.remove('d-none');
      uploadedFile.classList.add('d-none');
    }

    // Valid ID File Upload Handling
    const uploadAreaId = document.getElementById('uploadAreaId');
    const validIdDocument = document.getElementById('validIdDocument');
    const uploadPlaceholderId = document.getElementById('uploadPlaceholderId');
    const uploadedFileId = document.getElementById('uploadedFileId');
    const uploadedFileNameId = document.getElementById('uploadedFileNameId');

    uploadAreaId.addEventListener('click', (e) => {
      e.stopPropagation();
      validIdDocument.click();
    });
    
    uploadAreaId.addEventListener('dragover', (e) => {
      e.preventDefault();
      uploadAreaId.classList.add('dragover');
    });
    
    uploadAreaId.addEventListener('dragleave', () => {
      uploadAreaId.classList.remove('dragover');
    });
    
    uploadAreaId.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadAreaId.classList.remove('dragover');
      const files = e.dataTransfer.files;
      if (files.length > 0) {
        handleFileSelectId(files[0]);
      }
    });

    validIdDocument.addEventListener('change', function() {
      if (this.files.length > 0) {
        handleFileSelectId(this.files[0]);
      }
    });

    function handleFileSelectId(file) {
      const maxSize = 5 * 1024 * 1024; // 5MB
      const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
      
      if (!allowedTypes.includes(file.type)) {
        showToast('Invalid file type. Please upload JPG, PNG, or PDF files only.', 'error');
        return;
      }
      
      if (file.size > maxSize) {
        showToast('File is too large. Maximum size is 5MB.', 'error');
        return;
      }
      
      uploadPlaceholderId.classList.add('d-none');
      uploadedFileId.classList.remove('d-none');
      uploadedFileNameId.textContent = file.name;
    }

    function removeUploadedFileId() {
      validIdDocument.value = '';
      uploadPlaceholderId.classList.remove('d-none');
      uploadedFileId.classList.add('d-none');
    }

    // Save as Draft
    function saveDraft() {
      const formData = getFormData();
      formData.status = 'draft';
      
      const storageKey = `bank_details_draft_${employeeData.id}`;
      localStorage.setItem(storageKey, JSON.stringify(formData));
      
      showToast('Draft saved successfully!', 'success');
    }

    // Get form data
    function getFormData() {
      const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'Bank Transfer';
      
      return {
        employee_id: employeeData.id,
        payment_method: paymentMethod,
        bank_name: paymentMethod === 'Bank Transfer' ? document.getElementById('bankName').value : paymentMethod,
        bank_branch: document.getElementById('bankBranch').value,
        account_name: document.getElementById('accountName').value,
        account_number: document.getElementById('accountNumber').value,
        account_type: paymentMethod === 'Bank Transfer' ? document.getElementById('accountType').value : 'E-Wallet',
        currency: document.getElementById('currency')?.value || 'PHP',
        id_type: document.getElementById('idType')?.value || '',
        id_number: document.getElementById('idNumber')?.value || '',
        has_valid_id: validIdDocument?.files?.length > 0,
        has_proof_document: proofDocument?.files?.length > 0,
        submitted_at: new Date().toISOString(),
        created_at: new Date().toISOString()
      };
    }

    // Handle Bank Details Form Submission
    document.getElementById('bankDetailsForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      // Validate declaration checkbox
      if (!document.getElementById('declarationCheck').checked) {
        showToast('Please read and accept the declaration to proceed.', 'error');
        return;
      }

      const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
      
      // Validate bank selection for Bank Transfer
      if (paymentMethod === 'Bank Transfer' && !document.getElementById('bankName').value) {
        showToast('Please select your bank.', 'error');
        return;
      }

      // Validate Valid ID
      const idType = document.getElementById('idType').value;
      if (!idType) {
        showToast('Please select a valid ID type.', 'error');
        return;
      }

      // Check if Valid ID is uploaded
      if (!validIdDocument.files || validIdDocument.files.length === 0) {
        showToast('Please upload a copy of your valid ID.', 'error');
        return;
      }

      try {
        // Show loading state on submit button
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
        submitBtn.disabled = true;

        // Prepare FormData for API submission with file uploads
        const apiFormData = new FormData();
        apiFormData.append('email', '{{ Auth::user()->email }}');
        apiFormData.append('employee_id', employeeData?.employee_id || '');
        apiFormData.append('employee_name', employeeData?.full_name || '{{ Auth::user()->name }}');
        apiFormData.append('payment_method', paymentMethod);
        apiFormData.append('bank_name', document.getElementById('bankName').value);
        apiFormData.append('bank_branch', document.getElementById('bankBranch').value || '');
        apiFormData.append('account_name', document.getElementById('accountName').value);
        apiFormData.append('account_number', document.getElementById('accountNumber').value);
        apiFormData.append('account_type', document.getElementById('accountType').value || '');
        apiFormData.append('id_type', idType);
        apiFormData.append('id_number', document.getElementById('idNumber').value || '');
        
        // Append file uploads
        if (proofDocument.files && proofDocument.files.length > 0) {
          apiFormData.append('proof_of_account', proofDocument.files[0]);
        }
        if (validIdDocument.files && validIdDocument.files.length > 0) {
          apiFormData.append('valid_id', validIdDocument.files[0]);
        }

        // Submit to API
        const response = await fetch('/api/ess/payroll-registration', {
          method: 'POST',
          body: apiFormData,
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          }
        });

        const result = await response.json();
        
        // Restore button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;

        if (result.success) {
          bankDetails = result.data;
          showToast('Payroll account registration submitted successfully! You will be notified once approved.', 'success');
          
          // Close modal and refresh UI
          bootstrap.Modal.getInstance(document.getElementById('bankDetailsModal')).hide();
          updateBankStatus();
        } else {
          // Show error message from API
          const errorMsg = result.message || 'Failed to submit registration.';
          showToast(errorMsg, 'error');
          
          if (result.errors) {
            console.error('Validation errors:', result.errors);
          }
        }
      } catch (error) {
        console.error('Error submitting bank details:', error);
        showToast('Error submitting registration. Please try again.', 'error');
        
        // Restore button
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="bx bx-send me-1"></i>Submit for Approval';
        submitBtn.disabled = false;
      }
    });

    // Update modal view based on bank details status
    document.getElementById('bankDetailsModal').addEventListener('show.bs.modal', function() {
      noBankDetailsView.classList.add('d-none');
      bankDetailsFormView.classList.add('d-none');
      existingBankDetailsView.classList.add('d-none');

      if (!bankDetails) {
        noBankDetailsView.classList.remove('d-none');
      } else {
        existingBankDetailsView.classList.remove('d-none');
        populateBankDetailsView();
      }
    });

    // Populate existing bank details view
    function populateBankDetailsView() {
      if (!bankDetails) return;

      // Payment method
      const viewPaymentMethod = document.getElementById('viewPaymentMethod');
      if (viewPaymentMethod) {
        viewPaymentMethod.textContent = bankDetails.payment_method || 'Bank Transfer';
      }

      document.getElementById('viewBankName').textContent = bankDetails.bank_name || 'N/A';
      document.getElementById('viewBankBranch').textContent = bankDetails.bank_branch || 'N/A';
      document.getElementById('viewAccountName').textContent = bankDetails.account_name;
      document.getElementById('viewAccountNumber').textContent = maskAccountNumber(bankDetails.account_number);
      document.getElementById('viewAccountType').textContent = bankDetails.account_type || 'N/A';
      
      // Valid ID Information
      document.getElementById('viewIdType').textContent = bankDetails.id_type || 'N/A';
      document.getElementById('viewIdNumber').textContent = bankDetails.id_number ? maskIdNumber(bankDetails.id_number) : 'N/A';
      
      document.getElementById('viewSubmittedDate').textContent = formatDate(bankDetails.submitted_at || bankDetails.created_at);

      const statusBadge = document.getElementById('bankDetailStatusBadge');
      const statusText = bankDetails.status.charAt(0).toUpperCase() + bankDetails.status.slice(1);
      statusBadge.textContent = statusText;
      statusBadge.className = `status-badge status-${bankDetails.status}`;

      // Update status icon and message
      const statusIconWrapper = document.getElementById('statusIconWrapper');
      const statusIcon = document.getElementById('statusIcon');
      const statusMessage = document.getElementById('statusMessage');

      if (statusIconWrapper && statusIcon && statusMessage) {
        statusIconWrapper.className = 'status-icon-wrapper me-3 ' + bankDetails.status;
        
        switch (bankDetails.status) {
          case 'pending':
            statusIcon.className = 'bx bx-time-five';
            statusMessage.textContent = 'Your registration is being reviewed by the Payroll Department.';
            break;
          case 'approved':
            statusIcon.className = 'bx bx-check-circle';
            statusMessage.textContent = 'Your payroll account has been verified and approved.';
            break;
          case 'rejected':
            statusIcon.className = 'bx bx-x-circle';
            statusMessage.textContent = 'Your registration was rejected. Please review and resubmit.';
            break;
          default:
            statusIcon.className = 'bx bx-time-five';
            statusMessage.textContent = 'Status pending';
        }
      }

      // Show/hide approval info
      const approvalSection = document.getElementById('approvalInfoSection');
      const approvedBySection = document.getElementById('approvedBySection');
      const rejectionSection = document.getElementById('rejectionInfoSection');
      
      if (bankDetails.status === 'approved') {
        if (approvalSection) approvalSection.classList.remove('d-none');
        if (approvedBySection) approvedBySection.classList.remove('d-none');
        document.getElementById('viewApprovedDate').textContent = formatDate(bankDetails.approved_at);
        document.getElementById('viewApprovedBy').textContent = bankDetails.approved_by || 'Payroll Admin';
        if (rejectionSection) rejectionSection.classList.add('d-none');
      } else if (bankDetails.status === 'rejected') {
        if (rejectionSection) rejectionSection.classList.remove('d-none');
        document.getElementById('viewRejectionReason').textContent = bankDetails.remarks || 'No reason provided';
        if (approvalSection) approvalSection.classList.add('d-none');
        if (approvedBySection) approvedBySection.classList.add('d-none');
      } else {
        if (approvalSection) approvalSection.classList.add('d-none');
        if (approvedBySection) approvedBySection.classList.add('d-none');
        if (rejectionSection) rejectionSection.classList.add('d-none');
      }

      // Edit button visibility
      const editBtn = document.getElementById('editBankDetailsBtn');
      if (editBtn) {
        editBtn.style.display = bankDetails.status === 'approved' ? 'none' : 'block';
      }
    }

    // Edit bank details
    document.getElementById('editBankDetailsBtn').addEventListener('click', function() {
      existingBankDetailsView.classList.add('d-none');
      bankDetailsFormView.classList.remove('d-none');

      // Pre-fill payment method
      const paymentMethod = bankDetails.payment_method || 'Bank Transfer';
      const radioBtn = document.querySelector(`input[name="payment_method"][value="${paymentMethod}"]`);
      if (radioBtn) {
        radioBtn.checked = true;
        radioBtn.dispatchEvent(new Event('change'));
      }

      // Pre-fill form fields
      document.getElementById('bankName').value = bankDetails.bank_name || '';
      document.getElementById('bankBranch').value = bankDetails.bank_branch || '';
      document.getElementById('accountName').value = bankDetails.account_name;
      document.getElementById('accountNumber').value = bankDetails.account_number;
      document.getElementById('accountType').value = bankDetails.account_type || '';
      
      // Pre-fill Valid ID fields
      document.getElementById('idType').value = bankDetails.id_type || '';
      document.getElementById('idNumber').value = bankDetails.id_number || '';
      
      // Reset declaration checkbox and file uploads (need to re-upload for verification)
      document.getElementById('declarationCheck').checked = false;
    });

    // Mask account number for display
    function maskAccountNumber(number) {
      if (!number || number.length < 4) return number;
      return '*'.repeat(number.length - 4) + number.slice(-4);
    }

    // Mask ID number for display
    function maskIdNumber(number) {
      if (!number || number.length < 4) return number;
      return number.slice(0, 2) + '*'.repeat(number.length - 4) + number.slice(-2);
    }

    // Format date
    function formatDate(dateString) {
      if (!dateString) return 'N/A';
      return new Date(dateString).toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    }

    // Pay period selection
    document.getElementById('payPeriodSelect').addEventListener('change', function() {
      const selectedPeriod = this.options[this.selectedIndex];
      if (this.value) {
        document.getElementById('payPeriodLabel').textContent = selectedPeriod.text;
        document.getElementById('downloadPayslipBtn').disabled = !bankDetails || bankDetails.status !== 'approved';
        
        // Set dates
        const now = new Date();
        document.getElementById('payslipGeneratedDate').textContent = formatDate(now);
        document.getElementById('payDate').textContent = selectedPeriod.text.split(',')[0].split(' ')[1].includes('15') ? 
          selectedPeriod.text.replace('1-15', '15') : 
          formatDate(new Date(now.getFullYear(), now.getMonth() + 1, 0));
      }
    });

    // Toast notification
    function showToast(message, type = 'info') {
      // Create toast container if not exists
      let toastContainer = document.getElementById('toastContainer');
      if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
      }

      const toastId = 'toast-' + Date.now();
      const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
      const icon = type === 'success' ? 'bx-check-circle' : type === 'error' ? 'bx-error-circle' : 'bx-info-circle';

      const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert">
          <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
              <i class='bx ${icon} me-2 fs-5'></i>
              ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
          </div>
        </div>
      `;

      toastContainer.insertAdjacentHTML('beforeend', toastHtml);
      const toastElement = document.getElementById(toastId);
      const toast = new bootstrap.Toast(toastElement, { delay: 4000 });
      toast.show();

      // Remove toast element after it's hidden
      toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
    }
  </script>
</body>
</html>