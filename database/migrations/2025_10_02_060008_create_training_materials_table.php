<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     */
    protected $connection = 'training_management';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('training_management')->create('training_materials', function (Blueprint $table) {
            $table->id();
            $table->string('lesson_title');
            $table->unsignedBigInteger('training_catalog_id');
            $table->unsignedBigInteger('competency_id');
            $table->integer('proficiency_level');
            $table->longText('lesson_content');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes(); // Add soft deletes column

            // Add indexes for performance
            $table->index(['training_catalog_id', 'status']);
            $table->index(['competency_id', 'proficiency_level']);
            $table->index('is_active');
            $table->index('deleted_at'); // Index for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('training_management')->dropIfExists('training_materials');
    }
};
