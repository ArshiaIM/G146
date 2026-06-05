<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Employee\EmployeeRank;

class EmployeeRankSeeder extends Seeder
{
    public function run(): void
    {


        EmployeeRank::insert([
            ['id' => 1, 'name' => 'سرباز'],
            ['id' => 2, 'name' => 'سرباز دوم'],
            ['id' => 3, 'name' => 'سرباز یکم'],

            ['id' => 4, 'name' => 'گروهبان سوم'],
            ['id' => 5, 'name' => 'گروهبان دوم'],
            ['id' => 6, 'name' => 'گروهبان یکم'],

            ['id' => 7, 'name' => 'استوار دوم'],
            ['id' => 8, 'name' => 'استوار یکم'],

            ['id' => 9, 'name' => 'ستوان سوم'],
            ['id' => 10, 'name' => 'ستوان دوم'],
            ['id' => 11, 'name' => 'ستوان یکم'],

            ['id' => 12, 'name' => 'سروان'],
            ['id' => 13, 'name' => 'سرگرد'],

            ['id' => 14, 'name' => 'سرهنگ دوم'],
            ['id' => 15, 'name' => 'سرهنگ'],

            ['id' => 16, 'name' => 'سرتیپ'],
            ['id' => 17, 'name' => 'سرتیپ دوم'],

            ['id' => 18, 'name' => 'سرلشکر'],
            ['id' => 19, 'name' => 'سپهبد'],
            ['id' => 20, 'name' => 'ارتشبد'],
        ]);
    }
}
