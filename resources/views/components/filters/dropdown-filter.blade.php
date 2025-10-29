@props([
    'id' => '',
    'name' => '',
    'placeholder' => 'Select option',
    'icon' => null,
    'options' => [],
    'selected' => null
])

<div class="relative">
    <select name="{{ $name }}" 
            id="{{ $id }}"
            class="inline-flex items-center px-4 py-2 text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none pr-8">
        @if($icon)
            <i class="{{ $icon }} mr-2 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
        @endif
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $option)
            <option value="{{ $option['value'] }}" 
                    {{ $selected == $option['value'] ? 'selected' : '' }}>
                {{ $option['label'] }}
            </option>
        @endforeach
    </select>
    <i class="ri-arrow-down-s-line absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
</div>
