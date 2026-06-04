<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemApiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rate_limit_rules')->insert([
            [
                'name' => 'API General',
                'route_pattern' => '/api/*',
                'http_method' => '*',
                'max_requests' => 60,
                'window_seconds' => 60,
                'response_code' => 429,
                'response_message' => 'Too many requests. Please slow down.',
                'is_active' => true,
            ],
            [
                'name' => 'Auth Endpoint',
                'route_pattern' => '/api/auth/*',
                'http_method' => 'POST',
                'max_requests' => 5,
                'window_seconds' => 60,
                'response_code' => 429,
                'response_message' => 'Too many auth attempts. Try again later.',
                'is_active' => true,
            ],
        ]);

        DB::table('rate_limit_logs')->insert([
            [
                'rule_id' => 1,
                'user_id' => 1,
                'ip_address' => '192.168.1.100',
                'route' => '/api/products',
                'http_method' => 'GET',
                'identifier' => 'user_1',
                'hit_at' => '2026-06-04 06:55:00',
            ],
            [
                'rule_id' => 2,
                'user_id' => null,
                'ip_address' => '10.0.0.99',
                'route' => '/api/auth/login',
                'http_method' => 'POST',
                'identifier' => '10.0.0.99',
                'hit_at' => '2026-06-04 06:58:00',
            ],
        ]);

        DB::table('health_checks')->insert([
            [
                'check_type' => 'database',
                'status' => 'pass',
                'response_time_ms' => 12,
                'message' => 'Database Connectivity',
            ],
            [
                'check_type' => 'api',
                'status' => 'pass',
                'response_time_ms' => 45,
                'message' => 'API Health Endpoint',
            ],
        ]);

        DB::table('maintenance_windows')->insert([
            [
                'title' => 'Database Migration v2.1',
                'description' => 'Upgrade database server to version 2.1',
                'status' => 'scheduled',
                'starts_at' => '2026-06-11 08:00:00',
                'ends_at' => '2026-06-11 10:00:00',
                'created_by' => 1,
            ],
            [
                'title' => 'SSL Certificate Renewal',
                'description' => 'Renew expiring SSL certificate for CDN',
                'status' => 'completed',
                'starts_at' => '2026-05-21 08:00:00',
                'ends_at' => '2026-05-21 08:30:00',
                'created_by' => 1,
            ],
        ]);
    }
}
