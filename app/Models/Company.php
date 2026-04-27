<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Company extends Model
{
    protected $guarded = [];
    use LogsActivity;

    protected $casts = [
        'is_active' => 'boolean',
        'final_area' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(CompanyRequest::class, 'company_request_id');
    }

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // لتسجيل كل الحقول التي تغيرت
            ->logOnlyDirty() // لتسجيل الحقول التي تغيرت قيمتها فعلياً فقط
            ->dontSubmitEmptyLogs(); // عدم تسجيل لوغ إذا لم يتغير شيء
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }
}
