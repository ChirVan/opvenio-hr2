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
        Schema::connection('ess')->table('user_answers', function (Blueprint $table) {
            $table->decimal('manual_score', 5, 2)->nullable()->after('points_possible');
            $table->text('evaluator_comments')->nullable()->after('manual_score');
            $table->boolean('manually_graded')->default(false)->after('evaluator_comments');
            $table->unsignedBigInteger('graded_by')->nullable()->after('manually_graded');
            $table->timestamp('graded_at')->nullable()->after('graded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('ess')->table('user_answers', function (Blueprint $table) {
            $table->dropColumn([
                'manual_score',
                'evaluator_comments',
                'manually_graded',
                'graded_by',
                'graded_at'
            ]);
        });
    }
};
