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
        
        Schema::connection('training_management')->create('training_assignment_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_assignment_id');
            $table->unsignedBigInteger('training_material_id');
            $table->boolean('is_required')->default(true);
            $table->integer('order_sequence')->default(1);
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('training_assignment_id')->references('id')->on('training_assignments')->onDelete('cascade');
            $table->foreign('training_material_id')->references('id')->on('training_materials')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate material assignments
            $table->unique(['training_assignment_id', 'training_material_id'], 'unique_assignment_material');
            
            // Indexes
            $table->index(['training_material_id']);
            $table->index(['order_sequence']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('USE training_management');
        Schema::connection('training_management')->dropIfExists('training_assignment_materials');
    }
};
