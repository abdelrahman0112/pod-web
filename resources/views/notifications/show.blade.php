@extends('layouts.app')

@section('title', 'Notification Details')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Notification Details
        </h2>
        <a href="{{ route('notifications.index') }}" class="btn-secondary">
            Back to Notifications
        </a>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        @php
            $data = $notification->data;
            $isUnread = is_null($notification->read_at);
            $notificationType = class_basename($notification->type);
        @endphp

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-6">
                <!-- Notification Header -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-start space-x-4">
                        <!-- Notification Icon -->
                        <div class="flex-shrink-0">
                            @switch($notificationType)
                                @case('JobApplicationReceived')
                                    <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    @break
                                @case('EventReminder')
                                    <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    @break
                                @case('NewComment')
                                    <div class="h-12 w-12 bg-purple-100 rounded-full flex items-center justify-center">
                                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                    </div>
                                    @break
                                @case('HackathonInvitation')
                                    <div class="h-12 w-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </div>
                                    @break
                                @default
                                    <div class="h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM12 7V3l-5 5h5z"/>
                                        </svg>
                                    </div>
                            @endswitch
                        </div>

                        <!-- Title and Status -->
                        <div>
                            <div class="flex items-center space-x-3">
                                <h1 class="text-xl font-semibold text-gray-900">
                                    {{ $data['title'] ?? 'Notification' }}
                                </h1>
                                @if($isUnread)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Unread
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Read
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ ucfirst(str_replace(['_', 'App\\Notifications\\'], [' ', ''], $notification->type)) }}
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2">
                        @if($isUnread)
                            <form method="POST" action="{{ route('notifications.mark-as-read', $notification->id) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-secondary">
                                    Mark as Read
                                </button>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" class="inline" 
                              onsubmit="return confirm('Are you sure you want to delete this notification?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-secondary text-red-600 border-red-300 hover:bg-red-50">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Notification Content -->
                <div class="space-y-6">
                    <!-- Main Message -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Message</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700">
                                {{ $data['message'] ?? $data['body'] ?? 'You have a new notification' }}
                            </p>
                        </div>
                    </div>

                    <!-- Additional Details -->
                    @if(isset($data['details']) || isset($data['description']))
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Details</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-700">
                                    {{ $data['details'] ?? $data['description'] }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Metadata -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Information</h3>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Received</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $notification->created_at->format('M j, Y \a\t g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($isUnread)
                                        <span class="text-blue-600">Unread</span>
                                    @else
                                        <span class="text-gray-600">Read on {{ $notification->read_at->format('M j, Y \a\t g:i A') }}</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $notificationType }}</dd>
                            </div>
                            @if(isset($data['from']) || isset($data['sender']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">From</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $data['from'] ?? $data['sender'] }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Action Button -->
                    @if(isset($data['action_url']))
                        <div class="border-t border-gray-200 pt-6">
                            <a href="{{ $data['action_url'] }}" class="btn-primary">
                                {{ $data['action_text'] ?? 'Take Action' }}
                            </a>
                        </div>
                    @endif

                    <!-- All Data (for debugging, can be removed in production) -->
                    @if(config('app.debug'))
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Debug Information</h3>
                            <div class="bg-gray-100 rounded-lg p-4">
                                <pre class="text-xs text-gray-600 overflow-auto">{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
