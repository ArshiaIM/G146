<?php

namespace Database\Seeders;

use App\Models\Battalion;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Personnel;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // DatabaseSeeder.php

        // $battalions = [
        //     ['name' => 'گردان 146', 'code' => '146', 'companies' => [
        //         ['name' => 'ارکان',     'username' => '46ark', 'password'=>'1234'],
        //         ['name' => 'گروهان 1',  'username' => '46co1',],
        //         ['name' => 'گروهان 2',  'username' => '46co2',],
        //         ['name' => 'گروهان 3',  'username' => '46co3',],
        //     ]],
        //     ['name' => 'گردان 246', 'code' => '246', 'companies' => [
        //         ['name' => 'ارکان',     'username' => '46ark2',],
        //         ['name' => 'گروهان 1',  'username' => '26co1',],
        //         // ...
        //     ]],
        //     // بقیه گردان‌ها
        // ];

        // foreach ($battalions as $batData) {
        //     $battalion = Battalion::create([
        //         'name' => $batData['name'],
        //         'code' => $batData['code'],
        //     ]);

        //     foreach ($batData['companies'] as $coData) {
        //         $company = Company::create([
        //             'battalion_id' => $battalion->id,
        //             'name'         => $coData['name'],
        //             'code'         => $coData['username'],
        //         ]);

        //         User::create([
        //             'name'       => $coData['name'] . ' ' . $batData['name'],
        //             'username'   => $coData['username'],
        //             'password'   => Hash::make('1234'),
        //             'role'       => 'company_admin',
        //             'company_id' => $company->id,
        //         ]);
        //     }
        // }

    //     $companyId = 1; // آیدی گروهان رو عوض کن

    //     $personnel = [

    //         // ── افسر الف (5 نفر) ────────────────────────────
    //         ['rank_type' => 'officer_a', 'rank' => 'colonel',           'first_name' => 'محمد',   'last_name' => 'رضایی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_a', 'rank' => 'lieutenant_colonel', 'first_name' => 'حسین',   'last_name' => 'محمدی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_a', 'rank' => 'major',              'first_name' => 'علی',    'last_name' => 'کریمی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_a', 'rank' => 'major',              'first_name' => 'رضا',    'last_name' => 'احمدی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_a', 'rank' => 'captain',            'first_name' => 'مهدی',   'last_name' => 'حسینی',     'personnel_type' => 'Career'],

    //         // ── افسر ب (25 نفر) ─────────────────────────────
    //         ['rank_type' => 'officer_b', 'rank' => 'captain',           'first_name' => 'امیر',   'last_name' => 'صادقی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'captain',           'first_name' => 'سعید',   'last_name' => 'نجفی',      'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'captain',           'first_name' => 'جواد',   'last_name' => 'موسوی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'first_lieutenant',  'first_name' => 'کاظم',   'last_name' => 'علوی',      'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'first_lieutenant',  'first_name' => 'داود',   'last_name' => 'رحیمی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'first_lieutenant',  'first_name' => 'فرید',   'last_name' => 'ابراهیمی',  'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'first_lieutenant',  'first_name' => 'وحید',   'last_name' => 'قاسمی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'first_lieutenant',  'first_name' => 'نادر',   'last_name' => 'شریفی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'second_lieutenant', 'first_name' => 'بهروز',  'last_name' => 'منصوری',    'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'second_lieutenant', 'first_name' => 'شاهین',  'last_name' => 'طاهری',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'second_lieutenant', 'first_name' => 'پیمان',  'last_name' => 'جعفری',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'second_lieutenant', 'first_name' => 'کامران', 'last_name' => 'اکبری',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'second_lieutenant', 'first_name' => 'سجاد',   'last_name' => 'حیدری',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'third_lieutenant',  'first_name' => 'مصطفی', 'last_name' => 'نوروزی',    'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'third_lieutenant',  'first_name' => 'حامد',   'last_name' => 'یوسفی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'third_lieutenant',  'first_name' => 'سهیل',   'last_name' => 'پورمحمد',   'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'third_lieutenant',  'first_name' => 'آرش',    'last_name' => 'کمالی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'third_lieutenant',  'first_name' => 'ایمان',  'last_name' => 'صالحی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'third_lieutenant',  'first_name' => 'نیما',   'last_name' => 'ولی‌پور',   'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'third_lieutenant',  'first_name' => 'فرهاد',  'last_name' => 'اسدی',      'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'third_lieutenant',  'first_name' => 'بابک',   'last_name' => 'رستمی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'third_lieutenant',  'first_name' => 'امین',   'last_name' => 'غلامی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'third_lieutenant',  'first_name' => 'مجید',   'last_name' => 'عباسی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'third_lieutenant',  'first_name' => 'حسن',    'last_name' => 'زمانی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'officer_b', 'rank' => 'third_lieutenant',  'first_name' => 'علیرضا', 'last_name' => 'بهرامی',    'personnel_type' => 'Career'],

    //         // ── افسر وظیفه (3 نفر — اسم‌های مشخص) ──────────
    //         ['rank_type' => 'vazife_officer', 'rank' => 'second_lieutenant', 'first_name' => 'سینا',    'last_name' => 'آذیان',  'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_officer', 'rank' => 'second_lieutenant', 'first_name' => 'علیرضا',  'last_name' => 'زارعی', 'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_officer', 'rank' => 'second_lieutenant', 'first_name' => 'امید',    'last_name' => 'اربابی', 'personnel_type' => 'Conscription'],

    //         // ── درجه‌دار پایور (13 نفر) ─────────────────────
    //         ['rank_type' => 'nco', 'rank' => 'command_sergeant_major', 'first_name' => 'غلامرضا', 'last_name' => 'فراهانی',  'personnel_type' => 'Career'],
    //         ['rank_type' => 'nco', 'rank' => 'command_sergeant_major', 'first_name' => 'محمود',   'last_name' => 'دهقانی',   'personnel_type' => 'Career'],
    //         ['rank_type' => 'nco', 'rank' => 'sergeant_major',         'first_name' => 'اصغر',    'last_name' => 'ملکی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'nco', 'rank' => 'sergeant_major',         'first_name' => 'یوسف',    'last_name' => 'کوهی',     'personnel_type' => 'Career'],
    //         ['rank_type' => 'nco', 'rank' => 'sergeant_major',         'first_name' => 'حیدر',    'last_name' => 'سلیمانی',  'personnel_type' => 'Career'],
    //         ['rank_type' => 'nco', 'rank' => 'sergeant_first_class',   'first_name' => 'رحمت',    'last_name' => 'پناهی',    'personnel_type' => 'Career'],
    //         ['rank_type' => 'nco', 'rank' => 'sergeant_first_class',   'first_name' => 'منصور',   'last_name' => 'خدایی',    'personnel_type' => 'Career'],
    //         ['rank_type' => 'nco', 'rank' => 'sergeant_first_class',   'first_name' => 'تقی',     'last_name' => 'مرادی',    'personnel_type' => 'Career'],
    //         ['rank_type' => 'nco', 'rank' => 'staff_sergeant',         'first_name' => 'صادق',    'last_name' => 'ولایتی',   'personnel_type' => 'Career'],
    //         ['rank_type' => 'nco', 'rank' => 'staff_sergeant',         'first_name' => 'حجت',     'last_name' => 'امیری',    'personnel_type' => 'Career'],
    //         ['rank_type' => 'nco', 'rank' => 'staff_sergeant',         'first_name' => 'کریم',    'last_name' => 'بختیاری',  'personnel_type' => 'Career'],
    //         ['rank_type' => 'nco', 'rank' => 'sergeant',               'first_name' => 'ناصر',    'last_name' => 'طالبی',    'personnel_type' => 'Career'],
    //         ['rank_type' => 'nco', 'rank' => 'sergeant',               'first_name' => 'عباس',    'last_name' => 'قربانی',   'personnel_type' => 'Career'],

    //         // ── درجه‌دار وظیفه (21 نفر) ─────────────────────
    //         ['rank_type' => 'vazife_nco', 'rank' => 'sergeant_first_class', 'first_name' => 'ارشیا',  'last_name' => 'رحمانی',   'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'sergeant',             'first_name' => 'علی',    'last_name' => 'فیضی',     'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'مهران',  'last_name' => 'رضوی',     'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'سامان',  'last_name' => 'نادری',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'پارسا',  'last_name' => 'کاظمی',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'دانیال', 'last_name' => 'شکوهی',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'رامین',  'last_name' => 'فتحی',     'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'کیان',   'last_name' => 'مهدوی',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'آرمان',  'last_name' => 'درویشی',   'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'شایان',  'last_name' => 'میرزایی',  'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'پویا',   'last_name' => 'اسماعیلی', 'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'سروش',   'last_name' => 'حسن‌زاده', 'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'ماهان',  'last_name' => 'قلی‌زاده', 'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'علیرضا', 'last_name' => 'توکلی',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'امیرحسین','last_name'=> 'صفری',     'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'محمدرضا','last_name' => 'باقری',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'حسام',   'last_name' => 'سبزواری',  'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'بهنام',  'last_name' => 'خوشبخت',   'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'فرزاد',  'last_name' => 'ستاری',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'نوید',   'last_name' => 'جلالی',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'vazife_nco', 'rank' => 'corporal',             'first_name' => 'صالح',   'last_name' => 'عزیزی',    'personnel_type' => 'Conscription'],

    //         // ── سرباز (22 نفر) ──────────────────────────────
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'امیر',     'last_name' => 'معصومی',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'مهدی',     'last_name' => 'شاهی',      'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'رضا',      'last_name' => 'کرمی',      'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'علی',      'last_name' => 'منتظری',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'حسین',     'last_name' => 'پیری',      'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'محمد',     'last_name' => 'وحدتی',     'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'سینا',     'last_name' => 'مختاری',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'کوروش',    'last_name' => 'نظری',      'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'بهزاد',    'last_name' => 'حاتمی',     'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'میلاد',    'last_name' => 'صمدی',      'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'سپهر',     'last_name' => 'افشار',     'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'ابراهیم',  'last_name' => 'نیکو',      'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'محسن',     'last_name' => 'وفایی',     'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'ارسلان',   'last_name' => 'تاجیک',     'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'منوچهر',   'last_name' => 'شیرازی',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'فرید',     'last_name' => 'سهرابی',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'بهرام',    'last_name' => 'پاکدل',     'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'کامبیز',   'last_name' => 'سروری',     'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'داریوش',   'last_name' => 'فروزان',    'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'شهرام',    'last_name' => 'احمدپور',   'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'نیلوفر',   'last_name' => 'رضایی',     'personnel_type' => 'Conscription'],
    //         ['rank_type' => 'soldier', 'rank' => 'private', 'first_name' => 'وریا',     'last_name' => 'محمودی',    'personnel_type' => 'Conscription'],
    //     ];

    //     foreach ($personnel as $index => $p) {
    //         Personnel::create([
    //             'company_id'      => $companyId,
    //             'personnel_type'  => $p['personnel_type'],
    //             'rank_type'       => $p['rank_type'],
    //             'rank'            => $p['rank'],
    //             'first_name'      => $p['first_name'],
    //             'last_name'       => $p['last_name'],
    //             'national_code'   => '00' . str_pad($index + 1, 8, '0', STR_PAD_LEFT),
    //             'personnel_number'=> 'P' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
    //             'status'          => 'active',
    //             'service_start_date' => now()->subMonths(rand(1, 18))->toDateString(),
    //             'service_end_date'   => now()->addMonths(rand(1, 18))->toDateString(),
    //         ]);
    //     }

    //     $this->command->info('✅ ' . count($personnel) . ' نفر پرسنل ثبت شد');
    }
}
