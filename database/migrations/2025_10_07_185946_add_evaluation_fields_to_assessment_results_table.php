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
        Schema::connection('ess')->table('assessment_results', function (Blueprint $table) {
            $table->enum('evaluation_status', ['pending', 'passed', 'failed'])->default('pending')->after('status');
            $table->text('evaluation_notes')->nullable()->after('evaluation_status');
            $table->unsignedBigInteger('evaluated_by')->nullable()->after('evaluation_notes');
            $table->timestamp('evaluated_at')->nullable()->after('evaluated_by');
            
            // Add index for better performance
            $table->index(['evaluation_status', 'evaluated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('ess')->table('assessment_results', function (Blueprint $table) {
            $table->dropIndex(['evaluation_status', 'evaluated_at']);
            $table->dropColumn(['evaluation_status', 'evaluation_notes', 'evaluated_by', 'evaluated_at']);
        });
    }
};
