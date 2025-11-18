<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory\Goods; // Asegúrate de tener este modelo creado

class GoodsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'Laptop HP',
                'description' => 'Laptop nueva',
                'brand' => 'HP',
                'id_status' => 5, // Nuevo
                'stock' => 10,
                'id_category' => 1,
                'url_evidence' => null,
                'url_invoice' => null,  // 📄 Factura o comprobante
                'id_user' => 1,
                'id_provider' => 1,
            ],
            [
                'name' => 'Silla Oficina',
                'description' => 'Silla ergonómica',
                'brand' => 'IKEA',
                'id_status' => 4, // Bueno
                'stock' => 5,
                'id_category' => 2,
                'url_evidence' => null,
                'url_invoice' => null,  // 📄 Factura o comprobante
                'id_user' => 2,
                'id_provider' => 1,
            ],
        ];

        foreach ($data as $item) {
            Goods::create($item);
        }
    }
}
