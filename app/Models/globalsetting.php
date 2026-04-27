<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class globalsetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    protected $casts = [
        'value' => 'string',
    ];
}
