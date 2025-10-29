<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HackathonProjectFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'filename',
        'original_filename',
        'file_path',
        'mime_type',
        'file_size',
        'uploaded_by',
    ];

    /**
     * Get the project this file belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(HackathonProject::class, 'project_id');
    }

    /**
     * Get the user who uploaded the file.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get file size in MB.
     */
    public function getSizeInMB(): float
    {
        return round($this->file_size / 1048576, 2);
    }

    /**
     * Get file size formatted.
     */
    public function getFormattedSizeAttribute(): string
    {
        $mb = $this->getSizeInMB();
        if ($mb < 1) {
            return round($this->file_size / 1024, 2).' KB';
        }

        return $mb.' MB';
    }
}
