<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitionProfile extends Model
{
    protected $guarded = [];

    protected $casts = [
        'name'    => 'array',
        'address' => 'array',
        'bio'     => 'array',
    ];
}
