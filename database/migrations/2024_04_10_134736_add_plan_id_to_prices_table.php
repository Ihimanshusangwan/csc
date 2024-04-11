<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('prices', function (Blueprint $table) {
            // Add plan_id column
            $table->unsignedBigInteger('plan_id')->nullable();

            // Add foreign key constraint
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('prices', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['plan_id']);

            // Drop plan_id column
            $table->dropColumn('plan_id');
        });
    }
};
