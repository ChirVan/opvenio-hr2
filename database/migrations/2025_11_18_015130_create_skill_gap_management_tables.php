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
        
        // Skill Gap Assignments Table
        Schema::connection($connection)->create('skill_gap_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('employee_name');
            $table->string('job_title')->nullable();
            $table->string('competency_key');
            $table->enum('action_type', ['critical', 'training', 'mentoring'])->default('training');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index('employee_id');
            $table->index('status');
            $table->index('action_type');
        });

        // Development Plans Table
        Schema::connection($connection)->create('development_plans', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('employee_name');
            $table->string('job_title')->nullable();
            $table->string('plan_title');
            $table->json('objectives'); // Array of objectives
            $table->string('timeline'); // e.g., "30-90 days", "6 months"
            $table->json('resources')->nullable(); // Array of resources/training materials
            $table->enum('status', ['active', 'completed', 'on_hold', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('target_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index('employee_id');
            $table->index('status');
        });

        // Assessment Schedules Table
        Schema::connection($connection)->create('assessment_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('employee_name');
            $table->string('job_title')->nullable();
            $table->enum('assessment_type', ['individual', 'comprehensive', 'practical', 'feedback']);
            $table->date('scheduled_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'rescheduled'])->default('scheduled');
            $table->unsignedBigInteger('scheduled_by')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index('employee_id');
            $table->index('scheduled_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = 'competency_management';
        
        Schema::connection($connection)->dropIfExists('assessment_schedules');
        Schema::connection($connection)->dropIfExists('development_plans');
        Schema::connection($connection)->dropIfExists('skill_gap_assignments');
    }
};
