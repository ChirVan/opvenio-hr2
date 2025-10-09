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
            $table->unsignedBigInteger('evaluated_by')->nullable()->after('status');
            $table->timestamp('evaluated_at')->nullable()->after('evaluated_by');
            $table->text('evaluation_notes')->nullable()->after('evaluated_at');
            
            // Add foreign key constraint for evaluated_by
            $table->foreign('evaluated_by')->references('id')->on('opvenio_hr2.users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('ess')->table('assessment_results', function (Blueprint $table) {
            $table->dropForeign(['evaluated_by']);
            $table->dropColumn(['evaluated_by', 'evaluated_at', 'evaluation_notes']);
        });
    }
};
