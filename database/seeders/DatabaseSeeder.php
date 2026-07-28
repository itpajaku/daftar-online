<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@pengadilan.go.id')],
            [
                'name' => 'Admin Satker',
                'password' => env('ADMIN_PASSWORD', 'pengadilanindonesia'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10)
            ]
        );

        if (Setting::count() === 0) {
            Setting::insert([
                [
                    'key' => 'theme',
                    'value' => 'light',
                ],
                [
                    'key' => 'wa_admin_ecourt',
                    'value' => env('WA_ADMIN_ECOURT', '6281234567890')
                ],
                [
                    'key' => 'admin_ecourt',
                    'value' => env('ADMIN_ECOURT', 'Admin Ecourt')
                ],
                [
                    'key' => 'satker',
                    'value' => env('SATKER', 'Pengadilan Negeri Contoh')
                ],
            ]);
        }
        // $this->call([
        //     IdentitySeeder::class,
        // ]);
    }
}
