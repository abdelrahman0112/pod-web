<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HackathonTeamInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $teamId = $request->get('team_id');

        $excludeUserIds = [Auth::id()];

        // If team_id is provided, exclude team members and pending invitations
        if ($teamId) {
            // Exclude team members
            $teamMemberIds = \App\Models\HackathonTeamMember::where('team_id', $teamId)
                ->pluck('user_id')
                ->toArray();
            $excludeUserIds = array_merge($excludeUserIds, $teamMemberIds);

            // Exclude pending invitations
            $pendingInvitationIds = HackathonTeamInvitation::where('team_id', $teamId)
                ->where('status', 'pending')
                ->pluck('invitee_id')
                ->toArray();
            $excludeUserIds = array_merge($excludeUserIds, $pendingInvitationIds);

            // Also exclude the team leader
            $teamLeaderId = \App\Models\HackathonTeam::where('id', $teamId)
                ->value('leader_id');
            if ($teamLeaderId) {
                $excludeUserIds[] = $teamLeaderId;
            }
        }

        $users = User::where('is_active', true)
            ->whereNotIn('id', array_unique($excludeUserIds))
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%");
                });
            })
            ->limit(20)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name ?: $user->email,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar' => $user->avatar,
                    'avatar_color' => $user->avatar_color,
                ];
            });

        return response()->json([
            'users' => $users,
        ]);
    }
}
