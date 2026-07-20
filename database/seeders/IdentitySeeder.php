<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory;

class IdentitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('id_ID');

        $banks = [
            'BCA',
            'BRI',
            'BNI',
            'Mandiri',
            'BTN',
        ];

        $pekerjaan = [
            'PNS',
            'Wiraswasta',
            'Karyawan Swasta',
            'Guru',
            'Ibu Rumah Tangga',
            'Ojek Online',
            'Pegawai ',
            'Supir Online'
        ];

        $pendidikan = [
            'SMA',
            'S1',
        ];

        $status = [
            'Kawin',
            'Cerai Hidup',
        ];

        $agama = [
            'Islam',
        ];

        for ($i = 1; $i <= 100; $i++) {

            // tanggal dibuat antara Januari sampai hari ini
            $createdAt = Carbon::create(now()->year, 1, 1)
                ->addDays(rand(0, now()->dayOfYear - 1))
                ->addHours(rand(0, 23))
                ->addMinutes(rand(0, 59))
                ->addSeconds(rand(0, 59));

            $updatedAt = (clone $createdAt)->addDays(rand(0, 30));

            $gender = rand(0, 1) ? 'Laki-laki' : 'Perempuan';

            $identityId = DB::table('identities')->insertGetId([
                'nama_lengkap' => $faker->name($gender == 'Laki-laki' ? 'male' : 'female'),
                'jenis_kelamin' => $gender,
                'tanggal_lahir' => $faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
                'tempat_lahir' => $faker->city,
                'nomor_kependudukan' => $faker->numerify('################'),
                'nomor_telepon' => '08' . $faker->numerify('##########'),
                'email' => $faker->unique()->safeEmail,
                'pekerjaan' => $faker->randomElement($pekerjaan),
                'pendidikan' => $faker->randomElement($pendidikan),
                'status_perkawinan' => $faker->randomElement($status),
                'agama' => $faker->randomElement($agama),
                'alamat' => $faker->address,
                'kewarganegaraan' => 'WNI',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);

            DB::table('bank_accounts')->insert([
                'identity_id' => $identityId,
                'nama_bank' => $faker->randomElement($banks),
                'nomor_rekening' => $faker->bankAccountNumber,
                'nama_akun' => $faker->name,
                'file_ktp' => 'ktp/' . $faker->uuid . '.jpg',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
        }
    }
}
