<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkflowTriggersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('triggers')->insert([
            [
                'name' => 'User Registered',
                'slug' => 'user-registered',
                'event_type' => 'user.registered',
                'description' => 'Fires when a new user account is created',
                'config' => '{"priority":"high"}',
                'status' => 'active',
            ],
            [
                'name' => 'Order Placed',
                'slug' => 'order-placed',
                'event_type' => 'order.placed',
                'description' => 'Fires when a customer places an order',
                'config' => '{"priority":"normal"}',
                'status' => 'active',
            ],
            [
                'name' => 'Payment Received',
                'slug' => 'payment-received',
                'event_type' => 'payment.received',
                'description' => 'Fires when a payment is successfully processed',
                'config' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Subscription Expired',
                'slug' => 'subscription-expired',
                'event_type' => 'subscription.expired',
                'description' => 'Fires when a user subscription expires',
                'config' => null,
                'status' => 'inactive',
            ],
        ]);

        DB::table('trigger_workflow_mappings')->insert([
            ['trigger_id' => 1, 'workflow_id' => 1, 'priority' => 100, 'conditions' => null, 'status' => 'active'],
            ['trigger_id' => 2, 'workflow_id' => 2, 'priority' => 100, 'conditions' => null, 'status' => 'active'],
            ['trigger_id' => 2, 'workflow_id' => 2, 'priority' => 50, 'conditions' => '{"field":"order.total","operator":"greater_than","value":"1000"}', 'status' => 'active'],
        ]);

        DB::table('event_log')->insert([
            ['event_type' => 'user.registered', 'source_type' => 'users', 'source_id' => 3, 'payload' => '{"user_id":3,"email":"jane@example.com"}', 'occurred_at' => '2026-06-02 14:00:00', 'processed_at' => '2026-06-02 14:00:01'],
            ['event_type' => 'order.placed', 'source_type' => 'orders', 'source_id' => 1, 'payload' => '{"order_id":1,"user_id":3,"total":328.90}', 'occurred_at' => '2026-06-04 07:00:00', 'processed_at' => '2026-06-04 07:00:01'],
            ['event_type' => 'order.placed', 'source_type' => 'orders', 'source_id' => 2, 'payload' => '{"order_id":2,"user_id":3,"total":9.99}', 'occurred_at' => '2026-06-04 05:00:00', 'processed_at' => '2026-06-04 05:00:01'],
            ['event_type' => 'payment.received', 'source_type' => 'payments', 'source_id' => 1, 'payload' => '{"payment_id":1,"order_id":1,"amount":328.90,"gateway":"stripe"}', 'occurred_at' => '2026-06-04 07:00:00', 'processed_at' => null],
            ['event_type' => 'subscription.expired', 'source_type' => 'user_subscriptions', 'source_id' => 1, 'payload' => '{"subscription_id":1,"user_id":3,"product_id":1}', 'occurred_at' => '2026-05-28 14:00:00', 'processed_at' => null],
        ]);

        DB::table('condition_groups')->insert([
            ['name' => 'High Value Order', 'operator' => 'AND'],
            ['name' => 'New Customer', 'operator' => 'OR'],
        ]);

        DB::table('condition_rules')->insert([
            ['group_id' => 1, 'field' => 'order.total', 'operator' => 'greater_than', 'value' => '500'],
            ['group_id' => 1, 'field' => 'user.account_age', 'operator' => 'less_than', 'value' => '30'],
            ['group_id' => 2, 'field' => 'order.count', 'operator' => 'equals', 'value' => '0'],
            ['group_id' => 2, 'field' => 'user.created_at', 'operator' => 'greater_than', 'value' => '"2026-05-01"'],
        ]);

        DB::table('condition_group_mappings')->insert([
            ['group_id' => 1, 'entity_type' => 'workflow_transition', 'entity_id' => 4],
            ['group_id' => 2, 'entity_type' => 'workflow_definition', 'entity_id' => 1],
        ]);
    }
}
