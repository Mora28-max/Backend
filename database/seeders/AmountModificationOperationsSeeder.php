<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AmountModificationOperationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'name' => 'SURCHARGE',
                'description' => 'Sujeción',
            ],
            [
                'name' => 'DISCOUNT_ALL',
                'description' => 'Descuento total',
            ],
            [
                'name' => 'DISCOUNT_SURCHARGE',
                'description' => 'Descuento de sujeción',
            ],
            [
                'name' => 'DISCOUNT_WATER',
                'description' => 'Descuento de agua',
            ],
            [
                'name' => 'DISCOUNT_DRAINAGE',
                'description' => 'Descuento de drenaje',
            ],
            [
                'name' => 'DISCOUNT_WATER_AND_DRAINAGE',
                'description' => 'Descuento de agua y drenaje',
            ],
            [
                'name' => 'FORGIVE_AMOUNTS',
                'description' => 'Condonación',
            ],
            [
                'name' => 'RESET',
                'description' => 'Reetablecer el descuento',
            ],
            [
                'name' => 'REVERSE_FORGIVENESS',
                'description' => 'Revertir condonación',
            ],
        ];

        DB::table('amount_modification_operations')->insert($data);
    }
}
