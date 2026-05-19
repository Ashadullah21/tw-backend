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
        Schema::create('download_logs', function (Blueprint $table) {
            $table->id();                                             // bigint PK
            $table->text('url');                                      // full tweet URL
            $table->string('ip_address', 45)->nullable();             // IPv4 or IPv6
            $table->enum('status', ['success', 'failed'])             // result
                  ->default('failed');
            $table->timestamp('created_at')->useCurrent();            // auto-set on insert
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('download_logs');
    }
};
