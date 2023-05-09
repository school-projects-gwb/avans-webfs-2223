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
        Schema::create('combinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId("category_id")
                ->references('id')
                ->on('categories');
            $table->integer('menu_number');
            $table->decimal('price', 4, 2);
            $table->string('name');
            $table->string('comment')->nullable();
            $table->boolean('is_discount')->default(0)->change();
            $table->integer('dish_limit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combinations');
    }
};
