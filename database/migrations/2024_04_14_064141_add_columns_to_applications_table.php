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
        Schema::table('applications', function (Blueprint $table) {
            $table->string('price_type')->nullable();
            $table->decimal('govt_price', 10, 2)->nullable();
            $table->decimal('commission', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('price_type');
            $table->dropColumn('govt_price');
            $table->dropColumn('commission');
            $table->dropColumn('tax_percentage');
        });
    }
};
