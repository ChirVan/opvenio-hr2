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
        Schema::connection('training_management')->table('training_materials', function (Blueprint $table) {
            $table->softDeletes(); // Add deleted_at column
            $table->index('deleted_at'); // Add index for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('training_management')->table('training_materials', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Remove deleted_at column
        });
    }
};
