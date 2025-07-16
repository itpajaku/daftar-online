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
        Schema::create('ecourt_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('identity_id');
            $table->string('username');
            $table->string('password');
            $table->foreign('identity_id')->references('id')->on('identities')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecourt_accounts');
    }
};
