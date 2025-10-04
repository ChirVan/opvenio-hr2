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
        Schema::connection('learning_management')->create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->text('question');
            $table->string('correct_answer');
            $table->integer('points')->default(1);
            $table->integer('question_order')->default(1);
            $table->enum('question_type', ['identification'])->default('identification');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
            $table->index(['quiz_id', 'question_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('learning_management')->dropIfExists('quiz_questions');
    }
};
