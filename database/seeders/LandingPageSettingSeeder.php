<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\globalsetting;
use Illuminate\Support\Facades\Cache;

class LandingPageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // --- Section: Hero ---
            [
                'key' => 'hero_title',
                'value' => 'وعز الشرق أوله دمشق',
                'type' => 'text',
                'group' => 'hero'
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'معرض دمشق الدولي - منصة الأعمال والتجارة الرائدة في المنطقة',
                'type' => 'text',
                'group' => 'hero'
            ],
            [
                'key' => 'hero_image',
                'value' => 'main_pages/ZN2DcSVZCG8J95aX8K6aIAjJyVdwiYQuhWTo6mZ9.png',
                'type' => 'image',
                'group' => 'hero'
            ],

            // --- Section: About ---
            [
                'key' => 'about_title',
                'value' => 'عن المعرض',
                'type' => 'text',
                'group' => 'about'
            ],
            [
                'key' => 'about_content',
                'value' => 'معرض دمشق الدولي هو أحد أقدم وأعرق المعارض في المنطقة، يجمع تحت سقف واحد أبرز الشركات والعلامات التجارية من مختلف القطاعات الاقتصادية منذ تأسيسه، يشكل المعرض منصة حيوية لتبادل الخبرات وإبرام الصفقات التجارية، مساهماً في تعزيز الاقتصاد الوطني وفتح آفاق جديدة للتعاون الإقليمي والدولي.',
                'type' => 'text',
                'group' => 'about'
            ],
            [
                'key' => 'about_image',
                'value' => 'main_pages/9fJ8RLxLCfB7wsZvE9VKQpjCP2j1VFkFYDiulRn7.jpg',
                'type' => 'image',
                'group' => 'about'
            ],

            // --- Section: Stats (الكرت الأصفر) ---
            [
                'key' => 'stats_title',
                'value' => 'معرض دمشق الدولي 62',
                'type' => 'text',
                'group' => 'stats'
            ],
            [
                'key' => 'stats_description',
                'value' => 'تستضيف الدورة الثانية والستون من معرض دمشق الدولي أكثر من 1,500 عارض من مختلف أنحاء العالم، حيث يتم عرض أحدث المنتجات والخدمات في قطاعات الصناعة، التجارة، التكنولوجيا، والزراعة.',
                'type' => 'text',
                'group' => 'stats'
            ],
            [
                'key' => 'stats_image',
                'value' => 'main_pages/k49sh0buhciCH26Qpio29DfjBzRQ1j1ncRqacahQ.jpg',
                'type' => 'image',
                'group' => 'stats'
            ],

            // --- Section: Video Experience ---
            [
                'key' => 'experience_video_url',
                'value' => 'https://youtu.be/_LdRcEHRKhY?si=wgQzM0MbO1NTF_jn',
                'type' => 'text',
                'group' => 'experience'
            ],

            // --- Section: Date & Location ---
            [
                'key' => 'event_date',
                'value' => '27 آب - 5 أيلول 2025',
                'type' => 'text',
                'group' => 'event_info'
            ],
            [
                'key' => 'event_location',
                'value' => 'أرض المعارض، دمشق',
                'type' => 'text',
                'group' => 'event_info'
            ],
        ];

        foreach ($settings as $setting) {
            globalsetting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // مسح الكاش لضمان وصول التحديثات الجديدة فوراً للفرونت (ملاحظة 5)
        Cache::forget('global_settings');
    }
}
