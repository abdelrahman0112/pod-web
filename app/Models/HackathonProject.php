<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HackathonProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'title',
        'description',
        'url',
    ];

    /**
     * Get the team this project belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(HackathonTeam::class, 'team_id');
    }

    /**
     * Get all files for this project.
     */
    public function files(): HasMany
    {
        return $this->hasMany(HackathonProjectFile::class, 'project_id');
    }

    /**
     * Get the current file count.
     */
    public function getFileCountAttribute(): int
    {
        return $this->files()->count();
    }

    /**
     * Get the total size of all files.
     */
    public function getTotalSizeAttribute(): int
    {
        return $this->files()->sum('file_size');
    }

    /**
     * Check if project can accept more files.
     */
    public function canAcceptFiles(): bool
    {
        return $this->file_count < 5;
    }

    /**
     * Get total size in MB.
     */
    public function getTotalSizeInMB(): float
    {
        return round($this->total_size / 1048576, 2);
    }
}
