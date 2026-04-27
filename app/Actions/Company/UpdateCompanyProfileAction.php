<?php

namespace App\Actions\Company;

use App\Actions\BaseAction;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;

class UpdateCompanyProfileAction extends BaseAction
{
    /**
     * تحديث بيانات الشركة والمستخدم المرتبط بها
     */
    public function execute(Company $company, array $userData, array $companyData, $logo = null): Company
    {
        // نستخدم الـ executeAction الذي سيتكفل بالـ Transaction والـ Activity Log
        return $this->executeAction(function () use ($company, $userData, $companyData, $logo) {

            // 1. تحديث بيانات المستخدم (الاسم، الإيميل، إلخ)
            // بما أن هذا الموديل ليس هو الـ Result النهائي، سنحفظ تغييراته يدوياً للـ Log
            $company->user->update($userData);

            $userChanges = [];
            if ($company->user->wasChanged()) {
                $userChanges = [
                    'attributes' => $company->user->getChanges(),
                    'old' => array_intersect_key($company->user->getOriginal(), $company->user->getChanges())
                ];
            }

            // 2. معالجة اللوغو الخاص بالشركة
            if ($logo) {
                // حذف اللوغو القديم من S3 إذا وجد
                if ($company->logo) {
                    Storage::disk('s3')->delete($company->logo);
                }
                // رفع اللوغو الجديد
                $companyData['logo'] = $logo->store('companies/logos', 's3');
            }

            // 3. تحديث بيانات الشركة
            // ملاحظة: الـ BaseAction سيلقط تغييرات هذا الموديل تلقائياً لأنه الـ Result
            $company->update($companyData);

            // 4. دمج تغييرات المستخدم في مصفوفة الخصائص لتظهر في سجل واحد
            // سنستخدم متغير داخلي لتمريره للـ recordActivity لاحقاً
            $this->customProperties = !empty($userChanges) ? ['user_updates' => $userChanges] : [];

            // إرجاع الموديل مع بيانات المستخدم المحدثة
            return $company->fresh(['user']);

        }, "تم تحديث بيانات الملف الشخصي (الشركة والمستخدم)", $this->customProperties ?? [], false);
    }
}
