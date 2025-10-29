@props([
    'name' => '',
    'label' => '',
    'placeholder' => '',
    'rows' => 3,
    'value' => '',
    'required' => false,
    'id' => null
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
        <textarea 
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            {{ $attributes->merge([
                'class' => 'block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-vertical'
            ]) }}
        >{{ old($name, $value) }}</textarea>
    </div>
</div>
