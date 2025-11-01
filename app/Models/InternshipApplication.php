<?php

namespace App\Models;

use App\InternshipApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'internship_id',
        'full_name',
        'email',
        'phone',
        'university',
        'major',
        'graduation_status',
        'experience',
        'interest_categories',
        'availability_start',
        'availability_end',
        'motivation',
        'status',
        'admin_response',
        'admin_notes',
        'admin_id',
    ];

    protected function casts(): array
    {
        return [
            'interest_categories' => 'array',
            'availability_start' => 'date',
            'availability_end' => 'date',
            'status' => InternshipApplicationStatus::class,
        ];
    }

    public function graduationStatus(): ?\App\GraduationStatus
    {
        return $this->graduation_status ? \App\GraduationStatus::from($this->graduation_status) : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function internship(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Internship::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            InternshipApplicationStatus::PENDING => 'yellow',
            InternshipApplicationStatus::REVIEWED => 'blue',
            InternshipApplicationStatus::ACCEPTED => 'green',
            InternshipApplicationStatus::REJECTED => 'red',
            default => 'gray',
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return $this->status?->getLabel() ?? 'Unknown';
    }
}
