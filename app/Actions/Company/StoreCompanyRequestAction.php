<?php
namespace App\Actions\Company;

use App\Actions\General\TranslateTextAction;
use App\Models\CompanyRequest;
use App\Models\PricingTier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StoreCompanyRequestAction
{
    protected $translator;
    public function __construct(TranslateTextAction $translator)
    {
        $this->translator = $translator;
    }
    public function execute(array $data): CompanyRequest
    {
        return DB::transaction(function () use ($data) {


            $tier = PricingTier::where('company_type', $data['foreign_local'])
                ->where('slug', $data['setup_preference'])
                ->firstOrFail();
            if ($tier->min_area && $data['requested_area'] < $tier->min_area) {
                throw new \Exception("المساحة المطلوبة أقل من الحد الأدنى لهذا النوع ({$tier->min_area} متر مربع).");
            }
            $totalPrice = $data['requested_area'] * $tier->unit_price;
            $requiredDeposit = $totalPrice * 0.25;

            $translatedName = $this->translator->execute($data['company_name']);
            $translatedDescription = $this->translator->execute($data['company_description']);
            $translatedNationality = $this->translator->execute($data['nationality']);
            $translatedAddress = $this->translator->execute($data['address']);

            return CompanyRequest::create([
                'foreign_local'       => $data['foreign_local'],
                'company_name'        => $translatedName,
                'responsible_name'    => $data['responsible_name'],
                'job_title'           => $data['job_title'],
                'email'               => $data['email'],
                'phone'               => $data['phone'],
                'nationality'         => $translatedNationality,
                'commercial_register' => $data['commercial_register'],
                'address'             => $translatedAddress,
                'sector'              => $data['sector'],
                'company_description' => $translatedDescription,
                'requested_area'      => $data['requested_area'],
                'setup_preference'    => $data['setup_preference'], // القيمة النصية المختارة
                'terms_accepted_at'   => now(),
                'total_price'         => $totalPrice,
                'required_deposit'    => $requiredDeposit,
                'payment_due_date'    => Carbon::now()->addHours(48), // مهلة السداد الأولية
            ]);
        });
    }
}
