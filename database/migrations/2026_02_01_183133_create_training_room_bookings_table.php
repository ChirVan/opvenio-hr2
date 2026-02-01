<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     */
    protected $connection = 'training_management';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('training_management')->create('training_room_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->unsignedBigInteger('training_catalog_id');
            $table->string('course_name');
            $table->date('session_date');
            $table->string('location')->default('Training Room');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('facilitator')->nullable();
            $table->text('notes')->nullable();
            $table->json('attendees'); // Store attendee data as JSON
            $table->integer('attendee_count')->default(0);
            $table->enum('status', ['pending', 'approved', 'ongoing', 'completed', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('training_catalog_id')->references('id')->on('training_catalogs')->onDelete('cascade');
            $table->index('session_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('training_management')->dropIfExists('training_room_bookings');
    }
};
