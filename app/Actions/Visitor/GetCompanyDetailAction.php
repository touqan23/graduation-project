<?php
// app/Actions/Visitor/GetCompanyDetailAction.php
namespace App\Actions\Visitor;

use App\Models\Company;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GetCompanyDetailAction
{

    /**
     * جلب تفاصيل الشركة، منتجاتها، وعروضها بناءً على أنواع الأجنحة التي تمتلكها.
     */
    public function execute(int $companyId): array
    {
        //$locale = app()->getLocale();
        //$cacheKey = "company_full_profile_{$companyId}ك//_lang_{$locale}";
        //Cache::forget("companies:show:{$id}");

        return Cache::remember("companies:show:{$companyId}", now()->addMinutes(60), function () use ($companyId) { //($companyId, $locale)

            $baseCompany = Company::select('id', 'user_id')->findOrFail($companyId);
            $userCompanies = Company::where('user_id', $baseCompany->user_id)
                ->where('is_active', true)
                ->whereHas('request', function ($q) {
                    $q->where('request_status', 'approved')
                        ->where('payment_status', 'paid');
                })
                ->with([
                    'sectoor',
                    'booths.sector.hall',
                    'products.images',
                    'promotions' => fn($q) => $q->where('is_active', true),
                    'promotions.products.images'
                ])
                ->get();

            if ($userCompanies->isEmpty()) {
                return [];
            }

            // الشركة الأساسية التي سنأخذ منها معلومات البروفايل (الاسم، اللوغو، الوصف)
            $mainProfile = $userCompanies->firstWhere('id', $companyId) ?? $userCompanies->first();

            // 3. استخراج جميع الأجنحة من جميع طلبات الشركة
            $allBooths = $userCompanies->pluck('booths')->flatten();

            // التحقق من صلاحيات العرض والبيع
            $hasDisplay = $allBooths->contains('booth_type', 'display');
            $hasSales   = $allBooths->contains('booth_type', 'sales');

            // 4. بناء الـ Array النهائي مباشرة وإرجاعه (سيتم تكييش هذا الشكل النهائي)
            return [
                'id'          => $mainProfile->id,
                'name'        => $mainProfile->name,
                'nationality' => $mainProfile->nationality,
                'bio'         => $mainProfile->bio,
                'address'     => $mainProfile->address,
                'logo'        => $mainProfile->logo ? Storage::disk('s3')->url($mainProfile->logo) : null,
                'sector_id' => $mainProfile->sectoor->id ?? null,
                'sector_name' => $mainProfile->sectoor->name_ar ?? null,
                // تجميع مواقع الأجنحة المتعددة (إن وجدت)
                'locations'   => $allBooths->map(fn($booth) => [
                    'booth_number' => $booth->booth_number,
                    'booth_type'   => $booth->booth_type,
                    'hall_name'    => $booth->sector->hall->name ?? null,
                ])->unique('booth_number')->values()->toArray(),
                // ✅ إذا كان لديها كشك عرض -> نعرض المنتجات، وإلا مصفوفة فارغة
                'display_products' => $hasDisplay
                    ? $this->formatProducts($userCompanies->pluck('products')->flatten())
                    : [],
                // ✅ إذا كان لديها كشك مبيعات -> نعرض العروض (Promotions)، وإلا مصفوفة فارغة
                'sales_promotions' => $hasSales
                    ? $this->formatPromotions($userCompanies->pluck('promotions')->flatten())
                    : [],
            ];
        });
    }

    // ─── دوال الفورمات المساعدة (Helpers) ─────────────────────────

    private function formatProducts(Collection $products): array
    {
        return $products->map(function ($product) {
            // البحث عن الصورة الأساسية، أو أخذ أول صورة إن لم يتم تحديد أساسية
            $primaryImage = $product->images->where('is_primary', true)->first()
                ?? $product->images->first();

            return [
                'id'          => $product->id,
                'name'        => $product->name,
                'description' => $product->description,
                'price'       => $product->price,
                'image'       => $primaryImage ? Storage::disk('s3')->url($primaryImage->image_path) : null,
            ];
        })->toArray();
    }

    private function formatPromotions(Collection $promotions): array
    {
        return $promotions->map(function ($promo) {
            return [
                'id'                  => $promo->id,
                'type'                => $promo->type, // discount أو bundle
                'discount_percentage' => $promo->discount_percentage,
                'total_package_price' => $promo->total_package_price,
                // المنتجات المشمولة بالعرض
                'included_products'   => $this->formatProducts($promo->products)
            ];
        })->toArray();
    }
}
