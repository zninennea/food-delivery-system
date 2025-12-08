<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Change google_token to TEXT type to store longer tokens
            $table->text('google_token')->nullable()->change();
            $table->text('google_refresh_token')->nullable()->change();
            $table->text('avatar')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_token', 255)->nullable()->change();
            $table->string('google_refresh_token', 255)->nullable()->change();
            $table->string('avatar', 255)->nullable()->change();
        });
    }
};