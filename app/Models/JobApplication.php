<?php

namespace App\Models;

use App\JobApplicationStatus;
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
        'status' => JobApplicationStatus::class,
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
        return $this->status === JobApplicationStatus::PENDING;
    }

    /**
     * Check if application has been reviewed.
     */
    public function isReviewed(): bool
    {
        return $this->status === JobApplicationStatus::REVIEWED;
    }

    /**
     * Check if application is accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status === JobApplicationStatus::ACCEPTED;
    }

    /**
     * Check if application is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === JobApplicationStatus::REJECTED;
    }

    /**
     * Mark application as reviewed.
     */
    public function markAsReviewed(?string $adminNotes = null): void
    {
        $this->update([
            'status' => JobApplicationStatus::REVIEWED,
            'admin_notes' => $adminNotes ?: $this->admin_notes,
        ]);
    }

    /**
     * Accept this application.
     */
    public function accept(?string $adminNotes = null): void
    {
        $this->update([
            'status' => JobApplicationStatus::ACCEPTED,
            'admin_notes' => $adminNotes ?: $this->admin_notes,
        ]);
    }

    /**
     * Reject this application.
     */
    public function reject(?string $adminNotes = null): void
    {
        $this->update([
            'status' => JobApplicationStatus::REJECTED,
            'admin_notes' => $adminNotes ?: $this->admin_notes,
        ]);
    }

    /**
     * Get status color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            JobApplicationStatus::PENDING => 'yellow',
            JobApplicationStatus::REVIEWED => 'blue',
            JobApplicationStatus::ACCEPTED => 'green',
            JobApplicationStatus::REJECTED => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status display text.
     */
    public function getStatusDisplayAttribute(): string
    {
        return $this->status?->getLabel() ?? 'Unknown';
    }

    /**
     * Get status icon for UI.
     */
    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            JobApplicationStatus::PENDING => 'clock',
            JobApplicationStatus::REVIEWED => 'eye',
            JobApplicationStatus::ACCEPTED => 'check-circle',
            JobApplicationStatus::REJECTED => 'x-circle',
            default => 'help-circle',
        };
    }

    /**
     * Scope for applications by status.
     */
    public function scopeWithStatus($query, JobApplicationStatus|string $status)
    {
        $statusValue = $status instanceof JobApplicationStatus ? $status->value : $status;

        return $query->where('status', $statusValue);
    }

    /**
     * Scope for pending applications.
     */
    public function scopePending($query)
    {
        return $query->where('status', JobApplicationStatus::PENDING);
    }

    /**
     * Scope for reviewed applications.
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', JobApplicationStatus::REVIEWED);
    }

    /**
     * Scope for accepted applications.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', JobApplicationStatus::ACCEPTED);
    }

    /**
     * Scope for rejected applications.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', JobApplicationStatus::REJECTED);
    }

    /**
     * Scope for recent applications.
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
