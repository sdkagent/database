<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DynamicPricingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('price_rules')->insert([
            [
                'name' => 'Summer Sale 20% Off',
                'slug' => 'summer-sale-20',
                'description' => 'All products 20% off during summer',
                'priority' => 10,
                'conditions' => '{"conditions":[{"field":"season","operator":"eq","value":"summer"}]}',
                'adjustments' => '{"type":"percentage","value":20,"apply_to":"all"}',
                'applies_to' => 'all',
                'stackable' => false,
                'status' => 'active',
                'starts_at' => '2026-06-01 00:00:00',
                'expires_at' => '2026-08-31 23:59:59',
                'created_by' => 1,
            ],
            [
                'name' => 'Bulk Purchase Tier',
                'slug' => 'bulk-tier-10',
                'description' => '10% off for orders over $500',
                'priority' => 20,
                'conditions' => '{"conditions":[{"field":"order_total","operator":"gte","value":500}]}',
                'adjustments' => '{"type":"percentage","value":10,"apply_to":"order"}',
                'applies_to' => 'all',
                'stackable' => false,
                'status' => 'active',
                'starts_at' => null,
                'expires_at' => null,
                'created_by' => 1,
            ],
        ]);

        DB::table('price_tiers')->insert([
            ['product_id' => 1, 'min_quantity' => 10, 'max_quantity' => 100, 'unit_price' => 49.99],
            ['product_id' => 1, 'min_quantity' => 5, 'max_quantity' => 50, 'unit_price' => 84.99],
        ]);

        DB::table('price_overrides')->insert([
            [
                'user_id' => 2,
                'product_id' => 1,
                'override_price' => 29.99,
                'override_type' => 'fixed',
                'starts_at' => '2026-06-01 00:00:00',
                'expires_at' => '2026-12-31 23:59:59',
                'created_by' => 1,
            ],
            [
                'user_id' => 2,
                'product_id' => 2,
                'override_price' => 499.99,
                'override_type' => 'fixed',
                'starts_at' => '2026-06-01 00:00:00',
                'expires_at' => '2026-12-31 23:59:59',
                'created_by' => 1,
            ],
        ]);

        DB::table('price_rule_audit')->insert([
            [
                'price_rule_id' => 1,
                'user_id' => 1,
                'product_id' => 1,
                'original_price' => 39.99,
                'adjusted_price' => 31.99,
                'rule_name' => 'Summer Sale 20% applied to Product 1',
            ],
            [
                'price_rule_id' => 2,
                'user_id' => 1,
                'product_id' => 2,
                'original_price' => 599.99,
                'adjusted_price' => 539.99,
                'rule_name' => 'Bulk tier 10% applied to Product 2',
            ],
        ]);
    }
}
