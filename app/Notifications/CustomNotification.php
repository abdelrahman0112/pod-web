<?php

namespace App\Notifications;

use App\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CustomNotification extends Notification
{
    use Queueable;

    protected NotificationType $type;

    protected array $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(NotificationType $type, array $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return $this->data;
    }
}
