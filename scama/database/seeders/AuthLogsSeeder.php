<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthLogsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('auth_logs')->insert([
            [
                'user_id' => 3,
                'type' => 'login',
                'ip_address' => '192.168.1.100',
                'device_fingerprint' => 'fp-chrome-win10-a1b2c3',
                'status' => 'success',
                'details' => json_encode(['method' => 'password']),
                'created_at' => '2026-05-30 08:00:00',
            ],
            [
                'user_id' => 3,
                'type' => 'failed_login',
                'ip_address' => '10.0.0.55',
                'device_fingerprint' => null,
                'status' => 'failed',
                'details' => json_encode(['attempts' => 1, 'reason' => 'wrong_password']),
                'created_at' => '2026-05-30 08:05:00',
            ],
        ]);
    }
}
