<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_notifications',
        'push_notifications',
        'in_app_notifications',
        'notification_types',
    ];

    protected function casts(): array
    {
        return [
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'in_app_notifications' => 'boolean',
            'notification_types' => 'array',
        ];
    }

    /**
     * Get the user that owns these preferences.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user has a specific notification type enabled.
     */
    public function hasTypeEnabled(string $type): bool
    {
        // If notification_types is null, check global preferences
        if (is_null($this->notification_types)) {
            return true;
        }

        // Check if specific type is enabled
        return $this->notification_types[$type] ?? true;
    }
}
