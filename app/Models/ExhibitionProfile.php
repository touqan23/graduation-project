<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExhibitionProfile extends Model
{
    protected $guarded = [];

    protected $casts = [
        'name'        => 'array',
        'address'     => 'array',
        'bio'         => 'array',
        'start_date'  => 'date',
        'end_date'    => 'date',
    ];

}
