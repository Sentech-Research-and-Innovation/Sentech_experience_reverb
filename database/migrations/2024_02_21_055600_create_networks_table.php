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
        Schema::create('networks', function (Blueprint $table) {
            $table->id();
            $table->string('SiteName');
            $table->string('SiteCode');
            $table->string('Classification');
            $table->string('OC');
            $table->string('Region');
            $table->string('Province');
            $table->string('DeviceName');
            $table->string('DeviceIP');
            $table->string('EventInDateTime');
            $table->string('EventOutDateTime');
            $table->text('AlarmDescription');

            $table->string('RawValue');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('networks');
    }
};
