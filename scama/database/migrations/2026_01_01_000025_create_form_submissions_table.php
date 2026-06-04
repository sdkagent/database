<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('form_key', 50);
            $table->json('data');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('form_key', 'idx_form_submissions_key');
            $table->index('user_id', 'idx_form_submissions_user');
            $table->index('created_at', 'idx_form_submissions_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
