@props([
    'name' => '',
    'label' => '',
    'placeholder' => '',
    'icon' => null,
    'type' => 'text',
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
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} text-slate-400"></i>
            </div>
        @endif
        
        <input 
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            {{ $attributes->merge([
                'class' => 'block w-full rounded-lg border border-slate-300 px-3 py-2 text-slate-900 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 ' . ($icon ? 'pl-10' : '')
            ]) }}
        />
    </div>
</div>
