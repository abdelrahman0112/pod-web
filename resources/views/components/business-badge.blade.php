@props(['user'])

@if($user && ($user->isClient() || $user->isAdmin()))
    @php
        if ($user->isAdmin()) {
            $tooltip = 'Administrator';
        } elseif ($user->isClient()) {
            $tooltip = 'Business Account';
        }
    @endphp
    <span class="inline-flex items-center justify-center w-4 h-4 bg-emerald-500 rounded-full flex-shrink-0 ml-1.5 badge-group relative" 
          title="{{ $tooltip }}" 
          aria-label="{{ $tooltip }}"
          onclick="event.stopPropagation();"
          onmouseenter="event.stopPropagation();">
        <i class="ri-check-line text-white text-xs leading-none"></i>
        <span class="badge-tooltip absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-slate-900 text-white text-xs rounded whitespace-nowrap transition-opacity pointer-events-none z-50">
            {{ $tooltip }}
            <span class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1 border-4 border-transparent border-t-slate-900"></span>
        </span>
    </span>
@endif

