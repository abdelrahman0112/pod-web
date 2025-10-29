<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'location',
        'type',
        'application_deadline',
        'start_date',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(InternshipCategory::class);
    }
}
