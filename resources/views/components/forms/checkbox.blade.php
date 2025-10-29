@props([
    'name' => '',
    'value' => '1',
    'checked' => false,
    'label' => '',
    'help' => '',
    'required' => false,
    'disabled' => false,
    'id' => null
])

@php
    $id = $id ?? $name;
    $checked = $checked || old($name) == $value;
@endphp

<div class="space-y-2">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="flex items-center">
        <input 
            type="checkbox"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ $value }}"
            {{ $checked ? 'checked' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
        
        @if($label)
            <label for="{{ $id }}" class="ml-2 text-sm text-slate-700">
                {{ $label }}
            </label>
        @endif
    </div>
    
    @if($help)
        <p class="text-xs text-slate-500">{{ $help }}</p>
    @endif
    
    @error($name)
        <p class="text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
