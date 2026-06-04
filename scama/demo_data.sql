-- ================================================================
-- DEMO DATA — 2+ records per table
-- All FK references match IDs inserted in order below.
-- ================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ==========================================
-- 1. CORE — USERS & AUTH
-- ==========================================

TRUNCATE TABLE `users`;
INSERT INTO `users` (`id`, `role`, `name`, `email`, `password`, `phone`, `status`, `avatar_url`, `last_login_at`, `locale`, `timezone`, `created_at`) VALUES
(1, 'admin',   'Super Admin',        'admin@licensepro.com',    '$2y$10$hashedpassword1', '+1-555-0100', 'active',   'https://cdn.licensepro.com/avatars/admin.png',      '2026-05-30 08:00:00', 'en', 'America/New_York',    '2026-01-15 08:00:00'),
(2, 'seller',  'CodeMaster Dev',     'seller@licensepro.com',  '$2y$10$hashedpassword2', '+1-555-0101', 'active',   'https://cdn.licensepro.com/avatars/codemaster.png', '2026-05-29 14:30:00', 'en', 'America/Los_Angeles', '2026-02-01 10:30:00'),
(3, 'user',    'John Buyer',         'john@customer.com',       '$2y$10$hashedpassword3', '+1-555-0102', 'active',   NULL,                                                  '2026-05-30 14:00:00', 'en', 'America/New_York',    '2026-03-10 14:00:00'),
(4, 'support', 'Sarah Support',      'sarah@licensepro.com',    '$2y$10$hashedpassword4', '+1-555-0103', 'active',   'https://cdn.licensepro.com/avatars/sarah.png',      '2026-05-30 09:00:00', 'en', 'Europe/London',       '2026-03-15 09:00:00');

TRUNCATE TABLE `seller_profiles`;
INSERT INTO `seller_profiles` (`user_id`, `store_name`, `store_description`, `store_logo_url`, `status`, `current_balance`, `default_commission`, `verified_at`) VALUES
(1, 'Admin Store',    'Official store of the platform admin.',  'https://cdn.example.com/logos/admin.png',  'active', 500.0000, 85.00, '2026-04-01 12:00:00'),
(2, 'CodeMaster Shop','Premium software licenses for developers.','https://cdn.example.com/logos/codemaster.png','active',150.0000, 80.00, '2026-04-05 14:30:00');

TRUNCATE TABLE `payout_accounts`;
INSERT INTO `payout_accounts` (`id`, `seller_id`, `method`, `account_label`, `account_details`, `is_default`, `status`) VALUES
(1, 1, 'bank',   'Admin Checking Account',  '{"bank":"Chase","account":"****1234","routing":"****5678"}', TRUE,  'active'),
(2, 2, 'paypal', 'CodeMaster PayPal',       '{"email":"codemaster@paypal.com"}',                          TRUE,  'active'),
(3, 2, 'stripe', 'CodeMaster Stripe',       '{"account":"acct_stripe_123456"}',                             FALSE, 'active');

TRUNCATE TABLE `payout_transactions`;
INSERT INTO `payout_transactions` (`id`, `seller_id`, `payout_account_id`, `amount`, `fee`, `net_amount`, `currency`, `period_start`, `period_end`, `status`, `reference`, `processed_at`) VALUES
(1, 2, 2, 200.00, 5.00, 195.00, 'USD', '2026-05-01', '2026-05-15', 'completed', 'paypal_payout_001', '2026-05-16 10:00:00'),
(2, 1, 1, 100.00, 2.50, 97.50,  'USD', '2026-05-01', '2026-05-15', 'pending',   NULL,                 NULL);

TRUNCATE TABLE `balance_ledger`;
INSERT INTO `balance_ledger` (`id`, `seller_id`, `type`, `amount`, `balance_before`, `balance_after`, `reference_type`, `reference_id`, `description`, `created_at`) VALUES
(1, 2, 'sale_credit',     200.0000, 100.0000, 300.0000, 'order',      1, 'Sale commission from order #1',   '2026-05-10 12:00:00'),
(2, 2, 'payout_debit',    150.0000, 300.0000, 150.0000, 'payout',     1, 'Weekly payout processed',         '2026-05-16 10:00:00');

TRUNCATE TABLE `seller_verification`;
INSERT INTO `seller_verification` (`id`, `seller_id`, `document_type`, `document_url`, `status`, `verified_by`, `verified_at`, `rejection_reason`) VALUES
(1, 2, 'id_card',        'https://cdn.example.com/docs/codemaster_id.jpg',       'approved', 1, '2026-04-01 12:00:00', NULL),
(2, 1, 'business_license','https://cdn.example.com/docs/admin_business.pdf',      'pending',  NULL,  NULL, NULL);

TRUNCATE TABLE `seller_stats`;
INSERT INTO `seller_stats` (`id`, `seller_id`, `period_type`, `period_date`, `total_sales`, `total_earnings`, `total_orders`, `total_products`, `avg_rating`, `review_count`) VALUES
(1, 2, 'weekly',  '2026-05-11', 1200.00, 960.00, 15, 3, 4.50, 2),
(2, 2, 'monthly', '2026-05-01', 4500.00, 3600.00, 55, 3, 4.50, 2);

TRUNCATE TABLE `auth_logs`;
INSERT INTO `auth_logs` (`user_id`, `type`, `ip_address`, `device_fingerprint`, `status`, `details`, `created_at`) VALUES
(3, 'login',        '192.168.1.100', 'fp-chrome-win10-a1b2c3',  'success',   '{"method": "password"}',       '2026-05-30 08:00:00'),
(3, 'failed_login', '10.0.0.55',    NULL,                       'failed',    '{"attempts": 1, "reason": "wrong_password"}', '2026-05-30 08:05:00');

-- ==========================================
-- 2. PRODUCTS & SUBSCRIPTIONS
-- ==========================================

TRUNCATE TABLE `products`;
INSERT INTO `products` (`id`, `seller_id`, `name`, `slug`, `description`, `type`, `base_price`, `sku`, `stock`, `download_limit`, `total_sales`, `version`, `download_url`, `status`, `demo_url`, `docs_url`) VALUES
(1, 2, 'Ultimate CRM Script',       'ultimate-crm',            'Advanced CRM for Agencies with multi-tenant support',       'script',  59.00, 'SKU-CRM-001',  100, 5,  340, '2.3.1', NULL,                                                                                                 'active', 'https://demo.crm.app',     'https://docs.crm.app'),
(2, NULL, 'SaaS Boilerplate',       'saas-boilerplate',        'Laravel SaaS Starter with billing and team management',     'software', 99.00, 'SKU-SAAS-001', 50,  10, 120, '1.5.0', NULL,                                                                                                 'active', 'https://demo.saas.dev',    'https://docs.saas.dev'),
(3, 2, 'Desktop Inventory Manager', 'desktop-inventory-manager','Offline inventory management desktop application',           'desktop', 149.00, 'SKU-DESK-001', 25,  3,  55,  '2.1.0', 'https://downloads.licensepro.com/inventory-manager-2.1.0.exe', 'active', NULL,                       'https://docs.licensepro.com/inventory');

TRUNCATE TABLE `product_hardware_requirements`;
INSERT INTO `product_hardware_requirements` (`product_id`, `os_name`, `os_version_min`, `cpu_cores_min`, `memory_mb_min`, `disk_mb_min`, `additional_notes`) VALUES
(3, 'Windows', '10', 2, 4096, 500, 'SSD recommended'),
(3, 'macOS',   '12',  2, 4096, 500, 'Apple Silicon or Intel');

TRUNCATE TABLE `subscription_plans`;
INSERT INTO `subscription_plans` (`id`, `name`, `code`, `duration_months`, `max_activations`, `price_monthly`, `price_yearly`, `features`) VALUES
(1, 'Starter',       'starter',       1,  1,   9.99,   99.00, '["1 domain", "Basic support", "Community access"]'),
(2, 'Professional',  'professional', 12, 5,  29.99,  299.00, '["5 domains", "Priority support", "API access", "Advanced analytics"]'),
(3, 'Enterprise',    'enterprise',   12, 99, 99.99,  999.00, '["Unlimited domains", "Dedicated support", "API access", "White-label", "SLA guarantee"]');

TRUNCATE TABLE `user_subscriptions`;
INSERT INTO `user_subscriptions` (`user_id`, `plan_id`, `product_id`, `status`, `start_date`, `end_date`, `trial_ends_at`) VALUES
(3, 2, 1, 'active',    '2026-05-01 00:00:00', '2027-05-01 00:00:00', NULL),
(3, 1, 3, 'cancelled', '2026-04-01 00:00:00', '2026-05-01 00:00:00', '2026-04-15 00:00:00');

-- ==========================================
-- 3. BILLING
-- ==========================================

TRUNCATE TABLE `invoices`;
INSERT INTO `invoices` (`user_id`, `order_id`, `subscription_id`, `invoice_number`, `total`, `tax`, `status`, `created_at`) VALUES
(3, 1, 1, 'INV-2026-001', 328.90, 29.90, 'paid',   '2026-05-01 00:00:00'),
(3, 2, 2, 'INV-2026-002',   9.99,  0.00, 'void',   '2026-04-01 00:00:00');

TRUNCATE TABLE `payments`;
INSERT INTO `payments` (`invoice_id`, `gateway`, `transaction_id`, `amount`, `status`, `meta`, `created_at`) VALUES
(1, 'stripe',  'pi_stripe_39182931293', 299.00, 'success', '{"card_brand": "Visa", "last4": "4242"}',  '2026-05-01 00:00:00'),
(1, 'stripe',  'pi_stripe_39182931294',  29.90, 'success', '{"card_brand": "Visa", "last4": "4242"}',  '2026-05-01 00:00:00');

-- ==========================================
-- 4. ORDERS & COMMERCE
-- ==========================================



