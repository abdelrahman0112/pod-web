@props(['id' => 'confirmation-modal'])

<!-- Confirmation Modal -->
<div id="{{ $id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800" id="{{ $id }}-title">Confirm Action</h3>
            <button onclick="closeConfirmationModal('{{ $id }}')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <p class="text-slate-700" id="{{ $id }}-message">Are you sure you want to perform this action?</p>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button onclick="closeConfirmationModal('{{ $id }}')" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 transition-colors">
                Cancel
            </button>
            <button onclick="confirmAction('{{ $id }}')" id="{{ $id }}-confirm" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                Confirm
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let confirmationCallbacks = {};

    function openConfirmationModal(modalId, title, message, onConfirm) {
        const modal = document.getElementById(modalId);
        const titleElement = document.getElementById(modalId + '-title');
        const messageElement = document.getElementById(modalId + '-message');
        
        if (modal && titleElement && messageElement) {
            titleElement.textContent = title;
            messageElement.textContent = message;
            confirmationCallbacks[modalId] = onConfirm;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeConfirmationModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            delete confirmationCallbacks[modalId];
        }
    }

    function confirmAction(modalId) {
        if (confirmationCallbacks[modalId]) {
            confirmationCallbacks[modalId]();
            closeConfirmationModal(modalId);
        }
    }

    // Close modal on backdrop click
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[id$="-modal"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeConfirmationModal(this.id);
                }
            });
        });
    });
</script>
@endpush

