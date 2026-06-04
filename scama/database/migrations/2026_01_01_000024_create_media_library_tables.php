<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_library', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('filename');
            $table->string('filepath', 500);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('alt_text', 255)->nullable();
            $table->text('caption')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('mime_type', 'idx_media_library_mime');
            $table->index('uploaded_by', 'idx_media_library_uploader');
        });

        Schema::create('content_revisions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('content_type', 30);
            $table->unsignedBigInteger('content_id');
            $table->string('title', 255)->nullable();
            $table->longText('content')->nullable();
            $table->text('summary')->nullable();
            $table->json('meta')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['content_type', 'content_id'], 'idx_content_revisions_content');
            $table->index('created_by', 'idx_content_revisions_author');
            $table->index('created_at', 'idx_content_revisions_created');
        });

        Schema::create('cms_page_tags', function (Blueprint $table) {
            $table->foreignId('page_id')->constrained('cms_pages')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('cms_tags')->cascadeOnDelete();

            $table->primary(['page_id', 'tag_id']);
            $table->index('tag_id', 'idx_cms_page_tags_tag');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_page_tags');
        Schema::dropIfExists('content_revisions');
        Schema::dropIfExists('media_library');
    }
};
