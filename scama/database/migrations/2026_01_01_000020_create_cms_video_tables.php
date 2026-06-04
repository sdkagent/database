<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_galleries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique('slug', 'unique_video_galleries_slug');
            $table->index('author_id', 'idx_video_galleries_author');
            $table->index('status', 'idx_video_galleries_status');
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('gallery_id')->nullable()->constrained('video_galleries')->nullOnDelete();
            $table->string('title');
            $table->string('slug', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('video_url', 500)->nullable();
            $table->string('embed_url', 500)->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('file_type', 50)->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('draft');
            $table->boolean('featured')->default(false);
            $table->unsignedInteger('view_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique('slug', 'unique_videos_slug');
            $table->index('gallery_id', 'idx_videos_gallery');
            $table->index('author_id', 'idx_videos_author');
            $table->index('status', 'idx_videos_status');
            $table->index('featured', 'idx_videos_featured');
            $table->index(['gallery_id', 'sort_order'], 'idx_videos_gallery_sort');
        });

        Schema::create('video_tags', function (Blueprint $table) {
            $table->foreignId('video_id')->constrained('videos')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('cms_tags')->cascadeOnDelete();

            $table->primary(['video_id', 'tag_id']);
            $table->index('tag_id', 'idx_video_tags_tag');
        });

        Schema::create('video_guidelines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('video_id')->constrained('videos')->cascadeOnDelete();
            $table->integer('step_order')->default(0);
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('time_marker')->nullable();
            $table->string('image', 500)->nullable();
            $table->timestamps();

            $table->index('video_id', 'idx_video_guidelines_video');
            $table->index(['video_id', 'step_order'], 'idx_video_guidelines_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_guidelines');
        Schema::dropIfExists('video_tags');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('video_galleries');
    }
};
