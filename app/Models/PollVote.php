<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'option_index',
        'voted_at',
    ];

    protected $casts = [
        'voted_at' => 'datetime',
    ];

    /**
     * Get the post (poll) this vote belongs to.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user who voted.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a vote for a poll.
     */
    public static function recordVote($postId, $userId, $optionIndex): bool
    {
        // Check if user already voted
        $existingVote = static::where('post_id', $postId)->where('user_id', $userId)->first();

        if ($existingVote) {
            return false; // User already voted
        }

        static::create([
            'post_id' => $postId,
            'user_id' => $userId,
            'option_index' => $optionIndex,
            'voted_at' => now(),
        ]);

        return true;
    }

    /**
     * Check if user has voted on a poll.
     */
    public static function hasUserVoted($postId, $userId): bool
    {
        return static::where('post_id', $postId)->where('user_id', $userId)->exists();
    }

    /**
     * Get vote counts for a poll.
     */
    public static function getVoteCounts($postId): array
    {
        return static::where('post_id', $postId)
            ->selectRaw('option_index, COUNT(*) as count')
            ->groupBy('option_index')
            ->pluck('count', 'option_index')
            ->toArray();
    }
}
