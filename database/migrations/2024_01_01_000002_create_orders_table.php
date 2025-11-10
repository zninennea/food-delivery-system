<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('rider_id')->nullable()->constrained('users');
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'preparing', 'ready', 'on_the_way', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('total_amount', 8, 2);
            $table->text('delivery_address');
            $table->string('customer_phone');
            $table->text('special_instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}