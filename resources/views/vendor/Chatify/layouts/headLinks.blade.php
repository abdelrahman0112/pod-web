<title>Messages - People Of Data</title>

{{-- Meta tags --}}
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="id" content="{{ $id }}">
<meta name="messenger-color" content="{{ $messengerColor }}">
<meta name="messenger-theme" content="{{ $dark_mode }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="url" content="{{ url('').'/'.config('chatify.routes.prefix') }}" data-user="{{ Auth::user()->id }}">

{{-- Tailwind CDN --}}
<script src="https://cdn.tailwindcss.com/3.4.16"></script>

{{-- Remix Icons --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet" />

{{-- scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/chatify/font.awesome.min.js') }}"></script>
<script src="{{ asset('js/chatify/autosize.js') }}"></script>
{{-- EmojiButton Library --}}
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@2.8.2/dist/index.min.js"></script>
@vite('resources/js/app.js')
<script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>

{{-- styles --}}
<link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css'/>

{{-- Animated Notification System --}}
<style>
.notification-container {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10000;
    pointer-events: none;
}

.notification {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 12px 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 500;
    min-width: 200px;
    justify-content: center;
    opacity: 0;
    transform: translateY(-20px) scale(0.9);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.notification.show {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.notification.success {
    background: rgba(34, 197, 94, 0.95);
    color: white;
    border-color: rgba(34, 197, 94, 0.3);
}

.notification.warning {
    background: rgba(245, 158, 11, 0.95);
    color: white;
    border-color: rgba(245, 158, 11, 0.3);
}

.notification.error {
    background: rgba(239, 68, 68, 0.95);
    color: white;
    border-color: rgba(239, 68, 68, 0.3);
}

/* Emoji picker preview styling */
.emoji-picker__preview {
    box-sizing: initial !important;
    border-top: none !important;
}

.notification-icon {
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-icon.success {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
}

.notification-icon.warning {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
}

.notification-icon.error {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
}

/* Hide the original connection status */
.internet-connection {
    display: none !important;
}
</style>

{{-- Notification Container --}}
<div id="notification-container" class="notification-container"></div>

{{-- Tailwind Configuration --}}
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: "#6366f1",
                    secondary: "#8b5cf6",
                },
            },
        },
    };
</script>

{{-- Custom Styles for Chat --}}
<style>
    :root {
        --primary-color: #6366f1;
    }
    
    /* Header consistency - Fixed height with vertical centering for logo, chat header, and info sidebar */
    .messenger-listView > div:first-child,
    .m-header-messaging,
    .messenger-infoView nav {
        height: 73px !important;
        padding: 0 1.5rem !important;
        display: flex !important;
        align-items: center !important;
    }
    
    /* Search box wrapper - keep padding for proper spacing */
    .messenger-listView > div:nth-child(2) {
        padding: 1rem 1.5rem !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    
    /* Chat header flex container - ensure full width */
    .m-header-messaging .flex.items-center.justify-between {
        width: 100% !important;
    }
    
    /* Chat header buttons - float star and info buttons to the right */
    .m-header-messaging .flex.items-center.space-x-2 {
        margin-left: auto !important;
        float: right !important;
    }
    
    /* Avatar styles - Force rounded avatars */
    .avatar img,
    .header-avatar img,
    .messenger-infoView .avatar img,
    .messenger-list-item .avatar img,
    .favorite-list-item .avatar img {
        border-radius: 50% !important;
        border-radius: 9999px !important; /* Tailwind's rounded-full equivalent */
    }
    
    /* Ensure avatar containers are also rounded */
    .avatar,
    .header-avatar .avatar,
    .messenger-infoView .avatar,
    .messenger-list-item .avatar,
    .favorite-list-item .avatar {
        border-radius: 50% !important;
        border-radius: 9999px !important;
        overflow: hidden !important;
    }
    
    /* Remove any background images from avatar containers */
    .header-avatar,
    .header-avatar .avatar,
    .messenger-infoView .avatar,
    .messenger-list-item .avatar,
    .favorite-list-item .avatar {
        background-image: none !important;
        background-size: unset !important;
        background-position: unset !important;
    }
    
    /* Connection status animations */
    .internet-connection span {
        display: none;
    }
    
    .internet-connection .ic-connected {
        display: inline-flex;
    }
    
    /* Hide/show elements controlled by JavaScript */
    .messenger-infoView {
        display: none;
    }
    
    /* Hide all modals by default */
    .app-modal {
        display: none !important;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }
    
    .app-modal.show {
        display: flex !important;
    }
    
    .app-modal-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .app-modal-card {
        padding: 24px;
    }
    
    .app-modal-header {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 12px;
    }
    
    .app-modal-body {
        color: #6b7280;
        margin-bottom: 20px;
        line-height: 1.5;
    }
    
    .app-modal-footer {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 20px;
    }
    
    .app-btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }
    
    .app-btn.cancel {
        background-color: #f3f4f6;
        color: #374151;
    }
    
    .app-btn.cancel:hover {
        background-color: #e5e7eb;
    }
    
    .app-btn.a-btn-danger {
        background-color: #ef4444;
        color: white;
    }
    
    .app-btn.a-btn-danger:hover {
        background-color: #dc2626;
    }
    
    .app-btn.a-btn-success {
        background-color: #10b981;
        color: white;
    }
    
    .app-btn.a-btn-success:hover {
        background-color: #059669;
    }
    
    .app-btn.a-btn-primary {
        background-color: #3b82f6;
        color: white;
    }
    
    .app-btn.a-btn-primary:hover {
        background-color: #2563eb;
    }
    
    /* Image modal - Disabled to prevent conflicts with our custom lightbox */
    .imageModal {
        display: none !important;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: 10001;
        align-items: center;
        justify-content: center;
    }
    
    .imageModal.show {
        display: flex;
    }
    
    .imageModal-content {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }
    
    .imageModal-close {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .imageModal-close:hover {
        opacity: 0.7;
    }
    
    /* Upload avatar */
    .upload-avatar-preview {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-size: cover;
        background-position: center;
        margin: 0 auto 16px;
    }
    
    .upload-avatar-details {
        text-align: center;
        color: #6b7280;
        margin-bottom: 16px;
    }
    
    .upload-avatar {
        display: none;
    }
    
    .divider {
        height: 1px;
        background-color: #e5e7eb;
        margin: 20px 0;
    }
    
    .dark-mode-switch {
        cursor: pointer;
        font-size: 20px;
        margin-left: 8px;
    }
    
    .color-btn {
        display: inline-block;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin: 4px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: border-color 0.2s;
    }
    
    .color-btn:hover {
        border-color: #374151;
    }
    
    .color-btn.active {
        border-color: #1f2937;
        border-width: 3px;
    }
    
    /* Favorite star styling */
    .add-to-favorite {
        display: inline-block;
        position: relative;
    }

    .add-to-favorite i {
        color: #9ca3af !important;
        transition: color 0.2s ease;
    }

    .add-to-favorite:hover i {
        color: #fbbf24 !important;
    }

    .add-to-favorite.favorite i {
        color: #fbbf24 !important;
    }

    /* Override Tailwind classes for star button */
    .add-to-favorite.text-slate-600 i {
        color: #9ca3af !important;
    }

    .add-to-favorite.favorite.text-slate-600 i {
        color: #fbbf24 !important;
    }

    /* Force star button colors with maximum specificity */
    button.add-to-favorite i.fas.fa-star {
        color: #9ca3af !important;
    }

    button.add-to-favorite.favorite i.fas.fa-star {
        color: #fbbf24 !important;
    }

    /* Even more specific override for Tailwind */
    .m-header-messaging button.add-to-favorite i.fas.fa-star {
        color: #9ca3af !important;
    }

    .m-header-messaging button.add-to-favorite.favorite i.fas.fa-star {
        color: #fbbf24 !important;
    }

    /* Nuclear option - override everything */
    .add-to-favorite i[class*="fa-star"] {
        color: #9ca3af !important;
    }

    .add-to-favorite.favorite i[class*="fa-star"] {
        color: #fbbf24 !important;
    }
    
    /* Favorite list items */
    .favorite-list-item {
        cursor: pointer;
    }
    
    .messenger-favorites {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    /* EmojiButton library styles - Specific fixes */
    .emoji-picker {
        display: block !important;
        background-color: #ffffff !important;
        color: #000000 !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    /* AGGRESSIVE EMOJI PICKER OVERRIDES - Higher specificity */
    
    /* Force box-sizing changes with maximum specificity */
    .emoji-picker .emoji-picker__content,
    .emoji-picker .emoji-picker__emoji-area,
    .emoji-picker__content,
    .emoji-picker__emoji-area {
        box-sizing: initial !important;
        -webkit-box-sizing: initial !important;
        -moz-box-sizing: initial !important;
    }
    
    /* Force border color with maximum specificity */
    .emoji-picker .emoji-picker__content,
    .emoji-picker__content {
        border-bottom: 1px solid #f1f5f9 !important;
        border-top: 1px solid #f1f5f9 !important;
    }
    
    /* Force tab icon colors with maximum specificity */
    .emoji-picker .emoji-picker__category-button svg,
    .emoji-picker .emoji-picker__category-button i,
    .emoji-picker__category-button svg,
    .emoji-picker__category-button i {
        color: #6b7280 !important;
        fill: #6b7280 !important;
    }
    
    /* Force hover state for tab icons */
    .emoji-picker .emoji-picker__category-button:hover svg,
    .emoji-picker .emoji-picker__category-button:hover i,
    .emoji-picker__category-button:hover svg,
    .emoji-picker__category-button:hover i {
        color: #374151 !important;
        fill: #374151 !important;
    }
    
    /* Force active state for tab icons */
    .emoji-picker .emoji-picker__category-button.active svg,
    .emoji-picker .emoji-picker__category-button.active i,
    .emoji-picker__category-button.active svg,
    .emoji-picker__category-button.active i {
        color: #3b82f6 !important;
        fill: #3b82f6 !important;
    }
    
    /* Force tab button styling */
    .emoji-picker .emoji-picker__category-button,
    .emoji-picker__category-button {
        background-color: transparent !important;
        color: #6b7280 !important;
        border: none !important;
        border-bottom: 2px solid transparent !important;
        border-radius: 0 !important;
        padding: 8px 12px !important;
        margin: 0 !important;
        font-size: 14px !important;
        font-weight: 500 !important;
    }
    
    .emoji-picker .emoji-picker__category-button:hover,
    .emoji-picker__category-button:hover {
        background-color: transparent !important;
        color: #374151 !important;
    }
    
    .emoji-picker .emoji-picker__category-button.active,
    .emoji-picker__category-button.active {
        background-color: transparent !important;
        color: #3b82f6 !important;
        border-bottom: 2px solid #3b82f6 !important;
    }
    
    /* Universal overrides for any emoji picker elements */
    [class*="emoji-picker"] [class*="content"],
    [class*="emoji-picker"] [class*="emoji-area"] {
        box-sizing: initial !important;
    }
    
    [class*="emoji-picker"] [class*="category-button"] svg,
    [class*="emoji-picker"] [class*="category-button"] i {
        color: #6b7280 !important;
        fill: #6b7280 !important;
    }
    
    [class*="emoji-picker"] [class*="category-button"].active svg,
    [class*="emoji-picker"] [class*="category-button"].active i {
        color: #3b82f6 !important;
        fill: #3b82f6 !important;
    }
    
    /* Search box styling */
    .emoji-picker input,
    .emoji-picker input[type="text"],
    .emoji-picker input[type="search"] {
        background-color: #ffffff !important;
        color: #000000 !important;
        border: 1px solid #d1d5db !important;
        border-radius: 6px !important;
        padding: 8px 12px !important;
    }
    
    .emoji-picker input::placeholder {
        color: #6b7280 !important;
    }
    
    .emoji-picker input:focus {
        outline: none !important;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }
    
    /* Overall emoji picker container */
    .emoji-picker * {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
    
    /* Override the universal selector for specific elements */
    .emoji-picker__emoji,
    .emoji-picker__category-button {
        background-color: transparent !important;
    }
    
    .emoji-picker__category-button.active {
        color: #3b82f6 !important;
    }
    
    /* Responsive list view */
    @media (max-width: 979px) {
        .messenger-listView.conversation-active {
            display: none !important;
        }
    }
    
    /* Smooth scrolling for messages */
    .app-scroll {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    
    .app-scroll::-webkit-scrollbar {
        width: 6px;
    }
    
    .app-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .app-scroll::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 3px;
    }
    
    .app-scroll::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8;
    }
    
    /* Disable elastic scroll effect */
    html, body {
        overscroll-behavior: none;
        overscroll-behavior-y: none;
    }
    
    .messages-container, .app-scroll {
        overscroll-behavior: none;
        overscroll-behavior-y: none;
    }
    
    /* Prevent bounce effect on iOS */
    .messages-container {
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
    }
    
    /* Message animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .message-card {
        animation: slideIn 0.2s ease;
    }
    
    /* Typing indicator animation */
    @keyframes bounce {
        0%, 60%, 100% {
            transform: translateY(0);
        }
        30% {
            transform: translateY(-4px);
        }
    }
    
    .typing-dots .dot {
        animation: bounce 1.4s infinite;
    }
    
    .typing-dots .dot-1 {
        animation-delay: 0s;
    }
    
    .typing-dots .dot-2 {
        animation-delay: 0.2s;
    }
    
    .typing-dots .dot-3 {
        animation-delay: 0.4s;
    }
    
    /* Center scroll button within chat area only */
    .chat-area-centered {
        left: 50%;
        transform: translateX(-50%);
    }
    
    /* Adjust for sidebar width - assuming sidebar is ~320px */
    @media (min-width: 1024px) {
        .chat-area-centered {
            left: calc(50% + 160px);
            transform: translateX(-50%);
        }
    }
</style>
