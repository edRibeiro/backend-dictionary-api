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
        Schema::create('phonetics', function (Blueprint $table) {
            $table->id();
            $table->string('text')->nullable();
            $table->string('audio')->nullable();
            $table->string('source_url')->nullable();
            $table->string('license')->nullable();
            $table->foreignId('word_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phonetics');
    }
};
