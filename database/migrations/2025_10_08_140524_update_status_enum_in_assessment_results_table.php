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
        // Update the status enum to include 'passed' and 'rejected'
        DB::statement("ALTER TABLE `assessment_results` MODIFY COLUMN `status` ENUM('in_progress', 'completed', 'failed', 'passed', 'rejected') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE `assessment_results` MODIFY COLUMN `status` ENUM('in_progress', 'completed', 'failed') NOT NULL");
    }
};
