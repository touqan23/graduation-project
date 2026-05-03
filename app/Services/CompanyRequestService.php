<?php

namespace App\Services;

class CompanyRequestService
{
    /**
     * جلب كافة البيانات اللازمة لبناء فورم التسجيل في الفرونت إند.
     */
    public function getRegistrationFormData(): array
    {
        return [
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
                // تم تغيير النوع إلى select وربطه بتابع الخيارات
                ['name' => 'sector', 'type' => 'select', 'label' => 'القطاع', 'options' => $this->getSectorOptions()],
                ['name' => 'company_description', 'type' => 'textarea', 'label' => 'وصف الشركة'],
                ['name' => 'requested_area', 'type' => 'number', 'label' => 'المساحة المطلوبة (م2)'],
                ['name' => 'setup_preference', 'type' => 'select', 'label' => 'تفضيلات التجهيز', 'options' => $this->getSetupOptions()],
            ],
        ];
    }

    /**
     * جلب خيارات التجهيز المتاحة
     */
    private function getSetupOptions(): array
    {
        return [
            ['label' => 'جناح مجهز | Equipped Booth', 'value' => 'Equipped Booth'],
            ['label' => 'جناح غير مجهز | Not Equipped Booth', 'value' => 'Not Equipped Booth'],
            ['label' => 'مساحة فقط | Row Space Only', 'value' => 'Row Space Only'],
            ['label' => 'أكشاك بيع ab | Kiosk AB', 'value' => 'Kiosk AB'],
            ['label' => 'أكشاك بيع cd | Kiosk CD', 'value' => 'Kiosk CD'],
        ];
    }

    /**
     * جلب خيارات القطاعات يدوياً بالعربي والإنجليزي
     */
    private function getSectorOptions(): array
    {
        return [
            ['label' => 'الصناعات الغذائية ', 'value' => 'Food Industries'],
            ['label' => 'الثقافة والفنون ', 'value' => 'Culture & Arts'],
            ['label' => 'الحرف اليدوية ', 'value' => 'Handicrafts'],
            ['label' => 'الصناعة والتقنية ', 'value' => 'Industry & Technology'],
            ['label' => 'الأنشطة الرياضية ', 'value' => 'Sports Activities'],
            ['label' => 'التعليم والبرامج ', 'value' => 'Education & Programs'],
            ['label' => 'الصحة والجمال ', 'value' => 'Health & Beauty'],
            ['label' => 'البناء والعقارات ', 'value' => 'Construction & Real Estate'],
            ['label' => 'الطاقة المتجددة ', 'value' => 'Renewable Energy'],
            ['label' => 'السيارات والمركبات ', 'value' => 'Automobiles & Vehicles'],
            ['label' => 'الموضة والأزياء ', 'value' => 'Fashion & Clothing'],
            ['label' => 'الإعلام والاتصالات ', 'value' => 'Media & Communications'],
        ];
    }
}
