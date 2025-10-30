<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory\Category; // Asegúrate de tener el modelo Category creado

class CategorySeeder extends Seeder
{
    public function run(): void
    {$data = [
             
            // 🔹 Categorías de Bienes
            ['name' => 'Muebles de oficina y estantería', 'type' => 'Bienes'],
            ['name' => 'Equipo de cómputo y tecnología de la información', 'type' => 'Bienes'],
            ['name' => 'Equipo de transporte', 'type' => 'Bienes'],
            ['name' => 'Maquinaria y equipo de construcción', 'type' => 'Bienes'],
            ['name' => 'Equipo de telecomunicación', 'type' => 'Bienes'],
            ['name' => 'Herramientas, máquinas y herramientas', 'type' => 'Bienes'],
            ['name' => 'Otros equipos', 'type' => 'Bienes'],
            ['name' => 'Activos intangibles', 'type' => 'Bienes'],

            // 🔹 Categorías de Inmuebles
            ['name' => 'Terrenos', 'type' => 'Inmuebles'],
            ['name' => 'Edificios no habitacionales', 'type' => 'Inmuebles'],
        ];

        foreach ($data as $item) {
            Category::create($item);
        }
    }
}
