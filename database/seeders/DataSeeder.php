<?php

namespace Database\Seeders;

use App\Models\Booth;
use App\Models\Company;
use App\Models\CompanyRequest;
use App\Models\EventRequest;
use App\Models\EventSlot;
use App\Models\Hall;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Sector;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $halls = [
            [
                'name'           => ['ar' => 'قاعة الثقافة', 'en' => 'Culture Hall'],
                'description'    => null,
                'total_area_sqm' => 2500.00,
                'floor'          => 'Ground'
            ],
            [
                'name'           => ['ar' => 'القاعة الرياضية', 'en' => 'Sports Hall'],
                'description'    => null,
                'total_area_sqm' => 3000.00,
                'floor'          => 'Ground'
            ],
            [
                'name'           => ['ar' => 'قاعة الغذائيات', 'en' => 'Food Industries Hall'],
                'description'    => null,
                'total_area_sqm' => 2000.00,
                'floor'          => 'Ground'
            ],
            [
                'name'           => ['ar' => 'قاعة الحرف اليدوية', 'en' => 'Handicrafts Hall'],
                'description'    => null,
                'total_area_sqm' => 1500.00,
                'floor'          => 'Ground'
            ],
            [
                'name'           => ['ar' => 'قاعة الصناعة والتقنية', 'en' => 'Industry & Technology Hall'],
                'description'    => null,
                'total_area_sqm' => 3500.00,
                'floor'          => 'Ground'
            ],
            [
                'name'           => ['ar' => 'قاعة الصحة والجمال', 'en' => 'Health & Beauty Hall'],
                'description'    => null,
                'total_area_sqm' => 1800.00,
                'floor'          => 'Ground'
            ],
        ];

        foreach ($halls as $hall) {
            Hall::firstOrCreate(
            // ابحث عن القاعة بناءً على الاسم باللغة الإنجليزية مثلاً لضمان الدقة
                ['name->en' => $hall['name']['en']],
                array_merge($hall, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }


        $halls = Hall::all()->mapWithKeys(function ($item) {
            return [$item->getTranslation('name', 'ar') => $item];
        });


        $sectors = [
            ['name' => ['ar' => 'الصناعات الغذائية', 'en' => 'Food Industries'], 'hall_id' => 1],
            ['name' => ['ar' => 'الثقافة والفنون', 'en' => 'Culture & Arts'], 'hall_id' => 1],
            ['name' => ['ar' => 'الحرف اليدوية', 'en' => 'Handicrafts'], 'hall_id' => 2],
            ['name' => ['ar' => 'الصناعة والتقنية', 'en' => 'Industry & Technology'], 'hall_id' => 2],
            ['name' => ['ar' => 'الأنشطة الرياضية', 'en' => 'Sports Activities'], 'hall_id' => 3],
            ['name' => ['ar' => 'التعليم والبرامج', 'en' => 'Education & Programs'], 'hall_id' => 3],
            ['name' => ['ar' => 'الصحة والجمال', 'en' => 'Health & Beauty'], 'hall_id' => 4],
            ['name' => ['ar' => 'البناء والعقارات', 'en' => 'Construction & Real Estate'], 'hall_id' => 4],
            ['name' => ['ar' => 'الطاقة المتجددة', 'en' => 'Renewable Energy'], 'hall_id' => 5],
            ['name' => ['ar' => 'السيارات والمركبات', 'en' => 'Automobiles & Vehicles'], 'hall_id' => 5],
            ['name' => ['ar' => 'الموضة والأزياء', 'en' => 'Fashion & Clothing'], 'hall_id' => 6],
            ['name' => ['ar' => 'الإعلام والاتصالات', 'en' => 'Media & Communications'], 'hall_id' => 6],
        ];

        foreach ($sectors as $sector) {
            Sector::firstOrCreate(
                ['name->en' => $sector['name']['en']],
                array_merge($sector, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // تعريف البوثات لكل قاعة
        $boothsConfig = [

            'قاعة الثقافة' => [
                ['booth_number' => 'C-A1', 'booth_type' => 'display',  'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 2],
                ['booth_number' => 'C-A2', 'booth_type' => 'display',  'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 2],
                ['booth_number' => 'C-A3', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 48.0,    'sector_id' => 2],
                ['booth_number' => 'C-A4', 'booth_type' => 'sales',    'equipment_type' => 'Not Equipped Booth',   'size_sqm' => 36.0,    'sector_id' => 2],
                ['booth_number' => 'C-A5', 'booth_type' => 'display',  'equipment_type' => 'Row Space Only',     'size_sqm' => 25.0,    'sector_id' => 2],
                ['booth_number' => 'C-B1', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 48.0,    'sector_id' => 2],
                ['booth_number' => 'C-B2', 'booth_type' => 'display',  'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 2],
                ['booth_number' => 'C-B3', 'booth_type' => 'sales',    'equipment_type' => 'Not Equipped Booth',   'size_sqm' => 36.0,    'sector_id' => 2],
                ['booth_number' => 'C-B4', 'booth_type' => 'display',  'equipment_type' => 'Row Space Only',     'size_sqm' => 25.0,    'sector_id' => 2],
                ['booth_number' => 'C-B5', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 60.0,    'sector_id' => 2],
            ],

            'القاعة الرياضية' => [
                ['booth_number' => 'S-A1', 'booth_type' => 'display',  'equipment_type' => 'Row Space Only',     'size_sqm' => 50.0,    'sector_id' => 5],
                ['booth_number' => 'S-A2', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 5],
                ['booth_number' => 'S-A3', 'booth_type' => 'display',  'equipment_type' => 'Equipped Booth',       'size_sqm' => 48.0,    'sector_id' => 5],
                ['booth_number' => 'S-A4', 'booth_type' => 'sales',    'equipment_type' => 'Not Equipped Booth',   'size_sqm' => 36.0,    'sector_id' => 5],
                ['booth_number' => 'S-A5', 'booth_type' => 'display',  'equipment_type' => 'Row Space Only',     'size_sqm' => 25.0,    'sector_id' => 5],
                ['booth_number' => 'S-B1', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 48.0,    'sector_id' => 5],
                ['booth_number' => 'S-B2', 'booth_type' => 'display',  'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 5],
                ['booth_number' => 'S-B3', 'booth_type' => 'sales',    'equipment_type' => 'Not Equipped Booth',   'size_sqm' => 36.0,    'sector_id' => 5],
                ['booth_number' => 'S-B4', 'booth_type' => 'display',  'equipment_type' => 'Row Space Only',     'size_sqm' => 25.0,    'sector_id' => 5],
                ['booth_number' => 'S-B5', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 60.0,    'sector_id' => 5],
            ],

            'قاعة الغذائيات' => [
                ['booth_number' => 'F-A1', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 1],
                ['booth_number' => 'F-A2', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 48.0,    'sector_id' => 1],
                ['booth_number' => 'F-A3', 'booth_type' => 'display',  'equipment_type' => 'Not Equipped Booth',   'size_sqm' => 36.0,    'sector_id' => 1],
                ['booth_number' => 'F-A4', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 60.0,    'sector_id' => 1],
                ['booth_number' => 'F-A5', 'booth_type' => 'display',  'equipment_type' => 'Row Space Only',     'size_sqm' => 25.0,    'sector_id' => 1],
                ['booth_number' => 'F-B1', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 48.0,    'sector_id' => 1],
                ['booth_number' => 'F-B2', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 1],
                ['booth_number' => 'F-B3', 'booth_type' => 'display',  'equipment_type' => 'Not Equipped Booth',   'size_sqm' => 36.0,    'sector_id' => 1],
                ['booth_number' => 'F-B4', 'booth_type' => 'sales',    'equipment_type' => 'Row Space Only',     'size_sqm' => 25.0,    'sector_id' => 1],
                ['booth_number' => 'F-B5', 'booth_type' => 'display',  'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 1],
            ],

            'قاعة الحرف اليدوية' => [
                ['booth_number' => 'H-A1', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 25.0,    'sector_id' => 3],
                ['booth_number' => 'H-A2', 'booth_type' => 'display',  'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 3],
                ['booth_number' => 'H-A3', 'booth_type' => 'sales',    'equipment_type' => 'Not Equipped Booth',   'size_sqm' => 25.0,    'sector_id' => 3],
                ['booth_number' => 'H-A4', 'booth_type' => 'display',  'equipment_type' => 'Row Space Only',     'size_sqm' => 20.0,    'sector_id' => 3],
                ['booth_number' => 'H-A5', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 3],
                ['booth_number' => 'H-B1', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 3],
                ['booth_number' => 'H-B2', 'booth_type' => 'display',  'equipment_type' => 'Equipped Booth',       'size_sqm' => 25.0,    'sector_id' => 3],
                ['booth_number' => 'H-B3', 'booth_type' => 'sales',    'equipment_type' => 'Not Equipped Booth',   'size_sqm' => 25.0,    'sector_id' => 3],
                ['booth_number' => 'H-B4', 'booth_type' => 'display',  'equipment_type' => 'Row Space Only',     'size_sqm' => 20.0,    'sector_id' => 3],
                ['booth_number' => 'H-B5', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 48.0,    'sector_id' => 3],
            ],

            'قاعة الصناعة والتقنية' => [
                ['booth_number' => 'T-A1', 'booth_type' => 'display',  'equipment_type' => 'Equipped Booth',       'size_sqm' => 60.0,    'sector_id' => 4],
                ['booth_number' => 'T-A2', 'booth_type' => 'display',  'equipment_type' => 'Equipped Booth',       'size_sqm' => 60.0,    'sector_id' => 4],
                ['booth_number' => 'T-A3', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 48.0,    'sector_id' => 4],
                ['booth_number' => 'T-A4', 'booth_type' => 'display',  'equipment_type' => 'Not Equipped Booth',   'size_sqm' => 48.0,    'sector_id' => 4],
                ['booth_number' => 'T-A5', 'booth_type' => 'display',  'equipment_type' => 'Row Space Only',     'size_sqm' => 36.0,    'sector_id' => 4],
                ['booth_number' => 'T-B1', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 48.0,    'sector_id' => 4],
                ['booth_number' => 'T-B2', 'booth_type' => 'display',  'equipment_type' => 'Equipped Booth',       'size_sqm' => 60.0,    'sector_id' => 4],
                ['booth_number' => 'T-B3', 'booth_type' => 'sales',    'equipment_type' => 'Not Equipped Booth',   'size_sqm' => 36.0,    'sector_id' => 4],
                ['booth_number' => 'T-B4', 'booth_type' => 'display',  'equipment_type' => 'Row Space Only',     'size_sqm' => 36.0,    'sector_id' => 4],
                ['booth_number' => 'T-B5', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 60.0,    'sector_id' => 4],
            ],

            'قاعة الصحة والجمال' => [
                ['booth_number' => 'B-A1', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 7],
                ['booth_number' => 'B-A2', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 7],
                ['booth_number' => 'B-A3', 'booth_type' => 'display',  'equipment_type' => 'Not Equipped Booth',   'size_sqm' => 25.0,    'sector_id' => 7],
                ['booth_number' => 'B-A4', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 48.0,    'sector_id' => 7],
                ['booth_number' => 'B-A5', 'booth_type' => 'display',  'equipment_type' => 'Row Space Only',     'size_sqm' => 25.0,    'sector_id' => 7],
                ['booth_number' => 'B-B1', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 7],
                ['booth_number' => 'B-B2', 'booth_type' => 'display',  'equipment_type' => 'Equipped Booth',       'size_sqm' => 36.0,    'sector_id' => 7],
                ['booth_number' => 'B-B3', 'booth_type' => 'sales',    'equipment_type' => 'Not Equipped Booth',   'size_sqm' => 25.0,    'sector_id' => 7],
                ['booth_number' => 'B-B4', 'booth_type' => 'display',  'equipment_type' => 'Row Space Only',     'size_sqm' => 25.0,    'sector_id' => 7],
                ['booth_number' => 'B-B5', 'booth_type' => 'sales',    'equipment_type' => 'Equipped Booth',       'size_sqm' => 60.0,    'sector_id' => 7],
            ],
        ];

        foreach ($boothsConfig as $hallName => $booths) {
            $hall = $halls->get($hallName);
            if (! $hall) continue;

            foreach ($booths as $boothData) {
                Booth::firstOrCreate(
                    [
                        'booth_number' => $boothData['booth_number'],
                    ],
                    array_merge($boothData, [
                        'available'  => true,
                        'company_id' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])
                );
            }
        }


//        $requests = [
//
//            // ─── 1. سماكة للغذائيات (paid) ───────────────────────
//            [
//                'foreign_local'       => 'local',
//                'company_name'        => 'سماكة للغذائيات',
//                'responsible_name'    => 'محمد علي سماكة',
//                'job_title'           => 'المدير التنفيذي',
//                'email'               => 'request@samakia',
//                'phone'               => '+963 11 445 678',
//                'nationality'         => 'سورية',
//                'commercial_register' => 'DM-2019-0044',
//                'address'             => 'دمشق — شارع الثورة — بناء رقم 12',
//                'sector'              => 'الصناعات الغذائية',
//                'company_description' => 'شركة رائدة في تصنيع وتوزيع المنتجات الغذائية والسمك المجفف والمعلب بأعلى معايير الجودة.',
//                'requested_area'      => 48.0,
//                'setup_preference'    => 'Equipped Booth',
//                'terms_accepted_at'   => now()->subDays(30),
//                'request_status'      => 'approved',
//                'payment_status'      => 'paid',
//                'total_price'         => 2400.00,
//                'required_deposit'    => 1200.00,
//                'paid_amount'         => 2400.00,
//                'payment_due_date'    => now()->subDays(20)->toDateString(),
//            ],
//            [
//                'foreign_local'       => 'local',
//                'company_name'        => 'سماكة للغذائيات',
//                'responsible_name'    => 'محمد علي سماكة',
//                'job_title'           => 'المدير التنفيذي',
//                'email'               => 'request@samakia.sy',
//                'phone'               => '+963 11 445 6789',
//                'nationality'         => 'سورية',
//                'commercial_register' => 'DM-2019-00441',
//                'address'             => 'دمشق — شارع الثورة — بناء رقم 12',
//                'sector'              => 'الصناعات الغذائية',
//                'company_description' => 'شركة رائدة في تصنيع وتوزيع المنتجات الغذائية والسمك المجفف والمعلب بأعلى معايير الجودة.',
//                'requested_area'      => 48.0,
//                'setup_preference'    => 'Kiosk AB',
//                'terms_accepted_at'   => now()->subDays(30),
//                'request_status'      => 'approved',
//                'payment_status'      => 'paid',
//                'total_price'         => 2400.00,
//                'required_deposit'    => 1200.00,
//                'paid_amount'         => 2400.00,
//                'payment_due_date'    => now()->subDays(20)->toDateString(),
//            ],
//
//            // ─── 2. الحسن للسجاد (paid) ──────────────────────────
//            [
//                'foreign_local'       => 'local',
//                'company_name'        => 'الحسن للسجاد',
//                'responsible_name'    => 'عبدالله محمد الحسن',
//                'job_title'           => 'مالك الشركة',
//                'email'               => 'request@alhasan-carpet.sy',
//                'phone'               => '+963 11 556 7890',
//                'nationality'         => 'سورية',
//                'commercial_register' => 'DM-2015-00228',
//                'address'             => 'دمشق — المزة — شارع الجلاء',
//                'sector'              => 'الحرف اليدوية',
//                'company_description' => 'نحيك السجاد يدوياً بطريقة حسن بأدق التفاصيل وأجود أنواع الأصواف والحرير الطبيعي.',
//                'requested_area'      => 36.0,
//                'setup_preference'    => 'Kiosk AB',
//                'terms_accepted_at'   => now()->subDays(35),
//                'request_status'      => 'approved',
//                'payment_status'      => 'paid',
//                'total_price'         => 1800.00,
//                'required_deposit'    => 900.00,
//                'paid_amount'         => 1800.00,
//                'payment_due_date'    => now()->subDays(25)->toDateString(),
//            ],
//
//            // ─── 3. نشبه بعضنا (paid - foreign) ──────────────────
//            [
//                'foreign_local'       => 'foreign',
//                'company_name'        => 'مؤسسة نشبه بعضنا',
//                'responsible_name'    => 'فيصل الرشيد',
//                'job_title'           => 'المدير الإقليمي',
//                'email'               => 'request@nashbehbaadhna.sy',
//                'phone'               => '+966 50 123 4567',
//                'nationality'         => 'سعودية',
//                'commercial_register' => 'SA-2020-00991',
//                'address'             => 'الرياض — حي العليا — طريق الملك فهد',
//                'sector'              => 'الثقافة والفنون',
//                'company_description' => 'مؤسسة سعودية تعزز الواقع العربي وتدعم المشاريع الثقافية والاجتماعية في المنطقة العربية.',
//                'requested_area'      => 60.0,
//                'setup_preference'    => 'Equipped Booth',
//                'terms_accepted_at'   => now()->subDays(28),
//                'request_status'      => 'approved',
//                'payment_status'      => 'paid',
//                'total_price'         => 3500.00,
//                'required_deposit'    => 1750.00,
//                'paid_amount'         => 3500.00,
//                'payment_due_date'    => now()->subDays(18)->toDateString(),
//            ],
//
//            // ─── 4. حكاية المعارف (paid) ─────────────────────────
//            [
//                'foreign_local'       => 'local',
//                'company_name'        => 'حكاية المعارف في أرض الطرابيش',
//                'responsible_name'    => 'ليلى الخوري',
//                'job_title'           => 'مدير المشروع',
//                'email'               => 'request@hikayat.sy',
//                'phone'               => '+963 11 678 9012',
//                'nationality'         => 'سورية',
//                'commercial_register' => 'DM-2021-00554',
//                'address'             => 'دمشق — باب توما — شارع الأنصاري',
//                'sector'              => 'الثقافة والفنون',
//                'company_description' => 'مشروع ثقافي يروي تاريخ دمشق من خلال الفنون التقليدية والحرف الأصيلة.',
//                'requested_area'      => 36.0,
//                'setup_preference'    => 'Not Equipped Booth',
//                'terms_accepted_at'   => now()->subDays(22),
//                'request_status'      => 'approved',
//                'payment_status'      => 'paid',
//                'total_price'         => 1500.00,
//                'required_deposit'    => 750.00,
//                'paid_amount'         => 1500.00,
//                'payment_due_date'    => now()->subDays(12)->toDateString(),
//            ],
//
//            // ─── 5. دين وطن (paid) ───────────────────────────────
//            [
//                'foreign_local'       => 'local',
//                'company_name'        => 'برنامج دين وطن',
//                'responsible_name'    => 'أحمد الفاروق',
//                'job_title'           => 'المدير الإداري',
//                'email'               => 'request@deenandwatan.sy',
//                'phone'               => '+963 11 789 0123',
//                'nationality'         => 'سورية',
//                'commercial_register' => 'DM-2018-00332',
//                'address'             => 'دمشق — المالكي — شارع أبو رمانة',
//                'sector'              => 'التعليم والبرامج',
//                'company_description' => 'برنامج يناقش الأسئلة الدينية والاجتماعية يومياً على منصة يوتيوب بأسلوب مبسط.',
//                'requested_area'      => 36.0,
//                'setup_preference'    => 'Equipped Booth',
//                'terms_accepted_at'   => now()->subDays(18),
//                'request_status'      => 'approved',
//                'payment_status'      => 'paid',
//                'total_price'         => 1800.00,
//                'required_deposit'    => 900.00,
//                'paid_amount'         => 1800.00,
//                'payment_due_date'    => now()->subDays(8)->toDateString(),
//            ],
//
//            // ─── 6. المنير للفخار (paid) ─────────────────────────
//            [
//                'foreign_local'       => 'local',
//                'company_name'        => 'المنير للفخار والخزف',
//                'responsible_name'    => 'كريم المنير',
//                'job_title'           => 'صاحب الورشة',
//                'email'               => 'request@almoneer.sy',
//                'phone'               => '+963 11 890 1234',
//                'nationality'         => 'سورية',
//                'commercial_register' => 'DM-2010-00115',
//                'address'             => 'دمشق — القيمرية — زقاق الفخارين',
//                'sector'              => 'الحرف اليدوية',
//                'company_description' => 'ورشة حرفية متخصصة في صناعة الفخار والخزف الدمشقي بأساليب تقليدية موروثة.',
//                'requested_area'      => 25.0,
//                'setup_preference'    => 'Kiosk AB',
//                'terms_accepted_at'   => now()->subDays(40),
//                'request_status'      => 'approved',
//                'payment_status'      => 'paid',
//                'total_price'         => 1250.00,
//                'required_deposit'    => 625.00,
//                'paid_amount'         => 1250.00,
//                'payment_due_date'    => now()->subDays(30)->toDateString(),
//            ],
//
//            // ─── 7. التقنيات المتقدمة (paid) ─────────────────────
//            [
//                'foreign_local'       => 'local',
//                'company_name'        => 'شركة التقنيات المتقدمة',
//                'responsible_name'    => 'رامي السيد',
//                'job_title'           => 'المدير التقني',
//                'email'               => 'request@advanced-tech.sy',
//                'phone'               => '+963 11 901 2345',
//                'nationality'         => 'سورية',
//                'commercial_register' => 'DM-2017-00667',
//                'address'             => 'دمشق — كفرسوسة — المنطقة التقنية',
//                'sector'              => 'الصناعة والتقنية',
//                'company_description' => 'شركة سورية رائدة في تطوير حلول تقنية ذكية للمؤسسات والأفراد.',
//                'requested_area'      => 60.0,
//                'setup_preference'    => 'Equipped Booth',
//                'terms_accepted_at'   => now()->subDays(15),
//                'request_status'      => 'approved',
//                'payment_status'      => 'paid',
//                'total_price'         => 3000.00,
//                'required_deposit'    => 1500.00,
//                'paid_amount'         => 3000.00,
//                'payment_due_date'    => now()->subDays(5)->toDateString(),
//            ],
//
//            // ─── 8. البناء الحديث (paid) ─────────────────────────
//            [
//                'foreign_local'       => 'local',
//                'company_name'        => 'مجموعة البناء الحديث',
//                'responsible_name'    => 'سامر الحسيني',
//                'job_title'           => 'مدير المشاريع',
//                'email'               => 'request@modernbuild.sy',
//                'phone'               => '+963 11 012 3456',
//                'nationality'         => 'سورية',
//                'commercial_register' => 'DM-2012-00789',
//                'address'             => 'دمشق — المزرعة — شارع بغداد',
//                'sector'              => 'البناء والعقارات',
//                'company_description' => 'مجموعة متخصصة في تنفيذ المشاريع الإنشائية والتصاميم المعمارية الحديثة.',
//                'requested_area'      => 48.0,
//                'setup_preference'    => 'Row Space Only',
//                'terms_accepted_at'   => now()->subDays(20),
//                'request_status'      => 'approved',
//                'payment_status'      => 'paid',
//                'total_price'         => 2400.00,
//                'required_deposit'    => 1200.00,
//                'paid_amount'         => 2400.00,
//                'payment_due_date'    => now()->subDays(10)->toDateString(),
//            ],
//
//            // ─── 9. الأزياء السورية (paid) ───────────────────────
//            [
//                'foreign_local'       => 'local',
//                'company_name'        => 'مؤسسة الأزياء السورية',
//                'responsible_name'    => 'هالة الزعبي',
//                'job_title'           => 'المصممة الرئيسية',
//                'email'               => 'request@syrianfashion.sy',
//                'phone'               => '+963 11 123 4567',
//                'nationality'         => 'سورية',
//                'commercial_register' => 'DM-2019-00843',
//                'address'             => 'دمشق — الشعلان — شارع النصر',
//                'sector'              => 'الموضة والأزياء',
//                'company_description' => 'مؤسسة للتصميم والإنتاج في صناعة الأزياء السورية الأصيلة المزوجة بالطراز الحديث.',
//                'requested_area'      => 36.0,
//                'setup_preference'    => 'Equipped Booth',
//                'terms_accepted_at'   => now()->subDays(12),
//                'request_status'      => 'approved',
//                'payment_status'      => 'paid',
//                'total_price'         => 1800.00,
//                'required_deposit'    => 900.00,
//                'paid_amount'         => 1800.00,
//                'payment_due_date'    => now()->subDays(2)->toDateString(),
//            ],
//
//            // ─── 10. الصحة الشاملة (paid) ────────────────────────
//            [
//                'foreign_local'       => 'local',
//                'company_name'        => 'مركز الصحة الشاملة',
//                'responsible_name'    => 'د. نور السالم',
//                'job_title'           => 'المدير الطبي',
//                'email'               => 'request@healthcenter.sy',
//                'phone'               => '+963 11 234 5678',
//                'nationality'         => 'سورية',
//                'commercial_register' => 'DM-2016-00521',
//                'address'             => 'دمشق — المهاجرين — شارع المتنبي',
//                'sector'              => 'الصحة والجمال',
//                'company_description' => 'مركز صحي شامل يقدم خدمات الرعاية الصحية الوقائية والعلاجية والجمالية.',
//                'requested_area'      => 36.0,
//                'setup_preference'    => 'Equipped Booth',
//                'terms_accepted_at'   => now()->subDays(25),
//                'request_status'      => 'approved',
//                'payment_status'      => 'paid',
//                'total_price'         => 1800.00,
//                'required_deposit'    => 900.00,
//                'paid_amount'         => 1800.00,
//                'payment_due_date'    => now()->subDays(15)->toDateString(),
//            ],
//
//            // ─── 11. أكاديمية التدريب (approved - unpaid) ────────
//            [
//                'foreign_local'       => 'local',
//                'company_name'        => 'أكاديمية التدريب المهني',
//                'responsible_name'    => 'محمود الأتاسي',
//                'job_title'           => 'مدير الأكاديمية',
//                'email'               => 'request@academy.sy',
//                'phone'               => '+963 11 345 6789',
//                'nationality'         => 'سورية',
//                'commercial_register' => 'DM-2020-00632',
//                'address'             => 'دمشق — باب الجابية — شارع القوتلي',
//                'sector'              => 'التعليم والبرامج',
//                'company_description' => 'أكاديمية متخصصة في تدريب الكوادر المهنية وتطوير المهارات التقنية والإدارية.',
//                'requested_area'      => 36.0,
//                'setup_preference'    => 'Not Equipped Booth',
//                'terms_accepted_at'   => now()->subDays(5),
//                'request_status'      => 'approved',
//                'payment_status'      => 'unpaid',
//                'total_price'         => 1800.00,
//                'required_deposit'    => 900.00,
//                'paid_amount'         => 0.00,
//                'payment_due_date'    => now()->addDays(67)->toDateString(),
//            ],
//
//            // ─── 12. الطاقة الشمسية (pending) ────────────────────
//            [
//                'foreign_local'       => 'local',
//                'company_name'        => 'شركة الطاقة الشمسية السورية',
//                'responsible_name'    => 'علاء الدين الجمال',
//                'job_title'           => 'مدير التطوير',
//                'email'               => 'request@solarenergy.sy',
//                'phone'               => '+963 11 456 7890',
//                'nationality'         => 'سورية',
//                'commercial_register' => 'DM-2022-00765',
//                'address'             => 'دمشق — برزة — المنطقة الصناعية',
//                'sector'              => 'الطاقة المتجددة',
//                'company_description' => 'شركة متخصصة في تصميم وتركيب وصيانة منظومات الطاقة الشمسية للمنازل والمنشآت.',
//                'requested_area'      => 48.0,
//                'setup_preference'    => 'Row Space Only',
//                'terms_accepted_at'   => now()->subDays(2),
//                'request_status'      => 'pending',
//                'payment_status'      => 'unpaid',
//                'total_price'         => null,
//                'required_deposit'    => null,
//                'paid_amount'         => 0.00,
//                'payment_due_date'    => null,
//            ],
//        ];
//
//        foreach ($requests as $request) {
//            CompanyRequest::firstOrCreate(
//                ['email' => $request['email']],
//                array_merge($request, [
//                    'created_at' => now(),
//                    'updated_at' => now(),
//                ])
//            );
//        }




        // ─── Gate Operator Users ──────────────────────────────────
        $gateOperators = [
            [
                'name'  => 'أمين البوابة الرئيسية',
                'email' => 'gate1@damascusfair.sy',
                'phone' => '111111111'
            ],
            [
                'name'  => 'أمين البوابة الجانبية',
                'email' => 'gate2@damascusfair.sy',
                'phone' => '222222222'
            ],
        ];

        foreach ($gateOperators as $operator) {
            $u = User::firstOrCreate(
                ['email' => $operator['email']],
                [
                    'name'            => $operator['name'],
                    'email'           => $operator['email'],
                    'password'        => Hash::make('Gate@1234!'),
                    'phonenumber'     => $operator['phone'],
                    'failed_attempts' => 0,
                    'locked_until'    => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]
            );
            $u->assignRole('gate_operator');
        }
//
//        // ─── Company Users (حسابات الشركات) ──────────────────────
//        $companyUsers = [
//            ['name' => 'مستخدم سماكة للغذائيات',           'email' => 'user@samakia.sy',    'phone' => '333333333'],
//            ['name' => 'مستخدم الحسن للسجاد',              'email' => 'user@alhasan-carpet.sy',    'phone' => '444444444'],
//            ['name' => 'مستخدم نشبه بعضنا',                'email' => 'user@nashbehbaadhna.sy',    'phone' => '555555555'],
//            ['name' => 'مستخدم حكاية المعارف',             'email' => 'user@hikayat.sy',    'phone' => '666666666'],
//            ['name' => 'مستخدم دين وطن',                   'email' => 'user@deenandwatan.sy',    'phone' => '777777777'],
//            ['name' => 'مستخدم المنير للفخار',              'email' => 'user@almoneer.sy',    'phone' => '888888888'],
//            ['name' => 'مستخدم شركة التقنيات المتقدمة',     'email' => 'user@advanced-tech.sy',    'phone' => '999999999'],
//            ['name' => 'مستخدم مجموعة البناء الحديث',       'email' => 'user@modernbuild.sy',    'phone' => '111222333'],
//            ['name' => 'مستخدم مؤسسة الأزياء السورية',      'email' => 'user@syrianfashion.sy',    'phone' => '222111333'],
//            ['name' => 'مستخدم مركز الصحة الشاملة',         'email' => 'user@healthcenter.sy',    'phone' => '333222111'],
//            ['name' => 'مستخدم أكاديمية التدريب',           'email' => 'user@academy.sy',    'phone' => '222333111'],
//            ['name' => 'مستخدم شركة الطاقة الشمسية',        'email' => 'user@solarenergy.sy',    'phone' => '333111222'],
//        ];
//
//        foreach ($companyUsers as $user) {
//            $u = User::firstOrCreate(
//                ['email' => $user['email']],
//                [
//                    'name'            => $user['name'],
//                    'email'           => $user['email'],
//                    'password'        => Hash::make('Company@1234!'),
//                    'phonenumber'     => $user['phone'],
//                    'failed_attempts' => 0,
//                    'locked_until'    => null,
//                    'created_at'      => now(),
//                    'updated_at'      => now(),
//                ]
//            );
//            $u->assignRole('company');
//        }
//
//
//
//
//
//        $companies = [
//            [
//                'user_email'    => 'user@samakia.sy',
//                'request_email' => 'request@samakia.sy',
//                'name'          => 'سماكة للغذائيات',
//                'logo'          => 'logos/samakia.png',
//                'responsible_person' => 'محمد علي سماكة',
//                'sector'        => 'الصناعات الغذائية',
//                'sector_id'     => 1,
//                'bio'           => 'شركة رائدة في تصنيع وتوزيع المنتجات الغذائية والسمك المجفف والمعلب بأعلى معايير الجودة منذ عام 2019. نوزع منتجاتنا في أكثر من 15 محافظة سورية.',
//                'address'       => 'دمشق — شارع الثورة — بناء رقم 12',
//                'final_area'    => 48.0,
//                'booth_type'    => 'Equipped Booth',
//                'is_active'     => true,
//            ],
//            [
//                'user_email'    => 'user@alhasan-carpet.sy',
//                'request_email' => 'request@alhasan-carpet.sy',
//                'name'          => 'الحسن للسجاد بالطريقة اليدوية',
//                'logo'          => 'logos/alhasan.png',
//                'responsible_person' => 'عبدالله محمد الحسن',
//                'sector'        => 'الحرف اليدوية',
//                'sector_id'     => 3,
//                'bio'           => 'ورشة عائلية تتوارث حياكة السجاد اليدوي منذ أكثر من 50 عاماً. نستخدم أجود أنواع الصوف والحرير الطبيعي لإنتاج سجاد يدوم أجيالاً.',
//                'address'       => 'دمشق — المزة — شارع الجلاء',
//                'final_area'    => 36.0,
//                'booth_type'    => 'Equipped Booth',
//                'is_active'     => true,
//            ],
//            [
//                'user_email'    => 'user@nashbehbaadhna.sy',
//                'request_email' => 'request@nashbehbaadhna.sy',
//                'name'          => 'مؤسسة نشبه بعضنا',
//                'logo'          => 'logos/nashbeh.png',
//                'responsible_person' => 'فيصل الرشيد',
//                'sector'        => 'الثقافة والفنون',
//                'sector_id'     => 2,
//                'bio'           => 'مؤسسة سعودية تعزز الواقع الثقافي والاجتماعي العربي وتدعم الهوية العربية الأصيلة. نهدف إلى ربط المجتمعات العربية عبر مشاريع ثقافية مشتركة.',
//                'address'       => 'الرياض — حي العليا — طريق الملك فهد',
//                'final_area'    => 60.0,
//                'booth_type'    => 'Equipped Booth',
//                'is_active'     => true,
//            ],
//            [
//                'user_email'    => 'user@hikayat.sy',
//                'request_email' => 'request@hikayat.sy',
//                'name'          => 'حكاية المعارف في أرض الطرابيش',
//                'logo'          => 'logos/hikayat.png',
//                'responsible_person' => 'ليلى الخوري',
//                'sector'        => 'الثقافة والفنون',
//                'sector_id'     => 2,
//                'bio'           => 'مشروع ثقافي فريد يروي قصص وتراث دمشق العريقة من خلال الفنون التقليدية والحرف الأصيلة. نحافظ على الذاكرة الجمعية ونقدمها بقالب حديث.',
//                'address'       => 'دمشق — باب توما — شارع الأنصاري',
//                'final_area'    => 36.0,
//                'booth_type'    => 'Not Equipped Booth',
//                'is_active'     => true,
//            ],
//            [
//                'user_email'    => 'user@deenandwatan.sy',
//                'request_email' => 'request@deenandwatan.sy',
//                'name'          => 'برنامج دين وطن',
//                'logo'          => 'logos/deenandwatan.png',
//                'responsible_person' => 'أحمد الفاروق',
//                'sector'        => 'التعليم والبرامج',
//                'sector_id'     => 6,
//                'bio'           => 'برنامج "دين وطن" هو برنامج ديني واجتماعي يُعرض بشكل أساسي على منصة يوتيوب، يهدف إلى الربط بين تعاليم الدين الإسلامي وتفاصيل الحياة اليومية بأسلوب مبسط وعصري.',
//                'address'       => 'دمشق — المالكي — شارع أبو رمانة',
//                'final_area'    => 36.0,
//                'booth_type'    => 'Equipped Booth',
//                'is_active'     => true,
//            ],
//            [
//                'user_email'    => 'user@almoneer.sy',
//                'request_email' => 'request@almoneer.sy',
//                'name'          => 'المنير للفخار والخزف',
//                'logo'          => 'logos/almoneer.png',
//                'responsible_person' => 'كريم المنير',
//                'sector'        => 'الحرف اليدوية',
//                'sector_id'     => 3,
//                'bio'           => 'ورشة متخصصة في صناعة الفخار والخزف الدمشقي بأساليب تقليدية متوارثة منذ 3 أجيال. نصنع أواني وتحف وقطع زخرفية فريدة بأيدي حرفيين مهرة.',
//                'address'       => 'دمشق — القيمرية — زقاق الفخارين',
//                'final_area'    => 25.0,
//                'booth_type'    => 'Not Equipped Booth',
//                'is_active'     => true,
//            ],
//            [
//                'user_email'    => 'user@advanced-tech.sy',
//                'request_email' => 'request@advanced-tech.sy',
//                'name'          => 'شركة التقنيات المتقدمة',
//                'logo'          => 'logos/advancedtech.png',
//                'responsible_person' => 'رامي السيد',
//                'sector'        => 'الصناعة والتقنية',
//                'sector_id'     => 4,
//                'bio'           => 'شركة سورية رائدة في تطوير وتقديم الحلول التقنية الذكية للشركات والمؤسسات الحكومية والخاصة. متخصصون في أنظمة ERP وحلول الأمن السيبراني.',
//                'address'       => 'دمشق — كفرسوسة — المنطقة التقنية',
//                'final_area'    => 60.0,
//                'booth_type'    => 'Equipped Booth',
//                'is_active'     => true,
//            ],
//            [
//                'user_email'    => 'user@modernbuild.sy',
//                'request_email' => 'request@modernbuild.sy',
//                'name'          => 'مجموعة البناء الحديث',
//                'logo'          => 'logos/modernbuild.png',
//                'responsible_person' => 'سامر الحسيني',
//                'sector'        => 'البناء والعقارات',
//                'sector_id'     => 8,
//                'bio'           => 'مجموعة إنشائية متكاملة تضم خبرة 15 عاماً في تنفيذ المشاريع الإنشائية الكبرى والتصاميم المعمارية العصرية. نلتزم بأعلى معايير الجودة والسلامة.',
//                'address'       => 'دمشق — المزرعة — شارع بغداد',
//                'final_area'    => 48.0,
//                'booth_type'    => 'Row Space Only',
//                'is_active'     => true,
//            ],
//            [
//                'user_email'    => 'user@syrianfashion.sy',
//                'request_email' => 'request@syrianfashion.sy',
//                'name'          => 'مؤسسة الأزياء السورية',
//                'logo'          => 'logos/syrianfashion.png',
//                'responsible_person' => 'هالة الزعبي',
//                'sector'        => 'الموضة والأزياء',
//                'sector_id'     => 11,
//                'bio'           => 'دار أزياء سورية أصيلة تجمع بين التراث والحداثة. تصاميمنا مستوحاة من الزخارف الدمشقية التاريخية ومزوجة بالأساليب العصرية.',
//                'address'       => 'دمشق — الشعلان — شارع النصر',
//                'final_area'    => 36.0,
//                'booth_type'    => 'Equipped Booth',
//                'is_active'     => true,
//            ],
//            [
//                'user_email'    => 'user@healthcenter.sy',
//                'request_email' => 'request@healthcenter.sy',
//                'name'          => 'مركز الصحة الشاملة',
//                'logo'          => 'logos/healthcenter.png',
//                'responsible_person' => 'د. نور السالم',
//                'sector'        => 'الصحة والجمال',
//                'sector_id'     => 7,
//                'bio'           => 'مركز صحي متكامل يقدم خدمات الرعاية الصحية الوقائية والعلاجية والتجميلية. فريق من أمهر الأطباء والمختصين الصحيين في دمشق.',
//                'address'       => 'دمشق — المهاجرين — شارع المتنبي',
//                'final_area'    => 36.0,
//                'booth_type'    => 'Equipped Booth',
//                'is_active'     => true,
//            ],
//        ];
//
//        foreach ($companies as $data) {
//            $user    = User::where('email', $data['user_email'])->first();
//            $request = CompanyRequest::where('email', $data['request_email'])->first();
//
//            if (! $user || ! $request) continue;
//
//            Company::firstOrCreate(
//                ['user_id' => $user->id],
//                [
//                    'user_id'            => $user->id,
//                    'company_request_id' => $request->id,
//                    'name'               => $data['name'],
//                    'logo'               => $data['logo'],
//                    'responsible_person' => $data['responsible_person'],
//                    'sector'             => $data['sector'],
//                    'sector_id'             => $data['sector_id'],
//                    'bio'                => $data['bio'],
//                    'address'            => $data['address'],
//                    'final_area'         => $data['final_area'],
//                    'booth_type'         => $data['booth_type'],
//                    'is_active'          => $data['is_active'],
//                    'created_at'         => now(),
//                    'updated_at'         => now(),
//                ]
//            );
//        }
//
//
//        $assignments = [
//            // company_name               => booth_number
//            'سماكة للغذائيات'             => 'F-A1',
//            'الحسن للسجاد بالطريقة اليدوية' => 'H-A1',
//            'مؤسسة نشبه بعضنا'             => 'C-B1',
//            'حكاية المعارف في أرض الطرابيش' => 'C-A1',
//            'برنامج دين وطن'               => 'C-A2',
//            'المنير للفخار والخزف'          => 'H-A3',
//            'شركة التقنيات المتقدمة'        => 'T-A1',
//            'مجموعة البناء الحديث'          => 'T-A4',
//            'مؤسسة الأزياء السورية'         => 'B-A1',
//            'مركز الصحة الشاملة'            => 'B-A2',
//        ];
//
//        foreach ($assignments as $companyName => $boothNumber) {
//            $company = Company::where('name', $companyName)->first();
//            $booth   = Booth::where('booth_number', $boothNumber)->first();
//
//            if (! $company || ! $booth) continue;
//
//            // تحديث الـ booth بالشركة وتغيير حالته لغير متاح
//            $booth->update([
//                'company_id' => $company->id,
//                'sector_id' => $company->sector_id,
//                'available'  => false,
//            ]);
//        }
//
//
//        $productsData = [
//
//            // ─── سماكة للغذائيات ─────────────────────────────────
//            'سماكة للغذائيات' => [
//                [
//                    'name'        => 'سمك مجفف ممتاز',
//                    'description' => 'سمك مجفف طبيعي 100% بدون إضافات صناعية، يُحضَّر بطريقة تقليدية ومعبأ في أكياس 500 غرام.',
//                    'price'       => 4500.00,
//                    'images'      => [
//                        ['path' => 'products/samakia/dried-fish.jpg', 'is_primary' => true],
//                        ['path' => 'products/samakia/dried-fish-2.jpg', 'is_primary' => false],
//                    ],
//                ],
//                [
//                    'name'        => 'معلبات سمك التونة',
//                    'description' => 'تونة عالية الجودة معلبة بزيت الزيتون البكر، مناسبة للوجبات السريعة والسلطات.',
//                    'price'       => 1200.00,
//                    'images'      => [
//                        ['path' => 'products/samakia/tuna-cans.jpg', 'is_primary' => true],
//                    ],
//                ],
//                [
//                    'name'        => 'سردين مملح',
//                    'description' => 'سردين طازج مملح ومعبأ وفق أفضل المعايير الصحية، غني بأوميغا 3.',
//                    'price'       => 800.00,
//                    'images'      => [
//                        ['path' => 'products/samakia/salted-sardine.jpg', 'is_primary' => true],
//                    ],
//                ],
//            ],
//
//            // ─── الحسن للسجاد ─────────────────────────────────────
//            'الحسن للسجاد بالطريقة اليدوية' => [
//                [
//                    'name'        => 'سجادة حمراء تراثية',
//                    'description' => 'سجادة يدوية حمراء بزخارف دمشقية أصيلة، مصنوعة من أجود أنواع الصوف الطبيعي، مقاس 200×200 سم.',
//                    'price'       => 200.00,
//                    'images'      => [
//                        ['path' => 'products/alhasan/red-carpet.jpg', 'is_primary' => true],
//                        ['path' => 'products/alhasan/red-carpet-detail.jpg', 'is_primary' => false],
//                    ],
//                ],
//                [
//                    'name'        => 'سجادة دمشقية كلاسيك',
//                    'description' => 'سجادة كلاسيكية بألوان طبيعية مستخرجة من النباتات، مقاس 200×300 سم، مثالية للغرف الرئيسية.',
//                    'price'       => 350.00,
//                    'images'      => [
//                        ['path' => 'products/alhasan/classic-carpet.jpg', 'is_primary' => true],
//                    ],
//                ],
//                [
//                    'name'        => 'سجادة وردية للأطفال',
//                    'description' => 'سجادة ناعمة بتصاميم بهيجة مناسبة لغرف الأطفال، آمنة 100% وخالية من المواد الكيميائية.',
//                    'price'       => 150.00,
//                    'images'      => [
//                        ['path' => 'products/alhasan/kids-carpet.jpg', 'is_primary' => true],
//                    ],
//                ],
//                [
//                    'name'        => 'سجادة مصغرة زخرفية',
//                    'description' => 'قطعة زخرفية صغيرة مثالية كهدية أو للتعليق على الجدران، مقاس 60×90 سم.',
//                    'price'       => 80.00,
//                    'images'      => [
//                        ['path' => 'products/alhasan/mini-carpet.jpg', 'is_primary' => true],
//                    ],
//                ],
//            ],
//
//            // ─── المنير للفخار ────────────────────────────────────
//            'المنير للفخار والخزف' => [
//                [
//                    'name'        => 'إبريق فخاري تقليدي',
//                    'description' => 'إبريق مصنوع يدوياً من طين دمشق الأصيل ومزخرف بنقوش إسلامية، طلاء طبيعي مقاوم للحرارة.',
//                    'price'       => 75.00,
//                    'images'      => [
//                        ['path' => 'products/almoneer/teapot.jpg', 'is_primary' => true],
//                    ],
//                ],
//                [
//                    'name'        => 'طقم أكواب فخارية',
//                    'description' => 'طقم من 6 أكواب فخارية بزخارف هندسية ملونة، مناسب للضيافة ويتحمل درجات الحرارة العالية.',
//                    'price'       => 120.00,
//                    'images'      => [
//                        ['path' => 'products/almoneer/cups-set.jpg', 'is_primary' => true],
//                        ['path' => 'products/almoneer/cups-set-2.jpg', 'is_primary' => false],
//                    ],
//                ],
//                [
//                    'name'        => 'مزهرية فخارية كبيرة',
//                    'description' => 'مزهرية فخارية بارتفاع 45 سم مزخرفة يدوياً، تُضفي طابعاً أثرياً على أي مكان.',
//                    'price'       => 95.00,
//                    'images'      => [
//                        ['path' => 'products/almoneer/vase.jpg', 'is_primary' => true],
//                    ],
//                ],
//            ],
//
//            // ─── شركة التقنيات المتقدمة ───────────────────────────
//            'شركة التقنيات المتقدمة' => [
//                [
//                    'name'        => 'نظام ERP للمؤسسات',
//                    'description' => 'نظام متكامل لإدارة موارد المؤسسة يشمل المحاسبة والمخزون والموارد البشرية والمبيعات.',
//                    'price'       => null, // خدمة — السعر حسب الحجم
//                    'images'      => [
//                        ['path' => 'products/advancedtech/erp.jpg', 'is_primary' => true],
//                    ],
//                ],
//                [
//                    'name'        => 'حلول الأمن السيبراني',
//                    'description' => 'منظومة شاملة لحماية البنية التحتية الرقمية تشمل الجدران النارية وأنظمة كشف التسلل.',
//                    'price'       => null,
//                    'images'      => [
//                        ['path' => 'products/advancedtech/security.jpg', 'is_primary' => true],
//                    ],
//                ],
//                [
//                    'name'        => 'تطبيقات الجوال المخصصة',
//                    'description' => 'تطوير تطبيقات iOS وAndroid مخصصة لاحتياجات عملك بأحدث التقنيات.',
//                    'price'       => null,
//                    'images'      => [
//                        ['path' => 'products/advancedtech/apps.jpg', 'is_primary' => true],
//                    ],
//                ],
//            ],
//
//            // ─── مؤسسة الأزياء السورية ───────────────────────────
//            'مؤسسة الأزياء السورية' => [
//                [
//                    'name'        => 'عباءة دمشقية مطرزة',
//                    'description' => 'عباءة أنيقة بتطريز يدوي دمشقي أصيل على قماش الحرير الطبيعي، متوفرة بألوان متعددة.',
//                    'price'       => 180.00,
//                    'images'      => [
//                        ['path' => 'products/syrianfashion/abaya.jpg', 'is_primary' => true],
//                    ],
//                ],
//                [
//                    'name'        => 'شال حرير طبيعي',
//                    'description' => 'شال من الحرير الطبيعي 100% بزخارف مستوحاة من فسيفساء الجامع الأموي، مقاس 70×200 سم.',
//                    'price'       => 85.00,
//                    'images'      => [
//                        ['path' => 'products/syrianfashion/silk-shawl.jpg', 'is_primary' => true],
//                    ],
//                ],
//                [
//                    'name'        => 'تشكيلة كاجوال سورية',
//                    'description' => 'ملابس يومية مريحة بتصاميم تجمع الأصالة العربية والراحة العصرية، قطن مصري 100%.',
//                    'price'       => 65.00,
//                    'images'      => [
//                        ['path' => 'products/syrianfashion/casual.jpg', 'is_primary' => true],
//                    ],
//                ],
//            ],
//
//            // ─── مركز الصحة الشاملة ──────────────────────────────
//            'مركز الصحة الشاملة' => [
//                [
//                    'name'        => 'باقة الفحص الشامل',
//                    'description' => 'فحص طبي شامل يتضمن تحاليل الدم والبول والأشعة والاستشارة الطبية مع تقرير مفصل.',
//                    'price'       => 150.00,
//                    'images'      => [
//                        ['path' => 'products/healthcenter/checkup.jpg', 'is_primary' => true],
//                    ],
//                ],
//                [
//                    'name'        => 'جلسات العلاج الطبيعي',
//                    'description' => 'برامج علاج طبيعي متخصصة لإصابات الرياضة وآلام العمود الفقري والمفاصل.',
//                    'price'       => 80.00,
//                    'images'      => [
//                        ['path' => 'products/healthcenter/physiotherapy.jpg', 'is_primary' => true],
//                    ],
//                ],
//                [
//                    'name'        => 'منتجات العناية بالبشرة',
//                    'description' => 'خط من المنتجات الطبيعية لرعاية البشرة مصنوعة من مستخلصات الأعشاب السورية.',
//                    'price'       => 45.00,
//                    'images'      => [
//                        ['path' => 'products/healthcenter/skincare.jpg', 'is_primary' => true],
//                        ['path' => 'products/healthcenter/skincare-2.jpg', 'is_primary' => false],
//                    ],
//                ],
//            ],
//        ];
//
//        foreach ($productsData as $companyName => $products) {
//            $company = Company::where('name', $companyName)->first();
//            if (! $company) continue;
//
//            foreach ($products as $productData) {
//                $product = Product::firstOrCreate(
//                    [
//                        'company_id' => $company->id,
//                        'name'       => $productData['name'],
//                    ],
//                    [
//                        'company_id'  => $company->id,
//                        'name'        => $productData['name'],
//                        'description' => $productData['description'],
//                        'price'       => $productData['price'],
//                        'created_at'  => now(),
//                        'updated_at'  => now(),
//                    ]
//                );
//
//                // إضافة الصور إن لم تكن موجودة
//                if ($product->wasRecentlyCreated) {
//                    foreach ($productData['images'] as $image) {
//                        ProductImage::create([
//                            'product_id' => $product->id,
//                            'image_path' => $image['path'],
//                            'is_primary' => $image['is_primary'],
//                            'created_at' => now(),
//                            'updated_at' => now(),
//                        ]);
//                    }
//                }
//            }
//        }
//

    }
}
