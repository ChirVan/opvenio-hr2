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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('result_id'); // Links to assessment_results
            $table->unsignedBigInteger('question_id'); // Links to quiz_questions in learning_management
            $table->text('user_answer'); // User's submitted answer
            $table->text('correct_answer'); // Correct answer for reference
            $table->boolean('is_correct')->default(false);
            $table->integer('points_earned')->default(0);
            $table->integer('points_possible')->default(1);
            $table->timestamps();
            
            $table->index(['result_id', 'question_id']);
            $table->foreign('result_id')->references('id')->on('assessment_results')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
