<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModerationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('moderation_queue')->insert([
            [
                'content_type' => 'product_review',
                'content_id' => 1,
                'reported_by' => 2,
                'status' => 'pending',
                'priority' => 'normal',
                'assigned_to' => null,
                'notes' => 'Inappropriate language in product review',
            ],
            [
                'content_type' => 'cms_page',
                'content_id' => 1,
                'reported_by' => 1,
                'status' => 'pending',
                'priority' => 'normal',
                'assigned_to' => null,
                'notes' => 'Suspected spam content in CMS page body',
            ],
        ]);

        DB::table('moderation_reports')->insert([
            [
                'queue_item_id' => 1,
                'reporter_id' => 2,
                'reason_category' => 'abuse',
                'description' => 'Review contains hate speech',
            ],
            [
                'queue_item_id' => 2,
                'reporter_id' => 1,
                'reason_category' => 'spam',
                'description' => 'CMS page content appears to be spam with multiple external links',
            ],
        ]);

        DB::table('moderation_actions')->insert([
            [
                'queue_item_id' => 1,
                'moderator_id' => 1,
                'action' => 'warned',
                'reason' => 'First offense - verbal warning issued',
            ],
            [
                'queue_item_id' => 2,
                'moderator_id' => 1,
                'action' => 'hidden',
                'reason' => 'Content hidden pending further investigation',
            ],
        ]);

        DB::table('moderation_blocklist')->insert([
            [
                'block_type' => 'email',
                'value' => 'spammer@example.com',
                'reason' => 'Known spammer from previous campaigns',
                'created_by' => 1,
                'expires_at' => null,
            ],
            [
                'block_type' => 'keyword',
                'value' => 'casino',
                'reason' => 'Gambling-related content not permitted',
                'created_by' => 1,
                'expires_at' => null,
            ],
        ]);
    }
}
