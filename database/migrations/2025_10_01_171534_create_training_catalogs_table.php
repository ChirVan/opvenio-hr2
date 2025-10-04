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
        Schema::connection('training_management')->create('training_catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Framework name
            $table->string('label'); // Short label/subtitle
            $table->text('description'); // Catalog description
            $table->boolean('is_active')->default(true); // Active/Inactive status
            $table->unsignedBigInteger('framework_id')->nullable(); // Foreign key to competency_frameworks
            $table->timestamps();

            // Note: Foreign key constraint removed since it would reference a different database
            // The relationship will be handled at the application level
            
            // Indexes
            $table->index('title');
            $table->index('is_active');
            $table->index('framework_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('training_management')->dropIfExists('training_catalogs');
    }
};
