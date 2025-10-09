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
        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignment_id'); // Links to learning_management.assessment_assignments
            $table->string('employee_id'); // Employee ID from API
            $table->unsignedBigInteger('quiz_id'); // Links to learning_management.quizzes
            $table->integer('score')->default(0); // Score percentage
            $table->integer('total_questions')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->integer('attempt_number')->default(1);
            $table->enum('status', ['completed', 'failed', 'in_progress'])->default('completed');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['employee_id', 'quiz_id']);
            $table->index('assignment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_results');
    }
};
