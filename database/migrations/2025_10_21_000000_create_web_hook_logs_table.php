<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('web_hook_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('web_hook_id')->nullable()->constrained('web_hooks')->nullOnDelete();
      $table->string('event')->nullable();
      $table->json('payload')->nullable();
      $table->integer('status_code')->nullable();
      $table->text('response')->nullable();
      $table->boolean('success')->default(false);
      $table->text('error_message')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('web_hook_logs');
  }
};
