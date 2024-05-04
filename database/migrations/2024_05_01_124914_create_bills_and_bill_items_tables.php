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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_number');
            $table->text('description')->nullable();
            $table->decimal('grand_total', 10, 2);
            $table->decimal('grand_net_commission', 10, 2);
            $table->decimal('net_tax', 10, 2);
            $table->timestamps();
        });

        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_id');
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->string('item_name');
            $table->decimal('base_price', 10, 2);
            $table->decimal('commission', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
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
        Schema::dropIfExists('bill_items');
        Schema::dropIfExists('bills');
    }
};
