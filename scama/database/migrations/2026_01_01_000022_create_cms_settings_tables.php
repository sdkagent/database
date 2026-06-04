<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key', 100);
            $table->text('value')->nullable();
            $table->string('group', 20)->default('general');
            $table->timestamps();

            $table->unique(['key', 'group'], 'unique_cms_settings_key_group');
        });

        Schema::create('cms_social_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('platform', 50);
            $table->string('url');
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        Schema::create('cms_footers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('cms_headers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('cms_sidebars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('cms_media_galleries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type', 50);
            $table->integer('file_size')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_media_galleries');
        Schema::dropIfExists('cms_sidebars');
        Schema::dropIfExists('cms_headers');
        Schema::dropIfExists('cms_footers');
        Schema::dropIfExists('cms_social_links');
        Schema::dropIfExists('cms_settings');
    }
};
