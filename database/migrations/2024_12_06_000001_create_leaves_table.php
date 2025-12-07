<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     *
     * @var string
     */
    protected $connection = 'ess';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('ess')->create('leaves', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('employee_name');
            $table->string('employee_email');
            $table->string('leave_type'); // Vacation, Sick, Emergency, Personal
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('remarks')->nullable();
            $table->string('approved_by')->nullable();
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('employee_id');
            $table->index('employee_email');
            $table->index('status');
            $table->index('leave_type');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('ess')->dropIfExists('leaves');
    }
};
