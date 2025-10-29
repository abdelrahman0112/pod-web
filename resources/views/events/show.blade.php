@extends('layouts.app')

@section('title', 'Event Details - ' . $event->title)

@section('content')
    
<div class="flex flex-col lg:flex-row lg:gap-8 gap-6 w-full justify-center">
    <!-- Event Content -->
    <div class="flex-1 w-full lg:max-w-3xl min-w-0 lg:flex-shrink-0">
        <!-- Event Header -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            @if($event->banner_image)
                <div class="h-64 bg-cover bg-center relative" style="background-image: url('{{ Storage::url($event->banner_image) }}')">
                    @if($event->category)
                        <div class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-semibold text-white shadow-sm"
                             style="background-color: {{ $event->category->color }}">
                            {{ $event->category->name }}
                        </div>
                    @endif
                </div>
            @else
                <div class="h-64 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center relative">
                    <div class="text-6xl text-indigo-300">
                        <i class="ri-calendar-event-line"></i>
                    </div>
                    @if($event->category)
                        <div class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-semibold text-white shadow-sm"
                             style="background-color: {{ $event->category->color }}">
                            {{ $event->category->name }}
                        </div>
                    @endif
                </div>
            @endif
            
            <div class="p-8">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $event->title }}</h1>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center space-x-1 text-sm text-slate-600">
                                <i class="ri-calendar-line"></i>
                                <span>{{ $event->start_date->format('M j, Y') }}</span>
                                <span class="text-slate-400">•</span>
                                <span>{{ $event->start_date->format('g:i A') }}</span>
                                @if($event->end_date)
                                    <span class="text-slate-400 mx-1">-</span>
                                    <span>{{ $event->end_date->format('g:i A') }}</span>
                                @endif
                            </div>
                            
                            <div class="flex items-center space-x-1 text-sm text-slate-600">
                                <i class="ri-map-pin-line text-indigo-600"></i>
                                @if($event->format === 'online')
                                    <span class="text-indigo-600">Online</span>
                                @elseif($event->format === 'hybrid')
                                    <span class="text-indigo-600">{{ $event->location }} • Hybrid</span>
                                @else
                                    <span class="text-indigo-600">{{ $event->location }}</span>
                                @endif
                            </div>
                        </div>
                        
                        @if($event->description)
                        <div class="prose prose-slate max-w-none">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                        @endif
                    </div>
                    
                    @if(auth()->check() && (auth()->user()->id === $event->created_by || auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin'))
                        <a href="{{ route('events.edit', $event) }}" class="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-button hover:bg-slate-50 transition-colors !rounded-button whitespace-nowrap">
                            <i class="ri-edit-line mr-2"></i>
                            Edit Event
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Event Agenda Timeline -->
        @if($event->agendaItems->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-8">
            <h2 class="text-xl font-semibold text-slate-800 mb-6">Event Agenda</h2>
            <div class="relative">
                <!-- Timeline line -->
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-slate-200"></div>
                
                <div class="space-y-6">
                    @foreach($event->agendaItems as $index => $item)
                    <div class="relative flex items-start space-x-4">
                        <!-- Timeline dot -->
                        <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-semibold relative z-10">
                            {{ $index + 1 }}
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="bg-slate-50 rounded-lg p-4">
                                <h3 class="font-semibold text-slate-800 mb-2">{{ $item->title }}</h3>
                                @if($item->description)
                                    <p class="text-sm text-slate-600 mb-3">{{ $item->description }}</p>
                                @endif
                                <div class="flex items-center space-x-4 text-xs text-slate-500">
                                    <div class="flex items-center space-x-1">
                                        <i class="ri-time-line"></i>
                                        <span>{{ $item->start_time->format('g:i A') }}</span>
                                        @if($item->end_time)
                                            <span>- {{ $item->end_time->format('g:i A') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Event Organizer -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-8">
            <h2 class="text-xl font-semibold text-slate-800 mb-6">Event Organizer</h2>
            <a href="{{ route('profile.show', $event->creator) }}" class="flex items-center space-x-4 hover:bg-slate-50 p-4 rounded-lg transition-colors">
                <x-avatar 
                    :src="$event->creator->avatar ?? null"
                    :name="$event->creator->name ?? 'User'"
                    size="md"
                    :color="$event->creator->avatar_color ?? null" />
                <div>
                    <h3 class="text-lg font-medium text-slate-800">{{ $event->creator->name }}</h3>
                    <p class="text-slate-600">Event Organizer</p>
                    @if($event->creator->bio)
                        <p class="text-sm text-slate-500 mt-2">{{ Str::limit($event->creator->bio, 150) }}</p>
                    @endif
                </div>
            </a>
        </div>

        <!-- Share Event -->
        <x-share-section title="Share This Event">
            <button class="bg-blue-600 text-white px-4 py-3 rounded-button hover:bg-blue-700 transition-colors !rounded-button">
                <i class="ri-facebook-line mr-2"></i>
                Facebook
            </button>
            <button class="bg-slate-800 text-white px-4 py-3 rounded-button hover:bg-slate-900 transition-colors !rounded-button">
                <i class="ri-twitter-x-line mr-2"></i>
                X
            </button>
            <button class="bg-blue-700 text-white px-4 py-3 rounded-button hover:bg-blue-800 transition-colors !rounded-button">
                <i class="ri-linkedin-line mr-2"></i>
                LinkedIn
            </button>
            <button class="bg-slate-600 text-white px-4 py-3 rounded-button hover:bg-slate-700 transition-colors !rounded-button" onclick="copyEventLink()">
                <i class="ri-links-line mr-2"></i>
                Copy Link
            </button>
        </x-share-section>
    </div>

    <!-- Right Sidebar -->
    <div class="w-full lg:w-80 lg:flex-shrink-0 min-w-0">
        <div class="space-y-6">
            <!-- Registration Widget -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Registration</h3>
                
                <!-- Event Price -->
                <div class="mb-4">
                    <div class="text-2xl font-bold text-indigo-600">Free</div>
                    <div class="text-sm text-slate-500">No registration fee</div>
                </div>
                
                <!-- Available Places -->
                @if($event->max_attendees)
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-600">Available Places</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $event->getAvailableSpots() }}</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        @php
                            $percentage = ($event->confirmedRegistrations->count() / $event->max_attendees) * 100;
                        @endphp
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="text-xs text-slate-500 mt-1">{{ $event->confirmedRegistrations->count() }} of {{ $event->max_attendees }} registered</div>
                </div>
                @else
                <div class="mb-4">
                    <div class="text-sm text-slate-600">Unlimited Capacity</div>
                    <div class="text-sm font-semibold text-slate-800">{{ $event->confirmedRegistrations->count() }} registered</div>
                </div>
                @endif
                
                <!-- Event Countdown -->
                <div class="mb-4">
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                        <div class="text-sm text-indigo-700 font-medium mb-2">Event Date</div>
                        <div class="text-lg font-semibold text-indigo-800 mb-3">{{ $event->start_date->format('M j, Y \a\t g:i A') }}</div>
                        @if($event->start_date > now())
                            <div id="countdown-{{ $event->id }}" class="grid grid-cols-4 gap-2 text-center">
                                <div class="bg-white rounded p-2">
                                    <div class="text-lg font-bold text-indigo-600" id="days-{{ $event->id }}">0</div>
                                    <div class="text-xs text-indigo-500">Days</div>
                                </div>
                                <div class="bg-white rounded p-2">
                                    <div class="text-lg font-bold text-indigo-600" id="hours-{{ $event->id }}">0</div>
                                    <div class="text-xs text-indigo-500">Hours</div>
                                </div>
                                <div class="bg-white rounded p-2">
                                    <div class="text-lg font-bold text-indigo-600" id="minutes-{{ $event->id }}">0</div>
                                    <div class="text-xs text-indigo-500">Minutes</div>
                                </div>
                                <div class="bg-white rounded p-2">
                                    <div class="text-lg font-bold text-indigo-600" id="seconds-{{ $event->id }}">0</div>
                                    <div class="text-xs text-indigo-500">Seconds</div>
                                </div>
                            </div>
                        @else
                            <div class="text-sm text-indigo-600 font-medium">Event has started</div>
                        @endif
                    </div>
                </div>
                
                <!-- Registration Button -->
                <div class="space-y-3">
                    @if(auth()->check())
                        @if($event->canUserRegister(auth()->user()))
                            <form method="POST" action="{{ route('events.register', $event) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-3 rounded-button hover:bg-indigo-700 transition-colors !rounded-button">
                                    <i class="ri-user-add-line mr-2"></i>
                                    Register for Event
                                </button>
                            </form>
                        @elseif($event->registrations()->where('user_id', auth()->user()->id)->exists())
                            <div class="w-full bg-green-50 text-green-700 px-4 py-3 rounded-button text-center">
                                <i class="ri-check-line mr-2"></i>
                                You're Registered
                            </div>
                        @else
                            <div class="w-full bg-slate-50 text-slate-600 px-4 py-3 rounded-button text-center">
                                <i class="ri-lock-line mr-2"></i>
                                Registration Closed
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-indigo-600 text-white px-4 py-3 rounded-button hover:bg-indigo-700 transition-colors !rounded-button text-center">
                            <i class="ri-login-box-line mr-2"></i>
                            Login to Register
                        </a>
                    @endif
                </div>
            </div>

            <!-- Event Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Event Statistics</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Total Registrations</span>
                        <span class="text-sm font-semibold text-indigo-600">{{ $event->registrations->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Confirmed Attendees</span>
                        <span class="text-sm font-semibold text-green-600">{{ $event->confirmedRegistrations->count() }}</span>
                    </div>
                    @if($event->waitlist_enabled)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Waitlisted</span>
                            <span class="text-sm font-semibold text-orange-600">{{ $event->waitlistedRegistrations->count() }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Events Section -->
@if($event->category && $event->category->events()->where('id', '!=', $event->id)->where('is_active', true)->count() > 0)
<div class="mt-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">More {{ $event->category->name }} Events</h2>
        <a href="{{ route('events.index', ['category' => $event->category->id]) }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
            View All
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($event->category->events()->where('id', '!=', $event->id)->where('is_active', true)->take(3)->get() as $relatedEvent)
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow">
            <a href="{{ route('events.show', $relatedEvent) }}" class="block h-48 bg-gradient-to-br from-indigo-50 to-purple-50 relative overflow-hidden group">
                @if($relatedEvent->banner_image)
                    <img src="{{ Storage::url($relatedEvent->banner_image) }}" alt="{{ $relatedEvent->title }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300" />
                @else
                    <div class="w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                        <div class="text-6xl text-indigo-300">
                            <i class="ri-calendar-event-line"></i>
                        </div>
                    </div>
                @endif
                <div class="absolute top-4 left-4 bg-white bg-opacity-90 rounded-lg px-4 py-3 shadow-sm">
                    <div class="text-center">
                        <div class="text-sm text-indigo-600 font-semibold uppercase tracking-wide">{{ $relatedEvent->start_date->format('M') }}</div>
                        <div class="text-xl font-bold text-slate-800">{{ $relatedEvent->start_date->format('d') }}</div>
                        <div class="text-xs text-slate-500">{{ $relatedEvent->start_date->format('Y') }}</div>
                    </div>
                </div>
                @if($relatedEvent->category)
                    <div class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-semibold text-white shadow-sm"
                         style="background-color: {{ $relatedEvent->category->color }}">
                        {{ $relatedEvent->category->name }}
                    </div>
                @endif
            </a>
            <div class="p-6">
                <a href="{{ route('events.show', $relatedEvent) }}" class="block">
                    <h3 class="text-xl font-semibold text-slate-800 mb-3 hover:text-indigo-600 transition-colors">{{ $relatedEvent->title }}</h3>
                </a>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center space-x-1 text-sm text-slate-600">
                        <div class="w-4 h-4 flex items-center justify-center">
                            <i class="ri-time-line"></i>
                        </div>
                        <span>{{ $relatedEvent->start_date->format('M j, Y') }}</span>
                        <span class="text-slate-400">•</span>
                        <span>{{ $relatedEvent->start_date->format('g:i A') }}</span>
                        @if($relatedEvent->end_date)
                            <span class="text-slate-400 mx-1">-</span>
                            <span>{{ $relatedEvent->end_date->format('g:i A') }}</span>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-1 text-sm text-slate-600">
                        <div class="w-4 h-4 flex items-center justify-center">
                            <i class="ri-map-pin-line text-indigo-600"></i>
                        </div>
                        @if($relatedEvent->format === 'online')
                            <span class="text-indigo-600">Online</span>
                        @elseif($relatedEvent->format === 'hybrid')
                            <span class="text-indigo-600">{{ $relatedEvent->location }} • Hybrid</span>
                        @else
                            <span class="text-indigo-600">{{ $relatedEvent->location }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-slate-500">
                            @php
                                $status = $relatedEvent->getStatus();
                                $statusColors = [
                                    'upcoming' => 'text-green-600',
                                    'ongoing' => 'text-blue-600',
                                    'ended' => 'text-red-600',
                                    'registration_closed' => 'text-orange-600',
                                    'inactive' => 'text-gray-600'
                                ];
                                $statusLabels = [
                                    'upcoming' => 'Upcoming',
                                    'ongoing' => 'Live Now',
                                    'ended' => 'Ended',
                                    'registration_closed' => 'Registration Closed',
                                    'inactive' => 'Inactive'
                                ];
                            @endphp
                            <span class="{{ $statusColors[$status] ?? 'text-gray-600' }} font-medium">
                                {{ $statusLabels[$status] ?? ucfirst($status) }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<script>
function copyEventLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        // You could add a toast notification here
        alert('Event link copied to clipboard!');
    });
}

// Countdown functionality
function updateCountdown(eventId, targetDate) {
    const now = new Date().getTime();
    const distance = targetDate - now;

    if (distance < 0) {
        document.getElementById(`countdown-${eventId}`).innerHTML = '<div class="text-sm text-indigo-600 font-medium">Event has started</div>';
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById(`days-${eventId}`).innerHTML = days;
    document.getElementById(`hours-${eventId}`).innerHTML = hours;
    document.getElementById(`minutes-${eventId}`).innerHTML = minutes;
    document.getElementById(`seconds-${eventId}`).innerHTML = seconds;
}

// Initialize countdown
document.addEventListener('DOMContentLoaded', function() {
    const eventDate = new Date('{{ $event->start_date->toISOString() }}').getTime();
    updateCountdown({{ $event->id }}, eventDate);
    
    // Update every second
    setInterval(function() {
        updateCountdown({{ $event->id }}, eventDate);
    }, 1000);
});
</script>
@endsection