<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobApplicationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    protected $applicant;

    /**
     * Create a new notification instance.
     */
    public function __construct(JobApplication $application)
    {
        $this->application = $application;
        $this->applicant = $application->user;
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
            ->subject("New Job Application: {$this->application->jobListing->title}")
            ->greeting("Hello {$notifiable->name}!")
            ->line('You have received a new job application for your posted position.')
            ->line("**Job Position:** {$this->application->jobListing->title}")
            ->line("**Applicant:** {$this->applicant->name}")
            ->line('**Applied on:** '.$this->application->created_at->format('M j, Y'))
            ->action('Review Application', route('jobs.applications.show', [$this->application->jobListing, $this->application]))
            ->line('You can review the applicant\'s profile and application details by clicking the button above.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'job_id' => $this->application->job_listing_id,
            'job_title' => $this->application->jobListing->title,
            'application_id' => $this->application->id,
            'applicant_id' => $this->applicant->id,
            'applicant_name' => $this->applicant->name,
            'type' => 'job_application',
            'message' => "New job application received for {$this->application->jobListing->title}",
            'action_url' => route('jobs.applications.show', [$this->application->jobListing, $this->application]),
        ];
    }
}
