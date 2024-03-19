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
    Schema::create("farm_buildings", function (Blueprint $table) {
      $table->id();

      $table->unsignedBigInteger("farm_id")->index();
      $table
        ->foreign("farm_id")
        ->references("id")
        ->on("farms")
        ->onDelete("cascade");

      $table->unsignedBigInteger("building_id")->index();
      $table
        ->foreign("building_id")
        ->references("id")
        ->on("buildings")
        ->onDelete("cascade");

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
    Schema::dropIfExists("farm_buildings");
  }
};
