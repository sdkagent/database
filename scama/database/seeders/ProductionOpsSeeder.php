<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionOpsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('production_orders')->insert([
            [
                'product_id' => 3,
                'bom_id' => 1,
                'routing_id' => 1,
                'warehouse_id' => 1,
                'order_number' => 'MO-2026-0001',
                'quantity' => 50.00,
                'produced_qty' => 48.00,
                'scrap_qty' => 2.00,
                'status' => 'completed',
                'priority' => 'high',
                'scheduled_start' => '2026-05-01T08:00:00',
                'scheduled_end' => '2026-05-01T18:00:00',
                'actual_start' => '2026-05-01T08:00:00',
                'actual_end' => '2026-05-01T17:30:00',
                'notes' => 'Rush order for Desktop Inventory Manager',
                'created_by' => 1,
            ],
            [
                'product_id' => 1,
                'bom_id' => 2,
                'routing_id' => 2,
                'warehouse_id' => 1,
                'order_number' => 'MO-2026-0002',
                'quantity' => 100.00,
                'produced_qty' => 0.00,
                'scrap_qty' => 0.00,
                'status' => 'planned',
                'priority' => 'medium',
                'scheduled_start' => '2026-06-01T08:00:00',
                'scheduled_end' => '2026-06-01T17:00:00',
                'actual_start' => null,
                'actual_end' => null,
                'notes' => 'Scheduled CRM script packaging run',
                'created_by' => 1,
            ],
        ]);

        DB::table('production_order_steps')->insert([
            [
                'production_order_id' => 1,
                'routing_step_id' => 1,
                'work_center_id' => 1,
                'status' => 'completed',
                'actual_setup_time' => 14.00,
                'actual_run_time' => 44.00,
                'completed_qty' => 50.00,
                'scrap_qty' => 2.00,
                'started_at' => '2026-05-01T08:15:00',
                'completed_at' => '2026-05-01T15:30:00',
                'notes' => 'Components assembled successfully',
            ],
            [
                'production_order_id' => 1,
                'routing_step_id' => 2,
                'work_center_id' => 2,
                'status' => 'completed',
                'actual_setup_time' => 5.00,
                'actual_run_time' => 29.00,
                'completed_qty' => 48.00,
                'scrap_qty' => 0.00,
                'started_at' => '2026-05-01T15:45:00',
                'completed_at' => '2026-05-01T17:15:00',
                'notes' => 'Packaging completed, 2 units scrapped',
            ],
        ]);

        DB::table('production_outputs')->insert([
            ['production_order_id' => 1, 'product_id' => 3, 'warehouse_location_id' => 3, 'quantity' => 48.00, 'unit_cost' => 65.00, 'batch_number' => 'BATCH-DESK-MAY-001'],
            ['production_order_id' => 1, 'product_id' => 3, 'warehouse_location_id' => 3, 'quantity' => 2.00, 'unit_cost' => 65.00, 'batch_number' => 'BATCH-DESK-MAY-001-SCRAP'],
        ]);

        DB::table('production_material_issues')->insert([
            ['production_order_id' => 1, 'stock_item_id' => 4, 'product_id' => 3, 'warehouse_location_id' => 3, 'quantity' => 50.00, 'unit_cost' => 75.00],
        ]);

        DB::table('maintenance_schedules')->insert([
            [
                'work_center_id' => 1,
                'title' => 'Quarterly calibration',
                'type' => 'preventive',
                'frequency' => 'quarterly',
                'frequency_value' => null,
                'last_done_at' => '2026-02-01T08:00:00',
                'next_due_at' => '2026-05-01T08:00:00',
                'estimated_hours' => 4.00,
                'is_active' => true,
            ],
            [
                'work_center_id' => 1,
                'title' => 'Annual overhaul',
                'type' => 'predictive',
                'frequency' => 'yearly',
                'frequency_value' => null,
                'last_done_at' => '2025-06-01T08:00:00',
                'next_due_at' => '2026-06-01T08:00:00',
                'estimated_hours' => 16.00,
                'is_active' => true,
            ],
            [
                'work_center_id' => 2,
                'title' => 'Weekly cleaning & inspection',
                'type' => 'preventive',
                'frequency' => 'weekly',
                'frequency_value' => null,
                'last_done_at' => '2026-05-25T08:00:00',
                'next_due_at' => '2026-06-01T08:00:00',
                'estimated_hours' => 1.00,
                'is_active' => true,
            ],
        ]);

        DB::table('maintenance_logs')->insert([
            [
                'maintenance_schedule_id' => 1,
                'work_center_id' => 1,
                'title' => 'Q2 calibration - Assembly 1',
                'description' => 'Routine calibration of sensors and actuators',
                'type' => 'preventive',
                'status' => 'completed',
                'started_at' => '2026-05-01T08:00:00',
                'completed_at' => '2026-05-01T12:00:00',
                'duration_hours' => 4.00,
                'cost' => 350.00,
                'performed_by' => 1,
                'notes' => 'All sensors calibrated within spec',
            ],
            [
                'maintenance_schedule_id' => 3,
                'work_center_id' => 2,
                'title' => 'Weekly cleaning - Packaging',
                'description' => 'Cleaning and inspection of packaging station',
                'type' => 'preventive',
                'status' => 'completed',
                'started_at' => '2026-05-28T08:00:00',
                'completed_at' => '2026-05-28T09:00:00',
                'duration_hours' => 1.00,
                'cost' => 50.00,
                'performed_by' => 2,
                'notes' => 'Routine cleaning completed',
            ],
        ]);
    }
}
