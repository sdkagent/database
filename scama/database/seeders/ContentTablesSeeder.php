<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentTablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('application_updates')->insert([
            [
                'version' => '1.0.0',
                'type' => 'update',
                'changelog' => 'Initial release of the application.',
                'created_at' => '2026-01-01 00:00:00',
            ],
            [
                'version' => '2.0.0',
                'type' => 'update',
                'changelog' => 'Major update: New licensing engine, performance improvements.',
                'created_at' => '2026-03-15 00:00:00',
            ],
            [
                'version' => '1.5.0',
                'type' => 'version_history',
                'changelog' => 'Archived version notes from legacy system.',
                'created_at' => '2026-02-01 00:00:00',
            ],
        ]);

        DB::table('release_notes')->insert([
            [
                'version' => '2.0.0',
                'notes' => 'New licensing engine, improved hardware locking, security patches.',
                'created_at' => '2026-03-15 10:00:00',
            ],
            [
                'version' => '2.1.0',
                'notes' => 'Added desktop app support, fixed domain verification bug.',
                'created_at' => '2026-04-20 10:00:00',
            ],
        ]);

        DB::table('user_guides')->insert([
            [
                'author_id' => 4,
                'title' => 'Getting Started with Licensing',
                'slug' => 'getting-started',
                'content' => '<p>Step-by-step guide to integrate licensing into your app.</p>',
                'status' => 'published',
                'created_at' => '2026-02-01 10:00:00',
            ],
            [
                'author_id' => 4,
                'title' => 'API Integration Guide',
                'slug' => 'api-guide',
                'content' => '<p>How to use the LicensePro API for automated license management.</p>',
                'status' => 'published',
                'created_at' => '2026-03-01 10:00:00',
            ],
        ]);

        DB::table('announcements')->insert([
            [
                'title' => 'Scheduled Maintenance',
                'content' => 'The platform will be down for maintenance on June 1st from 2-4 AM EST.',
                'is_active' => true,
                'created_at' => '2026-05-25 10:00:00',
            ],
            [
                'title' => 'New Pricing Tiers',
                'content' => 'We are introducing new Enterprise plans with white-label support.',
                'is_active' => true,
                'created_at' => '2026-05-15 10:00:00',
            ],
        ]);

        DB::table('knowledge_base_articles')->insert([
            [
                'title' => 'How to generate a license key',
                'slug' => 'generate-license-key',
                'content' => '<p>Navigate to Licenses &gt; Generate Key...</p>',
                'category' => 'Licensing',
                'status' => 'published',
                'created_at' => '2026-01-10 10:00:00',
            ],
            [
                'title' => 'Troubleshooting activation failures',
                'slug' => 'troubleshoot-activation',
                'content' => '<p>If activation fails, check your hardware ID format.</p>',
                'category' => 'Troubleshooting',
                'status' => 'published',
                'created_at' => '2026-02-20 10:00:00',
            ],
        ]);

        DB::table('faq_items')->insert([
            [
                'question' => 'What happens if I exceed my activation limit?',
                'slug' => 'exceed-activation-limit',
                'answer' => 'Your oldest activation will be automatically deactivated.',
                'category' => 'Licensing',
                'position' => 1,
                'status' => 'published',
                'created_at' => '2026-01-01 10:00:00',
            ],
            [
                'question' => 'How do I cancel my subscription?',
                'slug' => 'cancel-subscription',
                'answer' => 'Go to Settings > Billing and click Cancel Subscription.',
                'category' => 'Billing',
                'position' => 2,
                'status' => 'published',
                'created_at' => '2026-01-01 10:00:00',
            ],
            [
                'question' => 'Can I transfer my license to another domain?',
                'slug' => 'transfer-license-domain',
                'answer' => 'Yes, from the Dashboard > Licenses > Transfer.',
                'category' => 'Licensing',
                'position' => 3,
                'status' => 'published',
                'created_at' => '2026-01-01 10:00:00',
            ],
        ]);
    }
}
