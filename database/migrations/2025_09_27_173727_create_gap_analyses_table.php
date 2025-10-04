<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGapAnalysesTable extends Migration
{
    public function up()
    {
        Schema::connection('competency_management')->create('gap_analyses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('competency_id');
            $table->string('framework');
            $table->string('proficiency_level');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('competency_id')->references('id')->on('competencies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::connection('competency_management')->dropIfExists('gap_analyses');
    }
}