TRUNCATE TABLE `coupons`;
INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_order_amount`, `max_uses`, `used_count`, `starts_at`, `expires_at`, `is_active`) VALUES
(1, 'WELCOME20',  'percentage',   20.00, 0,   100, 2,  '2026-01-01 00:00:00', '2027-01-01 00:00:00', TRUE),
(2, 'FLAT10',     'fixed_amount', 10.00, 50, 50,  0,  '2026-01-01 00:00:00', '2026-12-31 00:00:00', TRUE),
(3, 'EXPIRED50',  'percentage',   50.00, 0,   10, 10, '2025-01-01 00:00:00', '2025-12-31 00:00:00', FALSE);

TRUNCATE TABLE `carts`;
INSERT INTO `carts` (`id`, `user_id`, `coupon_id`, `subtotal`, `tax`, `total`) VALUES
(1, 3, NULL, 149.00, 14.90, 163.90),
(2, 3, 1,     59.00,  5.90,  51.82);

TRUNCATE TABLE `cart_items`;
INSERT INTO `cart_items` (`cart_id`, `product_id`, `plan_id`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 3, NULL, 1, 149.00, 149.00),
(2, 1, 2,    1,  59.00,  59.00);

TRUNCATE TABLE `orders`;
INSERT INTO `orders` (`id`, `user_id`, `order_number`, `status`, `subtotal`, `tax`, `discount_total`, `total`, `currency`, `billing_address_id`, `coupon_id`, `api_client_id`, `customer_notes`, `ip_address`, `user_agent`, `paid_at`, `cancelled_at`, `created_at`) VALUES
(1, 3, 'ORD-2026-00001', 'completed', 299.00, 29.90, 0.00, 328.90, 'USD', 1, NULL, 1, NULL,                                      '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',  '2026-05-01 00:05:00', NULL,                 '2026-05-01 00:00:00'),
(2, 3, 'ORD-2026-00002', 'cancelled',  9.99,  0.00, 0.00,   9.99, 'USD', 1, NULL, 2, 'Requested cancellation via support chat',   '10.0.0.55',     'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X)',         NULL,                    '2026-04-01 00:05:00', '2026-04-01 00:00:00'),
(3, 2, 'ORD-2026-00003', 'pending',   59.00,  5.90, 0.00,  64.90, 'USD', 3, NULL, NULL, NULL,                                    '203.0.113.50',  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36', NULL, NULL,                 '2026-05-30 12:00:00');

TRUNCATE TABLE `order_items`;
INSERT INTO `order_items` (`order_id`, `product_id`, `plan_id`, `item_type`, `name`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 1, 2, 'subscription', 'Ultimate CRM Script (Professional Plan)',  1, 299.00, 299.00),
(2, 3, 1, 'subscription', 'Desktop Inventory Manager (Starter Plan)', 1,   9.99,   9.99),
(3, 2, NULL, 'product',    'SaaS Boilerplate',                         1,  59.00,  59.00);

TRUNCATE TABLE `order_item_metadata`;
INSERT INTO `order_item_metadata` (`order_item_id`, `license_id`, `meta_key`, `meta_value`) VALUES
(1, 1, 'license_key',       'LIC-PRO-2026-8888'),
(1, 1, 'subscription_id',   '1'),
(1, 1, 'activation_count',  '2'),
(2, 2, 'license_key',       'LIC-DESKTOP-2026-9999'),
(2, 2, 'subscription_id',   '1'),
(3, NULL, 'notes',          'Awaiting payment confirmation to generate license');

TRUNCATE TABLE `order_status_history`;
INSERT INTO `order_status_history` (`order_id`, `from_status`, `to_status`, `changed_by`, `api_client_id`, `reason`, `created_at`) VALUES
(1, NULL,        'pending',    NULL, 1, 'Order placed via API',                     '2026-05-01 00:00:00'),
(1, 'pending',   'confirmed',  NULL, NULL, 'Payment received',                      '2026-05-01 00:05:00'),
(1, 'confirmed', 'processing', NULL, NULL, 'Subscription activated',                '2026-05-01 00:06:00'),
(1, 'processing','completed',  NULL, NULL, 'License generated and provisioned',     '2026-05-01 00:10:00'),
(2, NULL,        'pending',    NULL, 2, 'Order placed via API staging',             '2026-04-01 00:00:00'),
(2, 'pending',   'cancelled',  3,    NULL, 'User requested cancellation',            '2026-04-01 00:05:00'),
(3, NULL,        'pending',    NULL, NULL, 'Order placed via web',                  '2026-05-30 12:00:00');

TRUNCATE TABLE `refunds`;
INSERT INTO `refunds` (`order_id`, `payment_id`, `amount`, `reason`, `status`, `processed_by`, `created_at`) VALUES
(2, NULL, 9.99, 'Order cancelled before payment — auto refund initiated', 'approved', 1, '2026-04-01 12:00:00'),
(1, 1, 328.90, 'Customer requested full refund due to installation issues', 'completed', 1, '2026-05-05 14:00:00');

-- ==========================================
-- 5. LICENSING
-- ==========================================

TRUNCATE TABLE `api_clients`;
INSERT INTO `api_clients` (`user_id`, `name`, `api_key`, `api_secret`, `status`, `rate_limit`) VALUES
(3, 'John Production App',    'api-prod-a1b2c3d4e5f6g7h8',  'sk_live_xxxxxxxxxxxxxx',  'active',   60),
(3, 'John Staging App',       'api-stage-i9j0k1l2m3n4o5p6', 'sk_test_yyyyyyyyyyyyyy',  'active',  120);

TRUNCATE TABLE `licenses`;
INSERT INTO `licenses` (`id`, `user_id`, `product_id`, `api_client_id`, `subscription_id`, `license_key`, `api_key`, `status`, `expires_at`, `max_activations`, `current_activations`) VALUES
(1, 3, 1, 1, 1, 'LIC-PRO-2026-8888',    'license-api-key-1111-2222-3333', 'active',    '2027-05-01 00:00:00', 5, 2),
(2, 3, 3, 1, 1, 'LIC-DESKTOP-2026-9999','license-api-key-4444-5555-6666', 'active',    '2027-05-01 00:00:00', 3, 1),
(3, 2, 2, 2, 2, 'LIC-SAAS-2026-7777',   'license-api-key-7777-8888-9999', 'suspended', '2026-06-01 00:00:00', 1, 0);

TRUNCATE TABLE `license_activations`;
INSERT INTO `license_activations` (`license_id`, `domain`, `hosting_ip`, `status`, `last_verified_at`, `meta`) VALUES
(1, 'crm.johnsbusiness.com',          '103.231.222.15', 'active', '2026-05-30 10:00:00', '{"php_version": "8.2", "db": "MySQL 8.0"}'),
(1, 'staging.crm.johnsbusiness.com',  '103.231.222.16', 'active', '2026-05-29 16:00:00', '{"php_version": "8.1", "db": "MySQL 8.0"}'),
(2, 'localhost',                      '127.0.0.1',      'active', '2026-05-30 12:00:00', '{"os": "Windows 11 Pro"}');

TRUNCATE TABLE `hardware_activations`;
INSERT INTO `hardware_activations` (`id`, `license_id`, `machine_id`, `cpu_id`, `motherboard_serial`, `bios_serial`, `disk_serial`, `mac_address`, `os_name`, `os_version`, `os_architecture`, `cpu_name`, `cpu_cores`, `total_memory`, `system_manufacturer`, `system_model`, `local_ip`, `public_ip`, `status`, `activation_limit`, `required_os_min`, `required_memory_mb`, `required_disk_mb`, `compatibility_status`) VALUES
(1, 2, 'HW-MACHINE-ABC123',  'CPU-INTL-X9K3M',    'MB-ASUS-Z390-XYZ',    'BIOS-AMI-1.2.3',  'DISK-SNV123456',     'AA:BB:CC:DD:EE:FF', 'Windows', '11', 'x64', 'Intel Core i7-9700K', 8, 16384, 'ASUS',      'ROG Strix Z390',  '192.168.1.100', '203.157.123.45', 'active', 3, 'Windows 10', 4096, 500, 'compatible'),
(2, 2, 'HW-MACHINE-DEF456',  'CPU-AMD-R7-5800X',   'MB-GIGA-B550-UVW',    'BIOS-AMI-2.1.0',  'DISK-WDS789012',     'DD:EE:FF:AA:BB:CC', 'Windows', '10', 'x64', 'AMD Ryzen 7 5800X',  8, 32768, 'Gigabyte',  'B550 Aorus Pro', '192.168.1.200', '203.157.123.46', 'active', 3, 'Windows 10', 4096, 500, 'compatible');

TRUNCATE TABLE `hardware_activation_logs`;
INSERT INTO `hardware_activation_logs` (`hardware_activation_id`, `license_id`, `machine_id`, `hardware_snapshot`, `system_specs`, `activation_status`, `vm_detected`, `tamper_detected`, `compatibility_result`, `created_at`) VALUES
(1, 2, 'HW-MACHINE-ABC123', '{"cpu": "Intel Core i7-9700K", "memory_mb": 16384, "disk": "SNV123456"}', '{"os": "Windows 11 x64", "cpu_cores": 8, "total_memory_mb": 16384}', 'success', FALSE, FALSE, 'compatible', '2026-05-30 10:05:00'),
(2, 2, 'HW-MACHINE-DEF456', '{"cpu": "AMD Ryzen 7 5800X", "memory_mb": 32768, "disk": "WDS789012"}', '{"os": "Windows 10 x64", "cpu_cores": 8, "total_memory_mb": 32768}',   'success', FALSE, FALSE, 'compatible', '2026-05-29 18:00:00');

-- ==========================================
-- 6. VERIFICATION & FRAUD
-- ==========================================

TRUNCATE TABLE `verification_logs`;
INSERT INTO `verification_logs` (`activation_id`, `license_key`, `api_key`, `ip_address`, `user_agent`, `request_domain`, `request_ip`, `tier1_api`, `tier2_license`, `tier3_domain`, `tier4_ip`, `tier5_subscription`, `overall_result`, `created_at`) VALUES
(1, 'LIC-PRO-2026-8888', 'license-api-key-1111-2222-3333', '103.231.222.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/91', 'crm.johnsbusiness.com', '103.231.222.15', 'pass', 'pass', 'pass', 'pass', 'pass', 'valid', '2026-05-30 10:00:00'),
(2, 'LIC-PRO-2026-8888', 'license-api-key-1111-2222-3333', '45.12.55.1',     'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Firefox/115', 'unknown-hack.com',     '45.12.55.1',     'pass', 'pass', 'fail', 'fail', 'pass', 'suspicious', '2026-05-30 11:00:00');

TRUNCATE TABLE `fraud_logs`;
INSERT INTO `fraud_logs` (`license_id`, `activation_id`, `ip`, `domain`, `reason`, `severity`, `action_taken`, `created_at`) VALUES
(1, 2, '45.12.55.1',  'unknown-hack.com',    'Domain mismatch with valid key. Possible phishing.',  'medium',   'logged',  '2026-05-30 11:00:00'),
(3, NULL, '88.77.66.55', 'blackhat-forum.ru', 'Stolen license key circulating on dark web forums.',   'critical', 'revoked', '2026-05-28 00:00:00');

-- ==========================================
-- 7. SUPPORT & CHAT
-- ==========================================

TRUNCATE TABLE `tickets`;
INSERT INTO `tickets` (`user_id`, `subject`, `status`, `priority`, `assigned_to`, `category`, `closed_at`, `created_at`) VALUES
(3, 'Cannot install CRM on subdomain',        'open',   'high',   4, 'installation', NULL,                  '2026-05-29 15:00:00'),
(3, 'How to upgrade to Enterprise plan?',      'closed', 'low',   4, 'billing',      '2026-05-26 12:00:00', '2026-05-25 10:00:00'),
(2, 'Commission payout delayed',               'replied','medium',4, 'payouts',      NULL,                  '2026-05-28 09:00:00');

TRUNCATE TABLE `ticket_messages`;
INSERT INTO `ticket_messages` (`ticket_id`, `sender_id`, `message`, `attachments`, `created_at`) VALUES
(1, 3, 'I tried installing on a subdomain but getting a 500 error. Help!',                                    NULL,                                    '2026-05-29 15:00:00'),
(1, 4, 'Please check the PHP version. This script requires PHP 8.1+. Your subdomain is running 7.4.',          NULL,                                    '2026-05-29 16:30:00'),
(2, 3, 'I\'d like to know the steps to upgrade from Professional to Enterprise.',                             NULL,                                    '2026-05-25 10:00:00');

TRUNCATE TABLE `chat_sessions`;
INSERT INTO `chat_sessions` (`user_id`, `status`, `assigned_to`, `created_at`) VALUES
(3, 'closed', 4, '2026-05-30 09:00:00'),
(2, 'open',   4, '2026-05-30 13:00:00');

TRUNCATE TABLE `chat_messages`;
INSERT INTO `chat_messages` (`session_id`, `user_id`, `message`, `is_agent`, `created_at`) VALUES
(1, 3, 'Hi, I need help with activation.',        FALSE, '2026-05-30 09:00:00'),
(1, 4, 'Sure! Let me check your license status.',  TRUE,  '2026-05-30 09:01:00'),
(2, 2, 'My payout is late by 3 days.',             FALSE, '2026-05-30 13:00:00');

TRUNCATE TABLE `bot_configs`;
INSERT INTO `bot_configs` (`id`, `name`, `platform`, `platform_token`, `platform_username`, `webhook_url`, `llm_provider_id`, `llm_system_prompt`, `welcome_message`, `status`, `allowed_user_ids`, `rate_limit_per_minute`, `max_conversation_length`, `settings`) VALUES
(1, 'Support Telegram Bot',     'telegram', '789012:ABC-DEF1234ghIkl-zyx57W2v1u123ew11', 'LicenseProBot',    'https://api.licensepro.com/webhook/telegram', 1, 'You are a helpful support assistant for LicensePro platform.', 'Hello! Welcome to LicensePro support. How can I help you today?', 'active',  '[123456789, 987654321]', 30, 50, '{"parse_mode": "Markdown", "enable_typing": true}'),
(2, 'Announcement Discord Bot', 'discord', 'MTIzNDU2Nzg5MDEyMzQ1Njc4.GhIjKl.MnOpQrStUvWxYz', 'LicensePro Announce', 'https://api.licensepro.com/webhook/discord',      1, 'You are an announcement bot for LicensePro. Provide concise product updates.', 'Welcome to LicensePro announcements!', 'inactive', NULL, 10, 20, '{"presence": "online", "activity_type": "playing"}'),
(3, 'Sales WhatsApp Bot',      'whatsapp', 'whatsapp:sk_live_xxxxxxxxxxxx', 'LicensePro Sales',  NULL,                                                                    2, 'You are a sales assistant helping customers choose the right licensing plan.', 'Hi! Interested in LicensePro? Let me help you find the perfect plan.', 'active', NULL, 20, 30, '{"business_hours_only": true}');

TRUNCATE TABLE `bot_conversations`;
INSERT INTO `bot_conversations` (`user_id`, `bot_config_id`, `message`, `response`, `created_at`) VALUES
(3, 1, 'What is the capital of France?',    'The capital of France is Paris.',                                                                         '2026-05-30 14:00:00'),
(3, 1, 'How do I reset my license key?',    'You can reset your license from the Dashboard > Licenses section.',                                             '2026-05-30 14:05:00'),
(2, 3, 'Tell me about Enterprise pricing.', 'Enterprise plans start at $99.99/month with unlimited domains and dedicated support. Would you like a quote?', '2026-05-31 10:00:00');

-- ==========================================
-- 8. APPLICATION UPDATES
-- ==========================================

TRUNCATE TABLE `application_updates`;
INSERT INTO `application_updates` (`version`, `type`, `changelog`, `created_at`) VALUES
('1.0.0', 'update',         'Initial release of the application.',                   '2026-01-01 00:00:00'),
('2.0.0', 'update',         'Major update: New licensing engine, performance improvements.',  '2026-03-15 00:00:00'),
('1.5.0', 'version_history','Archived version notes from legacy system.',            '2026-02-01 00:00:00');

-- ==========================================
-- 9. CONTENT & CMS
-- ==========================================

TRUNCATE TABLE `posts`;
INSERT INTO `posts` (`id`, `type`, `author_id`, `title`, `slug`, `content`, `excerpt`, `featured_image`, `category_id`, `status`, `published_at`, `view_count`, `og_title`, `og_description`, `twitter_card`, `noindex`, `priority`, `changefreq`, `sitemap_include`, `created_at`) VALUES
(1, 'blog', 1, 'Introducing Version 2.0',      'intro-v2',       '<h1>Version 2.0 is here!</h1><p>We are excited to announce major improvements to the licensing engine.</p>', 'Major improvements to the licensing engine are now available in v2.0.', NULL, 1, 'published', '2026-03-20 10:00:00', 152, 'Introducing LicensePro v2.0', 'Discover the new licensing engine with hardware locking and API improvements.', 'summary_large_image', FALSE, 0.80, 'monthly', TRUE, '2026-03-20 10:00:00'),
(2, 'blog', 1, 'Best Practices for Licensing', 'best-practices', '<p>Follow these guidelines to secure your software products effectively.</p>', 'Secure your software products with these proven licensing guidelines.', NULL, 2, 'published', '2026-04-05 14:00:00', 89,  'Software Licensing Best Practices', 'Tips for protecting your software with effective license management.', 'summary', FALSE, 0.70, 'monthly', TRUE, '2026-04-05 14:00:00'),
(3, 'cms',  1, 'Welcome to Our New CMS',        'welcome-cms',    '<p>We are thrilled to launch our new content management system.</p>', 'Our new CMS is live with powerful content management features.', '/uploads/cms-welcome.jpg', 1, 'published', '2026-05-01 10:00:00', 210, 'Welcome to the New CMS', 'LicensePro launches a powerful new content management system.', 'summary_large_image', FALSE, 0.90, 'weekly', TRUE, '2026-05-01 10:00:00'),
(4, 'cms',  1, 'How to Secure Your License Keys','secure-licenses','<p>Follow these best practices to protect your license keys from theft.</p>', 'Protect your license keys with these security best practices.', NULL, 2, 'published', '2026-05-10 10:00:00', 67,  'License Key Security Guide', 'Essential best practices to keep your license keys safe from unauthorized use.', 'summary', TRUE, 0.50, 'monthly', TRUE, '2026-05-10 10:00:00');

TRUNCATE TABLE `post_tags`;
INSERT INTO `post_tags` (`post_id`, `tag_id`) VALUES
(1, 1), (1, 2),
(2, 1),
(3, 1), (3, 2),
(4, 1), (4, 3);

TRUNCATE TABLE `author_profiles`;
INSERT INTO `author_profiles` (`user_id`, `display_name`, `avatar_url`, `bio`, `website_url`, `twitter_handle`, `github_handle`, `is_public`) VALUES
(1, 'Admin User',   '/avatars/admin.png',   'Platform administrator and content manager.',  'https://licensepro.com',  '@licensepro',  'licensepro-dev',  TRUE),
(4, 'Sarah Connor', '/avatars/sarah.png',   'Technical writer and documentation specialist.', 'https://sarah.dev',       '@sarah_writes', 'sarah-c',         TRUE);

TRUNCATE TABLE `post_comments`;
INSERT INTO `post_comments` (`post_id`, `user_id`, `parent_id`, `author_name`, `author_email`, `body`, `status`, `created_at`) VALUES
(1, 2, NULL, NULL, NULL, 'Great update! When will the API documentation be available?', 'approved', '2026-03-21 08:00:00'),
(1, 1, 1, NULL, NULL, 'Thanks! The API docs will be published next week.', 'approved', '2026-03-21 09:00:00'),
(3, 3, NULL, NULL, NULL, 'The new CMS looks fantastic. Any plans for dark mode?', 'approved', '2026-05-02 14:00:00'),
(3, NULL, NULL, 'Guest User', 'guest@example.com', 'This is a great platform, keep up the good work!', 'pending', '2026-05-03 10:00:00');

TRUNCATE TABLE `post_reactions`;
INSERT INTO `post_reactions` (`post_id`, `user_id`, `reaction`) VALUES
(1, 2, 'like'),
(1, 3, 'love'),
(1, 4, 'clap'),
(3, 2, 'fire'),
(3, 3, 'like'),
(4, 2, 'like');

TRUNCATE TABLE `post_views`;
INSERT INTO `post_views` (`post_id`, `user_id`, `ip_address`, `user_agent`, `viewed_at`) VALUES
(1, 2, '192.168.1.10', 'Mozilla/5.0', '2026-03-20 10:30:00'),
(1, 3, '192.168.1.11', 'Mozilla/5.0', '2026-03-20 11:00:00'),
(3, 2, '192.168.1.10', 'Mozilla/5.0', '2026-05-01 10:05:00'),
(3, 4, '10.0.0.1',     'Chrome/120',   '2026-05-01 12:00:00');

TRUNCATE TABLE `post_media`;
INSERT INTO `post_media` (`post_id`, `file_name`, `file_path`, `file_type`, `file_size`, `is_featured`, `sort_order`) VALUES
(3, 'cms-welcome.jpg',     '/uploads/cms-welcome.jpg',     'image/jpeg', 245000, TRUE,  0),
(3, 'cms-screenshot.png',  '/uploads/cms-screenshot.png',  'image/png',  520000, FALSE, 1),
(4, 'license-security.pdf','/uploads/license-security.pdf','application/pdf', 180000, FALSE, 0);

TRUNCATE TABLE `post_series`;
INSERT INTO `post_series` (`id`, `title`, `slug`, `description`, `author_id`, `status`) VALUES
(1, 'LicensePro Best Practices', 'licensepro-best-practices', 'A comprehensive guide to getting the most out of LicensePro.', 1, 'active');

TRUNCATE TABLE `post_series_items`;
INSERT INTO `post_series_items` (`series_id`, `post_id`, `part_order`, `part_title`) VALUES
(1, 1, 1, 'Introducing the New Features'),
(1, 2, 2, 'Licensing Best Practices');

TRUNCATE TABLE `related_posts`;
INSERT INTO `related_posts` (`post_id`, `related_post_id`, `relation_type`, `weight`) VALUES
(1, 2, 'manual', 10),
(2, 1, 'manual', 10),
(3, 4, 'auto_category', 5),
(4, 3, 'auto_category', 5);

TRUNCATE TABLE `video_galleries`;
INSERT INTO `video_galleries` (`id`, `title`, `slug`, `description`, `author_id`, `status`, `sort_order`) VALUES
(1, 'Getting Started Tutorials', 'getting-started', 'Learn the basics of LicensePro platform setup and configuration.', 1, 'active', 1),
(2, 'Advanced Features',         'advanced',        'Deep dives into licensing automation, API integration, and security.', 1, 'active', 2);

TRUNCATE TABLE `videos`;
INSERT INTO `videos` (`id`, `gallery_id`, `title`, `slug`, `description`, `embed_url`, `thumbnail`, `duration`, `author_id`, `status`, `featured`, `view_count`, `sort_order`) VALUES
(1, 1, 'Platform Overview',            'platform-overview',     'A quick tour of the LicensePro dashboard and key features.',        'https://www.youtube.com/embed/dQw4w9WgXcQ', '/thumbnails/overview.jpg',   240, 1, 'published', TRUE,  1205, 1),
(2, 1, 'Setting Up Your First License','first-license',         'Step-by-step guide to generating and managing your first license.','https://www.youtube.com/embed/abc123def45',  '/thumbnails/first-license.jpg', 480, 1, 'published', FALSE, 834,  2),
(3, 1, 'API Integration Walkthrough',  'api-walkthrough',       'Connect your application to LicensePro APIs in minutes.',          'https://vimeo.com/98765432',                '/thumbnails/api.jpg',          600, 4, 'published', FALSE, 412,  3),
(4, NULL, 'Security Best Practices',   'security-best-practices','Protect your software from piracy and unauthorized use.',           'https://www.youtube.com/embed/xyz789abc00', '/thumbnails/security.jpg',    360, 1, 'published', FALSE, 2100, 0);

TRUNCATE TABLE `video_tags`;
INSERT INTO `video_tags` (`video_id`, `tag_id`) VALUES
(1, 1), (1, 2),
(2, 1), (2, 3),
(3, 1), (3, 3),
(4, 1), (4, 2);

TRUNCATE TABLE `video_guidelines`;
INSERT INTO `video_guidelines` (`video_id`, `step_order`, `title`, `description`, `time_marker`, `image`) VALUES
(1, 1, 'Dashboard Overview',  'The main dashboard shows your license stats, recent activations, and revenue at a glance.',   0,   '/screenshots/dashboard.png'),
(1, 2, 'Navigation Menu',     'Use the left sidebar to access Licenses, Products, Customers, and Settings.',                30,  '/screenshots/nav.png'),
(1, 3, 'Quick Actions',       'The top bar provides quick actions for generating licenses and creating products.',           120, '/screenshots/quick-actions.png'),
(2, 1, 'License Key Format',  'License keys follow the format LIC-PRODUCT-YYYY-NNNN for easy identification.',              0,   NULL),
(2, 2, 'Activation Limits',   'Set max activations per license to control concurrent usage across devices.',                 180, NULL),
(4, 1, 'Hardware Locking',    'Enable hardware locking to bind licenses to specific machine fingerprints.',                  0,   '/screenshots/hardware-lock.png'),
(4, 2, 'Domain Verification', 'Configure domain whitelisting to restrict license usage to approved domains.',               90,  NULL);

TRUNCATE TABLE `release_notes`;
INSERT INTO `release_notes` (`version`, `notes`, `created_at`) VALUES
('2.0.0', 'New licensing engine, improved hardware locking, security patches.', '2026-03-15 10:00:00'),
('2.1.0', 'Added desktop app support, fixed domain verification bug.',          '2026-04-20 10:00:00');

TRUNCATE TABLE `user_guides`;
INSERT INTO `user_guides` (`author_id`, `title`, `slug`, `content`, `status`, `created_at`) VALUES
(4, 'Getting Started with Licensing', 'getting-started', '<p>Step-by-step guide to integrate licensing into your app.</p>', 'published', '2026-02-01 10:00:00'),
(4, 'API Integration Guide',          'api-guide',       '<p>How to use the LicensePro API for automated license management.</p>', 'published', '2026-03-01 10:00:00');

TRUNCATE TABLE `announcements`;
INSERT INTO `announcements` (`title`, `content`, `is_active`, `created_at`) VALUES
('Scheduled Maintenance', 'The platform will be down for maintenance on June 1st from 2-4 AM EST.', TRUE,  '2026-05-25 10:00:00'),
('New Pricing Tiers',     'We are introducing new Enterprise plans with white-label support.',        TRUE,  '2026-05-15 10:00:00');

TRUNCATE TABLE `knowledge_base_articles`;
INSERT INTO `knowledge_base_articles` (`title`, `slug`, `content`, `category`, `status`, `created_at`) VALUES
('How to generate a license key',       'generate-license-key',      '<p>Navigate to Licenses &gt; Generate Key...</p>',       'Licensing',       'published', '2026-01-10 10:00:00'),
('Troubleshooting activation failures', 'troubleshoot-activation',   '<p>If activation fails, check your hardware ID format.</p>', 'Troubleshooting', 'published', '2026-02-20 10:00:00');

TRUNCATE TABLE `faq_items`;
INSERT INTO `faq_items` (`question`, `slug`, `answer`, `category`, `position`, `status`, `created_at`) VALUES
('What happens if I exceed my activation limit?',  'exceed-activation-limit',  'Your oldest activation will be automatically deactivated.', 'Licensing', 1, 'published', '2026-01-01 10:00:00'),
('How do I cancel my subscription?',               'cancel-subscription',      'Go to Settings > Billing and click Cancel Subscription.',    'Billing',   2, 'published', '2026-01-01 10:00:00'),
('Can I transfer my license to another domain?',   'transfer-license-domain',  'Yes, from the Dashboard > Licenses > Transfer.',             'Licensing', 3, 'published', '2026-01-01 10:00:00');

-- ==========================================
-- 10. CMS
-- ==========================================

TRUNCATE TABLE `cms_categories`;
INSERT INTO `cms_categories` (`id`, `name`, `slug`, `description`) VALUES
(1, 'News',        'news',        'Company news and announcements'),
(2, 'Tutorials',   'tutorials',   'How-to guides and walkthroughs');

TRUNCATE TABLE `cms_tags`;
INSERT INTO `cms_tags` (`id`, `name`, `slug`) VALUES
(1, 'licensing',    'licensing'),
(2, 'security',     'security'),
(3, 'api',          'api');

TRUNCATE TABLE `cms_pages`;
INSERT INTO `cms_pages` (`id`, `title`, `slug`, `content`, `status`, `meta_title`, `meta_description`, `noindex`, `priority`, `changefreq`, `sitemap_include`, `published_at`, `scheduled_for`, `author_id`, `created_at`) VALUES
(1, 'Home',    'home',    '<h1>Welcome to LicensePro</h1><p>Your complete licensing solution.</p>',     'published', 'Home | LicensePro', 'Complete software licensing platform with hardware locking, API integration, and subscription management.', FALSE, 1.00, 'daily', TRUE, '2026-01-01 10:00:00', NULL,     1, '2026-01-01 10:00:00'),
(2, 'About',   'about',   '<h1>About Us</h1><p>We are a leading provider of software licensing solutions.</p>', 'published', 'About | LicensePro', 'Learn about the team behind LicensePro and our mission to simplify software licensing.', FALSE, 0.70, 'monthly', TRUE, '2026-01-01 10:00:00', NULL,     1, '2026-01-01 10:00:00'),
(3, 'Contact', 'contact', '<h1>Contact</h1><p>Email: support@licensepro.com</p>',                     'published', 'Contact Us | LicensePro', 'Get in touch with the LicensePro team for support, sales, and partnership inquiries.', FALSE, 0.60, 'monthly', TRUE, '2026-01-01 10:00:00', NULL,     1, '2026-01-01 10:00:00');

TRUNCATE TABLE `cms_menus`;
INSERT INTO `cms_menus` (`id`, `name`, `slug`) VALUES
(1, 'Main Navigation', 'main-nav'),
(2, 'Footer Links',    'footer-links');

TRUNCATE TABLE `cms_menu_items`;
INSERT INTO `cms_menu_items` (`menu_id`, `title`, `url`, `target`, `position`) VALUES
(1, 'Home',    '/',           '_self', 1),
(1, 'About',   '/about',      '_self', 2),
(1, 'Contact', '/contact',    '_self', 3),
(2, 'Privacy Policy',  '/privacy',  '_blank', 1),
(2, 'Terms of Service','/terms',    '_blank', 2);

TRUNCATE TABLE `cms_widgets`;
INSERT INTO `cms_widgets` (`name`, `slug`, `description`) VALUES
('Recent Posts',   'recent-posts',   'Displays the most recent CMS posts'),
('Newsletter Signup', 'newsletter',  'Email subscription form widget');

TRUNCATE TABLE `cms_banners`;
INSERT INTO `cms_banners` (`title`, `image`, `link`, `status`, `position`) VALUES
('Spring Sale — 30% Off',   '/images/banners/spring-sale.jpg',   '/pricing',  'published', 1),
('New Feature: Desktop App','/images/banners/desktop-app.jpg',   '/features',  'published', 2);

TRUNCATE TABLE `cms_testimonials`;
INSERT INTO `cms_testimonials` (`name`, `position`, `company`, `content`, `status`) VALUES
('Alice Johnson',  'CTO',  'TechCorp Inc.',    'LicensePro transformed how we manage software licensing. Highly recommended!',  'published'),
('Bob Williams',   'CEO',  'StartupXYZ',       'The hardware locking feature saved us from piracy. Excellent product.',         'published');

TRUNCATE TABLE `cms_settings`;
INSERT INTO `cms_settings` (`key`, `value`, `group`) VALUES
('site_title',       'LicensePro',            'general'),
('meta_description', 'Software Licensing Platform', 'seo'),
('ga_tracking_id',   'G-XXXXXXXXXX',          'analytics'),
('primary_color',    '#3490dc',               'general'),
('facebook_url',     'https://facebook.com/licensepro', 'social');

TRUNCATE TABLE `cms_social_links`;
INSERT INTO `cms_social_links` (`platform`, `url`, `position`) VALUES
('Twitter',  'https://twitter.com/licensepro', 1),
('LinkedIn', 'https://linkedin.com/company/licensepro', 2),
('GitHub',   'https://github.com/licensepro',  3);

TRUNCATE TABLE `cms_footers`;
INSERT INTO `cms_footers` (`content`) VALUES
('<p>&copy; 2026 LicensePro. All rights reserved.</p>'),
('<p>Built with care for developers worldwide.</p>');

TRUNCATE TABLE `cms_headers`;
INSERT INTO `cms_headers` (`content`) VALUES
('<header><nav><!-- Main navigation --></nav></header>'),
('<header class="alternative"><nav><!-- Alternative header --></nav></header>');

TRUNCATE TABLE `cms_sidebars`;
INSERT INTO `cms_sidebars` (`content`) VALUES
('<aside><h3>Categories</h3><ul><li>News</li><li>Tutorials</li></ul></aside>'),
('<aside><h3>Tags</h3><div class="tag-cloud"><span>licensing</span></div></aside>');

TRUNCATE TABLE `cms_media_galleries`;
INSERT INTO `cms_media_galleries` (`file_name`, `file_path`, `file_type`, `file_size`) VALUES
('logo.png',         '/uploads/logo.png',         'image/png',   24576),
('screenshot.jpg',   '/uploads/screenshot.jpg',   'image/jpeg',  512000),
('guide.pdf',        '/uploads/guide.pdf',        'application/pdf', 1048576);

-- ==========================================
-- 11. THEMES
-- ==========================================

TRUNCATE TABLE `themes`;
INSERT INTO `themes` (`id`, `name`, `slug`, `description`, `version`, `author`, `status`) VALUES
(1, 'Default',    'default',    'The default theme for the website',     '1.1.0', 'LicensePro Team', 'active'),
(2, 'Dark Mode',  'dark-mode',  'A dark-themed alternative skin',        '1.0.0', 'LicensePro Team', 'inactive');

TRUNCATE TABLE `theme_settings`;
INSERT INTO `theme_settings` (`theme_id`, `key`, `value`) VALUES
(1, 'primary_color',   '#3498db'),
(1, 'secondary_color', '#2ecc71'),
(2, 'primary_color',   '#1a1a2e'),
(2, 'secondary_color', '#e94560');

TRUNCATE TABLE `theme_assets`;
INSERT INTO `theme_assets` (`theme_id`, `type`, `path`) VALUES
(1, 'css',   '/themes/default/style.css'),
(1, 'js',    '/themes/default/script.js'),
(1, 'image', '/themes/default/logo.png'),
(2, 'css',   '/themes/dark-mode/style.css');

TRUNCATE TABLE `theme_customizations`;
INSERT INTO `theme_customizations` (`theme_id`, `user_id`, `custom_css`, `custom_js`) VALUES
(1, 3, 'body { background-color: #f0f0f0; }',            'console.log("Custom JS Loaded");'),
(1, 2, 'header { background: linear-gradient(90deg, #3498db, #2ecc71); }', NULL);

TRUNCATE TABLE `theme_usage_logs`;
INSERT INTO `theme_usage_logs` (`theme_id`, `user_id`, `action`, `ip_address`, `created_at`) VALUES
(1, 3, 'activated',   '192.168.1.100', '2026-05-30 10:00:00'),
(1, 3, 'customized',  '192.168.1.100', '2026-05-30 10:15:00'),
(2, 3, 'activated',   '192.168.1.100', '2026-05-30 11:00:00');

TRUNCATE TABLE `theme_update_logs`;
INSERT INTO `theme_update_logs` (`theme_id`, `version_from`, `version_to`, `changelog`) VALUES
(1, '1.0.0', '1.1.0', 'Added new color scheme options and improved responsive layout.'),
(1, '1.1.0', '1.2.0', 'Fixed mobile navigation bug and updated font assets.');

TRUNCATE TABLE `theme_conflicts`;
INSERT INTO `theme_conflicts` (`theme_id`, `conflicting_plugin`, `description`) VALUES
(1, 'SEO Optimizer',     'The SEO Optimizer plugin causes layout issues with the Default theme.'),
(1, 'Analytics Pro',     'Analytics Pro conflicts with Default theme\'s JavaScript event handlers.');

TRUNCATE TABLE `theme_general_settings`;
INSERT INTO `theme_general_settings` (`theme_id`, `setting_key`, `setting_value`) VALUES
(1, 'enable_dark_mode',   'true'),
(1, 'container_width',    '1200px');

TRUNCATE TABLE `theme_generations`;
INSERT INTO `theme_generations` (`id`, `theme_id`, `user_id`, `theme_name`, `theme_description`, `status`, `created_at`) VALUES
(1, 1, 3, 'Custom Blue Theme', 'A custom blue variation of the default theme', 'completed', '2026-05-30 14:00:00'),
(2, 2, 3, 'Midnight Edition',  'A very dark theme for night-time users',        'completed', '2026-05-30 14:30:00');

TRUNCATE TABLE `theme_generation_logs`;
INSERT INTO `theme_generation_logs` (`generation_id`, `message`, `created_at`) VALUES
(1, 'Theme generation started.',              '2026-05-30 14:00:00'),
(1, 'Theme generation completed successfully.','2026-05-30 14:02:00'),
(2, 'Theme generation started.',              '2026-05-30 14:30:00'),
(2, 'Theme generation completed successfully.','2026-05-30 14:32:00');

-- ==========================================
-- 12. LLM INTEGRATION
-- ==========================================

TRUNCATE TABLE `llm_providers`;
INSERT INTO `llm_providers` (`id`, `name`, `api_key`, `base_url`, `status`) VALUES
(1, 'OpenAI',    'sk-openai-xxxx-xxxx-xxxx',  'https://api.openai.com/v1',    'active'),
(2, 'Anthropic', 'sk-ant-xxxx-xxxx-xxxx',     'https://api.anthropic.com/v1', 'active');

TRUNCATE TABLE `llm_provider_settings`;
INSERT INTO `llm_provider_settings` (`provider_id`, `setting_key`, `setting_value`) VALUES
(1, 'model',        'gpt-4o'),
(1, 'max_tokens',   '4096'),
(2, 'model',        'claude-3-opus-20240229'),
(2, 'max_tokens',   '8192');

TRUNCATE TABLE `llm_provider_activity`;
INSERT INTO `llm_provider_activity` (`provider_id`, `user_id`, `activity_type`, `details`, `created_at`) VALUES
(1, 3, 'prompt',  '{"tokens": 150, "model": "gpt-4o"}', '2026-05-30 14:00:00'),
(1, 3, 'response','{"tokens": 450, "model": "gpt-4o"}', '2026-05-30 14:00:05');

TRUNCATE TABLE `llm_provider_usage`;
INSERT INTO `llm_provider_usage` (`provider_id`, `user_id`, `tokens_used`, `created_at`) VALUES
(1, 3, 600,  '2026-05-30 14:00:00'),
(1, 3, 1200, '2026-05-30 15:00:00'),
(2, 3, 800,  '2026-05-30 16:00:00');

TRUNCATE TABLE `llm_provider_logs`;
INSERT INTO `llm_provider_logs` (`provider_id`, `user_id`, `prompt`, `response`, `created_at`) VALUES
(1, 3, 'What is the capital of France?',   'The capital of France is Paris.',                             '2026-05-30 14:00:00'),
(1, 3, 'Explain licensing in simple terms.','Licensing is like renting software instead of buying it outright. The license key is your proof of payment.', '2026-05-30 14:05:00');

-- ==========================================
-- 13. SECURITY & COMPLIANCE
-- ==========================================

TRUNCATE TABLE `security_events`;
INSERT INTO `security_events` (`user_id`, `event_type`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(3, 'login_success',       'User logged in successfully',                         '192.168.1.100', 'Mozilla/5.0 Chrome/91',                   '2026-05-30 08:00:00'),
(3, 'suspicious_ip',       'Login attempt from unrecognized IP address',          '45.33.22.11',   'Mozilla/5.0 Firefox/115',                 '2026-05-30 08:05:00'),
(NULL, 'brute_force_block','Blocked brute force attack on admin login endpoint',  '10.0.0.99',     NULL,                                       '2026-05-29 00:00:00');

TRUNCATE TABLE `vulnerability_scans`;
INSERT INTO `vulnerability_scans` (`scan_type`, `target`, `status`, `results`, `created_at`) VALUES
('full',  'crm.johnsbusiness.com',  'completed', '{"vulnerabilities": [{"id": "VULN-001", "description": "SQL Injection", "severity": "high"}]}', '2026-05-30 15:00:00'),
('quick', 'api.licensepro.com',     'completed', '{"vulnerabilities": []}',                                                                        '2026-05-30 16:00:00');

TRUNCATE TABLE `penetration_tests`;
INSERT INTO `penetration_tests` (`test_type`, `target`, `status`, `findings`, `created_at`) VALUES
('web_app',    'crm.johnsbusiness.com',  'completed', '{"findings": [{"id": "FIND-001", "description": "XSS in contact form", "severity": "medium"}]}', '2026-05-30 16:00:00'),
('external',   'licensepro.com',         'completed', '{"findings": [{"id": "FIND-002", "description": "Open SMTP relay", "severity": "critical"}]}',     '2026-05-30 17:00:00');

TRUNCATE TABLE `compliance_reports`;
INSERT INTO `compliance_reports` (`report_type`, `status`, `findings`, `created_at`) VALUES
('GDPR',   'completed', '{"compliance_status": "non-compliant", "issues": [{"id": "ISSUE-001", "description": "Lack of data export functionality", "severity": "high"}]}', '2026-05-30 17:00:00'),
('PCI-DSS','completed', '{"compliance_status": "compliant", "issues": []}',                                                                                                '2026-05-30 18:00:00');

TRUNCATE TABLE `security_incidents`;
INSERT INTO `security_incidents` (`incident_type`, `description`, `status`, `reported_at`) VALUES
('data_breach',           'Unauthorized access detected on crm.johnsbusiness.com',       'investigating', '2026-05-30 18:00:00'),
('unauthorized_access',   'Suspicious admin panel login from unknown IP',                'resolved',      '2026-05-28 12:00:00');

TRUNCATE TABLE `audit_trails`;
INSERT INTO `audit_trails` (`user_id`, `action`, `entity`, `entity_id`, `activity_type`, `description`, `details`, `old_value`, `new_value`, `ip_address`, `created_at`) VALUES
(3, 'license_activated', 'licenses', 1, NULL,                NULL,                           '{"domain": "crm.johnsbusiness.com"}',           NULL, NULL,                                     '192.168.1.100', '2026-05-30 10:00:00'),
(1, 'user_suspended',    'users',    2, NULL,                NULL,                           '{"reason": "TOS violation", "duration": "7 days"}', NULL, NULL, NULL,                               '2026-05-25 10:00:00'),
(3, NULL,                NULL,     NULL, 'login',             'User logged in',               NULL,              NULL, NULL,                                     '192.168.1.100', '2026-05-30 08:00:00'),
(3, NULL,                NULL,     NULL, 'license_activated', 'Activated license for CRM',    NULL,              NULL, NULL,                                     '192.168.1.100', '2026-05-30 10:00:00'),
(1, 'update',            'products', 1,   NULL,               NULL,                           NULL,              '{"base_price": "49.00"}', '{"base_price": "59.00"}', '10.0.0.1',      '2026-05-01 10:00:00'),
(1, 'delete',            'users',    5,   NULL,               NULL,                           NULL,              '{"name": "Spammer", "email": "spam@example.com"}', NULL, '10.0.0.1', '2026-05-15 10:00:00');

-- ==========================================
-- 14. LOGGING & MONITORING
-- ==========================================

TRUNCATE TABLE `notifications`;
INSERT INTO `notifications` (`user_id`, `type`, `title`, `message`, `data`, `is_read`, `read_at`, `channel`, `action_url`, `action_text`) VALUES
(3, 'system',       'Welcome to LicensePro',           'Welcome! Your account has been created successfully.',        '{"onboarding": true}',     TRUE,  '2026-03-10 14:05:00', 'in_app', '/dashboard',            'Go to Dashboard'),
(3, 'subscription', 'Subscription Renewed',            'Your Professional plan has been renewed successfully.',      '{"plan": "professional"}', FALSE, NULL,                  'email',  '/billing/subscriptions', 'View Subscription'),
(2, 'product',      'New Sale!',                       'Your product "Ultimate CRM Script" was just purchased.',     '{"sale_amount": 59.00}',   FALSE, NULL,                  'in_app', '/seller/products',       'View Product');

-- ==========================================
-- 15. SYSTEM SETTINGS
-- ==========================================

TRUNCATE TABLE `system_settings`;
INSERT INTO `system_settings` (`key`, `value`, `group`) VALUES
('app_name',         'LicensePro',         'general'),
('app_version',      '2.1.0',              'general'),
('maintenance_mode', 'false',              'system'),
('default_language', 'en',                 'localization');

TRUNCATE TABLE `system_logs`;
INSERT INTO `system_logs` (`level`, `message`, `context`, `created_at`) VALUES
('info',    'Application started successfully.',    '{"uptime_seconds": 0}',     '2026-05-30 00:00:00'),
('warning', 'High memory usage detected.',          '{"memory_usage_mb": 4096}',  '2026-05-30 12:00:00'),
('error',   'Database connection pool exhausted.',  '{"pool_size": 10, "active": 10}', '2026-05-29 18:00:00');

TRUNCATE TABLE `email_settings`;
INSERT INTO `email_settings` (`smtp_host`, `smtp_port`, `smtp_username`, `smtp_password`, `from_email`, `from_name`, `encryption`, `is_default`) VALUES
('smtp.sendgrid.net', 587, 'apikey', 'SG.xxxxx', 'noreply@licensepro.com', 'LicensePro', 'tls', TRUE),
('smtp.mailgun.org',  587, 'postmaster@mg.licensepro.com', 'mg-xxxxx', 'noreply@licensepro.com', 'LicensePro (Backup)', 'tls', FALSE);

TRUNCATE TABLE `email_templates`;
INSERT INTO `email_templates` (`name`, `subject`, `body`) VALUES
('welcome_email',        'Welcome to LicensePro!',          '<h1>Welcome!</h1><p>Thank you for joining LicensePro.</p>'),
('license_activated',    'License Activated Successfully',  '<p>Your license {{license_key}} has been activated on {{domain}}.</p>'),
('password_reset',       'Reset Your Password',             '<p>Click <a href="{{link}}">here</a> to reset your password.</p>');

TRUNCATE TABLE `payment_gateways`;
INSERT INTO `payment_gateways` (`id`, `name`, `description`, `is_active`) VALUES
(1, 'Stripe',  'Credit card payments via Stripe',   TRUE),
(2, 'PayPal',  'PayPal payment processing',          TRUE);

TRUNCATE TABLE `payment_gateway_settings`;
INSERT INTO `payment_gateway_settings` (`gateway_id`, `key`, `value`) VALUES
(1, 'publishable_key', 'pk_live_stripe_xxxxx'),
(1, 'secret_key',      'sk_live_stripe_xxxxx'),
(2, 'client_id',       'paypal_client_xxxxx'),
(2, 'client_secret',   'paypal_secret_xxxxx');

TRUNCATE TABLE `redirects`;
INSERT INTO `redirects` (`id`, `old_path`, `new_path`, `status_code`, `is_active`, `hits_count`) VALUES
(1, '/old-license-page', '/licenses',     '301', TRUE, 245),
(2, '/blog/old-post',    '/blog/new-post','301', TRUE, 89),
(3, '/temp-promo',       '/pricing',      '302', FALSE, 0);

TRUNCATE TABLE `slug_history`;
INSERT INTO `slug_history` (`id`, `content_type`, `content_id`, `old_slug`, `new_slug`) VALUES
(1, 'post',     1, 'intro-v1',          'intro-v2'),
(2, 'cms_page', 2, 'about-us',          'about'),
(3, 'product',  1, 'license-manager-v1','license-manager');

TRUNCATE TABLE `structured_data`;
INSERT INTO `structured_data` (`id`, `content_type`, `content_id`, `schema_type`, `json_ld`) VALUES
(1, 'post',     1, 'Article',            '{"@context":"https://schema.org","@type":"Article","headline":"Introducing Version 2.0","datePublished":"2026-03-20"}'),
(2, 'cms_page', 1, 'WebSite',           '{"@context":"https://schema.org","@type":"WebSite","name":"LicensePro","url":"https://licensepro.com"}'),
(3, 'product',  1, 'SoftwareApplication','{"@context":"https://schema.org","@type":"SoftwareApplication","name":"LicensePro","operatingSystem":"Windows,Linux,macOS"}');

TRUNCATE TABLE `seo_analysis`;
INSERT INTO `seo_analysis` (`id`, `content_type`, `content_id`, `score`, `issues`, `word_count`, `readability_score`) VALUES
(1, 'post',     1, 92.50, '[]',                                               1240, 78.30),
(2, 'cms_page', 1, 85.00, '[{"type":"missing_alt","severity":"medium"}]',     520,  65.00),
(3, 'post',     4, 95.00, '[{"type":"noindex","severity":"info"}]',            980,  82.50);

TRUNCATE TABLE `analytics_events`;
INSERT INTO `analytics_events` (`id`, `event_type`, `page_url`, `referrer_url`, `utm_source`, `utm_medium`, `utm_campaign`, `user_agent`, `ip_address`, `session_id`, `user_id`, `post_id`, `page_id`) VALUES
(1, 'pageview',  '/blog/intro-v2',    'https://google.com', 'google', 'organic', 'brand',    'Mozilla/5.0...',      '192.168.1.1', 'abc123', 1, 1, NULL),
(2, 'pageview',  '/contact',          'https://example.com', NULL,     NULL,      NULL,      'Mozilla/5.0...',      '10.0.0.1',    'def456', NULL, NULL, 3),
(3, 'click',     '/pricing',          NULL,                 'twitter','social',  'launch',  'Mozilla/5.0...',      '172.16.0.1',  'ghi789', 2, NULL, NULL);

TRUNCATE TABLE `media_library`;
INSERT INTO `media_library` (`id`, `filename`, `filepath`, `mime_type`, `file_size`, `width`, `height`, `alt_text`, `caption`, `uploaded_by`) VALUES
(1, 'hero-banner.png',      '/uploads/media/hero-banner.png',      'image/png',  245000,  1920, 1080, 'LicensePro platform dashboard',    'Main hero banner for the homepage',      1),
(2, 'api-documentation.pdf','/uploads/media/api-docs.pdf',         'application/pdf', 520000, NULL, NULL, 'API documentation download',     'Complete API reference guide',             1),
(3, 'product-screenshot.jpg','/uploads/media/product-shot.jpg',    'image/jpeg', 180000,  1280, 720,  'License key generation interface','Screenshot of the license generation form', 4);

TRUNCATE TABLE `content_revisions`;
INSERT INTO `content_revisions` (`id`, `content_type`, `content_id`, `title`, `content`, `summary`, `meta`, `created_by`) VALUES
(1, 'post',     1, 'Introducing Version 2.0',      '<h1>Version 2.0 is here!</h1><p>Initial draft of the announcement.</p>',   'Draft version of the v2.0 announcement.',     '{"word_count":450,"editor":"rich"}',  1),
(2, 'post',     1, 'Introducing Version 2.0',      '<h1>Version 2.0 is here!</h1><p>Updated with feature list.</p>',            'Second draft with detailed features.',         '{"word_count":890,"editor":"rich"}',  1),
(3, 'cms_page', 1, 'Home',                        '<h1>Welcome</h1><p>Original homepage content before redesign.</p>',          'Original homepage content.',                   '{"word_count":120,"editor":"rich"}',  4);

TRUNCATE TABLE `cms_page_tags`;
INSERT INTO `cms_page_tags` (`page_id`, `tag_id`) VALUES
(1, 1), (1, 2),
(2, 2),
(3, 3);

TRUNCATE TABLE `form_submissions`;
INSERT INTO `form_submissions` (`id`, `form_key`, `data`, `user_id`, `ip_address`, `user_agent`) VALUES
(1, 'contact', '{"name":"John Doe","email":"john@example.com","message":"I have a question about licensing."}', 1, '192.168.1.1',  'Mozilla/5.0...'),
(2, 'newsletter', '{"email":"jane@example.com","interests":["licensing","api"]}',                                NULL, '203.0.113.1', 'Mozilla/5.0...');

-- ==========================================
-- 16. AUTH, ROLES & PERMISSIONS
-- ==========================================

TRUNCATE TABLE `password_resets`;
INSERT INTO `password_resets` (`user_id`, `token`, `expires_at`, `used_at`, `created_at`) VALUES
(3, '$2y$10$tokenhashpasswordreset1', DATE_ADD('2026-05-30 14:00:00', INTERVAL 1 HOUR), '2026-05-30 14:05:00', '2026-05-30 14:00:00'),
(3, '$2y$10$tokenhashpasswordreset2', DATE_ADD('2026-05-29 10:00:00', INTERVAL 1 HOUR), NULL,                   '2026-05-29 10:00:00');

TRUNCATE TABLE `roles`;
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `is_system`, `organization_id`, `created_at`) VALUES

(1, 'Super Admin',    'super-admin',    'Full access to all system features',             TRUE,  NULL, '2026-01-01 00:00:00'),

(2, 'Support Agent',  'support-agent',  'Access to tickets, chats, and user management',  TRUE,  1,    '2026-01-01 00:00:00'),

(3, 'Seller',         'seller',         'Manage products, view sales, and payouts',        TRUE,  1,    '2026-01-01 00:00:00');

TRUNCATE TABLE `permissions`;
INSERT INTO `permissions` (`id`, `name`, `slug`, `description`, `group`, `organization_id`, `created_at`) VALUES

(1, 'Manage Users',       'manage-users',       'Create, edit, and suspend user accounts',       'users',     NULL, '2026-01-01 00:00:00'),

(2, 'Manage Products',    'manage-products',    'Add, edit, and archive products',               'products',  NULL, '2026-01-01 00:00:00'),

(3, 'Manage Orders',      'manage-orders',      'View and update order statuses',                'orders',    NULL, '2026-01-01 00:00:00'),

(4, 'View Reports',       'view-reports',       'Access analytics and sales reports',             'reports',   NULL, '2026-01-01 00:00:00');

TRUNCATE TABLE `role_permissions`;
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4),
(2, 1), (2, 3),
(3, 2), (3, 3), (3, 4);

TRUNCATE TABLE `user_roles`;
INSERT INTO `user_roles` (`user_id`, `role_id`, `organization_id`, `assigned_at`) VALUES

(1, 1, 1, '2026-01-15 08:00:00'),

(4, 2, 1, '2026-03-15 09:00:00'),

(2, 3, 1, '2026-02-01 10:30:00');

-- ==========================================
-- 17. ORGANIZATIONS & MEMBERS
-- ==========================================

TRUNCATE TABLE `organizations`;
INSERT INTO `organizations` (`id`, `name`, `slug`, `logo_url`, `website`, `status`, `created_at`) VALUES
(1, 'TechCorp Inc.',     'techcorp',      'https://cdn.example.com/logos/techcorp.png',  'https://techcorp.com',     'active',   '2026-01-20 09:00:00'),
(2, 'StartupXYZ',        'startupxyz',    'https://cdn.example.com/logos/startupxyz.png','https://startupxyz.io',    'active',   '2026-03-05 14:00:00');

TRUNCATE TABLE `organization_members`;
INSERT INTO `organization_members` (`organization_id`, `user_id`, `role`, `joined_at`) VALUES
(1, 1, 'owner',  '2026-01-20 09:00:00'),
(1, 3, 'member', '2026-03-10 14:00:00'),
(2, 2, 'owner',  '2026-03-05 14:00:00');

-- ==========================================
-- 18. WISHLISTS & PRODUCT CATEGORIES
-- ==========================================

TRUNCATE TABLE `wishlists`;
INSERT INTO `wishlists` (`user_id`, `product_id`, `created_at`) VALUES
(3, 2, '2026-05-15 10:00:00'),
(3, 1, '2026-05-20 12:00:00'),
(4, 3, '2026-05-25 16:00:00');

TRUNCATE TABLE `product_categories`;
INSERT INTO `product_categories` (`id`, `parent_id`, `name`, `slug`, `description`, `image_url`, `sort_order`) VALUES
(1, NULL, 'CRM Software',     'crm-software',     'Customer relationship management solutions',     '/images/cats/crm.png',      1),
(2, NULL, 'Developer Tools',  'developer-tools',  'Scripts, boilerplates and dev utilities',         '/images/cats/dev.png',      2),
(3, 1,    'Sales CRM',        'sales-crm',        'Sales-focused CRM applications',                  '/images/cats/sales.png',    3);

TRUNCATE TABLE `product_category_items`;
INSERT INTO `product_category_items` (`product_id`, `category_id`) VALUES
(1, 1), (1, 3),
(2, 2),
(3, 1);

TRUNCATE TABLE `product_discounts`;
INSERT INTO `product_discounts` (`product_id`, `name`, `type`, `value`, `max_uses`, `used_count`, `starts_at`, `ends_at`) VALUES
(1, 'Launch Special',  'percentage', 15.00, 50,  12, '2026-05-01 00:00:00', '2026-07-01 00:00:00'),
(3, 'Summer Sale',     'fixed',      25.00, 20,  3,  '2026-06-01 00:00:00', '2026-08-31 00:00:00');

-- ==========================================
-- 19. TAX, SHIPPING & DOWNLOADS
-- ==========================================

TRUNCATE TABLE `tax_rates`;
INSERT INTO `tax_rates` (`id`, `name`, `rate`, `type`, `country`, `region`, `is_default`, `active`) VALUES
(1, 'US Sales Tax',  10.00, 'percentage', 'US', NULL,  TRUE,  TRUE),
(2, 'UK VAT',        20.00, 'percentage', 'GB', NULL,  FALSE, TRUE),
(3, 'DE VAT',        19.00, 'percentage', 'DE', NULL,  FALSE, TRUE);

TRUNCATE TABLE `shipments`;
INSERT INTO `shipments` (`order_id`, `tracking_number`, `carrier`, `status`, `shipped_at`, `delivered_at`, `shipping_data`) VALUES
(1, '1Z999AA10123456784', 'UPS',       'delivered', '2026-05-02 10:00:00', '2026-05-05 14:00:00', '{"weight_kg": 0.5, "service": "ground"}'),
(2, '940011189922345678', 'USPS',      'returned',  '2026-04-02 10:00:00', '2026-04-10 10:00:00', '{"weight_kg": 0.2, "service": "first_class"}');

TRUNCATE TABLE `file_downloads`;
INSERT INTO `file_downloads` (`order_item_id`, `user_id`, `ip_address`, `download_count`, `last_downloaded`, `expires_at`) VALUES
(3, 2, '203.0.113.50', 1, '2026-05-30 12:05:00', '2026-06-29 12:00:00'),
(3, 2, '203.0.113.51', 0, NULL,                  '2026-06-29 12:00:00');

-- ==========================================
-- 20. AFFILIATES & REFERRALS
-- ==========================================

TRUNCATE TABLE `affiliates`;
INSERT INTO `affiliates` (`id`, `user_id`, `code`, `commission_rate`, `total_earned`, `total_paid`, `status`) VALUES
(1, 3, 'JOHN-BUYER-10',   15.00, 29.90, 0.00,  'active'),
(2, 4, 'SARAH-SUPPORT-10',10.00, 5.00,  5.00,  'active');

TRUNCATE TABLE `referrals`;
INSERT INTO `referrals` (`affiliate_id`, `referred_id`, `order_id`, `commission`, `status`, `created_at`) VALUES
(1, 2, 3, 8.85,  'pending',   '2026-05-30 12:00:00'),
(2, 1, 1, 5.00,  'paid',      '2026-05-01 00:00:00');

-- ==========================================
-- 21. 2FA, WEBHOOKS & FEATURE FLAGS
-- ==========================================

TRUNCATE TABLE `content_blocks`;
INSERT INTO `content_blocks` (`key`, `title`, `content`, `type`, `locations`, `active`) VALUES
('footer-about',      'Footer About Text',  '<p>LicensePro is the leading software licensing platform.</p>',   'html', '["footer"]',   TRUE),
('home-hero-text',    'Home Hero Text',     '{"headline": "Simplify Software Licensing", "subtext": "Protect, manage, and license your software with ease."}', 'json', '["home"]',     TRUE),
('cookie-consent',    'Cookie Consent',     '<p>We use cookies to improve your experience.</p>',               'html', '["global"]',   TRUE);

TRUNCATE TABLE `user_2fa`;
INSERT INTO `user_2fa` (`user_id`, `secret`, `method`, `backup_codes`, `is_enabled`, `verified_at`) VALUES
(1, 'JBSWY3DPEHPK3PXP', 'totp', '["ABCD-1234-EFGH-5678","IJKL-9012-MNOP-3456","QRST-7890-UVWX-1234","YZ12-3456-7890-ABCD"]', TRUE,  '2026-01-16 08:00:00'),
(3, NULL,                'email', NULL,                                                                                  FALSE, NULL);

TRUNCATE TABLE `webhooks`;
INSERT INTO `webhooks` (`id`, `name`, `url`, `events`, `secret`, `is_active`, `last_triggered_at`) VALUES
(1, 'Slack Order Notifications',  'https://hooks.slack.com/services/T00/B00/xxxx',   '["order.completed","order.refunded"]',        'whsec_slack_xxxx',  TRUE,  '2026-05-30 12:05:00'),
(2, 'Discord License Alerts',    'https://discord.com/api/webhooks/123/xxx',         '["license.activated","license.suspicious"]',  'whsec_discord_xxx', TRUE,  '2026-05-30 10:00:00');

TRUNCATE TABLE `feature_flags`;
INSERT INTO `feature_flags` (`name`, `key`, `description`, `enabled`, `conditions`) VALUES
('Dark Mode',           'dark_mode',           'Enable dark mode theme across the application',                TRUE,  '{"percentage": 100}'),
('AI Chat Assistant',   'ai_chat_assistant',   'Enable AI-powered chat bot for support',                       TRUE,  '{"percentage": 50, "user_ids": [3,4]}'),
('New Checkout Flow',   'new_checkout_flow',   'Redirect users to the redesigned checkout experience',          FALSE, NULL);

TRUNCATE TABLE `user_addresses`;
INSERT INTO `user_addresses` (`user_id`, `label`, `first_name`, `last_name`, `full_name`, `phone`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country`, `is_default_billing`, `is_default_shipping`) VALUES
(1, 'Home',     NULL,      NULL,      'Admin User',   '+12025551234', '123 Main St',               NULL,            'New York',      'NY', '10001', 'United States', TRUE,  TRUE),
(2, 'Office',   NULL,      NULL,      'John Doe',     '+12025551235', '456 Oak Ave',               NULL,            'Los Angeles',   'CA', '90001', 'United States', TRUE,  FALSE),
(3, 'Home',     NULL,      NULL,      'Jane Smith',   '+12025551236', '789 Pine Rd',               NULL,            'Chicago',       'IL', '60601', 'United States', FALSE, TRUE),
(3, 'Home',     'John',    'Buyer',   'John Buyer',   '+1-555-0102',  '123 Main Street, Apt 4B',   NULL,            'New York',      'NY', '10001', 'US', TRUE,  TRUE),
(3, 'Office',   'John',    'Buyer',   'John Buyer',   '+1-555-0199',  '456 Corporate Blvd, Suite 200', NULL,         'New York',      'NY', '10002', 'US', TRUE,  FALSE),
(2, 'Home',     'CodeMaster','Dev',   'CodeMaster Dev','+1-555-0101', '789 Developer Lane',          NULL,            'San Francisco', 'CA', '94105', 'US', TRUE,  TRUE);

