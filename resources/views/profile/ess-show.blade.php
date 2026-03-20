<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Settings</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind (already used by Jetstream) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{
        --primary: #0ea5a6; /* teal-ish */
        --muted: #6b7280;
        --bg: #f8fafc;
        --card-shadow: 0 6px 18px rgba(15,23,42,0.06);
        --glass: rgba(255,255,255,0.8);
        }
        .main{flex:1;padding:1.5rem 2rem}
        .navbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.25rem}
        .search-input{max-width:420px}
    </style>
</head>
<body class="bg-gray-100">

    <!-- Sidebar -->
    <div class="app-shell">
        @include('layouts.ess-aside')

        <main class='main'>
            <!-- Navbar -->
            @include('layouts.ess-navbar')
        
            <div class="container py-4">

                <!-- Page Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Employee Settings</h1>
                    <p class="text-gray-600 mt-1">
                        Manage your account information, security settings, and preferences.
                    </p>
                </div>

                <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 bg-white rounded-xl shadow-sm">

                    @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                        @livewire('profile.update-profile-information-form')
                        <x-section-border />
                    @endif

                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                        <div class="mt-10 sm:mt-0">
                            @livewire('profile.update-password-form')
                        </div>
                        <x-section-border />
                    @endif

                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        <div class="mt-10 sm:mt-0">
                            @livewire('profile.two-factor-authentication-form')
                        </div>
                        <x-section-border />
                    @endif

                    <div class="mt-10 sm:mt-0">
                        @livewire('profile.logout-other-browser-sessions-form')
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <x-section-border />
                        <div class="mt-10 sm:mt-0">
                            @livewire('profile.delete-user-form')
                        </div>
                    @endif

                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @livewireStyles
    @livewireScripts

</body>
</html>