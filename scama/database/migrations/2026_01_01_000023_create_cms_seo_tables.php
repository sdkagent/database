<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('old_path', 500);
            $table->string('new_path', 500);
            $table->string('status_code', 3)->default('301');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('hits_count')->default(0);
            $table->timestamps();

            $table->unique('old_path', 'unique_redirects_old_path');
            $table->index('is_active', 'idx_redirects_active');
            $table->index('status_code', 'idx_redirects_status_code');
        });

        Schema::create('slug_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('content_type', 30);
            $table->unsignedBigInteger('content_id');
            $table->string('old_slug');
            $table->string('new_slug');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['content_type', 'content_id'], 'idx_slug_history_content');
            $table->index('old_slug', 'idx_slug_history_old');
            $table->index('new_slug', 'idx_slug_history_new');
        });

        Schema::create('structured_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('content_type', 20);
            $table->unsignedBigInteger('content_id');
            $table->string('schema_type', 50);
            $table->json('json_ld');
            $table->timestamps();

            $table->index(['content_type', 'content_id'], 'idx_structured_data_content');
            $table->index('schema_type', 'idx_structured_data_schema_type');
            $table->unique(['content_type', 'content_id', 'schema_type'], 'unique_structured_data_entry');
        });

        Schema::create('seo_analysis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('content_type', 20);
            $table->unsignedBigInteger('content_id');
            $table->decimal('score', 5, 2)->nullable();
            $table->json('issues')->nullable();
            $table->unsignedInteger('word_count')->nullable();
            $table->decimal('readability_score', 5, 2)->nullable();
            $table->timestamp('checked_at')->useCurrentOnUpdate();

            $table->index(['content_type', 'content_id'], 'idx_seo_analysis_content');
            $table->index('score', 'idx_seo_analysis_score');
            $table->index('checked_at', 'idx_seo_analysis_checked');
        });

        Schema::create('analytics_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_type', 50);
            $table->string('page_url', 500)->nullable();
            $table->string('referrer_url', 500)->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->string('utm_term', 100)->nullable();
            $table->string('utm_content', 100)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id', 100)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('post_id')->nullable()->constrained('posts')->nullOnDelete();
            $table->foreignId('page_id')->nullable()->constrained('cms_pages')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index('event_type', 'idx_analytics_events_type');
            $table->index('created_at', 'idx_analytics_events_date');
            $table->index('session_id', 'idx_analytics_events_session');
            $table->index('user_id', 'idx_analytics_events_user');
            $table->index('post_id', 'idx_analytics_events_post');
            $table->index('page_id', 'idx_analytics_events_page');
            $table->index('utm_campaign', 'idx_analytics_events_utm_campaign');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
        Schema::dropIfExists('seo_analysis');
        Schema::dropIfExists('structured_data');
        Schema::dropIfExists('slug_history');
        Schema::dropIfExists('redirects');
    }
};
