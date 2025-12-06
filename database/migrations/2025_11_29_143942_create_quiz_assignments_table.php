<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     *
     * @var string
     */
    protected $connection = 'learning_management';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('learning_management')->create('quiz_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->string('employee_id'); // Can be string since it comes from external API
            $table->unsignedBigInteger('assigned_by')->nullable(); // User who assigned
            $table->timestamp('assigned_at')->useCurrent();
            $table->date('due_date')->nullable();
            $table->enum('status', ['assigned', 'in_progress', 'completed', 'expired', 'cancelled'])->default('assigned');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('score')->nullable();
            $table->integer('total_points')->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->integer('time_spent_seconds')->nullable(); // Time taken to complete
            $table->integer('attempts')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
            
            // Index for faster lookups
            $table->index(['employee_id', 'status']);
            $table->index(['quiz_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('learning_management')->dropIfExists('quiz_assignments');
    }
};
