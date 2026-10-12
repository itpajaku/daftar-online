<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('identities', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('kewarganegaraan');
            $table->text('alasan_penolakan')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('alasan_penolakan');
        });

        // Set status 'verified' for identities that already have ecourt_accounts
        DB::table('identities')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('ecourt_accounts')
                    ->whereColumn('ecourt_accounts.identity_id', 'identities.id');
            })
            ->update(['status' => 'verified']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('identities', function (Blueprint $table) {
            $table->dropColumn(['status', 'alasan_penolakan', 'rejected_at']);
        });
    }
};
