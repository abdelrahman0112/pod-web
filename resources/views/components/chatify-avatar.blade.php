@props([
    'src' => null,
    'name' => 'User',
    'size' => 'md',
    'class' => '',
    'showDefault' => true,
    'showStatus' => false,
    'isOnline' => false,
    'color' => null
])

@php
    $sizes = [
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-12 h-12 text-base',
        'lg' => 'w-32 h-32 text-2xl',
        'xl' => 'w-20 h-20 text-lg',
        'av-s' => 'w-8 h-8 text-xs',  // Chatify small
        'av-m' => 'w-10 h-10 text-sm', // Chatify medium
        'av-l' => 'w-16 h-16 text-lg'  // Chatify large
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $initials = strtoupper(substr($name, 0, 2));
    
    // Use provided color or fallback to default
    $colorClass = $color ?? 'bg-slate-100 text-slate-600';
@endphp

<div class="relative inline-flex items-center justify-center {{ $sizeClass }} {{ $class }} {{ $colorClass }} font-medium avatar" style="overflow: visible !important;">
    <div class="w-full h-full rounded-full overflow-hidden">
        @if($src && $src !== '' && $src !== 'null')
            <img src="{{ $src }}" 
                 alt="{{ $name }}" 
                 class="w-full h-full object-cover rounded-full"
                 onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="w-full h-full flex items-center justify-center rounded-full" style="display: none;">
                {{ $initials }}
            </div>
        @else
            <div class="w-full h-full flex items-center justify-center rounded-full">
                {{ $initials }}
            </div>
        @endif
    </div>
    
    {{-- Status Indicator - Completely outside the avatar container --}}
    @if($showStatus)
            <span class="absolute -bottom-1 -right-1 w-3 h-3 border-2 border-white rounded-full {{ $isOnline ? 'bg-green-500' : 'bg-slate-400' }}" 
                  title="{{ $isOnline ? 'Online' : 'Offline' }}"
                  style="z-index: 2; box-shadow: 0 0 0 1px rgba(0,0,0,0.1);"></span>
    @endif
</div>
