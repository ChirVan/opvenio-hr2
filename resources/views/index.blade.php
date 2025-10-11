@extends('layouts.guest')

@section('content')
<div class="h-full w-full flex bg-gray-100 dark:bg-gray-900">
    <!-- Left: Login form (30%) -->
    <div class="w-full md:w-[30%] flex items-center justify-center bg-white p-10 shadow-lg">
        <div class="w-full max-w-sm text-center">
            <!-- Logo and title -->
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16 rounded-full mb-2">
                <h2 class="text-xl font-semibold text-gray-800">Microfinance</h2>
            </div>

            <h2 class="text-3xl font-semibold mb-4 text-gray-800">Welcome back</h2>
            <p class="text-sm text-gray-600 mb-6">Sign in to your account to continue to Javes Cooperative.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                @if (session('status'))
                    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-100 rounded p-3">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded p-3">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Email Field -->
<div class="mb-4 text-start">
    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <i class="bx bx-envelope text-gray-400 text-lg"></i>
        </span>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
            placeholder="Enter your email">
    </div>
</div>

<!-- Password Field -->
<div class="mb-6 text-start">
    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <i class="bx bx-lock-alt text-gray-400 text-lg"></i>
        </span>
        <input id="password" type="password" name="password" required
            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
            placeholder="Enter your password">
    </div>
</div>

                <button type="submit"
                    class="w-full py-2 px-4 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Sign in
                </button>

                <p class="mt-4 text-xs text-gray-600">
                    By signing in, you agree to our
                    <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" class="text-green-600 hover:underline">
                        Terms and Conditions
                    </a>.
                </p>
            </form>
        </div>
    </div>

    <!-- Right: Branding (70%) -->
    <div class="hidden md:flex md:w-[70%] items-center justify-center p-12" style="background: var(--color-primary);">
        <div class="text-white text-center max-w-md">
            <h3 class="text-4xl font-bold mb-2">JAVES COOPERATIVE MICRO</h3>
            <p class="text-base mb-6">Human Resources II</p>

            <div class="bg-white bg-opacity-10 rounded-2xl p-8 shadow-lg flex flex-col items-center mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white opacity-90 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0 0c-4.418 0-8-1.79-8-4V9m8 11c4.418 0 8-1.79 8-4V9" />
                </svg>
                <h4 class="text-xl font-semibold mb-2">Training and Development</h4>
                <p class="text-sm text-white text-opacity-80 text-center">
                    Empower your growth with our comprehensive training and development programs.
                </p>
            </div>

            <p class="text-xs text-white text-opacity-80">Secure access to your HR data. If you have trouble signing in, contact your administrator.</p>
        </div>
    </div>
</div>

<!-- 💼 Modern Terms & Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
      
      <!-- Sticky header -->
      <div class="modal-header bg-success text-white sticky-top">
        <div class="d-flex align-items-center">
          <i class="bx bx-shield-alt-2 fs-3 me-2"></i>
          <h5 class="modal-title fw-semibold mb-0" id="termsModalLabel">Terms & Conditions</h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Scrollable content -->
      <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto; background-color: #f9fafb;">
        <!-- Section: Privacy -->
        <section class="mb-4 p-3 rounded bg-white shadow-sm">
          <h6 class="fw-bold text-success mb-3"><i class="bx bx-lock-alt me-2"></i>Your Data & Privacy</h6>
          <ul class="small mb-0">
            <li>We collect only the data we need to operate the System efficiently.</li>
            <li>Sensitive data (IDs, financial info, credentials) is encrypted and protected.</li>
            <li>We follow strict retention rules:
              <ul>
                <li>Financial records: up to <strong>2 years</strong></li>
                <li>Account data: while active + <strong>1 year</strong></li>
                <li>Logs: up to <strong>90 days</strong></li>
              </ul>
            </li>
          </ul>
        </section>

        <!-- Section: Security -->
        <section class="mb-4 p-3 rounded bg-white shadow-sm">
          <h6 class="fw-bold text-success mb-3"><i class="bx bx-check-shield me-2"></i>Security Practices</h6>
          <ul class="small mb-0">
            <li>All communication is protected by <strong>TLS encryption</strong>.</li>
            <li>Passwords and tokens are hashed and never stored in plain text.</li>
            <li>Admins and privileged users must enable <strong>Two-Factor Authentication</strong>.</li>
            <li>We never embed secrets or API keys in public-facing code.</li>
          </ul>
        </section>

        <!-- Section: Access & Identity -->
        <section class="mb-4 p-3 rounded bg-white shadow-sm">
          <h6 class="fw-bold text-success mb-3"><i class="bx bx-id-card me-2"></i>Access & Identity</h6>
          <ul class="small mb-0">
            <li>Access follows the <strong>least-privilege principle</strong> — you see only what you’re authorized to.</li>
            <li>Admin activities are <strong>logged and monitored</strong> for accountability.</li>
            <li>We support secure login through <strong>SSO</strong>, <strong>OAuth</strong>, and other identity providers.</li>
          </ul>
        </section>

        <!-- Section: Protection -->
        <section class="mb-4 p-3 rounded bg-white shadow-sm">
          <h6 class="fw-bold text-success mb-3"><i class="bx bx-shield-quarter me-2"></i>How We Protect You</h6>
          <ul class="small mb-0">
            <li>We continuously scan for vulnerabilities and apply patches promptly.</li>
            <li>Our system monitors user activity for suspicious behavior.</li>
            <li>Regular <strong>security audits</strong> and penetration testing ensure safety.</li>
          </ul>
        </section>

        <!-- Section: User Responsibilities -->
        <section class="mb-4 p-3 rounded bg-white shadow-sm">
          <h6 class="fw-bold text-success mb-3"><i class="bx bx-user-check me-2"></i>Your Responsibilities</h6>
          <ul class="small mb-0">
            <li>Do not attempt to hack, disrupt, or exploit the System.</li>
            <li>Keep your credentials private and secure.</li>
            <li>Do not upload malicious files or attempt unauthorized access.</li>
          </ul>
        </section>

        <!-- Section: Reporting -->
        <section class="mb-4 p-3 rounded bg-white shadow-sm">
          <h6 class="fw-bold text-success mb-3"><i class="bx bx-envelope me-2"></i>Reporting Issues</h6>
          <p class="small mb-0">
            If you discover a security issue, please contact us at
            <strong>microfinancehr2@gmail.com</strong>.<br>
            Include a summary, reproduction steps, and any relevant screenshots or logs.
          </p>
        </section>

        <!-- Section: Legal -->
        <section class="p-3 rounded bg-white shadow-sm">
          <h6 class="fw-bold text-success mb-3"><i class="bx bx-file me-2"></i>Legal & Compliance</h6>
          <ul class="small mb-0">
            <li>We comply with applicable privacy and data protection laws.</li>
            <li>We maintain detailed audit records to ensure accountability.</li>
            <li>Updates to these terms will be communicated — continued use means you accept them.</li>
          </ul>
        </section>
      </div>

      <!-- Footer -->
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">
          <i class="bx bx-check-circle me-1"></i> I Understand
        </button>
      </div>

    </div>
  </div>
</div>

@endsection