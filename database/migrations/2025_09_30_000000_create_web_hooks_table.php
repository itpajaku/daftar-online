<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('web_hooks', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('url');
      $table->string('event');
      $table->enum('type', ['GET', 'POST'])->default('POST');
      $table->text('body')->nullable();
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('web_hooks');
  }
};
