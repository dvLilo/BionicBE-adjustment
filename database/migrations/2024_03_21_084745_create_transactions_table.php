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
    Schema::create("transactions", function (Blueprint $table) {
      $table->id();

      $table->unsignedBigInteger("information_id")->index();
      $table
        ->foreign("information_id")
        ->references("id")
        ->on("information")
        ->onDelete("CASCADE");

      $table->integer("batch_no");
      $table->double("heads", 12);
      $table->double("weight", 12, 2);

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
    Schema::dropIfExists("transactions");
  }
};
