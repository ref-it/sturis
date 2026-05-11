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
        Schema::create('agenda_items', function (Blueprint $table) {
            $table->id();
            $table->integer('committee');
            $table->integer('meeting');
            $table->integer('structure_id')->nullable();
            $table->integer('parent')->nullable();
            $table->integer('order');
            $table->string('title');
            $table->longText('text');
            $table->string('people');
            $table->integer('expected_duration');
            $table->json('goals');
            $table->boolean('guest');
            $table->boolean('internal');
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_items');
    }
};
