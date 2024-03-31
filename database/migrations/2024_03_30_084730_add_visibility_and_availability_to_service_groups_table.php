<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_groups', function (Blueprint $table) {
            $table->tinyInteger('visibility')->default(1)->comment('0: Hidden, 1: Visible');
            $table->tinyInteger('availability')->default(0)->comment('0: Not Available, 1: Available');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_groups', function (Blueprint $table) {
            $table->dropColumn('visibility');
            $table->dropColumn('availability');
        });
    }
};
