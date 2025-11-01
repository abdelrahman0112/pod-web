<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = EventCategory::orderBy('name')->get();

        return view('admin.event-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.event-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_categories',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        EventCategory::create($validated);

        return redirect()->route('admin.event-categories.index')
            ->with('success', 'Event category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EventCategory $eventCategory)
    {
        return view('admin.event-categories.show', compact('eventCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventCategory $eventCategory)
    {
        return view('admin.event-categories.edit', compact('eventCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventCategory $eventCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_categories,name,'.$eventCategory->id,
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $eventCategory->update($validated);

        return redirect()->route('admin.event-categories.index')
            ->with('success', 'Event category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventCategory $eventCategory)
    {
        // Check if category has events
        if ($eventCategory->events()->count() > 0) {
            return redirect()->route('admin.event-categories.index')
                ->with('error', 'Cannot delete category that has events. Please reassign events first.');
        }

        $eventCategory->delete();

        return redirect()->route('admin.event-categories.index')
            ->with('success', 'Event category deleted successfully.');
    }
}
