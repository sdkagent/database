<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcurementPurchaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('purchase_orders')->insert([
            [
                'supplier_id' => 1,
                'order_number' => 'PO-2026-0001',
                'status' => 'received',
                'order_date' => '2026-05-02',
                'expected_date' => '2026-05-05',
                'subtotal' => 1250.00,
                'tax' => 125.00,
                'total' => 1375.00,
                'currency_id' => 1,
                'notes' => 'Monthly cloud hosting renewal',
                'requested_by' => 2,
                'approved_by' => 1,
            ],
            [
                'supplier_id' => 2,
                'order_number' => 'PO-2026-0002',
                'status' => 'approved',
                'order_date' => '2026-05-10',
                'expected_date' => '2026-05-20',
                'subtotal' => 750.00,
                'tax' => 75.00,
                'total' => 825.00,
                'currency_id' => 1,
                'notes' => 'Office supply restock',
                'requested_by' => 2,
                'approved_by' => 1,
            ],
            [
                'supplier_id' => 3,
                'order_number' => 'PO-2026-0003',
                'status' => 'draft',
                'order_date' => '2026-05-25',
                'expected_date' => '2026-06-01',
                'subtotal' => 5000.00,
                'tax' => 0.00,
                'total' => 5000.00,
                'currency_id' => 3,
                'notes' => 'Annual licensing fee for third-party SDK',
                'requested_by' => 1,
                'approved_by' => null,
            ],
        ]);

        DB::table('purchase_order_items')->insert([
            ['purchase_order_id' => 1, 'product_id' => 1, 'warehouse_location_id' => 1, 'description' => 'Cloud hosting for CRM - 6 months', 'quantity' => 1.00, 'received_qty' => 1.00, 'unit_price' => 1250.00, 'tax_rate' => 10.00, 'subtotal' => 1250.00, 'line_order' => 1],
            ['purchase_order_id' => 2, 'product_id' => 3, 'warehouse_location_id' => 1, 'description' => 'Desk supplies for office', 'quantity' => 50.00, 'received_qty' => 0.00, 'unit_price' => 15.00, 'tax_rate' => 10.00, 'subtotal' => 750.00, 'line_order' => 1],
            ['purchase_order_id' => 3, 'product_id' => 2, 'warehouse_location_id' => 1, 'description' => 'Annual SaaS SDK license for boilerplate', 'quantity' => 1.00, 'received_qty' => 0.00, 'unit_price' => 5000.00, 'tax_rate' => 0.00, 'subtotal' => 5000.00, 'line_order' => 1],
        ]);

        DB::table('purchase_receipts')->insert([
            [
                'purchase_order_id' => 1,
                'receipt_number' => 'PR-2026-0001',
                'received_date' => '2026-05-03',
                'status' => 'completed',
                'notes' => 'Cloud hosting service activated',
                'received_by' => 1,
            ],
            [
                'purchase_order_id' => 1,
                'receipt_number' => 'PR-2026-0002',
                'received_date' => '2026-05-04',
                'status' => 'completed',
                'notes' => 'Additional bandwidth add-on',
                'received_by' => 1,
            ],
        ]);

        DB::table('purchase_receipt_items')->insert([
            ['purchase_receipt_id' => 1, 'po_item_id' => 1, 'product_id' => 1, 'warehouse_location_id' => 3, 'quantity' => 1.00, 'unit_cost' => 1250.00, 'batch_number' => null, 'expiry_date' => null],
            ['purchase_receipt_id' => 2, 'po_item_id' => 1, 'product_id' => 1, 'warehouse_location_id' => 3, 'quantity' => 1.00, 'unit_cost' => 1250.00, 'batch_number' => null, 'expiry_date' => null],
        ]);

        DB::table('purchase_invoices')->insert([
            [
                'purchase_order_id' => 1,
                'supplier_id' => 1,
                'invoice_number' => 'INV-CLOUD-2026-001',
                'invoice_date' => '2026-05-02',
                'due_date' => '2026-06-01',
                'subtotal' => 1250.00,
                'tax' => 125.00,
                'total' => 1375.00,
                'currency_id' => 1,
                'status' => 'approved',
                'notes' => 'Monthly hosting invoice',
            ],
            [
                'purchase_order_id' => 2,
                'supplier_id' => 2,
                'invoice_number' => 'INV-TECH-2026-001',
                'invoice_date' => '2026-05-10',
                'due_date' => '2026-05-25',
                'subtotal' => 750.00,
                'tax' => 75.00,
                'total' => 825.00,
                'currency_id' => 1,
                'status' => 'pending',
                'notes' => 'Office supply invoice',
            ],
        ]);

        DB::table('purchase_invoice_items')->insert([
            ['purchase_invoice_id' => 1, 'po_item_id' => 1, 'product_id' => 1, 'quantity' => 1.00, 'unit_price' => 1250.00, 'subtotal' => 1250.00],
            ['purchase_invoice_id' => 2, 'po_item_id' => 2, 'product_id' => 3, 'quantity' => 50.00, 'unit_price' => 15.00, 'subtotal' => 750.00],
        ]);
    }
}
