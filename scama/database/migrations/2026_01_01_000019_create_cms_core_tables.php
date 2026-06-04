<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('slug', 100)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique('slug', 'unique_cms_categories_slug');
        });

        Schema::create('cms_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('slug', 100)->nullable();
            $table->timestamps();

            $table->unique('slug', 'unique_cms_tags_slug');
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 10)->default('cms');
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug', 255)->nullable();
            $table->text('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('featured_image', 500)->nullable();
            $table->foreignId('category_id')->nullable()->constrained('cms_categories')->nullOnDelete();
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_for')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 500)->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->string('og_image', 500)->nullable();
            $table->string('og_title', 255)->nullable();
            $table->text('og_description')->nullable();
            $table->string('twitter_card', 30)->nullable();
            $table->boolean('noindex')->default(false);
            $table->decimal('priority', 2, 1)->default(0.50);
            $table->string('changefreq', 20)->default('weekly');
            $table->boolean('sitemap_include')->default(true);
            $table->timestamps();

            $table->unique(['slug', 'type'], 'unique_posts_slug_type');
            $table->index(['type', 'status'], 'idx_posts_type_status');
            $table->index('author_id', 'idx_posts_author');
            $table->index('category_id', 'idx_posts_category');
            $table->index('published_at', 'idx_posts_published');
            $table->index(['type', 'author_id', 'status'], 'idx_posts_type_author_status');
            $table->index('scheduled_for', 'idx_posts_scheduled');
            $table->index(['sitemap_include', 'status'], 'idx_posts_sitemap');
            $table->index('noindex', 'idx_posts_noindex');
        });

        Schema::create('post_tags', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('cms_tags')->cascadeOnDelete();

            $table->primary(['post_id', 'tag_id']);
            $table->index('tag_id', 'idx_post_tags_tag');
        });

        Schema::create('post_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('post_comments')->nullOnDelete();
            $table->string('author_name', 255)->nullable();
            $table->string('author_email', 255)->nullable();
            $table->text('body');
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->index('post_id', 'idx_post_comments_post');
            $table->index('user_id', 'idx_post_comments_user');
            $table->index('parent_id', 'idx_post_comments_parent');
            $table->index('status', 'idx_post_comments_status');
        });

        Schema::create('post_reactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('reaction', 10)->default('like');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['post_id', 'user_id', 'reaction'], 'unique_post_reactions_user');
            $table->index('post_id', 'idx_post_reactions_post');
        });

        Schema::create('post_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('viewed_at')->useCurrent();

            $table->index('post_id', 'idx_post_views_post');
            $table->index('user_id', 'idx_post_views_user');
            $table->index('viewed_at', 'idx_post_views_date');
        });

        Schema::create('post_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path', 500);
            $table->string('file_type', 50);
            $table->unsignedInteger('file_size')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('post_id', 'idx_post_media_post');
            $table->index(['post_id', 'is_featured'], 'idx_post_media_featured');
        });

        Schema::create('post_series', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image', 500)->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->unique('slug', 'unique_post_series_slug');
            $table->index('author_id', 'idx_post_series_author');
        });

        Schema::create('post_series_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('series_id')->constrained('post_series')->cascadeOnDelete();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->integer('part_order')->default(0);
            $table->string('part_title', 255)->nullable();

            $table->unique(['series_id', 'post_id'], 'unique_series_item');
            $table->index('series_id', 'idx_post_series_items_series');
            $table->index('post_id', 'idx_post_series_items_post');
        });

        Schema::create('related_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('related_post_id')->constrained('posts')->cascadeOnDelete();
            $table->string('relation_type', 20)->default('manual');
            $table->integer('weight')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['post_id', 'related_post_id'], 'unique_related_pair');
            $table->index('post_id', 'idx_related_posts_post');
            $table->index('related_post_id', 'idx_related_posts_related');
        });

        Schema::create('author_profiles', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('display_name', 255)->nullable();
            $table->string('avatar_url', 500)->nullable();
            $table->text('bio')->nullable();
            $table->string('website_url', 500)->nullable();
            $table->string('twitter_handle', 100)->nullable();
            $table->string('github_handle', 100)->nullable();
            $table->string('linkedin_url', 500)->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();

            $table->primary('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('author_profiles');
        Schema::dropIfExists('related_posts');
        Schema::dropIfExists('post_series_items');
        Schema::dropIfExists('post_series');
        Schema::dropIfExists('post_media');
        Schema::dropIfExists('post_views');
        Schema::dropIfExists('post_reactions');
        Schema::dropIfExists('post_comments');
        Schema::dropIfExists('post_tags');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('cms_tags');
        Schema::dropIfExists('cms_categories');
    }
};
