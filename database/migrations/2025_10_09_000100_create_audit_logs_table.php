<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'mysql';
    public function up() {
        Schema::connection($this->connection)->create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_name');
            $table->string('activity');
            $table->string('details')->nullable();
            $table->timestamp('time_in')->nullable();
            $table->timestamp('time_out')->nullable();
            $table->string('status')->default('Success');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('audit_logs');
    }
};
