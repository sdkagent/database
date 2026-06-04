<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionCoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('work_centers')->insert([
            [
                'code' => 'WC-ASSY-01',
                'name' => 'Assembly Station 1',
                'type' => 'workstation',
                'description' => 'Main assembly line for desktop products',
                'cost_per_hour' => 45.00,
                'efficiency_rate' => 100.00,
                'is_active' => true,
            ],
            [
                'code' => 'WC-PACK-01',
                'name' => 'Packaging Station',
                'type' => 'manual',
                'description' => 'Manual packaging and labeling',
                'cost_per_hour' => 25.00,
                'efficiency_rate' => 95.00,
                'is_active' => true,
            ],
        ]);

        DB::table('work_center_capacity')->insert([
            ['work_center_id' => 1, 'capacity_date' => '2026-05-01', 'available_hours' => 8.00, 'maintenance_hours' => 0.00, 'booked_hours' => 6.00, 'overtime_hours' => 0.00, 'notes' => 'Regular production day'],
            ['work_center_id' => 1, 'capacity_date' => '2026-05-02', 'available_hours' => 8.00, 'maintenance_hours' => 2.00, 'booked_hours' => 4.00, 'overtime_hours' => 0.00, 'notes' => 'Scheduled maintenance 2 hours'],
            ['work_center_id' => 2, 'capacity_date' => '2026-05-01', 'available_hours' => 8.00, 'maintenance_hours' => 0.00, 'booked_hours' => 5.00, 'overtime_hours' => 1.00, 'notes' => 'Overtime due to backlog'],
        ]);

        DB::table('bill_of_materials')->insert([
            ['product_id' => 3, 'name' => 'Desktop Inventory Manager BOM', 'version' => '1.0', 'quantity' => 1.00, 'is_active' => true],
            ['product_id' => 1, 'name' => 'CRM Script Standard Package', 'version' => '2.0', 'quantity' => 1.00, 'is_active' => true],
        ]);

        DB::table('bom_items')->insert([
            ['bom_id' => 1, 'component_id' => 2, 'quantity' => 1.00, 'unit' => 'units', 'scrap_rate' => 0.00, 'line_order' => 1],
            ['bom_id' => 1, 'component_id' => 1, 'quantity' => 2.00, 'unit' => 'units', 'scrap_rate' => 2.50, 'line_order' => 2],
            ['bom_id' => 2, 'component_id' => 3, 'quantity' => 1.00, 'unit' => 'units', 'scrap_rate' => 0.00, 'line_order' => 1],
        ]);

        DB::table('routings')->insert([
            ['bom_id' => 1, 'name' => 'Desktop Assembly Routing', 'total_time' => 120.00, 'is_active' => true],
            ['bom_id' => 1, 'name' => 'Express Assembly Routing', 'total_time' => 60.00, 'is_active' => true],
        ]);

        DB::table('routing_steps')->insert([
            ['routing_id' => 1, 'work_center_id' => 1, 'step_name' => 'Component assembly', 'step_order' => 1, 'setup_time' => 15.00, 'run_time' => 45.00, 'teardown_time' => 10.00, 'notes' => 'Assemble main components'],
            ['routing_id' => 1, 'work_center_id' => 2, 'step_name' => 'Quality check & pack', 'step_order' => 2, 'setup_time' => 5.00, 'run_time' => 30.00, 'teardown_time' => 5.00, 'notes' => 'Inspect and package finished product'],
            ['routing_id' => 2, 'work_center_id' => 1, 'step_name' => 'Express assembly', 'step_order' => 1, 'setup_time' => 10.00, 'run_time' => 30.00, 'teardown_time' => 5.00, 'notes' => 'Simplified assembly for small batches'],
        ]);

        DB::table('capacity_plans')->insert([
            ['work_center_id' => 1, 'plan_date' => '2026-05-01', 'planned_hours' => 8.00, 'actual_hours' => 7.50, 'available_hours' => 8.00, 'notes' => 'Regular shift - full utilization'],
            ['work_center_id' => 1, 'plan_date' => '2026-05-02', 'planned_hours' => 6.00, 'actual_hours' => 6.00, 'available_hours' => 8.00, 'notes' => 'Reduced capacity due to maintenance'],
            ['work_center_id' => 2, 'plan_date' => '2026-05-01', 'planned_hours' => 8.00, 'actual_hours' => 8.00, 'available_hours' => 8.00, 'notes' => 'Full capacity with overtime'],
        ]);
    }
}
