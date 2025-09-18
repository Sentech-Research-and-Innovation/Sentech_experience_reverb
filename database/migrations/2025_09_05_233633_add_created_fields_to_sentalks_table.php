
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
        Schema::table('sentalks', function (Blueprint $table) {
            //
	$table->string('created_date')->nullable();
        $table->string('created_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sentalks', function (Blueprint $table) {
            //
	 $table->dropColumn('created_date');
        $table->dropColumn('created_time');
        });
    }
};
