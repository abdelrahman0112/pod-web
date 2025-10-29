@props([
    'id' => 'modal',
    'title' => '',
    'maxWidth' => 'max-w-md',
    'closeable' => true,
    'persistent' => false
])

@php
    $maxWidthClass = match($maxWidth) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        '5xl' => 'max-w-5xl',
        '6xl' => 'max-w-6xl',
        '7xl' => 'max-w-7xl',
        default => $maxWidth
    };
@endphp

<div 
    id="{{ $id }}" 
    class="fixed inset-0 z-50 overflow-y-auto hidden"
    x-data="{ 
        show: false,
        open() { this.show = true; document.body.classList.add('overflow-hidden'); },
        close() { this.show = false; document.body.classList.remove('overflow-hidden'); }
    }"
    x-show="show"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="close()"
    @click.self="close()"
    style="display: none;"
>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    
    <!-- Modal Panel -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div 
            class="relative bg-white rounded-lg shadow-xl w-full {{ $maxWidthClass }} transform transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            @click.stop
        >
            <!-- Header -->
            @if($title || $closeable)
                <div class="flex items-center justify-between p-6 border-b border-slate-200">
                    @if($title)
                        <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
                    @endif
                    
                    @if($closeable)
                        <button 
                            type="button"
                            @click="close()"
                            class="text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    @endif
                </div>
            @endif
            
            <!-- Content -->
            <div class="p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Global functions to open/close modals
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
            modal.querySelector('[x-data]').__x.$data.show = true;
            document.body.classList.add('overflow-hidden');
        }
    };
    
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.querySelector('[x-data]').__x.$data.show = false;
            document.body.classList.remove('overflow-hidden');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    };
});
</script>
