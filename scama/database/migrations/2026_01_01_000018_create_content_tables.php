<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_updates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('version', 20);
            $table->string('type', 20)->default('update');
            $table->text('changelog')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('version', 'idx_app_updates_version');
        });

        Schema::create('release_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('version', 20);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('user_guides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug', 255)->nullable();
            $table->text('content');
            $table->string('status', 20)->default('draft');
            $table->timestamps();

            $table->unique('slug', 'unique_user_guides_slug');
            $table->index('author_id', 'idx_guides_author');
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('knowledge_base_articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug', 255)->nullable();
            $table->text('content');
            $table->string('category', 100)->nullable();
            $table->string('status', 20)->default('published');
            $table->timestamps();

            $table->unique('slug', 'unique_kb_articles_slug');
            $table->index('category', 'idx_kb_articles_category');
            $table->index('status', 'idx_kb_articles_status');
        });

        Schema::create('faq_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('question');
            $table->text('answer');
            $table->string('slug', 255)->nullable();
            $table->string('category', 100)->nullable();
            $table->integer('position')->default(0);
            $table->string('status', 20)->default('published');
            $table->timestamp('created_at')->useCurrent();

            $table->unique('slug', 'unique_faq_items_slug');
            $table->index('category', 'idx_faq_items_category');
            $table->index('position', 'idx_faq_items_position');
            $table->index('status', 'idx_faq_items_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq_items');
        Schema::dropIfExists('knowledge_base_articles');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('user_guides');
        Schema::dropIfExists('release_notes');
        Schema::dropIfExists('application_updates');
    }
};
