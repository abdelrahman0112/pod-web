@extends('layouts.app')

@section('title', 'Request Business Account - People Of Data')

@section('content')

<div class="max-w-3xl mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-slate-800 mb-2">Request Business Account</h1>
        <p class="text-slate-600">
            Upgrade your account to access business features like posting jobs, creating events, and hosting hackathons
        </p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="ri-check-circle-line text-green-600 text-xl mr-3"></i>
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="ri-error-warning-line text-red-600 text-xl mr-3"></i>
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Existing Request Notice -->
    @if(isset($existingRequest) && $existingRequest)
        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="ri-time-line text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">Request Already Submitted</h3>
                    <p class="text-yellow-700 mb-3">
                        You have a pending business account request that was submitted on {{ $existingRequest->created_at->format('M d, Y') }}. 
                        Our team is currently reviewing your request.
                    </p>
                    <div class="text-sm text-yellow-600">
                        <p><strong>Company:</strong> {{ $existingRequest->company_name }}</p>
                        <p><strong>Business Field:</strong> {{ $existingRequest->business_field }}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Request Form -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
            <form method="POST" action="{{ route('client.request.store') }}" class="space-y-6">
                @csrf

                <!-- Company Information Section -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Company Information</h3>
                        
                        <div class="space-y-5">
                            <!-- Company Name -->
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-slate-700 mb-2">
                                    Company Name <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="company_name" 
                                    id="company_name" 
                                    value="{{ old('company_name') }}" 
                                    required
                                    placeholder="Enter your company name"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('company_name') border-red-300 @enderror">
                                @error('company_name')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Business Field -->
                            <div>
                                <label for="business_field" class="block text-sm font-medium text-slate-700 mb-2">
                                    Business Field/Industry <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="business_field" 
                                    id="business_field" 
                                    value="{{ old('business_field') }}" 
                                    required
                                    placeholder="e.g. Technology, Finance, Healthcare, Education"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('business_field') border-red-300 @enderror">
                                @error('business_field')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-slate-500">What industry or field does your business operate in?</p>
                            </div>

                            <!-- Company Website -->
                            <div>
                                <label for="company_website" class="block text-sm font-medium text-slate-700 mb-2">
                                    Company Website
                                </label>
                                <input 
                                    type="url" 
                                    name="company_website" 
                                    id="company_website" 
                                    value="{{ old('company_website') }}" 
                                    placeholder="https://www.example.com"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('company_website') border-red-300 @enderror">
                                @error('company_website')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- LinkedIn Company Page -->
                            <div>
                                <label for="linkedin_company_page" class="block text-sm font-medium text-slate-700 mb-2">
                                    LinkedIn Company Page
                                </label>
                                <input 
                                    type="url" 
                                    name="linkedin_company_page" 
                                    id="linkedin_company_page" 
                                    value="{{ old('linkedin_company_page') }}" 
                                    placeholder="https://www.linkedin.com/company/example"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('linkedin_company_page') border-red-300 @enderror">
                                @error('linkedin_company_page')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="border-t border-slate-200 pt-6">
                        <div>
                            <label for="additional_info" class="block text-sm font-medium text-slate-700 mb-2">
                                Additional Information
                            </label>
                            <textarea 
                                name="additional_info" 
                                id="additional_info" 
                                rows="5"
                                placeholder="Tell us more about your business, why you need a business account, what you plan to use it for (hiring, events, hackathons, etc.)..."
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-vertical @error('additional_info') border-red-300 @enderror">{{ old('additional_info') }}</textarea>
                            @error('additional_info')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1.5 text-xs text-slate-500">Help us understand your needs better by providing any additional context.</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('home') }}" 
                       class="px-6 py-2.5 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-semibold flex items-center space-x-2">
                        <span>Submit Request</span>
                        <i class="ri-arrow-right-line"></i>
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>

@endsection

