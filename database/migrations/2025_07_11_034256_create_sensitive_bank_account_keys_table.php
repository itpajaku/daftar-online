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
        Schema::create('sensitive_bank_account_keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_account_id')->unique();
            $table->string('hash_nomor_rekening');
            $table->foreign('bank_account_id')
                ->references('id')
                ->on('bank_accounts')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesitive_bank_account_keys');
    }
};
