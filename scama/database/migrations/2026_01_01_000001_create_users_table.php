<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('role', 20)->default('user');
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('status', 20)->default('active');
            $table->json('ip_whitelist')->nullable();
            $table->string('telegram_chat_id', 100)->nullable();
            $table->json('settings')->nullable();
            $table->string('avatar_url', 500)->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('locale', 5)->default('en');
            $table->string('timezone', 50)->default('UTC');
            $table->timestamps();

            $table->unique('email', 'unique_users_email');
            $table->index('status', 'idx_users_status');
            $table->index('role', 'idx_users_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
