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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('mobile');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('service_group_id');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('service_group_id')->references('id')->on('service_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff');
    }
}
;