<?php
namespace App\Actions\Company;

use App\Models\CompanyRequest;
use App\Models\PricingTier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StoreCompanyRequestAction
{
    public function execute(array $data): CompanyRequest
    {
        return DB::transaction(function () use ($data) {

            // 1. جلب خطة التسعير مباشرة باستخدام الـ slug القادم من الفورم
            // الـ slug الآن يطابق تماماً الـ setup_preference (مثلاً: 'Equipped Booth')
            $tier = PricingTier::where('company_type', $data['foreign_local'])
                ->where('slug', $data['setup_preference'])
                ->firstOrFail();

            // 2. التحقق من الحد الأدنى للمساحة بناءً على القواعد المخزنة
            if ($tier->min_area && $data['requested_area'] < $tier->min_area) {
                throw new \Exception("المساحة المطلوبة أقل من الحد الأدنى لهذا النوع ({$tier->min_area} متر مربع).");
            }

            // 3. الحسابات المالية (تثبيت السعر وقت الطلب)
            $totalPrice = $data['requested_area'] * $tier->unit_price;
            $requiredDeposit = $totalPrice * 0.25;

            // 4. تخزين الطلب في قاعدة البيانات
            return CompanyRequest::create([
                'foreign_local'       => $data['foreign_local'],
                'company_name'        => $data['company_name'],
                'responsible_name'    => $data['responsible_name'],
                'job_title'           => $data['job_title'],
                'email'               => $data['email'],
                'phone'               => $data['phone'],
                'nationality'         => $data['nationality'],
                'commercial_register' => $data['commercial_register'],
                'address'             => $data['address'],
                'sector'              => $data['sector'],
                'company_description' => $data['company_description'],
                'requested_area'      => $data['requested_area'],
                'setup_preference'    => $data['setup_preference'], // القيمة النصية المختارة
                'terms_accepted_at'   => now(),
                'total_price'         => $totalPrice,
                'required_deposit'    => $requiredDeposit,
                'payment_due_date'    => Carbon::now()->addHours(48), // مهلة السداد الأولية
                //'pricing_tier_id'     => $tier->id, // مرجع للخطة السعرية المستخدمة
            ]);
        });
    }
}
