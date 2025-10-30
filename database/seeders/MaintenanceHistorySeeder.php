<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Maintenance\MaintenanceHistory;
use App\Models\Maintenance\TypeMaintenance;
use App\Models\Inventory\Goods;
use App\Models\User;

class MaintenancehistorySeeder extends Seeder
{
    public function run(): void
    {
        // Datos fijos de ejemplo, sin depender de otras tablas
        $data = [
            [
                'id_goods' => 1,
                'id_type_maintenance' => 1,
                'id_user' => 1,
                'date' => '2025-01-10',
                'observations' => 'Mantenimiento preventivo',
                'cost' => 250.00,
                'next_maintenance_date' => '2025-06-10',
                // 'code' se asigna automáticamente desde el modelo
            ],
            [
                'id_goods' => 2,
                'id_type_maintenance' => 2,
                'id_user' => 2,
                'date' => '2025-02-15',
                'observations' => 'Revisión general',
                'cost' => 400.00,
                'next_maintenance_date' => '2025-07-15',
            ],
            [
                'id_goods' => 1,
                'id_type_maintenance' => 2,
                'id_user' => 1,
                'date' => '2025-03-20',
                'observations' => 'Mantenimiento correctivo',
                'cost' => 300.00,
                'next_maintenance_date' => '2025-09-20',
            ],
        ];

        foreach ($data as $item) {
            MaintenanceHistory::create($item);
        }
    }
}