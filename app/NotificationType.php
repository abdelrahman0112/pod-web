<?php

namespace App;

/**
 * Enum defining all notification types in the system.
 */
enum NotificationType: string
{
    // Posts & Social Interactions
    case POST_LIKED = 'post_liked';
    case COMMENT_ADDED = 'comment_added';
    case COMMENT_REPLY = 'comment_reply';
    case POST_MENTION = 'post_mention';

    // Events
    case EVENT_CREATED = 'event_created';
    case EVENT_REGISTERED = 'event_registered';
    case EVENT_WAITLISTED = 'event_waitlisted';
    case EVENT_CONFIRMED = 'event_confirmed';
    case EVENT_REMINDER = 'event_reminder';
    case EVENT_CANCELLED = 'event_cancelled';

    // Jobs
    case JOB_POSTED = 'job_posted';
    case JOB_APPLICATION_RECEIVED = 'job_application_received';
    case JOB_APPLICATION_ACCEPTED = 'job_application_accepted';
    case JOB_APPLICATION_REJECTED = 'job_application_rejected';
    case JOB_APPLICATION_INTERVIEW = 'job_application_interview';

    // Hackathons
    case HACKATHON_CREATED = 'hackathon_created';
    case HACKATHON_REGISTERED = 'hackathon_registered';
    case HACKATHON_TEAM_INVITED = 'hackathon_team_invited';
    case HACKATHON_TEAM_INVITATION_ACCEPTED = 'hackathon_team_invitation_accepted';
    case HACKATHON_TEAM_JOIN_REQUEST = 'hackathon_team_join_request';
    case HACKATHON_TEAM_JOIN_ACCEPTED = 'hackathon_team_join_accepted';
    case HACKATHON_WINNER = 'hackathon_winner';

    // Internships
    case INTERNSHIP_APPLICATION_RECEIVED = 'internship_application_received';
    case INTERNSHIP_APPLICATION_ACCEPTED = 'internship_application_accepted';
    case INTERNSHIP_APPLICATION_REJECTED = 'internship_application_rejected';

    // Profile & Account
    case PROFILE_VIEWED = 'profile_viewed';
    case ACCOUNT_ACTIVATED = 'account_activated';
    case ACCOUNT_SUSPENDED = 'account_suspended';

    // Messages
    case MESSAGE_RECEIVED = 'message_received';

    // Admin Actions
    case ADMIN_APPROVED = 'admin_approved';
    case ADMIN_REJECTED = 'admin_rejected';

    /**
     * Get the icon for this notification type.
     */
    public function icon(): string
    {
        return match ($this) {
            // Posts
            self::POST_LIKED => 'heroicon-o-heart',
            self::COMMENT_ADDED, self::COMMENT_REPLY => 'heroicon-o-chat-bubble-left-right',
            self::POST_MENTION => 'heroicon-o-at-symbol',

            // Events
            self::EVENT_CREATED, self::EVENT_REGISTERED, self::EVENT_CONFIRMED, self::EVENT_REMINDER => 'heroicon-o-calendar',
            self::EVENT_WAITLISTED => 'heroicon-o-clock',
            self::EVENT_CANCELLED => 'heroicon-o-x-circle',

            // Jobs
            self::JOB_POSTED, self::JOB_APPLICATION_RECEIVED => 'heroicon-o-briefcase',
            self::JOB_APPLICATION_ACCEPTED => 'heroicon-o-check-circle',
            self::JOB_APPLICATION_REJECTED => 'heroicon-o-x-circle',
            self::JOB_APPLICATION_INTERVIEW => 'heroicon-o-video-camera',

            // Hackathons
            self::HACKATHON_CREATED, self::HACKATHON_REGISTERED => 'heroicon-o-trophy',
            self::HACKATHON_TEAM_INVITED, self::HACKATHON_TEAM_JOIN_REQUEST => 'heroicon-o-user-group',
            self::HACKATHON_TEAM_INVITATION_ACCEPTED, self::HACKATHON_TEAM_JOIN_ACCEPTED => 'heroicon-o-check-circle',
            self::HACKATHON_WINNER => 'heroicon-o-star',

            // Internships
            self::INTERNSHIP_APPLICATION_RECEIVED => 'heroicon-o-academic-cap',
            self::INTERNSHIP_APPLICATION_ACCEPTED => 'heroicon-o-check-circle',
            self::INTERNSHIP_APPLICATION_REJECTED => 'heroicon-o-x-circle',

            // Profile
            self::PROFILE_VIEWED => 'heroicon-o-eye',
            self::ACCOUNT_ACTIVATED => 'heroicon-o-check-circle',
            self::ACCOUNT_SUSPENDED => 'heroicon-o-shield-exclamation',

            // Messages
            self::MESSAGE_RECEIVED => 'heroicon-o-envelope',

            // Admin
            self::ADMIN_APPROVED => 'heroicon-o-shield-check',
            self::ADMIN_REJECTED => 'heroicon-o-shield-x',
        };
    }

