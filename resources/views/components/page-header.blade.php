@props([
    'title' => '',
    'description' => '',
    'actionUrl' => '',
    'actionText' => '',
    'icon' => 'ri-add-line'
])

<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $title }}</h1>
        @if($description)
            <p class="text-slate-600">{{ $description }}</p>
        @endif
    </div>
    @if($actionUrl && $actionText)
        <a href="{{ $actionUrl }}">
            <button
                class="bg-indigo-600 text-white px-6 py-3 rounded-button hover:bg-indigo-700 transition-colors flex items-center space-x-2 !rounded-button whitespace-nowrap">
                <div class="w-5 h-5 flex items-center justify-center">
                    <i class="{{ $icon }}"></i>
                </div>
                <span>{{ $actionText }}</span>
            </button>
        </a>
    @endif
</div>
