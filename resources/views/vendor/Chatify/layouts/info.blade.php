{{-- User Info Sidebar - Tailwind Version --}}
<div class="p-6 space-y-6">
    
    {{-- User Avatar and Name --}}
    <div class="text-center">
        <div class="inline-block mb-4">
            <x-chatify-avatar 
                :src="null"
                :name="'User'"
                size="av-l"
                :showDefault="false"
                :showStatus="true"
                :isOnline="false" />
        </div>
        <h3 class="info-name text-lg font-semibold text-slate-800 mb-1">Select a conversation</h3>
        <p class="text-sm text-slate-500">Choose a conversation to view user details</p>
    </div>

    {{-- Action Buttons --}}
    <div class="messenger-infoView-btns space-y-2">
        <button class="delete-conversation w-full px-4 py-2.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors font-medium text-sm hidden">
            <i class="ri-delete-bin-line mr-2"></i> Delete Conversation
        </button>
    </div>

    {{-- Shared Photos --}}
    <div class="messenger-infoView-shared">
        <h4 class="messenger-title text-sm font-semibold text-slate-700 mb-3">Shared Photos</h4>
        <div class="shared-photos-list grid grid-cols-3 gap-2">
            <p class="col-span-3 text-center text-slate-400 text-sm py-4">No shared photos yet</p>
        </div>
    </div>

    {{-- Divider --}}
    <div class="border-t border-slate-200"></div>

    {{-- Additional Info (will be populated by JS) --}}
    <div class="user-additional-info space-y-3 hidden">
        <div class="flex items-center justify-between text-sm">
            <span class="text-slate-500">Email</span>
            <span class="user-email font-medium text-slate-800"></span>
        </div>
        <div class="flex items-center justify-between text-sm">
            <span class="text-slate-500">Status</span>
            <span class="user-status inline-flex items-center">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                <span class="font-medium text-slate-800">Online</span>
            </span>
        </div>
    </div>
</div>