INSERT INTO `coupons` (`code`, `type`, `value`, `min_order_amount`, `max_uses`, `used_count`, `starts_at`, `expires_at`, `is_active`) VALUES
('LAUNCH20',     'percentage',   20.00, 10.00, 100,  5,  '2026-01-01 00:00:00', '2026-12-31 23:59:59', TRUE),
('SAVE10',       'fixed_amount', 10.00, NULL,  500, 42,  '2026-03-01 00:00:00', '2026-09-30 23:59:59', TRUE),
('VIP50',        'percentage',   50.00, 50.00, 10,   0,  '2026-06-01 00:00:00', '2026-07-31 23:59:59', TRUE),
('EXPIRED10',    'percentage',   10.00, NULL,  NULL, 0,  '2025-01-01 00:00:00', '2025-06-30 23:59:59', FALSE);

TRUNCATE TABLE `wishlist_items`;
INSERT INTO `wishlist_items` (`wishlist_id`, `product_id`, `notes`) VALUES
(1, 1, 'Wait for sale'),
(1, 2, NULL),
(2, 3, 'For brother'),
(3, 1, NULL);

INSERT INTO `carts` (`user_id`, `coupon_id`, `subtotal`, `tax`, `total`, `expires_at`) VALUES
(3, NULL, 0.00, 0.00, 0.00, DATE_ADD(NOW(), INTERVAL 7 DAY)),
(3, NULL, 0.00, 0.00, 0.00, DATE_ADD(NOW(), INTERVAL 1 DAY));

