<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'training_management';

    public function up(): void
    {
        DB::connection('training_management')->statement(
            "ALTER TABLE training_room_bookings MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'ongoing', 'completed', 'cancelled') DEFAULT 'pending'"
        );
    }

    public function down(): void
    {
        DB::connection('training_management')->statement(
            "ALTER TABLE training_room_bookings MODIFY COLUMN status ENUM('pending', 'approved', 'ongoing', 'completed', 'cancelled') DEFAULT 'pending'"
        );
    }
};
