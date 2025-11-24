<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', ['cash_on_delivery', 'gcash'])->default('cash_on_delivery');
            $table->decimal('cash_provided', 8, 2)->nullable();
            $table->string('gcash_reference_number')->nullable();
            $table->string('gcash_receipt_path')->nullable();
            $table->enum('payment_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->decimal('delivery_fee', 8, 2)->default(50.00); // Add delivery fee
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'cash_provided', 
                'gcash_reference_number',
                'gcash_receipt_path',
                'payment_status',
                'delivery_fee'
            ]);
        });
    }
};