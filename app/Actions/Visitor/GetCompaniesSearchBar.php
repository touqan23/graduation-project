<?php
// app/Actions/Visitor/GetCompaniesSearchBar.php
namespace App\Actions\Visitor;

use App\Models\Company;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GetCompaniesSearchBar
{
    /**
     * يدعم ثلاث حالات:
     *  1. sector فقط          → cached
     *  3. search فقط          → no cache (dynamic, global search)
     */
    public function execute(?int $sectorId = null, ?string $search = null): Collection
    {
        // حالة البحث — دائماً بدون cache
        if ($search) {
            $companies = $this->fetchCompanies($sectorId, $search);
        }
        //Cache::forget("companies:by_sector:{$sectorId}");
        // حالة الـ sector فقط — مع cache
        elseif ($sectorId) {
            $companies = Cache::remember("companies:by_sector:{$sectorId}", now()->addMinutes(30),
                fn() => $this->fetchCompanies($sectorId)
            );
        } else {
            return collect();
        }

        return $companies->map(function ($company) {
            $booth = $company->booth;
            $sector = $booth->sector ?? null;

            return [
                'id'    => $company->id,
                'name'  => $company->name,
                'logo'  => $company->logo ? Storage::disk('s3')->url($company->logo) : null,
                'sector_name' => $sector->name_ar,
                'sector_id' => $sector->id,
                'location' => $booth ? [
                    'booth_id' => $booth->id,
                    'booth_number' => $booth->booth_number,
                    'booth_type' => $booth->booth_type,
                    'hall_name'   => $sector->hall->name ?? null,
                    'hall_id'     => $sector->hall->id ?? null,
                ] : null,
            ];
        });
    }

    private function fetchCompanies(?int $sectorId, ?string $search = null): Collection
    {
        return Company::query()
            ->active()
            // ✅ الآن يفلتر بالـ ID الصحيح
            ->when($sectorId, fn($q) => $q->where('sector_id', $sectorId))
            ->when($search,   fn($q) => $q->where('name', 'LIKE', "%{$search}%"))
            ->with([
                'booth:id,company_id,booth_number,booth_type,sector_id',
                'booth.sector:id,name_ar,name_en,hall_id',
                'booth.sector.hall:id,name',
            ])
            ->select([
                'id', 'name', 'logo'
            ])
            ->orderBy('name')
            ->get();
    }
}
