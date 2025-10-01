<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExtraordinaryAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'name' => 'Material para descarga de drenaje',
                'code' => 'EXTRA-0001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Material para mantenimiento de toma',
                'code' => 'EXTRA-0002',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Derecho de conexión de toma de agua potable',
                'code' => 'EXTRA-0003',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Mano de obra instalacion de toma de agua',
                'code' => 'EXTRA-0004',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Corte de pavimento',
                'code' => 'EXTRA-0005',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Reposicion de pavimento',
                'code' => 'EXTRA-0006',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Reconexion de toma de agua',
                'code' => 'EXTRA-0007',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Multa por reconexion no autorizada',
                'code' => 'EXTRA-0008',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Multa por desperdicio de agua',
                'code' => 'EXTRA-0009',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Multa derrames a la via publica',
                'code' => 'EXTRA-0010',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Constancia de no adeudo',
                'code' => 'EXTRA-0011',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Constancia de factibilidad de servicio',
                'code' => 'EXTRA-0012',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Constancia de vivienda deshabitada',
                'code' => 'EXTRA-0013',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Cambio de micromedidor',
                'code' => 'EXTRA-0014',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Constancia de no servicio',
                'code' => 'EXTRA-0015',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Micromedidor y piezas especiales',
                'code' => 'EXTRA-0016',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sancion por la reconexion',
                'code' => 'EXTRA-0017',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sancion por la reconexion clandestina',
                'code' => 'EXTRA-0018',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Mano de obra para descarga',
                'code' => 'EXTRA-0019',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Material para la instalacion de micromedidor',
                'code' => 'EXTRA-0020',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Reparacion de fuga',
                'code' => 'EXTRA-0021',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sancion por desperdicio de agua',
                'code' => 'EXTRA-0022',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Cambio de propietario',
                'code' => 'EXTRA-0023',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Trabajos extras',
                'code' => 'EXTRA-0024',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Reubicación de toma',
                'code' => 'EXTRA-0025',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('extraordinary_accounts')->insert($data);
    }
}
