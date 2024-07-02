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
    Schema::create('fieldboys', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('mobile');
      $table->string('aadhar')->unique();
      $table->string('pancard')->unique();
      $table->string('referal_code')->unique();
      $table->text('address');

      // Define location as foreign key referencing location table
      $table->unsignedBigInteger('location_id');
      $table->foreign('location_id')->references('id')->on('locations');

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
    Schema::dropIfExists('fieldboys');
  }
};
