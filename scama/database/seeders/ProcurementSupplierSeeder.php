<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcurementSupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'company_name' => 'CloudHost Inc',
                'supplier_code' => 'SUP-001',
                'contact_name' => 'Alice Wang',
                'email' => 'alice@cloudhost.com',
                'phone' => '+1-415-555-0100',
                'address' => '500 Server Dr',
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'US',
                'postal_code' => '94105',
                'tax_id' => 'TAX-US-001',
                'payment_terms' => 'Net 30',
                'currency_id' => 1,
                'status' => 'active',
            ],
            [
                'company_name' => 'TechSupply Co',
                'supplier_code' => 'SUP-002',
                'contact_name' => 'Bob Chen',
                'email' => 'bob@techsupply.co',
                'phone' => '+1-312-555-0200',
                'address' => '200 Parts Ave',
                'city' => 'Chicago',
                'state' => 'IL',
                'country' => 'US',
                'postal_code' => '60601',
                'tax_id' => 'TAX-US-002',
                'payment_terms' => 'Net 15',
                'currency_id' => 1,
                'status' => 'active',
            ],
            [
                'company_name' => 'Global Licensing Ltd',
                'supplier_code' => 'SUP-003',
                'contact_name' => 'Carol Smith',
                'email' => 'carol@globallicense.com',
                'phone' => '+44-20-5555-0300',
                'address' => '10 Thames Street',
                'city' => 'London',
                'state' => null,
                'country' => 'GB',
                'postal_code' => 'EC1A',
                'tax_id' => 'TAX-GB-001',
                'payment_terms' => 'Net 60',
                'currency_id' => 3,
                'status' => 'active',
            ],
        ]);

        DB::table('supplier_contacts')->insert([
            ['supplier_id' => 1, 'first_name' => 'Alice', 'last_name' => 'Wang', 'job_title' => 'Account Manager', 'email' => 'alice@cloudhost.com', 'phone' => '+1-415-555-0100', 'is_primary' => true],
            ['supplier_id' => 1, 'first_name' => 'Dan', 'last_name' => 'Lee', 'job_title' => 'Support Engineer', 'email' => 'dan@cloudhost.com', 'phone' => '+1-415-555-0101', 'is_primary' => false],
            ['supplier_id' => 2, 'first_name' => 'Bob', 'last_name' => 'Chen', 'job_title' => 'Sales Director', 'email' => 'bob@techsupply.co', 'phone' => '+1-312-555-0200', 'is_primary' => true],
        ]);

        DB::table('supplier_products')->insert([
            ['supplier_id' => 1, 'product_id' => 1, 'supplier_sku' => 'CLOUD-HOST-CRM', 'lead_time_days' => 1, 'moq' => 1, 'is_preferred' => true],
            ['supplier_id' => 1, 'product_id' => 2, 'supplier_sku' => 'CLOUD-HOST-SAAS', 'lead_time_days' => 2, 'moq' => 1, 'is_preferred' => true],
            ['supplier_id' => 2, 'product_id' => 3, 'supplier_sku' => 'TS-DESK-SUPPLY', 'lead_time_days' => 5, 'moq' => 10, 'is_preferred' => false],
        ]);

        DB::table('supplier_pricelists')->insert([
            ['supplier_product_id' => 1, 'unit_price' => 25.00, 'currency_id' => 1, 'min_quantity' => 1, 'effective_from' => '2026-01-01', 'effective_until' => '2026-12-31', 'is_active' => true],
            ['supplier_product_id' => 2, 'unit_price' => 45.00, 'currency_id' => 1, 'min_quantity' => 1, 'effective_from' => '2026-01-01', 'effective_until' => '2026-12-31', 'is_active' => true],
            ['supplier_product_id' => 3, 'unit_price' => 75.00, 'currency_id' => 1, 'min_quantity' => 10, 'effective_from' => '2026-03-01', 'effective_until' => null, 'is_active' => true],
        ]);

        DB::table('rfqs')->insert([
            [
                'rfq_number' => 'RFQ-2026-0001',
                'title' => 'Cloud hosting services renewal',
                'description' => 'Seeking proposals for cloud hosting for next 12 months',
                'issue_date' => '2026-04-01',
                'closing_date' => '2026-04-15',
                'status' => 'awarded',
                'created_by' => 2,
            ],
            [
                'rfq_number' => 'RFQ-2026-0002',
                'title' => 'Office furniture bulk purchase',
                'description' => 'Need 50 ergonomic chairs and desks',
                'issue_date' => '2026-05-01',
                'closing_date' => '2026-05-20',
                'status' => 'sent',
                'created_by' => 2,
            ],
        ]);

        DB::table('rfq_items')->insert([
            ['rfq_id' => 1, 'product_id' => 1, 'quantity' => 6.00, 'notes' => '6-month CRM hosting package', 'line_order' => 1],
            ['rfq_id' => 1, 'product_id' => 2, 'quantity' => 12.00, 'notes' => '12-month SaaS hosting package', 'line_order' => 2],
            ['rfq_id' => 2, 'product_id' => 3, 'quantity' => 50.00, 'notes' => 'Quantity of 50 items', 'line_order' => 1],
        ]);

        DB::table('supplier_quotations')->insert([
            [
                'rfq_id' => 1,
                'supplier_id' => 1,
                'quotation_number' => 'QTN-CLOUD-001',
                'quotation_date' => '2026-04-10',
                'valid_until' => '2026-05-10',
                'subtotal' => 1250.00,
                'tax' => 125.00,
                'total' => 1375.00,
                'currency_id' => 1,
                'status' => 'accepted',
                'notes' => 'Best pricing for cloud hosting',
            ],
            [
                'rfq_id' => 1,
                'supplier_id' => 2,
                'quotation_number' => 'QTN-TECH-001',
                'quotation_date' => '2026-04-12',
                'valid_until' => '2026-05-12',
                'subtotal' => 1400.00,
                'tax' => 140.00,
                'total' => 1540.00,
                'currency_id' => 1,
                'status' => 'rejected',
                'notes' => 'Higher pricing than cloud specialist',
            ],
        ]);

        DB::table('quotation_items')->insert([
            ['quotation_id' => 1, 'rfq_item_id' => 1, 'product_id' => 1, 'quantity' => 6.00, 'unit_price' => 1250.00, 'subtotal' => 7500.00, 'line_order' => 1],
            ['quotation_id' => 1, 'rfq_item_id' => 2, 'product_id' => 2, 'quantity' => 12.00, 'unit_price' => 500.00, 'subtotal' => 6000.00, 'line_order' => 2],
            ['quotation_id' => 2, 'rfq_item_id' => 1, 'product_id' => 1, 'quantity' => 6.00, 'unit_price' => 1350.00, 'subtotal' => 8100.00, 'line_order' => 1],
        ]);
    }
}
