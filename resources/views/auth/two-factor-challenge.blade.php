@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-900">Two-Factor Authentication</h2>
            <p class="text-sm text-gray-600 mt-2" id="instruction-text">Please confirm access to your account by entering the authentication code provided by your authenticator application.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded p-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('two-factor.login') }}">
            @csrf

            <!-- Authentication Code Form -->
            <div id="code-form">
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Authentication Code</label>
                    <input id="code" type="text" name="code" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                           placeholder="Enter 6-digit code" maxlength="6" autofocus autocomplete="one-time-code">
                </div>

                <div class="mb-6">
                    <button type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                        Verify
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-2">Don't have your phone?</p>
                    <button type="button" onclick="toggleRecoveryForm()" 
                            class="text-green-600 hover:text-green-800 text-sm font-medium">
                        Use a recovery code
                    </button>
                </div>
            </div>

            <!-- Recovery Code Form -->
            <div id="recovery-form" class="hidden">
                <div class="mb-4">
                    <label for="recovery_code" class="block text-sm font-medium text-gray-700 mb-2">Recovery Code</label>
                    <input id="recovery_code" type="text" name="recovery_code" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                           placeholder="Enter recovery code">
                </div>

                <div class="mb-6">
                    <button type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                        Use Recovery Code
                    </button>
                </div>

                <div class="text-center">
                    <button type="button" onclick="toggleRecoveryForm()" 
                            class="text-green-600 hover:text-green-800 text-sm font-medium">
                        Back to authentication code
                    </button>
                </div>
            </div>

        </form>

        <script>
            function toggleRecoveryForm() {
                const codeForm = document.getElementById('code-form');
                const recoveryForm = document.getElementById('recovery-form');
                const instructionText = document.getElementById('instruction-text');
                
                if (codeForm.classList.contains('hidden')) {
                    // Show code form, hide recovery form
                    codeForm.classList.remove('hidden');
                    recoveryForm.classList.add('hidden');
                    instructionText.textContent = 'Please confirm access to your account by entering the authentication code provided by your authenticator application.';
                    document.getElementById('code').focus();
                } else {
                    // Show recovery form, hide code form
                    codeForm.classList.add('hidden');
                    recoveryForm.classList.remove('hidden');
                    instructionText.textContent = 'Please confirm access to your account by entering one of your emergency recovery codes.';
                    document.getElementById('recovery_code').focus();
                }
            }

            // Auto-format authentication code input
            document.getElementById('code').addEventListener('input', function(e) {
                this.value = this.value.replace(/\D/g, '');
            });
        </script>
    </div>
</div>
@endsection
