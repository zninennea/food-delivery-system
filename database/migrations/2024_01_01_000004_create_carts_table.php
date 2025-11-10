<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->text('special_instructions')->nullable();
            $table->timestamps();

            // Ensure unique combination of customer and menu item
            $table->unique(['customer_id', 'menu_item_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
}