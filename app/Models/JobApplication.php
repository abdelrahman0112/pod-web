<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_listing_id',
        'user_id',
        'cover_letter',
        'additional_info',
        'status',
        'admin_notes',
        'status_updated_at',
    ];

    protected $casts = [
        'additional_info' => 'array',
        'status_updated_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Update status_updated_at when status changes
        static::updating(function ($application) {
            if ($application->isDirty('status')) {
                $application->status_updated_at = now();
            }
        });
    }

    /**
     * Get the job listing this application is for.
     */
    public function jobListing()
    {
        return $this->belongsTo(JobListing::class);
    }

    /**
     * Get the user who submitted this application.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if application is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if application has been reviewed.
     */
    public function isReviewed(): bool
    {
        return $this->status === 'reviewed';
    }

    /**
     * Check if interview is scheduled.
     */
    public function hasInterviewScheduled(): bool
    {
        return $this->status === 'interview_scheduled';
    }

    /**
     * Check if user has been interviewed.
     */
    public function isInterviewed(): bool
    {
        return $this->status === 'interviewed';
    }

    /**
     * Check if application is accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if application is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if application is withdrawn.
     */
    public function isWithdrawn(): bool
    {
        return $this->status === 'withdrawn';
    }

    /**
     * Mark application as reviewed.
     */
    public function markAsReviewed(?string $adminNotes = null): void
    {
        $this->update([
            'status' => 'reviewed',
            'admin_notes' => $adminNotes ?: $this->admin_notes,
        ]);
    }

    /**
     * Schedule interview for this application.
     */
    public function scheduleInterview(?string $adminNotes = null): void
    {
        $this->update([
            'status' => 'interview_scheduled',
            'admin_notes' => $adminNotes ?: $this->admin_notes,
        ]);
    }

    /**
     * Mark as interviewed.
     */
    public function markAsInterviewed(?string $adminNotes = null): void
    {
        $this->update([
            'status' => 'interviewed',
            'admin_notes' => $adminNotes ?: $this->admin_notes,
        ]);
    }

    /**
     * Accept this application.
     */
    public function accept(?string $adminNotes = null): void
    {
        $this->update([
            'status' => 'accepted',
            'admin_notes' => $adminNotes ?: $this->admin_notes,
        ]);
    }

    /**
     * Reject this application.
     */
    public function reject(?string $adminNotes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'admin_notes' => $adminNotes ?: $this->admin_notes,
        ]);
    }

    /**
     * Withdraw this application.
     */
    public function withdraw(): void
    {
        $this->update(['status' => 'withdrawn']);
    }

    /**
     * Get status color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'reviewed' => 'blue',
            'interview_scheduled' => 'purple',
            'interviewed' => 'indigo',
            'accepted' => 'green',
            'rejected' => 'red',
            'withdrawn' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get status display text.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending Review',
            'reviewed' => 'Reviewed',
            'interview_scheduled' => 'Interview Scheduled',
            'interviewed' => 'Interviewed',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'withdrawn' => 'Withdrawn',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * Get status icon for UI.
     */
    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'clock',
            'reviewed' => 'eye',
            'interview_scheduled' => 'calendar',
            'interviewed' => 'user-check',
            'accepted' => 'check-circle',
            'rejected' => 'x-circle',
            'withdrawn' => 'arrow-left',
            default => 'help-circle',
        };
    }

    /**
     * Scope for applications by status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for pending applications.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for reviewed applications.
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    /**
     * Scope for accepted applications.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope for rejected applications.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for recent applications.
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
