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
        Schema::create('order_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->timestamps();

            $table->foreignId('order_id')->unsigned()->constrained('orders');
            $table->foreignId('dish_id')->unsigned()->nullable()->constrained('dishes');
            $table->foreignId('combination_id')->unsigned()->nullable()->constrained('combinations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_lines');
    }
};
