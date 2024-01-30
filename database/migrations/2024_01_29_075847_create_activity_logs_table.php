<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create("activity_logs", function (Blueprint $table) {
      $table->id();

      $table->unsignedInteger("user_id");
      $table
        ->foreign("user_id")
        ->references("ID")
        ->on("user_account")
        ->onDelete("NO ACTION");

      $table->string("name");
      $table->text("description");
      $table->string("subject");

      $table->longText("history");

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
    Schema::dropIfExists("activity_logs");
  }
};
