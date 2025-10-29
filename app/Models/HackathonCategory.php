<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HackathonCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'description',
    ];

    /**
     * Get all hackathons in this category.
     */
    public function hackathons(): HasMany
    {
        return $this->hasMany(Hackathon::class);
    }

    /**
     * Get active hackathons in this category.
     */
    public function activeHackathons(): HasMany
    {
        return $this->hasMany(Hackathon::class);
    }

    /**
     * Scope for active categories.
     */
    public function scopeActive($query)
    {
        return $query;
    }

    /**
     * Scope for ordered categories.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }
}
