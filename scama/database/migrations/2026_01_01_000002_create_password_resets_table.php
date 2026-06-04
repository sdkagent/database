<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('token', 191);
            $table->string('type', 20)->default('password');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index('user_id', 'idx_password_resets_user');
            $table->index('token', 'idx_password_resets_token');
            $table->index('expires_at', 'idx_password_resets_expires');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_resets');
    }
};
