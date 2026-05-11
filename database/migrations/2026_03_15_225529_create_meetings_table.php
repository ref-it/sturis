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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->integer('committee');
            $table->date('date');
            $table->time('time');
            $table->string('address');
            $table->string('room');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('name');
            $table->json('meeting_chairs')->default('[]');
            $table->json('minute_takers')->default('[]');
            $table->string('url_internal')->nullable();
            $table->string('url_draft')->nullable();
            $table->string('url_public')->nullable();
            $table->integer('minutes_approved_resolution')->nullable();
            $table->string('uid');
            $table->longText('minutes_structure');
            $table->boolean('ignore_minutes');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
