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
    Schema::create("information", function (Blueprint $table) {
      $table->id();

      $table->unsignedBigInteger("user_id")->index();
      $table
        ->foreign("user_id")
        ->references("id")
        ->on("users")
        ->onDelete("CASCADE");

      $table->unsignedBigInteger("category_id")->index();
      $table
        ->foreign("category_id")
        ->references("id")
        ->on("categories")
        ->onDelete("CASCADE");

      $table->unsignedBigInteger("farm_id")->index();
      $table
        ->foreign("farm_id")
        ->references("id")
        ->on("farms")
        ->onUpdate("CASCADE")
        ->onDelete("CASCADE");

      $table->unsignedBigInteger("building_id")->index();
      $table
        ->foreign("building_id")
        ->references("id")
        ->on("buildings")
        ->onUpdate("CASCADE")
        ->onDelete("CASCADE");

      $table
        ->unsignedBigInteger("leadman_id")
        ->index()
        ->nullable();
      $table
        ->foreign("leadman_id")
        ->references("id")
        ->on("leadmen")
        ->onUpdate("CASCADE")
        ->onDelete("CASCADE");
      $table->string("leadman_name");

      $table
        ->unsignedBigInteger("buyer_id")
        ->index()
        ->nullable();
      $table
        ->foreign("buyer_id")
        ->references("id")
        ->on("buyers")
        ->onUpdate("CASCADE")
        ->onDelete("CASCADE");
      $table->string("buyer_name");

      $table
        ->unsignedBigInteger("plate_id")
        ->index()
        ->nullable();
      $table
        ->foreign("plate_id")
        ->references("id")
        ->on("plates")
        ->onUpdate("CASCADE")
        ->onDelete("CASCADE");
      $table->string("plate_name");

      $table->string("type");
      $table->integer("series_no");

      $table->timestamp("harvested_at");

      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists("informations");
  }
};
