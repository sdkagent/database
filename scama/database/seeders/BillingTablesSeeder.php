<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillingTablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('invoices')->insert([
            [
                'user_id' => 3,
                'order_id' => 1,
                'subscription_id' => 1,
                'invoice_number' => 'INV-2026-001',
                'total' => 328.90,
                'tax' => 29.90,
                'status' => 'paid',
                'created_at' => '2026-05-01 00:00:00',
            ],
            [
                'user_id' => 3,
                'order_id' => 2,
                'subscription_id' => 2,
                'invoice_number' => 'INV-2026-002',
                'total' => 9.99,
                'tax' => 0.00,
                'status' => 'void',
                'created_at' => '2026-04-01 00:00:00',
            ],
        ]);

        DB::table('payments')->insert([
            [
                'invoice_id' => 1,
                'gateway' => 'stripe',
                'transaction_id' => 'pi_stripe_39182931293',
                'amount' => 299.00,
                'status' => 'success',
                'meta' => json_encode(['card_brand' => 'Visa', 'last4' => '4242']),
                'created_at' => '2026-05-01 00:00:00',
            ],
            [
                'invoice_id' => 1,
                'gateway' => 'stripe',
                'transaction_id' => 'pi_stripe_39182931294',
                'amount' => 29.90,
                'status' => 'success',
                'meta' => json_encode(['card_brand' => 'Visa', 'last4' => '4242']),
                'created_at' => '2026-05-01 00:00:00',
            ],
        ]);

        DB::table('tax_rates')->insert([
            [
                'id' => 1,
                'name' => 'US Sales Tax',
                'rate' => 10.00,
                'type' => 'percentage',
                'country' => 'US',
                'region' => null,
                'is_default' => true,
                'active' => true,
            ],
            [
                'id' => 2,
                'name' => 'UK VAT',
                'rate' => 20.00,
                'type' => 'percentage',
                'country' => 'GB',
                'region' => null,
                'is_default' => false,
                'active' => true,
            ],
            [
                'id' => 3,
                'name' => 'DE VAT',
                'rate' => 19.00,
                'type' => 'percentage',
                'country' => 'DE',
                'region' => null,
                'is_default' => false,
                'active' => true,
            ],
        ]);
    }
}
