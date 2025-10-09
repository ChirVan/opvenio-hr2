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
            $table->json('evaluation_data')->nullable()->after('evaluation_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('ess')->table('assessment_results', function (Blueprint $table) {
            $table->dropColumn('evaluation_data');
        });
    }
};
