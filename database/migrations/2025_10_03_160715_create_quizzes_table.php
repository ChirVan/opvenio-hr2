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
        Schema::connection('learning_management')->create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('quiz_title');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('competency_id');
            $table->text('description')->nullable();
            $table->integer('time_limit')->default(30); // in minutes
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('total_questions')->default(0);
            $table->integer('total_points')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('category_id')->references('id')->on('assessment_categories')->onDelete('cascade');
            $table->index(['status', 'category_id']);
            $table->index('competency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('learning_management')->dropIfExists('quizzes');
    }
};
