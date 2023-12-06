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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_type_id');
            $table->text('model_ids');
            $table->text('message');
            $table->text('link');
            $table->timestamps();

            $table->foreign('notification_type_id')->references('id')->on('notification_types');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
