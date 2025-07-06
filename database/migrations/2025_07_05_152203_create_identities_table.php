<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('identities', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('jenis_kelamin');
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->string('nomor_kependudukan', 255);
            $table->string('nomor_telepon', 255);
            $table->string('email');
            $table->string('pekerjaan');
            $table->string('pendidikan');
            $table->string('status_perkawinan');
            $table->string('agama');
            $table->string('alamat', 512);
            $table->string('kewarganegaraan')->default('WNI'); //
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identities');
    }
};
