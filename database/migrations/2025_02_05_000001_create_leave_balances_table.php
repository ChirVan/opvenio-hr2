<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        // Create leave_types table for configurable leave types
        Schema::connection('ess')->create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // VL, SL, EL, etc.
            $table->string('name'); // Vacation Leave, Sick Leave, etc.
            $table->text('description')->nullable();
            $table->integer('default_credits')->default(0); // Default annual credits
            $table->integer('max_days_per_request')->default(5); // Max days per single request
            $table->integer('max_per_month')->default(2); // Max requests per month
            $table->integer('advance_notice_days')->default(1); // Days in advance required
            $table->boolean('requires_medical_cert')->default(false);
            $table->boolean('is_paid')->default(true);
            $table->boolean('is_active')->default(true);
            $table->string('icon')->default('bx-calendar'); // Boxicons icon
            $table->string('color_class')->default('primary'); // CSS class for styling
            $table->timestamps();
        });

        // Create leave_balances table to track each employee's credits
        Schema::connection('ess')->create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('employee_email');
            $table->unsignedBigInteger('leave_type_id');
            $table->integer('year'); // Fiscal year
            $table->decimal('total_credits', 5, 1)->default(0); // Annual credits
            $table->decimal('used_credits', 5, 1)->default(0); // Credits used
            $table->decimal('pending_credits', 5, 1)->default(0); // Credits in pending requests
            $table->timestamps();
            
            $table->unique(['employee_id', 'leave_type_id', 'year']);
            $table->index('employee_email');
            $table->index('year');
            
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
        });

        // Add leave_type_id and days_requested to leaves table
        Schema::connection('ess')->table('leaves', function (Blueprint $table) {
            $table->unsignedBigInteger('leave_type_id')->nullable()->after('leave_type');
            $table->decimal('days_requested', 4, 1)->default(1)->after('end_date');
            $table->boolean('is_half_day')->default(false)->after('days_requested');
            $table->string('half_day_period')->nullable()->after('is_half_day'); // AM or PM
        });

        // Seed default leave types (Philippine government-style)
        $leaveTypes = [
            [
                'code' => 'VL',
                'name' => 'Vacation Leave',
                'description' => 'Annual vacation leave for rest and recreation',
                'default_credits' => 15,
                'max_days_per_request' => 5,
                'max_per_month' => 2,
                'advance_notice_days' => 5,
                'requires_medical_cert' => false,
                'is_paid' => true,
                'icon' => 'bx-sun',
                'color_class' => 'vacation',
            ],
            [
                'code' => 'SL',
                'name' => 'Sick Leave',
                'description' => 'Leave for illness or medical appointments',
                'default_credits' => 15,
                'max_days_per_request' => 5,
                'max_per_month' => 3,
                'advance_notice_days' => 0,
                'requires_medical_cert' => true,
                'is_paid' => true,
                'icon' => 'bx-plus-medical',
                'color_class' => 'sick',
            ],
            [
                'code' => 'EL',
                'name' => 'Emergency Leave',
                'description' => 'Immediate leave for urgent personal matters',
                'default_credits' => 3,
                'max_days_per_request' => 3,
                'max_per_month' => 1,
                'advance_notice_days' => 0,
                'requires_medical_cert' => false,
                'is_paid' => true,
                'icon' => 'bx-error',
                'color_class' => 'emergency',
            ],
            [
                'code' => 'BL',
                'name' => 'Bereavement Leave',
                'description' => 'Leave due to death of immediate family member',
                'default_credits' => 5,
                'max_days_per_request' => 5,
                'max_per_month' => 1,
                'advance_notice_days' => 0,
                'requires_medical_cert' => false,
                'is_paid' => true,
                'icon' => 'bx-heart',
                'color_class' => 'bereavement',
            ],
            [
                'code' => 'ML',
                'name' => 'Maternity Leave',
                'description' => 'Leave for childbirth and recovery (105 days as per RA 11210)',
                'default_credits' => 105,
                'max_days_per_request' => 105,
                'max_per_month' => 1,
                'advance_notice_days' => 30,
                'requires_medical_cert' => true,
                'is_paid' => true,
                'icon' => 'bx-female',
                'color_class' => 'maternity',
            ],
            [
                'code' => 'PL',
                'name' => 'Paternity Leave',
                'description' => 'Leave for fathers upon childbirth (7 days as per RA 8187)',
                'default_credits' => 7,
                'max_days_per_request' => 7,
                'max_per_month' => 1,
                'advance_notice_days' => 7,
                'requires_medical_cert' => true,
                'is_paid' => true,
                'icon' => 'bx-male',
                'color_class' => 'paternity',
            ],
            [
                'code' => 'SPL',
                'name' => 'Solo Parent Leave',
                'description' => 'Additional leave for solo parents (7 days as per RA 8972)',
                'default_credits' => 7,
                'max_days_per_request' => 7,
                'max_per_month' => 2,
                'advance_notice_days' => 3,
                'requires_medical_cert' => false,
                'is_paid' => true,
                'icon' => 'bx-user-check',
                'color_class' => 'solo-parent',
            ],
            [
                'code' => 'SIL',
                'name' => 'Service Incentive Leave',
                'description' => 'Annual incentive leave after 1 year of service',
                'default_credits' => 5,
                'max_days_per_request' => 5,
                'max_per_month' => 1,
                'advance_notice_days' => 3,
                'requires_medical_cert' => false,
                'is_paid' => true,
                'icon' => 'bx-gift',
                'color_class' => 'incentive',
            ],
            [
                'code' => 'LWOP',
                'name' => 'Leave Without Pay',
                'description' => 'Unpaid leave when credits are exhausted',
                'default_credits' => 30,
                'max_days_per_request' => 30,
                'max_per_month' => 1,
                'advance_notice_days' => 5,
                'requires_medical_cert' => false,
                'is_paid' => false,
                'icon' => 'bx-wallet',
                'color_class' => 'lwop',
            ],
        ];

        $now = now();
        foreach ($leaveTypes as $type) {
            DB::connection('ess')->table('leave_types')->insert(array_merge($type, [
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('ess')->table('leaves', function (Blueprint $table) {
            $table->dropColumn(['leave_type_id', 'days_requested', 'is_half_day', 'half_day_period']);
        });
        
        Schema::connection('ess')->dropIfExists('leave_balances');
        Schema::connection('ess')->dropIfExists('leave_types');
    }
};
