<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_table_registration', function (Blueprint $table) {
            $table->unsignedBigInteger('table_registration_id');
            $table->unsignedBigInteger('order_id');
            $table->timestamps();

            $table->primary(['table_registration_id', 'order_id']);

            $table->foreign('table_registration_id')->references('id')->on('table_registrations');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_table_registration');
    }
};
