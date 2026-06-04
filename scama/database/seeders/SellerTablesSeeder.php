<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SellerTablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('seller_profiles')->insert([
            [
                'user_id' => 1,
                'store_name' => 'Admin Store',
                'store_description' => 'Official store of the platform admin.',
                'store_logo_url' => 'https://cdn.example.com/logos/admin.png',
                'status' => 'active',
                'current_balance' => 500.0000,
                'default_commission' => 85.00,
                'verified_at' => '2026-04-01 12:00:00',
            ],
            [
                'user_id' => 2,
                'store_name' => 'CodeMaster Shop',
                'store_description' => 'Premium software licenses for developers.',
                'store_logo_url' => 'https://cdn.example.com/logos/codemaster.png',
                'status' => 'active',
                'current_balance' => 150.0000,
                'default_commission' => 80.00,
                'verified_at' => '2026-04-05 14:30:00',
            ],
        ]);

        DB::table('payout_accounts')->insert([
            [
                'id' => 1,
                'seller_id' => 1,
                'method' => 'bank',
                'account_label' => 'Admin Checking Account',
                'account_details' => json_encode(['bank' => 'Chase', 'account' => '****1234', 'routing' => '****5678']),
                'is_default' => true,
                'status' => 'active',
            ],
            [
                'id' => 2,
                'seller_id' => 2,
                'method' => 'paypal',
                'account_label' => 'CodeMaster PayPal',
                'account_details' => json_encode(['email' => 'codemaster@paypal.com']),
                'is_default' => true,
                'status' => 'active',
            ],
            [
                'id' => 3,
                'seller_id' => 2,
                'method' => 'stripe',
                'account_label' => 'CodeMaster Stripe',
                'account_details' => json_encode(['account' => 'acct_stripe_123456']),
                'is_default' => false,
                'status' => 'active',
            ],
        ]);

        DB::table('payout_transactions')->insert([
            [
                'id' => 1,
                'seller_id' => 2,
                'payout_account_id' => 2,
                'amount' => 200.00,
                'fee' => 5.00,
                'net_amount' => 195.00,
                'currency' => 'USD',
                'period_start' => '2026-05-01',
                'period_end' => '2026-05-15',
                'status' => 'completed',
                'reference' => 'paypal_payout_001',
                'processed_at' => '2026-05-16 10:00:00',
            ],
            [
                'id' => 2,
                'seller_id' => 1,
                'payout_account_id' => 1,
                'amount' => 100.00,
                'fee' => 2.50,
                'net_amount' => 97.50,
                'currency' => 'USD',
                'period_start' => '2026-05-01',
                'period_end' => '2026-05-15',
                'status' => 'pending',
                'reference' => null,
                'processed_at' => null,
            ],
        ]);

        DB::table('balance_ledger')->insert([
            [
                'id' => 1,
                'seller_id' => 2,
                'type' => 'sale_credit',
                'amount' => 200.0000,
                'balance_before' => 100.0000,
                'balance_after' => 300.0000,
                'reference_type' => 'order',
                'reference_id' => 1,
                'description' => 'Sale commission from order #1',
                'created_at' => '2026-05-10 12:00:00',
            ],
            [
                'id' => 2,
                'seller_id' => 2,
                'type' => 'payout_debit',
                'amount' => 150.0000,
                'balance_before' => 300.0000,
                'balance_after' => 150.0000,
                'reference_type' => 'payout',
                'reference_id' => 1,
                'description' => 'Weekly payout processed',
                'created_at' => '2026-05-16 10:00:00',
            ],
        ]);

        DB::table('seller_verification')->insert([
            [
                'id' => 1,
                'seller_id' => 2,
                'document_type' => 'id_card',
                'document_url' => 'https://cdn.example.com/docs/codemaster_id.jpg',
                'status' => 'approved',
                'verified_by' => 1,
                'verified_at' => '2026-04-01 12:00:00',
                'rejection_reason' => null,
            ],
            [
                'id' => 2,
                'seller_id' => 1,
                'document_type' => 'business_license',
                'document_url' => 'https://cdn.example.com/docs/admin_business.pdf',
                'status' => 'pending',
                'verified_by' => null,
                'verified_at' => null,
                'rejection_reason' => null,
            ],
        ]);

        DB::table('seller_stats')->insert([
            [
                'id' => 1,
                'seller_id' => 2,
                'period_type' => 'weekly',
                'period_date' => '2026-05-11',
                'total_sales' => 1200.00,
                'total_earnings' => 960.00,
                'total_orders' => 15,
                'total_products' => 3,
                'avg_rating' => 4.50,
                'review_count' => 2,
            ],
            [
                'id' => 2,
                'seller_id' => 2,
                'period_type' => 'monthly',
                'period_date' => '2026-05-01',
                'total_sales' => 4500.00,
                'total_earnings' => 3600.00,
                'total_orders' => 55,
                'total_products' => 3,
                'avg_rating' => 4.50,
                'review_count' => 2,
            ],
        ]);
    }
}
