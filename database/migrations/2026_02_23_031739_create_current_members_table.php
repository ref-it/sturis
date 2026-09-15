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
        Schema::create('current_members', function (Blueprint $table) {
            $table->id();
            $table->string('committee');
            $table->int('user_id');
            $table->string('job');
            $table->boolean('flag_elected');
            $table->boolean('flag_active');
            $table->boolean('flag_staff');
            $table->boolean('flag_suspended');
            $table->integer('group');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_members');
    }
};
