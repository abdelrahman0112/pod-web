@props([
    'title' => '',
    'stats' => []
])

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <h3 class="font-semibold text-slate-800 mb-4">{{ $title }}</h3>
    <div class="space-y-4">
        @foreach($stats as $stat)
            @php
                $colors = [
                    'indigo' => 'text-indigo-600',
                    'green' => 'text-green-600',
                    'purple' => 'text-purple-600',
                    'default' => 'text-slate-800'
                ];
                $colorClass = $colors[$stat['color']] ?? $colors['default'];
            @endphp
            <div class="flex items-center justify-between">
                <span class="text-slate-600">{{ $stat['label'] }}</span>
                <span class="font-semibold {{ $colorClass }}">{{ $stat['value'] }}</span>
            </div>
        @endforeach
    </div>
</div>
