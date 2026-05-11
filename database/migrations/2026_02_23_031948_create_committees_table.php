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
        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->tinyInteger('default_weekday');
            $table->time('default_time');
            $table->string('default_address');
            $table->string('default_room');
            $table->string('default_latitude')->default();
            $table->string('default_longitude')->default('1');
            $table->boolean('minutes_in_wiki')->default(false);
            $table->boolean('wiki_internal_minutes')->default(false);
            $table->string('wiki_path_internal')->nullable();
            $table->string('wiki_path_draft')->nullable();
            $table->string('wiki_path_public')->nullable();
            $table->json('minutes_structure')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committees');
    }
};
