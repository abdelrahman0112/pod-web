@extends('layouts.app')

@section('title', 'My Job Applications - People Of Data')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    <!-- Page Header -->
    <x-page-header 
        title="My Job Applications"
        description="Track the status of your job applications" />

    <!-- Status Filter -->
    <div class="mb-6">
        <div class="flex items-center space-x-2 overflow-x-auto pb-2">
            <a href="{{ route('jobs.my-applications') }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
                All Applications
            </a>
            @foreach(\App\JobApplicationStatus::cases() as $status)
                <a href="{{ route('jobs.my-applications', ['status' => $status->value]) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors whitespace-nowrap {{ request('status') === $status->value ? 'bg-indigo-600 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
                    {{ $status->getLabel() }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Applications List -->
    <div class="space-y-4">
        @if($applications->count() > 0)
            @foreach($applications as $application)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <!-- Job Information -->
                        <div class="flex-1 min-w-0">
                            <div class="mb-2">
                                <a href="{{ route('jobs.my-application.show', $application) }}" class="text-lg font-semibold text-slate-800 hover:text-indigo-600 transition-colors">
                                    {{ $application->jobListing->title }}
                                </a>
                            </div>
                            
                            <p class="text-sm text-slate-600 mb-3">
                                {{ $application->jobListing->company_name }}
                            </p>
                            
                            <div class="flex items-center space-x-4 text-xs text-slate-500">
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
                        <div class="flex items-center space-x-3 flex-shrink-0">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    'reviewed' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'accepted' => 'bg-green-100 text-green-700 border-green-200',
                                    'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                ];
                                $statusColor = $statusColors[$application->status->value] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                
                                $statusIcons = [
                                    'pending' => 'ri-time-line',
                                    'reviewed' => 'ri-eye-line',
                                    'accepted' => 'ri-check-line',
                                    'rejected' => 'ri-close-line',
                                ];
                                $statusIcon = $statusIcons[$application->status->value] ?? 'ri-question-line';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium border {{ $statusColor }}">
                                <i class="{{ $statusIcon }} text-sm mr-1.5"></i>
                                {{ $application->status->getLabel() }}
                            </span>
                            
                            <a href="{{ route('jobs.my-application.show', $application) }}" 
                               class="text-sm text-indigo-600 hover:text-indigo-700 font-medium whitespace-nowrap">
                                View Application
                                <i class="ri-arrow-right-line ml-1"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Cover Letter Preview -->
                    @if($application->cover_letter)
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <h4 class="text-sm font-medium text-slate-700 mb-2">Cover Letter:</h4>
                            <p class="text-sm text-slate-600 line-clamp-3">
                                {{ $application->cover_letter }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-8">
                {{ $applications->links() }}
            </div>
        @else
            <!-- No Applications -->
            <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-slate-100">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-file-list-line text-slate-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-800 mb-2">
                    @if(request('status'))
                        No {{ \App\JobApplicationStatus::from(request('status'))->getLabel() }} Applications
                    @else
                        No Applications Found
                    @endif
                </h3>
                <p class="text-slate-600 mb-6">
                    @if(request('status'))
                        You don't have any {{ strtolower(\App\JobApplicationStatus::from(request('status'))->getLabel()) }} applications at the moment.
                    @else
                        You haven't applied to any jobs yet. Start browsing to find opportunities!
                    @endif
                </p>
                <a href="{{ route('jobs.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                    <i class="ri-briefcase-line mr-2"></i>
                    Browse Jobs
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
