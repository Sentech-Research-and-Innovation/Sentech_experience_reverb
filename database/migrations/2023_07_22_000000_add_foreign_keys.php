<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->foreign('contact_person_id')->references('id')->on('users')->onDelete('set null');
        });
    }
}
