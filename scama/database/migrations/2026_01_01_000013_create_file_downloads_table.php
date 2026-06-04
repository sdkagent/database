<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_downloads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamp('last_downloaded')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('order_item_id', 'idx_file_downloads_order_item');
            $table->index('user_id', 'idx_file_downloads_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_downloads');
    }
};
