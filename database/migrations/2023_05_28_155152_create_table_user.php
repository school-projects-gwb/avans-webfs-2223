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
        Schema::create('table_user', function (Blueprint $table) {
            $table->integer('weekday');

            $table->unsignedBigInteger('table_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->primary(['table_id', 'user_id', 'weekday']);

            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');;
            $table->foreign('user_Id')->references('id')->on('users')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_user');
    }
};
