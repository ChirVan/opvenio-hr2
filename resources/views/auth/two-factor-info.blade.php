<x-app-layout>
    @section('navbar')
        @include('layouts.navbar')
    @endsection

    @section('sidebar')
        @include('layouts.sidebar')
    @endsection

    <div class="py-3">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Security Settings</h1>
            <p class="text-gray-600 mt-1">Manage your account security and two-factor authentication.</p>
        </div>
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Two-Factor Authentication Status</h2>
            
            <div class="space-y-6">
                <!-- Current Status -->
                <div class="border rounded-lg p-4 {{ $enabled ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($enabled && $confirmed)
                                <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($enabled && !$confirmed)
                                <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            @else
                                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium">
                                @if($enabled && $confirmed)
                                    Two-Factor Authentication is Enabled
                                @elseif($enabled && !$confirmed)
                                    Two-Factor Authentication is Pending Confirmation
                                @else
                                    Two-Factor Authentication is Disabled
                                @endif
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                @if($enabled && $confirmed)
                                    Your account is protected with two-factor authentication.
                                @elseif($enabled && !$confirmed)
                                    Complete the setup process to activate two-factor authentication.
                                @else
                                    Enable two-factor authentication to add an extra layer of security.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- User Information -->
                <div class="border rounded-lg p-4 border-gray-200">
                    <h4 class="font-medium text-gray-900 mb-3">Account Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">Email:</span>
                            <span class="ml-2">{{ $user->email }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Name:</span>
                            <span class="ml-2">{{ $user->name }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">2FA Secret:</span>
                            <span class="ml-2">{{ $user->two_factor_secret ? 'Present' : 'Not Set' }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">2FA Confirmed:</span>
                            <span class="ml-2">{{ $user->two_factor_confirmed_at ? $user->two_factor_confirmed_at->format('M d, Y H:i') : 'Not Confirmed' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-4">
                    <a href="{{ route('profile.show') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                        Manage 2FA Settings
                    </a>
                    
                    <a href="{{ route('dashboard') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                        Back to Dashboard
                    </a>
                </div>

                <!-- Testing Information -->
                <div class="border rounded-lg p-4 border-blue-200 bg-blue-50">
                    <h4 class="font-medium text-blue-900 mb-2">Testing Two-Factor Authentication</h4>
                    <div class="text-sm text-blue-800 space-y-2">
                        <p>To test your 2FA setup:</p>
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Go to your profile settings and enable 2FA</li>
                            <li>Scan the QR code with your authenticator app</li>
                            <li>Confirm the setup with a verification code</li>
                            <li>Log out and log back in to test the 2FA challenge</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>