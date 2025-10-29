<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'platform',
        'shared_at',
    ];

    protected $casts = [
        'shared_at' => 'datetime',
    ];

    /**
     * Get the post this share belongs to.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user who shared the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a share for a post.
     */
    public static function recordShare($postId, $userId, $platform = null): void
    {
        static::create([
            'post_id' => $postId,
            'user_id' => $userId,
            'platform' => $platform,
            'shared_at' => now(),
        ]);
    }

    /**
     * Get share count for a post.
     */
    public static function getShareCount($postId): int
    {
        return static::where('post_id', $postId)->count();
    }
}
