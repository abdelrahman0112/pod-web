@extends('layouts.app')

@section('title', 'Page Title - People Of Data')@section('title', 'Page Title - People Of Data')

@section('content')@section('content')    :title="'Notifications - People Of Data'"
    :breadcrumbs="[
        ['title' => 'Notifications', 'url' => '']
    ]">

    <div class="max-w-4xl mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 mb-2">Notifications</h1>
                <p class="text-slate-600">Stay updated with the latest activity and announcements</p>
            </div>
            
            @if($notifications->count() > 0)
                <div class="flex items-center space-x-3">
                    <x-forms.button 
                        href="{{ route('notifications.mark-all-as-read') }}"
                        method="PATCH"
                        variant="outline"
                        icon="ri-check-double-line"
                        size="sm">
                        Mark All Read
                    </x-forms.button>
                    <x-forms.button 
                        variant="ghost"
                        icon="ri-settings-line"
                        size="sm">
                        Settings
                    </x-forms.button>
                </div>
            @endif
        </div>

        <!-- Notification Filters -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <x-forms.button 
                    href="{{ route('notifications.index') }}"
                    variant="{{ request('filter') ? 'outline' : 'primary' }}"
                    size="sm">
                    All
                </x-forms.button>
                <x-forms.button 
                    href="{{ route('notifications.index', ['filter' => 'unread']) }}"
                    variant="{{ request('filter') === 'unread' ? 'primary' : 'outline' }}"
                    size="sm">
                    Unread
                </x-forms.button>
                <x-forms.button 
                    href="{{ route('notifications.index', ['filter' => 'jobs']) }}"
                    variant="{{ request('filter') === 'jobs' ? 'primary' : 'outline' }}"
                    size="sm">
                    Jobs
                </x-forms.button>
                <x-forms.button 
                    href="{{ route('notifications.index', ['filter' => 'events']) }}"
                    variant="{{ request('filter') === 'events' ? 'primary' : 'outline' }}"
                    size="sm">
                    Events
                </x-forms.button>
                <x-forms.button 
                    href="{{ route('notifications.index', ['filter' => 'messages']) }}"
                    variant="{{ request('filter') === 'messages' ? 'primary' : 'outline' }}"
                    size="sm">
                    Messages
                </x-forms.button>
            </div>
        </div>

        <!-- Notifications List -->
        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 {{ $notification->read_at ? '' : 'ring-2 ring-indigo-100' }}">
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                <!-- Notification Icon -->
                                <div class="flex-shrink-0">
                                    @php
                                        $iconClass = 'w-10 h-10 rounded-full flex items-center justify-center';
                                        $icon = 'ri-notification-line';
                                        $bgColor = 'bg-slate-100 text-slate-600';
                                        
                                        switch($notification->type) {
                                            case 'job_posted':
                                                $icon = 'ri-briefcase-line';
                                                $bgColor = 'bg-blue-100 text-blue-600';
                                                break;
                                            case 'event_reminder':
                                                $icon = 'ri-calendar-line';
                                                $bgColor = 'bg-green-100 text-green-600';
                                                break;
                                            case 'message_received':
                                                $icon = 'ri-message-3-line';
                                                $bgColor = 'bg-purple-100 text-purple-600';
                                                break;
                                            case 'application_status':
                                                $icon = 'ri-file-text-line';
                                                $bgColor = 'bg-orange-100 text-orange-600';
                                                break;
                                            default:
                                                $icon = 'ri-notification-line';
                                                $bgColor = 'bg-slate-100 text-slate-600';
                                        }
                                    @endphp
                                    <div class="{{ $iconClass }} {{ $bgColor }}">
                                        <i class="{{ $icon }}"></i>
                                    </div>
                                </div>

                                <!-- Notification Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-slate-800 mb-1">
                                                {{ $notification->data['title'] ?? 'Notification' }}
                                            </h3>
                                            <p class="text-slate-600 mb-3">
                                                {{ $notification->data['message'] ?? 'You have a new notification.' }}
                                            </p>
                                            
                                            <!-- Notification Meta -->
                                            <div class="flex items-center space-x-4 text-sm text-slate-500">
                                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                                                @if(!$notification->read_at)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                                        New
                                                    </span>
                                                @endif
                                                @if(isset($notification->data['category']))
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                                        {{ ucfirst($notification->data['category']) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex items-center space-x-2 ml-4">
                                            @if(isset($notification->data['action_url']))
                                                <x-forms.button 
                                                    href="{{ $notification->data['action_url'] }}"
                                                    variant="primary"
                                                    size="sm">
                                                    {{ $notification->data['action_text'] ?? 'View' }}
                                                </x-forms.button>
                                            @endif
                                            
                                            @if(!$notification->read_at)
                                                <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <x-forms.button 
                                                        type="submit"
                                                        variant="ghost"
                                                        icon="ri-check-line"
                                                        size="sm">
                                                        Mark Read
                                                    </x-forms.button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <x-forms.button 
                                                    type="submit"
                                                    variant="ghost"
                                                    icon="ri-delete-bin-line"
                                                    size="sm"
                                                    onclick="return confirm('Are you sure you want to delete this notification?')">
                                                    Delete
                                                </x-forms.button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ri-notification-off-line text-slate-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-slate-800 mb-2">No notifications yet</h3>
                <p class="text-slate-600 mb-6 max-w-md mx-auto">
                    @if(request('filter'))
                        No {{ request('filter') }} notifications found. Try changing your filter or check back later.
                    @else
                        You're all caught up! When you receive notifications, they'll appear here.
                    @endif
                </p>
                <div class="flex justify-center space-x-4">
                    <x-forms.button 
                        href="{{ route('home') }}"
                        variant="primary"
                        icon="ri-home-line">
                        Go to Dashboard
                    </x-forms.button>
                    <x-forms.button 
                        href="{{ route('events.index') }}"
                        variant="outline"
                        icon="ri-calendar-line">
                        Browse Events
                    </x-forms.button>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-refresh notifications every 30 seconds
            setInterval(function() {
                // Check for new notifications via AJAX
                fetch('/api/notifications/count')
                    .then(response => response.json())
                    .then(data => {
                        // Update notification count in header if changed
                        const notificationBadge = document.querySelector('.notification-count');
                        if (notificationBadge && data.unread_count > 0) {
                            notificationBadge.textContent = data.unread_count;
                            notificationBadge.style.display = 'block';
                        }
                    })
                    .catch(error => console.log('Error checking notifications:', error));
            }, 30000);

            // Mark notification as read when clicked
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    const notificationId = this.dataset.notificationId;
                    if (this.classList.contains('unread')) {
                        fetch(`/notifications/${notificationId}/mark-as-read`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            }
                        });
                        this.classList.remove('unread');
                    }
                });
            });
        });
    </script>
    @endpush

@endsection