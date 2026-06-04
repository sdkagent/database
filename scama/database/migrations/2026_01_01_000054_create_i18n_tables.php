<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_packs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 10)->notNull()->unique();
            $table->string('name', 100)->notNull();
            $table->string('native_name', 100)->nullable();
            $table->boolean('is_rtl')->default(false);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active', 'idx_language_packs_active');
            $table->index('is_default', 'idx_language_packs_default');
        });

        Schema::create('translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('language_pack_id')->constrained('language_packs')->cascadeOnDelete();
            $table->string('namespace', 100)->default('frontend');
            $table->string('group', 100)->default('general');
            $table->string('key', 255)->notNull();
            $table->text('value')->notNull();
            $table->timestamps();

            $table->unique(['language_pack_id', 'namespace', 'group', 'key'], 'idx_translations_unique');
            $table->index('language_pack_id', 'idx_translations_language');
            $table->index('key', 'idx_translations_key');
        });

        Schema::create('translation_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('language_pack_id')->constrained('language_packs')->cascadeOnDelete();
            $table->string('namespace', 100)->default('frontend');
            $table->string('file_path', 500)->notNull();
            $table->enum('file_format', ['json', 'po', 'xlf', 'csv'])->default('json');
            $table->unsignedInteger('version')->default(1);
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('language_pack_id', 'idx_translation_files_language');
            $table->index('namespace', 'idx_translation_files_namespace');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_files');
        Schema::dropIfExists('translations');
        Schema::dropIfExists('language_packs');
    }
};
