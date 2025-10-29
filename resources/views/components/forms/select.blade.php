@props([
    'name' => '',
    'label' => '',
    'placeholder' => 'Select option',
    'options' => [],
    'value' => '',
    'required' => false,
    'id' => null,
    'showPlaceholder' => true,
])

<div class="space-y-2">
    @if($label)
        <label class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        <select 
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            @if($required) required @endif
            {{ $attributes->merge([
                'class' => 'block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500'
            ]) }}
        >
            @if($showPlaceholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach($options as $option)
                <option value="{{ $option['value'] }}" {{ old($name, $value) == $option['value'] ? 'selected' : '' }}>
                    {{ $option['label'] }}
                </option>
            @endforeach
        </select>
    </div>
</div>
