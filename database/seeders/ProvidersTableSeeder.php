<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory\Provider;

class ProvidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $data = [
            
            [
                'name' => 'Herramientas López',
                'rfc' => 'LOPF789456CD2',
                'person_type_id' => 1, // Física
                'address' => 'Av. Morelos #45, Guadalajara',
                'phone' => '3337894561',
                'email' => 'ventas@herramientaslopez.mx',
                'id_user' => 1,
                'status_id' => 2, // Inactivo
                'url_evidence' => 'https://ejemplo.com/evidencia_fisica2.pdf',
                'id_type' => 0,
            ],
            [
                'name' => 'Proveedor Eléctrico S.A.',
                'rfc' => 'ELEC123456AB7',
                'person_type_id' => 2, // Moral
                'address' => 'Av. Central #45, Ciudad de México',
                'phone' => '5551234567',
                'email' => 'contacto@electricosa.com',
                'id_user' => 1,
                'status_id' => 1, // Activo
                'url_evidence' => 'https://ejemplo.com/evidencia_moral1.pdf',
                'id_type' => 0,
            ],
            [
                'name' => 'Materiales Industriales del Norte',
                'rfc' => 'MIN980765PL2',
                'person_type_id' => 2, // Moral
                'address' => 'Calle Hidalgo #22, Monterrey, NL',
                'phone' => '8112345678',
                'email' => 'ventas@materialesnorte.mx',
                'id_user' => 1,
                'status_id' => 2, // Inactivo
                'url_evidence' => 'https://ejemplo.com/evidencia_moral2.pdf',
                'id_type' => 1,
            ],
        ];

        foreach ($data as $item) {
            Provider::create($item);
        }
    }
}
