<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consent_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('consent_type', ['marketing', 'analytics', 'functional', 'third_party', 'cookies'])->notNull();
            $table->string('purpose', 255)->nullable();
            $table->boolean('granted')->notNull();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id', 'idx_consent_logs_user');
            $table->index('consent_type', 'idx_consent_logs_type');
        });

        Schema::create('data_export_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->enum('format', ['json', 'csv', 'xml'])->default('json');
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->string('file_path', 500)->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id', 'idx_data_export_requests_user');
            $table->index('status', 'idx_data_export_requests_status');
        });

        Schema::create('data_deletion_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('reason')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('user_id', 'idx_data_deletion_requests_user');
            $table->index('status', 'idx_data_deletion_requests_status');
            $table->index('processed_by', 'idx_data_deletion_requests_processor');
        });

        Schema::create('cookie_consent_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->notNull();
            $table->string('slug', 255)->notNull()->unique();
            $table->text('description')->nullable();
            $table->boolean('required')->default(false);
            $table->boolean('default_granted')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cookie_consent_settings');
        Schema::dropIfExists('data_deletion_requests');
        Schema::dropIfExists('data_export_requests');
        Schema::dropIfExists('consent_logs');
    }
};
