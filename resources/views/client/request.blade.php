@extends('layouts.app')

@section('title', 'Request Client Account')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Request Client Account
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-6 py-8">
                <!-- Introduction -->
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Upgrade to Client Account</h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Client Account Benefits
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Post job listings to reach qualified candidates</li>
                                        <li>Access advanced candidate filtering and search</li>
                                        <li>Host events and workshops for the community</li>
                                        <li>Direct messaging with potential hires</li>
                                        <li>Priority support and dedicated account manager</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('client.request') }}" class="space-y-6">
                    @csrf

                    <!-- Company Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Company Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                                <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}" required
                                       class="input-field @error('company_name') border-red-300 @enderror">
                                @error('company_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="industry" class="block text-sm font-medium text-gray-700 mb-2">Industry *</label>
                                <select name="industry" id="industry" required
                                        class="input-field @error('industry') border-red-300 @enderror">
                                    <option value="">Select Industry</option>
                                    <option value="technology" {{ old('industry') == 'technology' ? 'selected' : '' }}>Technology</option>
                                    <option value="finance" {{ old('industry') == 'finance' ? 'selected' : '' }}>Finance</option>
                                    <option value="healthcare" {{ old('industry') == 'healthcare' ? 'selected' : '' }}>Healthcare</option>
                                    <option value="consulting" {{ old('industry') == 'consulting' ? 'selected' : '' }}>Consulting</option>
                                    <option value="education" {{ old('industry') == 'education' ? 'selected' : '' }}>Education</option>
                                    <option value="other" {{ old('industry') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('industry')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="company_size" class="block text-sm font-medium text-gray-700 mb-2">Company Size</label>
                                <select name="company_size" id="company_size"
                                        class="input-field @error('company_size') border-red-300 @enderror">
                                    <option value="">Select Size</option>
                                    <option value="1-10" {{ old('company_size') == '1-10' ? 'selected' : '' }}>1-10 employees</option>
                                    <option value="11-50" {{ old('company_size') == '11-50' ? 'selected' : '' }}>11-50 employees</option>
                                    <option value="51-200" {{ old('company_size') == '51-200' ? 'selected' : '' }}>51-200 employees</option>
                                    <option value="201-1000" {{ old('company_size') == '201-1000' ? 'selected' : '' }}>201-1000 employees</option>
                                    <option value="1000+" {{ old('company_size') == '1000+' ? 'selected' : '' }}>1000+ employees</option>
                                </select>
                                @error('company_size')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Company Website</label>
                                <input type="url" name="website" id="website" value="{{ old('website') }}"
                                       placeholder="https://www.yourcompany.com"
                                       class="input-field @error('website') border-red-300 @enderror">
                                @error('website')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-2">Contact Person *</label>
                                <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name', auth()->user()->name) }}" required
                                       class="input-field @error('contact_name') border-red-300 @enderror">
                                @error('contact_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Your Position *</label>
                                <input type="text" name="position" id="position" value="{{ old('position') }}" required
                                       placeholder="e.g., HR Manager, CTO, Founder"
                                       class="input-field @error('position') border-red-300 @enderror">
                                @error('position')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Usage Intent -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Usage Intent</h4>
                        <div>
                            <label for="intent" class="block text-sm font-medium text-gray-700 mb-2">How do you plan to use the client account? *</label>
                            <textarea name="intent" id="intent" rows="4" required
                                      placeholder="Please describe your hiring needs, types of positions you'll be posting, or events you plan to host..."
                                      class="input-field @error('intent') border-red-300 @enderror">{{ old('intent') }}</textarea>
                            @error('intent')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('home') }}" class="btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
