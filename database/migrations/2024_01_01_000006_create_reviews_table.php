<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Make restaurant_rating nullable and add default value
            $table->integer('restaurant_rating')->default(5)->nullable()->change();
            // Also make rider_rating nullable
            $table->integer('rider_rating')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->integer('restaurant_rating')->nullable(false)->change();
            $table->integer('rider_rating')->nullable(false)->change();
        });
    }
};