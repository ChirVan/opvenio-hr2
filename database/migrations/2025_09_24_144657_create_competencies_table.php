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
        Schema::create('competencies', function (Blueprint $table) {
            $table->id();
            $table->string('competency_name');
            $table->text('description');
            $table->unsignedBigInteger('framework_id');
            $table->integer('proficiency_levels')->default(5);
            $table->enum('status', ['active', 'inactive', 'draft', 'archived'])->default('draft');
            $table->text('behavioral_indicators')->nullable();
            $table->text('assessment_criteria')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('framework_id')->references('id')->on('competency_frameworks')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index('status');
            $table->index('framework_id');
            $table->index('competency_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competencies');
    }
};
