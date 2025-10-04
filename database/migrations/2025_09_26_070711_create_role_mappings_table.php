<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('role_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('role_name');
            $table->unsignedBigInteger('framework_id');
            $table->unsignedBigInteger('competency_id');
            $table->enum('proficiency_level', ['Beginner', 'Intermediate', 'Advanced', 'Expert', 'Master'])->default('Beginner');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('framework_id')->references('id')->on('competency_frameworks')->onDelete('cascade');
            $table->foreign('competency_id')->references('id')->on('competencies')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index('status');
            $table->index('role_name');
            $table->index(['framework_id', 'competency_id']);
            
            // Ensure unique combination of role, framework, and competency
            $table->unique(['role_name', 'framework_id', 'competency_id'], 'unique_role_framework_competency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_mappings');
    }
};
