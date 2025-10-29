<?php

namespace App\Models;

use Chatify\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class ChFavorite extends Model
{
    use UUID;

    protected $fillable = [
        'user_id',
        'favorite_id',
    ];
}
