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
            $table->string('comment')->nullable();
            $table->timestamps();

            $table->foreignId('order_id')->unsigned()->constrained('orders');
            $table->foreignId('dish_id')->unsigned()->constrained('dishes');
            $table->foreignId('option_id')->unsigned()->nullable()->constrained('options');
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
