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
        $connection = Schema::connection('competency_management');
        
        // Check if columns exist before adding them
        if (!$connection->hasColumn('gap_analyses', 'assessment_date')) {
            $connection->table('gap_analyses', function (Blueprint $table) {
                $table->date('assessment_date')->nullable()->after('notes');
            });
        }
        
        if (!$connection->hasColumn('gap_analyses', 'status')) {
            $connection->table('gap_analyses', function (Blueprint $table) {
                $table->enum('status', ['pending', 'in_progress', 'completed', 'on_hold'])->default('pending')->after('notes');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('competency_management')->table('gap_analyses', function (Blueprint $table) {
            if (Schema::connection('competency_management')->hasColumn('gap_analyses', 'assessment_date')) {
                $table->dropColumn('assessment_date');
            }
            if (Schema::connection('competency_management')->hasColumn('gap_analyses', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};