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
        Schema::connection('competency_management')->table('gap_analyses', function (Blueprint $table) {
            // Drop the foreign key constraint that references the local employees table
            $table->dropForeign(['employee_id']);
            
            // Keep the column but remove the constraint since we're using external API
            // The employee_id will now store the external API employee ID
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('competency_management')->table('gap_analyses', function (Blueprint $table) {
            // Re-add the foreign key constraint if we need to rollback
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }
};