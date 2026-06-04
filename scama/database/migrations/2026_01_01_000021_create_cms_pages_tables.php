<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->longText('content')->nullable();
            $table->string('status', 20)->default('draft');
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 500)->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->string('og_image', 500)->nullable();
            $table->string('og_title', 255)->nullable();
            $table->text('og_description')->nullable();
            $table->string('twitter_card', 30)->nullable();
            $table->boolean('noindex')->default(false);
            $table->decimal('priority', 2, 1)->default(0.50);
            $table->string('changefreq', 20)->default('weekly');
            $table->boolean('sitemap_include')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_for')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('slug', 'unique_cms_pages_slug');
            $table->index('status', 'idx_cms_pages_status');
            $table->index(['sitemap_include', 'status'], 'idx_cms_pages_sitemap');
            $table->index('noindex', 'idx_cms_pages_noindex');
            $table->index('author_id', 'idx_cms_pages_author');
        });

        Schema::create('cms_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('slug', 100)->nullable();
            $table->timestamps();

            $table->unique('slug', 'unique_cms_menus_slug');
        });

        Schema::create('cms_menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('menu_id')->constrained('cms_menus')->cascadeOnDelete();
            $table->string('title', 100);
            $table->string('url', 255)->nullable();
            $table->string('target', 20)->default('_self');
            $table->integer('position')->default(0);
            $table->timestamps();

            $table->index('menu_id', 'idx_cms_menu_items_menu');
        });

        Schema::create('cms_widgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('slug', 100)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique('slug', 'unique_cms_widgets_slug');
        });

        Schema::create('cms_banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('link', 255)->nullable();
            $table->string('status', 20)->default('draft');
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        Schema::create('cms_testimonials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->nullable();
            $table->string('position', 255)->nullable();
            $table->string('company', 255)->nullable();
            $table->text('content')->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_testimonials');
        Schema::dropIfExists('cms_banners');
        Schema::dropIfExists('cms_widgets');
        Schema::dropIfExists('cms_menu_items');
        Schema::dropIfExists('cms_menus');
        Schema::dropIfExists('cms_pages');
    }
};