INSERT INTO `cart_items` (`cart_id`, `product_id`, `plan_id`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 1, NULL, 1, 59.00, 59.00),
(1, 2, NULL, 2, 29.00, 58.00),
(2, 3, NULL, 1, 99.00, 99.00);

TRUNCATE TABLE `reviews`;
INSERT INTO `reviews` (`reviewable_type`, `reviewable_id`, `user_id`, `order_id`, `rating`, `title`, `body`, `is_approved`, `is_verified_purchase`, `created_at`) VALUES
('product', 1, 2, 1, 5, 'Excellent script!', 'This is exactly what I needed. Easy to install and configure.', TRUE,  TRUE,  '2026-04-28 10:00:00'),
('product', 1, 3, 2, 4, 'Great but needs docs', 'Works well but documentation could be better.', TRUE,  TRUE,  '2026-04-29 10:00:00'),
('product', 2, 3, 3, 3, 'Decent plugin', 'Does the job but has some bugs.', FALSE, TRUE,  '2026-05-01 10:00:00'),
('product', 3, 3, 2, 5, 'Best SaaS platform', 'Highly recommend for any business.', TRUE,  TRUE,  '2026-04-30 10:00:00'),
('seller', 2, 3, 1, 5, NULL, 'Excellent service, instant license delivery!', TRUE,  FALSE, '2026-05-12 14:00:00'),
('seller', 2, 4, 1, 4, NULL, 'Good product, would recommend.',              TRUE,  FALSE, '2026-05-13 09:30:00');

