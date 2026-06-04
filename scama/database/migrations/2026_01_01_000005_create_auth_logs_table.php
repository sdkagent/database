<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type', 20);
            $table->string('ip_address', 45);
            $table->string('device_fingerprint', 255)->nullable();
            $table->string('status', 20)->default('success');
            $table->json('details')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'type'], 'idx_auth_logs_user_type');
            $table->index('created_at', 'idx_auth_logs_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_logs');
    }
};
