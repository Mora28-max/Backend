<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommitteesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'code' => 'C1',
                'name' => 'Comité de Agua Potable Tatoxcac, "La concensionaria"',
                'notes' => '5pue102728/27hoge96',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'C2',
                'name' => 'Comité de Agua Potable "La Libertad"',
                'notes' => 'La libertad, Zacapoaxtla, Puebla (10pue110881/27hmge00)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'C3',
                'name' => 'Comité de Tatoxcac 2',
                'notes' => 'Zacapoaxtla, Puebla',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'C4',
                'name' => 'Comité de Agua Potable de "Las Lomas" 2',
                'notes' => 'Las Lomas, Zacapoaxtla, Puebla',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'C5',
                'name' => 'Comité de Agua Potable de "Mahuatitan"',
                'notes' => 'Comaltepec 2, Zacapoaxtla, Puebla',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'C6',
                'name' => 'C. Gelacio Muñoz Calderón y Emelia Juarez Rojas',
                'notes' => 'Xalticpac #43',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'C7',
                'name' => 'Las Lomas manantial "OMIQUILA"',
                'notes' => 'N. T. 10pue114044/27hogr06',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'C8',
                'name' => 'Aprovechamiento "Molinagco"',
                'notes' => 'Tatoxcac, Zacapoaxtla, Puebla',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'code' => 'C9',
                'name' => 'Aprovechamiento "Atepolihui"',
                'notes' => 'Tatoxcac, Zacapoaxtla, Puebla',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('committees')->insert($data);
    }
}
