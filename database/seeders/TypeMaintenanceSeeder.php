<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Maintenance\TypeMaintenance;

class TypeMaintenanceSeeder extends Seeder
{
   public function run(): void
    {
        $data = [
            ['name' => 'Preventivo'],
            ['name' => 'Correctivo'],
            ['name' => 'Predictivo'],
        ];

        foreach ($data as $item) {
            TypeMaintenance::create($item);
        }
    }
}
   

