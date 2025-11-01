@extends('layouts.app')

@section('title', 'My Application - ' . $application->jobListing->title . ' - People Of Data')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    <!-- Back to Applications Link -->
    <div class="mb-6">
        <a href="{{ route('jobs.my-applications') }}" 
           class="flex items-center space-x-2 text-slate-600 hover:text-indigo-600 transition-colors">
            <i class="ri-arrow-left-line"></i>
            <span class="text-sm font-medium">Back to My Applications</span>
        </a>
    </div>

    <div class="flex flex-col lg:flex-row lg:gap-8 gap-6">
        <!-- Application Content -->
        <div class="flex-1 w-full lg:max-w-3xl min-w-0 lg:flex-shrink-0">
            <!-- Application Header -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
                <div class="p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $application->jobListing->title }}</h1>
                            <p class="text-lg text-slate-600 mb-4">{{ $application->jobListing->company_name }}</p>
                            
                            <div class="flex items-center space-x-4 text-sm text-slate-600">
                                <span class="flex items-center">
                                    <i class="ri-time-line mr-1"></i>
                                    Applied {{ $application->created_at->diffForHumans() }}
                                </span>
                                @if($application->status_updated_at)
                                    <span class="flex items-center">
                                        <i class="ri-refresh-line mr-1"></i>
                                        Updated {{ $application->status_updated_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Application Status -->
                        <div class="flex-shrink-0">
                            @php
                                $statusValue = $application->status->value;
                                $statusLabel = $application->status->getLabel();
                                $statusClass = match($statusValue) {
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'reviewed' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'accepted' => 'bg-green-100 text-green-800 border-green-200',
                                    'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200',
                                };
                                
                                $statusIcons = [
                                    'pending' => 'ri-time-line',
                                    'reviewed' => 'ri-eye-line',
                                    'accepted' => 'ri-check-line',
                                    'rejected' => 'ri-close-line',
                                ];
                                $statusIcon = $statusIcons[$statusValue] ?? 'ri-question-line';
                            @endphp
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border {{ $statusClass }}">
                                <i class="{{ $statusIcon }} text-base mr-2"></i>
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>

                    <!-- Application Details -->
                    <div class="space-y-6">
                        <!-- Cover Letter -->
                        <div>
                            <h4 class="font-semibold text-slate-800 mb-3">Cover Letter</h4>
                            <div class="bg-slate-50 rounded-lg p-6">
                                <p class="text-slate-700 whitespace-pre-wrap">{{ $application->cover_letter }}</p>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        @if($application->additional_info)
                            <div>
                                <h4 class="font-semibold text-slate-800 mb-3">Additional Information</h4>
                                <div class="bg-slate-50 rounded-lg p-6">
                                    <p class="text-slate-700 whitespace-pre-wrap">{{ $application->additional_info }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Application Metadata -->
                        <div class="bg-slate-50 rounded-lg p-6">
                            <h4 class="font-semibold text-slate-800 mb-4">Application Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-slate-600">Application ID:</span>
                                    <span class="text-sm text-slate-800 ml-2">#{{ $application->id }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-600">Submitted:</span>
                                    <span class="text-sm text-slate-800 ml-2">{{ $application->created_at->format('M j, Y g:i A') }}</span>
                                </div>
                                @if($application->status_updated_at)
                                    <div>
                                        <span class="text-sm font-medium text-slate-600">Status Updated:</span>
                                        <span class="text-sm text-slate-800 ml-2">{{ $application->status_updated_at->format('M j, Y g:i A') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="w-full lg:w-80 lg:flex-shrink-0 min-w-0">
            <div class="space-y-6">
                <!-- Job Information -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Job Information</h3>
                    <div class="space-y-4">
                        <div>
                            <a href="{{ route('jobs.show', $application->jobListing) }}" 
                               class="text-lg font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                                {{ $application->jobListing->title }}
                            </a>
                            <p class="text-sm text-slate-600 mt-1">{{ $application->jobListing->company_name }}</p>
                        </div>
                        
                        @if($application->jobListing->formatted_salary)
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                <span class="text-sm text-slate-600">Salary Range</span>
                                <span class="text-sm font-semibold text-green-600">
                                    EGP {{ number_format($application->jobListing->salary_min ?? 0) }}@if($application->jobListing->salary_max) - {{ number_format($application->jobListing->salary_max) }}@endif
                                </span>
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <span class="text-sm text-slate-600">Experience Level</span>
                            <span class="text-sm font-semibold text-slate-800">{{ $application->jobListing->experience_level->getLabel() }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Location Type</span>
                            <span class="text-sm font-semibold text-purple-600">{{ $application->jobListing->location_type->getLabel() }}</span>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-100">
                            <a href="{{ route('jobs.show', $application->jobListing) }}" 
                               class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-center block text-sm font-medium">
                                View Job Posting
                                <i class="ri-arrow-right-line ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Application Status Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Application Status</h3>
                    <div class="space-y-3">
                        @php
                            $statusDescriptions = [
                                'pending' => 'Your application has been received and is awaiting review by the hiring team.',
                                'reviewed' => 'Your application has been reviewed and is under consideration.',
                                'accepted' => 'Congratulations! Your application has been accepted.',
                                'rejected' => 'We appreciate your interest, but we have decided to move forward with other candidates.',
                            ];
                            $statusDescription = $statusDescriptions[$statusValue] ?? 'Your application status has been updated.';
                        @endphp
                        <p class="text-sm text-slate-600">{{ $statusDescription }}</p>
                        
                        @if($application->status_updated_at)
                            <div class="text-xs text-slate-500 pt-2 border-t border-slate-100">
                                Last updated: {{ $application->status_updated_at->format('M j, Y') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
