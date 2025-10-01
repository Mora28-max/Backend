<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('customer_types')->insert([
            'name' => 'Normal',
            'description' => 'Cliente normal',
            'percentage_disount' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('customer_types')->insert([
            'name' => 'INAPAM',
            'description' => 'Cliente mayor de 60 años que puedan seguir trabajando',
            'percentage_disount' => 0.5,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('customer_types')->insert([
            'name' => 'Pensionado o Jubilado',
            'description' => 'Cliente que dejó de trabajar por su edad y percibe una pensión.',
            'percentage_disount' => 0.3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
