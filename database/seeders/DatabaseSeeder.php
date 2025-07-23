<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::insert([
            'name' => 'Admin Satker',
            'email' => 'admin@pajakartautara.id',
            'password' => bcrypt('paju400622'),
        ]);

        Setting::insert([
            [
                'key' => 'theme',
                'value' => 'light',
            ],
            [
                'key' => 'wa_admin_ecourt',
                'value' => '6289636811489'
            ],
            [
                'key' => 'admin_ecourt',
                'value' => 'Imal Malik'
            ],
            [
                'key' => 'satker',
                'value' => 'Pengadilan Agama Jakarta Utara'
            ],
        ]);
    }
}
