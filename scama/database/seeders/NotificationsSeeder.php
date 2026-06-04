<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('api_request_logs')->insert([
            [
                'api_client_id' => 1,
                'order_id' => null,
                'endpoint' => '/api/v1/licenses/verify',
                'method' => 'POST',
                'request_data' => json_encode(['license_key' => 'LIC-PRO-2026-8888', 'domain' => 'crm.johnsbusiness.com']),
                'response_data' => json_encode(['status' => 'valid', 'expires_at' => '2027-05-01']),
                'status_code' => 200,
                'ip_address' => '103.231.222.15',
                'user_agent' => 'LicensePro-Client/2.0',
                'created_at' => '2026-05-30 10:00:00',
            ],
            [
                'api_client_id' => 1,
                'order_id' => 1,
                'endpoint' => '/api/v1/orders/1',
                'method' => 'GET',
                'request_data' => json_encode([]),
                'response_data' => json_encode(['order_number' => 'ORD-2026-00001', 'status' => 'completed']),
                'status_code' => 200,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Dashboard/1.0',
                'created_at' => '2026-05-28 15:30:00',
            ],
            [
                'api_client_id' => 2,
                'order_id' => null,
                'endpoint' => '/api/v1/licenses/validate',
                'method' => 'POST',
                'request_data' => json_encode(['license_key' => 'LIC-SAAS-2026-7777']),
                'response_data' => json_encode(['status' => 'suspended', 'reason' => 'payment_past_due']),
                'status_code' => 403,
                'ip_address' => '203.157.123.46',
                'user_agent' => 'LicensePro-Client/2.0',
                'created_at' => '2026-05-30 12:00:00',
            ],
        ]);

        DB::table('notifications')->insert([
            [
                'user_id' => 3,
                'type' => 'system',
                'title' => 'Welcome to LicensePro',
                'message' => 'Welcome! Your account has been created successfully.',
                'data' => '{"onboarding": true}',
                'is_read' => true,
                'read_at' => '2026-03-10 14:05:00',
                'channel' => 'in_app',
                'action_url' => '/dashboard',
                'action_text' => 'Go to Dashboard',
            ],
            [
                'user_id' => 3,
                'type' => 'subscription',
                'title' => 'Subscription Renewed',
                'message' => 'Your Professional plan has been renewed successfully.',
                'data' => '{"plan": "professional"}',
                'is_read' => false,
                'read_at' => null,
                'channel' => 'email',
                'action_url' => '/billing/subscriptions',
                'action_text' => 'View Subscription',
            ],
            [
                'user_id' => 2,
                'type' => 'product',
                'title' => 'New Sale!',
                'message' => 'Your product "Ultimate CRM Script" was just purchased.',
                'data' => '{"sale_amount": 59.00}',
                'is_read' => false,
                'read_at' => null,
                'channel' => 'in_app',
                'action_url' => '/seller/products',
                'action_text' => 'View Product',
            ],
        ]);
    }
}
