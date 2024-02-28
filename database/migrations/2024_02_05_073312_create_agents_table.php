<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('type'); 
            $table->string('full_name')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('shop_address')->nullable();
            $table->string('aadhar_card')->nullable();
            $table->string('shop_license')->nullable();
            $table->string('owner_photo')->nullable();
            $table->string('supporting_document')->nullable();
            $table->string('username');
            $table->string('password');
            $table->foreignId('plan_id')->constrained('plans');
            $table->foreignId('location_id')->constrained('locations');
            $table->string('payment_status')->nullable();
            $table->string('payment_mode')->nullable();
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->decimal('unpaid_amount', 10, 2)->nullable(); 
            $table->decimal('balance', 10, 2)->nullable(); 
            $table->date('reg_date')->default(DB::raw('CURRENT_DATE'));
            $table->date('purchase_date')->nullable(); 
            $table->date('expiration_date')->nullable(); 
            $table->boolean('is_hold')->default(0);
            $table->timestamps();
          
        });
    }

    public function down()
    {
        Schema::dropIfExists('registrations');
    }
}
