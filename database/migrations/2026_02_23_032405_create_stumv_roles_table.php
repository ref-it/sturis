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
        Schema::create('stumv_roles', function (Blueprint $table) {
            $table->id();
            $table->integer('committee');
            $table->integer('group')->nullable();
            $table->boolean('flag_elected');
            $table->boolean('flag_active');
            $table->boolean('flag_staff');
            $table->string('stumv_committee');
            $table->string('stumv_role');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stumv_roles');
    }
};
