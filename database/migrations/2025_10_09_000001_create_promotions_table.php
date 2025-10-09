<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    public function up()
    {
        Schema::connection('succession_planning')->create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('employee_name');
            $table->string('employee_email');
            $table->string('job_title');
            $table->string('potential_job');
            $table->float('assessment_score')->nullable();
            $table->string('category')->nullable();
            $table->text('strengths')->nullable();
            $table->text('recommendations')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('succession_planning')->dropIfExists('promotions');
    }
}