INSERT INTO `refunds` (`order_id`, `payment_id`, `amount`, `reason`, `status`, `processed_by`) VALUES
(3, NULL, 29.00, 'Item not as described', 'processed', 1),
(2, NULL, 9.99, 'Changed mind', 'pending', NULL);

TRUNCATE TABLE `return_requests`;
INSERT INTO `return_requests` (`order_id`, `order_item_id`, `user_id`, `reason`, `description`, `status`, `resolution`, `admin_notes`, `processed_by`, `processed_at`) VALUES
(3, 3, 4, 'not_as_described', 'The product does not match the screenshots shown.', 'received', 'refund', 'Item received in good condition. Processing refund.', 1, '2026-05-28 16:00:00'),
(2, 2, 3, 'changed_mind', 'I no longer need this software.', 'approved', 'store_credit', 'Approved store credit as requested by customer.', 1, '2026-05-25 10:00:00');

TRUNCATE TABLE `user_payment_methods`;
INSERT INTO `user_payment_methods` (`user_id`, `gateway_id`, `method_type`, `gateway_token`, `display_name`, `last_four`, `expiry_month`, `expiry_year`, `card_brand`, `is_default`) VALUES
(1, NULL, 'card',   'tok_visa_4242',      'Visa ending in 4242', '4242', '12', '2028', 'Visa',       TRUE),
(1, NULL, 'paypal', 'paypal_merchant_1',  'My PayPal',           NULL,   NULL, NULL,   NULL,        FALSE),
(2, NULL, 'card',   'tok_mc_5555',        'Mastercard Business', '5555', '08', '2027', 'Mastercard', TRUE),
(3, NULL, 'card',   'tok_amex_3000',      'Amex Platinum',      '3000', '03', '2029', 'Amex',       TRUE);

TRUNCATE TABLE `personal_access_tokens`;
INSERT INTO `personal_access_tokens` (`user_id`, `name`, `token`, `abilities`, `expires_at`) VALUES
(1, 'API Integration',    'pat_api_integration_abc123def456', '["read","write"]',     NULL),
(2, 'CI/CD Pipeline',     'pat_cicd_ghi789jkl012',           '["read"]',             '2027-01-01 00:00:00'),
(3, 'Mobile App Access',  'pat_mobile_mno345pqr678',         '["read","write","push"]', NULL);

TRUNCATE TABLE `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('sess_admin_001', 1, '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120', '{"data":"session_data"}', 1717200000),
('sess_user_002',  3, '10.0.0.50',    'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0) Mobile/15E148', '{"data":"session_data"}', 1717199000),
('sess_guest_003', NULL, '172.16.0.10', 'Mozilla/5.0 (Linux; Android 14) SamsungBrowser/24', '{"data":"guest_data"}', 1717198000);

TRUNCATE TABLE `social_accounts`;
INSERT INTO `social_accounts` (`user_id`, `provider`, `provider_id`, `provider_email`, `avatar_url`) VALUES
(1, 'google', 'google_uid_12345', 'admin@gmail.com',        'https://lh3.googleusercontent.com/a/photo1'),
(3, 'github', 'github_uid_67890', 'jane@github.com',        'https://avatars.githubusercontent.com/u/67890');

TRUNCATE TABLE `user_devices`;
INSERT INTO `user_devices` (`user_id`, `platform`, `device_token`, `device_name`, `fingerprint`, `ip_address`, `user_agent`, `is_active`, `last_active_at`, `last_notified_at`) VALUES
(1, 'web',    'web_push_token_admin_001',    'Chrome on Desktop',            NULL, NULL, NULL, TRUE,  NULL, NULL),
(3, 'ios',    'apns_token_iphone_jane_001',  'Jane\'s iPhone 15',            NULL, NULL, NULL, TRUE,  NULL, NULL),
(4, 'android','fcm_token_pixel_001',         'Pixel 8 Pro',                  NULL, NULL, NULL, TRUE,  NULL, NULL),
(1, 'android','fcm_token_tablet_001',        'Samsung Tablet',               NULL, NULL, NULL, FALSE, NULL, NULL),
(3, 'web',    'fp_token_chrome_001',         'Chrome on Windows',            'fp-chrome-win10-a1b2c3',  '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', TRUE, '2026-05-30 14:30:00', NULL),
(3, 'web',    'fp_token_firefox_002',        'Firefox on Windows',           'fp-firefox-win10-d4e5f6', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:115.0) Gecko/20100101 Firefox/115.0', TRUE, '2026-05-29 09:15:00', NULL);

TRUNCATE TABLE `sms_providers`;
INSERT INTO `sms_providers` (`name`, `provider`, `api_key`, `api_secret`, `from_number`, `is_active`, `is_default`, `priority`) VALUES
('Twilio Main',      'twilio', 'ACxxxxxxxxxx', 'auth_token_xxx', '+12025551234', TRUE,  TRUE,  1),
('AWS SNS Backup',   'aws_sns', 'AKIAxxxxxxxx', 'aws_secret_xxx', '+12025555678', TRUE,  FALSE, 2);

TRUNCATE TABLE `sms_templates`;
INSERT INTO `sms_templates` (`name`, `category`, `body`, `variables`) VALUES
('welcome_message', 'transactional', 'Welcome {{name}}! Thank you for joining {{platform}}. Start exploring now.', '["name","platform"]'),
('promo_offer',     'promotional',   'Hey {{name}}, get {{discount}}% off on your next purchase! Offer ends {{expiry}}.', '["name","discount","expiry"]'),
('order_confirmed', 'transactional', 'Hi {{name}}, your order #{{order_id}} has been confirmed. Total: ${{amount}}.', '["name","order_id","amount"]');

TRUNCATE TABLE `sms_campaigns`;
INSERT INTO `sms_campaigns` (`name`, `message_body`, `sms_template_id`, `target_type`, `target_roles`, `filter_criteria`, `scheduled_at`, `status`, `total_recipients`, `success_count`, `fail_count`, `created_by`) VALUES
('Summer Sale 2026',          'Limited-time summer sale! Get up to 50% off on all premium scripts. Visit {{url}} now!',   NULL, 'all',      NULL,                                 NULL,                           '2026-07-01 09:00:00', 'draft',     0, 0, 0, 1),
('Welcome New Users',         'Welcome {{name}}! Your account is ready. Check out our getting-started guide.',            NULL, 'selected', '["admin","seller"]',                '{"status": "active"}',         NULL,                   'sent',      4, 4, 0, 1),
('Subscription Renewal Alert', 'Hi {{name}}, your subscription renews on {{date}}. Update payment to avoid interruption.', 2,   'role',     '["user","seller"]',                 NULL,                           '2026-06-15 10:00:00', 'scheduled', 0, 0, 0, 2),
('Flash Deal Alert',          'Flash sale! {{product}} at {{price}} — only for the next 24 hours!',                       NULL, 'all',      NULL,                                 '{"locale": "en"}',             '2026-06-20 14:30:00', 'draft',     0, 0, 0, 3);

TRUNCATE TABLE `sms_campaign_recipients`;
INSERT INTO `sms_campaign_recipients` (`campaign_id`, `user_id`, `phone`, `status`, `provider_message_id`, `provider_id`, `sent_at`, `delivered_at`) VALUES
(2, 1, '+12025551234', 'delivered', 'SMxxx001', 1, '2026-05-28 10:05:00', '2026-05-28 10:05:03'),
(2, 2, '+12025551235', 'delivered', 'SMxxx002', 1, '2026-05-28 10:05:00', '2026-05-28 10:05:02'),
(2, 3, '+12025551236', 'sent',      'SMxxx003', 1, '2026-05-28 10:05:01', NULL),
(2, 4, '+12025551237', 'failed',    'SMxxx004', 2, '2026-05-28 10:05:02', NULL);

TRUNCATE TABLE `sms_automations`;
INSERT INTO `sms_automations` (`name`, `trigger_type`, `event_name`, `cron_expression`, `timezone`, `target_type`, `target_roles`, `filter_criteria`, `message_body`, `sms_template_id`, `is_active`, `total_sent`, `created_by`) VALUES
('Welcome Series - New Registration', 'event',   'user.registered',  NULL,              NULL, 'event_context', NULL,              NULL, NULL,                             1, TRUE, 1247, 1),
('Weekly Promo Digest',               'schedule', NULL,              '0 10 * * 1',     'UTC', 'all',           NULL,              NULL, 'Check out our latest deals this week! {{url}}', NULL, TRUE, 52,   2),
('Abandoned Cart Reminder',           'event',   'cart.abandoned',   NULL,              NULL, 'event_context', NULL,              '{"hours_since": 24}', 'You left items in your cart! Complete your order now with {{discount}}% off.', 2, TRUE, 389, 1),
('Monthly Renewal Notice',           'schedule', NULL,              '0 8 28 * *',      'UTC', 'role',         '["user","seller"]', '{"status": "active"}', 'Your subscription renews in 3 days. Keep your account active!', NULL, FALSE, 0, 2);

TRUNCATE TABLE `sms_logs`;
INSERT INTO `sms_logs` (`provider_id`, `campaign_id`, `recipient_id`, `direction`, `request_payload`, `response_payload`, `http_status`, `provider_message_id`, `error_message`) VALUES
(1, 2, 1, 'outgoing', '{"To":"+12025551234","From":"+12025551234","Body":"Welcome..."}', '{"sid":"SMxxx001","status":"sent"}',  200, 'SMxxx001', NULL),
(1, 2, 1, 'callback', NULL, '{"sid":"SMxxx001","status":"delivered"}', 200, 'SMxxx001', NULL),
(2, 2, 4, 'outgoing', '{"To":"+12025551237","From":"+12025551234","Body":"Welcome..."}', '{"sid":"SMxxx004","error":"Invalid phone"}', 400, 'SMxxx004', 'Invalid phone number format');

TRUNCATE TABLE `currencies`;
INSERT INTO `currencies` (`code`, `name`, `symbol`, `decimal_places`, `is_base`, `is_active`) VALUES
('USD', 'US Dollar', '$', 2, TRUE, TRUE),
('EUR', 'Euro', 'EUR', 2, FALSE, TRUE),
('GBP', 'British Pound', 'GBP', 2, FALSE, TRUE),
('BDT', 'Bangladeshi Taka', 'Tk', 2, FALSE, TRUE);


TRUNCATE TABLE `exchange_rates`;
INSERT INTO `exchange_rates` (`from_currency_id`, `to_currency_id`, `rate`, `date`) VALUES
(1, 2, 0.92, '2026-01-01'),
(1, 3, 0.79, '2026-01-01'),
(2, 1, 1.09, '2026-01-01');


TRUNCATE TABLE `fiscal_years`;
INSERT INTO `fiscal_years` (`name`, `start_date`, `end_date`, `is_closed`) VALUES
('FY 2026', '2026-01-01', '2026-12-31', FALSE),
('FY 2025', '2025-01-01', '2025-12-31', TRUE);


TRUNCATE TABLE `account_periods`;
INSERT INTO `account_periods` (`fiscal_year_id`, `type`, `start_date`, `end_date`, `is_closed`) VALUES
(1, 'month', '2026-01-01', '2026-01-31', FALSE),
(1, 'month', '2026-02-01', '2026-02-28', FALSE),
(1, 'month', '2026-03-01', '2026-03-31', FALSE),
(1, 'month', '2026-04-01', '2026-04-30', FALSE),
(1, 'month', '2026-05-01', '2026-05-31', FALSE),
(1, 'quarter', '2026-01-01', '2026-03-31', FALSE),
(1, 'year', '2026-01-01', '2026-12-31', FALSE),
(2, 'month', '2025-01-01', '2025-01-31', TRUE),
(2, 'month', '2025-02-01', '2025-02-28', TRUE),
(2, 'month', '2025-03-01', '2025-03-31', TRUE),
(2, 'month', '2025-04-01', '2025-04-30', TRUE),
(2, 'month', '2025-05-01', '2025-05-31', TRUE),
(2, 'month', '2025-06-01', '2025-06-30', TRUE),
(2, 'month', '2025-07-01', '2025-07-31', TRUE),
(2, 'month', '2025-08-01', '2025-08-31', TRUE),
(2, 'month', '2025-09-01', '2025-09-30', TRUE),
(2, 'month', '2025-10-01', '2025-10-31', TRUE),
(2, 'month', '2025-11-01', '2025-11-30', TRUE),
(2, 'month', '2025-12-01', '2025-12-31', TRUE),
(2, 'quarter', '2025-01-01', '2025-03-31', TRUE),
(2, 'quarter', '2025-04-01', '2025-06-30', TRUE),
(2, 'quarter', '2025-07-01', '2025-09-30', TRUE),
(2, 'quarter', '2025-10-01', '2025-12-31', TRUE),
(2, 'year', '2025-01-01', '2025-12-31', TRUE);


TRUNCATE TABLE `chart_of_accounts`;
INSERT INTO `chart_of_accounts` (`parent_id`, `account_code`, `account_name`, `type`, `subtype`, `is_active`, `description`) VALUES
(NULL, '1000', 'Cash & Bank', 'asset', 'current_asset', TRUE, 'Cash in hand and bank accounts'),
(NULL, '2000', 'Accounts Payable', 'liability', 'current', TRUE, 'Money owed to suppliers'),
(NULL, '3000', 'Retained Earnings', 'equity', 'retained', TRUE, 'Accumulated retained earnings'),
(NULL, '4000', 'Sales Revenue', 'revenue', 'sales', TRUE, 'Revenue from product sales'),
(NULL, '5000', 'Cost of Goods Sold', 'expense', 'cogs', TRUE, 'Direct costs of goods sold'),
(1, '1100', 'Operating Account', 'asset', 'bank', TRUE, 'Main operating checking account'),
(2, '2100', 'Supplier Invoices', 'liability', 'trade_payable', TRUE, 'Unpaid supplier invoices');


TRUNCATE TABLE `cost_centers`;
INSERT INTO `cost_centers` (`code`, `name`, `description`, `is_active`) VALUES
('CC-ADMIN', 'Administration', 'General administrative overhead', TRUE),
('CC-SALES', 'Sales & Marketing', 'Sales team and marketing campaigns', TRUE),
('CC-DEV', 'Development', 'Software development team', TRUE);


TRUNCATE TABLE `profit_centers`;
INSERT INTO `profit_centers` (`code`, `name`, `description`, `is_active`) VALUES
('PC-SAAS', 'SaaS Products', 'Subscription-based SaaS products', TRUE),
('PC-APPS', 'Desktop Apps', 'One-time desktop application sales', TRUE);


TRUNCATE TABLE `journal_entry_types`;
INSERT INTO `journal_entry_types` (`name`, `code`, `description`) VALUES
('Sales Invoice', 'SALES', 'Revenue from customer sales'),
('Purchase Invoice', 'PURCHASE', 'Supplier purchase invoices'),
('Journal Voucher', 'JOURNAL', 'General journal adjustments');


TRUNCATE TABLE `journal_entries`;
INSERT INTO `journal_entries` (`entry_number`, `entry_type_id`, `fiscal_year_id`, `account_period_id`, `entry_date`, `description`, `reference_type`, `reference_id`, `created_by`, `is_posted`, `posted_at`) VALUES
('JE-2026-0001', 1, 1, 1, '2026-05-01', 'Sale of Ultimate CRM Script', 'order', 1, 1, TRUE, '2026-05-01T00:10:00'),
('JE-2026-0002', 2, 1, 5, '2026-05-05', 'Server hosting invoice #INV-2026-001', 'order', 2, 1, TRUE, '2026-05-05T12:00:00'),
('JE-2026-0003', 3, 1, 5, '2026-05-15', 'Monthly accrual adjustment', NULL, NULL, 1, TRUE, '2026-05-15T23:59:00');


TRUNCATE TABLE `journal_entry_lines`;
INSERT INTO `journal_entry_lines` (`journal_entry_id`, `account_id`, `cost_center_id`, `profit_center_id`, `debit`, `credit`, `description`, `line_order`) VALUES
(1, 6, 1, 1, 328.90, 0.00, 'Cash received from customer', 1),
(1, 4, 1, 1, 0.00, 328.90, 'Sales revenue recognized', 2),
(2, 7, 1, 1, 1200.00, 0.00, 'Server hosting expense', 1),
(2, 2, 1, 1, 0.00, 1200.00, 'Accounts payable - CloudHost Inc', 2),
(3, 5, 2, 1, 500.00, 0.00, 'Accrued COGS adjustment', 1),
(3, 4, 2, 1, 0.00, 500.00, 'Revenue adjustment', 2);


TRUNCATE TABLE `account_balances`;
INSERT INTO `account_balances` (`account_id`, `fiscal_year_id`, `account_period_id`, `period_type`, `opening_balance`, `period_debit`, `period_credit`, `closing_balance`) VALUES
(6, 1, 1, 'month', 15000.00, 5000.00, 2000.00, 18000.00),
(4, 1, 1, 'month', 0.00, 0.00, 5000.00, 5000.00),
(7, 1, 1, 'month', 2000.00, 1200.00, 800.00, 2400.00);


TRUNCATE TABLE `budgets`;
INSERT INTO `budgets` (`fiscal_year_id`, `profit_center_id`, `cost_center_id`, `name`, `description`, `status`) VALUES
(1, 1, 2, 'SaaS Revenue Budget 2026', 'Projected SaaS subscription revenue for FY 2026', 'active'),
(1, 2, 3, 'Desktop Apps Budget 2026', 'Projected desktop application sales for FY 2026', 'active');


TRUNCATE TABLE `budget_lines`;
INSERT INTO `budget_lines` (`budget_id`, `account_id`, `period_id`, `amount`) VALUES
(1, 4, 1, 50000.00),
(1, 4, 2, 52000.00),
(1, 4, 3, 48000.00),
(2, 4, 1, 25000.00),
(2, 5, 1, 10000.00);


TRUNCATE TABLE `budget_versions`;
INSERT INTO `budget_versions` (`budget_id`, `version`, `notes`, `snapshot`, `created_by`) VALUES
(1, 1, 'Initial budget draft for FY 2026', '{"total_revenue": 50000, "total_expense": 30000}', 1),
(1, 2, 'Revised after Q1 results', '{"total_revenue": 52000, "total_expense": 31000}', 1);


TRUNCATE TABLE `cost_allocations`;
INSERT INTO `cost_allocations` (`source_cost_center_id`, `target_cost_center_id`, `account_id`, `allocation_method`, `allocation_value`, `is_active`) VALUES
(1, 2, 7, 'percentage', 30.00, TRUE),
(1, 3, 7, 'percentage', 70.00, TRUE);


TRUNCATE TABLE `warehouses`;
INSERT INTO `warehouses` (`name`, `code`, `address`, `city`, `state`, `country`, `postal_code`, `is_active`) VALUES
('Main Warehouse - New York', 'WH-NYC', '100 Industrial Blvd', 'New York', 'NY', 'US', '10001', TRUE),
('West Coast Warehouse', 'WH-LAX', '200 Distribution Ave', 'Los Angeles', 'CA', 'US', '90001', TRUE);


TRUNCATE TABLE `warehouse_locations`;
INSERT INTO `warehouse_locations` (`warehouse_id`, `parent_id`, `code`, `name`, `type`, `max_weight`, `max_volume`, `is_active`) VALUES
(1, NULL, 'AISLE-A', 'Main Aisle A', 'aisle', 2000.00, 500.00, TRUE),
(1, 1, 'RACK-A1', 'Rack A1', 'rack', 500.00, 100.00, TRUE),
(1, 2, 'SHELF-A1A', 'Shelf A1A', 'shelf', 100.00, 20.00, TRUE),
(2, NULL, 'AISLE-B', 'West Aisle B', 'aisle', 2000.00, 500.00, TRUE),
(2, 4, 'RACK-B1', 'Rack B1', 'rack', 500.00, 100.00, TRUE);


TRUNCATE TABLE `stock_items`;
INSERT INTO `stock_items` (`product_id`, `warehouse_location_id`, `serial_number`, `batch_number`, `quantity`, `reserved_quantity`, `unit_cost`, `expiry_date`, `status`) VALUES
(1, 3, 'SN-CRM-001', 'BATCH-CRM-001', 50.00, 2.00, 25.00, '2026-12-31', 'available'),
(1, 3, 'SN-CRM-002', 'BATCH-CRM-001', 48.00, 0.00, 25.00, '2026-12-31', 'available'),
(2, 5, 'SN-SAAS-001', 'BATCH-SAAS-001', 100.00, 5.00, 45.00, NULL, 'available'),
(3, 3, 'SN-DESK-001', 'BATCH-DESK-001', 25.00, 1.00, 75.00, NULL, 'available');


TRUNCATE TABLE `inventory_movements`;
INSERT INTO `inventory_movements` (`product_id`, `from_location_id`, `to_location_id`, `stock_item_id`, `movement_type`, `reference_type`, `reference_id`, `quantity`, `unit_cost`, `notes`, `created_by`) VALUES
(1, NULL, 3, 1, 'receipt', 'purchase_order', 1, 50.00, 25.00, 'Initial stock receipt from supplier', 1),
(2, NULL, 5, 3, 'receipt', 'purchase_order', 2, 100.00, 45.00, 'SaaS license code batch received', 1),
(1, 3, 2, 1, 'transfer', 'transfer_order', 1, 5.00, 25.00, 'Transferred to Rack A1 for picking', 1);


TRUNCATE TABLE `inventory_adjustments`;
INSERT INTO `inventory_adjustments` (`product_id`, `warehouse_location_id`, `adjustment_type`, `expected_qty`, `actual_qty`, `difference`, `reason`, `approved_by`) VALUES
(1, 3, 'count', 50.00, 49.00, -1.00, 'Inventory count discrepancy - 1 unit missing', 1),
(3, 3, 'damage', 25.00, 24.00, -1.00, 'Damaged during handling', 1);


TRUNCATE TABLE `stock_counts`;
INSERT INTO `stock_counts` (`warehouse_id`, `count_date`, `status`, `counted_by`, `verified_by`, `notes`) VALUES
(1, '2026-05-01', 'completed', 1, 1, 'Monthly cycle count - Aisle A'),
(2, '2026-05-15', 'completed', 2, 1, 'Monthly cycle count - Aisle B');


TRUNCATE TABLE `stock_count_items`;
INSERT INTO `stock_count_items` (`stock_count_id`, `product_id`, `location_id`, `expected_qty`, `counted_qty`, `notes`) VALUES
(1, 1, 3, 50.00, 49.00, 'One unit missing, needs investigation'),
(1, 3, 3, 25.00, 25.00, 'Count matched'),
(2, 2, 5, 100.00, 100.00, 'Count matched');


TRUNCATE TABLE `reorder_rules`;
INSERT INTO `reorder_rules` (`product_id`, `warehouse_id`, `min_quantity`, `max_quantity`, `reorder_point`, `reorder_qty`, `lead_time_days`, `is_active`) VALUES
(1, 1, 5.00, 100.00, 10.00, 50.00, 7, TRUE),
(2, 2, 10.00, 200.00, 20.00, 100.00, 3, TRUE);


TRUNCATE TABLE `transfer_orders`;
INSERT INTO `transfer_orders` (`from_warehouse_id`, `to_warehouse_id`, `transfer_number`, `status`, `requested_by`, `approved_by`, `notes`) VALUES
(1, 2, 'TO-2026-0001', 'completed', 2, 1, 'Replenish West Coast stock'),
(2, 1, 'TO-2026-0002', 'draft', 2, NULL, 'Return excess inventory to main WH');


TRUNCATE TABLE `transfer_order_items`;
INSERT INTO `transfer_order_items` (`transfer_order_id`, `product_id`, `stock_item_id`, `quantity`, `received_qty`, `unit_cost`) VALUES
(1, 1, 1, 10.00, 10.00, 25.00),
(1, 3, 4, 5.00, 5.00, 75.00);


TRUNCATE TABLE `suppliers`;
INSERT INTO `suppliers` (`company_name`, `supplier_code`, `contact_name`, `email`, `phone`, `address`, `city`, `state`, `country`, `postal_code`, `tax_id`, `payment_terms`, `currency_id`, `status`) VALUES
('CloudHost Inc', 'SUP-001', 'Alice Wang', 'alice@cloudhost.com', '+1-415-555-0100', '500 Server Dr', 'San Francisco', 'CA', 'US', '94105', 'TAX-US-001', 'Net 30', 1, 'active'),
('TechSupply Co', 'SUP-002', 'Bob Chen', 'bob@techsupply.co', '+1-312-555-0200', '200 Parts Ave', 'Chicago', 'IL', 'US', '60601', 'TAX-US-002', 'Net 15', 1, 'active'),
('Global Licensing Ltd', 'SUP-003', 'Carol Smith', 'carol@globallicense.com', '+44-20-5555-0300', '10 Thames Street', 'London', NULL, 'GB', 'EC1A', 'TAX-GB-001', 'Net 60', 3, 'active');


TRUNCATE TABLE `supplier_contacts`;
INSERT INTO `supplier_contacts` (`supplier_id`, `first_name`, `last_name`, `job_title`, `email`, `phone`, `is_primary`) VALUES
(1, 'Alice', 'Wang', 'Account Manager', 'alice@cloudhost.com', '+1-415-555-0100', TRUE),
(1, 'Dan', 'Lee', 'Support Engineer', 'dan@cloudhost.com', '+1-415-555-0101', FALSE),
(2, 'Bob', 'Chen', 'Sales Director', 'bob@techsupply.co', '+1-312-555-0200', TRUE);


TRUNCATE TABLE `supplier_products`;
INSERT INTO `supplier_products` (`supplier_id`, `product_id`, `supplier_sku`, `lead_time_days`, `moq`, `is_preferred`) VALUES
(1, 1, 'CLOUD-HOST-CRM', 1, 1, TRUE),
(1, 2, 'CLOUD-HOST-SAAS', 2, 1, TRUE),
(2, 3, 'TS-DESK-SUPPLY', 5, 10, FALSE);


TRUNCATE TABLE `supplier_pricelists`;
INSERT INTO `supplier_pricelists` (`supplier_product_id`, `unit_price`, `currency_id`, `min_quantity`, `effective_from`, `effective_until`, `is_active`) VALUES
(1, 25.00, 1, 1, '2026-01-01', '2026-12-31', TRUE),
(2, 45.00, 1, 1, '2026-01-01', '2026-12-31', TRUE),
(3, 75.00, 1, 10, '2026-03-01', NULL, TRUE);


TRUNCATE TABLE `purchase_orders`;
INSERT INTO `purchase_orders` (`supplier_id`, `order_number`, `status`, `order_date`, `expected_date`, `subtotal`, `tax`, `total`, `currency_id`, `notes`, `requested_by`, `approved_by`) VALUES
(1, 'PO-2026-0001', 'received', '2026-05-02', '2026-05-05', 1250.00, 125.00, 1375.00, 1, 'Monthly cloud hosting renewal', 2, 1),
(2, 'PO-2026-0002', 'approved', '2026-05-10', '2026-05-20', 750.00, 75.00, 825.00, 1, 'Office supply restock', 2, 1),
(3, 'PO-2026-0003', 'draft', '2026-05-25', '2026-06-01', 5000.00, 0.00, 5000.00, 3, 'Annual licensing fee for third-party SDK', 1, NULL);


TRUNCATE TABLE `purchase_order_items`;
INSERT INTO `purchase_order_items` (`purchase_order_id`, `product_id`, `warehouse_location_id`, `description`, `quantity`, `received_qty`, `unit_price`, `tax_rate`, `subtotal`, `line_order`) VALUES
(1, 1, 1, 'Cloud hosting for CRM - 6 months', 1.00, 1.00, 1250.00, 10.00, 1250.00, 1),
(2, 3, 1, 'Desk supplies for office', 50.00, 0.00, 15.00, 10.00, 750.00, 1),
(3, 2, 1, 'Annual SaaS SDK license for boilerplate', 1.00, 0.00, 5000.00, 0.00, 5000.00, 1);


TRUNCATE TABLE `purchase_receipts`;
INSERT INTO `purchase_receipts` (`purchase_order_id`, `receipt_number`, `received_date`, `status`, `notes`, `received_by`) VALUES
(1, 'PR-2026-0001', '2026-05-03', 'completed', 'Cloud hosting service activated', 1),
(1, 'PR-2026-0002', '2026-05-04', 'completed', 'Additional bandwidth add-on', 1);


TRUNCATE TABLE `purchase_receipt_items`;
INSERT INTO `purchase_receipt_items` (`purchase_receipt_id`, `po_item_id`, `product_id`, `warehouse_location_id`, `quantity`, `unit_cost`, `batch_number`, `expiry_date`) VALUES
(1, 1, 1, 3, 1.00, 1250.00, NULL, NULL),
(2, 1, 1, 3, 1.00, 1250.00, NULL, NULL);


TRUNCATE TABLE `purchase_invoices`;
INSERT INTO `purchase_invoices` (`purchase_order_id`, `supplier_id`, `invoice_number`, `invoice_date`, `due_date`, `subtotal`, `tax`, `total`, `currency_id`, `status`, `notes`) VALUES
(1, 1, 'INV-CLOUD-2026-001', '2026-05-02', '2026-06-01', 1250.00, 125.00, 1375.00, 1, 'approved', 'Monthly hosting invoice'),
(2, 2, 'INV-TECH-2026-001', '2026-05-10', '2026-05-25', 750.00, 75.00, 825.00, 1, 'pending', 'Office supply invoice');


TRUNCATE TABLE `purchase_invoice_items`;
INSERT INTO `purchase_invoice_items` (`purchase_invoice_id`, `po_item_id`, `product_id`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 1, 1, 1.00, 1250.00, 1250.00),
(2, 2, 3, 50.00, 15.00, 750.00);


TRUNCATE TABLE `rfqs`;
INSERT INTO `rfqs` (`rfq_number`, `title`, `description`, `issue_date`, `closing_date`, `status`, `created_by`) VALUES
('RFQ-2026-0001', 'Cloud hosting services renewal', 'Seeking proposals for cloud hosting for next 12 months', '2026-04-01', '2026-04-15', 'awarded', 2),
('RFQ-2026-0002', 'Office furniture bulk purchase', 'Need 50 ergonomic chairs and desks', '2026-05-01', '2026-05-20', 'sent', 2);


TRUNCATE TABLE `rfq_items`;
INSERT INTO `rfq_items` (`rfq_id`, `product_id`, `quantity`, `notes`, `line_order`) VALUES
(1, 1, 6.00, '6-month CRM hosting package', 1),
(1, 2, 12.00, '12-month SaaS hosting package', 2),
(2, 3, 50.00, 'Quantity of 50 items', 1);


TRUNCATE TABLE `supplier_quotations`;
INSERT INTO `supplier_quotations` (`rfq_id`, `supplier_id`, `quotation_number`, `quotation_date`, `valid_until`, `subtotal`, `tax`, `total`, `currency_id`, `status`, `notes`) VALUES
(1, 1, 'QTN-CLOUD-001', '2026-04-10', '2026-05-10', 1250.00, 125.00, 1375.00, 1, 'accepted', 'Best pricing for cloud hosting'),
(1, 2, 'QTN-TECH-001', '2026-04-12', '2026-05-12', 1400.00, 140.00, 1540.00, 1, 'rejected', 'Higher pricing than cloud specialist');


TRUNCATE TABLE `quotation_items`;
INSERT INTO `quotation_items` (`quotation_id`, `rfq_item_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `line_order`) VALUES
(1, 1, 1, 6.00, 1250.00, 7500.00, 1),
(1, 2, 2, 12.00, 500.00, 6000.00, 2),
(2, 1, 1, 6.00, 1350.00, 8100.00, 1);


