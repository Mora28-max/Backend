<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReportPrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            ['name' => 'Muy baja', 'color' => 'cyan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Baja', 'color' => 'green', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Media', 'color' => 'yellow', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Alta', 'color' => 'red', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Crítica', 'color' => 'gray', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('report_priorities')->insert($data);
    }
}
