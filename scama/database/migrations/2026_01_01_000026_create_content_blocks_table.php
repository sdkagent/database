<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key', 100);
            $table->string('title');
            $table->longText('content')->nullable();
            $table->string('type', 10)->default('html');
            $table->json('locations')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique('key', 'unique_content_blocks_key');
            $table->index('active', 'idx_content_blocks_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
    }
};
