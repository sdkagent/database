<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkflowCoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('workflow_definitions')->insert([
            [
                'name' => 'Welcome Email Series',
                'slug' => 'welcome-email-series',
                'description' => 'Send a welcome email sequence when a new user registers',
                'category' => 'Onboarding',
                'status' => 'active',
                'version' => 1,
                'config' => '{"timeout": 3600}',
                'is_system' => true,
                'created_by' => 1,
            ],
            [
                'name' => 'Order Fulfillment',
                'slug' => 'order-fulfillment',
                'description' => 'Automated order processing and fulfillment workflow',
                'category' => 'Commerce',
                'status' => 'draft',
                'version' => 1,
                'config' => null,
                'is_system' => false,
                'created_by' => 2,
            ],
        ]);

        DB::table('workflow_nodes')->insert([
            ['workflow_id' => 1, 'type' => 'trigger', 'name' => 'User Registered', 'description' => 'Triggers on user.registered event', 'config' => '{"event_type":"user.registered"}', 'position_x' => 0, 'position_y' => 0, 'timeout_seconds' => null, 'retry_count' => 0, 'retry_delay' => 0],
            ['workflow_id' => 1, 'type' => 'action', 'name' => 'Send Welcome Email', 'description' => 'Sends the welcome email template', 'config' => '{"template_id":1,"delay_minutes":0}', 'position_x' => 200, 'position_y' => 0, 'timeout_seconds' => 30, 'retry_count' => 3, 'retry_delay' => 60],
            ['workflow_id' => 1, 'type' => 'end', 'name' => 'Complete', 'description' => 'Workflow complete', 'config' => null, 'position_x' => 400, 'position_y' => 0, 'timeout_seconds' => null, 'retry_count' => 0, 'retry_delay' => 0],
            ['workflow_id' => 2, 'type' => 'trigger', 'name' => 'Order Placed', 'description' => 'Triggers on order.placed event', 'config' => '{"event_type":"order.placed"}', 'position_x' => 0, 'position_y' => 200, 'timeout_seconds' => null, 'retry_count' => 0, 'retry_delay' => 0],
            ['workflow_id' => 2, 'type' => 'action', 'name' => 'Process Payment', 'description' => 'Process payment via selected gateway', 'config' => '{"gateway":"stripe","capture":true}', 'position_x' => 200, 'position_y' => 200, 'timeout_seconds' => 120, 'retry_count' => 3, 'retry_delay' => 30],
            ['workflow_id' => 2, 'type' => 'approval', 'name' => 'Review Order', 'description' => 'Manual review for high-value orders', 'config' => '{"min_amount":500}', 'position_x' => 400, 'position_y' => 200, 'timeout_seconds' => 86400, 'retry_count' => 0, 'retry_delay' => 0],
            ['workflow_id' => 2, 'type' => 'action', 'name' => 'Send Confirmation', 'description' => 'Send order confirmation to customer', 'config' => '{"template_id":2}', 'position_x' => 600, 'position_y' => 200, 'timeout_seconds' => 30, 'retry_count' => 2, 'retry_delay' => 30],
            ['workflow_id' => 2, 'type' => 'end', 'name' => 'Complete', 'description' => 'Order fulfillment complete', 'config' => null, 'position_x' => 800, 'position_y' => 200, 'timeout_seconds' => null, 'retry_count' => 0, 'retry_delay' => 0],
        ]);

        DB::table('workflow_transitions')->insert([
            ['workflow_id' => 1, 'from_node_id' => 1, 'to_node_id' => 2, 'condition_expression' => null, 'label' => 'on_register', 'priority' => 0],
            ['workflow_id' => 1, 'from_node_id' => 2, 'to_node_id' => 3, 'condition_expression' => null, 'label' => 'email_sent', 'priority' => 0],
            ['workflow_id' => 2, 'from_node_id' => 4, 'to_node_id' => 5, 'condition_expression' => null, 'label' => 'order_placed', 'priority' => 0],
            ['workflow_id' => 2, 'from_node_id' => 5, 'to_node_id' => 6, 'condition_expression' => '{"field":"order.total","operator":"greater_than","value":500}', 'label' => 'high_value', 'priority' => 0],
            ['workflow_id' => 2, 'from_node_id' => 5, 'to_node_id' => 7, 'condition_expression' => '{"field":"order.total","operator":"less_or_equal","value":500}', 'label' => 'standard', 'priority' => 0],
            ['workflow_id' => 2, 'from_node_id' => 6, 'to_node_id' => 7, 'condition_expression' => null, 'label' => 'approved', 'priority' => 0],
            ['workflow_id' => 2, 'from_node_id' => 7, 'to_node_id' => 8, 'condition_expression' => null, 'label' => 'confirmation_sent', 'priority' => 0],
        ]);

        DB::table('workflow_runs')->insert([
            ['workflow_id' => 1, 'triggered_by' => 3, 'trigger_type' => 'user.registered', 'trigger_payload' => '{"user_id":3,"email":"jane@example.com"}', 'status' => 'completed', 'current_node_id' => 3, 'started_at' => '2026-06-02 14:00:00', 'completed_at' => '2026-06-02 14:05:00'],
            ['workflow_id' => 2, 'triggered_by' => 2, 'trigger_type' => 'order.placed', 'trigger_payload' => '{"order_id":1,"user_id":3,"total":328.90,"currency":"USD"}', 'status' => 'running', 'current_node_id' => 6, 'started_at' => '2026-06-04 07:00:00', 'completed_at' => null],
            ['workflow_id' => 2, 'triggered_by' => null, 'trigger_type' => 'order.placed', 'trigger_payload' => '{"order_id":2,"user_id":3,"total":9.99,"currency":"USD"}', 'status' => 'completed', 'current_node_id' => 8, 'started_at' => '2026-06-04 05:00:00', 'completed_at' => '2026-06-04 05:10:00'],
        ]);

        DB::table('workflow_run_logs')->insert([
            ['run_id' => 1, 'node_id' => 1, 'action_type' => 'trigger', 'level' => 'info', 'message' => 'Trigger matched: user.registered', 'payload' => '{"user_id":3}'],
            ['run_id' => 1, 'node_id' => 2, 'action_type' => 'action', 'level' => 'info', 'message' => 'Welcome email sent successfully', 'payload' => '{"template_id":1,"email":"jane@example.com","status":"delivered"}'],
            ['run_id' => 1, 'node_id' => 3, 'action_type' => 'end', 'level' => 'info', 'message' => 'Workflow completed', 'payload' => '{"duration_seconds":300,"nodes_executed":3}'],
            ['run_id' => 2, 'node_id' => 4, 'action_type' => 'trigger', 'level' => 'info', 'message' => 'Trigger matched: order.placed', 'payload' => '{"order_id":1}'],
            ['run_id' => 2, 'node_id' => 5, 'action_type' => 'action', 'level' => 'warn', 'message' => 'Payment processing initiated', 'payload' => '{"gateway":"stripe","amount":328.90}'],
            ['run_id' => 2, 'node_id' => 6, 'action_type' => 'approval', 'level' => 'info', 'message' => 'Awaiting manual approval', 'payload' => '{"threshold_exceeded":true,"amount":328.90}'],
            ['run_id' => 3, 'node_id' => 4, 'action_type' => 'trigger', 'level' => 'info', 'message' => 'Trigger matched: order.placed', 'payload' => '{"order_id":2}'],
            ['run_id' => 3, 'node_id' => 5, 'action_type' => 'action', 'level' => 'info', 'message' => 'Payment processed successfully', 'payload' => '{"gateway":"stripe","charge_id":"ch_abc123"}'],
            ['run_id' => 3, 'node_id' => 7, 'action_type' => 'action', 'level' => 'info', 'message' => 'Confirmation email sent', 'payload' => '{"template_id":2,"email":"jane@example.com"}'],
            ['run_id' => 3, 'node_id' => 8, 'action_type' => 'end', 'level' => 'info', 'message' => 'Workflow completed', 'payload' => '{"duration_seconds":600,"nodes_executed":4}'],
        ]);

        DB::table('workflow_run_node_states')->insert([
            ['run_id' => 1, 'node_id' => 1, 'status' => 'completed', 'input' => '{"event_type":"user.registered","user_id":3}', 'output' => '{"matched":true}', 'attempts' => 1, 'started_at' => '2026-06-02 14:00:00', 'completed_at' => '2026-06-02 14:00:01'],
            ['run_id' => 1, 'node_id' => 2, 'status' => 'completed', 'input' => '{"user_id":3,"email":"jane@example.com"}', 'output' => '{"delivery_id":"dlv_001","status":"sent"}', 'attempts' => 1, 'started_at' => '2026-06-02 14:01:00', 'completed_at' => '2026-06-02 14:02:00'],
            ['run_id' => 1, 'node_id' => 3, 'status' => 'completed', 'input' => '{"duration_seconds":300}', 'output' => '{"completed":true}', 'attempts' => 1, 'started_at' => '2026-06-02 14:02:00', 'completed_at' => '2026-06-02 14:02:00'],
            ['run_id' => 2, 'node_id' => 4, 'status' => 'completed', 'input' => '{"event_type":"order.placed","order_id":1}', 'output' => '{"matched":true}', 'attempts' => 1, 'started_at' => '2026-06-04 07:00:00', 'completed_at' => '2026-06-04 07:00:01'],
            ['run_id' => 2, 'node_id' => 5, 'status' => 'completed', 'input' => '{"order_id":1,"total":328.90}', 'output' => '{"charge_id":"ch_xyz789","status":"pending"}', 'attempts' => 1, 'started_at' => '2026-06-04 07:00:10', 'completed_at' => '2026-06-04 07:00:30'],
            ['run_id' => 2, 'node_id' => 6, 'status' => 'running', 'input' => '{"order_id":1,"total":328.90,"requires_approval":true}', 'output' => null, 'attempts' => 0, 'started_at' => '2026-06-04 07:00:30', 'completed_at' => null],
            ['run_id' => 3, 'node_id' => 4, 'status' => 'completed', 'input' => '{"event_type":"order.placed","order_id":2}', 'output' => '{"matched":true}', 'attempts' => 1, 'started_at' => '2026-06-04 05:00:00', 'completed_at' => '2026-06-04 05:00:01'],
            ['run_id' => 3, 'node_id' => 5, 'status' => 'completed', 'input' => '{"order_id":2,"total":59.00}', 'output' => '{"charge_id":"ch_abc123","status":"captured"}', 'attempts' => 1, 'started_at' => '2026-06-04 05:00:05', 'completed_at' => '2026-06-04 05:00:15'],
            ['run_id' => 3, 'node_id' => 7, 'status' => 'completed', 'input' => '{"order_id":2,"email":"jane@example.com"}', 'output' => '{"delivery_id":"dlv_002"}', 'attempts' => 1, 'started_at' => '2026-06-04 05:00:20', 'completed_at' => '2026-06-04 05:00:25'],
            ['run_id' => 3, 'node_id' => 8, 'status' => 'completed', 'input' => '{"duration_seconds":600}', 'output' => '{"completed":true}', 'attempts' => 1, 'started_at' => '2026-06-04 05:00:25', 'completed_at' => '2026-06-04 05:00:25'],
        ]);

        DB::table('workflow_run_variables')->insert([
            ['run_id' => 1, 'name' => 'welcome_email_sent', 'value' => 'true'],
            ['run_id' => 1, 'name' => 'user_email', 'value' => '"jane@example.com"'],
            ['run_id' => 2, 'name' => 'payment_charge_id', 'value' => '"ch_xyz789"'],
            ['run_id' => 2, 'name' => 'requires_approval', 'value' => 'true'],
            ['run_id' => 3, 'name' => 'payment_charge_id', 'value' => '"ch_abc123"'],
            ['run_id' => 3, 'name' => 'order_total', 'value' => '59.00'],
        ]);
    }
}
