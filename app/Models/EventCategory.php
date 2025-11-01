<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'category_id');
    }

    public function getColorAttribute($value)
    {
        return $value ?: '#3B82F6';
    }
}
