<?php
$seenIcon = (!!$seen ? 'check-double' : 'check');
$timeAndSeen = "<span data-time='$created_at' class='message-time inline-flex items-center text-xs text-slate-400 mt-1'>
        ".($isSender ? "<i class='fas fa-$seenIcon mr-1'></i>" : '' )." <span class='time'>$timeAgo</span>
    </span>";
?>

<div class="message-card flex {{ $isSender ? 'justify-end' : 'justify-start' }} mb-4" data-id="{{ $id }}">
    <div class="max-w-[70%] {{ $isSender ? 'order-2' : 'order-1' }}">
        
        {{-- Message Bubble --}}
        <div class="message-card-content">
            @if (@$attachment->type != 'image' || $message)
                <div class="message {{ $isSender ? 'bg-indigo-600 text-white' : 'bg-white border border-slate-200 text-slate-800' }} rounded-2xl px-4 py-3 shadow-sm relative group">
                    {{-- Delete Button (for sender only) --}}
                    @if ($isSender)
                        <button class="delete-btn absolute -right-8 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity text-slate-400 hover:text-red-500 p-1.5 rounded-lg hover:bg-slate-100" 
                                data-id="{{ $id }}"
                                title="Delete message">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    @endif
                    
                    {{-- Message Content --}}
                    <div class="message-text {{ $isSender ? 'text-white' : 'text-slate-800' }}">
                        {!! ($message == null && $attachment != null && @$attachment->type != 'file') ? $attachment->title : nl2br($message) !!}
                    </div>
                    
                    {{-- File Attachment --}}
                    @if(@$attachment->type == 'file')
                        <a href="{{ route(config('chatify.attachments.download_route_name'), ['fileName'=>$attachment->file]) }}" 
                           class="file-download flex items-center space-x-2 mt-2 p-3 {{ $isSender ? 'bg-indigo-500' : 'bg-slate-50' }} rounded-lg hover:opacity-90 transition-opacity">
                            <i class="fas fa-file text-lg"></i>
                            <span class="flex-1 truncate font-medium">{{$attachment->title}}</span>
                            <i class="fas fa-download text-sm"></i>
                        </a>
                    @endif
                    
                    {{-- Time and Seen Status --}}
                    {!! $timeAndSeen !!}
                </div>
            @endif
            
            {{-- Image Attachment --}}
            @if(@$attachment->type == 'image')
                <div class="image-wrapper">
                    <div class="image-file chat-image rounded-2xl overflow-hidden shadow-sm cursor-pointer hover:opacity-90 transition-opacity" 
                         style="background-image: url('{{ app('ChatifyMessenger')->getAttachmentUrl($attachment->file) }}'); background-size: cover; background-position: center; min-height: 200px; max-width: 300px;">
                        <div class="p-3 bg-gradient-to-t from-black/50 to-transparent">
                            <p class="text-white text-sm font-medium">{{ $attachment->title }}</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        {!! $timeAndSeen !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
