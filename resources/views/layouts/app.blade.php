<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        <!-- Boxicons CDN -->
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
        
        <style>
            :root {
                --sidebar-width: 256px;
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
                z-index: 20; /* below the sidebar (sidebar z-index:40) */
            }

            /* When sidebar is active, anchor main content to the right by adding a left margin
               and reducing width so it doesn't underlap the fixed sidebar. */
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
                /* On mobile we use the overlay so don't resize or add margin */
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
        
        <!-- Navbar (fixed at top, full width) -->
        <nav class="fixed top-0 left-0 right-0 z-50 shadow" id="navbar" style="background: var(--color-primary); border-color: var(--color-primary);">
            @if (isset($navbar))
                {{ $navbar }}
            @else
                @yield('navbar')
            @endif
        </nav>
        
        <!-- Sidebar Overlay (for mobile) -->
        <div id="sidebarOverlay"></div>
        
        <!-- Sidebar (fixed, slides in/out) -->
        @if (isset($sidebar))
            {{ $sidebar }}
        @else
            @yield('sidebar')
        @endif
        
    <!-- Main Content Container -->
    <div id="mainContent" class="bg-gray-100 dark:bg-gray-900 shifted" style="margin-top: var(--navbar-height);">
            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow sticky top-0 z-10">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            
            <!-- Page Content -->
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
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        
        <script>
            // Global sidebar toggle functionality
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('mainContent');
                const overlay = document.getElementById('sidebarOverlay');
                const toggleBtn = document.getElementById('sidebarToggle');
                
                if (!sidebar || !mainContent || !toggleBtn) return;
                
                // Initialize sidebarOpen from DOM state so default 'active' class is respected
                let sidebarOpen = sidebar.classList.contains('active');

                // If sidebar is open by default and we're on mobile, show overlay
                if (sidebarOpen && window.innerWidth <= 768) {
                    overlay.classList.add('active');
                }
                
                function toggleSidebar() {
                    sidebarOpen = !sidebarOpen;
                    
                    if (sidebarOpen) {
                        sidebar.classList.add('active');
                        mainContent.classList.add('shifted');
                        
                        // Show overlay on mobile
                        if (window.innerWidth <= 768) {
                            overlay.classList.add('active');
                        }
                    } else {
                        sidebar.classList.remove('active');
                        mainContent.classList.remove('shifted');
                        overlay.classList.remove('active');
                    }
                }
                
                // Toggle button click
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleSidebar();
                });
                
                // Overlay click (mobile)
                overlay.addEventListener('click', function() {
                    if (sidebarOpen) {
                        toggleSidebar();
                    }
                });
                
                // Close sidebar on window resize (mobile to desktop)
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768) {
                        overlay.classList.remove('active');
                    }
                });
                
                // ESC key to close sidebar
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && sidebarOpen) {
                        toggleSidebar();
                    }
                });
            });
        </script>
    </body>
</html>