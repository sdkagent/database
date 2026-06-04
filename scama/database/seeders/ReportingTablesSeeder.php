<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportingTablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('report_categories')->insert([
            ['id' => 1, 'name' => 'Sales Reports', 'slug' => 'sales-reports', 'description' => 'Revenue and order analytics', 'parent_id' => null, 'sort_order' => 1],
            ['id' => 2, 'name' => 'User Analytics', 'slug' => 'user-analytics', 'description' => 'User behavior and engagement', 'parent_id' => null, 'sort_order' => 2],
        ]);

        DB::table('report_definitions')->insert([
            [
                'id' => 1,
                'name' => 'Daily Revenue Summary',
                'slug' => 'daily-revenue-summary',
                'description' => 'Daily revenue breakdown by product',
                'category_id' => 1,
                'report_type' => 'tabular',
                'config' => '{"columns":["date","product","revenue","orders"],"filters":{"date_range":"today"}}',
                'is_system' => true,
                'created_by' => 1,
            ],
            [
                'id' => 2,
                'name' => 'User Growth Chart',
                'slug' => 'user-growth-chart',
                'description' => 'Monthly user registration trends',
                'category_id' => 2,
                'report_type' => 'chart',
                'config' => '{"type":"line","metrics":["registrations","activations"],"group_by":"month"}',
                'is_system' => true,
                'created_by' => 1,
            ],
        ]);

        DB::table('report_schedules')->insert([
            [
                'report_id' => 1,
                'name' => 'Daily Revenue',
                'cron_expression' => '0 8 * * *',
                'recipients' => '["admin@example.com"]',
                'format' => 'pdf',
                'config' => '{"time":"08:00","timezone":"UTC"}',
                'last_run_at' => null,
                'next_run_at' => '2026-06-05 08:00:00',
                'status' => 'active',
                'created_by' => 1,
            ],
            [
                'report_id' => 2,
                'name' => 'Weekly Summary',
                'cron_expression' => '0 9 * * 1',
                'recipients' => '["admin@example.com","sales@example.com"]',
                'format' => 'csv',
                'config' => '{"day":"monday","time":"09:00"}',
                'last_run_at' => null,
                'next_run_at' => '2026-06-08 09:00:00',
                'status' => 'active',
                'created_by' => 1,
            ],
        ]);

        DB::table('dashboard_widgets')->insert([
            [
                'user_id' => 1,
                'dashboard_name' => 'default',
                'widget_type' => 'kpi',
                'title' => 'Revenue KPI',
                'config' => '{"metric":"total_revenue","label":"Revenue","prefix":"$","format":"number"}',
                'position_x' => 0,
                'position_y' => 0,
                'width' => 3,
                'height' => 1,
            ],
            [
                'user_id' => 1,
                'dashboard_name' => 'default',
                'widget_type' => 'chart',
                'title' => 'Order Timeline',
                'config' => '{"type":"bar","dataset":"daily_orders","period":"7d","stacked":false}',
                'position_x' => 0,
                'position_y' => 1,
                'width' => 4,
                'height' => 2,
            ],
        ]);
    }
}
