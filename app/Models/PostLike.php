<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
    ];

    /**
     * Get the post this like belongs to.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user who liked the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Toggle like for a user on a post.
     */
    public static function toggleLike($postId, $userId): bool
    {
        $like = static::where('post_id', $postId)->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();

            return false; // unliked
        } else {
            static::create(['post_id' => $postId, 'user_id' => $userId]);

            return true; // liked
        }
    }

    /**
     * Check if user has liked a post.
     */
    public static function hasUserLiked($postId, $userId): bool
    {
        return static::where('post_id', $postId)->where('user_id', $userId)->exists();
    }
}
