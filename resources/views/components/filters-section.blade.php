@props([
    'alpineData',
    'allLabel' => 'All'
])

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-8" x-data="{{ $alpineData }}">
    <div class="space-y-4 w-full">
        <!-- Category Filters -->
        <div class="flex items-center space-x-2 w-full" x-ref="categoryContainer" style="flex-wrap: nowrap;">
            <button @click="filterByCategory(null)" 
                    :class="selectedCategory === null ? 'bg-indigo-100 text-indigo-600' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-4 py-2 rounded-button text-sm font-medium !rounded-button whitespace-nowrap">
                {{ $allLabel }}
            </button>
            
            <!-- Visible Categories -->
            <template x-for="(category, index) in visibleCategories" :key="category.id">
                <button @click="filterByCategory(category.id)" 
                        :class="selectedCategory === category.id ? 'text-white' : 'text-slate-600 hover:bg-slate-100'"
                        :style="selectedCategory === category.id ? 'background-color: ' + category.color : ''"
                        class="px-4 py-2 rounded-button text-sm font-medium !rounded-button whitespace-nowrap">
                    <span x-text="category.name"></span>
                </button>
            </template>
            
            <!-- Show More Button -->
            <div x-show="hasMoreCategories" class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center space-x-1 px-4 py-2 border border-slate-200 rounded-button text-sm hover:bg-slate-50 !rounded-button whitespace-nowrap">
                    <span>More</span>
                    <div class="w-4 h-4 flex items-center justify-center">
                        <i class="ri-arrow-down-s-line" :class="open ? 'rotate-180' : ''"></i>
                    </div>
                </button>
                
                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false" 
                     class="absolute top-full left-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-50">
                    <div class="p-2">
                        <template x-for="category in hiddenCategories" :key="category.id">
                            <button @click="filterByCategory(category.id); open = false" 
                                    :class="selectedCategory === category.id ? 'text-white' : 'text-slate-600 hover:bg-slate-50'"
                                    :style="selectedCategory === category.id ? 'background-color: ' + category.color : ''"
                                    class="w-full text-left px-3 py-2 text-sm rounded whitespace-nowrap">
                                <span x-text="category.name"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Dropdowns -->
        <div class="flex items-center space-x-4 flex-wrap">
            {{ $slot }}
        </div>
    </div>
</div>

