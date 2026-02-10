<!-- ESS Bootstrap Navbar - Aligned with Admin Navbar Theme -->
<nav class="navbar navbar-expand-lg fixed-top shadow-sm" style="background: #ffffff; border-bottom: 1px solid #f0f0f0; height: 64px;">
    <div class="container-fluid px-3 px-sm-4">
        <!-- Brand / Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none py-0" href="{{ route('ess.dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 36px; height: 36px; object-fit: contain;">
            <div class="d-none d-sm-block lh-sm">
                <div class="fw-bold" style="font-size: 0.85rem; color: #1f2937;">Microfinance HR</div>
                <div class="fw-semibold" style="font-size: 0.6rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Employee Self-Service</div>
            </div>
        </a>

        <!-- Right side items -->
        <div class="d-flex align-items-center gap-2 gap-sm-3">
            <!-- Clock Pill -->
            <span id="navbarDateTime" class="d-none d-md-inline-block px-3 py-2 rounded-3 border" 
                  style="font-size: 0.7rem; font-weight: 700; color: #374151; background: #f9fafb; border-color: #e5e7eb !important;">
                --:--:--
            </span>

            <!-- Divider -->
            <div class="d-none d-sm-block" style="width: 1px; height: 32px; background: #e5e7eb;"></div>

            <!-- Notifications - links to promotion offers -->
            <a href="{{ route('ess.promotion-offers') }}" class="btn btn-light border-0 rounded-3 position-relative d-flex align-items-center justify-content-center" 
                    style="width: 40px; height: 40px; background: transparent; text-decoration: none;" 
                    onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'"
                    title="Promotion Offers">
                <i class="bx bxs-bell" style="font-size: 1.25rem; color: #f59e0b;"></i>
                @php
                    $pendingOfferCount = 0;
                    try {
                        $essUser = \Illuminate\Support\Facades\Auth::user();
                        $essEmpId = null;
                        $essEmployeeApi = app(\App\Services\EmployeeApiService::class);
                        $essAllEmps = $essEmployeeApi->getEmployees();
                        if ($essAllEmps) {
                            $essMatch = collect($essAllEmps)->firstWhere('email', $essUser->email);
                            if ($essMatch) $essEmpId = $essMatch['employee_id'] ?? $essMatch['id'] ?? null;
                        }
                        if ($essEmpId) {
                            $pendingOfferCount = \Illuminate\Support\Facades\DB::connection('succession_planning')
                                ->table('promotions')
                                ->where('employee_id', $essEmpId)
                                ->where('status', 'pending_acceptance')
                                ->count();
                        }
                    } catch (\Exception $e) {}
                @endphp
                @if($pendingOfferCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                        {{ $pendingOfferCount }}
                    </span>
                @endif
            </a>

            <!-- Messages -->
            <button class="btn btn-light border-0 rounded-3 position-relative d-flex align-items-center justify-content-center" 
                    type="button" style="width: 40px; height: 40px; background: transparent;" 
                    onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                <i class="bx bxs-envelope" style="font-size: 1.25rem; color: #6b7280;"></i>
            </button>

            <!-- Divider -->
            <div class="d-none d-sm-block" style="width: 1px; height: 32px; background: #e5e7eb;"></div>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <button class="btn d-flex align-items-center gap-2 rounded-3 border-0 px-2 py-2" 
                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="background: transparent; transition: background 0.15s;"
                        onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                    <!-- Avatar Circle -->
                    <div class="d-flex align-items-center justify-content-center rounded-circle border" 
                         style="width: 36px; height: 36px; background: #ecfdf5; border-color: #d1fae5 !important; font-weight: 700; color: #059669; font-size: 0.85rem;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <!-- Name & Role -->
                    <div class="d-none d-md-block text-start lh-sm">
                        <div class="fw-bold" style="font-size: 0.8rem; color: #374151;">{{ Auth::user()->name }}</div>
                        <div class="fw-semibold" style="font-size: 0.6rem; color: #6b7280; text-transform: uppercase;">Employee</div>
                    </div>
                    <!-- Chevron -->
                    <svg style="width: 16px; height: 16px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" style="border-radius: 12px; min-width: 220px; overflow: hidden;">
                    <li>
                        <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" href="#" style="font-size: 0.85rem; color: #374151;">
                            <i class="bx bx-user" style="font-size: 1.1rem; color: #6b7280;"></i> My Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" href="#" style="font-size: 0.85rem; color: #374151;">
                            <i class="bx bx-cog" style="font-size: 1.1rem; color: #6b7280;"></i> Settings
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" href="{{ route('ess.security') }}" style="font-size: 0.85rem; color: #374151;">
                            <i class="bx bx-shield" style="font-size: 1.1rem; color: #6b7280;"></i> Security Settings
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1" style="border-color: #f3f4f6;"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" style="font-size: 0.85rem; color: #dc2626;">
                                <i class="bx bx-power-off" style="font-size: 1.1rem;"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    .navbar .dropdown-item:hover {
        background-color: #f9fafb !important;
    }
    .navbar .dropdown-item:last-child:hover {
        background-color: #fef2f2 !important;
    }
</style>

<script>
    // Update date/time - matching admin navbar clock style
    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'short', 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        document.getElementById('navbarDateTime').textContent = now.toLocaleDateString('en-US', options);
    }
    
    // Update immediately and then every minute
    updateDateTime();
    setInterval(updateDateTime, 60000);
</script>