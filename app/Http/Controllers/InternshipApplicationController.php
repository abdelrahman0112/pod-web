<?php

namespace App\Http\Controllers;

use App\Models\InternshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternshipApplicationController extends Controller
{
    /**
     * Display the internship application form.
     */
    public function create()
    {
        return view('internships.apply');
    }

    /**
     * Store a newly created internship application.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'university' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:2020|max:2030',
            'gpa' => 'nullable|numeric|min:0|max:4',
            'experience' => 'nullable|string|max:2000',
            'skills' => 'required|string|max:500',
            'interests' => 'required|string|max:500',
            'availability_start' => 'required|date|after:today',
            'availability_end' => 'required|date|after:availability_start',
            'motivation' => 'required|string|max:2000',
            'portfolio_links' => 'nullable|array',
            'portfolio_links.github' => 'nullable|url|max:255',
            'portfolio_links.linkedin' => 'nullable|url|max:255',
            'portfolio_links.website' => 'nullable|url|max:255',
            'portfolio_links.other' => 'nullable|url|max:255',
            'terms' => 'accepted',
        ]);

        // Create the internship application
        $application = InternshipApplication::create([
            'user_id' => Auth::id(),
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'university' => $validated['university'],
            'major' => $validated['major'],
            'graduation_year' => $validated['graduation_year'],
            'gpa' => $validated['gpa'],
            'experience' => $validated['experience'],
            'skills' => $validated['skills'],
            'interests' => $validated['interests'],
            'availability_start' => $validated['availability_start'],
            'availability_end' => $validated['availability_end'],
            'motivation' => $validated['motivation'],
            'portfolio_links' => json_encode($validated['portfolio_links'] ?? []),
            'status' => 'pending',
        ]);

        return redirect()->route('home')->with('success', 'Your internship application has been submitted successfully! We will review your application and get back to you soon.');
    }

    /**
     * Display all internship applications for admin.
     */
    public function index()
    {
        // For future admin functionality
        $applications = InternshipApplication::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.internships.index', compact('applications'));
    }

    /**
     * Display the specified internship application.
     */
    public function show(InternshipApplication $application)
    {
        // For future admin functionality
        return view('admin.internships.show', compact('application'));
    }
}
