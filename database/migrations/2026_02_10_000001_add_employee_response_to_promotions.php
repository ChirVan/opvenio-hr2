<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('succession_planning')->table('promotions', function (Blueprint $table) {
            if (!Schema::connection('succession_planning')->hasColumn('promotions', 'employee_response')) {
                $table->string('employee_response')->nullable()->after('status'); // accepted, declined
            }
            if (!Schema::connection('succession_planning')->hasColumn('promotions', 'employee_response_note')) {
                $table->text('employee_response_note')->nullable()->after('employee_response');
            }
            if (!Schema::connection('succession_planning')->hasColumn('promotions', 'employee_responded_at')) {
                $table->timestamp('employee_responded_at')->nullable()->after('employee_response_note');
            }
        });
    }

    public function down(): void
    {
        Schema::connection('succession_planning')->table('promotions', function (Blueprint $table) {
            $table->dropColumn(['employee_response', 'employee_response_note', 'employee_responded_at']);
        });
    }
};
