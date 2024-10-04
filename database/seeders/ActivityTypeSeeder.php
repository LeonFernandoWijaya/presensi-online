<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('activity_types')->insert([
            ['name' => 'WFO'],
            ['name' => 'WFH'],
            ['name' => 'Remote']
        ]);
    }
}
