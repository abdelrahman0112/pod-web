<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HackathonTeamInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'inviter_id',
        'invitee_id',
        'status',
        'message',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    /**
     * Get the team this invitation is for.
     */
    public function team()
    {
        return $this->belongsTo(HackathonTeam::class, 'team_id');
    }

    /**
     * Get the user who sent the invitation.
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    /**
     * Get the user who was invited.
     */
    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }

    /**
     * Check if invitation is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if invitation is accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if invitation is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
