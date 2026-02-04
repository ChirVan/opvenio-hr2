<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Global Title and Favicon -->
    <title>@yield('title', 'Microfinance')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        :root {
            --navbar-height: 74px;
            --sidebar-width: 288px;
        }

        /* Sidebar Styles */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            transition: transform 0.3s ease-in-out;
            z-index: 40;
            box-sizing: border-box;
        }

        /* Desktop: Sidebar visible by default */
        @media (min-width: 769px) {
            #sidebar {
                transform: translateX(0);
            }
            #sidebar.hidden-sidebar {
                transform: translateX(-100%);
            }
        }

        /* Mobile: Sidebar hidden by default, show when active */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.active {
                transform: translateX(0);
            }
        }

        /* Main Content Styles */
        #mainContent {
            margin-left: 0;
            transition: width 0.3s ease-in-out, margin-left 0.3s ease-in-out;
            width: 100%;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            position: relative;
            z-index: 20;
        }

        /* Desktop: Main content shifted by default */
        @media (min-width: 769px) {
            #mainContent {
                margin-left: var(--sidebar-width);
                width: calc(100% - var(--sidebar-width));
            }
            #mainContent.full-width {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Mobile: Main content full width */
        @media (max-width: 768px) {
            #mainContent {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Overlay for mobile */
        #sidebarOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 35;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        #sidebarOverlay.active {
            display: block;
            opacity: 1;
        }

        /* Smooth scrolling for main content */
        #mainContent {
            overflow-y: auto;
            height: calc(100vh - var(--navbar-height));
        }
    </style>
</head>

<body class="font-sans antialiased overflow-hidden">
    <x-banner />

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 shadow" id="navbar" style="background: var(--color-primary); border-color: var(--color-primary);">
        @if (isset($navbar))
            {{ $navbar }}
        @else
            @yield('navbar')
        @endif
    </nav>

    <!-- Sidebar Overlay (mobile) -->
    <div id="sidebarOverlay"></div>

    <!-- Sidebar -->
    @if (isset($sidebar))
        {{ $sidebar }}
    @else
        @yield('sidebar')
    @endif

    <!-- Main Content -->
    <div id="mainContent" class="bg-gray-100 dark:bg-gray-900" style="margin-top: var(--navbar-height);">
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow sticky top-0 z-10">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="flex-1 p-6">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>
    </div>

    @stack('modals')
    @livewireScripts

    <script>
        // Sidebar toggle logic
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            if (!sidebar || !mainContent || !toggleBtn) return;
            
            // Track if we're on mobile
            const isMobile = () => window.innerWidth <= 768;
            
            // Track sidebar state (mobile only uses active class)
            let mobileOpen = false;

            function toggleSidebar() {
                if (isMobile()) {
                    // Mobile: Toggle active class
                    mobileOpen = !mobileOpen;
                    if (mobileOpen) {
                        sidebar.classList.add('active');
                        overlay.classList.add('active');
                    } else {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                    }
                } else {
                    // Desktop: Toggle hidden-sidebar and full-width classes
                    const isHidden = sidebar.classList.contains('hidden-sidebar');
                    if (isHidden) {
                        sidebar.classList.remove('hidden-sidebar');
                        mainContent.classList.remove('full-width');
                    } else {
                        sidebar.classList.add('hidden-sidebar');
                        mainContent.classList.add('full-width');
                    }
                }
            }

            toggleBtn.addEventListener('click', e => {
                e.stopPropagation();
                toggleSidebar();
            });

            if (overlay) {
                overlay.addEventListener('click', () => {
                    if (mobileOpen) toggleSidebar();
                });
            }

            window.addEventListener('resize', () => {
                if (!isMobile()) {
                    // Reset mobile state when switching to desktop
                    sidebar.classList.remove('active');
                    if (overlay) overlay.classList.remove('active');
                    mobileOpen = false;
                }
            });

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && mobileOpen) toggleSidebar();
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Simple toast helper (top-right small notifications)
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        // Generic notification helper
        function swalNotify(type = 'success', title = '', text = '') {
            Toast.fire({ icon: type, title: title || text });
        }

        // Confirm dialog helper that returns the promise
        function swalConfirm({ title = 'Are you sure?', text = '', confirmText = 'Yes', cancelText = 'Cancel' } = {}) {
            return Swal.fire({
            title,
            text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            reverseButtons: true,
            });
        }
    </script>

</body>
</html>
