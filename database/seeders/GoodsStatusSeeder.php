<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory\GoodsStatus; // Asegúrate de tener este modelo

class GoodsStatusSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Obsoleto'],
            ['name' => 'Malo'],
            ['name' => 'Regular'],
            ['name' => 'Bueno'],
            ['name' => 'Nuevo'],
        ];

        foreach ($data as $item) {
            GoodsStatus::create($item);
        }
    }
}
