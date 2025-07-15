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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin Satker',
            'email' => 'admin@mahkamahagungri.go.id',
            'password' => bcrypt('Paju400622'),
        ]);

        Setting::create([
            'key' => 'theme',
            'value' => 'light',
        ]);
    }
}
