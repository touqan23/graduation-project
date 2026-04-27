<?php
namespace App\Services;

use App\Models\PricingTier;

class CompanyRequestService
{
    /**
     * جلب كافة البيانات اللازمة لبناء فورم التسجيل في الفرونت إند.
     */
    public function getRegistrationFormData(): array
    {
        return [
            // 1. تعريف الحقول المطلوبة (Metadata) ليقوم الفرونت إند برسمها
            'form_fields' => [
                ['name' => 'foreign_local', 'type' => 'select', 'label' => 'نوع الشركة', 'options' => ['local' => 'محلي', 'foreign' => 'أجنبي']],
                ['name' => 'company_name', 'type' => 'text', 'label' => 'اسم الشركة'],
                ['name' => 'responsible_name', 'type' => 'text', 'label' => 'اسم الشخص المسؤول'],
                ['name' => 'job_title', 'type' => 'text', 'label' => 'المسمى الوظيفي'],
                ['name' => 'email', 'type' => 'email', 'label' => 'البريد الإلكتروني'],
                ['name' => 'phone', 'type' => 'tel', 'label' => 'رقم الهاتف'],
                ['name' => 'nationality', 'type' => 'text', 'label' => 'الجنسية'],
                ['name' => 'commercial_register', 'type' => 'text', 'label' => 'السجل التجاري'],
                ['name' => 'address', 'type' => 'textarea', 'label' => 'العنوان التفصيلي'],
                ['name' => 'sector', 'type' => 'text', 'label' => 'القطاع'],
                ['name' => 'company_description', 'type' => 'textarea', 'label' => 'وصف الشركة'],
                ['name' => 'requested_area', 'type' => 'number', 'label' => 'المساحة المطلوبة (م2)'],
                ['name' => 'setup_preference', 'type' => 'select', 'label' => 'تفضيلات التجهيز', 'options' => $this->getSetupOptions()],
            ],
        ];
    }

    /**
     * جلب خيارات التجهيز المتاحة من الـ Enum أو من جدول الأسعار
     */
    private function getSetupOptions(): array
    {
        return [
            ['label' => 'جناح مجهز', 'value' => 'Equipped Booth'],
            ['label' => 'جناح غير مجهز', 'value' => 'Not Equipped Booth'],
            ['label' => 'مساحة فقط', 'value' => 'Row Space Only'],
        ];

    }
}
