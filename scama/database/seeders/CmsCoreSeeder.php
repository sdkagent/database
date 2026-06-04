<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsCoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cms_categories')->insert([
            [
                'id' => 1,
                'name' => 'News',
                'slug' => 'news',
                'description' => 'Company news and announcements',
            ],
            [
                'id' => 2,
                'name' => 'Tutorials',
                'slug' => 'tutorials',
                'description' => 'How-to guides and walkthroughs',
            ],
        ]);

        DB::table('cms_tags')->insert([
            [
                'id' => 1,
                'name' => 'licensing',
                'slug' => 'licensing',
            ],
            [
                'id' => 2,
                'name' => 'security',
                'slug' => 'security',
            ],
            [
                'id' => 3,
                'name' => 'api',
                'slug' => 'api',
            ],
        ]);

        DB::table('posts')->insert([
            [
                'id' => 1,
                'type' => 'blog',
                'author_id' => 1,
                'title' => 'Introducing Version 2.0',
                'slug' => 'intro-v2',
                'content' => '<h1>Version 2.0 is here!</h1><p>We are excited to announce major improvements to the licensing engine.</p>',
                'excerpt' => 'Major improvements to the licensing engine are now available in v2.0.',
                'featured_image' => null,
                'category_id' => 1,
                'status' => 'published',
                'published_at' => '2026-03-20 10:00:00',
                'view_count' => 152,
                'og_title' => 'Introducing LicensePro v2.0',
                'og_description' => 'Discover the new licensing engine with hardware locking and API improvements.',
                'twitter_card' => 'summary_large_image',
                'noindex' => false,
                'priority' => 0.80,
                'changefreq' => 'monthly',
                'sitemap_include' => true,
                'created_at' => '2026-03-20 10:00:00',
            ],
            [
                'id' => 2,
                'type' => 'blog',
                'author_id' => 1,
                'title' => 'Best Practices for Licensing',
                'slug' => 'best-practices',
                'content' => '<p>Follow these guidelines to secure your software products effectively.</p>',
                'excerpt' => 'Secure your software products with these proven licensing guidelines.',
                'featured_image' => null,
                'category_id' => 2,
                'status' => 'published',
                'published_at' => '2026-04-05 14:00:00',
                'view_count' => 89,
                'og_title' => 'Software Licensing Best Practices',
                'og_description' => 'Tips for protecting your software with effective license management.',
                'twitter_card' => 'summary',
                'noindex' => false,
                'priority' => 0.70,
                'changefreq' => 'monthly',
                'sitemap_include' => true,
                'created_at' => '2026-04-05 14:00:00',
            ],
            [
                'id' => 3,
                'type' => 'cms',
                'author_id' => 1,
                'title' => 'Welcome to Our New CMS',
                'slug' => 'welcome-cms',
                'content' => '<p>We are thrilled to launch our new content management system.</p>',
                'excerpt' => 'Our new CMS is live with powerful content management features.',
                'featured_image' => '/uploads/cms-welcome.jpg',
                'category_id' => 1,
                'status' => 'published',
                'published_at' => '2026-05-01 10:00:00',
                'view_count' => 210,
                'og_title' => 'Welcome to the New CMS',
                'og_description' => 'LicensePro launches a powerful new content management system.',
                'twitter_card' => 'summary_large_image',
                'noindex' => false,
                'priority' => 0.90,
                'changefreq' => 'weekly',
                'sitemap_include' => true,
                'created_at' => '2026-05-01 10:00:00',
            ],
            [
                'id' => 4,
                'type' => 'cms',
                'author_id' => 1,
                'title' => 'How to Secure Your License Keys',
                'slug' => 'secure-licenses',
                'content' => '<p>Follow these best practices to protect your license keys from theft.</p>',
                'excerpt' => 'Protect your license keys with these security best practices.',
                'featured_image' => null,
                'category_id' => 2,
                'status' => 'published',
                'published_at' => '2026-05-10 10:00:00',
                'view_count' => 67,
                'og_title' => 'License Key Security Guide',
                'og_description' => 'Essential best practices to keep your license keys safe from unauthorized use.',
                'twitter_card' => 'summary',
                'noindex' => true,
                'priority' => 0.50,
                'changefreq' => 'monthly',
                'sitemap_include' => true,
                'created_at' => '2026-05-10 10:00:00',
            ],
        ]);

        DB::table('post_tags')->insert([
            ['post_id' => 1, 'tag_id' => 1],
            ['post_id' => 1, 'tag_id' => 2],
            ['post_id' => 2, 'tag_id' => 1],
            ['post_id' => 3, 'tag_id' => 1],
            ['post_id' => 3, 'tag_id' => 2],
            ['post_id' => 4, 'tag_id' => 1],
            ['post_id' => 4, 'tag_id' => 3],
        ]);

        DB::table('post_comments')->insert([
            [
                'post_id' => 1,
                'user_id' => 2,
                'parent_id' => null,
                'author_name' => null,
                'author_email' => null,
                'body' => 'Great update! When will the API documentation be available?',
                'status' => 'approved',
                'created_at' => '2026-03-21 08:00:00',
            ],
            [
                'post_id' => 1,
                'user_id' => 1,
                'parent_id' => 1,
                'author_name' => null,
                'author_email' => null,
                'body' => 'Thanks! The API docs will be published next week.',
                'status' => 'approved',
                'created_at' => '2026-03-21 09:00:00',
            ],
            [
                'post_id' => 3,
                'user_id' => 3,
                'parent_id' => null,
                'author_name' => null,
                'author_email' => null,
                'body' => 'The new CMS looks fantastic. Any plans for dark mode?',
                'status' => 'approved',
                'created_at' => '2026-05-02 14:00:00',
            ],
            [
                'post_id' => 3,
                'user_id' => null,
                'parent_id' => null,
                'author_name' => 'Guest User',
                'author_email' => 'guest@example.com',
                'body' => 'This is a great platform, keep up the good work!',
                'status' => 'pending',
                'created_at' => '2026-05-03 10:00:00',
            ],
        ]);

        DB::table('post_reactions')->insert([
            ['post_id' => 1, 'user_id' => 2, 'reaction' => 'like'],
            ['post_id' => 1, 'user_id' => 3, 'reaction' => 'love'],
            ['post_id' => 1, 'user_id' => 4, 'reaction' => 'clap'],
            ['post_id' => 3, 'user_id' => 2, 'reaction' => 'fire'],
            ['post_id' => 3, 'user_id' => 3, 'reaction' => 'like'],
            ['post_id' => 4, 'user_id' => 2, 'reaction' => 'like'],
        ]);

        DB::table('post_views')->insert([
            [
                'post_id' => 1,
                'user_id' => 2,
                'ip_address' => '192.168.1.10',
                'user_agent' => 'Mozilla/5.0',
                'viewed_at' => '2026-03-20 10:30:00',
            ],
            [
                'post_id' => 1,
                'user_id' => 3,
                'ip_address' => '192.168.1.11',
                'user_agent' => 'Mozilla/5.0',
                'viewed_at' => '2026-03-20 11:00:00',
            ],
            [
                'post_id' => 3,
                'user_id' => 2,
                'ip_address' => '192.168.1.10',
                'user_agent' => 'Mozilla/5.0',
                'viewed_at' => '2026-05-01 10:05:00',
            ],
            [
                'post_id' => 3,
                'user_id' => 4,
                'ip_address' => '10.0.0.1',
                'user_agent' => 'Chrome/120',
                'viewed_at' => '2026-05-01 12:00:00',
            ],
        ]);

        DB::table('post_media')->insert([
            [
                'post_id' => 3,
                'file_name' => 'cms-welcome.jpg',
                'file_path' => '/uploads/cms-welcome.jpg',
                'file_type' => 'image/jpeg',
                'file_size' => 245000,
                'is_featured' => true,
                'sort_order' => 0,
            ],
            [
                'post_id' => 3,
                'file_name' => 'cms-screenshot.png',
                'file_path' => '/uploads/cms-screenshot.png',
                'file_type' => 'image/png',
                'file_size' => 520000,
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'post_id' => 4,
                'file_name' => 'license-security.pdf',
                'file_path' => '/uploads/license-security.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 180000,
                'is_featured' => false,
                'sort_order' => 0,
            ],
        ]);

        DB::table('post_series')->insert([
            [
                'id' => 1,
                'title' => 'LicensePro Best Practices',
                'slug' => 'licensepro-best-practices',
                'description' => 'A comprehensive guide to getting the most out of LicensePro.',
                'author_id' => 1,
                'status' => 'active',
            ],
        ]);

        DB::table('post_series_items')->insert([
            [
                'series_id' => 1,
                'post_id' => 1,
                'part_order' => 1,
                'part_title' => 'Introducing the New Features',
            ],
            [
                'series_id' => 1,
                'post_id' => 2,
                'part_order' => 2,
                'part_title' => 'Licensing Best Practices',
            ],
        ]);

        DB::table('related_posts')->insert([
            [
                'post_id' => 1,
                'related_post_id' => 2,
                'relation_type' => 'manual',
                'weight' => 10,
            ],
            [
                'post_id' => 2,
                'related_post_id' => 1,
                'relation_type' => 'manual',
                'weight' => 10,
            ],
            [
                'post_id' => 3,
                'related_post_id' => 4,
                'relation_type' => 'auto_category',
                'weight' => 5,
            ],
            [
                'post_id' => 4,
                'related_post_id' => 3,
                'relation_type' => 'auto_category',
                'weight' => 5,
            ],
        ]);

        DB::table('author_profiles')->insert([
            [
                'user_id' => 1,
                'display_name' => 'Admin User',
                'avatar_url' => '/avatars/admin.png',
                'bio' => 'Platform administrator and content manager.',
                'website_url' => 'https://licensepro.com',
                'twitter_handle' => '@licensepro',
                'github_handle' => 'licensepro-dev',
                'is_public' => true,
            ],
            [
                'user_id' => 4,
                'display_name' => 'Sarah Connor',
                'avatar_url' => '/avatars/sarah.png',
                'bio' => 'Technical writer and documentation specialist.',
                'website_url' => 'https://sarah.dev',
                'twitter_handle' => '@sarah_writes',
                'github_handle' => 'sarah-c',
                'is_public' => true,
            ],
        ]);
    }
}
