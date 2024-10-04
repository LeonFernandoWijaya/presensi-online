<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('activity_categories')->insert([
            ['name' => 'No Activity'],
            ['name' => 'PM'],
            ['name' => 'CM'],
            ['name' => 'Instalasi'],
            ['name' => 'Other'],
        ]);
    }
}
