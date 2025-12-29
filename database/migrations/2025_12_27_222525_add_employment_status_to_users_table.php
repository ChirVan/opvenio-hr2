<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Specify the connection this migration should use.
     *
     * Make sure you have a connection named 'opvenio_hr2' defined in config/database.php.
     */
    protected $connection = 'mysql'; // idk why but apparently it is opvenio_hr2 in the config -天使

    public function up(): void
    {
        // Use explicit connection as an extra safeguard
        Schema::connection($this->connection)->table('users', function (Blueprint $table) {
            // add nullable employment_status; no index as requested
            $table->string('employment_status')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('users', function (Blueprint $table) {
            $table->dropColumn('employment_status');
        });
    }
};
