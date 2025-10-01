<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $datos = [
            array(
                'name' => 'Fijo',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            array(
                'name' => 'Medido',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),

        ];
        DB::table('service_types')->insert($datos);
    }
}
