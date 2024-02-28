<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services'); 
            $table->foreignId('location_id')->constrained('locations');
            $table->decimal('default_govt_price', 10, 2)->nullable()->default(NULL);
            $table->decimal('default_commission_price', 10, 2)->nullable()->default(NULL);
            $table->decimal('default_tax_percentage', 5, 2)->nullable()->default(NULL);
            $table->decimal('tatkal_govt_price', 10, 2)->nullable()->default(NULL);
            $table->decimal('tatkal_commission_price', 10, 2)->nullable()->default(NULL);
            $table->decimal('tatkal_tax_percentage', 5, 2)->nullable()->default(NULL);
            $table->decimal('subscribed_default_govt_price', 10, 2)->nullable()->default(NULL);
            $table->decimal('subscribed_default_commission_price', 10, 2)->nullable()->default(NULL);
            $table->decimal('subscribed_default_tax_percentage', 5, 2)->nullable()->default(NULL);
            $table->decimal('subscribed_tatkal_govt_price', 10, 2)->nullable()->default(NULL);
            $table->decimal('subscribed_tatkal_commission_price', 10, 2)->nullable()->default(NULL);
            $table->decimal('subscribed_tatkal_tax_percentage', 5, 2)->nullable()->default(NULL);
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
