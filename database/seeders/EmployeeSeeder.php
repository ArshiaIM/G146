<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee\Employee;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $employees = [];

        // 4 تا rank_id = 9 با unit_id متفاوت
        $employees[] = [
            'unit_id' => 2,
            'employee_type_id' => 1,
            'employee_rank_id' => 9,
            'first_name' => 'علی',
            'last_name' => 'زارع',
            'national_id' => fake()->unique()->numerify('##########'),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $employees[] = [
            'unit_id' => 3,
            'employee_type_id' => 1,
            'employee_rank_id' => 9,
            'first_name' => 'محمد',
            'last_name' => 'آزادی',
            'national_id' => fake()->unique()->numerify('##########'),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $employees[] = [
            'unit_id' => 4,
            'employee_type_id' => 1,
            'employee_rank_id' => 8,
            'first_name' => 'محسن',
            'last_name' => 'حیدری',
            'national_id' => fake()->unique()->numerify('##########'),
            'created_at' => $now,
            'updated_at' => $now,
        ];


        // 1 عدد rank_id = 6
        $employees[] = [
            'unit_id' => 1,
            'employee_type_id' => 1,
            'employee_rank_id' => 6,
            'first_name' => 'محسن',
            'last_name' => 'دهداری',
            'national_id' => fake()->unique()->numerify('##########'),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // 1 عدد rank_id = 7
        $employees[] = [
            'unit_id' => 1,
            'employee_type_id' => 1,
            'employee_rank_id' => 7,
            'first_name' => 'رضا',
            'last_name' => 'بیضایی',
           'national_id' => fake()->unique()->numerify('##########'),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        for ($i = 1; $i <= 194; $i++) {

            $unit = 1;
            if ($i % 20 === 0) $unit = 2;
            if ($i % 35 === 0) $unit = 3;
            if ($i % 50 === 0) $unit = 4;

            // -------- TYPE LOGIC --------
            if ($i <= 140) {
                // ~70%
                $type = rand(6, 7);
            } elseif ($i <= 152) {
                // 8 تا 12 رکورد type = 4
                $type = 4;
            } else {
                $type = 5;
            }

            // -------- RANK LOGIC --------
            if ($type == 4) {
                $rank = rand(9, 11);
            } elseif ($type == 5) {
                $rank = rand(12, 16);
            } else {
                $rank = rand(6, 11);
            }

            $employees[] = [
                'unit_id' => $unit,
                'employee_type_id' => $type,
                'employee_rank_id' => $rank,
                'first_name' => 'کارمند',
                'last_name' => "AUTO_$i",
                'national_id' => fake()->unique()->numerify('##########'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Employee::insert($employees);
    }
}

