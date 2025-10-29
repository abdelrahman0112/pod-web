<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HackathonTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'hackathon_id',
        'name',
        'leader_id',
        'description',
        'is_public',  // Whether team is open to join requests from others
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get the hackathon this team belongs to.
     */
    public function hackathon()
    {
        return $this->belongsTo(Hackathon::class);
    }

    /**
     * Get the team leader.
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Get all team members (excluding leader).
     */
    public function members()
    {
        return $this->hasMany(HackathonTeamMember::class, 'team_id');
    }

    /**
     * Get all team members including leader.
     */
    public function allMembers()
    {
        return collect([$this->leader])->merge($this->members->pluck('user'));
    }

    /**
     * Get member count including leader.
     */
    public function getMemberCountAttribute(): int
    {
        return $this->members()->count() + 1; // +1 for leader
    }

    /**
     * Check if team has available spots.
     */
    public function hasAvailableSpots(): bool
    {
        return $this->member_count < $this->hackathon->max_team_size;
    }

    /**
     * Check if team meets minimum size requirement.
     */
    public function meetsMinimumSize(): bool
    {
        return $this->member_count >= $this->hackathon->min_team_size;
    }

    /**
     * Check if team is complete and ready to participate.
     */
    public function isReady(): bool
    {
        return $this->meetsMinimumSize() && $this->member_count <= $this->hackathon->max_team_size;
    }

    /**
     * Check if user is in this team.
     */
    public function hasUser($user): bool
    {
        if ($this->leader_id === $user->id) {
            return true;
        }

        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Add a member to the team.
     */
    public function addMember($user): HackathonTeamMember
    {
        if ($this->hasUser($user)) {
            throw new \Exception('User is already in this team.');
        }

        if (! $this->hasAvailableSpots()) {
            throw new \Exception('Team is full.');
        }

        return $this->members()->create(['user_id' => $user->id]);
    }

    /**
     * Remove a member from the team.
     */
    public function removeMember($user): bool
    {
        if ($this->leader_id === $user->id) {
            throw new \Exception('Cannot remove team leader.');
        }

        return $this->members()->where('user_id', $user->id)->delete() > 0;
    }

    /**
     * Get the project for this team.
     */
    public function project(): HasOne
    {
        return $this->hasOne(HackathonProject::class, 'team_id');
    }

    /**
     * Check if team has a project.
     */
    public function hasProject(): bool
    {
        return $this->project()->exists();
    }
}
