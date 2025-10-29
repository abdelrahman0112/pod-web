@props([
    'name' => '',
    'label' => '',
    'min' => 0,
    'max' => 100,
    'value' => 50,
    'step' => 1,
    'minLabel' => '',
    'maxLabel' => '',
    'valuePrefix' => '',
    'valueSuffix' => '',
    'showValue' => false
])

<div>
    <label class="text-sm font-medium text-slate-700 mb-2 block">{{ $label }}</label>
    <div class="px-1">
        <input 
            type="range" 
            name="{{ $name }}"
            min="{{ $min }}" 
            max="{{ $max }}" 
            value="{{ $value }}" 
            step="{{ $step }}"
            class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer slider"
        />
        <div class="flex justify-between items-center mt-2">
            <span class="text-xs text-slate-500">{{ $minLabel }}</span>
            @if($showValue)
                <span class="text-sm font-medium text-slate-700">{{ $valuePrefix }}{{ $value }}{{ $valueSuffix }}</span>
            @endif
            <span class="text-xs text-slate-500">{{ $maxLabel }}</span>
        </div>
    </div>
</div>
