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
        Schema::create('sensitive_identity_keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('identities_id')->unique();
            $table->string('hash_nik');
            $table->string('hash_nomor_telepon');
            $table->foreign('identities_id')
                ->references('id')
                ->on('identities')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesitive_identity_keys');
    }
};
