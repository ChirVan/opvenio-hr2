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
        Schema::connection('succession_planning')->table('promotions', function (Blueprint $table) {
            if (!Schema::connection('succession_planning')->hasColumn('promotions', 'promoted_at')) {
                $table->timestamp('promoted_at')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('succession_planning')->table('promotions', function (Blueprint $table) {
            if (Schema::connection('succession_planning')->hasColumn('promotions', 'promoted_at')) {
                $table->dropColumn('promoted_at');
            }
        });
    }
};
