@props(['date'])

<div class="absolute top-4 left-4 bg-white bg-opacity-90 rounded-lg px-4 py-3 shadow-sm">
    <div class="text-center">
        <div class="text-sm text-indigo-600 font-semibold uppercase tracking-wide">{{ $date->format('M') }}</div>
        <div class="text-xl font-bold text-slate-800">{{ $date->format('d') }}</div>
        <div class="text-xs text-slate-500">{{ $date->format('Y') }}</div>
    </div>
</div>

