<?php

namespace App\Http\Controllers;

use App\Actions\Visitor\GetCompaniesSearchBar;
use App\Actions\Visitor\GetCompanyDetailAction;
use App\Actions\Visitor\GetWelcomePageAction;
use App\Actions\Visitor\SendSupportMessageAction;
use App\Mail\SupportMessageMail;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VisitorController extends Controller
{
    use ApiResponse;
    public function welcomePage (GetWelcomePageAction $action): JsonResponse
    {
            $data = $action->execute();
            return $this->success($data, 'Welcome page content retrieved.');

    }

    // get companies by sector id or search by name
    public function getCompanies(Request $request, GetCompaniesSearchBar $action): JsonResponse
    {
        $validated = $request->validate([
            'sector_id' => ['sometimes', 'nullable', 'integer', 'exists:sectors,id'],
            'search'    => ['sometimes', 'nullable', 'string', 'max:100'],
        ]);

        $companies = $action->execute(
            sectorId: $validated['sector_id'] ?? null,
            search:   $validated['search']    ?? null,
        );

        return $this->success($companies,
            'Companies fetched successfully'
        );
    }

    public function companyDetails(int $id, GetCompanyDetailAction $action): JsonResponse
    {
        $company = $action->execute($id);

        return $this->success($company,
            'Company detail fetched successfully'
        );
    }

    public function SupportMessage(Request $request , SendSupportMessageAction $action): JsonResponse
    {
        $validated = $request->validate([
            'email'   => ['required','email'],
            'subject' => ['required','string','max:150'],
            'message' => ['required','string','min:10'],
        ]);

        try {
            $action->execute($validated['email'], $validated['subject'], $validated['message']);

            return $this->success(null, 'Your message has been successfully received. The support team will get back to you soon.');
        }catch (Exception $exception){
            return $this->error($exception->getMessage());
        }
    }
}
