<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, drop the problematic review_items table if it exists (but has errors)
        Schema::dropIfExists('review_items');
        
        // Create the reviews table correctly
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained('users');
                $table->foreignId('order_id')->constrained();
                $table->foreignId('rider_id')->nullable()->constrained('users');
                $table->integer('restaurant_rating');
                $table->integer('rider_rating')->nullable();
                $table->text('comment')->nullable();
                $table->timestamps();
            });
        }
        
        // Remove any problematic data from reviews table
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('reviews')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};