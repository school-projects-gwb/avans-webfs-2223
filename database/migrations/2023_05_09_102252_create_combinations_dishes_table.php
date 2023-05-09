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
        Schema::create('combinations_dishes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('combination_id');
            $table->unsignedBigInteger('dish_id');
            $table->boolean('is_optional')->default(0)->change();
            $table->timestamps();

            $table->foreign('combination_id')->references('id')->on('combinations');
            $table->foreign('dish_id')->references('id')->on('dishes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combinations_dishes');
    }
};
