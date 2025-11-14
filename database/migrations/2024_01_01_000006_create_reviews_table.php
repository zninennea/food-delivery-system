<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('order_id')->constrained();
            $table->foreignId('rider_id')->nullable()->constrained('users');
            $table->integer('restaurant_rating'); // Overall restaurant rating
            $table->integer('rider_rating')->nullable(); // Rider rating
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // Review items for individual menu items
        Schema::create('review_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained();
            $table->foreignId('menu_item_id')->constrained();
            $table->integer('rating'); // 1-5 stars
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('review_items');
        Schema::dropIfExists('reviews');
    }
};