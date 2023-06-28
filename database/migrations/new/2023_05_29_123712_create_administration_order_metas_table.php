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
        Schema::create('adminstration_order_metas', function (Blueprint $table) {
            $table->id();
            $table->integer('application_id');
            $table->string('date_issued');
            $table->string('noticed_number');
            $table->string('court_type')->nullable();
            $table->string('court_name');
            $table->string('attorney_name');
            $table->string('type');
            $table->string('reason');
            $table->string('plantiff');
            $table->string('amount');
            $table->boolean('valid');
            $table->string('deduction');
            $table->boolean('letter_of_authority');
            $table->boolean('letter_include_loan_amount_term');
            $table->boolean('letter_dated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adminstration_order_metas');
    }
};
