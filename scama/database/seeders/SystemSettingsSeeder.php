<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('system_settings')->insert([
            [
                'key' => 'app_name',
                'value' => 'LicensePro',
                'group' => 'general',
            ],
            [
                'key' => 'app_version',
                'value' => '2.1.0',
                'group' => 'general',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'group' => 'system',
            ],
            [
                'key' => 'default_language',
                'value' => 'en',
                'group' => 'localization',
            ],
        ]);

        DB::table('system_logs')->insert([
            [
                'level' => 'info',
                'message' => 'Application started successfully.',
                'context' => '{"uptime_seconds": 0}',
                'created_at' => '2026-05-30 00:00:00',
            ],
            [
                'level' => 'warning',
                'message' => 'High memory usage detected.',
                'context' => '{"memory_usage_mb": 4096}',
                'created_at' => '2026-05-30 12:00:00',
            ],
            [
                'level' => 'error',
                'message' => 'Database connection pool exhausted.',
                'context' => '{"pool_size": 10, "active": 10}',
                'created_at' => '2026-05-29 18:00:00',
            ],
        ]);

        DB::table('email_settings')->insert([
            [
                'smtp_host' => 'smtp.sendgrid.net',
                'smtp_port' => 587,
                'smtp_username' => 'apikey',
                'smtp_password' => 'SG.xxxxx',
                'from_email' => 'noreply@licensepro.com',
                'from_name' => 'LicensePro',
                'encryption' => 'tls',
                'is_default' => true,
            ],
            [
                'smtp_host' => 'smtp.mailgun.org',
                'smtp_port' => 587,
                'smtp_username' => 'postmaster@mg.licensepro.com',
                'smtp_password' => 'mg-xxxxx',
                'from_email' => 'noreply@licensepro.com',
                'from_name' => 'LicensePro (Backup)',
                'encryption' => 'tls',
                'is_default' => false,
            ],
        ]);

        DB::table('email_templates')->insert([
            [
                'name' => 'welcome_email',
                'subject' => 'Welcome to LicensePro!',
                'body' => '<h1>Welcome!</h1><p>Thank you for joining LicensePro.</p>',
            ],
            [
                'name' => 'license_activated',
                'subject' => 'License Activated Successfully',
                'body' => '<p>Your license {{license_key}} has been activated on {{domain}}.</p>',
            ],
            [
                'name' => 'password_reset',
                'subject' => 'Reset Your Password',
                'body' => '<p>Click <a href="{{link}}">here</a> to reset your password.</p>',
            ],
        ]);

        DB::table('payment_gateways')->insert([
            [
                'id' => 1,
                'name' => 'Stripe',
                'description' => 'Credit card payments via Stripe',
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => 'PayPal',
                'description' => 'PayPal payment processing',
                'is_active' => true,
            ],
        ]);

        DB::table('payment_gateway_settings')->insert([
            [
                'gateway_id' => 1,
                'key' => 'publishable_key',
                'value' => 'pk_live_stripe_xxxxx',
            ],
            [
                'gateway_id' => 1,
                'key' => 'secret_key',
                'value' => 'sk_live_stripe_xxxxx',
            ],
            [
                'gateway_id' => 2,
                'key' => 'client_id',
                'value' => 'paypal_client_xxxxx',
            ],
            [
                'gateway_id' => 2,
                'key' => 'client_secret',
                'value' => 'paypal_secret_xxxxx',
            ],
        ]);

        DB::table('webhooks')->insert([
            [
                'id' => 1,
                'name' => 'Slack Order Notifications',
                'url' => 'https://hooks.slack.com/services/T00/B00/xxxx',
                'events' => '["order.completed","order.refunded"]',
                'secret' => 'whsec_slack_xxxx',
                'is_active' => true,
                'last_triggered_at' => '2026-05-30 12:05:00',
            ],
            [
                'id' => 2,
                'name' => 'Discord License Alerts',
                'url' => 'https://discord.com/api/webhooks/123/xxx',
                'events' => '["license.activated","license.suspicious"]',
                'secret' => 'whsec_discord_xxx',
                'is_active' => true,
                'last_triggered_at' => '2026-05-30 10:00:00',
            ],
        ]);

        DB::table('feature_flags')->insert([
            [
                'name' => 'Dark Mode',
                'key' => 'dark_mode',
                'description' => 'Enable dark mode theme across the application',
                'enabled' => true,
                'conditions' => '{"percentage": 100}',
            ],
            [
                'name' => 'AI Chat Assistant',
                'key' => 'ai_chat_assistant',
                'description' => 'Enable AI-powered chat bot for support',
                'enabled' => true,
                'conditions' => '{"percentage": 50, "user_ids": [3,4]}',
            ],
            [
                'name' => 'New Checkout Flow',
                'key' => 'new_checkout_flow',
                'description' => 'Redirect users to the redesigned checkout experience',
                'enabled' => false,
                'conditions' => null,
            ],
        ]);
    }
}