TRUNCATE TABLE `work_centers`;
INSERT INTO `work_centers` (`code`, `name`, `type`, `description`, `cost_per_hour`, `efficiency_rate`, `is_active`) VALUES
('WC-ASSY-01', 'Assembly Station 1', 'workstation', 'Main assembly line for desktop products', 45.00, 100.00, TRUE),
('WC-PACK-01', 'Packaging Station', 'manual', 'Manual packaging and labeling', 25.00, 95.00, TRUE);


TRUNCATE TABLE `work_center_capacity`;
INSERT INTO `work_center_capacity` (`work_center_id`, `capacity_date`, `available_hours`, `maintenance_hours`, `booked_hours`, `overtime_hours`, `notes`) VALUES
(1, '2026-05-01', 8.00, 0.00, 6.00, 0.00, 'Regular production day'),
(1, '2026-05-02', 8.00, 2.00, 4.00, 0.00, 'Scheduled maintenance 2 hours'),
(2, '2026-05-01', 8.00, 0.00, 5.00, 1.00, 'Overtime due to backlog');


TRUNCATE TABLE `bill_of_materials`;
INSERT INTO `bill_of_materials` (`product_id`, `name`, `version`, `quantity`, `is_active`) VALUES
(3, 'Desktop Inventory Manager BOM', '1.0', 1.00, TRUE),
(1, 'CRM Script Standard Package', '2.0', 1.00, TRUE);


TRUNCATE TABLE `bom_items`;
INSERT INTO `bom_items` (`bom_id`, `component_id`, `quantity`, `unit`, `scrap_rate`, `line_order`) VALUES
(1, 2, 1.00, 'units', 0.00, 1),
(1, 1, 2.00, 'units', 2.50, 2),
(2, 3, 1.00, 'units', 0.00, 1);


TRUNCATE TABLE `routings`;
INSERT INTO `routings` (`bom_id`, `name`, `total_time`, `is_active`) VALUES
(1, 'Desktop Assembly Routing', 120.00, TRUE),
(1, 'Express Assembly Routing', 60.00, TRUE);


TRUNCATE TABLE `routing_steps`;
INSERT INTO `routing_steps` (`routing_id`, `work_center_id`, `step_name`, `step_order`, `setup_time`, `run_time`, `teardown_time`, `notes`) VALUES
(1, 1, 'Component assembly', 1, 15.00, 45.00, 10.00, 'Assemble main components'),
(1, 2, 'Quality check & pack', 2, 5.00, 30.00, 5.00, 'Inspect and package finished product'),
(2, 1, 'Express assembly', 1, 10.00, 30.00, 5.00, 'Simplified assembly for small batches');


TRUNCATE TABLE `production_orders`;
INSERT INTO `production_orders` (`product_id`, `bom_id`, `routing_id`, `warehouse_id`, `order_number`, `quantity`, `produced_qty`, `scrap_qty`, `status`, `priority`, `scheduled_start`, `scheduled_end`, `actual_start`, `actual_end`, `notes`, `created_by`) VALUES
(3, 1, 1, 1, 'MO-2026-0001', 50.00, 48.00, 2.00, 'completed', 'high', '2026-05-01T08:00:00', '2026-05-01T18:00:00', '2026-05-01T08:00:00', '2026-05-01T17:30:00', 'Rush order for Desktop Inventory Manager', 1),
(1, 2, 2, 1, 'MO-2026-0002', 100.00, 0.00, 0.00, 'planned', 'medium', '2026-06-01T08:00:00', '2026-06-01T17:00:00', NULL, NULL, 'Scheduled CRM script packaging run', 1);


TRUNCATE TABLE `production_order_steps`;
INSERT INTO `production_order_steps` (`production_order_id`, `routing_step_id`, `work_center_id`, `status`, `actual_setup_time`, `actual_run_time`, `completed_qty`, `scrap_qty`, `started_at`, `completed_at`, `notes`) VALUES
(1, 1, 1, 'completed', 14.00, 44.00, 50.00, 2.00, '2026-05-01T08:15:00', '2026-05-01T15:30:00', 'Components assembled successfully'),
(1, 2, 2, 'completed', 5.00, 29.00, 48.00, 0.00, '2026-05-01T15:45:00', '2026-05-01T17:15:00', 'Packaging completed, 2 units scrapped');


TRUNCATE TABLE `production_outputs`;
INSERT INTO `production_outputs` (`production_order_id`, `product_id`, `warehouse_location_id`, `quantity`, `unit_cost`, `batch_number`) VALUES
(1, 3, 3, 48.00, 65.00, 'BATCH-DESK-MAY-001'),
(1, 3, 3, 2.00, 65.00, 'BATCH-DESK-MAY-001-SCRAP');


TRUNCATE TABLE `production_material_issues`;
INSERT INTO `production_material_issues` (`production_order_id`, `stock_item_id`, `product_id`, `warehouse_location_id`, `quantity`, `unit_cost`) VALUES
(1, 4, 3, 3, 50.00, 75.00);


TRUNCATE TABLE `capacity_plans`;
INSERT INTO `capacity_plans` (`work_center_id`, `plan_date`, `planned_hours`, `actual_hours`, `available_hours`, `notes`) VALUES
(1, '2026-05-01', 8.00, 7.50, 8.00, 'Regular shift - full utilization'),
(1, '2026-05-02', 6.00, 6.00, 8.00, 'Reduced capacity due to maintenance'),
(2, '2026-05-01', 8.00, 8.00, 8.00, 'Full capacity with overtime');