    /**
     * Get the icon color for this notification type (always white for overlay icons).
     */
    public function iconColor(): string
    {
        return 'text-white';
    }

    /**
     * Get the overlay icon circle background color for this notification type.
     * Each notification type has a unique, meaningful color.
     */
    public function overlayBackgroundColor(): string
    {
        return match ($this) {
            // Posts - Social interactions (warm colors)
            self::POST_LIKED => 'bg-red-500',           // Red for love/like
            self::COMMENT_ADDED => 'bg-blue-500',       // Blue for comments
            self::COMMENT_REPLY => 'bg-cyan-500',       // Cyan for replies
            self::POST_MENTION => 'bg-violet-500',      // Violet for mentions

            // Events - Calendar & attendance (orange/yellow spectrum)
            self::EVENT_CREATED => 'bg-orange-500',     // Orange for new events
            self::EVENT_REGISTERED => 'bg-amber-500',   // Amber for registration
            self::EVENT_CONFIRMED => 'bg-green-500',    // Green for confirmation
            self::EVENT_REMINDER => 'bg-yellow-500',    // Yellow for reminders
            self::EVENT_WAITLISTED => 'bg-yellow-600',  // Dark yellow for waiting
            self::EVENT_CANCELLED => 'bg-red-600',      // Dark red for cancellation

            // Jobs - Career opportunities (blue/green spectrum)
            self::JOB_POSTED => 'bg-blue-600',          // Blue for job posts
            self::JOB_APPLICATION_RECEIVED => 'bg-sky-500',     // Sky blue for received
            self::JOB_APPLICATION_ACCEPTED => 'bg-emerald-500', // Emerald for accepted
            self::JOB_APPLICATION_REJECTED => 'bg-rose-500',    // Rose for rejected
            self::JOB_APPLICATION_INTERVIEW => 'bg-indigo-500', // Indigo for interview

            // Hackathons - Competition & teamwork (purple spectrum)
            self::HACKATHON_CREATED => 'bg-purple-600',         // Purple for creation
            self::HACKATHON_REGISTERED => 'bg-purple-500',      // Purple for registration
            self::HACKATHON_TEAM_INVITED => 'bg-indigo-500',    // Indigo for invites
            self::HACKATHON_TEAM_INVITATION_ACCEPTED => 'bg-teal-500',  // Teal for acceptance
            self::HACKATHON_TEAM_JOIN_REQUEST => 'bg-violet-500',       // Violet for requests
            self::HACKATHON_TEAM_JOIN_ACCEPTED => 'bg-cyan-500',        // Cyan for join accepted
            self::HACKATHON_WINNER => 'bg-yellow-500',          // Gold for winner

            // Internships - Education & training (teal/green spectrum)
            self::INTERNSHIP_APPLICATION_RECEIVED => 'bg-teal-600',     // Teal for received
            self::INTERNSHIP_APPLICATION_ACCEPTED => 'bg-green-500',    // Green for accepted
            self::INTERNSHIP_APPLICATION_REJECTED => 'bg-orange-600',   // Orange for rejected

            // Profile - Personal account (gray/green/red)
            self::PROFILE_VIEWED => 'bg-slate-500',     // Slate for views
            self::ACCOUNT_ACTIVATED => 'bg-lime-500',   // Lime for activation
            self::ACCOUNT_SUSPENDED => 'bg-red-700',    // Dark red for suspension

            // Messages - Communication (blue)
            self::MESSAGE_RECEIVED => 'bg-blue-500',    // Blue for messages

            // Admin - Moderation (green/red)
            self::ADMIN_APPROVED => 'bg-emerald-600',   // Emerald for approval
            self::ADMIN_REJECTED => 'bg-red-600',       // Red for rejection
        };
    }

