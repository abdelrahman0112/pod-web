<?php

namespace App\Models;

use App\EventRegistrationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'ticket_code',
        'text_code',
        'checked_in',
        'checked_in_at',
    ];

    protected function casts(): array
    {
        return [
            'checked_in' => 'boolean',
            'checked_in_at' => 'datetime',
            'status' => EventRegistrationStatus::class,
        ];
    }

    /**
     * Get the event this registration belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who registered.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if registration is confirmed.
     */
    public function isConfirmed(): bool
    {
        return $this->status === EventRegistrationStatus::CONFIRMED;
    }

    /**
     * Check if registration is waitlisted.
     */
    public function isWaitlisted(): bool
    {
        return $this->status === EventRegistrationStatus::WAITLISTED;
    }

    /**
     * Check if registration is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === EventRegistrationStatus::CANCELLED;
    }

    /**
     * Check if user has checked in.
     */
    public function isCheckedIn(): bool
    {
        return $this->checked_in;
    }

    /**
     * Cancel this registration.
     */
    public function cancel(): void
    {
        $this->update([
            'status' => EventRegistrationStatus::CANCELLED,
        ]);

        // Promote someone from waitlist if event is full
        if ($this->event->waitlist_enabled) {
            $this->event->promoteFromWaitlist(1);
        }
    }

    /**
     * Check in the attendee.
     */
    public function checkIn(): void
    {
        $this->update([
            'checked_in' => true,
            'checked_in_at' => now(),
        ]);
    }

    /**
     * Get QR code URL for ticket.
     */
    public function getQrCodeUrl(): string
    {
        return route('events.verify-ticket', ['code' => $this->ticket_code]);
    }

    /**
     * Generate QR code data.
     */
    public function getQrCodeData(): array
    {
        return [
            'ticket_code' => $this->ticket_code,
            'event_id' => $this->event_id,
            'user_id' => $this->user_id,
            'event_title' => $this->event->title,
            'attendee_name' => $this->user->name,
            'verify_url' => $this->getQrCodeUrl(),
        ];
    }

    /**
     * Scope for confirmed registrations.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', EventRegistrationStatus::CONFIRMED);
    }

    /**
     * Scope for waitlisted registrations.
     */
    public function scopeWaitlisted($query)
    {
        return $query->where('status', EventRegistrationStatus::WAITLISTED);
    }

    /**
     * Scope for cancelled registrations.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', EventRegistrationStatus::CANCELLED);
    }

    /**
     * Scope for checked-in registrations.
     */
    public function scopeCheckedIn($query)
    {
        return $query->where('checked_in', true);
    }
}