TRUNCATE TABLE `maintenance_schedules`;
INSERT INTO `maintenance_schedules` (`work_center_id`, `title`, `type`, `frequency`, `frequency_value`, `last_done_at`, `next_due_at`, `estimated_hours`, `is_active`) VALUES
(1, 'Quarterly calibration', 'preventive', 'quarterly', NULL, '2026-02-01T08:00:00', '2026-05-01T08:00:00', 4.00, TRUE),
(1, 'Annual overhaul', 'predictive', 'yearly', NULL, '2025-06-01T08:00:00', '2026-06-01T08:00:00', 16.00, TRUE),
(2, 'Weekly cleaning & inspection', 'preventive', 'weekly', NULL, '2026-05-25T08:00:00', '2026-06-01T08:00:00', 1.00, TRUE);


TRUNCATE TABLE `maintenance_logs`;
INSERT INTO `maintenance_logs` (`maintenance_schedule_id`, `work_center_id`, `title`, `description`, `type`, `status`, `started_at`, `completed_at`, `duration_hours`, `cost`, `performed_by`, `notes`) VALUES
(1, 1, 'Q2 calibration - Assembly 1', 'Routine calibration of sensors and actuators', 'preventive', 'completed', '2026-05-01T08:00:00', '2026-05-01T12:00:00', 4.00, 350.00, 1, 'All sensors calibrated within spec'),
(3, 2, 'Weekly cleaning - Packaging', 'Cleaning and inspection of packaging station', 'preventive', 'completed', '2026-05-28T08:00:00', '2026-05-28T09:00:00', 1.00, 50.00, 2, 'Routine cleaning completed');


TRUNCATE TABLE `departments`;
INSERT INTO `departments` (`parent_id`, `code`, `name`, `manager_id`, `is_active`) VALUES
(NULL, 'DEPT-EXEC', 'Executive', 1, TRUE),
(NULL, 'DEPT-ENG', 'Engineering', 1, TRUE),
(1, 'DEPT-SALES', 'Sales & Marketing', 2, TRUE),
(2, 'DEPT-DEV', 'Software Development', 1, TRUE);


TRUNCATE TABLE `job_positions`;
INSERT INTO `job_positions` (`department_id`, `title`, `description`, `requirements`, `salary_min`, `salary_max`, `is_active`) VALUES
(1, 'Chief Technology Officer', 'Oversees all technology and engineering', '15+ years in software, 5+ in leadership', 150000.00, 250000.00, TRUE),
(2, 'Senior Software Engineer', 'Develops and maintains core platform', '8+ years in PHP/Laravel, 3+ in SaaS', 100000.00, 160000.00, TRUE),
(3, 'Sales Manager', 'Leads sales team and drives revenue growth', '10+ years B2B SaaS sales experience', 90000.00, 150000.00, TRUE);


TRUNCATE TABLE `employees`;
INSERT INTO `employees` (`user_id`, `employee_number`, `department_id`, `job_position_id`, `reports_to`, `hire_date`, `termination_date`, `employment_type`, `status`, `base_salary`, `currency_id`, `emergency_contact`) VALUES
(1, 'EMP-0001', 1, 1, NULL, '2023-01-15', NULL, 'full_time', 'active', 180000.00, 1, '{"name": "Jane Doe", "phone": "+1-555-9999", "relation": "spouse"}'),
(2, 'EMP-0002', 3, 3, 1, '2024-06-01', NULL, 'full_time', 'active', 120000.00, 1, '{"name": "Mark Dev", "phone": "+1-555-8888", "relation": "brother"}'),
(3, 'EMP-0003', 2, 2, 1, '2025-03-10', NULL, 'full_time', 'active', 140000.00, 1, '{"name": "Sarah Buyer", "phone": "+1-555-7777", "relation": "sister"}'),
(4, 'EMP-0004', 2, 2, 3, '2025-03-15', NULL, 'full_time', 'active', 95000.00, 1, '{"name": "Tom Support", "phone": "+1-555-6666", "relation": "friend"}');


TRUNCATE TABLE `employee_contracts`;
INSERT INTO `employee_contracts` (`employee_id`, `contract_type`, `start_date`, `end_date`, `salary`, `currency_id`, `benefits`, `status`) VALUES
(1, 'permanent', '2023-01-15', NULL, 180000.00, 1, '{"health_insurance": true, "stock_options": 5000}', 'active'),
(2, 'permanent', '2024-06-01', NULL, 120000.00, 1, '{"health_insurance": true, "stock_options": 2000}', 'active'),
(3, 'permanent', '2025-03-10', NULL, 140000.00, 1, '{"health_insurance": true}', 'active');


TRUNCATE TABLE `employee_documents`;
INSERT INTO `employee_documents` (`employee_id`, `document_type`, `file_name`, `file_path`, `expiry_date`, `is_verified`, `notes`) VALUES
(1, 'id', 'john_doe_passport.pdf', '/hr/documents/emp-0001/passport.pdf', '2031-01-15', TRUE, 'US Passport - verified'),
(2, 'contract', 'mark_dev_contract.pdf', '/hr/documents/emp-0002/contract.pdf', NULL, TRUE, 'Signed employment contract'),
(3, 'certificate', 'sarah_cert.pdf', '/hr/documents/emp-0003/cert.pdf', NULL, FALSE, 'Pending verification of degree');


TRUNCATE TABLE `attendance`;
INSERT INTO `attendance` (`employee_id`, `date`, `clock_in`, `clock_out`, `status`, `notes`) VALUES
(1, '2026-05-01', '2026-05-01T08:00:00', '2026-05-01T17:00:00', 'present', NULL),
(1, '2026-05-02', '2026-05-02T08:30:00', '2026-05-02T17:30:00', 'present', 'Late by 30 min due to traffic'),
(2, '2026-05-01', '2026-05-01T09:00:00', '2026-05-01T18:00:00', 'present', NULL),
(2, '2026-05-02', NULL, NULL, 'absent', 'Sick leave - no clock in');


TRUNCATE TABLE `leave_types`;
INSERT INTO `leave_types` (`name`, `code`, `days_allowed`, `is_paid`, `carry_forward`, `max_carry_days`, `is_active`) VALUES
('Annual Leave', 'ANNUAL', 20, TRUE, TRUE, 5, TRUE),
('Sick Leave', 'SICK', 10, TRUE, FALSE, 0, TRUE),
('Personal Leave', 'PERSONAL', 5, TRUE, FALSE, 0, TRUE);


TRUNCATE TABLE `leave_requests`;
INSERT INTO `leave_requests` (`employee_id`, `leave_type_id`, `start_date`, `end_date`, `reason`, `status`, `approved_by`, `approved_at`) VALUES
(1, 1, '2026-07-01', '2026-07-15', 'Family vacation to Europe', 'approved', 1, '2026-05-15T09:00:00'),
(2, 2, '2026-05-02', '2026-05-02', 'Feeling unwell - rest day', 'approved', 1, '2026-05-02T08:00:00'),
(3, 3, '2026-06-10', '2026-06-10', 'Personal appointment', 'pending', NULL, NULL);


TRUNCATE TABLE `leave_balances`;
INSERT INTO `leave_balances` (`employee_id`, `leave_type_id`, `year`, `total_days`, `used_days`, `pending_days`) VALUES
(1, 1, 2026, 20.00, 0.00, 15.00),
(1, 2, 2026, 10.00, 1.00, 0.00),
(2, 1, 2026, 20.00, 1.00, 0.00),
(2, 2, 2026, 10.00, 1.00, 0.00),
(3, 1, 2026, 20.00, 0.00, 1.00),
(3, 3, 2026, 5.00, 0.00, 1.00);


TRUNCATE TABLE `timesheets`;
INSERT INTO `timesheets` (`employee_id`, `date`, `start_time`, `end_time`, `total_hours`, `break_hours`, `description`, `is_approved`, `approved_by`) VALUES
(1, '2026-05-01', '08:00:00', '17:00:00', 9.00, 1.00, 'Regular work - Platform development', TRUE, 1),
(1, '2026-05-02', '08:30:00', '17:30:00', 9.00, 1.00, 'Code review and deployment', TRUE, 1),
(2, '2026-05-01', '09:00:00', '18:00:00', 9.00, 1.00, 'Sales calls and client meetings', TRUE, 1),
(2, '2026-05-03', '09:00:00', '17:00:00', 8.00, 1.00, 'Reporting and pipeline review', FALSE, NULL);


TRUNCATE TABLE `payroll_components`;
INSERT INTO `payroll_components` (`name`, `code`, `type`, `calculation`, `value`, `is_taxable`, `is_active`) VALUES
('Basic Salary', 'BASIC', 'earning', 'fixed', 8000.00, TRUE, TRUE),
('Housing Allowance', 'HOUSING', 'earning', 'percentage_of_basic', 20.00, TRUE, TRUE),
('Health Insurance', 'HEALTH', 'deduction', 'fixed', 500.00, TRUE, TRUE),
('Tax Withholding', 'TAX', 'deduction', 'percentage_of_gross', 15.00, TRUE, TRUE),
('Employer Pension', 'PENSION', 'employer_contribution', 'percentage_of_basic', 10.00, FALSE, TRUE);


TRUNCATE TABLE `payroll_runs`;
INSERT INTO `payroll_runs` (`fiscal_year_id`, `account_period_id`, `run_number`, `period_start`, `period_end`, `payment_date`, `status`, `total_gross`, `total_deductions`, `total_net`, `notes`, `processed_by`) VALUES
(1, 1, 'PR-2026-05-001', '2026-05-01', '2026-05-31', '2026-05-31', 'completed', 38000.00, 5700.00, 32300.00, 'May 2026 payroll run', 1),
(1, 2, 'PR-2026-04-001', '2026-04-01', '2026-04-30', '2026-04-30', 'completed', 38000.00, 5700.00, 32300.00, 'April 2026 payroll run', 1);


TRUNCATE TABLE `payroll_items`;
INSERT INTO `payroll_items` (`payroll_run_id`, `employee_id`, `gross_pay`, `total_deductions`, `net_pay`, `bank_account`, `payment_method`, `status`) VALUES
(1, 1, 15000.00, 2250.00, 12750.00, '****1234', 'bank_transfer', 'paid'),
(1, 2, 12000.00, 1800.00, 10200.00, '****5678', 'bank_transfer', 'paid'),
(1, 3, 11000.00, 1650.00, 9350.00, '****9012', 'bank_transfer', 'paid');


TRUNCATE TABLE `payroll_item_details`;
INSERT INTO `payroll_item_details` (`payroll_item_id`, `payroll_component_id`, `amount`) VALUES
(1, 1, 8000.00),
(1, 2, 1600.00),
(1, 3, 500.00),
(1, 4, 2250.00),
(2, 1, 8000.00),
(2, 2, 1600.00),
(3, 3, 500.00);


TRUNCATE TABLE `workflow_definitions`;
INSERT INTO `workflow_definitions` (`name`, `slug`, `description`, `category`, `status`, `version`, `config`, `is_system`, `created_by`) VALUES
('Welcome Email Series', 'welcome-email-series', 'Send a welcome email sequence when a new user registers', 'Onboarding', 'active', 1, '{"timeout": 3600}', TRUE, 1),
('Order Fulfillment',   'order-fulfillment',   'Automated order processing and fulfillment workflow',         'Commerce',  'draft',  1, NULL, FALSE, 2);

TRUNCATE TABLE `workflow_nodes`;
INSERT INTO `workflow_nodes` (`workflow_id`, `type`, `name`, `description`, `config`, `position_x`, `position_y`, `timeout_seconds`, `retry_count`, `retry_delay`) VALUES
(1, 'trigger',   'User Registered',    'Triggers on user.registered event',          '{"event_type":"user.registered"}',              0,    0,   NULL, 0, 0),
(1, 'action',    'Send Welcome Email', 'Sends the welcome email template',           '{"template_id":1,"delay_minutes":0}',            200, 0,   30,   3, 60),
(1, 'end',       'Complete',           'Workflow complete',                          NULL,                                              400, 0,   NULL, 0, 0),
(2, 'trigger',   'Order Placed',       'Triggers on order.placed event',             '{"event_type":"order.placed"}',                 0,    200, NULL, 0, 0),
(2, 'action',    'Process Payment',    'Process payment via selected gateway',        '{"gateway":"stripe","capture":true}',            200, 200, 120,  3, 30),
(2, 'approval',  'Review Order',       'Manual review for high-value orders',        '{"min_amount":500}',                            400, 200, 86400,0, 0),
(2, 'action',    'Send Confirmation',  'Send order confirmation to customer',        '{"template_id":2}',                             600, 200, 30,   2, 30),
(2, 'end',       'Complete',           'Order fulfillment complete',                  NULL,                                              800, 200, NULL, 0, 0);

TRUNCATE TABLE `workflow_transitions`;
INSERT INTO `workflow_transitions` (`workflow_id`, `from_node_id`, `to_node_id`, `condition_expression`, `label`, `priority`) VALUES
(1, 1, 2, NULL, 'on_register',       0),
(1, 2, 3, NULL, 'email_sent',        0),
(2, 4, 5, NULL, 'order_placed',      0),
(2, 5, 6, '{"field":"order.total","operator":"greater_than","value":500}', 'high_value', 0),
(2, 5, 7, '{"field":"order.total","operator":"less_or_equal","value":500}', 'standard',  0),
(2, 6, 7, NULL, 'approved',          0),
(2, 7, 8, NULL, 'confirmation_sent', 0);

TRUNCATE TABLE `workflow_runs`;
INSERT INTO `workflow_runs` (`workflow_id`, `triggered_by`, `trigger_type`, `trigger_payload`, `status`, `current_node_id`, `started_at`, `completed_at`) VALUES
(1, 3, 'user.registered', '{"user_id":3,"email":"jane@example.com"}',                              'completed',  3, NOW() - INTERVAL 2 DAY,  NOW() - INTERVAL 2 DAY + INTERVAL 5 MINUTE),
(2, 2, 'order.placed',    '{"order_id":1,"user_id":3,"total":328.90,"currency":"USD"}',           'running',    6, NOW() - INTERVAL 1 HOUR, NULL),
(2, NULL, 'order.placed', '{"order_id":2,"user_id":3,"total":9.99,"currency":"USD"}',              'completed',  8, NOW() - INTERVAL 3 HOUR, NOW() - INTERVAL 3 HOUR + INTERVAL 10 MINUTE);

TRUNCATE TABLE `workflow_run_logs`;
INSERT INTO `workflow_run_logs` (`run_id`, `node_id`, `action_type`, `level`, `message`, `payload`) VALUES
(1, 1, 'trigger',   'info',  'Trigger matched: user.registered', '{"user_id":3}'),
(1, 2, 'action',    'info',  'Welcome email sent successfully',  '{"template_id":1,"email":"jane@example.com","status":"delivered"}'),
(1, 3, 'end',       'info',  'Workflow completed',               '{"duration_seconds":300,"nodes_executed":3}'),
(2, 4, 'trigger',   'info',  'Trigger matched: order.placed',    '{"order_id":1}'),
(2, 5, 'action',    'warn',  'Payment processing initiated',     '{"gateway":"stripe","amount":328.90}'),
(2, 6, 'approval',  'info',  'Awaiting manual approval',         '{"threshold_exceeded":true,"amount":328.90}'),
(3, 4, 'trigger',   'info',  'Trigger matched: order.placed',    '{"order_id":2}'),
(3, 5, 'action',    'info',  'Payment processed successfully',   '{"gateway":"stripe","charge_id":"ch_abc123"}'),
(3, 7, 'action',    'info',  'Confirmation email sent',          '{"template_id":2,"email":"jane@example.com"}'),
(3, 8, 'end',       'info',  'Workflow completed',               '{"duration_seconds":600,"nodes_executed":4}');

TRUNCATE TABLE `workflow_run_node_states`;
INSERT INTO `workflow_run_node_states` (`run_id`, `node_id`, `status`, `input`, `output`, `attempts`, `started_at`, `completed_at`) VALUES
(1, 1, 'completed', '{"event_type":"user.registered","user_id":3}',            '{"matched":true}',                    1, NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY + INTERVAL 1 SECOND),
(1, 2, 'completed', '{"user_id":3,"email":"jane@example.com"}',                '{"delivery_id":"dlv_001","status":"sent"}', 1, NOW() - INTERVAL 2 DAY + INTERVAL 1 MINUTE, NOW() - INTERVAL 2 DAY + INTERVAL 2 MINUTE),
(1, 3, 'completed', '{"duration_seconds":300}',                                '{"completed":true}',                  1, NOW() - INTERVAL 2 DAY + INTERVAL 2 MINUTE, NOW() - INTERVAL 2 DAY + INTERVAL 2 MINUTE),
(2, 4, 'completed', '{"event_type":"order.placed","order_id":1}',              '{"matched":true}',                    1, NOW() - INTERVAL 1 HOUR, NOW() - INTERVAL 1 HOUR + INTERVAL 1 SECOND),
(2, 5, 'completed', '{"order_id":1,"total":328.90}',                          '{"charge_id":"ch_xyz789","status":"pending"}', 1, NOW() - INTERVAL 1 HOUR + INTERVAL 10 SECOND, NOW() - INTERVAL 1 HOUR + INTERVAL 30 SECOND),
(2, 6, 'running',   '{"order_id":1,"total":328.90,"requires_approval":true}', NULL, 0, NOW() - INTERVAL 1 HOUR + INTERVAL 30 SECOND, NULL),
(3, 4, 'completed', '{"event_type":"order.placed","order_id":2}',              '{"matched":true}',                    1, NOW() - INTERVAL 3 HOUR, NOW() - INTERVAL 3 HOUR + INTERVAL 1 SECOND),
(3, 5, 'completed', '{"order_id":2,"total":59.00}',                           '{"charge_id":"ch_abc123","status":"captured"}', 1, NOW() - INTERVAL 3 HOUR + INTERVAL 5 SECOND, NOW() - INTERVAL 3 HOUR + INTERVAL 15 SECOND),
(3, 7, 'completed', '{"order_id":2,"email":"jane@example.com"}',               '{"delivery_id":"dlv_002"}',           1, NOW() - INTERVAL 3 HOUR + INTERVAL 20 SECOND, NOW() - INTERVAL 3 HOUR + INTERVAL 25 SECOND),
(3, 8, 'completed', '{"duration_seconds":600}',                                '{"completed":true}',                  1, NOW() - INTERVAL 3 HOUR + INTERVAL 25 SECOND, NOW() - INTERVAL 3 HOUR + INTERVAL 25 SECOND);

TRUNCATE TABLE `workflow_run_variables`;
INSERT INTO `workflow_run_variables` (`run_id`, `name`, `value`) VALUES
(1, 'welcome_email_sent',  'true'),
(1, 'user_email',          '"jane@example.com"'),
(2, 'payment_charge_id',   '"ch_xyz789"'),
(2, 'requires_approval',   'true'),
(3, 'payment_charge_id',   '"ch_abc123"'),
(3, 'order_total',         '59.00');

TRUNCATE TABLE `triggers`;
INSERT INTO `triggers` (`name`, `slug`, `event_type`, `description`, `config`, `status`) VALUES
('User Registered',    'user-registered',    'user.registered',  'Fires when a new user account is created',  '{"priority":"high"}',   'active'),
('Order Placed',       'order-placed',       'order.placed',     'Fires when a customer places an order',     '{"priority":"normal"}', 'active'),
('Payment Received',   'payment-received',   'payment.received', 'Fires when a payment is successfully processed', NULL, 'active'),
('Subscription Expired', 'subscription-expired', 'subscription.expired', 'Fires when a user subscription expires', NULL, 'inactive');

