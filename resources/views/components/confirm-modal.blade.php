@props([
    'id' => 'confirmModal',
    'title' => 'Confirm Action',
    'message' => 'Are you sure you want to proceed?',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'confirmAction' => '',
    'confirmMethod' => 'POST',
    'danger' => false,
    'success' => false,
])

<div 
    id="{{ $id }}" 
    class="fixed inset-0 z-50 overflow-y-auto"
    x-data="{ show: false }"
    x-show="show"
    x-transition
    style="display: none;"
    x-cloak
>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50" onclick="window.closeConfirmModal('{{ $id }}')"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md transform transition-all" @click.stop>
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 {{ $danger ? 'bg-red-100' : ($success ? 'bg-green-100' : 'bg-amber-100') }}">
                    <i class="ri-question-line text-2xl {{ $danger ? 'text-red-600' : ($success ? 'text-green-600' : 'text-amber-600') }}"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ $title }}</h3>
                <p class="text-slate-600 mb-6">{{ $message }}</p>
                
                <div class="flex justify-center space-x-3">
                    <button 
                        type="button" 
                        onclick="window.closeConfirmModal('{{ $id }}')"
                        class="px-5 py-2.5 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors font-medium">
                        {{ $cancelText }}
                    </button>
                    <form method="POST" action="{{ $confirmAction }}" class="inline" id="confirmForm{{ $id }}">
                        @csrf
                        @if($confirmMethod !== 'POST')
                            <input type="hidden" name="_method" value="{{ $confirmMethod }}">
                        @endif
                        {{ $slot }}
                        <button 
                            type="submit"
                            class="px-5 py-2.5 rounded-xl transition-colors font-medium {{ $danger ? 'bg-red-600 text-white hover:bg-red-700' : ($success ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-indigo-600 text-white hover:bg-indigo-700') }}">
                            {{ $confirmText }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
if (!window.closeConfirmModal) {
    window.closeConfirmModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            try {
                // Close Alpine modal immediately
                if (modal.__x && modal.__x.$data) {
                    modal.__x.$data.show = false;
                }
            } catch (e) {
                // Alpine not available, just hide
            }
            
            // Remove overflow and hide immediately
            document.body.classList.remove('overflow-hidden');
            modal.style.display = 'none';
        }
    };
}
</script>