    /**
     * Get the background color for this notification type.
     */
    public function backgroundColor(): string
    {
        return match ($this) {
            // Posts
            self::POST_LIKED => 'bg-red-50',
            self::COMMENT_ADDED, self::COMMENT_REPLY, self::POST_MENTION => 'bg-blue-50',

            // Events
            self::EVENT_CREATED, self::EVENT_REGISTERED, self::EVENT_CONFIRMED, self::EVENT_REMINDER => 'bg-orange-50',
            self::EVENT_WAITLISTED => 'bg-yellow-50',
            self::EVENT_CANCELLED => 'bg-red-50',

            // Jobs
            self::JOB_POSTED, self::JOB_APPLICATION_RECEIVED, self::JOB_APPLICATION_INTERVIEW => 'bg-blue-50',
            self::JOB_APPLICATION_ACCEPTED => 'bg-emerald-50',
            self::JOB_APPLICATION_REJECTED => 'bg-red-50',

            // Hackathons
            self::HACKATHON_CREATED, self::HACKATHON_REGISTERED, self::HACKATHON_WINNER => 'bg-purple-50',
            self::HACKATHON_TEAM_INVITED, self::HACKATHON_TEAM_JOIN_REQUEST => 'bg-indigo-50',
            self::HACKATHON_TEAM_INVITATION_ACCEPTED, self::HACKATHON_TEAM_JOIN_ACCEPTED => 'bg-emerald-50',

            // Internships
            self::INTERNSHIP_APPLICATION_RECEIVED => 'bg-teal-50',
            self::INTERNSHIP_APPLICATION_ACCEPTED => 'bg-emerald-50',
            self::INTERNSHIP_APPLICATION_REJECTED => 'bg-red-50',

            // Profile
            self::PROFILE_VIEWED => 'bg-gray-50',
            self::ACCOUNT_ACTIVATED => 'bg-emerald-50',
            self::ACCOUNT_SUSPENDED => 'bg-red-50',

            // Messages
            self::MESSAGE_RECEIVED => 'bg-blue-50',

            // Admin
            self::ADMIN_APPROVED => 'bg-emerald-50',
            self::ADMIN_REJECTED => 'bg-red-50',
        };
    }

    /**
     * Get the category for this notification type.
     */
    public function category(): string
    {
        return match ($this) {
            self::POST_LIKED, self::COMMENT_ADDED, self::COMMENT_REPLY, self::POST_MENTION => 'social',
            self::EVENT_CREATED, self::EVENT_REGISTERED, self::EVENT_WAITLISTED, self::EVENT_CONFIRMED, self::EVENT_REMINDER, self::EVENT_CANCELLED => 'events',
            self::JOB_POSTED, self::JOB_APPLICATION_RECEIVED, self::JOB_APPLICATION_ACCEPTED, self::JOB_APPLICATION_REJECTED, self::JOB_APPLICATION_INTERVIEW => 'jobs',
            self::HACKATHON_CREATED, self::HACKATHON_REGISTERED, self::HACKATHON_TEAM_INVITED, self::HACKATHON_TEAM_INVITATION_ACCEPTED, self::HACKATHON_TEAM_JOIN_REQUEST, self::HACKATHON_TEAM_JOIN_ACCEPTED, self::HACKATHON_WINNER => 'hackathons',
            self::INTERNSHIP_APPLICATION_RECEIVED, self::INTERNSHIP_APPLICATION_ACCEPTED, self::INTERNSHIP_APPLICATION_REJECTED => 'internships',
            self::PROFILE_VIEWED, self::ACCOUNT_ACTIVATED, self::ACCOUNT_SUSPENDED => 'account',
            self::MESSAGE_RECEIVED => 'messages',
            self::ADMIN_APPROVED, self::ADMIN_REJECTED => 'admin',
        };
    }

    /**
     * Check if this notification type requires email notification.
     */
    public function requiresEmail(): bool
    {
        return match ($this) {
            self::EVENT_REMINDER, self::EVENT_CONFIRMED, self::EVENT_CANCELLED,
            self::JOB_APPLICATION_ACCEPTED, self::JOB_APPLICATION_REJECTED,
            self::HACKATHON_TEAM_INVITED, self::HACKATHON_WINNER,
            self::INTERNSHIP_APPLICATION_ACCEPTED, self::INTERNSHIP_APPLICATION_REJECTED,
            self::ACCOUNT_ACTIVATED, self::ACCOUNT_SUSPENDED => true,
            default => false,
        };
    }

