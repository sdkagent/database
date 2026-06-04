<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VerificationSecuritySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('verification_logs')->insert([
            [
                'activation_id' => 1,
                'license_key' => 'LIC-PRO-2026-8888',
                'api_key' => 'license-api-key-1111-2222-3333',
                'ip_address' => '103.231.222.15',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/91',
                'request_domain' => 'crm.johnsbusiness.com',
                'request_ip' => '103.231.222.15',
                'tier1_api' => 'pass',
                'tier2_license' => 'pass',
                'tier3_domain' => 'pass',
                'tier4_ip' => 'pass',
                'tier5_subscription' => 'pass',
                'overall_result' => 'valid',
                'created_at' => '2026-05-30 10:00:00',
            ],
            [
                'activation_id' => 2,
                'license_key' => 'LIC-PRO-2026-8888',
                'api_key' => 'license-api-key-1111-2222-3333',
                'ip_address' => '45.12.55.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Firefox/115',
                'request_domain' => 'unknown-hack.com',
                'request_ip' => '45.12.55.1',
                'tier1_api' => 'pass',
                'tier2_license' => 'pass',
                'tier3_domain' => 'fail',
                'tier4_ip' => 'fail',
                'tier5_subscription' => 'pass',
                'overall_result' => 'suspicious',
                'created_at' => '2026-05-30 11:00:00',
            ],
        ]);

        DB::table('fraud_logs')->insert([
            [
                'license_id' => 1,
                'activation_id' => 2,
                'ip' => '45.12.55.1',
                'domain' => 'unknown-hack.com',
                'reason' => 'Domain mismatch with valid key. Possible phishing.',
                'severity' => 'medium',
                'action_taken' => 'logged',
                'created_at' => '2026-05-30 11:00:00',
            ],
            [
                'license_id' => 3,
                'activation_id' => null,
                'ip' => '88.77.66.55',
                'domain' => 'blackhat-forum.ru',
                'reason' => 'Stolen license key circulating on dark web forums.',
                'severity' => 'critical',
                'action_taken' => 'revoked',
                'created_at' => '2026-05-28 00:00:00',
            ],
        ]);

        DB::table('user_2fa')->insert([
            [
                'user_id' => 1,
                'secret' => 'JBSWY3DPEHPK3PXP',
                'method' => 'totp',
                'backup_codes' => json_encode(['ABCD-1234-EFGH-5678', 'IJKL-9012-MNOP-3456', 'QRST-7890-UVWX-1234', 'YZ12-3456-7890-ABCD']),
                'is_enabled' => true,
                'verified_at' => '2026-01-16 08:00:00',
            ],
            [
                'user_id' => 3,
                'secret' => null,
                'method' => 'email',
                'backup_codes' => null,
                'is_enabled' => false,
                'verified_at' => null,
            ],
        ]);
    }
}
