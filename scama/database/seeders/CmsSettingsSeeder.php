<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsSettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cms_settings')->insert([
            [
                'key' => 'site_title',
                'value' => 'LicensePro',
                'group' => 'general',
            ],
            [
                'key' => 'meta_description',
                'value' => 'Software Licensing Platform',
                'group' => 'seo',
            ],
            [
                'key' => 'ga_tracking_id',
                'value' => 'G-XXXXXXXXXX',
                'group' => 'analytics',
            ],
            [
                'key' => 'primary_color',
                'value' => '#3490dc',
                'group' => 'general',
            ],
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/licensepro',
                'group' => 'social',
            ],
        ]);

        DB::table('cms_social_links')->insert([
            [
                'platform' => 'Twitter',
                'url' => 'https://twitter.com/licensepro',
                'position' => 1,
            ],
            [
                'platform' => 'LinkedIn',
                'url' => 'https://linkedin.com/company/licensepro',
                'position' => 2,
            ],
            [
                'platform' => 'GitHub',
                'url' => 'https://github.com/licensepro',
                'position' => 3,
            ],
        ]);

        DB::table('cms_footers')->insert([
            [
                'content' => '<p>&copy; 2026 LicensePro. All rights reserved.</p>',
            ],
            [
                'content' => '<p>Built with care for developers worldwide.</p>',
            ],
        ]);

        DB::table('cms_headers')->insert([
            [
                'content' => '<header><nav><!-- Main navigation --></nav></header>',
            ],
            [
                'content' => '<header class="alternative"><nav><!-- Alternative header --></nav></header>',
            ],
        ]);

        DB::table('cms_sidebars')->insert([
            [
                'content' => '<aside><h3>Categories</h3><ul><li>News</li><li>Tutorials</li></ul></aside>',
            ],
            [
                'content' => '<aside><h3>Tags</h3><div class="tag-cloud"><span>licensing</span></div></aside>',
            ],
        ]);

        DB::table('cms_media_galleries')->insert([
            [
                'file_name' => 'logo.png',
                'file_path' => '/uploads/logo.png',
                'file_type' => 'image/png',
                'file_size' => 24576,
            ],
            [
                'file_name' => 'screenshot.jpg',
                'file_path' => '/uploads/screenshot.jpg',
                'file_type' => 'image/jpeg',
                'file_size' => 512000,
            ],
            [
                'file_name' => 'guide.pdf',
                'file_path' => '/uploads/guide.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 1048576,
            ],
        ]);
    }
}
