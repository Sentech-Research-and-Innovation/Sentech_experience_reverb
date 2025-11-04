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
         Schema::create('messages', function (Blueprint $table) {
        $table->id();  // auto-incrementing ID
        $table->foreignId('user_id')->constrained()->onDelete('cascade');  // user foreign key
        $table->text('message');  // the content of the message
        $table->timestamps();  // created_at and updated_at columns
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
