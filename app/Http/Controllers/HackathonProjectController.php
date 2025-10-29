<?php

namespace App\Http\Controllers;

use App\Models\HackathonProject;
use App\Models\HackathonProjectFile;
use App\Models\HackathonTeam;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HackathonProjectController extends Controller
{
    /**
     * Store a newly created project.
     */
    public function store(Request $request, HackathonTeam $team): RedirectResponse
    {
        // Only leader can create project
        if ($team->leader_id !== Auth::id()) {
            return back()->with('error', 'Only the team leader can create a project.');
        }

        // Check if team already has a project
        if ($team->project()->exists()) {
            return back()->with('error', 'Team already has a project.');
        }

        // Check if hackathon has started
        if (! $team->hackathon->hasStarted()) {
            return back()->with('error', 'Cannot create project before hackathon starts.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'url' => 'nullable|url|max:500',
        ]);

        $validated['team_id'] = $team->id;

        HackathonProject::create($validated);

        return back()->with('success', 'Project created successfully!');
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, HackathonProject $project): RedirectResponse
    {
        // Only leader can update project details
        if ($project->team->leader_id !== Auth::id()) {
            return back()->with('error', 'Only the team leader can update project details.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'url' => 'nullable|url|max:500',
        ]);

        $project->update($validated);

        return back()->with('success', 'Project updated successfully!');
    }

    /**
     * Upload files to the project.
     */
    public function uploadFiles(Request $request, HackathonProject $project): RedirectResponse
    {
        // Check if user is team member
        if (! $project->team->hasUser(Auth::user())) {
            return back()->with('error', 'Only team members can upload files.');
        }

        // Check if hackathon has started
        if (! $project->team->hackathon->hasStarted()) {
            return back()->with('error', 'Cannot upload files before hackathon starts.');
        }

        $validator = Validator::make($request->all(), [
            'files.*' => [
                'required',
                'file',
                'max:24576', // 24 MB in KB
                'mimes:pdf,doc,docx,zip,rar,txt,md,py,js,java,cpp,c,html,css,xml,json,csv,sql,xls,xlsx,ppt,pptx,png,jpg,jpeg,gif,svg,psd,ai,fig,sketch',
            ],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('error', 'File upload failed. Please check file types and sizes.');
        }

        $files = $request->file('files');
        $currentFileCount = $project->file_count;
        $remainingSlots = 5 - $currentFileCount;

        if (count($files) > $remainingSlots) {
            return back()->with('error', "You can only upload up to {$remainingSlots} more file(s). Maximum 5 files allowed.");
        }

        foreach ($files as $file) {
            // Generate unique filename
            $filename = uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
            $originalFilename = $file->getClientOriginalName();

            // Store in secure directory
            $path = $file->storeAs('hackathon_projects/'.$project->team_id, $filename, 'public');

            // Save file metadata
            HackathonProjectFile::create([
                'project_id' => $project->id,
                'filename' => $filename,
                'original_filename' => $originalFilename,
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => Auth::id(),
            ]);
        }

        return back()->with('success', 'Files uploaded successfully!');
    }

    /**
     * Delete a file from the project.
     */
    public function deleteFile(Request $request, HackathonProjectFile $file): RedirectResponse
    {
        $project = $file->project;

        // Only team members can delete files
        if (! $project->team->hasUser(Auth::user())) {
            return back()->with('error', 'Only team members can delete files.');
        }

        // Delete file from storage
        Storage::disk('public')->delete($file->file_path);

        // Delete file record
        $file->delete();

        return back()->with('success', 'File deleted successfully!');
    }

    /**
     * Download a project file.
     */
    public function downloadFile(HackathonProjectFile $file)
    {
        // Check if user has access to view hackathon
        $project = $file->project;
        $hackathon = $project->team->hackathon;

        // Allow download if hackathon has started or user is team member
        if ($hackathon->hasStarted() || $project->team->hasUser(Auth::user())) {
            if (Storage::disk('public')->exists($file->file_path)) {
                return Storage::disk('public')->download($file->file_path, $file->original_filename);
            }
        }

        abort(404);
    }
}
