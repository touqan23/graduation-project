<?php
namespace App\Actions\Company;

use App\Models\User;
use App\Models\Company;
use App\Models\CompanyRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PromoteApprovedRequestsAction
{
    public function execute(): Collection
    {
        // 1. جلب الطلبات المستوفية للشروط
        $pendingPromotions = CompanyRequest::where('request_status', 'approved')
            ->whereIn('payment_status', ['paid', 'partial_paid'])
            ->whereDoesntHave('company')
            ->get();

        $promotedCompanies = collect();
        foreach ($pendingPromotions as $request) {
            try {
                $company = activity()->withoutLogs(function () use ($request) {
                    return DB::transaction(function () use ($request) {
                        if (User::withTrashed()->where('email', $request->email)->exists()) {
                            Log::warning("Skipping request {$request->id}: Email already exists.");
                            return null;
                        }
                        $user = User::create([
                            'name'        => $request->company_name,
                            'email'       => $request->email,
                            'phonenumber' => $request->phone,
                            'password'    => Hash::make($request->phone),
                        ]);
                        $user->assignRole('company');
                        return Company::create([
                            'user_id'            => $user->id,
                            'company_request_id' => $request->id,
                            'name'               => $request->company_name,
                            'responsible_person' => $request->responsible_name,
                            'sector'             => $request->sector,
                            'address'            => $request->address,
                            'bio'                => $request->company_description,
                            'final_area'         => $request->requested_area,
                            'booth_type'         => $request->setup_preference,
                        ]);
                    });
                });

                if ($company) {
                    $promotedCompanies->push($company);
                }

            } catch (\Exception $e) {
                Log::error("Critical error in PromoteApprovedRequestsAction for ID {$request->id}: " . $e->getMessage());
            }
        }

        return $promotedCompanies;
    }
}
