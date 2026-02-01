@extends('layouts.guest')

@section('content')
<div class="min-h-screen w-full flex relative overflow-hidden" style="background: #059669;">
    
    <!-- Background Decorative Circles -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full" style="background: rgba(255,255,255,0.08);"></div>
        <div class="absolute top-1/3 -left-20 w-72 h-72 rounded-full" style="background: rgba(255,255,255,0.05);"></div>
        <div class="absolute -top-20 right-1/3 w-64 h-64 rounded-full" style="background: rgba(255,255,255,0.06);"></div>
        <div class="absolute bottom-20 right-1/4 w-80 h-80 rounded-full" style="background: rgba(255,255,255,0.04);"></div>
        <div class="absolute top-1/2 right-[30%] w-48 h-48 rounded-full" style="background: rgba(255,255,255,0.07);"></div>
    </div>

    <!-- Left Side: Branding -->
    <div class="hidden md:flex md:w-[60%] flex-col items-center justify-center p-12 relative z-10">
        
        <!-- Logo -->
        <div class="mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-24 h-24">
        </div>
        
        <!-- Title -->
        <h1 class="text-4xl font-bold text-white mb-2">Microfinance HR</h1>
        <p class="text-white/70 text-sm tracking-wider mb-10">Human Resource II</p>
        
        <!-- Image Carousel -->
        <div class="w-full max-w-2xl mb-10 overflow-hidden">
            <!-- Carousel Container - Seamless Loop -->
            <div id="carousel" class="relative" style="min-height: 280px;">
                <!-- Slide 1 -->
                <div class="carousel-slide flex flex-col items-center px-8 transition-opacity duration-500" style="opacity: 1;">
                    <svg class="w-64 h-48 mb-4" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Training Clipart -->
                        <rect x="100" y="50" width="200" height="140" rx="8" fill="white" fill-opacity="0.15"/>
                        <rect x="120" y="70" width="60" height="40" rx="4" fill="#34D399"/>
                        <rect x="200" y="70" width="80" height="15" rx="2" fill="white" fill-opacity="0.5"/>
                        <rect x="200" y="95" width="60" height="15" rx="2" fill="white" fill-opacity="0.3"/>
                        <rect x="120" y="130" width="160" height="8" rx="2" fill="white" fill-opacity="0.2"/>
                        <rect x="120" y="150" width="120" height="8" rx="2" fill="white" fill-opacity="0.2"/>
                        <rect x="120" y="170" width="140" height="8" rx="2" fill="white" fill-opacity="0.2"/>
                        <!-- Person -->
                        <circle cx="320" cy="180" r="25" fill="#FCD34D"/>
                        <rect x="295" y="205" width="50" height="60" rx="8" fill="#60A5FA"/>
                        <circle cx="312" cy="175" r="3" fill="#1F2937"/>
                        <circle cx="328" cy="175" r="3" fill="#1F2937"/>
                        <path d="M315 185 Q320 190 325 185" stroke="#1F2937" stroke-width="2" fill="none"/>
                    </svg>
                    <h4 class="text-white font-bold text-xl mb-2">Training & Development</h4>
                    <p class="text-white/70 text-sm text-center">Empower your growth with comprehensive training programs.</p>
                </div>
                <!-- Slide 2 -->
                <div class="carousel-slide absolute top-0 left-0 w-full flex flex-col items-center px-8 transition-opacity duration-500" style="opacity: 0;">
                    <svg class="w-64 h-48 mb-4" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Team Clipart -->
                        <circle cx="150" cy="120" r="30" fill="#FCD34D"/>
                        <rect x="120" y="150" width="60" height="70" rx="10" fill="#34D399"/>
                        <circle cx="143" cy="115" r="4" fill="#1F2937"/>
                        <circle cx="157" cy="115" r="4" fill="#1F2937"/>
                        <path d="M145 128 Q150 133 155 128" stroke="#1F2937" stroke-width="2" fill="none"/>
                        
                        <circle cx="250" cy="120" r="30" fill="#FCD34D"/>
                        <rect x="220" y="150" width="60" height="70" rx="10" fill="#60A5FA"/>
                        <circle cx="243" cy="115" r="4" fill="#1F2937"/>
                        <circle cx="257" cy="115" r="4" fill="#1F2937"/>
                        <path d="M245 128 Q250 133 255 128" stroke="#1F2937" stroke-width="2" fill="none"/>
                        
                        <circle cx="200" cy="80" r="25" fill="#FCD34D"/>
                        <rect x="175" y="105" width="50" height="60" rx="8" fill="#F472B6"/>
                        <circle cx="193" cy="76" r="3" fill="#1F2937"/>
                        <circle cx="207" cy="76" r="3" fill="#1F2937"/>
                        <path d="M195 86 Q200 91 205 86" stroke="#1F2937" stroke-width="2" fill="none"/>
                        
                        <!-- Connection lines -->
                        <path d="M175 130 L190 110" stroke="white" stroke-opacity="0.3" stroke-width="2"/>
                        <path d="M225 130 L210 110" stroke="white" stroke-opacity="0.3" stroke-width="2"/>
                    </svg>
                    <h4 class="text-white font-bold text-xl mb-2">Team Collaboration</h4>
                    <p class="text-white/70 text-sm text-center">Work together to achieve organizational excellence.</p>
                </div>
                <!-- Slide 3 -->
                <div class="carousel-slide absolute top-0 left-0 w-full flex flex-col items-center px-8 transition-opacity duration-500" style="opacity: 0;">
                    <svg class="w-64 h-48 mb-4" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Analytics Clipart -->
                        <rect x="80" y="60" width="240" height="160" rx="8" fill="white" fill-opacity="0.15"/>
                        <!-- Chart bars -->
                        <rect x="110" y="160" width="30" height="40" rx="4" fill="#34D399"/>
                        <rect x="155" y="130" width="30" height="70" rx="4" fill="#60A5FA"/>
                        <rect x="200" y="100" width="30" height="100" rx="4" fill="#FCD34D"/>
                        <rect x="245" y="80" width="30" height="120" rx="4" fill="#F472B6"/>
                        <!-- Trend line -->
                        <path d="M125 150 L170 120 L215 90 L260 70" stroke="white" stroke-width="3" fill="none"/>
                        <circle cx="125" cy="150" r="5" fill="white"/>
                        <circle cx="170" cy="120" r="5" fill="white"/>
                        <circle cx="215" cy="90" r="5" fill="white"/>
                        <circle cx="260" cy="70" r="5" fill="white"/>
                    </svg>
                    <h4 class="text-white font-bold text-xl mb-2">Performance Analytics</h4>
                    <p class="text-white/70 text-sm text-center">Track progress and measure success with data-driven insights.</p>
                </div>
                <!-- Slide 4 -->
                <div class="carousel-slide absolute top-0 left-0 w-full flex flex-col items-center px-8 transition-opacity duration-500" style="opacity: 0;">
                    <svg class="w-64 h-48 mb-4" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Skills/Learning Clipart -->
                        <circle cx="200" cy="100" r="50" fill="white" fill-opacity="0.15"/>
                        <circle cx="200" cy="100" r="35" fill="white" fill-opacity="0.1"/>
                        <!-- Brain/lightbulb -->
                        <path d="M185 85 Q200 60 215 85 Q230 100 215 115 L210 125 L190 125 L185 115 Q170 100 185 85" fill="#FCD34D"/>
                        <rect x="190" y="125" width="20" height="10" rx="2" fill="#9CA3AF"/>
                        <line x1="195" y1="130" x2="205" y2="130" stroke="#6B7280" stroke-width="2"/>
                        <!-- Skill badges -->
                        <rect x="100" y="160" width="80" height="30" rx="15" fill="#34D399"/>
                        <rect x="160" y="200" width="80" height="30" rx="15" fill="#60A5FA"/>
                        <rect x="220" y="160" width="80" height="30" rx="15" fill="#F472B6"/>
                        <!-- Stars -->
                        <path d="M130 175 L132 180 L138 180 L133 184 L135 190 L130 186 L125 190 L127 184 L122 180 L128 180 Z" fill="white"/>
                        <path d="M190 215 L192 220 L198 220 L193 224 L195 230 L190 226 L185 230 L187 224 L182 220 L188 220 Z" fill="white"/>
                        <path d="M250 175 L252 180 L258 180 L253 184 L255 190 L250 186 L245 190 L247 184 L242 180 L248 180 Z" fill="white"/>
                    </svg>
                    <h4 class="text-white font-bold text-xl mb-2">Skill Development</h4>
                    <p class="text-white/70 text-sm text-center">Bridge competency gaps and unlock your potential.</p>
                </div>
            </div>
        </div>
        
        <!-- Quote -->
        <div class="text-center max-w-md">
            <p class="text-white/90 italic text-sm leading-relaxed">
                "The strength of the team is each individual member. The strength of each member is the team."
            </p>
            <p class="text-white/60 text-sm mt-3">- Phil Jackson</p>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-full md:w-[40%] flex items-center justify-center p-6 relative z-10">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back!</h2>
                <p class="text-sm text-gray-500">Please enter your details to sign in.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                @if (session('status'))
                    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg p-3">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <i class="bx bx-at text-xl"></i>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg
                                   focus:ring-2 focus:ring-[#059669] focus:border-[#059669] 
                                   transition-all duration-200 text-sm text-gray-700"
                            placeholder="Enter your email" autocomplete="off">
                    </div>
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <i class="bx bx-lock-alt text-xl"></i>
                        </span>
                        <input id="password" type="password" name="password" required
                            class="block w-full pl-12 pr-12 py-3 border border-gray-200 rounded-lg
                                   focus:ring-2 focus:ring-[#059669] focus:border-[#059669] 
                                   transition-all duration-200 text-sm text-gray-700"
                            placeholder="Enter your password">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600">
                            <i id="password-toggle-icon" class="bx bx-show text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Sign In Button -->
                <button type="submit"
                    class="w-full py-3 px-4 text-sm font-semibold rounded-lg text-white 
                           bg-[#059669] hover:bg-[#047857] 
                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#059669] 
                           transition-all duration-200">
                    Sign In
                </button>

                <!-- Terms Checkbox -->
                <div class="mt-6 flex items-center">
                    <input type="checkbox" id="terms" name="terms" required
                        class="w-4 h-4 rounded border-gray-300 text-[#059669] focus:ring-[#059669]">
                    <label for="terms" class="ml-2 text-sm text-gray-600">
                        I agree to the 
                        <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" 
                           class="text-[#059669] hover:text-[#047857] font-medium hover:underline">
                            Terms and Conditions
                        </a>
                    </label>
                </div>

                <!-- Copyright -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-400">© 2026 Microfinance HR. All Rights Reserved.</p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Password Toggle
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('password-toggle-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bx-show');
        toggleIcon.classList.add('bx-hide');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bx-hide');
        toggleIcon.classList.add('bx-show');
    }
}

// Carousel Functionality - Smooth fade transition
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-slide');
const totalSlides = slides.length;

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.style.opacity = i === index ? '1' : '0';
    });
}

// Auto-rotate every 3 seconds
setInterval(() => {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
}, 3000);
</script>

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