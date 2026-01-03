{{-- Navbar: brand, compact icons, date/time, profile dropdown --}}
<div class="flex items-center justify-between px-4 py-2 text-white" style="height: var(--navbar-height);">
    <div class="flex items-center gap-3">
        <!-- Sidebar toggle -->
        <button id="sidebarToggle" class="btn btn-primary inline-flex items-center" aria-label="Toggle sidebar">
            <i id="toggleIcon" class="bx bx-menu text-xl text-white"></i>
        </button>

        <!-- Brand / Title -->
        <a href="/" class="ml-3 font-semibold text-lg text-white">Human Resources 2</a>
    </div>

    <div class="flex items-center gap-3">
        <!-- Date/time -->
        <div id="navbarDateTime" class="text-sm text-white hidden sm:block"></div>

        <!-- Inbox icon -->
        <button class="inline-flex items-center p-2 text-white hover:bg-white hover:bg-opacity-10 rounded" aria-label="Inbox">
            <i class="bx bx-envelope text-lg"></i>
        </button>

        <!-- Notifications icon -->
        <button class="inline-flex items-center p-2 text-white hover:bg-white hover:bg-opacity-10 rounded" aria-label="Notifications">
            <i class="bx bx-bell text-lg"></i>
        </button>

        <!-- Profile dropdown -->
        <div class="relative" x-data="{}">
            <button id="profileButton" class="inline-flex items-center gap-2 p-2 text-white hover:bg-white hover:bg-opacity-10 rounded">
                <i class="bx bx-user-circle text-2xl"></i>
                <span class="hidden sm:inline">{{ Auth::user() ? Auth::user()->name : 'Guest' }}</span>
                <i class="bx bx-chevron-down"></i>
            </button>

            <!-- Simple dropdown menu -->
            <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded shadow-lg py-2 z-50">

                <a href="{{ route('profile.show') }}#two-factor" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 text-black">
                    <i class="bx bx-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="{{ route('audit.logs') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 text-black">
                    <i class="bx bx-history"></i>
                    <span>Audit Logs</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center gap-2 text-black">
                        <i class="bx bx-power-off"></i>
                        <span>Log out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Date/time display
        document.addEventListener('DOMContentLoaded', function() {
            const el = document.getElementById('navbarDateTime');
            if (!el) return;
            function update() {
                const now = new Date();
                const date = now.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
                const time = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                el.textContent = `${date} ${time}`;
            }
            update();
            setInterval(update, 60_000);

            // Profile dropdown toggle
            const profileBtn = document.getElementById('profileButton');
            const dropdown = document.getElementById('profileDropdown');
            if (profileBtn && dropdown) {
                profileBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdown.classList.toggle('hidden');
                });
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>