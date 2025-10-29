@extends('layouts.app')

@section('title', 'Edit Event Category')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.event-categories.index') }}" 
               class="text-indigo-600 hover:text-indigo-700 mb-4 inline-flex items-center">
                <i class="ri-arrow-left-line mr-2"></i>Back to Categories
            </a>
            <h1 class="text-3xl font-bold text-slate-900">Edit Event Category</h1>
            <p class="text-slate-600 mt-2">Update category details and color</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <form action="{{ route('admin.event-categories.update', $eventCategory) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                            Category Name *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $eventCategory->name) }}"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror"
                               placeholder="e.g., Technology, Business, Education"
                               required>
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-slate-700 mb-2">
                            Category Color *
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" 
                                   id="color" 
                                   name="color" 
                                   value="{{ old('color', $eventCategory->color) }}"
                                   class="w-12 h-10 border border-slate-300 rounded-lg cursor-pointer @error('color') border-red-300 @enderror">
                            <input type="text" 
                                   id="color-text" 
                                   value="{{ old('color', $eventCategory->color) }}"
                                   class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('color') border-red-300 @enderror"
                                   placeholder="#3B82F6"
                                   pattern="^#[0-9A-Fa-f]{6}$"
                                   required>
                        </div>
                        <p class="text-slate-500 text-sm mt-1">Choose a color that represents this category</p>
                        @error('color')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror"
                                  placeholder="Optional description for this category">{{ old('description', $eventCategory->description) }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-200 mt-8">
                    <a href="{{ route('admin.event-categories.index') }}" 
                       class="px-4 py-2 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorPicker = document.getElementById('color');
    const colorText = document.getElementById('color-text');
    
    // Sync color picker with text input
    colorPicker.addEventListener('input', function() {
        colorText.value = this.value;
    });
    
    // Sync text input with color picker
    colorText.addEventListener('input', function() {
        if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
            colorPicker.value = this.value;
        }
    });
});
</script>
@endsection
