<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxEngineSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tax_jurisdictions')->insert([
            [
                'name' => 'California State Tax',
                'country' => 'US',
                'state' => 'CA',
                'city' => null,
                'postal_code' => null,
                'rate' => 8.7500,
                'tax_type' => 'sales',
                'is_compound' => false,
                'priority' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'NYC City Surcharge',
                'country' => 'US',
                'state' => 'NY',
                'city' => 'New York City',
                'postal_code' => '100*',
                'rate' => 4.5000,
                'tax_type' => 'sales',
                'is_compound' => true,
                'priority' => 2,
                'status' => 'active',
            ],
        ]);

        DB::table('tax_exemptions')->insert([
            [
                'user_id' => 2,
                'exemption_type' => 'reseller',
                'certificate_number' => 'RES-CA-2026-001',
                'issuing_authority' => null,
                'valid_from' => '2026-06-04',
                'valid_to' => '2027-06-04',
                'status' => 'active',
                'verified_by' => 1,
            ],
            [
                'user_id' => 1,
                'exemption_type' => 'nonprofit',
                'certificate_number' => 'NPO-US-FED-12345',
                'issuing_authority' => null,
                'valid_from' => '2026-06-04',
                'valid_to' => null,
                'status' => 'pending',
                'verified_by' => null,
            ],
        ]);

        DB::table('tax_rules')->insert([
            [
                'name' => 'CA Sales Tax',
                'priority' => 1,
                'action_type' => 'rate_override',
                'action_value' => '{"rate":8.7500}',
                'status' => 'active',
            ],
            [
                'name' => 'NYC Surcharge',
                'priority' => 2,
                'action_type' => 'compound',
                'action_value' => '{"rate":4.5000}',
                'status' => 'active',
            ],
        ]);

        DB::table('tax_report_data')->insert([
            [
                'tax_jurisdiction_id' => 1,
                'period_start' => '2026-01-01',
                'period_end' => '2026-03-31',
                'taxable_amount' => 150000.00,
                'tax_collected' => 13125.00,
                'returns_filed' => true,
                'filed_at' => '2026-04-15 10:00:00',
            ],
            [
                'tax_jurisdiction_id' => 2,
                'period_start' => '2026-01-01',
                'period_end' => '2026-03-31',
                'taxable_amount' => 50000.00,
                'tax_collected' => 2250.00,
                'returns_filed' => false,
                'filed_at' => null,
            ],
        ]);
    }
}
