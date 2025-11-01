@extends('layouts.guest')

@section('title', 'Reset Password')
@section('description', 'Reset your People Of Data account password')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-indigo-50 to-purple-50">
    <div class="max-w-md w-full">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="mx-auto w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mb-4">
                <i class="ri-key-line text-4xl text-indigo-600"></i>
            </div>
            <h2 class="text-3xl font-bold text-slate-800 mb-2">
                Reset Password
            </h2>
            <p class="text-slate-600">
                Enter your new password below
            </p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
            @if (session('status'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="ri-checkbox-circle-line text-green-600 text-xl mr-3"></i>
                        <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="ri-error-warning-line text-red-600 text-xl mr-3 mt-0.5"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-800 mb-2">Please correct the following errors:</p>
                            <ul class="text-xs text-red-700 list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form class="space-y-6" method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Token -->
                <input type="hidden" name="token" value="{{ $request->token }}">
                <input type="hidden" name="email" value="{{ $request->email }}">

                <!-- Email Display -->
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                    <p class="text-xs text-indigo-600 font-medium mb-1">Resetting password for:</p>
                    <p class="text-sm text-indigo-900 font-semibold">{{ $request->email }}</p>
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                        New Password
                    </label>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                               placeholder="Enter new password"
                               class="w-full px-4 py-3 pr-12 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('password') border-red-300 @enderror">
                        <button type="button" onclick="togglePasswordVisibility('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            <i id="password-icon" class="ri-eye-line text-xl"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-slate-500">
                        <i class="ri-information-line mr-1"></i>
                        Must be at least 8 characters with uppercase, lowercase, and numbers
                    </p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">
                        Confirm New Password
                    </label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                               placeholder="Confirm new password"
                               class="w-full px-4 py-3 pr-12 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('password_confirmation') border-red-300 @enderror">
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            <i id="password_confirmation-icon" class="ri-eye-line text-xl"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-all font-medium shadow-md hover:shadow-lg">
                        <i class="ri-check-line mr-2"></i>
                        Reset Password
                    </button>
                </div>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Sign In
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '-icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('ri-eye-line');
            icon.classList.add('ri-eye-off-line');
        } else {
            input.type = 'password';
            icon.classList.remove('ri-eye-off-line');
            icon.classList.add('ri-eye-line');
        }
    }
</script>
@endsection

