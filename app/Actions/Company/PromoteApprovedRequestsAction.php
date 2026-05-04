<?php

namespace App\Actions\Company;

use App\Models\User;
use App\Models\Company;
use App\Models\Sector;
use App\Models\CompanyRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PromoteApprovedRequestsAction
{

    public function execute(): Collection
    {
        $pendingPromotions = CompanyRequest::where('request_status', 'approved')
            ->whereIn('payment_status', ['paid', 'partial_paid'])
            ->whereDoesntHave('company')
            ->get();

        $promotedCompanies = collect();

        foreach ($pendingPromotions as $request) {
            try {
                $company = DB::transaction(function () use ($request) {

                    if (User::withTrashed()->where('email', $request->email)->exists()) {
                        Log::warning("Skipping request {$request->id}: Email already exists.");
                        return null;
                    }

                    $sector = Sector::where('name->ar', $request->sector)
                        ->orWhere('name->en', $request->sector)
                        ->first();

                    $user = User::create([
                        'name'        => $request->company_name, // نأخذ نسخة نصية للاسم
                        'email'       => $request->email,
                        'phonenumber' => $request->phone,
                        'password'    => Hash::make($request->phone),
                    ]);

                    $user->assignRole('company');

                    return Company::create([
                        'user_id'            => $user->id,
                        'company_request_id' => $request->id,
                        'name'               => $request->getTranslations('company_name'),
                        'bio'                => $request->getTranslations('company_description'),
                        'nationality'        => $request->getTranslations('nationality'),
                        'responsible_person' => $request->responsible_name,
                        'sector'             => $request->sector,
                        'sector_id'          => $sector ? $sector->id : 1,
                        'address'            => $request->getTranslations('address'),
                        'final_area'         => $request->requested_area,
                        'booth_type'         => $request->setup_preference,
                        'is_active'          => true,
                    ]);
                });

                if ($company) {
                    $promotedCompanies->push($company);
                }

            } catch (\Exception $e) {
                Log::error("Error promoting request ID {$request->id}: " . $e->getMessage());
            }
        }

        return $promotedCompanies;
    }
}
