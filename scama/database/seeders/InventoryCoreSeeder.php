<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryCoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('warehouses')->insert([
            [
                'name' => 'Main Warehouse - New York',
                'code' => 'WH-NYC',
                'address' => '100 Industrial Blvd',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'US',
                'postal_code' => '10001',
                'is_active' => true,
            ],
            [
                'name' => 'West Coast Warehouse',
                'code' => 'WH-LAX',
                'address' => '200 Distribution Ave',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'US',
                'postal_code' => '90001',
                'is_active' => true,
            ],
        ]);

        DB::table('warehouse_locations')->insert([
            ['warehouse_id' => 1, 'parent_id' => null, 'code' => 'AISLE-A', 'name' => 'Main Aisle A', 'type' => 'aisle', 'max_weight' => 2000.00, 'max_volume' => 500.00, 'is_active' => true],
            ['warehouse_id' => 1, 'parent_id' => 1, 'code' => 'RACK-A1', 'name' => 'Rack A1', 'type' => 'rack', 'max_weight' => 500.00, 'max_volume' => 100.00, 'is_active' => true],
            ['warehouse_id' => 1, 'parent_id' => 2, 'code' => 'SHELF-A1A', 'name' => 'Shelf A1A', 'type' => 'shelf', 'max_weight' => 100.00, 'max_volume' => 20.00, 'is_active' => true],
            ['warehouse_id' => 2, 'parent_id' => null, 'code' => 'AISLE-B', 'name' => 'West Aisle B', 'type' => 'aisle', 'max_weight' => 2000.00, 'max_volume' => 500.00, 'is_active' => true],
            ['warehouse_id' => 2, 'parent_id' => 4, 'code' => 'RACK-B1', 'name' => 'Rack B1', 'type' => 'rack', 'max_weight' => 500.00, 'max_volume' => 100.00, 'is_active' => true],
        ]);

        DB::table('stock_items')->insert([
            ['product_id' => 1, 'warehouse_location_id' => 3, 'serial_number' => 'SN-CRM-001', 'batch_number' => 'BATCH-CRM-001', 'quantity' => 50.00, 'reserved_quantity' => 2.00, 'unit_cost' => 25.00, 'expiry_date' => '2026-12-31', 'status' => 'available'],
            ['product_id' => 1, 'warehouse_location_id' => 3, 'serial_number' => 'SN-CRM-002', 'batch_number' => 'BATCH-CRM-001', 'quantity' => 48.00, 'reserved_quantity' => 0.00, 'unit_cost' => 25.00, 'expiry_date' => '2026-12-31', 'status' => 'available'],
            ['product_id' => 2, 'warehouse_location_id' => 5, 'serial_number' => 'SN-SAAS-001', 'batch_number' => 'BATCH-SAAS-001', 'quantity' => 100.00, 'reserved_quantity' => 5.00, 'unit_cost' => 45.00, 'expiry_date' => null, 'status' => 'available'],
            ['product_id' => 3, 'warehouse_location_id' => 3, 'serial_number' => 'SN-DESK-001', 'batch_number' => 'BATCH-DESK-001', 'quantity' => 25.00, 'reserved_quantity' => 1.00, 'unit_cost' => 75.00, 'expiry_date' => null, 'status' => 'available'],
        ]);

        DB::table('inventory_movements')->insert([
            ['product_id' => 1, 'from_location_id' => null, 'to_location_id' => 3, 'stock_item_id' => 1, 'movement_type' => 'receipt', 'reference_type' => 'purchase_order', 'reference_id' => 1, 'quantity' => 50.00, 'unit_cost' => 25.00, 'notes' => 'Initial stock receipt from supplier', 'created_by' => 1],
            ['product_id' => 2, 'from_location_id' => null, 'to_location_id' => 5, 'stock_item_id' => 3, 'movement_type' => 'receipt', 'reference_type' => 'purchase_order', 'reference_id' => 2, 'quantity' => 100.00, 'unit_cost' => 45.00, 'notes' => 'SaaS license code batch received', 'created_by' => 1],
            ['product_id' => 1, 'from_location_id' => 3, 'to_location_id' => 2, 'stock_item_id' => 1, 'movement_type' => 'transfer', 'reference_type' => 'transfer_order', 'reference_id' => 1, 'quantity' => 5.00, 'unit_cost' => 25.00, 'notes' => 'Transferred to Rack A1 for picking', 'created_by' => 1],
        ]);

        DB::table('inventory_adjustments')->insert([
            ['product_id' => 1, 'warehouse_location_id' => 3, 'adjustment_type' => 'count', 'expected_qty' => 50.00, 'actual_qty' => 49.00, 'difference' => -1.00, 'reason' => 'Inventory count discrepancy - 1 unit missing', 'approved_by' => 1],
            ['product_id' => 3, 'warehouse_location_id' => 3, 'adjustment_type' => 'damage', 'expected_qty' => 25.00, 'actual_qty' => 24.00, 'difference' => -1.00, 'reason' => 'Damaged during handling', 'approved_by' => 1],
        ]);
    }
}
