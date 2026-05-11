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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username');
            $table->string('firstname');
            $table->string('lastname');
            $table->json('groups');
            $table->longText('oidc_token');
            $table->longText('oidc_refresh_token');
            $table->longText('oidc_id_token');
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('firstname');
            $table->dropColumn('lastname');
            $table->dropColumn('groups');
            $table->dropColumn('oidc_token');
            $table->dropColumn('oidc_refresh_token');
            $table->dropColumn('oidc_id_token');
            $table->string('password')->nullable(false)->change();
        });
    }
};
