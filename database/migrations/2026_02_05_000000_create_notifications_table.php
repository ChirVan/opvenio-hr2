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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type'); // 'hr4_sync', 'training_room_approved', 'course_request', etc.
            $table->string('title');
            $table->text('message');
            $table->string('icon')->default('bx-bell'); // Boxicon class
            $table->string('icon_color')->default('text-blue-500'); // Tailwind color class
            $table->string('link')->nullable(); // Optional link to navigate to
            $table->json('data')->nullable(); // Additional data as JSON
            $table->boolean('is_read')->default(false);
            $table->boolean('is_global')->default(false); // If true, shown to all users
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['is_global', 'is_read']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
