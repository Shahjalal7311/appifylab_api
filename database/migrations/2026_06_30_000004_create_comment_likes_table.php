<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('comment_likes', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('user_id')->unsigned();
      $table->bigInteger('comment_id')->unsigned();
      $table->timestamps();

      $table->unique(['user_id', 'comment_id']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('comment_likes');
  }
};
