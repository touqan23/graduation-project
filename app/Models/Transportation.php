<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transportation extends Model
{
    protected $fillable = [
        'name',
        'image',
        'google_maps_url',
    ];

    protected $casts = [
        'name'            => 'array',
    ];

}
