{{-- user info and avatar --}}
<x-chatify-avatar 
    :src="null"
    :name="'User'"
    size="av-l" 
    class="chatify-d-flex" />
<p class="info-name">Select a conversation</p>
<div class="messenger-infoView-btns">
    <p class="text-slate-500 text-sm">Choose a conversation to view user details</p>
</div>
{{-- shared photos --}}
<div class="messenger-infoView-shared">
    <p class="messenger-title"><span>Shared Photos</span></p>
    <div class="shared-photos-list">
        <p class="text-slate-500 text-sm">No shared photos yet</p>
    </div>
</div>
