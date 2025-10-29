@props([
    'name' => '',
    'value' => '',
    'label' => '',
    'checked' => false
])

<label class="flex items-center space-x-2 cursor-pointer">
    <input 
        type="checkbox" 
        name="{{ $name }}" 
        value="{{ $value }}"
        {{ $checked ? 'checked' : '' }}
        class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 focus:ring-2"
    />
    <span class="text-sm text-slate-700">{{ $label }}</span>
</label>
