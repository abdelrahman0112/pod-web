@props([
    'title' => 'Share This',
    'copyFunction' => 'copyLink',
])

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-8">
    <h2 class="text-xl font-semibold text-slate-800 mb-4">{{ $title }}</h2>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        {{ $slot }}
    </div>
</div>
