<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Employee Portal') - {{ config('app.name') }}</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            border-radius: 10px;
            margin: 5px 0;
            transition: all 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white !important;
            transform: translateX(5px);
        }
        .nav-link i {
            font-size: 1.2rem;
            margin-right: 10px;
        }
        .main-content {
            background-color: #ffffff;
            min-height: 100vh;
            border-radius: 15px 0 0 15px;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            margin-left: -15px;
            padding-left: 30px;
        }
        .user-profile {
            background: rgba(255,255,255,0.2);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
        }
        .stat-card {
            border: none;
            border-radius: 15px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        }
        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            transform: translateY(-2px);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Main Container -->
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="p-4">
                    <!-- User Profile Section -->
                    <div class="user-profile text-center text-white">
                        <div class="mb-3">
                            <i class='bx bxs-user-circle' style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <p class="mb-0 small opacity-75">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>

                    <!-- Navigation Menu -->
                    <nav class="nav flex-column">
                        <a href="{{ route('ess.dashboard') }}" class="nav-link {{ request()->routeIs('ess.dashboard') ? 'active' : '' }}">
                            <i class='bx bx-home'></i>Dashboard
                        </a>
                        <a href="{{ route('ess.lms') }}" class="nav-link {{ request()->routeIs('ess.lms') ? 'active' : '' }}">
                            <i class='bx bx-book-open'></i>Learning & Assessment
                        </a>
                        <a href="#" class="nav-link">
                            <i class='bx bx-user'></i>My Profile
                        </a>
                        <a href="#" class="nav-link">
                            <i class='bx bx-calendar'></i>My Schedule
                        </a>
                        <a href="#" class="nav-link">
                            <i class='bx bx-file-blank'></i>Documents
                        </a>
                        <a href="#" class="nav-link">
                            <i class='bx bx-cog'></i>Settings
                        </a>
                    </nav>

                    <!-- Logout Button -->
                    <div class="mt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn logout-btn w-100">
                                <i class='bx bx-log-out me-2'></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>