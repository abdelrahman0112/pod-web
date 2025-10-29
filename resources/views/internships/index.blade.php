@extends('layouts.app')

@section('title', 'Internships')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Internships</h1>
            <p class="text-slate-600">Find your next internship opportunity</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('internships.apply') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-button hover:bg-indigo-700 transition-colors flex items-center space-x-2 !rounded-button whitespace-nowrap">
                <div class="w-5 h-5 flex items-center justify-center">
                    <i class="ri-add-line"></i>
                </div>
                <span>Submit General Application</span>
            </a>
        </div>
    </div>

    <!-- Tabs -->
    @php
        $tabs = [
            'available-internships' => ['label' => 'Available Internships'],
            'my-applications' => ['label' => 'My Applications'],
        ];
    @endphp
    <x-tabs :tabs="$tabs" />

    <div class="w-full" x-data="{ activeTab: 'available-internships' }" @tab-switched.window="activeTab = $event.detail.tab">
        <!-- Tab Content -->
        <div x-show="activeTab === 'available-internships'">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($internships as $internship)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold">{{ $internship->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $internship->category->name }}</p>
                        <p class="mt-2">{{ Str::limit($internship->description, 150) }}</p>
                        <div class="mt-4">
                            <a href="{{ route('internships.apply', ['internship_id' => $internship->id]) }}" class="text-indigo-600 hover:text-indigo-800">Apply Now</a>
                        </div>
                    </div>
                @empty
                    <p>No internships available at the moment.</p>
                @endforelse
            </div>
        </div>

        <div x-show="activeTab === 'my-applications'" style="display: none;">
            <div class="space-y-4">
                @forelse($myApplications as $application)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold">{{ $application->internship ? $application->internship->title : 'General Application' }}</h3>
                        <p class="text-sm text-gray-500">Applied on {{ $application->created_at->format('M d, Y') }}</p>
                        <p class="mt-2">Status: <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded-full">{{ ucfirst($application->status) }}</span></p>
                    </div>
                @empty
                    <p>You have not submitted any applications yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
