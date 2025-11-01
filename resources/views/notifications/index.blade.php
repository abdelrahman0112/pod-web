@extends('layouts.app')

@section('title', 'Notifications - People Of Data')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1 sm:mb-2">Notifications</h1>
                <p class="text-sm sm:text-base text-slate-600">Stay updated with the latest activity and announcements</p>
            </div>
            
            @if($notifications->count() > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                    @csrf
                    <x-forms.button 
                        type="submit"
                        variant="outline"
                        icon="ri-check-double-line"
                        size="sm">
                        Mark All Read
                    </x-forms.button>
                </form>
            @endif
        </div>

        <!-- Notifications List -->
        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <a href="{{ $notification->data['click_action'] ?? '#' }}" 
                       data-notification-id="{{ $notification->id }}"
                       class="notification-item block bg-white rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-all {{ $notification->read_at ? '' : 'ring-2 ring-indigo-100' }}"
                       @if(!$notification->read_at) onclick="markAsRead('{{ $notification->id }}', event)" @endif>
                        <div class="p-4 sm:p-6">
                            <div class="flex items-start space-x-4">
                                <!-- User Avatar with Overlay Icon -->
                                <div class="flex-shrink-0 relative">
                                    <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white">
                                        <!-- Avatar Image or Initials -->
                                        @if(isset($notification->data['actor_avatar']) && $notification->data['actor_avatar'] && $notification->data['actor_avatar'] !== 'null' && $notification->data['actor_avatar'] !== '')
                                            <img src="{{ $notification->data['actor_avatar'] }}" 
                                                 alt="{{ $notification->data['actor_name'] ?? 'User' }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            @php
                                                $actorName = $notification->data['actor_name'] ?? 'User';
                                                $initials = strlen($actorName) >= 2 ? strtoupper(substr($actorName, 0, 2)) : strtoupper($actorName);
                                            @endphp
                                            <div class="w-full h-full flex items-center justify-center font-semibold text-sm {{ $notification->data['actor_avatar_color'] ?? 'bg-slate-100 text-slate-600' }}">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Action Icon Overlay -->
                                    @if(isset($notification->data['action_icon']))
                                        <div class="absolute -bottom-1.5 -right-1.5 w-6 h-6 rounded-full flex items-center justify-center border-2 border-white {{ $notification->data['overlay_background_color'] ?? 'bg-indigo-500' }}">
                                            <i class="{{ $notification->data['action_icon'] }} {{ $notification->data['icon_color'] ?? 'text-white' }} text-xs"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Notification Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-slate-900 font-medium mb-1">
                                        {{ $notification->data['body'] ?? $notification->data['message'] ?? 'You have a new notification.' }}
                                    </p>
                                    
                                    <!-- Notification Meta -->
                                    <div class="flex items-center space-x-4 text-xs text-slate-500">
                                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                                        @if(!$notification->read_at)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                                New
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Unread Indicator -->
                                @if(!$notification->read_at)
                                    <div class="flex-shrink-0 ml-2">
                                        <div class="w-2 h-2 bg-indigo-600 rounded-full"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
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
        function markAsRead(notificationId, event) {
            event.preventDefault();
            
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            }).then(() => {
                // Update UI
                const item = event.target.closest('.notification-item');
                if (item) {
                    item.classList.remove('ring-2', 'ring-indigo-100');
                    // Remove "New" badge
                    const newBadge = item.querySelector('span.inline-flex');
                    if (newBadge) newBadge.style.display = 'none';
                    // Remove dot indicator
                    const dot = item.querySelector('.w-2.h-2.bg-indigo-600');
                    if (dot) dot.remove();
                }
            }).catch(error => console.error('Error marking as read:', error));
            
            // Allow navigation to happen
            setTimeout(() => {
                window.location.href = event.target.closest('a').href;
            }, 100);
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-refresh notification count every 30 seconds
            setInterval(function() {
                fetch('/notifications/unread-count')
                    .then(response => response.json())
                    .then(data => {
                        const notificationBadge = document.querySelector('.notification-count');
                        if (notificationBadge) {
                            if (data.data && data.data.count > 0) {
                                notificationBadge.textContent = data.data.count;
                                notificationBadge.style.display = 'block';
                            } else {
                                notificationBadge.style.display = 'none';
                            }
                        }
                    })
                    .catch(error => console.log('Error checking notifications:', error));
            }, 30000);
        });
    </script>
    @endpush

@endsection