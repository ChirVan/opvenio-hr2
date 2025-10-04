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
            $table->date('assessment_date')->nullable()->after('notes');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'on_hold'])->default('pending')->after('assessment_date');
            $table->timestamps(); // Add created_at and updated_at if they don't exist
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('competency_management')->table('gap_analyses', function (Blueprint $table) {
            $table->dropColumn(['assessment_date', 'status']);
            $table->dropTimestamps(); // Only if we added them
        });
    }
};
