<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPromotedAtToPromotionsTable extends Migration
{
    public function up()
    {
        Schema::connection('succession_planning')->table('promotions', function (Blueprint $table) {
            $table->timestamp('promoted_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::connection('succession_planning')->table('promotions', function (Blueprint $table) {
            $table->dropColumn('promoted_at');
        });
    }
}
