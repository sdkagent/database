<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrivacyGdprSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('consent_logs')->insert([
            [
                'user_id' => 1,
                'consent_type' => 'marketing',
                'purpose' => null,
                'granted' => true,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; rv:120.0) Gecko/20100101 Firefox/120.0',
            ],
            [
                'user_id' => 2,
                'consent_type' => 'functional',
                'purpose' => null,
                'granted' => false,
                'ip_address' => '10.0.0.50',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15',
            ],
        ]);

        DB::table('data_export_requests')->insert([
            [
                'user_id' => 1,
                'status' => 'completed',
                'format' => 'json',
                'completed_at' => '2026-06-04 08:00:00',
                'file_path' => '/exports/user_1_full_20260601.zip',
                'expires_at' => '2026-07-04 08:00:00',
            ],
            [
                'user_id' => 2,
                'status' => 'pending',
                'format' => 'csv',
                'completed_at' => null,
                'file_path' => null,
                'expires_at' => null,
            ],
        ]);

        DB::table('data_deletion_requests')->insert([
            [
                'user_id' => 2,
                'status' => 'pending',
                'reason' => 'User requested account anonymization',
                'processed_at' => null,
                'processed_by' => null,
                'notes' => null,
            ],
            [
                'user_id' => 1,
                'status' => 'approved',
                'reason' => 'Approved by support team',
                'processed_at' => null,
                'processed_by' => 1,
                'notes' => null,
            ],
        ]);

        DB::table('cookie_consent_settings')->insert([
            [
                'name' => 'Essential Cookies',
                'slug' => 'essential',
                'description' => 'Required for site functionality',
                'required' => true,
                'default_granted' => true,
            ],
            [
                'name' => 'Analytics Cookies',
                'slug' => 'analytics',
                'description' => 'Usage and performance tracking',
                'required' => false,
                'default_granted' => false,
            ],
        ]);
    }
}
