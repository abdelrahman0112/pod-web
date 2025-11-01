@props([
    'registrations' => collect()
])

@if($registrations->count() > 0)
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="mb-4">
        <h3 class="font-semibold text-slate-800">My Upcoming Events</h3>
    </div>
    
    <div class="space-y-3">
        @foreach($registrations as $registration)
            <div class="border border-slate-200 rounded-lg p-3">
                <div class="mb-2">
                    <a href="{{ route('events.show', $registration->event) }}" class="font-medium text-slate-800 hover:text-indigo-600 transition-colors text-sm line-clamp-1">
                        {{ $registration->event->title }}
                    </a>
                </div>
                
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-slate-600 truncate">
                        {{ $registration->event->start_date->format('M j, Y') }}
                    </p>
                    <span class="flex-shrink-0 ml-2">
                        @php
                            $statusColors = [
                                'confirmed' => 'bg-green-100 text-green-700 border-green-200',
                                'waitlisted' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                            ];
                            $statusColor = $statusColors[$registration->status->value] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            
                            $statusIcons = [
                                'confirmed' => 'ri-check-line',
                                'waitlisted' => 'ri-time-line',
                                'cancelled' => 'ri-close-line',
                            ];
                            $statusIcon = $statusIcons[$registration->status->value] ?? 'ri-question-line';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border {{ $statusColor }}">
                            <i class="{{ $statusIcon }} text-xs mr-1"></i>
                            {{ $registration->status->getLabel() }}
                        </span>
                    </span>
                </div>
                
                <div class="flex items-center space-x-2 text-xs text-slate-500">
                    @if($registration->event->location)
                        <span class="flex items-center">
                            <i class="ri-map-pin-line mr-1"></i>
                            {{ Str::limit($registration->event->location, 20) }}
                        </span>
                    @endif
                    @if($registration->event->start_date->isToday())
                        <span class="text-indigo-600 font-medium">Today</span>
                    @elseif($registration->event->start_date->isTomorrow())
                        <span class="text-indigo-600 font-medium">Tomorrow</span>
                    @else
                        <span>{{ $registration->event->start_date->diffForHumans() }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@elseif(auth()->check())
<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <h3 class="font-semibold text-slate-800 mb-2">My Upcoming Events</h3>
    <p class="text-sm text-slate-600 mb-4">You're not registered for any upcoming events yet.</p>
    <a href="{{ route('events.index') }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-700 font-medium">
        Browse Events
        <i class="ri-arrow-right-line ml-1"></i>
    </a>
</div>
@endif