TRUNCATE TABLE `trigger_workflow_mappings`;
INSERT INTO `trigger_workflow_mappings` (`trigger_id`, `workflow_id`, `priority`, `conditions`, `status`) VALUES
(1, 1, 100, NULL,                                                                          'active'),
(2, 2, 100, NULL,                                                                          'active'),
(2, 2, 50,  '{"field":"order.total","operator":"greater_than","value":"1000"}',            'active');

TRUNCATE TABLE `event_log`;
INSERT INTO `event_log` (`event_type`, `source_type`, `source_id`, `payload`, `occurred_at`, `processed_at`) VALUES
('user.registered',     'users',       3, '{"user_id":3,"email":"jane@example.com"}',                                    NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY + INTERVAL 1 SECOND),
('order.placed',        'orders',      1, '{"order_id":1,"user_id":3,"total":328.90}',                                   NOW() - INTERVAL 1 HOUR, NOW() - INTERVAL 1 HOUR + INTERVAL 1 SECOND),
('order.placed',        'orders',      2, '{"order_id":2,"user_id":3,"total":9.99}',                                    NOW() - INTERVAL 3 HOUR, NOW() - INTERVAL 3 HOUR + INTERVAL 1 SECOND),
('payment.received',    'payments',    1, '{"payment_id":1,"order_id":1,"amount":328.90,"gateway":"stripe"}',            NOW(), NULL),
('subscription.expired','user_subscriptions', 1, '{"subscription_id":1,"user_id":3,"product_id":1}',                     NOW() - INTERVAL 7 DAY, NULL);

TRUNCATE TABLE `condition_groups`;
INSERT INTO `condition_groups` (`name`, `operator`) VALUES
('High Value Order', 'AND'),
('New Customer',     'OR');

TRUNCATE TABLE `condition_rules`;
INSERT INTO `condition_rules` (`group_id`, `field`, `operator`, `value`) VALUES
(1, 'order.total',       'greater_than',    '500'),
(1, 'user.account_age',  'less_than',       '30'),
(2, 'order.count',       'equals',          '0'),
(2, 'user.created_at',   'greater_than',    '"2026-05-01"');

TRUNCATE TABLE `condition_group_mappings`;
INSERT INTO `condition_group_mappings` (`group_id`, `entity_type`, `entity_id`) VALUES
(1, 'workflow_transition', 4),
(2, 'workflow_definition', 1);

TRUNCATE TABLE `approval_requests`;
INSERT INTO `approval_requests` (`workflow_run_id`, `node_id`, `status`, `requested_by`, `requested_at`, `responded_at`, `notes`) VALUES
(2, 6, 'pending', 2, NOW() - INTERVAL 1 HOUR, NULL, 'Order #1 requires approval due to high value'),
(3, 6, 'approved', 3, NOW() - INTERVAL 3 HOUR, NOW() - INTERVAL 3 HOUR + INTERVAL 30 MINUTE, 'Approved - standard order');

TRUNCATE TABLE `approval_stages`;
INSERT INTO `approval_stages` (`approval_request_id`, `stage_order`, `status`, `strategy`, `min_approvers`) VALUES
(1, 1, 'pending',  'all', 2),
(2, 1, 'approved', 'any', 1);

TRUNCATE TABLE `approval_assignees`;
INSERT INTO `approval_assignees` (`stage_id`, `user_id`, `status`, `response`, `responded_at`) VALUES
(1, 1, 'pending',  NULL, NULL),
(1, 4, 'approved', 'Looks good, proceed.', NOW() - INTERVAL 1 HOUR + INTERVAL 40 MINUTE),
(2, 1, 'approved', 'Approved.',              NOW() - INTERVAL 3 HOUR + INTERVAL 30 MINUTE);

TRUNCATE TABLE `email_automations`;
INSERT INTO `email_automations` (`name`, `trigger_event`, `email_template_id`, `conditions`, `audience_filter`, `sender_name`, `sender_email`, `reply_to`, `status`, `created_by`) VALUES
('Welcome Series',     'user.registered',     1, NULL,                                 '{"role":"user"}',                    'Admin',   'admin@example.com', 'support@example.com', 'active',  1),
('Abandoned Cart',     'cart.abandoned',      2, '{"field":"cart.total","operator":"greater_than","value":"50"}', '{"hours_since_update":24}', 'Sales',   'sales@example.com',  'support@example.com', 'draft',   2);

TRUNCATE TABLE `scheduled_tasks`;
INSERT INTO `scheduled_tasks` (`name`, `description`, `cron_expression`, `task_type`, `config`, `status`, `last_run_at`, `next_run_at`, `is_system`, `created_by`) VALUES
('Daily Stats Refresh',   'Refresh seller statistics every morning',    '0 2 * * *',     'run_workflow', '{"workflow_id":1}',                    'active',  NULL, DATE_ADD(NOW(), INTERVAL 1 DAY) + INTERVAL 2 HOUR, TRUE, 1),
('Weekly Report',         'Generate and email weekly sales report',     '0 8 * * 1',     'send_report',  '{"report_type":"sales","recipients":["admin@example.com"]}', 'active', NULL, DATE_ADD(NOW(), INTERVAL 7 - WEEKDAY(NOW()) DAY) + INTERVAL 8 HOUR, FALSE, 2),
('Hourly Cleanup',        'Cleanup old event logs',                     '0 * * * *',     'run_sql',      '{"query":"DELETE FROM event_log WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)"}', 'paused', NULL, NULL, TRUE, 1);

TRUNCATE TABLE `webhook_delivery_logs`;
INSERT INTO `webhook_delivery_logs` (`webhook_id`, `event_type`, `payload`, `request_headers`, `response_status`, `response_body`, `attempt`, `success`, `error_message`, `delivered_at`, `next_retry_at`) VALUES
(1, 'order.placed', '{"order_id":1,"event":"order.placed"}', '{"Content-Type":"application/json","X-Signature":"sha256=abc123"}', 200, '{"received":true}', 1, TRUE,  NULL, NOW() - INTERVAL 1 HOUR, NULL),
(1, 'order.placed', '{"order_id":2,"event":"order.placed"}', '{"Content-Type":"application/json","X-Signature":"sha256=def456"}', 502, 'Bad Gateway',    1, FALSE, 'HTTP 502 Bad Gateway', NOW() - INTERVAL 30 MINUTE, NOW() + INTERVAL 5 MINUTE);

-- ==========================================
-- 24. REPORTING & DASHBOARDS
-- ==========================================

TRUNCATE TABLE `report_categories`;
INSERT INTO `report_categories` (`id`, `name`, `slug`, `description`, `parent_id`, `sort_order`) VALUES
(1, 'Sales Reports',   'sales-reports',   'Revenue and order analytics',   NULL, 1),
(2, 'User Analytics',  'user-analytics',  'User behavior and engagement',  NULL, 2);

TRUNCATE TABLE `report_definitions`;
INSERT INTO `report_definitions` (`id`, `name`, `slug`, `description`, `category_id`, `report_type`, `config`, `is_system`, `created_by`) VALUES
(1, 'Daily Revenue Summary',   'daily-revenue-summary',   'Daily revenue breakdown by product',   1, 'tabular', '{"columns":["date","product","revenue","orders"],"filters":{"date_range":"today"}}',  TRUE, 1),
(2, 'User Growth Chart',       'user-growth-chart',       'Monthly user registration trends',     2, 'chart',   '{"type":"line","metrics":["registrations","activations"],"group_by":"month"}',      TRUE, 1);

TRUNCATE TABLE `report_schedules`;
INSERT INTO `report_schedules` (`report_id`, `name`, `cron_expression`, `recipients`, `format`, `config`, `last_run_at`, `next_run_at`, `status`, `created_by`) VALUES
(1, 'Daily Revenue', '0 8 * * *', '["admin@example.com"]', 'pdf', '{"time":"08:00","timezone":"UTC"}', NULL, DATE_ADD(NOW(), INTERVAL 1 DAY), 'active', 1),
(2, 'Weekly Summary', '0 9 * * 1', '["admin@example.com","sales@example.com"]', 'csv', '{"day":"monday","time":"09:00"}', NULL, DATE_ADD(NOW(), INTERVAL 7 - WEEKDAY(NOW()) DAY), 'active', 1);

TRUNCATE TABLE `dashboard_widgets`;
INSERT INTO `dashboard_widgets` (`user_id`, `dashboard_name`, `widget_type`, `title`, `config`, `position_x`, `position_y`, `width`, `height`) VALUES
(1, 'default', 'kpi', 'Revenue KPI', '{"metric":"total_revenue","label":"Revenue","prefix":"$","format":"number"}', 0, 0, 3, 1),
(1, 'default', 'chart', 'Order Timeline', '{"type":"bar","dataset":"daily_orders","period":"7d","stacked":false}', 0, 1, 4, 2);

-- ==========================================
-- 25. PRIVACY & GDPR
-- ==========================================

TRUNCATE TABLE `consent_logs`;
INSERT INTO `consent_logs` (`user_id`, `consent_type`, `purpose`, `granted`, `ip_address`, `user_agent`) VALUES
(1, 'marketing', NULL, TRUE, '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; rv:120.0) Gecko/20100101 Firefox/120.0'),
(2, 'functional', NULL, FALSE, '10.0.0.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15');

TRUNCATE TABLE `data_export_requests`;
INSERT INTO `data_export_requests` (`user_id`, `status`, `format`, `completed_at`, `file_path`, `expires_at`) VALUES
(1, 'completed', 'json', NOW(), '/exports/user_1_full_20260601.zip', DATE_ADD(NOW(), INTERVAL 30 DAY)),
(2, 'pending', 'csv', NULL, NULL, NULL);

TRUNCATE TABLE `data_deletion_requests`;
INSERT INTO `data_deletion_requests` (`user_id`, `status`, `reason`, `processed_at`, `processed_by`, `notes`) VALUES
(2, 'pending', 'User requested account anonymization', NULL, NULL, NULL),
(1, 'approved', 'Approved by support team', NULL, 1, NULL);

TRUNCATE TABLE `cookie_consent_settings`;
INSERT INTO `cookie_consent_settings` (`name`, `slug`, `description`, `required`, `default_granted`) VALUES
('Essential Cookies', 'essential', 'Required for site functionality', TRUE, TRUE),
('Analytics Cookies', 'analytics', 'Usage and performance tracking', FALSE, FALSE);

-- ==========================================
-- 26. TAX ENGINE
-- ==========================================

TRUNCATE TABLE `tax_jurisdictions`;
INSERT INTO `tax_jurisdictions` (`name`, `country`, `state`, `city`, `postal_code`, `rate`, `tax_type`, `is_compound`, `priority`, `status`) VALUES
('California State Tax', 'US', 'CA', NULL, NULL, 8.7500, 'sales', FALSE, 1, 'active'),
('NYC City Surcharge', 'US', 'NY', 'New York City', '100*', 4.5000, 'sales', TRUE, 2, 'active');

TRUNCATE TABLE `tax_exemptions`;
INSERT INTO `tax_exemptions` (`user_id`, `exemption_type`, `certificate_number`, `issuing_authority`, `valid_from`, `valid_to`, `status`, `verified_by`) VALUES
(2, 'reseller', 'RES-CA-2026-001', NULL, CURDATE(), DATE_ADD(NOW(), INTERVAL 365 DAY), 'active', 1),
(1, 'nonprofit', 'NPO-US-FED-12345', NULL, CURDATE(), NULL, 'pending', NULL);

TRUNCATE TABLE `tax_rules`;
INSERT INTO `tax_rules` (`name`, `priority`, `action_type`, `action_value`, `status`) VALUES
('CA Sales Tax', 1, 'rate_override', '{"rate":8.7500}', 'active'),
('NYC Surcharge', 2, 'compound', '{"rate":4.5000}', 'active');

TRUNCATE TABLE `tax_report_data`;
INSERT INTO `tax_report_data` (`tax_jurisdiction_id`, `period_start`, `period_end`, `taxable_amount`, `tax_collected`, `returns_filed`, `filed_at`) VALUES
(1, '2026-01-01', '2026-03-31', 150000.00, 13125.00, TRUE, '2026-04-15 10:00:00'),
(2, '2026-01-01', '2026-03-31', 50000.00, 2250.00, FALSE, NULL);

-- ==========================================
-- 27. DYNAMIC PRICING
-- ==========================================

TRUNCATE TABLE `price_rules`;
INSERT INTO `price_rules` (`name`, `slug`, `description`, `priority`, `conditions`, `adjustments`, `applies_to`, `stackable`, `status`, `starts_at`, `expires_at`, `created_by`) VALUES
('Summer Sale 20% Off', 'summer-sale-20', 'All products 20% off during summer', 10, '{"conditions":[{"field":"season","operator":"eq","value":"summer"}]}', '{"type":"percentage","value":20,"apply_to":"all"}', 'all', FALSE, 'active', '2026-06-01 00:00:00', '2026-08-31 23:59:59', 1),
('Bulk Purchase Tier', 'bulk-tier-10', '10% off for orders over $500', 20, '{"conditions":[{"field":"order_total","operator":"gte","value":500}]}', '{"type":"percentage","value":10,"apply_to":"order"}', 'all', FALSE, 'active', NULL, NULL, 1);

TRUNCATE TABLE `price_tiers`;
INSERT INTO `price_tiers` (`product_id`, `min_quantity`, `max_quantity`, `unit_price`) VALUES
(1, 10, 100, 49.99),
(1, 5, 50, 84.99);

TRUNCATE TABLE `price_overrides`;
INSERT INTO `price_overrides` (`user_id`, `product_id`, `override_price`, `override_type`, `starts_at`, `expires_at`, `created_by`) VALUES
(2, 1, 29.99, 'fixed', '2026-06-01 00:00:00', '2026-12-31 23:59:59', 1),
(2, 2, 499.99, 'fixed', '2026-06-01 00:00:00', '2026-12-31 23:59:59', 1);

TRUNCATE TABLE `price_rule_audit`;
INSERT INTO `price_rule_audit` (`price_rule_id`, `user_id`, `product_id`, `original_price`, `adjusted_price`, `rule_name`) VALUES
(1, 1, 1, 39.99, 31.99, 'Summer Sale 20% applied to Product 1'),
(2, 1, 2, 599.99, 539.99, 'Bulk tier 10% applied to Product 2');

-- ==========================================
-- 28. INTERNATIONALIZATION
-- ==========================================

TRUNCATE TABLE `language_packs`;
INSERT INTO `language_packs` (`code`, `name`, `native_name`, `is_rtl`, `is_default`, `is_active`) VALUES
('en', 'English', 'English', FALSE, TRUE, TRUE),
('es', 'Spanish', 'Español', FALSE, FALSE, TRUE);

TRUNCATE TABLE `translations`;
INSERT INTO `translations` (`language_pack_id`, `namespace`, `group`, `key`, `value`) VALUES
(2, 'frontend', 'general', 'welcome_message', '¡Bienvenido a LicensePro!'),
(2, 'frontend', 'general', 'checkout', 'Finalizar Compra');

TRUNCATE TABLE `translation_files`;
INSERT INTO `translation_files` (`language_pack_id`, `namespace`, `file_path`, `file_format`, `version`) VALUES
(1, 'frontend', '/lang/en/messages.php', 'json', 1),
(2, 'frontend', '/lang/es/messages.php', 'json', 1);

-- ==========================================
-- 29. CONTENT MODERATION
-- ==========================================

TRUNCATE TABLE `moderation_queue`;
INSERT INTO `moderation_queue` (`content_type`, `content_id`, `reported_by`, `status`, `priority`, `assigned_to`, `notes`) VALUES
('product_review', 1, 2, 'pending', 'normal', NULL, 'Inappropriate language in product review'),
('cms_page', 1, 1, 'pending', 'normal', NULL, 'Suspected spam content in CMS page body');

TRUNCATE TABLE `moderation_reports`;
INSERT INTO `moderation_reports` (`queue_item_id`, `reporter_id`, `reason_category`, `description`) VALUES
(1, 2, 'abuse', 'Review contains hate speech'),
(2, 1, 'spam', 'CMS page content appears to be spam with multiple external links');

TRUNCATE TABLE `moderation_actions`;
INSERT INTO `moderation_actions` (`queue_item_id`, `moderator_id`, `action`, `reason`) VALUES
(1, 1, 'warned', 'First offense - verbal warning issued'),
(2, 1, 'hidden', 'Content hidden pending further investigation');

TRUNCATE TABLE `moderation_blocklist`;
INSERT INTO `moderation_blocklist` (`block_type`, `value`, `reason`, `created_by`, `expires_at`) VALUES
('email', 'spammer@example.com', 'Known spammer from previous campaigns', 1, NULL),
('keyword', 'casino', 'Gambling-related content not permitted', 1, NULL);

-- ==========================================
-- 30. SYSTEM & API CONFIGURATION
-- ==========================================

TRUNCATE TABLE `rate_limit_rules`;
INSERT INTO `rate_limit_rules` (`name`, `route_pattern`, `http_method`, `max_requests`, `window_seconds`, `response_code`, `response_message`, `is_active`) VALUES
('API General', '/api/*', '*', 60, 60, 429, 'Too many requests. Please slow down.', TRUE),
('Auth Endpoint', '/api/auth/*', 'POST', 5, 60, 429, 'Too many auth attempts. Try again later.', TRUE);

TRUNCATE TABLE `rate_limit_logs`;
INSERT INTO `rate_limit_logs` (`rule_id`, `user_id`, `ip_address`, `route`, `http_method`, `identifier`, `hit_at`) VALUES
(1, 1, '192.168.1.100', '/api/products', 'GET', 'user_1', NOW() - INTERVAL 5 MINUTE),
(2, NULL, '10.0.0.99', '/api/auth/login', 'POST', '10.0.0.99', NOW() - INTERVAL 2 MINUTE);

TRUNCATE TABLE `health_checks`;
INSERT INTO `health_checks` (`check_type`, `status`, `response_time_ms`, `message`) VALUES
('database', 'pass', 12, 'Database Connectivity'),
('api', 'pass', 45, 'API Health Endpoint');

TRUNCATE TABLE `maintenance_windows`;
INSERT INTO `maintenance_windows` (`title`, `description`, `status`, `starts_at`, `ends_at`, `created_by`) VALUES
('Database Migration v2.1', 'Upgrade database server to version 2.1', 'scheduled', DATE_ADD(NOW(), INTERVAL 7 DAY), DATE_ADD(NOW(), INTERVAL 7 DAY) + INTERVAL 2 HOUR, 1),
('SSL Certificate Renewal', 'Renew expiring SSL certificate for CDN', 'completed', DATE_SUB(NOW(), INTERVAL 14 DAY), DATE_SUB(NOW(), INTERVAL 14 DAY) + INTERVAL 30 MINUTE, 1);

SET FOREIGN_KEY_CHECKS = 1;
