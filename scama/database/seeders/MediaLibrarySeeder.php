<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaLibrarySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('media_library')->insert([
            [
                'id' => 1,
                'filename' => 'hero-banner.png',
                'filepath' => '/uploads/media/hero-banner.png',
                'mime_type' => 'image/png',
                'file_size' => 245000,
                'width' => 1920,
                'height' => 1080,
                'alt_text' => 'LicensePro platform dashboard',
                'caption' => 'Main hero banner for the homepage',
                'uploaded_by' => 1,
            ],
            [
                'id' => 2,
                'filename' => 'api-documentation.pdf',
                'filepath' => '/uploads/media/api-docs.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 520000,
                'width' => null,
                'height' => null,
                'alt_text' => 'API documentation download',
                'caption' => 'Complete API reference guide',
                'uploaded_by' => 1,
            ],
            [
                'id' => 3,
                'filename' => 'product-screenshot.jpg',
                'filepath' => '/uploads/media/product-shot.jpg',
                'mime_type' => 'image/jpeg',
                'file_size' => 180000,
                'width' => 1280,
                'height' => 720,
                'alt_text' => 'License key generation interface',
                'caption' => 'Screenshot of the license generation form',
                'uploaded_by' => 4,
            ],
        ]);

        DB::table('content_revisions')->insert([
            [
                'id' => 1,
                'content_type' => 'post',
                'content_id' => 1,
                'title' => 'Introducing Version 2.0',
                'content' => '<h1>Version 2.0 is here!</h1><p>Initial draft of the announcement.</p>',
                'summary' => 'Draft version of the v2.0 announcement.',
                'meta' => json_encode(['word_count' => 450, 'editor' => 'rich']),
                'created_by' => 1,
            ],
            [
                'id' => 2,
                'content_type' => 'post',
                'content_id' => 1,
                'title' => 'Introducing Version 2.0',
                'content' => '<h1>Version 2.0 is here!</h1><p>Updated with feature list.</p>',
                'summary' => 'Second draft with detailed features.',
                'meta' => json_encode(['word_count' => 890, 'editor' => 'rich']),
                'created_by' => 1,
            ],
            [
                'id' => 3,
                'content_type' => 'cms_page',
                'content_id' => 1,
                'title' => 'Home',
                'content' => '<h1>Welcome</h1><p>Original homepage content before redesign.</p>',
                'summary' => 'Original homepage content.',
                'meta' => json_encode(['word_count' => 120, 'editor' => 'rich']),
                'created_by' => 4,
            ],
        ]);

        DB::table('cms_page_tags')->insert([
            ['page_id' => 1, 'tag_id' => 1],
            ['page_id' => 1, 'tag_id' => 2],
            ['page_id' => 2, 'tag_id' => 2],
            ['page_id' => 3, 'tag_id' => 3],
        ]);
    }
}
