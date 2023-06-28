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
        Schema::create('adminstration_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('application_id');
            $table->string('company_name');
            $table->date('date_of_order');
            $table->string('instalment_value');
            $table->string('paid_by');
            $table->string('attorney_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adminstration_orders');
    }
};
