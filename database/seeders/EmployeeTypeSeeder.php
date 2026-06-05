<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee\EmployeeType;

class EmployeeTypeSeeder extends Seeder
{
    public function run(): void
    {

        EmployeeType::insert([
            ['id' => 1, 'name' => 'افسر الف'],
            ['id' => 2, 'name' => 'افسر ب'],
            ['id' => 3, 'name' => 'افسر وظیفه'],
            ['id' => 4, 'name' => 'درجه‌دار پایور'],
            ['id' => 5, 'name' => 'درجه‌دار وظیفه'],
            ['id' => 6, 'name' => 'سرباز'],
        ]);
    }
}
