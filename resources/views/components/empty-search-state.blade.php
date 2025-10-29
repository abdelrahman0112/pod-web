@props([
    'icon' => 'ri-search-line',
    'iconBg' => 'from-indigo-100 to-purple-100',
    'iconColor' => 'text-indigo-400',
    'filterKeys' => [],
    'clearFiltersFunction' => 'clearAllFilters',
    'title' => 'No Results Found',
    'titleFiltered' => null,
    'description' => 'Try adjusting your search criteria to find what you\'re looking for.',
    'descriptionFiltered' => null,
    'colSpanFull' => true,
])

@php
    $hasFilters = !empty($filterKeys) && request()->hasAny($filterKeys);
    $displayTitle = $hasFilters && $titleFiltered ? $titleFiltered : $title;
    $displayDescription = $hasFilters && $descriptionFiltered ? $descriptionFiltered : $description;
@endphp

<div class="{{ $colSpanFull ? 'col-span-full' : '' }} flex flex-col items-center justify-center py-16 px-8">
    <div class="w-24 h-24 bg-gradient-to-br {{ $iconBg }} rounded-full flex items-center justify-center mb-6">
        <i class="{{ $icon }} text-4xl {{ $iconColor }}"></i>
    </div>
    
    <h3 class="text-xl font-semibold text-slate-800 mb-3">{{ $displayTitle }}</h3>
    
    <p class="text-slate-600 text-center mb-6 max-w-md">{{ $displayDescription }}</p>
    
    @if($hasFilters)
        <button onclick="{{ $clearFiltersFunction }}()" 
                class="bg-indigo-600 text-white px-6 py-3 rounded-button hover:bg-indigo-700 transition-colors !rounded-button whitespace-nowrap inline-flex items-center space-x-2">
            <i class="ri-refresh-line"></i>
            <span>Clear All Filters</span>
        </button>
    @endif
</div>

