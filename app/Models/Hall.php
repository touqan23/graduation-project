<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Hall extends Model
{
    use HasTranslations;

    protected $fillable = ['name','total_area_sqm','description'];
    public $translatable = ['name'];

    protected $casts = [
        'name' => 'array',
    ];

    public function sectors() : HasMany
    {
        return $this->hasMany(Sector::class);
    }
}
