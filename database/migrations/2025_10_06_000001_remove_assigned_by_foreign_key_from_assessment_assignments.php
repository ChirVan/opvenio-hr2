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
        Schema::connection('learning_management')->table('assessment_assignments', function (Blueprint $table) {
            // Drop the foreign key constraint for assigned_by since users table is in different database
            $table->dropForeign(['assigned_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('learning_management')->table('assessment_assignments', function (Blueprint $table) {
            // Re-add the foreign key constraint (this will fail if users are not in same database)
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');
        });
    }
};