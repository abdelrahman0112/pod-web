<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLiked extends Notification
{
    use Queueable;

    protected $post;

    protected $liker;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post, User $liker)
    {
        $this->post = $post;
        $this->liker = $liker;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // Only in-app notification for likes
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'post_content' => substr($this->post->content, 0, 100),
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'type' => 'post_liked',
            'message' => "{$this->liker->name} liked your post",
            'action_url' => route('posts.show', $this->post),
        ];
    }
}
