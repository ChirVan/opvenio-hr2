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
        // Modify the enum to include 'retried' status
        DB::connection('ess')->statement("ALTER TABLE assessment_results MODIFY COLUMN status ENUM('in_progress', 'completed', 'failed', 'passed', 'retried') NOT NULL DEFAULT 'in_progress'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::connection('ess')->statement("ALTER TABLE assessment_results MODIFY COLUMN status ENUM('in_progress', 'completed', 'failed', 'passed') NOT NULL DEFAULT 'in_progress'");
    }
};
