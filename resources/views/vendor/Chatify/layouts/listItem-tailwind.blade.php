{{-- -------------------- Saved Messages -------------------- --}}
@if($get == 'saved')
    <div class="messenger-list-item px-3 py-3 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors" data-contact="{{ Auth::user()->id }}">
        <div class="flex items-center space-x-3">
            {{-- Avatar --}}
            <div class="flex-shrink-0">
                <div class="saved-messages w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="far fa-bookmark text-indigo-600 text-lg"></i>
                </div>
            </div>
            
            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-sm font-semibold text-slate-800 truncate" data-id="{{ Auth::user()->id }}" data-type="user">
                        Saved Messages
                    </p>
                </div>
                <p class="text-xs text-slate-500 truncate">Save messages secretly</p>
            </div>
        </div>
    </div>
@endif

{{-- -------------------- Contact list -------------------- --}}
@if($get == 'users' && !!$lastMessage)
<?php
$lastMessageBody = mb_convert_encoding($lastMessage->body, 'UTF-8', 'UTF-8');
$lastMessageBody = strlen($lastMessageBody) > 35 ? mb_substr($lastMessageBody, 0, 35, 'UTF-8').'..' : $lastMessageBody;
?>
<div class="messenger-list-item px-3 py-3 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors" data-contact="{{ $user->id }}">
    <div class="flex items-center space-x-3">
        {{-- Avatar with Online Status --}}
        <div class="flex-shrink-0 relative">
            <x-chatify-avatar 
                :src="$user->avatar ?? null"
                :name="$user->name ?? 'User'"
                size="av-m"
                :showDefault="false" />
            @if($user->active_status)
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
            @endif
        </div>
        
        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-1">
                <p class="text-sm font-semibold text-slate-800 truncate" data-id="{{ $user->id }}" data-type="user">
                    {{ strlen($user->name) > 18 ? trim(substr($user->name,0,18)).'..' : $user->name }}
                </p>
                <span class="contact-item-time text-xs text-slate-400 flex-shrink-0 ml-2" data-time="{{$lastMessage->created_at}}">
                    {{ $lastMessage->timeAgo }}
                </span>
            </div>
            
            <div class="flex items-center justify-between">
                <p class="text-xs text-slate-500 truncate flex-1">
                    {{-- Last Message user indicator --}}
                    {!!
                        $lastMessage->from_id == Auth::user()->id
                        ? '<span class="lastMessageIndicator font-medium text-slate-600">You: </span>'
                        : ''
                    !!}
                    {{-- Last message body --}}
                    @if($lastMessage->attachment == null)
                        {!! $lastMessageBody !!}
                    @else
                        <span class="inline-flex items-center">
                            <i class="fas fa-file mr-1"></i> Attachment
                        </span>
                    @endif
                </p>
                {{-- Unread counter --}}
                @if($unseenCounter > 0)
                    <span class="flex-shrink-0 ml-2 inline-flex items-center justify-center w-5 h-5 bg-indigo-600 text-white text-xs font-medium rounded-full">
                        {{ $unseenCounter }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

{{-- -------------------- Search Item -------------------- --}}
@if($get == 'search_item')
<div class="messenger-list-item px-3 py-3 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors" data-contact="{{ $user->id }}">
    <div class="flex items-center space-x-3">
        {{-- Avatar --}}
        <div class="flex-shrink-0">
            <x-chatify-avatar 
                :src="$user->avatar ?? null"
                :name="$user->name ?? 'User'"
                size="av-m"
                :showDefault="false" />
        </div>
        
        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-slate-800 truncate" data-id="{{ $user->id }}" data-type="user">
                {{ strlen($user->name) > 20 ? trim(substr($user->name,0,20)).'..' : $user->name }}
            </p>
            <p class="text-xs text-slate-500 truncate">{{ $user->email ?? '' }}</p>
        </div>
    </div>
</div>
@endif

{{-- -------------------- Shared photos Item -------------------- --}}
@if($get == 'sharedPhoto')
<div class="shared-photo rounded-lg overflow-hidden cursor-pointer hover:opacity-90 transition-opacity" 
     style="background-image: url('{{ $image }}'); background-size: cover; background-position: center; width: 100%; aspect-ratio: 1/1;">
</div>
@endif
