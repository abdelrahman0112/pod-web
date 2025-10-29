<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HackathonTeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'role',
        'skills',
        'joined_at',
    ];

    protected $casts = [
        'skills' => 'array',
        'joined_at' => 'datetime',
    ];

    /**
     * Get the team this member belongs to.
     */
    public function team()
    {
        return $this->belongsTo(HackathonTeam::class, 'team_id');
    }

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Automatically set joined_at when creating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            if (! $member->joined_at) {
                $member->joined_at = now();
            }
        });
    }
}
