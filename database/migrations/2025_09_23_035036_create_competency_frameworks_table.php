<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('competency_frameworks', function (Blueprint $table) {
            $table->id();
            $table->string('framework_name');
            $table->text('description');
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'draft', 'archived'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('status');
            $table->index('effective_date');
            $table->index('framework_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competency_frameworks');
    }
};
