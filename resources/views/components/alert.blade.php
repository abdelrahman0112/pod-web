@props([
    'type' => 'info', // info, success, warning, error
    'title' => null,
    'message' => '',
    'dismissible' => true,
    'id' => null
])

@php
    $typeClasses = [
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'error' => 'bg-red-50 border-red-200 text-red-800'
    ];
    
    $iconClasses = [
        'info' => 'ri-information-line text-blue-500',
        'success' => 'ri-check-line text-green-500',
        'warning' => 'ri-error-warning-line text-yellow-500',
        'error' => 'ri-close-circle-line text-red-500'
    ];
    
    $typeClass = $typeClasses[$type] ?? $typeClasses['info'];
    $iconClass = $iconClasses[$type] ?? $iconClasses['info'];
@endphp

<div 
    @if($id) id="{{ $id }}" @endif
    class="alert-component {{ $typeClass }} border rounded-lg p-4 mb-4 transition-all duration-300 ease-in-out transform"
    style="display: none;"
>
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="{{ $iconClass }} text-lg"></i>
        </div>
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-medium mb-1">{{ $title }}</h3>
            @endif
            <div class="text-sm">
                {{ $message }}
            </div>
        </div>
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button 
                        type="button" 
                        class="inline-flex rounded-md p-1.5 hover:bg-opacity-20 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors"
                        onclick="dismissAlert(this)"
                    >
                        <span class="sr-only">Dismiss</span>
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function showAlert(type, message, title = null, id = null) {
    // Remove any existing alert with the same ID
    if (id) {
        const existingAlert = document.getElementById(id);
        if (existingAlert) {
            existingAlert.remove();
        }
    }
    
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert-component border rounded-lg p-4 mb-4 transition-all duration-300 ease-in-out transform`;
    
    // Set type-specific classes
    const typeClasses = {
        'info': 'bg-blue-50 border-blue-200 text-blue-800',
        'success': 'bg-green-50 border-green-200 text-green-800',
        'warning': 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'error': 'bg-red-50 border-red-200 text-red-800'
    };
    
    const iconClasses = {
        'info': 'ri-information-line text-blue-500',
        'success': 'ri-check-line text-green-500',
        'warning': 'ri-error-warning-line text-yellow-500',
        'error': 'ri-close-circle-line text-red-500'
    };
    
    alertDiv.classList.add(...typeClasses[type].split(' '));
    
    if (id) {
        alertDiv.id = id;
    }
    
    alertDiv.innerHTML = `
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="${iconClasses[type]} text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                ${title ? `<h3 class="text-sm font-medium mb-1">${title}</h3>` : ''}
                <div class="text-sm">${message}</div>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button 
                        type="button" 
                        class="inline-flex rounded-md p-1.5 hover:bg-opacity-20 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors"
                        onclick="dismissAlert(this)"
                    >
                        <span class="sr-only">Dismiss</span>
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Insert at the top of the main content area
    const mainContent = document.querySelector('main') || document.body;
    const firstChild = mainContent.firstChild;
    mainContent.insertBefore(alertDiv, firstChild);
    
    // Show with animation
    setTimeout(() => {
        alertDiv.style.display = 'block';
        alertDiv.style.transform = 'translateY(0)';
        alertDiv.style.opacity = '1';
    }, 10);
    
    // Auto-dismiss after 5 seconds for success/info messages
    if (type === 'success' || type === 'info') {
        setTimeout(() => {
            dismissAlert(alertDiv.querySelector('button'));
        }, 5000);
    }
}

function dismissAlert(button) {
    const alertDiv = button.closest('.alert-component');
    if (alertDiv) {
        alertDiv.style.transform = 'translateY(-10px)';
        alertDiv.style.opacity = '0';
        setTimeout(() => {
            alertDiv.remove();
        }, 300);
    }
}

// Global error handler
window.showError = function(message, title = 'Error') {
    showAlert('error', message, title);
};

window.showSuccess = function(message, title = 'Success') {
    showAlert('success', message, title);
};

window.showWarning = function(message, title = 'Warning') {
    showAlert('warning', message, title);
};

window.showInfo = function(message, title = 'Info') {
    showAlert('info', message, title);
};
</script>
