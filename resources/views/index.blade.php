@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">
    <div class="max-w-4xl w-full rounded-lg shadow-lg overflow-hidden flex">
        <!-- Left: Login form -->
        <div class="w-full md:w-1/2 p-8 bg-white">
            <h2 class="text-2xl font-semibold mb-4">Welcome back</h2>
            <p class="text-sm text-gray-600 mb-6">Sign in to your account to continue to OPVENIO HR2.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                @if (session('status'))
                    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-100 rounded p-3">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded p-3">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required
                           class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>

                <div class="flex items-center justify-between mb-4">
                    <label class="flex items-center text-sm">
                        <input type="checkbox" name="remember" class="mr-2" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>

                    <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:underline">Forgot password?</a>
                </div>

                <div>
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Sign in
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">Don't have an account? <a href="#" class="text-green-600 hover:underline">Request access</a></p>
        </div>

        <!-- Right: Green panel with branding -->
        <div class="hidden md:flex md:w-1/2 items-center justify-center p-8" style="background: var(--color-primary);">
            <div class="text-white text-center max-w-xs">
                <h3 class="text-3xl font-bold mb-2">OPVENIO HR2</h3>
                <p class="text-sm mb-6">Human Resources II</p>

                    <div class="mb-6">
                        <div class="bg-white bg-opacity-10 rounded-lg p-6 shadow-md flex flex-col items-center">
                            <div class="mb-3">
                                <!-- Heroicons academic-cap SVG for training/development -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0 0c-4.418 0-8-1.79-8-4V9m8 11c4.418 0 8-1.79 8-4V9" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-white mb-1">Training and Development</h4>
                            <p class="text-sm text-white text-opacity-80 text-center">
                                Empower your growth with our comprehensive training and development programs.
                            </p>
                        </div>
                    </div>

                <p class="text-xs">Secure access to your HR data. If you have trouble signing in, contact your administrator.</p>
            </div>
        </div>
    </div>
</div>
@endsection