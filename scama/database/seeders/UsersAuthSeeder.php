<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersAuthSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'role' => 'admin',
                'name' => 'Super Admin',
                'email' => 'admin@licensepro.com',
                'password' => '$2y$10$hashedpassword1',
                'phone' => '+1-555-0100',
                'status' => 'active',
                'avatar_url' => 'https://cdn.licensepro.com/avatars/admin.png',
                'last_login_at' => '2026-05-30 08:00:00',
                'locale' => 'en',
                'timezone' => 'America/New_York',
                'created_at' => '2026-01-15 08:00:00',
            ],
            [
                'id' => 2,
                'role' => 'seller',
                'name' => 'CodeMaster Dev',
                'email' => 'seller@licensepro.com',
                'password' => '$2y$10$hashedpassword2',
                'phone' => '+1-555-0101',
                'status' => 'active',
                'avatar_url' => 'https://cdn.licensepro.com/avatars/codemaster.png',
                'last_login_at' => '2026-05-29 14:30:00',
                'locale' => 'en',
                'timezone' => 'America/Los_Angeles',
                'created_at' => '2026-02-01 10:30:00',
            ],
            [
                'id' => 3,
                'role' => 'user',
                'name' => 'John Buyer',
                'email' => 'john@customer.com',
                'password' => '$2y$10$hashedpassword3',
                'phone' => '+1-555-0102',
                'status' => 'active',
                'avatar_url' => null,
                'last_login_at' => '2026-05-30 14:00:00',
                'locale' => 'en',
                'timezone' => 'America/New_York',
                'created_at' => '2026-03-10 14:00:00',
            ],
            [
                'id' => 4,
                'role' => 'support',
                'name' => 'Sarah Support',
                'email' => 'sarah@licensepro.com',
                'password' => '$2y$10$hashedpassword4',
                'phone' => '+1-555-0103',
                'status' => 'active',
                'avatar_url' => 'https://cdn.licensepro.com/avatars/sarah.png',
                'last_login_at' => '2026-05-30 09:00:00',
                'locale' => 'en',
                'timezone' => 'Europe/London',
                'created_at' => '2026-03-15 09:00:00',
            ],
        ]);

        DB::table('password_resets')->insert([
            [
                'user_id' => 3,
                'token' => '$2y$10$tokenhashpasswordreset1',
                'expires_at' => '2026-05-30 15:00:00',
                'used_at' => '2026-05-30 14:05:00',
                'created_at' => '2026-05-30 14:00:00',
            ],
            [
                'user_id' => 3,
                'token' => '$2y$10$tokenhashpasswordreset2',
                'expires_at' => '2026-05-29 11:00:00',
                'used_at' => null,
                'created_at' => '2026-05-29 10:00:00',
            ],
        ]);

        DB::table('organizations')->insert([
            [
                'id' => 1,
                'name' => 'TechCorp Inc.',
                'slug' => 'techcorp',
                'logo_url' => 'https://cdn.example.com/logos/techcorp.png',
                'website' => 'https://techcorp.com',
                'status' => 'active',
                'created_at' => '2026-01-20 09:00:00',
            ],
            [
                'id' => 2,
                'name' => 'StartupXYZ',
                'slug' => 'startupxyz',
                'logo_url' => 'https://cdn.example.com/logos/startupxyz.png',
                'website' => 'https://startupxyz.io',
                'status' => 'active',
                'created_at' => '2026-03-05 14:00:00',
            ],
        ]);

        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full access to all system features',
                'is_system' => true,
                'organization_id' => null,
                'created_at' => '2026-01-01 00:00:00',
            ],
            [
                'id' => 2,
                'name' => 'Support Agent',
                'slug' => 'support-agent',
                'description' => 'Access to tickets, chats, and user management',
                'is_system' => true,
                'organization_id' => 1,
                'created_at' => '2026-01-01 00:00:00',
            ],
            [
                'id' => 3,
                'name' => 'Seller',
                'slug' => 'seller',
                'description' => 'Manage products, view sales, and payouts',
                'is_system' => true,
                'organization_id' => 1,
                'created_at' => '2026-01-01 00:00:00',
            ],
        ]);

        DB::table('permissions')->insert([
            [
                'id' => 1,
                'name' => 'Manage Users',
                'slug' => 'manage-users',
                'description' => 'Create, edit, and suspend user accounts',
                'group' => 'users',
                'organization_id' => null,
                'created_at' => '2026-01-01 00:00:00',
            ],
            [
                'id' => 2,
                'name' => 'Manage Products',
                'slug' => 'manage-products',
                'description' => 'Add, edit, and archive products',
                'group' => 'products',
                'organization_id' => null,
                'created_at' => '2026-01-01 00:00:00',
            ],
            [
                'id' => 3,
                'name' => 'Manage Orders',
                'slug' => 'manage-orders',
                'description' => 'View and update order statuses',
                'group' => 'orders',
                'organization_id' => null,
                'created_at' => '2026-01-01 00:00:00',
            ],
            [
                'id' => 4,
                'name' => 'View Reports',
                'slug' => 'view-reports',
                'description' => 'Access analytics and sales reports',
                'group' => 'reports',
                'organization_id' => null,
                'created_at' => '2026-01-01 00:00:00',
            ],
        ]);

        DB::table('role_permissions')->insert([
            ['role_id' => 1, 'permission_id' => 1],
            ['role_id' => 1, 'permission_id' => 2],
            ['role_id' => 1, 'permission_id' => 3],
            ['role_id' => 1, 'permission_id' => 4],
            ['role_id' => 2, 'permission_id' => 1],
            ['role_id' => 2, 'permission_id' => 3],
            ['role_id' => 3, 'permission_id' => 2],
            ['role_id' => 3, 'permission_id' => 3],
            ['role_id' => 3, 'permission_id' => 4],
        ]);

        DB::table('user_roles')->insert([
            [
                'user_id' => 1,
                'role_id' => 1,
                'organization_id' => 1,
                'assigned_at' => '2026-01-15 08:00:00',
            ],
            [
                'user_id' => 4,
                'role_id' => 2,
                'organization_id' => 1,
                'assigned_at' => '2026-03-15 09:00:00',
            ],
            [
                'user_id' => 2,
                'role_id' => 3,
                'organization_id' => 1,
                'assigned_at' => '2026-02-01 10:30:00',
            ],
        ]);

        DB::table('organization_members')->insert([
            [
                'organization_id' => 1,
                'user_id' => 1,
                'role' => 'owner',
                'joined_at' => '2026-01-20 09:00:00',
            ],
            [
                'organization_id' => 1,
                'user_id' => 3,
                'role' => 'member',
                'joined_at' => '2026-03-10 14:00:00',
            ],
            [
                'organization_id' => 2,
                'user_id' => 2,
                'role' => 'owner',
                'joined_at' => '2026-03-05 14:00:00',
            ],
        ]);
    }
}
