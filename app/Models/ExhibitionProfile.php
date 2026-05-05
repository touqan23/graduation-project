<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ExhibitionProfile extends Model
{
    use HasTranslations;
    protected $guarded = [];

    public $translatable = ['name','address','bio',];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
    ];



}
