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
        }

        /* Sidebar Styles */
        #sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: var(--sidebar-width);
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            z-index: 40;
            box-sizing: border-box;
        }

        #sidebar.active {
            transform: translateX(0);
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

        #mainContent.shifted {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
        }

        /* Overlay for mobile */
        #sidebarOverlay {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: 100%;
            height: calc(100vh - var(--navbar-height));
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 30;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        #sidebarOverlay.active {
            display: block;
            opacity: 1;
        }

        /* Responsive behavior */
        @media (max-width: 768px) {
            #mainContent.shifted {
                margin-left: 0;
                width: 100%;
            }
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
    <div id="mainContent" class="bg-gray-100 dark:bg-gray-900 shifted" style="margin-top: var(--navbar-height);">
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
            
            let sidebarOpen = sidebar.classList.contains('active');
            if (sidebarOpen && window.innerWidth <= 768) {
                overlay.classList.add('active');
            }

            function toggleSidebar() {
                sidebarOpen = !sidebarOpen;
                if (sidebarOpen) {
                    sidebar.classList.add('active');
                    mainContent.classList.add('shifted');
                    if (window.innerWidth <= 768) overlay.classList.add('active');
                } else {
                    sidebar.classList.remove('active');
                    mainContent.classList.remove('shifted');
                    overlay.classList.remove('active');
                }
            }

            toggleBtn.addEventListener('click', e => {
                e.stopPropagation();
                toggleSidebar();
            });

            overlay.addEventListener('click', () => {
                if (sidebarOpen) toggleSidebar();
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) overlay.classList.remove('active');
            });

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && sidebarOpen) toggleSidebar();
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
