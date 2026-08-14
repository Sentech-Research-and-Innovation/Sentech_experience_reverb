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
    		Schema::create('sentalks', function (Blueprint $table) {
        		$table->id();
        		$table->string('pdf_path');
        		$table->string('creator');
        		$table->integer('number_views')->default(0);
        		$table->integer('number_likes')->default(0);
        		$table->integer('number_downloads')->default(0);
        		$table->timestamps();
    		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentalks');
    }
};
