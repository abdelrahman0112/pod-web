<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'company',
        'company_url',
        'start_date',
        'end_date',
        'is_current',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Get the user that owns the experience.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted duration
     */
    public function getDurationAttribute(): string
    {
        if ($this->is_current) {
            return $this->start_date->format('M Y') . ' - Present';
        }

        return $this->start_date->format('M Y') . ' - ' . $this->end_date->format('M Y');
    }
}
