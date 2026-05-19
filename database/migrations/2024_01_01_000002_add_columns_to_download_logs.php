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
        Schema::table('download_logs', function (Blueprint $table) {
            $table->string('user_agent', 500)->nullable()->after('status');
            $table->string('referer', 500)->nullable()->after('user_agent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('download_logs', function (Blueprint $table) {
            $table->dropColumn(['user_agent', 'referer']);
        });
    }
};
