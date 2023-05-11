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
        Schema::create('opening_times', function (Blueprint $table) {
            $table->integer('weekday_number');
            $table->time('start_time');
            $table->time('end_time');

            $table->primary(['weekday_number']);
            $table->foreignId('restaurant_id')->unsigned()->constrained('restaurants');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opening_times');
    }
};
