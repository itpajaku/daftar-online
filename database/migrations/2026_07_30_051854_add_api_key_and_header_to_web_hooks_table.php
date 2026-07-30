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
        Schema::table('web_hooks', function (Blueprint $table) {
            $table->string('api_key')->nullable()->after('url');
            $table->string('header_auth_name')->nullable()->default('Authorization')->after('api_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_hooks', function (Blueprint $table) {
            $table->dropColumn(['api_key', 'header_auth_name']);
        });
    }
};
