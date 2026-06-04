<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsSeoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('redirects')->insert([
            [
                'id' => 1,
                'old_path' => '/old-license-page',
                'new_path' => '/licenses',
                'status_code' => '301',
                'is_active' => true,
                'hits_count' => 245,
            ],
            [
                'id' => 2,
                'old_path' => '/blog/old-post',
                'new_path' => '/blog/new-post',
                'status_code' => '301',
                'is_active' => true,
                'hits_count' => 89,
            ],
            [
                'id' => 3,
                'old_path' => '/temp-promo',
                'new_path' => '/pricing',
                'status_code' => '302',
                'is_active' => false,
                'hits_count' => 0,
            ],
        ]);

        DB::table('slug_history')->insert([
            [
                'id' => 1,
                'content_type' => 'post',
                'content_id' => 1,
                'old_slug' => 'intro-v1',
                'new_slug' => 'intro-v2',
            ],
            [
                'id' => 2,
                'content_type' => 'cms_page',
                'content_id' => 2,
                'old_slug' => 'about-us',
                'new_slug' => 'about',
            ],
            [
                'id' => 3,
                'content_type' => 'product',
                'content_id' => 1,
                'old_slug' => 'license-manager-v1',
                'new_slug' => 'license-manager',
            ],
        ]);

        DB::table('structured_data')->insert([
            [
                'id' => 1,
                'content_type' => 'post',
                'content_id' => 1,
                'schema_type' => 'Article',
                'json_ld' => '{"@context":"https://schema.org","@type":"Article","headline":"Introducing Version 2.0","datePublished":"2026-03-20"}',
            ],
            [
                'id' => 2,
                'content_type' => 'cms_page',
                'content_id' => 1,
                'schema_type' => 'WebSite',
                'json_ld' => '{"@context":"https://schema.org","@type":"WebSite","name":"LicensePro","url":"https://licensepro.com"}',
            ],
            [
                'id' => 3,
                'content_type' => 'product',
                'content_id' => 1,
                'schema_type' => 'SoftwareApplication',
                'json_ld' => '{"@context":"https://schema.org","@type":"SoftwareApplication","name":"LicensePro","operatingSystem":"Windows,Linux,macOS"}',
            ],
        ]);

        DB::table('seo_analysis')->insert([
            [
                'id' => 1,
                'content_type' => 'post',
                'content_id' => 1,
                'score' => 92.50,
                'issues' => json_encode([]),
                'word_count' => 1240,
                'readability_score' => 78.30,
            ],
            [
                'id' => 2,
                'content_type' => 'cms_page',
                'content_id' => 1,
                'score' => 85.00,
                'issues' => json_encode([['type' => 'missing_alt', 'severity' => 'medium']]),
                'word_count' => 520,
                'readability_score' => 65.00,
            ],
            [
                'id' => 3,
                'content_type' => 'post',
                'content_id' => 4,
                'score' => 95.00,
                'issues' => json_encode([['type' => 'noindex', 'severity' => 'info']]),
                'word_count' => 980,
                'readability_score' => 82.50,
            ],
        ]);

        DB::table('analytics_events')->insert([
            [
                'id' => 1,
                'event_type' => 'pageview',
                'page_url' => '/blog/intro-v2',
                'referrer_url' => 'https://google.com',
                'utm_source' => 'google',
                'utm_medium' => 'organic',
                'utm_campaign' => 'brand',
                'user_agent' => 'Mozilla/5.0...',
                'ip_address' => '192.168.1.1',
                'session_id' => 'abc123',
                'user_id' => 1,
                'post_id' => 1,
                'page_id' => null,
            ],
            [
                'id' => 2,
                'event_type' => 'pageview',
                'page_url' => '/contact',
                'referrer_url' => 'https://example.com',
                'utm_source' => null,
                'utm_medium' => null,
                'utm_campaign' => null,
                'user_agent' => 'Mozilla/5.0...',
                'ip_address' => '10.0.0.1',
                'session_id' => 'def456',
                'user_id' => null,
                'post_id' => null,
                'page_id' => 3,
            ],
            [
                'id' => 3,
                'event_type' => 'click',
                'page_url' => '/pricing',
                'referrer_url' => null,
                'utm_source' => 'twitter',
                'utm_medium' => 'social',
                'utm_campaign' => 'launch',
                'user_agent' => 'Mozilla/5.0...',
                'ip_address' => '172.16.0.1',
                'session_id' => 'ghi789',
                'user_id' => 2,
                'post_id' => null,
                'page_id' => null,
            ],
        ]);
    }
}
