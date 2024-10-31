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
    Schema::create('salla_payloads', function (Blueprint $table) {
      $table->id();
      $table->string('access_token');
      $table->string('expires');
      $table->string('refresh_token');
      $table->string('scope');
      $table->string('token_type');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('salla_payloads');
  }
};
