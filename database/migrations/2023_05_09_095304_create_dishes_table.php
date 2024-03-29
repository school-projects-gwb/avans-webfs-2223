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
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->foreignId("category_id")
                ->references('id')
                ->on('categories');
            $table->integer('menu_number')->nullable();
            $table->char('menu_addition', 2)->nullable();
            $table->string('name', 75);
            $table->decimal('price', 4, 2);
            $table->text('description')->nullable();
            $table->boolean('is_discount')->nullable();
            $table->boolean('option_required')->nullable();
            $table->integer('option_amount')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};
