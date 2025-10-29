<?php

namespace App\Notifications;

use App\Models\HackathonTeam;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HackathonTeamInvite extends Notification implements ShouldQueue
{
    use Queueable;

    protected $team;

    protected $hackathon;

    protected $inviter;

    /**
     * Create a new notification instance.
     */
    public function __construct(HackathonTeam $team, User $inviter)
    {
        $this->team = $team;
        $this->hackathon = $team->hackathon;
        $this->inviter = $inviter;
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
        return (new MailMessage)
            ->subject("Team Invitation: {$this->hackathon->title}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("You've been invited to join a team for the {$this->hackathon->title} hackathon.")
            ->line("**Team:** {$this->team->name}")
            ->line("**Invited by:** {$this->inviter->name}")
            ->line("**Hackathon:** {$this->hackathon->title}")
            ->line('**Start Date:** '.$this->hackathon->start_date->format('M j, Y'))
            ->action('View Team Details', route('hackathons.teams.show', [$this->hackathon, $this->team]))
            ->line('Join the team and collaborate on an amazing project!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'hackathon_id' => $this->hackathon->id,
            'hackathon_title' => $this->hackathon->title,
            'team_id' => $this->team->id,
            'team_name' => $this->team->name,
            'inviter_id' => $this->inviter->id,
            'inviter_name' => $this->inviter->name,
            'type' => 'team_invite',
            'message' => "{$this->inviter->name} invited you to join team '{$this->team->name}' for {$this->hackathon->title}",
            'action_url' => route('hackathons.teams.show', [$this->hackathon, $this->team]),
        ];
    }
}
