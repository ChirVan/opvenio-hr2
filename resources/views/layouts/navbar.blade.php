{{-- Navbar: Matching reference design exactly --}}
<header class="h-16 bg-white flex items-center justify-between px-4 sm:px-6 relative shadow-[0_2px_8px_rgba(0,0,0,0.06)]">
    
    <!-- Border cover (removes the vertical line only in header height) -->
    <div class="hidden md:block absolute left-0 top-0 h-16 w-[2px] bg-white"></div>

    <div class="flex items-center gap-3">
        <!-- Mobile menu button / Sidebar toggle -->
        <button id="sidebarToggle"
            class="w-10 h-10 rounded-xl hover:bg-gray-100 active:bg-gray-200 transition flex items-center justify-center text-gray-600">
            <i class='bx bx-menu text-xl'></i>
        </button>
        
        <!-- Logo (visible in navbar) -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-9 h-9">
            <div class="hidden sm:block leading-tight">
                <div class="font-bold text-gray-800 group-hover:text-emerald-600 transition-colors text-sm">
                    Microfinance HR
                </div>
                <div class="text-[10px] text-gray-500 font-semibold uppercase group-hover:text-emerald-600 transition-colors">
                    HUMAN RESOURCE II
                </div>
            </div>
        </a>
    </div>

    <div class="flex items-center gap-3 sm:gap-5">
        <!-- Clock pill -->
        <span id="real-time-clock"
            class="text-xs font-bold text-gray-700 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
            --:--:--
        </span>

        <!-- Bell with notification badge -->
        <button class="w-10 h-10 rounded-xl hover:bg-gray-100 active:bg-gray-200 transition flex items-center justify-center relative">
            <i class='bx bxs-bell text-xl text-amber-500'></i>
            <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-red-500 border-2 border-white"></span>
        </button>

        <!-- Divider -->
        <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>

        <!-- User Profile Dropdown -->
        <div class="relative">
            <button id="user-menu-button"
                class="flex items-center gap-3 focus:outline-none group rounded-xl px-2 py-2
                       hover:bg-gray-100 active:bg-gray-200 transition">
                <!-- Avatar -->
                <div class="w-10 h-10 rounded-full bg-white shadow group-hover:shadow-md transition-shadow overflow-hidden flex items-center justify-center border border-gray-100">
                    <div class="w-full h-full flex items-center justify-center font-bold text-emerald-600 bg-emerald-50">
                        {{ strtoupper(substr(Auth::user() ? Auth::user()->name : 'G', 0, 1)) }}
                    </div>
                </div>
                <!-- Name & Role -->
                <div class="hidden md:flex flex-col items-start text-left">
                    <span class="text-sm font-bold text-gray-700 group-hover:text-emerald-600 transition-colors">
                        {{ Auth::user() ? Auth::user()->name : 'Guest' }}
                    </span>
                    <span class="text-[10px] text-gray-500 font-medium uppercase group-hover:text-emerald-600 transition-colors">
                        {{ Auth::user() && Auth::user()->role ? Auth::user()->role : 'User' }}
                    </span>
                </div>
                <!-- Chevron -->
                <svg class="w-4 h-4 text-gray-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Dropdown Panel -->
            <div id="user-menu-dropdown"
                class="dropdown-panel hidden opacity-0 translate-y-2 scale-95 pointer-events-none
                       absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-lg border border-gray-100
                       transition-all duration-200 z-50">
                <a href="{{ route('profile.show') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition rounded-t-xl">
                    <i class='bx bx-user mr-2'></i> Profile
                </a>
                <a href="{{ route('profile.show') }}#two-factor" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-cog mr-2'></i> Settings
                </a>
                <a href="{{ route('audit.logs') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-history mr-2'></i> Audit Logs
                </a>
                <div class="h-px bg-gray-100"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition rounded-b-xl">
                        <i class='bx bx-power-off mr-2'></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<style>
    .dropdown-panel {
        transform-origin: top right;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Realtime Clock
    const clockEl = document.getElementById('real-time-clock');
    const updateClock = () => {
        if (!clockEl) return;
        const now = new Date();
        clockEl.textContent = now.toLocaleTimeString('en-US', {
            hour12: true,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        });
    };
    if (clockEl) {
        updateClock();
        setInterval(updateClock, 1000);
    }

    // User Dropdown
    const userBtn = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-menu-dropdown');

    const openDropdown = () => {
        if (!userDropdown) return;
        userDropdown.classList.remove('hidden');
        requestAnimationFrame(() => {
            userDropdown.classList.remove('opacity-0', 'translate-y-2', 'scale-95', 'pointer-events-none');
            userDropdown.classList.add('opacity-100', 'translate-y-0', 'scale-100', 'pointer-events-auto');
        });
    };

    const closeDropdown = () => {
        if (!userDropdown) return;
        userDropdown.classList.add('opacity-0', 'translate-y-2', 'scale-95', 'pointer-events-none');
        userDropdown.classList.remove('opacity-100', 'translate-y-0', 'scale-100', 'pointer-events-auto');
        setTimeout(() => userDropdown.classList.add('hidden'), 200);
    };

    if (userBtn && userDropdown) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isHidden = userDropdown.classList.contains('hidden');
            if (isHidden) openDropdown();
            else closeDropdown();
        });

        document.addEventListener('click', () => {
            if (!userDropdown.classList.contains('hidden')) closeDropdown();
        });
    }

    // Mobile Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const mobileBtn = document.getElementById('mobile-menu-btn');

    const openSidebar = () => {
        if (!sidebar || !overlay) return;
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        requestAnimationFrame(() => overlay.classList.remove('opacity-0'));
    };

    const closeSidebar = () => {
        if (!sidebar || !overlay) return;
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('opacity-0');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    };

    if (mobileBtn && sidebar) {
        mobileBtn.addEventListener('click', () => {
            const closed = sidebar.classList.contains('-translate-x-full');
            if (closed) openSidebar();
            else closeSidebar();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }
});
</script>
