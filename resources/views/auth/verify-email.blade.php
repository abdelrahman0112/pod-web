@extends('layouts.guest')

@section('title', 'Verify Email')
@section('description', 'Verify your email address to access all People Of Data features')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Verify your email address
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Before getting started, could you verify your email address by clicking on the link we just emailed to you?
            </p>
        </div>

        <!-- Design content will be pasted here -->
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="space-y-6">
                @if (session('status') == 'verification-link-sent')
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        A new verification link has been sent to the email address you provided during registration.
                    </div>
                @endif

                <div class="text-center">
                    <div class="text-gray-600 mb-4">
                        <p>If you didn't receive the email, we will gladly send you another.</p>
                    </div>

                    <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                        @csrf
                        <button type="submit" class="w-full btn-primary">
                            Resend Verification Email
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Email verification notice -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        Email verification required
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>You need to verify your email address to apply for jobs, events, hackathons, and internships.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
