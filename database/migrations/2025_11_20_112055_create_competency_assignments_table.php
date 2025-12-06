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
        Schema::connection('competency_management')->create('competency_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('employee_name');
            $table->string('job_title')->nullable();
            $table->string('competency_id'); // CMP-001, CMP-002, etc.
            $table->string('competency_name');
            $table->enum('action_type', ['critical', 'training', 'mentoring']);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->unsignedBigInteger('assigned_by');
            $table->timestamp('assigned_at');
            $table->timestamps();
            
            $table->index(['employee_id', 'competency_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('competency_management')->dropIfExists('competency_assignments');
    }
};
