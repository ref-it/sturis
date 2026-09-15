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
        Schema::create('oidc_backchannel_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('sub')->index();
            $table->string('sid')->nullable()->index();
            $table->string('laravel_session_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oidc_backchannel_sessions');
    }
};
