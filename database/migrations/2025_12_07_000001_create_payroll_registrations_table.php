<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     */
    protected $connection = 'ess';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('ess')->create('payroll_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('employee_id')->nullable();
            $table->string('employee_name');
            $table->string('email');
            
            // Payment Method
            $table->enum('payment_method', ['Bank Transfer', 'GCash', 'Maya'])->default('Bank Transfer');
            
            // Bank/E-Wallet Details
            $table->string('bank_name');
            $table->string('bank_branch')->nullable();
            $table->string('account_name');
            $table->string('account_number');
            $table->string('account_type')->nullable();
            
            // Valid ID Information
            $table->string('id_type')->nullable();
            $table->string('id_number')->nullable();
            
            // Document Attachments (file paths)
            $table->string('proof_of_account_path')->nullable();
            $table->string('valid_id_path')->nullable();
            
            // Status Management
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('employee_id');
            $table->index('status');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('ess')->dropIfExists('payroll_registrations');
    }
};
