<?php

namespace App\Http\Controllers;

use App\Models\Hackathon;
use App\Models\HackathonTeam;
use App\Models\HackathonTeamInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HackathonDashboardController extends Controller
{
    /**
     * Display the hackathons dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's hackathons (created by user)
        $userHackathons = Hackathon::where('created_by', $user->id)
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        // Get hackathons user is participating in
        $participatingHackathons = Hackathon::whereHas('teams', function ($query) use ($user) {
            $query->where('leader_id', $user->id)
                ->orWhereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
        })->orderBy('start_date', 'desc')->get();

        // Get user's teams
        $userTeams = HackathonTeam::where('leader_id', $user->id)
            ->orWhereHas('members', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['hackathon', 'leader', 'members.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistics
        $stats = [
            'total_hackathons' => $userHackathons->total(),
            'participating_hackathons' => $participatingHackathons->count(),
            'total_teams' => $userTeams->count(),
            'upcoming_hackathons' => Hackathon::where('start_date', '>', now())->count(),
        ];

        return view('dashboard.hackathons.index', compact(
            'userHackathons',
            'participatingHackathons',
            'userTeams',
            'stats'
        ));
    }

    /**
     * Display hackathons categories management.
     */
    public function categories()
    {
        // For now, we'll use technologies as categories
        $technologies = Hackathon::select('technologies')
            ->whereNotNull('technologies')
            ->get()
            ->pluck('technologies')
            ->flatten()
            ->unique()
            ->filter()
            ->sort()
            ->values();

        return view('dashboard.hackathons.categories', compact('technologies'));
    }

    /**
     * Display hackathons teams management.
     */
    public function teams()
    {
        $user = Auth::user();

        // Get teams where user is leader
        $leadingTeams = HackathonTeam::where('leader_id', $user->id)
            ->with(['hackathon', 'members.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get teams where user is member
        $memberTeams = HackathonTeam::whereHas('members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['hackathon', 'leader', 'members.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get public teams looking for members (available teams)
        $availableTeams = HackathonTeam::where('is_public', true)
            ->whereDoesntHave('members', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('leader_id', '!=', $user->id)
            ->with(['hackathon', 'leader'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get received invitations
        $receivedInvitations = \App\Models\HackathonTeamInvitation::where('invitee_id', $user->id)
            ->where('status', 'pending')
            ->with(['team', 'inviter', 'team.hackathon'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get sent join requests
        $sentJoinRequests = \App\Models\HackathonTeamJoinRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with(['team', 'team.hackathon'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get received join requests (for teams where user is leader)
        $receivedJoinRequests = \App\Models\HackathonTeamJoinRequest::whereHas('team', function ($query) use ($user) {
            $query->where('leader_id', $user->id);
        })
            ->where('status', 'pending')
            ->with(['team', 'team.hackathon', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.hackathons.teams', compact(
            'leadingTeams',
            'memberTeams',
            'availableTeams',
            'receivedInvitations',
            'sentJoinRequests',
            'receivedJoinRequests'
        ));
    }

    /**
     * Display hackathons registration management.
     */
    public function registrations()
    {
        $user = Auth::user();

        // Get hackathons user can register for
        $availableHackathons = Hackathon::where('is_active', true)
            ->where('registration_deadline', '>', now())
            ->where('start_date', '>', now())
            ->whereDoesntHave('teams', function ($query) use ($user) {
                $query->where('leader_id', $user->id)
                    ->orWhereHas('members', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
            ->orderBy('registration_deadline')
            ->get();

        // Get registration requests (public teams looking for members)
        $registrationRequests = HackathonTeam::where('is_public', true)
            ->whereDoesntHave('members', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('leader_id', '!=', $user->id)
            ->with(['hackathon', 'leader'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.hackathons.registrations', compact(
            'availableHackathons',
            'registrationRequests'
        ));
    }

    /**
     * Create a new team for a hackathon.
     */
    public function createTeam(Request $request)
    {
        try {
            $request->validate([
                'hackathon_id' => 'required|exists:hackathons,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $hackathon = Hackathon::findOrFail($request->hackathon_id);
            $user = Auth::user();

            // Check if registration is open
            if (! $hackathon->isRegistrationOpen()) {
                return back()->with('error', 'Registration is closed for this hackathon.');
            }

            // Check if user can participate
            if (! $hackathon->canUserParticipate($user)) {
                return back()->with('error', 'You cannot participate in this hackathon.');
            }

            // Check if team name is unique for this hackathon
            if (HackathonTeam::where('hackathon_id', $hackathon->id)
                ->where('name', $request->name)
                ->exists()) {
                return back()->with('error', 'Team name already exists for this hackathon.');
            }

            // Create team
            $team = HackathonTeam::create([
                'hackathon_id' => $hackathon->id,
                'name' => $request->name,
                'leader_id' => $user->id,
                'description' => $request->description,
                'is_public' => $request->has('is_public') ? (bool) $request->is_public : true,
            ]);

            \Log::info('Team created successfully', ['team_id' => $team->id, 'name' => $team->name]);

            return back()->with('success', 'Team created successfully!');
        } catch (\Exception $e) {
            \Log::error('Error creating team', ['error' => $e->getMessage(), 'request' => $request->all()]);

            return back()->with('error', 'Failed to create team: '.$e->getMessage());
        }
    }

    /**
     * Join a team.
     */
    public function joinTeam(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:hackathon_teams,id',
        ]);

        $team = HackathonTeam::findOrFail($request->team_id);
        $user = Auth::user();

        // Check if hackathon allows registration
        if (! $team->hackathon->isRegistrationOpen()) {
            return back()->with('error', 'Registration is closed for this hackathon.');
        }

        // Check if user can participate
        if (! $team->hackathon->canUserParticipate($user)) {
            return back()->with('error', 'You are already participating in this hackathon.');
        }

        // Check if team has available spots
        if (! $team->hasAvailableSpots()) {
            return back()->with('error', 'Team is full.');
        }

        // Check if team is public and accepts join requests
        if (! $team->is_public) {
            return back()->with('error', 'This team is not accepting join requests.');
        }

        // Check if user has already sent a join request
        $existingRequest = \App\Models\HackathonTeamJoinRequest::where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'You have already sent a join request to this team.');
        }

        try {
            // Create join request
            \App\Models\HackathonTeamJoinRequest::create([
                'team_id' => $team->id,
                'user_id' => $user->id,
                'status' => 'pending',
            ]);

            return back()->with('success', 'Join request sent to the team leader!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel a join request.
     */
    public function cancelJoinRequest(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:hackathon_team_join_requests,id',
        ]);

        $joinRequest = \App\Models\HackathonTeamJoinRequest::findOrFail($request->request_id);
        $user = Auth::user();

        // Check if user owns this request
        if ($joinRequest->user_id !== $user->id) {
            return back()->with('error', 'Unauthorized.');
        }

        // Only allow canceling if status is pending
        if ($joinRequest->status !== 'pending') {
            return back()->with('error', 'Cannot cancel this request.');
        }

        $joinRequest->delete();

        return back()->with('success', 'Join request cancelled.');
    }

    /**
     * Leave a team.
     */
    public function leaveTeam(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:hackathon_teams,id',
        ]);

        $team = HackathonTeam::with(['hackathon', 'members'])->findOrFail($request->team_id);
        $user = Auth::user();

        // Check if user is team leader
        if ($team->leader_id === $user->id) {
            return back()->with('error', 'Team leaders cannot leave their team. Transfer leadership first.');
        }

        try {
            $team->removeMember($user);

            return back()->with('success', 'Successfully left the team!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Disband team (leader only).
     */
    public function disbandTeam(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:hackathon_teams,id',
        ]);

        $team = HackathonTeam::with(['hackathon', 'members'])->findOrFail($request->team_id);
        $user = Auth::user();

        // Check if user is team leader
        if ($team->leader_id !== $user->id) {
            return back()->with('error', 'Only team leaders can disband the team.');
        }

        // Check if hackathon has started
        if ($team->hackathon->start_date <= now()) {
            return back()->with('error', 'Cannot disband team. The hackathon has already started.');
        }

        // Check if hackathon has ended
        if ($team->hackathon->end_date <= now()) {
            return back()->with('error', 'Cannot disband team. The hackathon has ended.');
        }

        try {
            // Remove all members first
            $team->members()->delete();

            // Delete all pending invitations
            \App\Models\HackathonTeamInvitation::where('team_id', $team->id)->delete();

            // Delete all pending join requests
            \App\Models\HackathonTeamJoinRequest::where('team_id', $team->id)->delete();

            // Delete the team
            $team->delete();

            return redirect()->route('home.hackathons.teams')->with('success', 'Team disbanded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to disband team: '.$e->getMessage());
        }
    }

    /**
     * Update team settings.
     */
    public function updateTeam(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:hackathon_teams,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team = HackathonTeam::findOrFail($request->team_id);
        $user = Auth::user();

        // Check if user is team leader
        if ($team->leader_id !== $user->id) {
            return back()->with('error', 'Only team leaders can update team settings.');
        }

        $team->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_public' => $request->has('is_public') ? (bool) $request->is_public : true,
        ]);

        return back()->with('success', 'Team updated successfully!');
    }

    /**
     * Show team details page.
     */
    public function showTeam($teamId)
    {
        $team = HackathonTeam::with(['hackathon', 'leader', 'members.user', 'hackathon.creator', 'project.files.uploader'])
            ->findOrFail($teamId);

        $user = Auth::user();
        $isLeader = $team->leader_id === $user->id;
        $isMember = $team->hasUser($user);

        // Get pending invitations for this team
        $pendingInvitations = HackathonTeamInvitation::where('team_id', $teamId)
            ->where('status', 'pending')
            ->with(['inviter', 'invitee'])
            ->get();

        // Get user's pending invitations
        $userPendingInvitations = HackathonTeamInvitation::where('invitee_id', $user->id)
            ->where('status', 'pending')
            ->with(['team', 'inviter', 'team.hackathon'])
            ->get();

        // Get join requests for this team (if leader)
        $joinRequests = [];
        if ($isLeader) {
            $joinRequests = \App\Models\HackathonTeamJoinRequest::where('team_id', $teamId)
                ->where('status', 'pending')
                ->with(['user'])
                ->get();
        }

        return view('dashboard.hackathons.team-show', compact('team', 'isLeader', 'isMember', 'pendingInvitations', 'userPendingInvitations', 'joinRequests'));
    }

    /**
     * Send team invitation.
     */
    public function inviteMember(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:hackathon_teams,id',
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|exists:users,id',
            'message' => 'nullable|string|max:500',
        ]);

        $team = HackathonTeam::with('hackathon')->findOrFail($request->team_id);
        $user = Auth::user();

        // Check if user is team leader
        if ($team->leader_id !== $user->id) {
            return back()->with('error', 'Only team leaders can invite members.');
        }

        // Calculate available spots
        $currentMembers = $team->members->count();
        $availableSpots = $team->hackathon->max_team_size - $currentMembers;

        // Check if invitations exceed available spots
        if (count($request->user_ids) > $availableSpots) {
            return back()->with('error', "You can only invite up to {$availableSpots} more member(s)");
        }

        $successCount = 0;
        $errors = [];

        foreach ($request->user_ids as $userId) {
            $invitee = User::findOrFail($userId);

            // Check if user is already in the team
            if ($team->hasUser($invitee)) {
                $errors[] = "{$invitee->name} is already a member of this team";

                continue;
            }

            // Check if there's already a pending invitation
            if (HackathonTeamInvitation::where('team_id', $team->id)
                ->where('invitee_id', $userId)
                ->where('status', 'pending')
                ->exists()) {
                $errors[] = "Invitation already sent to {$invitee->name}";

                continue;
            }

            // Create invitation
            HackathonTeamInvitation::create([
                'team_id' => $team->id,
                'inviter_id' => $user->id,
                'invitee_id' => $userId,
                'message' => $request->message,
                'status' => 'pending',
            ]);

            $successCount++;
        }

        if (count($errors) > 0 && $successCount === 0) {
            return back()->with('error', implode(', ', $errors));
        }

        if ($successCount > 0) {
            $message = $successCount === 1
                ? 'Invitation sent successfully!'
                : "{$successCount} invitations sent successfully!";

            if (count($errors) > 0) {
                $message .= ' Note: '.implode(', ', $errors);
            }

            return back()->with('success', $message);
        }

        return back()->with('error', implode(', ', $errors));
    }

    /**
     * Accept team invitation.
     */
    public function acceptInvitation(Request $request)
    {
        $request->validate([
            'invitation_id' => 'required|exists:hackathon_team_invitations,id',
        ]);

        $invitation = HackathonTeamInvitation::findOrFail($request->invitation_id);
        $user = Auth::user();

        // Check if invitation is for current user
        if ($invitation->invitee_id !== $user->id) {
            return back()->with('error', 'Unauthorized.');
        }

        // Check if invitation is still pending
        if ($invitation->status !== 'pending') {
            return back()->with('error', 'Invitation has already been responded to.');
        }

        $team = $invitation->team;

        // Check if team still has available spots
        if (! $team->hasAvailableSpots()) {
            return back()->with('error', 'Team is now full.');
        }

        // Check if user is still available for this hackathon
        if (! $team->hackathon->canUserParticipate($user)) {
            return back()->with('error', 'You cannot participate in this hackathon.');
        }

        try {
            // Add user to team
            $team->addMember($user);

            // Update invitation status
            $invitation->update([
                'status' => 'accepted',
                'responded_at' => now(),
            ]);

            return back()->with('success', 'You have joined the team!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to join team: '.$e->getMessage());
        }
    }

    /**
     * Reject team invitation.
     */
    public function rejectInvitation(Request $request)
    {
        $request->validate([
            'invitation_id' => 'required|exists:hackathon_team_invitations,id',
        ]);

        $invitation = HackathonTeamInvitation::findOrFail($request->invitation_id);
        $user = Auth::user();

        // Check if invitation is for current user
        if ($invitation->invitee_id !== $user->id) {
            return back()->with('error', 'Unauthorized.');
        }

        // Check if invitation is still pending
        if ($invitation->status !== 'pending') {
            return back()->with('error', 'Invitation has already been responded to.');
        }

        // Update invitation status
        $invitation->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Invitation rejected.');
    }

    /**
     * Delete invitation.
     */
    public function deleteInvitation(Request $request)
    {
        $request->validate([
            'invitation_id' => 'required|exists:hackathon_team_invitations,id',
        ]);

        $invitation = HackathonTeamInvitation::findOrFail($request->invitation_id);
        $user = Auth::user();

        // Check if user is team leader or invitation recipient
        $isLeader = $invitation->team->leader_id === $user->id;
        $isRecipient = $invitation->invitee_id === $user->id;

        if (! $isLeader && ! $isRecipient) {
            return back()->with('error', 'Unauthorized.');
        }

        // Delete invitation
        $invitation->delete();

        return back()->with('success', 'Invitation deleted.');
    }

    /**
     * Accept join request.
     */
    public function acceptJoinRequest(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:hackathon_team_join_requests,id',
        ]);

        $joinRequest = \App\Models\HackathonTeamJoinRequest::findOrFail($request->request_id);
        $user = Auth::user();

        // Check if user is team leader
        if ($joinRequest->team->leader_id !== $user->id) {
            return back()->with('error', 'Only team leaders can accept join requests.');
        }

        // Check if request is still pending
        if ($joinRequest->status !== 'pending') {
            return back()->with('error', 'Request has already been responded to.');
        }

        // Check if team has available spots
        if (! $joinRequest->team->hasAvailableSpots()) {
            $joinRequest->update([
                'status' => 'rejected',
                'responded_at' => now(),
            ]);

            return back()->with('error', 'Team is full.');
        }

        // Add user to team
        $joinRequest->team->addMember(User::findOrFail($joinRequest->user_id));

        // Update request status
        $joinRequest->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Join request accepted.');
    }

    /**
     * Reject join request.
     */
    public function rejectJoinRequest(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:hackathon_team_join_requests,id',
        ]);

        $joinRequest = \App\Models\HackathonTeamJoinRequest::findOrFail($request->request_id);
        $user = Auth::user();

        // Check if user is team leader
        if ($joinRequest->team->leader_id !== $user->id) {
            return back()->with('error', 'Only team leaders can reject join requests.');
        }

        // Check if request is still pending
        if ($joinRequest->status !== 'pending') {
            return back()->with('error', 'Request has already been responded to.');
        }

        // Update request status
        $joinRequest->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Join request rejected.');
    }
}
