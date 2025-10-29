@extends('layouts.app')

@section('title', 'Event Categories')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Event Categories</h1>
            <p class="text-slate-600 mt-2">Manage event categories and their colors</p>
        </div>
        <a href="{{ route('admin.event-categories.create') }}" 
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
            <i class="ri-add-line mr-2"></i>Add Category
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-slate-200">
        @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-medium text-slate-700">Category</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-slate-700">Color</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-slate-700">Description</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-slate-700">Events</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($categories as $category)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $category->color }}"></div>
                                        <span class="font-medium text-slate-900">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white" 
                                          style="background-color: {{ $category->color }}">
                                        {{ $category->color }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $category->description ? Str::limit($category->description, 50) : 'No description' }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $category->events()->count() }} events
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.event-categories.edit', $category) }}" 
                                           class="text-indigo-600 hover:text-indigo-700 text-sm">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.event-categories.destroy', $category) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this category?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-700 text-sm">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-slate-400 mb-4">
                    <i class="ri-folder-open-line text-4xl"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-900 mb-2">No categories found</h3>
                <p class="text-slate-600 mb-6">Get started by creating your first event category.</p>
                <a href="{{ route('admin.event-categories.create') }}" 
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="ri-add-line mr-2"></i>Create Category
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
