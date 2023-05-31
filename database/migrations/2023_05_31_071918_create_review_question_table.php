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
        Schema::create('review_question', function (Blueprint $table) {
            $table->id();
            $table->integer('answer');
            $table->timestamps();

            $table->foreignId('review_id')->unsigned()->constrained('reviews');
            $table->foreignId('question_id')->unsigned()->constrained('questions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_question');
    }
};
