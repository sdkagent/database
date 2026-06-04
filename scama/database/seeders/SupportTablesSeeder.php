<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportTablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tickets')->insert([
            [
                'user_id' => 3,
                'subject' => 'Cannot install CRM on subdomain',
                'status' => 'open',
                'priority' => 'high',
                'assigned_to' => 4,
                'category' => 'installation',
                'closed_at' => null,
                'created_at' => '2026-05-29 15:00:00',
            ],
            [
                'user_id' => 3,
                'subject' => 'How to upgrade to Enterprise plan?',
                'status' => 'closed',
                'priority' => 'low',
                'assigned_to' => 4,
                'category' => 'billing',
                'closed_at' => '2026-05-26 12:00:00',
                'created_at' => '2026-05-25 10:00:00',
            ],
            [
                'user_id' => 2,
                'subject' => 'Commission payout delayed',
                'status' => 'replied',
                'priority' => 'medium',
                'assigned_to' => 4,
                'category' => 'payouts',
                'closed_at' => null,
                'created_at' => '2026-05-28 09:00:00',
            ],
        ]);

        DB::table('ticket_messages')->insert([
            [
                'ticket_id' => 1,
                'sender_id' => 3,
                'message' => 'I tried installing on a subdomain but getting a 500 error. Help!',
                'attachments' => null,
                'created_at' => '2026-05-29 15:00:00',
            ],
            [
                'ticket_id' => 1,
                'sender_id' => 4,
                'message' => 'Please check the PHP version. This script requires PHP 8.1+. Your subdomain is running 7.4.',
                'attachments' => null,
                'created_at' => '2026-05-29 16:30:00',
            ],
            [
                'ticket_id' => 2,
                'sender_id' => 3,
                'message' => 'I\'d like to know the steps to upgrade from Professional to Enterprise.',
                'attachments' => null,
                'created_at' => '2026-05-25 10:00:00',
            ],
        ]);

        DB::table('chat_sessions')->insert([
            [
                'user_id' => 3,
                'status' => 'closed',
                'assigned_to' => 4,
                'created_at' => '2026-05-30 09:00:00',
            ],
            [
                'user_id' => 2,
                'status' => 'open',
                'assigned_to' => 4,
                'created_at' => '2026-05-30 13:00:00',
            ],
        ]);

        DB::table('chat_messages')->insert([
            [
                'session_id' => 1,
                'user_id' => 3,
                'message' => 'Hi, I need help with activation.',
                'is_agent' => false,
                'created_at' => '2026-05-30 09:00:00',
            ],
            [
                'session_id' => 1,
                'user_id' => 4,
                'message' => 'Sure! Let me check your license status.',
                'is_agent' => true,
                'created_at' => '2026-05-30 09:01:00',
            ],
            [
                'session_id' => 2,
                'user_id' => 2,
                'message' => 'My payout is late by 3 days.',
                'is_agent' => false,
                'created_at' => '2026-05-30 13:00:00',
            ],
        ]);

        DB::table('bot_conversations')->insert([
            [
                'user_id' => 3,
                'bot_config_id' => 1,
                'message' => 'What is the capital of France?',
                'response' => 'The capital of France is Paris.',
                'created_at' => '2026-05-30 14:00:00',
            ],
            [
                'user_id' => 3,
                'bot_config_id' => 1,
                'message' => 'How do I reset my license key?',
                'response' => 'You can reset your license from the Dashboard > Licenses section.',
                'created_at' => '2026-05-30 14:05:00',
            ],
            [
                'user_id' => 2,
                'bot_config_id' => 3,
                'message' => 'Tell me about Enterprise pricing.',
                'response' => 'Enterprise plans start at $99.99/month with unlimited domains and dedicated support. Would you like a quote?',
                'created_at' => '2026-05-31 10:00:00',
            ],
        ]);
    }
}
