<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SecurityComplianceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('security_events')->insert([
            [
                'user_id' => 3,
                'event_type' => 'login_success',
                'description' => 'User logged in successfully',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 Chrome/91',
                'created_at' => '2026-05-30 08:00:00',
            ],
            [
                'user_id' => 3,
                'event_type' => 'suspicious_ip',
                'description' => 'Login attempt from unrecognized IP address',
                'ip_address' => '45.33.22.11',
                'user_agent' => 'Mozilla/5.0 Firefox/115',
                'created_at' => '2026-05-30 08:05:00',
            ],
            [
                'user_id' => null,
                'event_type' => 'brute_force_block',
                'description' => 'Blocked brute force attack on admin login endpoint',
                'ip_address' => '10.0.0.99',
                'user_agent' => null,
                'created_at' => '2026-05-29 00:00:00',
            ],
        ]);

        DB::table('vulnerability_scans')->insert([
            [
                'scan_type' => 'full',
                'target' => 'crm.johnsbusiness.com',
                'status' => 'completed',
                'results' => '{"vulnerabilities": [{"id": "VULN-001", "description": "SQL Injection", "severity": "high"}]}',
                'created_at' => '2026-05-30 15:00:00',
            ],
            [
                'scan_type' => 'quick',
                'target' => 'api.licensepro.com',
                'status' => 'completed',
                'results' => '{"vulnerabilities": []}',
                'created_at' => '2026-05-30 16:00:00',
            ],
        ]);

        DB::table('penetration_tests')->insert([
            [
                'test_type' => 'web_app',
                'target' => 'crm.johnsbusiness.com',
                'status' => 'completed',
                'findings' => '{"findings": [{"id": "FIND-001", "description": "XSS in contact form", "severity": "medium"}]}',
                'created_at' => '2026-05-30 16:00:00',
            ],
            [
                'test_type' => 'external',
                'target' => 'licensepro.com',
                'status' => 'completed',
                'findings' => '{"findings": [{"id": "FIND-002", "description": "Open SMTP relay", "severity": "critical"}]}',
                'created_at' => '2026-05-30 17:00:00',
            ],
        ]);

        DB::table('compliance_reports')->insert([
            [
                'report_type' => 'GDPR',
                'status' => 'completed',
                'findings' => '{"compliance_status": "non-compliant", "issues": [{"id": "ISSUE-001", "description": "Lack of data export functionality", "severity": "high"}]}',
                'created_at' => '2026-05-30 17:00:00',
            ],
            [
                'report_type' => 'PCI-DSS',
                'status' => 'completed',
                'findings' => '{"compliance_status": "compliant", "issues": []}',
                'created_at' => '2026-05-30 18:00:00',
            ],
        ]);

        DB::table('security_incidents')->insert([
            [
                'incident_type' => 'data_breach',
                'description' => 'Unauthorized access detected on crm.johnsbusiness.com',
                'status' => 'investigating',
                'reported_at' => '2026-05-30 18:00:00',
            ],
            [
                'incident_type' => 'unauthorized_access',
                'description' => 'Suspicious admin panel login from unknown IP',
                'status' => 'resolved',
                'reported_at' => '2026-05-28 12:00:00',
            ],
        ]);

        DB::table('audit_trails')->insert([
            [
                'user_id' => 3,
                'action' => 'license_activated',
                'entity' => 'licenses',
                'entity_id' => 1,
                'activity_type' => null,
                'description' => null,
                'details' => '{"domain": "crm.johnsbusiness.com"}',
                'old_value' => null,
                'new_value' => null,
                'ip_address' => '192.168.1.100',
                'created_at' => '2026-05-30 10:00:00',
            ],
            [
                'user_id' => 1,
                'action' => 'user_suspended',
                'entity' => 'users',
                'entity_id' => 2,
                'activity_type' => null,
                'description' => null,
                'details' => '{"reason": "TOS violation", "duration": "7 days"}',
                'old_value' => null,
                'new_value' => null,
                'ip_address' => null,
                'created_at' => '2026-05-25 10:00:00',
            ],
            [
                'user_id' => 3,
                'action' => null,
                'entity' => null,
                'entity_id' => null,
                'activity_type' => 'login',
                'description' => 'User logged in',
                'details' => null,
                'old_value' => null,
                'new_value' => null,
                'ip_address' => '192.168.1.100',
                'created_at' => '2026-05-30 08:00:00',
            ],
            [
                'user_id' => 3,
                'action' => null,
                'entity' => null,
                'entity_id' => null,
                'activity_type' => 'license_activated',
                'description' => 'Activated license for CRM',
                'details' => null,
                'old_value' => null,
                'new_value' => null,
                'ip_address' => '192.168.1.100',
                'created_at' => '2026-05-30 10:00:00',
            ],
            [
                'user_id' => 1,
                'action' => 'update',
                'entity' => 'products',
                'entity_id' => 1,
                'activity_type' => null,
                'description' => null,
                'details' => null,
                'old_value' => '{"base_price": "49.00"}',
                'new_value' => '{"base_price": "59.00"}',
                'ip_address' => '10.0.0.1',
                'created_at' => '2026-05-01 10:00:00',
            ],
            [
                'user_id' => 1,
                'action' => 'delete',
                'entity' => 'users',
                'entity_id' => 5,
                'activity_type' => null,
                'description' => null,
                'details' => null,
                'old_value' => '{"name": "Spammer", "email": "spam@example.com"}',
                'new_value' => null,
                'ip_address' => '10.0.0.1',
                'created_at' => '2026-05-15 10:00:00',
            ],
        ]);
    }
}
