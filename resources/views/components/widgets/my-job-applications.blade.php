@props([
    'applications' => collect()
])

@if($applications->count() > 0)
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-slate-800">My Applications</h3>
        @if(auth()->check())
            <a href="{{ route('jobs.my-applications') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                View All
            </a>
        @endif
    </div>
    
    <div class="space-y-3">
        @foreach($applications as $application)
            <div class="border border-slate-200 rounded-lg p-3">
                <div class="mb-2">
                    <a href="{{ route('jobs.show', $application->jobListing) }}" class="font-medium text-slate-800 hover:text-indigo-600 transition-colors text-sm line-clamp-1">
                        {{ $application->jobListing->title }}
                    </a>
                </div>
                
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-slate-600 truncate">
                        {{ $application->jobListing->company_name }}
                    </p>
                    <span class="flex-shrink-0 ml-2">
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
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border {{ $statusColor }}">
                            <i class="{{ $statusIcon }} text-xs mr-1"></i>
                            {{ $application->status->getLabel() }}
                        </span>
                    </span>
                </div>
                
                <p class="text-xs text-slate-500">
                    Applied {{ $application->created_at->diffForHumans() }}
                </p>
            </div>
        @endforeach
    </div>
</div>
@elseif(auth()->check())
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <h3 class="font-semibold text-slate-800 mb-2">My Applications</h3>
    <p class="text-sm text-slate-500 text-center py-4">You haven't applied to any jobs yet.</p>
</div>
@endif
