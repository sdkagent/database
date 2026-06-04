<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'id' => 1,
                'seller_id' => 2,
                'name' => 'Ultimate CRM Script',
                'slug' => 'ultimate-crm',
                'description' => 'Advanced CRM for Agencies with multi-tenant support',
                'type' => 'script',
                'base_price' => 59.00,
                'sku' => 'SKU-CRM-001',
                'stock' => 100,
                'download_limit' => 5,
                'total_sales' => 340,
                'version' => '2.3.1',
                'download_url' => null,
                'status' => 'active',
                'demo_url' => 'https://demo.crm.app',
                'docs_url' => 'https://docs.crm.app',
            ],
            [
                'id' => 2,
                'seller_id' => null,
                'name' => 'SaaS Boilerplate',
                'slug' => 'saas-boilerplate',
                'description' => 'Laravel SaaS Starter with billing and team management',
                'type' => 'software',
                'base_price' => 99.00,
                'sku' => 'SKU-SAAS-001',
                'stock' => 50,
                'download_limit' => 10,
                'total_sales' => 120,
                'version' => '1.5.0',
                'download_url' => null,
                'status' => 'active',
                'demo_url' => 'https://demo.saas.dev',
                'docs_url' => 'https://docs.saas.dev',
            ],
            [
                'id' => 3,
                'seller_id' => 2,
                'name' => 'Desktop Inventory Manager',
                'slug' => 'desktop-inventory-manager',
                'description' => 'Offline inventory management desktop application',
                'type' => 'desktop',
                'base_price' => 149.00,
                'sku' => 'SKU-DESK-001',
                'stock' => 25,
                'download_limit' => 3,
                'total_sales' => 55,
                'version' => '2.1.0',
                'download_url' => 'https://downloads.licensepro.com/inventory-manager-2.1.0.exe',
                'status' => 'active',
                'demo_url' => null,
                'docs_url' => 'https://docs.licensepro.com/inventory',
            ],
        ]);

        DB::table('product_hardware_requirements')->insert([
            [
                'product_id' => 3,
                'os_name' => 'Windows',
                'os_version_min' => '10',
                'cpu_cores_min' => 2,
                'memory_mb_min' => 4096,
                'disk_mb_min' => 500,
                'additional_notes' => 'SSD recommended',
            ],
            [
                'product_id' => 3,
                'os_name' => 'macOS',
                'os_version_min' => '12',
                'cpu_cores_min' => 2,
                'memory_mb_min' => 4096,
                'disk_mb_min' => 500,
                'additional_notes' => 'Apple Silicon or Intel',
            ],
        ]);

        DB::table('product_categories')->insert([
            [
                'id' => 1,
                'parent_id' => null,
                'name' => 'CRM Software',
                'slug' => 'crm-software',
                'description' => 'Customer relationship management solutions',
                'image_url' => '/images/cats/crm.png',
                'sort_order' => 1,
            ],
            [
                'id' => 2,
                'parent_id' => null,
                'name' => 'Developer Tools',
                'slug' => 'developer-tools',
                'description' => 'Scripts, boilerplates and dev utilities',
                'image_url' => '/images/cats/dev.png',
                'sort_order' => 2,
            ],
            [
                'id' => 3,
                'parent_id' => 1,
                'name' => 'Sales CRM',
                'slug' => 'sales-crm',
                'description' => 'Sales-focused CRM applications',
                'image_url' => '/images/cats/sales.png',
                'sort_order' => 3,
            ],
        ]);

        DB::table('product_category_items')->insert([
            ['product_id' => 1, 'category_id' => 1],
            ['product_id' => 1, 'category_id' => 3],
            ['product_id' => 2, 'category_id' => 2],
            ['product_id' => 3, 'category_id' => 1],
        ]);

        DB::table('product_discounts')->insert([
            [
                'product_id' => 1,
                'name' => 'Launch Special',
                'type' => 'percentage',
                'value' => 15.00,
                'max_uses' => 50,
                'used_count' => 12,
                'starts_at' => '2026-05-01 00:00:00',
                'ends_at' => '2026-07-01 00:00:00',
            ],
            [
                'product_id' => 3,
                'name' => 'Summer Sale',
                'type' => 'fixed',
                'value' => 25.00,
                'max_uses' => 20,
                'used_count' => 3,
                'starts_at' => '2026-06-01 00:00:00',
                'ends_at' => '2026-08-31 00:00:00',
            ],
        ]);

        DB::table('subscription_plans')->insert([
            [
                'id' => 1,
                'name' => 'Starter',
                'code' => 'starter',
                'duration_months' => 1,
                'max_activations' => 1,
                'price_monthly' => 9.99,
                'price_yearly' => 99.00,
                'features' => json_encode(['1 domain', 'Basic support', 'Community access']),
            ],
            [
                'id' => 2,
                'name' => 'Professional',
                'code' => 'professional',
                'duration_months' => 12,
                'max_activations' => 5,
                'price_monthly' => 29.99,
                'price_yearly' => 299.00,
                'features' => json_encode(['5 domains', 'Priority support', 'API access', 'Advanced analytics']),
            ],
            [
                'id' => 3,
                'name' => 'Enterprise',
                'code' => 'enterprise',
                'duration_months' => 12,
                'max_activations' => 99,
                'price_monthly' => 99.99,
                'price_yearly' => 999.00,
                'features' => json_encode(['Unlimited domains', 'Dedicated support', 'API access', 'White-label', 'SLA guarantee']),
            ],
        ]);

        DB::table('user_subscriptions')->insert([
            [
                'user_id' => 3,
                'plan_id' => 2,
                'product_id' => 1,
                'status' => 'active',
                'start_date' => '2026-05-01 00:00:00',
                'end_date' => '2027-05-01 00:00:00',
                'trial_ends_at' => null,
            ],
            [
                'user_id' => 3,
                'plan_id' => 1,
                'product_id' => 3,
                'status' => 'cancelled',
                'start_date' => '2026-04-01 00:00:00',
                'end_date' => '2026-05-01 00:00:00',
                'trial_ends_at' => '2026-04-15 00:00:00',
            ],
        ]);

        DB::table('wishlists')->insert([
            [
                'user_id' => 3,
                'product_id' => 2,
                'created_at' => '2026-05-15 10:00:00',
            ],
            [
                'user_id' => 3,
                'product_id' => 1,
                'created_at' => '2026-05-20 12:00:00',
            ],
            [
                'user_id' => 4,
                'product_id' => 3,
                'created_at' => '2026-05-25 16:00:00',
            ],
        ]);
    }
}
