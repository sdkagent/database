<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LicensingTablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('api_clients')->insert([
            [
                'user_id' => 3,
                'name' => 'John Production App',
                'api_key' => 'api-prod-a1b2c3d4e5f6g7h8',
                'api_secret' => 'sk_live_xxxxxxxxxxxxxx',
                'status' => 'active',
                'rate_limit' => 60,
            ],
            [
                'user_id' => 3,
                'name' => 'John Staging App',
                'api_key' => 'api-stage-i9j0k1l2m3n4o5p6',
                'api_secret' => 'sk_test_yyyyyyyyyyyyyy',
                'status' => 'active',
                'rate_limit' => 120,
            ],
        ]);

        DB::table('licenses')->insert([
            [
                'id' => 1,
                'user_id' => 3,
                'product_id' => 1,
                'api_client_id' => 1,
                'subscription_id' => 1,
                'license_key' => 'LIC-PRO-2026-8888',
                'api_key' => 'license-api-key-1111-2222-3333',
                'status' => 'active',
                'expires_at' => '2027-05-01 00:00:00',
                'max_activations' => 5,
                'current_activations' => 2,
            ],
            [
                'id' => 2,
                'user_id' => 3,
                'product_id' => 3,
                'api_client_id' => 1,
                'subscription_id' => 1,
                'license_key' => 'LIC-DESKTOP-2026-9999',
                'api_key' => 'license-api-key-4444-5555-6666',
                'status' => 'active',
                'expires_at' => '2027-05-01 00:00:00',
                'max_activations' => 3,
                'current_activations' => 1,
            ],
            [
                'id' => 3,
                'user_id' => 2,
                'product_id' => 2,
                'api_client_id' => 2,
                'subscription_id' => 2,
                'license_key' => 'LIC-SAAS-2026-7777',
                'api_key' => 'license-api-key-7777-8888-9999',
                'status' => 'suspended',
                'expires_at' => '2026-06-01 00:00:00',
                'max_activations' => 1,
                'current_activations' => 0,
            ],
        ]);

        DB::table('license_activations')->insert([
            [
                'license_id' => 1,
                'domain' => 'crm.johnsbusiness.com',
                'hosting_ip' => '103.231.222.15',
                'status' => 'active',
                'last_verified_at' => '2026-05-30 10:00:00',
                'meta' => json_encode(['php_version' => '8.2', 'db' => 'MySQL 8.0']),
            ],
            [
                'license_id' => 1,
                'domain' => 'staging.crm.johnsbusiness.com',
                'hosting_ip' => '103.231.222.16',
                'status' => 'active',
                'last_verified_at' => '2026-05-29 16:00:00',
                'meta' => json_encode(['php_version' => '8.1', 'db' => 'MySQL 8.0']),
            ],
            [
                'license_id' => 2,
                'domain' => 'localhost',
                'hosting_ip' => '127.0.0.1',
                'status' => 'active',
                'last_verified_at' => '2026-05-30 12:00:00',
                'meta' => json_encode(['os' => 'Windows 11 Pro']),
            ],
        ]);

        DB::table('hardware_activations')->insert([
            [
                'id' => 1,
                'license_id' => 2,
                'machine_id' => 'HW-MACHINE-ABC123',
                'cpu_id' => 'CPU-INTL-X9K3M',
                'motherboard_serial' => 'MB-ASUS-Z390-XYZ',
                'bios_serial' => 'BIOS-AMI-1.2.3',
                'disk_serial' => 'DISK-SNV123456',
                'mac_address' => 'AA:BB:CC:DD:EE:FF',
                'os_name' => 'Windows',
                'os_version' => '11',
                'os_architecture' => 'x64',
                'cpu_name' => 'Intel Core i7-9700K',
                'cpu_cores' => 8,
                'total_memory' => 16384,
                'system_manufacturer' => 'ASUS',
                'system_model' => 'ROG Strix Z390',
                'local_ip' => '192.168.1.100',
                'public_ip' => '203.157.123.45',
                'status' => 'active',
                'activation_limit' => 3,
                'required_os_min' => 'Windows 10',
                'required_memory_mb' => 4096,
                'required_disk_mb' => 500,
                'compatibility_status' => 'compatible',
            ],
            [
                'id' => 2,
                'license_id' => 2,
                'machine_id' => 'HW-MACHINE-DEF456',
                'cpu_id' => 'CPU-AMD-R7-5800X',
                'motherboard_serial' => 'MB-GIGA-B550-UVW',
                'bios_serial' => 'BIOS-AMI-2.1.0',
                'disk_serial' => 'DISK-WDS789012',
                'mac_address' => 'DD:EE:FF:AA:BB:CC',
                'os_name' => 'Windows',
                'os_version' => '10',
                'os_architecture' => 'x64',
                'cpu_name' => 'AMD Ryzen 7 5800X',
                'cpu_cores' => 8,
                'total_memory' => 32768,
                'system_manufacturer' => 'Gigabyte',
                'system_model' => 'B550 Aorus Pro',
                'local_ip' => '192.168.1.200',
                'public_ip' => '203.157.123.46',
                'status' => 'active',
                'activation_limit' => 3,
                'required_os_min' => 'Windows 10',
                'required_memory_mb' => 4096,
                'required_disk_mb' => 500,
                'compatibility_status' => 'compatible',
            ],
        ]);

        DB::table('hardware_activation_logs')->insert([
            [
                'hardware_activation_id' => 1,
                'license_id' => 2,
                'machine_id' => 'HW-MACHINE-ABC123',
                'hardware_snapshot' => json_encode(['cpu' => 'Intel Core i7-9700K', 'memory_mb' => 16384, 'disk' => 'SNV123456']),
                'system_specs' => json_encode(['os' => 'Windows 11 x64', 'cpu_cores' => 8, 'total_memory_mb' => 16384]),
                'activation_status' => 'success',
                'vm_detected' => false,
                'tamper_detected' => false,
                'compatibility_result' => 'compatible',
                'created_at' => '2026-05-30 10:05:00',
            ],
            [
                'hardware_activation_id' => 2,
                'license_id' => 2,
                'machine_id' => 'HW-MACHINE-DEF456',
                'hardware_snapshot' => json_encode(['cpu' => 'AMD Ryzen 7 5800X', 'memory_mb' => 32768, 'disk' => 'WDS789012']),
                'system_specs' => json_encode(['os' => 'Windows 10 x64', 'cpu_cores' => 8, 'total_memory_mb' => 32768]),
                'activation_status' => 'success',
                'vm_detected' => false,
                'tamper_detected' => false,
                'compatibility_result' => 'compatible',
                'created_at' => '2026-05-29 18:00:00',
            ],
        ]);
    }
}
