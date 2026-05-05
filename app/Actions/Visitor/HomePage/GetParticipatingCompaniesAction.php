<?php

namespace App\Actions\Visitor\HomePage;

use App\Models\Company;

class GetParticipatingCompaniesAction
{
    // نمرر رقم الصفحة هنا
    public function execute(int $page)
    {
        return Company::query()
            ->where('is_active', true)
            ->with(['sector:id,name,hall_id', 'sector.hall:id,name'])
            ->select('id', 'name', 'logo', 'nationality', 'sector_id')
            ->paginate(3, ['*'], 'page', $page);
    }
}
