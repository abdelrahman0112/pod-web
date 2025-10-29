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
    public function create(Request $request)
    {
        $internship_id = $request->query('internship_id');
        return view('internships.apply', compact('internship_id'));
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

        $validated['internship_id'] = $request->internship_id;
        // Create the internship application
        $application = InternshipApplication::create([
            'internship_id' => $validated['internship_id'],
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
     * Display the internship index page.
     */
    public function index()
    {
        $internships = \App\Models\Internship::where('status', 'open')
            ->orderBy('application_deadline', 'asc')
            ->get();

        $myApplications = InternshipApplication::where('user_id', Auth::id())
            ->with('internship')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('internships.index', compact('internships', 'myApplications'));
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
