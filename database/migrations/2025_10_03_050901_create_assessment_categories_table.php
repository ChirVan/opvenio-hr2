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
        // Set the database connection to learning_management
        DB::statement('USE learning_management');
        
        Schema::connection('learning_management')->create('assessment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name');
            $table->string('category_slug')->unique();
            $table->string('category_icon');
            $table->text('description');
            $table->enum('color_theme', ['blue', 'green', 'red', 'purple', 'orange', 'teal', 'indigo', 'pink'])->default('blue');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by'); // User who created the category
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active']);
            $table->index(['category_slug']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('USE learning_management');
        Schema::connection('learning_management')->dropIfExists('assessment_categories');
    }
};
