<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            ['name' => 'Abierto', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'En proceso', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Cerrado', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Cancelado', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Terminado', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('process_status')->insert($data);
    }
}
