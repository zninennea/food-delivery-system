<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, ensure the column has the correct default value and enum values
        Schema::table('orders', function (Blueprint $table) {
            // Since the column already exists, we'll modify it
            $table->enum('gcash_payment_status', ['pending', 'verified', 'rejected'])
                  ->default('pending')
                  ->change();
        });

        // Set default value for existing records
        DB::table('orders')
            ->whereNull('gcash_payment_status')
            ->update(['gcash_payment_status' => 'pending']);
    }

    public function down()
    {
        // We can't easily rollback an enum change, so leave it as is
    }
};