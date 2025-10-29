{{-- Send Message Form - Tailwind Version --}}
<div class="messenger-sendCard bg-white border-t border-slate-200 px-6 py-4">
    <form id="message-form" method="POST" action="{{ route('send.message') }}" enctype="multipart/form-data" class="flex items-end space-x-3">
        @csrf
        
        {{-- Attachment Button --}}
        <label class="flex-shrink-0 cursor-pointer">
            <div class="w-10 h-10 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition-colors">
                <i class="fas fa-plus-circle text-xl"></i>
            </div>
            <input 
                disabled='disabled' 
                type="file" 
                class="upload-attachment hidden" 
                name="file" 
                accept=".{{implode(', .',config('chatify.attachments.allowed_images'))}}, .{{implode(', .',config('chatify.attachments.allowed_files'))}}" />
        </label>

        {{-- Emoji Button --}}
        <button 
            type="button"
            class="emoji-button flex-shrink-0 w-10 h-10 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition-colors">
            <i class="fas fa-smile text-xl"></i>
        </button>

        {{-- Message Input --}}
        <div class="flex-1 relative">
            <textarea 
                readonly='readonly' 
                name="message" 
                class="m-send app-scroll w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none transition-all bg-slate-50 hover:bg-white" 
                placeholder="Type a message..."
                rows="1"
                style="max-height: 120px; min-height: 44px;"></textarea>
        </div>

        {{-- Send Button --}}
        <button 
            disabled='disabled' 
            type="submit"
            class="send-button flex-shrink-0 w-10 h-10 flex items-center justify-center bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm">
            <i class="fas fa-paper-plane text-lg"></i>
        </button>
    </form>
</div>
