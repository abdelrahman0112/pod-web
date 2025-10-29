<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;

    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event, $type = 'reminder')
    {
        $this->event = $event;
        $this->type = $type; // reminder, 24h, 1h, starting
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $subject = $this->getSubject();
        $message = $this->getMessage();

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name}!")
            ->line($message)
            ->line('**Event Details:**')
            ->line('📅 **Date:** '.$this->event->start_date->format('M j, Y'))
            ->line('🕒 **Time:** '.$this->event->start_date->format('g:i A'))
            ->line('📍 **Location:** '.$this->event->location)
            ->action('View Event Details', route('events.show', $this->event))
            ->line('Thank you for being part of the People of Data community!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'event_start' => $this->event->start_date,
            'type' => $this->type,
            'message' => $this->getMessage(),
            'action_url' => route('events.show', $this->event),
        ];
    }

    /**
     * Get the notification subject based on type.
     */
    private function getSubject()
    {
        switch ($this->type) {
            case '24h':
                return "📅 Event Tomorrow: {$this->event->title}";
            case '1h':
                return "⏰ Event Starting Soon: {$this->event->title}";
            case 'starting':
                return "🚀 Event Starting Now: {$this->event->title}";
            default:
                return "📢 Event Reminder: {$this->event->title}";
        }
    }

    /**
     * Get the notification message based on type.
     */
    private function getMessage()
    {
        switch ($this->type) {
            case '24h':
                return "Your registered event '{$this->event->title}' is starting tomorrow. Don't forget to join us!";
            case '1h':
                return "Your registered event '{$this->event->title}' is starting in 1 hour. Get ready to join!";
            case 'starting':
                return "Your registered event '{$this->event->title}' is starting now! Join us immediately.";
            default:
                return "Don't forget about your upcoming event: '{$this->event->title}'.";
        }
    }
}
