<?php

namespace App\Http\Controllers;

use App\Models\ClientConversionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientConversionRequestController extends Controller
{
    /**
     * Show the form for creating a new client conversion request.
     */
    public function create(): View
    {
        // Check if user already has a pending request
        $existingRequest = auth()->user()->clientConversionRequests()
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return view('client.conversion-request', compact('existingRequest'));
        }

        return view('client.conversion-request');
    }

    /**
     * Store a newly created client conversion request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'business_field' => 'required|string|max:255',
            'company_website' => 'nullable|url|max:255',
            'linkedin_company_page' => 'nullable|url|max:255',
            'additional_info' => 'nullable|string|max:2000',
        ]);

        // Check if user already has a pending request
        $existingRequest = auth()->user()->clientConversionRequests()
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'You already have a pending client conversion request.');
        }

        // Check if user is already a client or admin
        if (auth()->user()->isClient() || auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'You already have client or admin privileges.');
        }

        ClientConversionRequest::create([
            'user_id' => auth()->id(),
            ...$validated,
        ]);

        return redirect()->route('home')->with('success', 'Your client conversion request has been submitted and is under review.');
    }

    /**
     * Display the specified client conversion request.
     */
    public function show(ClientConversionRequest $clientConversionRequest): View
    {
        $this->authorize('view', $clientConversionRequest);

        return view('client.conversion-request-details', compact('clientConversionRequest'));
    }

    /**
     * Show the form for editing the specified client conversion request.
     */
    public function edit(ClientConversionRequest $clientConversionRequest): View
    {
        $this->authorize('update', $clientConversionRequest);

        return view('client.conversion-request-edit', compact('clientConversionRequest'));
    }

    /**
     * Update the specified client conversion request.
     */
    public function update(Request $request, ClientConversionRequest $clientConversionRequest): RedirectResponse
    {
        $this->authorize('update', $clientConversionRequest);

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'business_field' => 'required|string|max:255',
            'company_website' => 'nullable|url|max:255',
            'linkedin_company_page' => 'nullable|url|max:255',
            'additional_info' => 'nullable|string|max:2000',
        ]);

        $clientConversionRequest->update($validated);

        return redirect()->route('client.conversion-request.show', $clientConversionRequest)
            ->with('success', 'Your client conversion request has been updated.');
    }

    /**
     * Remove the specified client conversion request.
     */
    public function destroy(ClientConversionRequest $clientConversionRequest): RedirectResponse
    {
        $this->authorize('delete', $clientConversionRequest);

        $clientConversionRequest->delete();

        return redirect()->route('home')->with('success', 'Your client conversion request has been cancelled.');
    }
}
