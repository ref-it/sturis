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
        Schema::create('resolutions', function (Blueprint $table) {
            $table->id();
            $table->integer('committee');
            $table->integer('meeting');
            $table->string('number');
            $table->integer('type');
            $table->longText('text');
            $table->longText('link')->nullable();
            $table->integer('yes')->nullable();
            $table->integer('no')->nullable();
            $table->integer('abstention')->nullable();
            $table->text('result');
            $table->boolean('internal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resolutions');
    }
};
