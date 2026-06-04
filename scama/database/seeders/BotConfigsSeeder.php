<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BotConfigsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bot_configs')->insert([
            [
                'id' => 1,
                'name' => 'Support Telegram Bot',
                'platform' => 'telegram',
                'platform_token' => '789012:ABC-DEF1234ghIkl-zyx57W2v1u123ew11',
                'platform_username' => 'LicenseProBot',
                'webhook_url' => 'https://api.licensepro.com/webhook/telegram',
                'llm_provider_id' => 1,
                'llm_system_prompt' => 'You are a helpful support assistant for LicensePro platform.',
                'welcome_message' => 'Hello! Welcome to LicensePro support. How can I help you today?',
                'status' => 'active',
                'allowed_user_ids' => '[123456789, 987654321]',
                'rate_limit_per_minute' => 30,
                'max_conversation_length' => 50,
                'settings' => '{"parse_mode": "Markdown", "enable_typing": true}',
            ],
            [
                'id' => 2,
                'name' => 'Announcement Discord Bot',
                'platform' => 'discord',
                'platform_token' => 'MTIzNDU2Nzg5MDEyMzQ1Njc4.GhIjKl.MnOpQrStUvWxYz',
                'platform_username' => 'LicensePro Announce',
                'webhook_url' => 'https://api.licensepro.com/webhook/discord',
                'llm_provider_id' => 1,
                'llm_system_prompt' => 'You are an announcement bot for LicensePro. Provide concise product updates.',
                'welcome_message' => 'Welcome to LicensePro announcements!',
                'status' => 'inactive',
                'allowed_user_ids' => null,
                'rate_limit_per_minute' => 10,
                'max_conversation_length' => 20,
                'settings' => '{"presence": "online", "activity_type": "playing"}',
            ],
            [
                'id' => 3,
                'name' => 'Sales WhatsApp Bot',
                'platform' => 'whatsapp',
                'platform_token' => 'whatsapp:sk_live_xxxxxxxxxxxx',
                'platform_username' => 'LicensePro Sales',
                'webhook_url' => null,
                'llm_provider_id' => 2,
                'llm_system_prompt' => 'You are a sales assistant helping customers choose the right licensing plan.',
                'welcome_message' => 'Hi! Interested in LicensePro? Let me help you find the perfect plan.',
                'status' => 'active',
                'allowed_user_ids' => null,
                'rate_limit_per_minute' => 20,
                'max_conversation_length' => 30,
                'settings' => '{"business_hours_only": true}',
            ],
        ]);
    }
}
