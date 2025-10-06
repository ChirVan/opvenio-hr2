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
        Schema::create('assessment_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_category_id');
            $table->unsignedBigInteger('quiz_id');
            $table->string('employee_id'); // External employee ID from API
            $table->string('employee_name')->nullable(); // Store employee name for reference
            $table->string('employee_email')->nullable(); // Store employee email for reference
            $table->integer('duration'); // Duration in minutes
            $table->datetime('start_date'); // Available from
            $table->datetime('due_date'); // Due date
            $table->string('max_attempts')->default('3'); // 1, 2, 3, or 'unlimited'
            $table->enum('status', ['pending', 'in_progress', 'completed', 'overdue', 'cancelled'])->default('pending');
            $table->integer('attempts_used')->default(0);
            $table->decimal('score', 5, 2)->nullable(); // Final score
            $table->datetime('started_at')->nullable(); // When employee started the assessment
            $table->datetime('completed_at')->nullable(); // When employee completed the assessment
            $table->text('notes')->nullable(); // Additional notes
            $table->json('assignment_metadata')->nullable(); // Store additional data like employee details from API
            $table->unsignedBigInteger('assigned_by'); // User who assigned the assessment
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('assessment_category_id')->references('id')->on('assessment_categories')->onDelete('cascade');
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');

            // Indexes for better performance
            $table->index(['employee_id', 'status']);
            $table->index(['status', 'due_date']);
            $table->index(['quiz_id', 'status']);
            $table->index('start_date');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_assignments');
    }
};