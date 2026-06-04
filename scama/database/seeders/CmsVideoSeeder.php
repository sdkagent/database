<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsVideoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('video_galleries')->insert([
            [
                'id' => 1,
                'title' => 'Getting Started Tutorials',
                'slug' => 'getting-started',
                'description' => 'Learn the basics of LicensePro platform setup and configuration.',
                'author_id' => 1,
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'id' => 2,
                'title' => 'Advanced Features',
                'slug' => 'advanced',
                'description' => 'Deep dives into licensing automation, API integration, and security.',
                'author_id' => 1,
                'status' => 'active',
                'sort_order' => 2,
            ],
        ]);

        DB::table('videos')->insert([
            [
                'id' => 1,
                'gallery_id' => 1,
                'title' => 'Platform Overview',
                'slug' => 'platform-overview',
                'description' => 'A quick tour of the LicensePro dashboard and key features.',
                'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'thumbnail' => '/thumbnails/overview.jpg',
                'duration' => 240,
                'author_id' => 1,
                'status' => 'published',
                'featured' => true,
                'view_count' => 1205,
                'sort_order' => 1,
            ],
            [
                'id' => 2,
                'gallery_id' => 1,
                'title' => 'Setting Up Your First License',
                'slug' => 'first-license',
                'description' => 'Step-by-step guide to generating and managing your first license.',
                'embed_url' => 'https://www.youtube.com/embed/abc123def45',
                'thumbnail' => '/thumbnails/first-license.jpg',
                'duration' => 480,
                'author_id' => 1,
                'status' => 'published',
                'featured' => false,
                'view_count' => 834,
                'sort_order' => 2,
            ],
            [
                'id' => 3,
                'gallery_id' => 1,
                'title' => 'API Integration Walkthrough',
                'slug' => 'api-walkthrough',
                'description' => 'Connect your application to LicensePro APIs in minutes.',
                'embed_url' => 'https://vimeo.com/98765432',
                'thumbnail' => '/thumbnails/api.jpg',
                'duration' => 600,
                'author_id' => 4,
                'status' => 'published',
                'featured' => false,
                'view_count' => 412,
                'sort_order' => 3,
            ],
            [
                'id' => 4,
                'gallery_id' => null,
                'title' => 'Security Best Practices',
                'slug' => 'security-best-practices',
                'description' => 'Protect your software from piracy and unauthorized use.',
                'embed_url' => 'https://www.youtube.com/embed/xyz789abc00',
                'thumbnail' => '/thumbnails/security.jpg',
                'duration' => 360,
                'author_id' => 1,
                'status' => 'published',
                'featured' => false,
                'view_count' => 2100,
                'sort_order' => 0,
            ],
        ]);

        DB::table('video_tags')->insert([
            ['video_id' => 1, 'tag_id' => 1],
            ['video_id' => 1, 'tag_id' => 2],
            ['video_id' => 2, 'tag_id' => 1],
            ['video_id' => 2, 'tag_id' => 3],
            ['video_id' => 3, 'tag_id' => 1],
            ['video_id' => 3, 'tag_id' => 3],
            ['video_id' => 4, 'tag_id' => 1],
            ['video_id' => 4, 'tag_id' => 2],
        ]);

        DB::table('video_guidelines')->insert([
            [
                'video_id' => 1,
                'step_order' => 1,
                'title' => 'Dashboard Overview',
                'description' => 'The main dashboard shows your license stats, recent activations, and revenue at a glance.',
                'time_marker' => 0,
                'image' => '/screenshots/dashboard.png',
            ],
            [
                'video_id' => 1,
                'step_order' => 2,
                'title' => 'Navigation Menu',
                'description' => 'Use the left sidebar to access Licenses, Products, Customers, and Settings.',
                'time_marker' => 30,
                'image' => '/screenshots/nav.png',
            ],
            [
                'video_id' => 1,
                'step_order' => 3,
                'title' => 'Quick Actions',
                'description' => 'The top bar provides quick actions for generating licenses and creating products.',
                'time_marker' => 120,
                'image' => '/screenshots/quick-actions.png',
            ],
            [
                'video_id' => 2,
                'step_order' => 1,
                'title' => 'License Key Format',
                'description' => 'License keys follow the format LIC-PRODUCT-YYYY-NNNN for easy identification.',
                'time_marker' => 0,
                'image' => null,
            ],
            [
                'video_id' => 2,
                'step_order' => 2,
                'title' => 'Activation Limits',
                'description' => 'Set max activations per license to control concurrent usage across devices.',
                'time_marker' => 180,
                'image' => null,
            ],
            [
                'video_id' => 4,
                'step_order' => 1,
                'title' => 'Hardware Locking',
                'description' => 'Enable hardware locking to bind licenses to specific machine fingerprints.',
                'time_marker' => 0,
                'image' => '/screenshots/hardware-lock.png',
            ],
            [
                'video_id' => 4,
                'step_order' => 2,
                'title' => 'Domain Verification',
                'description' => 'Configure domain whitelisting to restrict license usage to approved domains.',
                'time_marker' => 90,
                'image' => null,
            ],
        ]);
    }
}
