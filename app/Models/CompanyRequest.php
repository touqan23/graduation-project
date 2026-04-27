<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyRequest extends Model
{
    use HasFactory;

    protected $table = 'company_requests';

    protected $fillable = [
        'foreign_local',
        'company_name',
        'responsible_name',
        'job_title',
        'email',
        'phone',
        'nationality',
        'commercial_register',
        'address',
        'sector',
        'company_description',
        'requested_area',
        'setup_preference',
        'terms_accepted_at',
        'request_status',
        'payment_status',
        'total_price',
        'required_deposit',
        'paid_amount',
        'payment_due_date',
    ];

    protected $casts = [
        'terms_accepted_at' => 'datetime',
        'payment_due_date' => 'date',
        'total_price' => 'decimal:2',
        'required_deposit' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];


    // نوع الشركة
    const TYPE_FOREIGN = 'foreign';
    const TYPE_LOCAL = 'local';

    // نوع البوث
    const BOOTH_EQUIPPED = 'Equipped Booth';
    const BOOTH_NOT_EQUIPPED = 'Not Equipped Booth';
    const BOOTH_ROW = 'Row Space Only';
    const Lecture_Hall='Lecture Hall';
    const Kiosk = 'Kiosk';
    const Games_Area = 'Games Area';

    // حالة الطلب
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // حالة الدفع
    const PAYMENT_PAID = 'paid';
    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PARTIAL = 'partial_paid';

    // App\Models\CompanyRequest.php

    public function company()
    {
        return $this->hasOne(Company::class, 'company_request_id');
    }
}