    /**
     * Get the action icon (small overlay icon) - unique for each notification type.
     */
    public function actionIcon(): string
    {
        return match ($this) {
            // Posts - Social interactions
            self::POST_LIKED => 'ri-heart-fill',
            self::COMMENT_ADDED => 'ri-chat-3-fill',
            self::COMMENT_REPLY => 'ri-reply-fill',
            self::POST_MENTION => 'ri-at-line',

            // Events - Calendar & attendance
            self::EVENT_CREATED => 'ri-calendar-event-fill',
            self::EVENT_REGISTERED => 'ri-calendar-check-fill',
            self::EVENT_CONFIRMED => 'ri-checkbox-circle-fill',
            self::EVENT_REMINDER => 'ri-alarm-fill',
            self::EVENT_WAITLISTED => 'ri-time-fill',
            self::EVENT_CANCELLED => 'ri-calendar-close-fill',

            // Jobs - Career opportunities
            self::JOB_POSTED => 'ri-briefcase-fill',
            self::JOB_APPLICATION_RECEIVED => 'ri-mail-open-fill',
            self::JOB_APPLICATION_ACCEPTED => 'ri-thumb-up-fill',
            self::JOB_APPLICATION_REJECTED => 'ri-thumb-down-fill',
            self::JOB_APPLICATION_INTERVIEW => 'ri-vidicon-fill',

            // Hackathons - Competition & teamwork
            self::HACKATHON_CREATED => 'ri-trophy-fill',
            self::HACKATHON_REGISTERED => 'ri-user-add-fill',
            self::HACKATHON_TEAM_INVITED => 'ri-team-fill',
            self::HACKATHON_TEAM_INVITATION_ACCEPTED => 'ri-user-follow-fill',
            self::HACKATHON_TEAM_JOIN_REQUEST => 'ri-user-shared-fill',
            self::HACKATHON_TEAM_JOIN_ACCEPTED => 'ri-group-fill',
            self::HACKATHON_WINNER => 'ri-medal-fill',

            // Internships - Education & training
            self::INTERNSHIP_APPLICATION_RECEIVED => 'ri-file-text-fill',
            self::INTERNSHIP_APPLICATION_ACCEPTED => 'ri-hand-heart-fill',
            self::INTERNSHIP_APPLICATION_REJECTED => 'ri-file-damage-fill',

            // Profile - Personal account
            self::PROFILE_VIEWED => 'ri-eye-fill',
            self::ACCOUNT_ACTIVATED => 'ri-user-smile-fill',
            self::ACCOUNT_SUSPENDED => 'ri-user-forbid-fill',

            // Messages - Communication
            self::MESSAGE_RECEIVED => 'ri-message-3-fill',

            // Admin - Moderation
            self::ADMIN_APPROVED => 'ri-shield-check-fill',
            self::ADMIN_REJECTED => 'ri-shield-cross-fill',
        };
    }

    /**
     * Build deep link URL based on notification data.
     */
    public function buildUrl(array $data): string
    {
        $baseUrl = config('app.url');

        return match ($this) {
            // Posts
            self::POST_LIKED, self::COMMENT_ADDED, self::COMMENT_REPLY, self::POST_MENTION => $baseUrl.'/posts/'.($data['post_id'] ?? ''),

            // Events
            self::EVENT_CREATED, self::EVENT_REGISTERED, self::EVENT_WAITLISTED, self::EVENT_CONFIRMED,
            self::EVENT_REMINDER, self::EVENT_CANCELLED => $baseUrl.'/events/'.($data['event_id'] ?? ''),

            // Jobs
            self::JOB_POSTED => $baseUrl.'/jobs/'.($data['job_id'] ?? ''),
            self::JOB_APPLICATION_RECEIVED, self::JOB_APPLICATION_ACCEPTED, self::JOB_APPLICATION_REJECTED,
            self::JOB_APPLICATION_INTERVIEW => $baseUrl.'/jobs/my-applications'.($data['application_id'] ? '/'.$data['application_id'] : ''),

            // Hackathons
            self::HACKATHON_CREATED, self::HACKATHON_REGISTERED, self::HACKATHON_TEAM_INVITED,
            self::HACKATHON_TEAM_INVITATION_ACCEPTED, self::HACKATHON_TEAM_JOIN_REQUEST,
            self::HACKATHON_TEAM_JOIN_ACCEPTED, self::HACKATHON_WINNER => $baseUrl.'/hackathons/'.($data['hackathon_id'] ?? ''),

            // Internships
            self::INTERNSHIP_APPLICATION_RECEIVED, self::INTERNSHIP_APPLICATION_ACCEPTED,
            self::INTERNSHIP_APPLICATION_REJECTED => $baseUrl.'/internships/my-applications',

            // Profile
            self::PROFILE_VIEWED => $baseUrl.'/profile/'.($data['viewer_id'] ?? ''),

            // Messages
            self::MESSAGE_RECEIVED => $baseUrl.'/chatify'.($data['sender_id'] ?? ''),

            // Admin
            self::ADMIN_APPROVED, self::ADMIN_REJECTED => $baseUrl.'/home',

            // Default
            default => $baseUrl.'/home',
        };
    }
}
