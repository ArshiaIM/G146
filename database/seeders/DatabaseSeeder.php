<?php

namespace Database\Seeders;

use App\Models\Battalion;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // DatabaseSeeder.php

        $battalions = [
            ['name' => 'گردان 146', 'code' => '146', 'companies' => [
                ['name' => 'ارکان',     'username' => '46ark',],
                ['name' => 'گروهان 1',  'username' => '46co1',],
                ['name' => 'گروهان 2',  'username' => '46co2',],
                ['name' => 'گروهان 3',  'username' => '46co3',],
            ]],
            ['name' => 'گردان 246', 'code' => '246', 'companies' => [
                ['name' => 'ارکان',     'username' => '46ark2',],
                ['name' => 'گروهان 1',  'username' => '26co1',],
                // ...
            ]],
            // بقیه گردان‌ها
        ];

        foreach ($battalions as $batData) {
            $battalion = Battalion::create([
                'name' => $batData['name'],
                'code' => $batData['code'],
            ]);

            foreach ($batData['companies'] as $coData) {
                $company = Company::create([
                    'battalion_id' => $battalion->id,
                    'name'         => $coData['name'],
                    'code'         => $coData['username'],
                ]);

                User::create([
                    'name'       => $coData['name'] . ' ' . $batData['name'],
                    'username'   => $coData['username'],
                    'password'   => Hash::make('1234'),
                    'role'       => 'company_admin',
                    'company_id' => $company->id,
                ]);
            }
        }
    }
}
