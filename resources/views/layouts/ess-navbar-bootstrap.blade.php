<!-- ESS Bootstrap Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="{{ route('ess.dashboard') }}">
            <i class="bx bx-buildings me-2"></i>
            Employee Portal
        </a>

        <!-- Right side items -->
        <div class="d-flex align-items-center">
            <!-- Date/Time -->
            <span class="text-light me-3 d-none d-md-block small" id="navbarDateTime"></span>

            <!-- Notifications -->
            <button class="btn btn-outline-light btn-sm me-2" type="button">
                <i class="bx bx-envelope"></i>
            </button>

            <button class="btn btn-outline-light btn-sm me-3" type="button">
                <i class="bx bx-bell"></i>
            </button>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bx bx-user-circle me-1"></i>
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="bx bx-user me-2"></i>My Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bx bx-cog me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bx bx-log-out me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
    // Update date/time
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