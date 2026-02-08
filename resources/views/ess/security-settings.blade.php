<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Security Settings - {{ config('app.name') }}</title>

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
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.1);
        }
        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }
        .section-subtitle {
            font-size: 0.8rem;
            color: #6b7280;
        }
        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            padding: 10px 14px;
            font-size: 0.85rem;
        }
        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.15);
        }
        .btn-green {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 10px 24px;
            transition: all 0.2s;
        }
        .btn-green:hover {
            background: linear-gradient(135deg, #157347 0%, #116d3b 100%);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
        }
        .btn-outline-green {
            border: 2px solid #198754;
            color: #198754;
            background: transparent;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 9px 24px;
            transition: all 0.2s;
        }
        .btn-outline-green:hover {
            background: #198754;
            color: #fff;
        }
        .btn-danger-soft {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 10px 24px;
            transition: all 0.2s;
        }
        .btn-danger-soft:hover {
            background: #dc2626;
            color: #fff;
            border-color: #dc2626;
        }
        .tfa-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .tfa-enabled {
            background: #ecfdf5;
            color: #059669;
        }
        .tfa-disabled {
            background: #fef3c7;
            color: #d97706;
        }
        .qr-container {
            background: #f9fafb;
            border: 2px dashed #e5e7eb;
            border-radius: 14px;
            padding: 24px;
            text-align: center;
        }
        .recovery-code {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 14px;
            margin: 4px;
            display: inline-block;
        }
        .alert-ess {
            border-radius: 10px;
            font-size: 0.85rem;
            border: none;
        }
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        .password-toggle:hover {
            color: #374151;
        }
        .back-link {
            color: #6b7280;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #198754;
        }
    </style>
</head>
<body>
    @include('layouts.ess-navbar-bootstrap')

    <div class="container" style="margin-top: 84px; max-width: 720px; padding-bottom: 60px;">
        <!-- Back Link -->
        <a href="{{ route('ess.dashboard') }}" class="back-link d-inline-flex align-items-center gap-1 mb-3">
            <i class="bx bx-arrow-back" style="font-size: 1.1rem;"></i> Back to Dashboard
        </a>

        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
                    <i class="bx bx-shield-quarter" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold" style="font-size: 1.15rem;">Security Settings</h4>
                    <p class="mb-0 opacity-75" style="font-size: 0.8rem;">Manage your password and two-factor authentication</p>
                </div>
            </div>
        </div>

        <!-- Success/Error Alerts -->
        <div id="alertContainer"></div>

        @if(session('status'))
            <div class="alert alert-success alert-ess d-flex align-items-center gap-2" style="background: #ecfdf5; color: #059669;">
                <i class="bx bx-check-circle" style="font-size: 1.2rem;"></i> {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-ess" style="background: #fef2f2; color: #dc2626;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bx bx-error-circle" style="font-size: 1.2rem;"></i> <strong>Please fix the following errors:</strong>
                </div>
                <ul class="mb-0 ps-3" style="font-size: 0.8rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ─── CHANGE PASSWORD CARD ─── -->
        <div class="card mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; background: #ecfdf5;">
                        <i class="bx bx-lock-alt" style="font-size: 1.1rem; color: #059669;"></i>
                    </div>
                    <div>
                        <div class="section-title">Change Password</div>
                        <div class="section-subtitle">Update your password to keep your account secure</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('user-password.update') }}" id="passwordForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control" id="current_password" name="current_password" required autocomplete="current-password">
                            <i class="bx bx-hide password-toggle" onclick="togglePassword('current_password', this)"></i>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password">
                            <i class="bx bx-hide password-toggle" onclick="togglePassword('password', this)"></i>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                            <i class="bx bx-hide password-toggle" onclick="togglePassword('password_confirmation', this)"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-green">
                        <i class="bx bx-check me-1"></i> Update Password
                    </button>
                </form>
            </div>
        </div>

        <!-- ─── TWO-FACTOR AUTHENTICATION CARD ─── -->
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; background: #eff6ff;">
                            <i class="bx bx-devices" style="font-size: 1.1rem; color: #2563eb;"></i>
                        </div>
                        <div>
                            <div class="section-title">Two-Factor Authentication</div>
                            <div class="section-subtitle">Add an extra layer of security to your account</div>
                        </div>
                    </div>
                    @if(auth()->user()->two_factor_secret)
                        @if(auth()->user()->two_factor_confirmed_at)
                            <span class="tfa-status-badge tfa-enabled"><i class="bx bxs-check-circle"></i> Enabled</span>
                        @else
                            <span class="tfa-status-badge" style="background: #eff6ff; color: #2563eb;"><i class="bx bx-loader-alt bx-spin"></i> Pending Confirmation</span>
                        @endif
                    @else
                        <span class="tfa-status-badge tfa-disabled"><i class="bx bxs-shield-x"></i> Disabled</span>
                    @endif
                </div>

                <!-- 2FA Not Enabled State -->
                <div id="tfa-off" style="{{ auth()->user()->two_factor_secret ? 'display:none' : '' }}">
                    <div class="p-3 rounded-3 mb-3" style="background: #fffbeb; border: 1px solid #fde68a;">
                        <div class="d-flex gap-2">
                            <i class="bx bx-info-circle" style="font-size: 1.2rem; color: #d97706; flex-shrink: 0; margin-top: 2px;"></i>
                            <div style="font-size: 0.8rem; color: #92400e;">
                                <strong>Why enable 2FA?</strong><br>
                                Two-factor authentication adds an extra layer of security. You'll need both your password and an authenticator app code to log in.
                            </div>
                        </div>
                    </div>
                    <button id="enableTfaBtn" class="btn btn-green" onclick="enableTwoFactor()">
                        <i class="bx bx-shield-quarter me-1"></i> Enable Two-Factor Authentication
                    </button>
                </div>

                <!-- 2FA Setup (QR Code + Confirm) -->
                <div id="tfa-setup" style="{{ (auth()->user()->two_factor_secret && !auth()->user()->two_factor_confirmed_at) ? '' : 'display:none' }}">
                    <div class="qr-container mb-3">
                        <p class="mb-2" style="font-size: 0.85rem; font-weight: 600; color: #374151;">Scan this QR code with your authenticator app</p>
                        <p class="mb-3" style="font-size: 0.75rem; color: #6b7280;">Use Google Authenticator, Authy, or any TOTP app</p>
                        <div id="qrCode" class="d-flex justify-content-center mb-3">
                            <div class="spinner-border text-success spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div id="secretKey" style="display:none;">
                            <p style="font-size: 0.75rem; color: #6b7280;">Or enter this key manually:</p>
                            <code id="secretKeyText" class="d-block p-2 rounded" style="background: #e5e7eb; font-size: 0.85rem; letter-spacing: 2px;"></code>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary mt-2" onclick="toggleSecretKey()" style="font-size: 0.75rem; border-radius: 8px;">
                            <i class="bx bx-key me-1"></i> Show Manual Key
                        </button>
                    </div>

                    <form id="confirmTfaForm" onsubmit="confirmTwoFactor(event)">
                        <label class="form-label">Enter the 6-digit code from your authenticator app</label>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control" id="tfa_code" maxlength="6" placeholder="000000" 
                                   style="max-width: 200px; text-align: center; font-size: 1.1rem; font-weight: 700; letter-spacing: 4px;" 
                                   autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*">
                            <button type="submit" class="btn btn-green" id="confirmTfaBtn">
                                <i class="bx bx-check me-1"></i> Confirm
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 2FA Enabled State -->
                <div id="tfa-on" style="{{ (auth()->user()->two_factor_secret && auth()->user()->two_factor_confirmed_at) ? '' : 'display:none' }}">
                    <div class="p-3 rounded-3 mb-3" style="background: #ecfdf5; border: 1px solid #a7f3d0;">
                        <div class="d-flex gap-2">
                            <i class="bx bx-check-shield" style="font-size: 1.2rem; color: #059669; flex-shrink: 0; margin-top: 2px;"></i>
                            <div style="font-size: 0.8rem; color: #065f46;">
                                <strong>Two-factor authentication is active.</strong><br>
                                Your account is protected with an authenticator app. You'll need to provide a code from your app each time you log in.
                            </div>
                        </div>
                    </div>

                    <!-- Recovery Codes Section -->
                    <div class="mb-3">
                        <button class="btn btn-outline-green btn-sm" onclick="showRecoveryCodes()" id="showRecoveryBtn">
                            <i class="bx bx-key me-1"></i> View Recovery Codes
                        </button>
                        <button class="btn btn-outline-green btn-sm ms-2" onclick="regenerateRecoveryCodes()">
                            <i class="bx bx-refresh me-1"></i> Regenerate Recovery Codes
                        </button>
                    </div>

                    <div id="recoveryCodes" style="display: none;">
                        <div class="p-3 rounded-3 mb-3" style="background: #fffbeb; border: 1px solid #fde68a;">
                            <div class="d-flex gap-2 mb-2">
                                <i class="bx bx-error" style="font-size: 1.1rem; color: #d97706; flex-shrink: 0;"></i>
                                <span style="font-size: 0.75rem; color: #92400e; font-weight: 600;">Save these codes in a safe place. Each code can only be used once.</span>
                            </div>
                            <div id="recoveryCodesList" class="text-center"></div>
                        </div>
                    </div>

                    <hr class="my-3" style="border-color: #f3f4f6;">

                    <button class="btn btn-danger-soft" onclick="disableTwoFactor()">
                        <i class="bx bx-shield-x me-1"></i> Disable Two-Factor Authentication
                    </button>
                </div>

                <!-- Loading Overlay -->
                <div id="tfaLoading" style="display: none;" class="text-center py-3">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2" style="font-size: 0.8rem; color: #6b7280;">Processing...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // ─── Password Toggle ───
        function togglePassword(fieldId, icon) {
            const field = document.getElementById(fieldId);
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.replace('bx-hide', 'bx-show');
            } else {
                field.type = 'password';
                icon.classList.replace('bx-show', 'bx-hide');
            }
        }

        // ─── Alert Helper ───
        function showAlert(type, message) {
            const container = document.getElementById('alertContainer');
            const colors = {
                success: { bg: '#ecfdf5', color: '#059669', icon: 'bx-check-circle' },
                error:   { bg: '#fef2f2', color: '#dc2626', icon: 'bx-error-circle' },
                info:    { bg: '#eff6ff', color: '#2563eb', icon: 'bx-info-circle' }
            };
            const c = colors[type] || colors.info;
            container.innerHTML = `
                <div class="alert alert-ess d-flex align-items-center gap-2 mb-3" style="background: ${c.bg}; color: ${c.color};">
                    <i class="bx ${c.icon}" style="font-size: 1.2rem;"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close ms-auto" style="font-size: 0.6rem;" onclick="this.parentElement.remove()"></button>
                </div>`;
            // Auto-dismiss after 5 seconds
            setTimeout(() => { container.innerHTML = ''; }, 5000);
        }

        // ─── ENABLE 2FA ───
        async function enableTwoFactor() {
            const btn = document.getElementById('enableTfaBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Enabling...';

            try {
                // First, we need to confirm the password — Fortify requires this
                const response = await fetch('/user/two-factor-authentication', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok || response.status === 200) {
                    // Show setup section
                    document.getElementById('tfa-off').style.display = 'none';
                    document.getElementById('tfa-setup').style.display = '';
                    // Load QR code
                    loadQRCode();
                    showAlert('info', 'Scan the QR code below with your authenticator app to complete setup.');
                } else if (response.status === 423) {
                    // Password confirmation required
                    showPasswordConfirmModal('enable');
                } else {
                    const data = await response.json().catch(() => ({}));
                    showAlert('error', data.message || 'Failed to enable 2FA. Please try again.');
                }
            } catch (err) {
                showAlert('error', 'Network error. Please try again.');
                console.error(err);
            }

            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-shield-quarter me-1"></i> Enable Two-Factor Authentication';
        }

        // ─── LOAD QR CODE ───
        async function loadQRCode() {
            try {
                const response = await fetch('/user/two-factor-qr-code', {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                if (response.ok) {
                    const data = await response.json();
                    document.getElementById('qrCode').innerHTML = data.svg;
                }
            } catch (err) {
                document.getElementById('qrCode').innerHTML = '<p class="text-danger" style="font-size:0.8rem;">Failed to load QR code</p>';
            }

            // Load secret key
            try {
                const response = await fetch('/user/two-factor-secret-key', {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                if (response.ok) {
                    const data = await response.json();
                    document.getElementById('secretKeyText').textContent = data.secretKey;
                }
            } catch (err) {
                console.error('Failed to load secret key', err);
            }
        }

        function toggleSecretKey() {
            const el = document.getElementById('secretKey');
            el.style.display = el.style.display === 'none' ? '' : 'none';
        }

        // ─── CONFIRM 2FA ───
        async function confirmTwoFactor(e) {
            e.preventDefault();
            const code = document.getElementById('tfa_code').value.trim();
            if (!code || code.length !== 6) {
                showAlert('error', 'Please enter a valid 6-digit code.');
                return;
            }

            const btn = document.getElementById('confirmTfaBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Verifying...';

            try {
                const response = await fetch('/user/confirmed-two-factor-authentication', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ code: code })
                });

                if (response.ok || response.status === 200) {
                    document.getElementById('tfa-setup').style.display = 'none';
                    document.getElementById('tfa-on').style.display = '';
                    // Update badge
                    updateBadge('enabled');
                    showAlert('success', 'Two-factor authentication has been enabled successfully!');
                } else {
                    const data = await response.json().catch(() => ({}));
                    showAlert('error', data.message || 'Invalid code. Please try again.');
                }
            } catch (err) {
                showAlert('error', 'Network error. Please try again.');
            }

            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-check me-1"></i> Confirm';
        }

        // ─── DISABLE 2FA ───
        async function disableTwoFactor() {
            if (!confirm('Are you sure you want to disable two-factor authentication? This will make your account less secure.')) {
                return;
            }

            try {
                const response = await fetch('/user/two-factor-authentication', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok || response.status === 200) {
                    document.getElementById('tfa-on').style.display = 'none';
                    document.getElementById('tfa-off').style.display = '';
                    document.getElementById('recoveryCodes').style.display = 'none';
                    updateBadge('disabled');
                    showAlert('success', 'Two-factor authentication has been disabled.');
                } else if (response.status === 423) {
                    showPasswordConfirmModal('disable');
                } else {
                    showAlert('error', 'Failed to disable 2FA. Please try again.');
                }
            } catch (err) {
                showAlert('error', 'Network error. Please try again.');
            }
        }

        // ─── SHOW RECOVERY CODES ───
        async function showRecoveryCodes() {
            const container = document.getElementById('recoveryCodes');
            const list = document.getElementById('recoveryCodesList');

            if (container.style.display !== 'none') {
                container.style.display = 'none';
                return;
            }

            list.innerHTML = '<div class="spinner-border spinner-border-sm text-success" role="status"></div>';
            container.style.display = '';

            try {
                const response = await fetch('/user/two-factor-recovery-codes', {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                if (response.ok) {
                    const codes = await response.json();
                    list.innerHTML = codes.map(code =>
                        `<span class="recovery-code">${code}</span>`
                    ).join('');
                } else if (response.status === 423) {
                    container.style.display = 'none';
                    showPasswordConfirmModal('recovery');
                }
            } catch (err) {
                list.innerHTML = '<p class="text-danger" style="font-size:0.8rem;">Failed to load recovery codes</p>';
            }
        }

        // ─── REGENERATE RECOVERY CODES ───
        async function regenerateRecoveryCodes() {
            if (!confirm('This will invalidate your existing recovery codes. Continue?')) return;

            try {
                const response = await fetch('/user/two-factor-recovery-codes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    showAlert('success', 'Recovery codes regenerated. View your new codes below.');
                    showRecoveryCodes(); // Refresh the codes display
                } else if (response.status === 423) {
                    showPasswordConfirmModal('regenerate');
                }
            } catch (err) {
                showAlert('error', 'Failed to regenerate codes. Please try again.');
            }
        }

        // ─── PASSWORD CONFIRMATION MODAL ───
        function showPasswordConfirmModal(action) {
            // Create modal if not exists
            let modal = document.getElementById('passwordConfirmModal');
            if (!modal) {
                const modalHTML = `
                <div class="modal fade" id="passwordConfirmModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content" style="border-radius: 16px; border: none;">
                            <div class="modal-header border-0 pb-0">
                                <h6 class="modal-title fw-bold">Confirm Password</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body pt-2">
                                <p style="font-size: 0.8rem; color: #6b7280;">Please confirm your password to continue.</p>
                                <input type="password" class="form-control" id="confirmPasswordInput" placeholder="Enter your password">
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="font-size: 0.8rem;">Cancel</button>
                                <button type="button" class="btn btn-green btn-sm" id="confirmPasswordBtn" onclick="submitPasswordConfirm()">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>`;
                document.body.insertAdjacentHTML('beforeend', modalHTML);
                modal = document.getElementById('passwordConfirmModal');
            }

            modal.dataset.action = action;
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            setTimeout(() => document.getElementById('confirmPasswordInput').focus(), 300);
        }

        async function submitPasswordConfirm() {
            const password = document.getElementById('confirmPasswordInput').value;
            if (!password) return;

            try {
                const response = await fetch('/user/confirm-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ password: password })
                });

                if (response.ok) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('passwordConfirmModal'));
                    modal.hide();
                    document.getElementById('confirmPasswordInput').value = '';

                    // Retry the original action
                    const action = document.getElementById('passwordConfirmModal').dataset.action;
                    if (action === 'enable') enableTwoFactor();
                    else if (action === 'disable') disableTwoFactor();
                    else if (action === 'recovery') showRecoveryCodes();
                    else if (action === 'regenerate') regenerateRecoveryCodes();
                } else {
                    showAlert('error', 'Incorrect password. Please try again.');
                }
            } catch (err) {
                showAlert('error', 'Network error. Please try again.');
            }
        }

        // ─── UPDATE BADGE ───
        function updateBadge(status) {
            const badges = document.querySelectorAll('.tfa-status-badge');
            badges.forEach(b => b.remove());
            const container = document.querySelector('.card-body .d-flex.align-items-center.justify-content-between');
            if (container) {
                if (status === 'enabled') {
                    container.insertAdjacentHTML('beforeend', '<span class="tfa-status-badge tfa-enabled"><i class="bx bxs-check-circle"></i> Enabled</span>');
                } else {
                    container.insertAdjacentHTML('beforeend', '<span class="tfa-status-badge tfa-disabled"><i class="bx bxs-shield-x"></i> Disabled</span>');
                }
            }
        }

        // ─── CLOCK (match ESS navbar) ───
        function updateNavClock() {
            const el = document.getElementById('navbarDateTime');
            if (el) {
                const now = new Date();
                el.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
        }
        setInterval(updateNavClock, 1000);
        updateNavClock();

        // ─── LOAD QR IF SETUP PENDING ───
        @if(auth()->user()->two_factor_secret && !auth()->user()->two_factor_confirmed_at)
            loadQRCode();
        @endif
    </script>
</body>
</html>
