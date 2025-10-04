<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set the database connection to training_management
        DB::statement('USE training_management');
        
        Schema::connection('training_management')->create('training_assignment_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_assignment_id');
            $table->unsignedBigInteger('employee_id'); // From competency_management.employees
            $table->enum('status', ['assigned', 'in_progress', 'completed', 'failed', 'cancelled'])->default('assigned');
            $table->datetime('assigned_at')->useCurrent();
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('training_assignment_id')->references('id')->on('training_assignments')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate assignments
            $table->unique(['training_assignment_id', 'employee_id'], 'unique_assignment_employee');
            
            // Indexes
            $table->index(['employee_id']);
            $table->index(['status']);
            $table->index(['assigned_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('USE training_management');
        Schema::connection('training_management')->dropIfExists('training_assignment_employees');
    }
};
