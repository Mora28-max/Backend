<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory\InventoryMaterial;

class InventoryMaterialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $data = [
            [
                'name' => 'Cable de red Cat6',
                'stock' => 150,
                'description' => 'Cable de red para conexión LAN',
                'cost' => 45.50,
                'url_evidence' => null,
                'provider_id' => 1,
                'unit_type_id' => 1,
                'id_user' => 1,
            ],
            [
                'name' => 'Tubo PVC 3/4"',
                'stock' => 90,
                'description' => 'Tubo de PVC para instalaciones eléctricas',
                'cost' => 32.75,
                'url_evidence' => null,
                'provider_id' => 2,
                'unit_type_id' => 1,
                'id_user' => 1,
            ],
        ];

        foreach ($data as $item) {
            InventoryMaterial::create($item);
        }
    }
}