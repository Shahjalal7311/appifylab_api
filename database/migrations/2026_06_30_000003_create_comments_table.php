<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('comments', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('user_id')->unsigned();
      $table->bigInteger('post_id')->unsigned(); 
      $table->bigInteger('parent_id')->nullable()->unsigned();
      $table->text('body');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('comments');
  }
};
