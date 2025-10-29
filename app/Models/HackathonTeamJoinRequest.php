<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HackathonTeamJoinRequest extends Model
{
    protected $fillable = [
        'team_id',
        'user_id',
        'message',
        'status',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function team()
    {
        return $this->belongsTo(HackathonTeam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
