<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'firstname' => 'Juan',
                'lastname' => 'Bernardo Amador González',
                'email' => 'j_b182@hotmail.com',
                'phone' => 2225070410,
                'password' => '$2y$04$/khFCFkAZ7bKaaexw5ZqVutZ8517TbwpWNd40q8LJ4VwcNKPLG1Uy',
                'role' => 'Admin',
            ],
            [
                'firstname' => 'Arnoldo',
                'lastname' => 'Felix Xocota',
                'email' => 'fexarnol@gmail.com',
                'phone' => 2331467525,
                'password' => '$2y$04$WoCsPjQmUpmjgSkQH7cdee6NUBU31sM6to.FNgp6pQQU0Zqu8sU9.',
                'role' => 'General Administrator',
            ],
            [
                'firstname' => 'Fernando',
                'lastname' => 'Sosa Bonilla',
                'email' => 'fernandososabonilla@gmail.com',
                'phone' => 2222386034,
                'password' => '$2y$04$BwSv1MvnkWgJ2YU9BYvgduCiLV7eM322H6o4ww/gFbLZnYDq0jv1i',
                'role' => 'Technical Assistant',
            ],
            [
                'firstname' => 'Beatriz Elizabeth',
                'lastname' => 'Rodríguez González',
                'email' => 'bevamay05@gmail.com',
                'phone' => 2331536875,
                'password' => '$2y$04$rvltzjG13pnOhmjVrdztA..kzWuvPAcxzwYAxTzeKQ/AMksgIuqlu',
                'role' => 'Cashier',
            ],
            [
                'firstname' => 'Araceli',
                'lastname' => 'Morales',
                'email' => 'aracelimcas@gmail.com',
                'phone' => 2331233415,
                'password' => '$2y$12$RRiRTlJeSA/mZ.gSM.YeQ.95J3v5YrBytOyw/PSIryG22prid97bW',
                'role' => 'General Assistant B',
            ],
            [
                'firstname' => 'Carlos',
                'lastname' => 'Cabrera Carreón',
                'email' => 'carloscabreracarreon@gmail.com',
                'phone' => 2331258972,
                'password' => '$2y$10$48LCRJBkK7gWdhsISXru3uwJiCV6LB0MaJX8mwAaKzjd6VQ0YcSZm',
                'role' => 'Developer',
            ],
            [
                'firstname' => 'Mario Alexis',
                'lastname' => 'Vázquez Peralta',
                'email' => 'somapaz2024@gmail.com',
                'phone' => 2331343478,
                'password' => '$2y$04$1FTslRCwY7ZdewvBT7OWU.MLM6AmmRmUaNvQ9xf.Fhaf1urI.XaZi',
                'role' => 'Developer',
            ],
            [
                'firstname' => 'Emilio',
                'lastname' => 'Bonilla Palafox',
                'email' => 'somapaz.operacion@gmail.com',
                'phone' => 2331164774,
                'password' => '$2y$10$rbDHX.kHDGClBWH99SSNYeaixlHqIlJjy7Zogm9jTchPSn.s32IQi',
                'role' => 'Technical Coordinator',
            ],
            [
                'firstname' => 'Cesar Azarí',
                'lastname' => 'García Vázquez',
                'email' => 'cesafage06@gmail.com',
                'phone' => 5655100017,
                'password' => '$2y$10$pcZPSGCeBdeof6PIRE8Tr.SrPohlx642k75cnsDff0AJ9x6PMkaqe',
                'role' => 'General Assistant A',
            ],
            [
                'firstname' => 'María Fernanda',
                'lastname' => 'González Palafox',
                'email' => 'laimafe@hotmail.com',
                'phone' => 2224949789,
                'password' => '$2y$04$ZiMCKUxBYRItXKBshZrN6.zU2E697E1cIHnp6ld7/euGPjhpukPJW',
                'role' => 'General Assistant B',
            ],
            [
                'firstname' => 'Miguel',
                'lastname' => 'Encarnación Polvo',
                'email' => 'encarnacionpolvomiguel@gmail.com',
                'phone' => 2331266804,
                'password' => '$2y$10$kyk5YYzq7z2d3hiNXw6XYeRY0JaLGS9Le1nnXbZp3T9rs.HKFyffq',
                'role' => 'Plumber',
            ],
            [
                'firstname' => 'Raul',
                'lastname' => 'Portillo',
                'email' => 'portilloraul375@gmail.com',
                'phone' => 9876543210,
                'password' => '$2y$04$tgLT.LSB3V1g.BaewnuJjuXimFIulaTx4s8htyYLQ2sDy3aAydUmG',
                'role' => 'Plumber',
            ],
            [
                'firstname' => 'Lidia',
                'lastname' => 'Cordova Morales',
                'email' => 'zsomapaz@gmail.com',
                'phone' => 2331157097,
                'password' => '$2y$10$9lekWhxmGOUtr.l7WSIWvuTRO2hkYVBp6KJ5wQuZPTdU32YzJMrnK',
                'role' => 'Notifier',
            ],
            [
                'firstname' => 'Emelia Monserrath',
                'lastname' => 'Morales Diaz',
                'email' => 'mate_morlsdiaz@hotmail.com',
                'phone' => 2331249006,
                'password' => '$2y$04$b8Ke6wupipRp1bLBrPtCAu6NwcksuSlj.6.Dv2FpXSjR44.jCBE7u',
                'role' => 'Notifier',
            ],
            [
                'firstname' => 'María del Carmen',
                'lastname' => 'Mora Calderón',
                'email' => 'meysulis04@gmail.com',
                'phone' => 2331187785,
                'password' => '$2y$10$fVIK2Htu8kKcasGl1CKC2OqnyJlk4D0dLktnlYZTJLoeDDnHMB2FW',
                'role' => 'General Assistant B',
            ],
            [
                'firstname' => 'Andervan',
                'lastname' => 'Romero Sanchez',
                'email' => 'anderrmo1695@gmail.com',
                'phone' => 5511931460,
                'password' => '$2y$10$fbXKh4aBVZgKDne.oFq/Y.JmqDMMsGqCtmHtZiBr7Rf3Wko9VVN1S',
                'role' => 'General Assistant B',
            ]
        ];

        foreach ($users as $data) {
            $user = User::create([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $user->assignRole($data['role']);
        }
    }
}
