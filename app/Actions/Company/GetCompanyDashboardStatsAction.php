<?php

namespace App\Actions\Company;

use App\Models\Company;
use Spatie\Activitylog\Models\Activity;

class GetCompanyDashboardStatsAction
{
    public function execute(Company $company): array
    {
        $productsCount = $company->products()->count();
        $promotionsCount = $company->promotions()->count();
        $recentActivities = Activity::where('causer_id', auth()->id())
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($activity) {
                return [
                    'description' => $activity->description,
                    'time' => $activity->created_at->diffForHumans(), // مثل: "منذ ساعتين"
                ];
            });

        return [
            'products_count'    => $productsCount,
            'promotions_count'  => $promotionsCount,
            'status'            => 'مقبول',
            'recent_activities' => $recentActivities
        ];
    }
}
