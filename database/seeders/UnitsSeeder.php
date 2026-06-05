<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitsSeeder extends Seeder
{
    public function run(): void
    {

        Unit::insert([
            ['id' => 1, 'name' => 'گروهان ارکان', 'battalion_id' => 6],
            ['id' => 2, 'name' => 'گروهان یکم' , 'battalion_id' => 6],
            ['id' => 3, 'name' => 'گروهان دوم' , 'battalion_id' => 6],
            ['id' => 4, 'name' => 'گروهان سوم' , 'battalion_id' => 6],
        ]);
    }
}
