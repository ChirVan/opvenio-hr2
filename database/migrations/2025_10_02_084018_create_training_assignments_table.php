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
        
        Schema::connection('training_management')->create('training_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('assignment_title');
            $table->unsignedBigInteger('training_catalog_id');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('assignment_type', ['mandatory', 'optional', 'development'])->default('mandatory');
            $table->date('start_date');
            $table->date('due_date');
            $table->text('instructions')->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->unsignedBigInteger('created_by'); // User who created the assignment
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('training_catalog_id')->references('id')->on('training_catalogs')->onDelete('cascade');
            
            // Indexes
            $table->index(['status', 'start_date']);
            $table->index(['due_date']);
            $table->index(['training_catalog_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('USE training_management');
        Schema::connection('training_management')->dropIfExists('training_assignments');
    }
};
