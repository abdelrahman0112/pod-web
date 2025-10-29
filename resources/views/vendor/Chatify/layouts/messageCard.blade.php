<?php
$seenIcon = (!!$seen ? 'check-double' : 'check');
$timeAndSeen = "<span data-time='$created_at' class='message-time inline-flex items-center text-xs text-slate-400 mt-1'>
        ".($isSender ? "<i class='ri-$seenIcon mr-1'></i>" : '' )." <span class='time'>$timeAgo</span>
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
                            <i class="ri-delete-bin-line text-sm"></i>
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
                            <i class="ri-file-line text-lg"></i>
                            <span class="flex-1 truncate font-medium">{{$attachment->title}}</span>
                            <i class="ri-download-line text-sm"></i>
                        </a>
                    @endif
                    
                    {{-- Time and Seen Status --}}
                    {!! $timeAndSeen !!}
                </div>
            @endif
            
            {{-- Image Attachment --}}
            @if(@$attachment->type == 'image')
                <div class="image-wrapper mt-2">
                    <div class="image-file rounded-2xl overflow-hidden shadow-sm bg-slate-100">
                        <img src="{{ app('ChatifyMessenger')->getAttachmentUrl($attachment->file) }}" 
                             alt="{{ $attachment->title }}"
                             class="chat-image max-w-xs cursor-pointer hover:opacity-90 transition-opacity rounded-2xl"
                             data-image="{{ app('ChatifyMessenger')->getAttachmentUrl($attachment->file) }}"
                             style="max-height: 300px; object-fit: cover; width: auto;"
                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23e2e8f0%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%2364748b%22 font-family=%22Arial%22 font-size=%2214%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EImage not found%3C/text%3E%3C/svg%3E';" />
                    </div>
                    <div class="mt-2">
                        {!! $timeAndSeen !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
