<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkflowActionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('approval_requests')->insert([
            [
                'workflow_run_id' => 2,
                'node_id' => 6,
                'status' => 'pending',
                'requested_by' => 2,
                'requested_at' => '2026-06-04 07:00:00',
                'responded_at' => null,
                'notes' => 'Order #1 requires approval due to high value',
            ],
            [
                'workflow_run_id' => 3,
                'node_id' => 6,
                'status' => 'approved',
                'requested_by' => 3,
                'requested_at' => '2026-06-04 05:00:00',
                'responded_at' => '2026-06-04 05:30:00',
                'notes' => 'Approved - standard order',
            ],
        ]);

        DB::table('approval_stages')->insert([
            ['approval_request_id' => 1, 'stage_order' => 1, 'status' => 'pending', 'strategy' => 'all', 'min_approvers' => 2],
            ['approval_request_id' => 2, 'stage_order' => 1, 'status' => 'approved', 'strategy' => 'any', 'min_approvers' => 1],
        ]);

        DB::table('approval_assignees')->insert([
            ['stage_id' => 1, 'user_id' => 1, 'status' => 'pending', 'response' => null, 'responded_at' => null],
            ['stage_id' => 1, 'user_id' => 4, 'status' => 'approved', 'response' => 'Looks good, proceed.', 'responded_at' => '2026-06-04 07:40:00'],
            ['stage_id' => 2, 'user_id' => 1, 'status' => 'approved', 'response' => 'Approved.', 'responded_at' => '2026-06-04 05:30:00'],
        ]);

        DB::table('email_automations')->insert([
            [
                'name' => 'Welcome Series',
                'trigger_event' => 'user.registered',
                'email_template_id' => 1,
                'conditions' => null,
                'audience_filter' => '{"role":"user"}',
                'sender_name' => 'Admin',
                'sender_email' => 'admin@example.com',
                'reply_to' => 'support@example.com',
                'status' => 'active',
                'created_by' => 1,
            ],
            [
                'name' => 'Abandoned Cart',
                'trigger_event' => 'cart.abandoned',
                'email_template_id' => 2,
                'conditions' => '{"field":"cart.total","operator":"greater_than","value":"50"}',
                'audience_filter' => '{"hours_since_update":24}',
                'sender_name' => 'Sales',
                'sender_email' => 'sales@example.com',
                'reply_to' => 'support@example.com',
                'status' => 'draft',
                'created_by' => 2,
            ],
        ]);

        DB::table('scheduled_tasks')->insert([
            [
                'name' => 'Daily Stats Refresh',
                'description' => 'Refresh seller statistics every morning',
                'cron_expression' => '0 2 * * *',
                'task_type' => 'run_workflow',
                'config' => '{"workflow_id":1}',
                'status' => 'active',
                'last_run_at' => null,
                'next_run_at' => '2026-06-05 02:00:00',
                'is_system' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Weekly Report',
                'description' => 'Generate and email weekly sales report',
                'cron_expression' => '0 8 * * 1',
                'task_type' => 'send_report',
                'config' => '{"report_type":"sales","recipients":["admin@example.com"]}',
                'status' => 'active',
                'last_run_at' => null,
                'next_run_at' => '2026-06-08 08:00:00',
                'is_system' => false,
                'created_by' => 2,
            ],
            [
                'name' => 'Hourly Cleanup',
                'description' => 'Cleanup old event logs',
                'cron_expression' => '0 * * * *',
                'task_type' => 'run_sql',
                'config' => '{"query":"DELETE FROM event_log WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)"}',
                'status' => 'paused',
                'last_run_at' => null,
                'next_run_at' => null,
                'is_system' => true,
                'created_by' => 1,
            ],
        ]);

        DB::table('webhook_delivery_logs')->insert([
            [
                'webhook_id' => 1,
                'event_type' => 'order.placed',
                'payload' => '{"order_id":1,"event":"order.placed"}',
                'request_headers' => '{"Content-Type":"application/json","X-Signature":"sha256=abc123"}',
                'response_status' => 200,
                'response_body' => '{"received":true}',
                'attempt' => 1,
                'success' => true,
                'error_message' => null,
                'delivered_at' => '2026-06-04 07:00:00',
                'next_retry_at' => null,
            ],
            [
                'webhook_id' => 1,
                'event_type' => 'order.placed',
                'payload' => '{"order_id":2,"event":"order.placed"}',
                'request_headers' => '{"Content-Type":"application/json","X-Signature":"sha256=def456"}',
                'response_status' => 502,
                'response_body' => 'Bad Gateway',
                'attempt' => 1,
                'success' => false,
                'error_message' => 'HTTP 502 Bad Gateway',
                'delivered_at' => '2026-06-04 06:30:00',
                'next_retry_at' => '2026-06-04 06:35:00',
            ],
        ]);
    }
}
