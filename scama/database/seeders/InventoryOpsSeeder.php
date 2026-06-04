<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryOpsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('stock_counts')->insert([
            [
                'warehouse_id' => 1,
                'count_date' => '2026-05-01',
                'status' => 'completed',
                'counted_by' => 1,
                'verified_by' => 1,
                'notes' => 'Monthly cycle count - Aisle A',
            ],
            [
                'warehouse_id' => 2,
                'count_date' => '2026-05-15',
                'status' => 'completed',
                'counted_by' => 2,
                'verified_by' => 1,
                'notes' => 'Monthly cycle count - Aisle B',
            ],
        ]);

        DB::table('stock_count_items')->insert([
            ['stock_count_id' => 1, 'product_id' => 1, 'location_id' => 3, 'expected_qty' => 50.00, 'counted_qty' => 49.00, 'notes' => 'One unit missing, needs investigation'],
            ['stock_count_id' => 1, 'product_id' => 3, 'location_id' => 3, 'expected_qty' => 25.00, 'counted_qty' => 25.00, 'notes' => 'Count matched'],
            ['stock_count_id' => 2, 'product_id' => 2, 'location_id' => 5, 'expected_qty' => 100.00, 'counted_qty' => 100.00, 'notes' => 'Count matched'],
        ]);

        DB::table('reorder_rules')->insert([
            ['product_id' => 1, 'warehouse_id' => 1, 'min_quantity' => 5.00, 'max_quantity' => 100.00, 'reorder_point' => 10.00, 'reorder_qty' => 50.00, 'lead_time_days' => 7, 'is_active' => true],
            ['product_id' => 2, 'warehouse_id' => 2, 'min_quantity' => 10.00, 'max_quantity' => 200.00, 'reorder_point' => 20.00, 'reorder_qty' => 100.00, 'lead_time_days' => 3, 'is_active' => true],
        ]);

        DB::table('transfer_orders')->insert([
            [
                'from_warehouse_id' => 1,
                'to_warehouse_id' => 2,
                'transfer_number' => 'TO-2026-0001',
                'status' => 'completed',
                'requested_by' => 2,
                'approved_by' => 1,
                'notes' => 'Replenish West Coast stock',
            ],
            [
                'from_warehouse_id' => 2,
                'to_warehouse_id' => 1,
                'transfer_number' => 'TO-2026-0002',
                'status' => 'draft',
                'requested_by' => 2,
                'approved_by' => null,
                'notes' => 'Return excess inventory to main WH',
            ],
        ]);

        DB::table('transfer_order_items')->insert([
            ['transfer_order_id' => 1, 'product_id' => 1, 'stock_item_id' => 1, 'quantity' => 10.00, 'received_qty' => 10.00, 'unit_cost' => 25.00],
            ['transfer_order_id' => 1, 'product_id' => 3, 'stock_item_id' => 4, 'quantity' => 5.00, 'received_qty' => 5.00, 'unit_cost' => 75.00],
        ]);
    }
}
