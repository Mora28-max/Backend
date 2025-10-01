<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClassificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $datos = [
            array(
                'name' => 'Menor Consumo',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'name' => 'Mayor Consumo',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'name' => 'Clasificación I',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'name' => 'Clasificación II',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'name' => 'Clasificación III',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'name' => 'Clasificación IV',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array(
                'name' => 'Eventual',
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ];
        DB::table('classification_types')->insert($datos);
    }
}
