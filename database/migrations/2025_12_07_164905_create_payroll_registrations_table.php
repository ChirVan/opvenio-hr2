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
        Schema::create('payroll_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Local user reference
            $table->string('employee_id')->nullable(); // HR4 employee ID
            $table->string('employee_email');
            $table->string('employee_name');
            
            // Payment Method
            $table->enum('payment_method', ['Bank Transfer', 'GCash', 'Maya'])->default('Bank Transfer');
            
            // Bank/E-Wallet Details
            $table->string('bank_name');
            $table->string('bank_branch')->nullable();
            $table->string('account_name');
            $table->string('account_number');
            $table->string('account_type')->nullable();
            
            // Valid ID Information
            $table->string('id_type');
            $table->string('id_number');
            
            // File Uploads (stored as paths)
            $table->string('proof_of_account_path')->nullable();
            $table->string('valid_id_path')->nullable();
            
            // Status & Approval
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable(); // For rejection reason
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('employee_email');
            $table->index('status');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_registrations');
    }
};
