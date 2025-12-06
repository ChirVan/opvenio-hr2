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
        // Set connection to competency_management
        $connection = 'competency_management';
        
        // Assigned Competencies Table
        Schema::connection($connection)->create('assigned_competencies', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->index();
            $table->string('employee_name');
            $table->string('job_title')->nullable();
            $table->unsignedBigInteger('competency_id')->index();
            $table->unsignedBigInteger('framework_id')->nullable()->index();
            $table->enum('assignment_type', ['development', 'gap_closure', 'skill_enhancement', 'mandatory'])->default('development');
            $table->enum('proficiency_level', ['beginner', 'intermediate', 'advanced', 'expert'])->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->date('target_date')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['assigned', 'in_progress', 'completed', 'on_hold', 'cancelled'])->default('assigned');
            $table->integer('progress_percentage')->default(0);
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('competency_id')
                  ->references('id')
                  ->on('competencies')
                  ->onDelete('cascade');
                  
            $table->foreign('framework_id')
                  ->references('id')
                  ->on('competency_frameworks')
                  ->onDelete('set null');
            
            // Indexes for better query performance
            $table->index('status');
            $table->index('assignment_type');
            $table->index('priority');
            $table->index('target_date');
            $table->index(['employee_id', 'competency_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = 'competency_management';
        
        Schema::connection($connection)->dropIfExists('assigned_competencies');
    }
};
