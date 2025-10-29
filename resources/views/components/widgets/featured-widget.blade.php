@props([
    'title' => '',
    'items' => []
])

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <h3 class="font-semibold text-slate-800 mb-4">{{ $title }}</h3>
    <div class="space-y-4">
        @foreach($items as $item)
            <div class="border border-slate-100 rounded-lg p-4">
                <div class="flex items-start justify-between mb-2">
                    <h4 class="font-medium text-slate-800">{{ $item['title'] }}</h4>
                    @if(isset($item['badge']))
                        <span class="text-xs bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full">
                            <i class="ri-star-fill"></i>
                        </span>
                    @endif
                </div>
                <p class="text-sm text-slate-600 mb-2">{{ $item['description'] }}</p>
                <div class="flex items-center justify-between">
                    <div class="text-xs text-slate-500">{{ $item['meta'] }}</div>
                    @if(isset($item['price']))
                        <div class="text-sm font-semibold text-green-600">{{ $item['price'] }}</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
