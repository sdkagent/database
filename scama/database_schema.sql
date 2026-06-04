-- ================================================================
-- DATABASE SCHEMA — Fully Optimized & Reorganized
-- All tables use InnoDB, utf8mb4 charset, BIGINT UNSIGNED PKs/FKs
-- ================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ==========================================
-- 1. CORE — USERS & AUTH
-- ==========================================

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `role`             VARCHAR(20) NOT NULL DEFAULT 'user',
  `name`             VARCHAR(255) NOT NULL,
  `email`            VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL,
  `password`         VARCHAR(255) NULL,
  `phone`            VARCHAR(50) NULL,
  `status`           VARCHAR(20) DEFAULT 'active',
  `ip_whitelist`     JSON NULL,
  `telegram_chat_id` VARCHAR(100) NULL,
  `settings`         JSON NULL,
  `avatar_url`       VARCHAR(500) NULL,
  `last_login_at`    TIMESTAMP NULL,
  `locale`           VARCHAR(5) NULL DEFAULT 'en',
  `timezone`         VARCHAR(50) NULL DEFAULT 'UTC',
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_users_email` (`email`),
  INDEX `idx_users_status` (`status`),
  INDEX `idx_users_role` (`role`),

  CONSTRAINT `chk_users_role`   CHECK (`role`   IN ('admin','user','seller','support')),
  CONSTRAINT `chk_users_status` CHECK (`status` IN ('active','suspended','pending','banned'))

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  `token`      VARCHAR(191) NOT NULL,
  `type`       VARCHAR(20) DEFAULT 'password',
  `expires_at` TIMESTAMP NULL,
  `used_at`    TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_password_resets_user` (`user_id`),
  INDEX `idx_password_resets_token` (`token`),
  INDEX `idx_password_resets_expires` (`expires_at`),
  CONSTRAINT `fk_password_resets_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `organizations`;
CREATE TABLE `organizations` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(255) NOT NULL,
  `slug`        VARCHAR(255) NOT NULL UNIQUE,
  `logo_url`    VARCHAR(500) NULL,
  `website`     VARCHAR(500) NULL,
  `status`      ENUM('active','suspended') DEFAULT 'active',
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_organizations_slug` (`slug`),
  INDEX `idx_organizations_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(100) NOT NULL,
  `slug`        VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `is_system`        BOOLEAN DEFAULT FALSE,

  `organization_id`  BIGINT UNSIGNED NULL,

  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,



  UNIQUE INDEX `unique_roles_slug` (`slug`),

  INDEX `idx_roles_organization` (`organization_id`),

  CONSTRAINT `fk_roles_organization` FOREIGN KEY (`organization_id`) REFERENCES `organizations`(`id`) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(100) NOT NULL,
  `slug`        VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `group`            VARCHAR(100) NULL,

  `organization_id`  BIGINT UNSIGNED NULL,

  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,



  UNIQUE INDEX `unique_permissions_slug` (`slug`),

  INDEX `idx_permissions_organization` (`organization_id`),

  CONSTRAINT `fk_permissions_organization` FOREIGN KEY (`organization_id`) REFERENCES `organizations`(`id`) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `role_id`       BIGINT UNSIGNED NOT NULL,
  `permission_id` BIGINT UNSIGNED NOT NULL,

  PRIMARY KEY (`role_id`, `permission_id`),
  INDEX `idx_role_permissions_permission` (`permission_id`),
  CONSTRAINT `fk_role_permissions_role`       FOREIGN KEY (`role_id`)       REFERENCES `roles`(`id`)       ON DELETE CASCADE,
  CONSTRAINT `fk_role_permissions_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (

  `user_id`          BIGINT UNSIGNED NOT NULL,

  `role_id`          BIGINT UNSIGNED NOT NULL,

  `organization_id`  BIGINT UNSIGNED NOT NULL,

  `assigned_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,



  PRIMARY KEY (`user_id`, `role_id`, `organization_id`),

  INDEX `idx_user_roles_role` (`role_id`),

  INDEX `idx_user_roles_organization` (`organization_id`),

  INDEX `idx_user_roles_user_org` (`user_id`, `organization_id`),

  CONSTRAINT `fk_user_roles_user`         FOREIGN KEY (`user_id`)         REFERENCES `users`(`id`)         ON DELETE CASCADE,

  CONSTRAINT `fk_user_roles_role`         FOREIGN KEY (`role_id`)         REFERENCES `roles`(`id`)         ON DELETE CASCADE,

  CONSTRAINT `fk_user_roles_organization` FOREIGN KEY (`organization_id`) REFERENCES `organizations`(`id`) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `organization_members`;
CREATE TABLE `organization_members` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `organization_id` BIGINT UNSIGNED NOT NULL,
  `user_id`         BIGINT UNSIGNED NOT NULL,
  `role`            ENUM('owner','admin','member','viewer') NOT NULL DEFAULT 'member',
  `joined_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_org_members_org_user` (`organization_id`, `user_id`),
  INDEX `idx_org_members_user` (`user_id`),
  CONSTRAINT `fk_org_members_org` FOREIGN KEY (`organization_id`) REFERENCES `organizations`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_org_members_user` FOREIGN KEY (`user_id`)         REFERENCES `users`(`id`)           ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `seller_profiles`;
CREATE TABLE `seller_profiles` (
  `user_id`            BIGINT UNSIGNED NOT NULL,
  `store_name`         VARCHAR(100) NULL,
  `store_description`  TEXT NULL,
  `store_logo_url`     VARCHAR(500) NULL,
  `store_cover_url`    VARCHAR(500) NULL,
  `status`             ENUM('pending','active','suspended','banned') DEFAULT 'pending',
  `current_balance`    DECIMAL(15,4) DEFAULT 0.0000,
  `default_commission` DECIMAL(5,2) DEFAULT 80.00,
  `verified_at`        TIMESTAMP NULL,
  `created_at`         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`         TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`user_id`),
  INDEX `idx_seller_profiles_status` (`status`),
  CONSTRAINT `fk_seller_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `payout_accounts`;
CREATE TABLE `payout_accounts` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `seller_id`        BIGINT UNSIGNED NOT NULL,
  `method`           ENUM('bank','paypal','stripe','crypto','bkash') NOT NULL,
  `account_label`    VARCHAR(100) NULL,
  `account_details`  JSON NULL,
  `is_default`       BOOLEAN DEFAULT FALSE,
  `status`           ENUM('active','inactive') DEFAULT 'active',
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_payout_accounts_seller` (`seller_id`),
  INDEX `idx_payout_accounts_seller_default` (`seller_id`, `is_default`),
  CONSTRAINT `fk_payout_accounts_seller` FOREIGN KEY (`seller_id`) REFERENCES `seller_profiles`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `payout_transactions`;
CREATE TABLE `payout_transactions` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `seller_id`         BIGINT UNSIGNED NOT NULL,
  `payout_account_id` BIGINT UNSIGNED NULL,
  `amount`            DECIMAL(15,2) NOT NULL,
  `fee`               DECIMAL(15,2) DEFAULT 0.00,
  `net_amount`        DECIMAL(15,2) NOT NULL,
  `currency`          VARCHAR(3) DEFAULT 'USD',
  `period_start`      DATE NULL,
  `period_end`        DATE NULL,
  `status`            ENUM('pending','processing','completed','failed') DEFAULT 'pending',
  `reference`         VARCHAR(255) NULL,
  `notes`             TEXT NULL,
  `processed_at`      TIMESTAMP NULL,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_payout_transactions_seller` (`seller_id`),
  INDEX `idx_payout_transactions_account` (`payout_account_id`),
  INDEX `idx_payout_transactions_status` (`status`),
  INDEX `idx_payout_transactions_seller_status` (`seller_id`, `status`),
  CONSTRAINT `fk_payout_transactions_seller`  FOREIGN KEY (`seller_id`)         REFERENCES `seller_profiles`(`user_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_payout_transactions_account` FOREIGN KEY (`payout_account_id`) REFERENCES `payout_accounts`(`id`)       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `balance_ledger`;
CREATE TABLE `balance_ledger` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `seller_id`      BIGINT UNSIGNED NULL,
  `type`           ENUM('sale_credit','commission_earned','payout_debit','adjustment','fee') NOT NULL,
  `amount`         DECIMAL(15,4) NOT NULL,
  `balance_before` DECIMAL(15,4) NOT NULL,
  `balance_after`  DECIMAL(15,4) NOT NULL,
  `reference_type` VARCHAR(50) NULL,
  `reference_id`   BIGINT UNSIGNED NULL,
  `description`    TEXT NULL,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_balance_ledger_seller` (`seller_id`),
  INDEX `idx_balance_ledger_type` (`type`),
  INDEX `idx_balance_ledger_reference` (`reference_type`, `reference_id`),
  INDEX `idx_balance_ledger_seller_created` (`seller_id`, `created_at`),
  CONSTRAINT `fk_balance_ledger_seller` FOREIGN KEY (`seller_id`) REFERENCES `seller_profiles`(`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `seller_verification`;
CREATE TABLE `seller_verification` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `seller_id`       BIGINT UNSIGNED NOT NULL,
  `document_type`   ENUM('id_card','passport','business_license','tax_id') NOT NULL,
  `document_url`    VARCHAR(500) NOT NULL,
  `status`          ENUM('pending','approved','rejected') DEFAULT 'pending',
  `verified_by`     BIGINT UNSIGNED NULL,
  `verified_at`     TIMESTAMP NULL,
  `rejection_reason` TEXT NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_seller_verification_seller` (`seller_id`),
  INDEX `idx_seller_verification_status` (`status`),
  INDEX `idx_seller_verification_seller_status` (`seller_id`, `status`),
  INDEX `idx_seller_verification_verified_by` (`verified_by`),
  CONSTRAINT `fk_seller_verification_seller`    FOREIGN KEY (`seller_id`)    REFERENCES `seller_profiles`(`user_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_seller_verification_verified_by`  FOREIGN KEY (`verified_by`)  REFERENCES `users`(`id`)                ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `seller_stats`;
CREATE TABLE `seller_stats` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `seller_id`      BIGINT UNSIGNED NOT NULL,
  `period_type`    ENUM('daily','weekly','monthly') NOT NULL,
  `period_date`    DATE NOT NULL,
  `total_sales`    DECIMAL(15,2) DEFAULT 0.00,
  `total_earnings` DECIMAL(15,2) DEFAULT 0.00,
  `total_orders`   INT DEFAULT 0,
  `total_products` INT DEFAULT 0,
  `avg_rating`     DECIMAL(3,2) NULL,
  `review_count`   INT DEFAULT 0,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_seller_stats_seller_period` (`seller_id`, `period_type`, `period_date`),
  INDEX `idx_seller_stats_seller` (`seller_id`),
  INDEX `idx_seller_stats_period` (`period_type`, `period_date`),
  CONSTRAINT `fk_seller_stats_seller` FOREIGN KEY (`seller_id`) REFERENCES `seller_profiles`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `auth_logs`;
CREATE TABLE `auth_logs` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`           BIGINT UNSIGNED NULL,
  `type`              ENUM('login','logout','failed_login','2fa_attempt','magic_link','password_reset') NOT NULL,
  `ip_address`        VARCHAR(45) NOT NULL,
  `device_fingerprint` VARCHAR(255) NULL,
  `status`            ENUM('success','failed','suspicious') DEFAULT 'success',
  `details`           JSON NULL,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_auth_logs_user_type` (`user_id`, `type`),
  INDEX `idx_auth_logs_created` (`created_at`),
  CONSTRAINT `fk_auth_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 2. PRODUCTS & SUBSCRIPTIONS
-- ==========================================

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `seller_id`        BIGINT UNSIGNED NULL,
  `name`             VARCHAR(255) NOT NULL,
  `slug`             VARCHAR(255) NOT NULL,
  `description`      TEXT NULL,
  `type`             ENUM('script','software','plugin','saas','desktop') NOT NULL,
  `base_price`       DECIMAL(10,2) DEFAULT 0.00,
  `sku`              VARCHAR(100) NULL,
  `stock`            INT UNSIGNED NULL,
  `download_limit`   INT UNSIGNED NULL,
  `total_sales`      INT UNSIGNED DEFAULT 0,
  `avg_rating`       DECIMAL(3,2) DEFAULT 0.00,
  `review_count`     INT UNSIGNED DEFAULT 0,
  `version`          VARCHAR(20) NULL,
  `download_url`     VARCHAR(500) NULL,
  `status`           ENUM('draft','active','archived') DEFAULT 'draft',
  `demo_url`         VARCHAR(500) NULL,
  `docs_url`         VARCHAR(500) NULL,
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_products_slug` (`slug`),
  UNIQUE INDEX `unique_products_sku` (`sku`),
  INDEX `idx_products_seller` (`seller_id`),
  INDEX `idx_products_status` (`status`),
  INDEX `idx_products_seller_status` (`seller_id`, `status`),
  FULLTEXT INDEX `ft_products_search` (`name`, `description`),

  CONSTRAINT `fk_products_seller` FOREIGN KEY (`seller_id`) REFERENCES `seller_profiles`(`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `product_hardware_requirements`;

CREATE TABLE `product_hardware_requirements` (

  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  `product_id`      BIGINT UNSIGNED NOT NULL,

  `os_name`         VARCHAR(50) NULL,

  `os_version_min`  VARCHAR(20) NULL,

  `cpu_cores_min`   INT NULL,

  `memory_mb_min`   INT NULL,

  `disk_mb_min`     INT NULL,

  `additional_notes` TEXT NULL,



  INDEX `idx_product_hardware_requirements_product` (`product_id`),

  CONSTRAINT `fk_product_hardware_requirements_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `subscription_plans`;
CREATE TABLE `subscription_plans` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`             VARCHAR(50) NOT NULL,
  `code`             VARCHAR(50) NULL,
  `duration_months`  INT NOT NULL,
  `max_activations`  INT NOT NULL DEFAULT 1,
  `price_monthly`    DECIMAL(10,2) NOT NULL,
  `price_yearly`     DECIMAL(10,2) NOT NULL,
  `features`         JSON NULL,
  `status`           BOOLEAN DEFAULT TRUE,
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_subscription_plans_code` (`code`),
  INDEX `idx_plans_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `user_subscriptions`;
CREATE TABLE `user_subscriptions` (
  `id`                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`               BIGINT UNSIGNED NOT NULL,
  `plan_id`               BIGINT UNSIGNED NOT NULL,
  `product_id`            BIGINT UNSIGNED NULL,
  `status`                ENUM('active','cancelled','expired','past_due') DEFAULT 'active',
  `start_date`            TIMESTAMP NOT NULL,
  `end_date`              TIMESTAMP NOT NULL,
  `trial_ends_at`         TIMESTAMP NULL,
  `stripe_subscription_id` VARCHAR(255) NULL,
  `created_at`            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_user_subscriptions_user` (`user_id`),
  INDEX `idx_user_subscriptions_plan` (`plan_id`),
  INDEX `idx_user_subscriptions_product` (`product_id`),
  INDEX `idx_user_subscriptions_status` (`status`),
  INDEX `idx_user_subscriptions_user_status` (`user_id`, `status`),
  CONSTRAINT `fk_user_subscriptions_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)              ON DELETE CASCADE,
  CONSTRAINT `fk_user_subscriptions_plan`    FOREIGN KEY (`plan_id`)    REFERENCES `subscription_plans`(`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_user_subscriptions_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)            ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `wishlists`;

CREATE TABLE `wishlists` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_wishlists_user_product` (`user_id`, `product_id`),
  INDEX `idx_wishlists_product` (`product_id`),
  CONSTRAINT `fk_wishlists_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)    ON DELETE CASCADE,
  CONSTRAINT `fk_wishlists_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE `product_categories` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `parent_id`   BIGINT UNSIGNED NULL,
  `name`        VARCHAR(255) NOT NULL,
  `slug`        VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `image_url`   VARCHAR(500) NULL,
  `sort_order`  INT DEFAULT 0,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_product_categories_slug` (`slug`),
  INDEX `idx_product_categories_parent` (`parent_id`),
  CONSTRAINT `fk_product_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `product_categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `product_category_items`;
CREATE TABLE `product_category_items` (
  `product_id`  BIGINT UNSIGNED NOT NULL,
  `category_id` BIGINT UNSIGNED NOT NULL,

  PRIMARY KEY (`product_id`, `category_id`),
  INDEX `idx_prod_cat_items_category` (`category_id`),
  CONSTRAINT `fk_prod_cat_items_product`  FOREIGN KEY (`product_id`)  REFERENCES `products`(`id`)            ON DELETE CASCADE,
  CONSTRAINT `fk_prod_cat_items_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `product_discounts`;
CREATE TABLE `product_discounts` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id`      BIGINT UNSIGNED NOT NULL,
  `name`            VARCHAR(255) NOT NULL,
  `type`            ENUM('percentage','fixed') NOT NULL,
  `value`           DECIMAL(10,2) NOT NULL,
  `max_uses`        INT UNSIGNED NULL,
  `used_count`      INT UNSIGNED DEFAULT 0,
  `starts_at`       TIMESTAMP NOT NULL,
  `ends_at`         TIMESTAMP NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_product_discounts_product` (`product_id`),
  INDEX `idx_product_discounts_dates` (`starts_at`, `ends_at`),
  CONSTRAINT `fk_product_discounts_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 3. BILLING
-- ==========================================

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`         BIGINT UNSIGNED NOT NULL,
  `order_id`        BIGINT UNSIGNED NULL,
  `subscription_id` BIGINT UNSIGNED NULL,
  `invoice_number`  VARCHAR(50) NULL,
  `total`           DECIMAL(10,2) NOT NULL,
  `tax`             DECIMAL(10,2) DEFAULT 0.00,
  `status`          ENUM('draft','open','paid','void','refunded') DEFAULT 'draft',
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_invoice_number` (`invoice_number`),
  INDEX `idx_invoices_user` (`user_id`),
  INDEX `idx_invoices_order` (`order_id`),
  INDEX `idx_invoices_subscription` (`subscription_id`),
  CONSTRAINT `fk_invoices_user`         FOREIGN KEY (`user_id`)         REFERENCES `users`(`id`)              ON DELETE CASCADE,
  CONSTRAINT `fk_invoices_order`        FOREIGN KEY (`order_id`)        REFERENCES `orders`(`id`)             ON DELETE SET NULL,
  CONSTRAINT `fk_invoices_subscription` FOREIGN KEY (`subscription_id`) REFERENCES `user_subscriptions`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `invoice_id`     BIGINT UNSIGNED NOT NULL,
  `gateway`        VARCHAR(50) NOT NULL,
  `transaction_id` VARCHAR(255) NULL,
  `amount`         DECIMAL(10,2) NOT NULL,
  `status`         ENUM('pending','success','failed') DEFAULT 'pending',
  `meta`           JSON NULL,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_payments_invoice` (`invoice_id`),
  INDEX `idx_payments_gateway` (`gateway`),
  CONSTRAINT `fk_payments_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `tax_rates`;
CREATE TABLE `tax_rates` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(255) NOT NULL,
  `rate`        DECIMAL(5,2) NOT NULL,
  `type`        ENUM('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `country`     VARCHAR(2) NULL,
  `region`      VARCHAR(100) NULL,
  `is_default`  BOOLEAN DEFAULT FALSE,
  `active`      BOOLEAN DEFAULT TRUE,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_tax_rates_country` (`country`),
  INDEX `idx_tax_rates_active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 4. ORDERS & COMMERCE
-- ==========================================

DROP TABLE IF EXISTS `coupons`;

CREATE TABLE `coupons` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `code`              VARCHAR(50) NOT NULL,
  `type`              ENUM('percentage','fixed_amount') NOT NULL,
  `value`             DECIMAL(10,2) NOT NULL,
  `min_order_amount`  DECIMAL(10,2) DEFAULT 0.00,
  `max_uses`          INT NULL,
  `used_count`        INT DEFAULT 0,
  `starts_at`         TIMESTAMP NULL,
  `expires_at`        TIMESTAMP NULL,
  `is_active`         BOOLEAN DEFAULT TRUE,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_coupons_code` (`code`),
  INDEX `idx_coupons_active` (`is_active`),
  INDEX `idx_coupons_code_active` (`code`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `carts`;

CREATE TABLE `carts` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  `coupon_id`  BIGINT UNSIGNED NULL,
  `subtotal`   DECIMAL(10,2) DEFAULT 0.00,
  `tax`        DECIMAL(10,2) DEFAULT 0.00,
  `total`      DECIMAL(10,2) DEFAULT 0.00,
  `expires_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_carts_user` (`user_id`),
  INDEX `idx_carts_coupon` (`coupon_id`),
  INDEX `idx_carts_user_expires` (`user_id`, `expires_at`),
  CONSTRAINT `fk_carts_user`   FOREIGN KEY (`user_id`)   REFERENCES `users`(`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_carts_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cart_items`;

CREATE TABLE `cart_items` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `cart_id`    BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `plan_id`    BIGINT UNSIGNED NULL,
  `quantity`   INT DEFAULT 1,
  `unit_price` DECIMAL(10,2) NOT NULL,
  `subtotal`   DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_cart_items_cart_product_plan` (`cart_id`, `product_id`, `plan_id`),
  INDEX `idx_cart_items_cart` (`cart_id`),
  INDEX `idx_cart_items_product` (`product_id`),
  INDEX `idx_cart_items_plan` (`plan_id`),
  CONSTRAINT `fk_cart_items_cart`    FOREIGN KEY (`cart_id`)    REFERENCES `carts`(`id`)              ON DELETE CASCADE,
  CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)            ON DELETE CASCADE,
  CONSTRAINT `fk_cart_items_plan`    FOREIGN KEY (`plan_id`)    REFERENCES `subscription_plans`(`id`)   ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id`                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`             BIGINT UNSIGNED NOT NULL,
  `order_number`        VARCHAR(50) NOT NULL,
  `status`              VARCHAR(20) DEFAULT 'pending',
  `subtotal`            DECIMAL(10,2) NOT NULL,
  `tax`                 DECIMAL(10,2) DEFAULT 0.00,
  `discount_total`      DECIMAL(10,2) DEFAULT 0.00,
  `total`               DECIMAL(10,2) NOT NULL,
  `currency`            VARCHAR(3) DEFAULT 'USD',
  `notes`               TEXT NULL,
  `billing_address_id`  BIGINT UNSIGNED NULL,
  `shipping_address_id` BIGINT UNSIGNED NULL,
  `coupon_id`           BIGINT UNSIGNED NULL,
  `api_client_id`       BIGINT UNSIGNED NULL,
  `customer_notes`      TEXT NULL,
  `ip_address`          VARCHAR(45) NULL,
  `user_agent`          TEXT NULL,
  `paid_at`             TIMESTAMP NULL,
  `cancelled_at`        TIMESTAMP NULL,
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_orders_number` (`order_number`),
  INDEX `idx_orders_user` (`user_id`),
  INDEX `idx_orders_status` (`status`),
  INDEX `idx_orders_billing` (`billing_address_id`),
  INDEX `idx_orders_shipping` (`shipping_address_id`),
  INDEX `idx_orders_coupon` (`coupon_id`),
  INDEX `idx_orders_api_client` (`api_client_id`),
  INDEX `idx_orders_paid` (`paid_at`),
  INDEX `idx_orders_user_status` (`user_id`, `status`),
  INDEX `idx_orders_status_paid` (`status`, `paid_at`),
  INDEX `idx_orders_user_created` (`user_id`, `created_at`),

  CONSTRAINT `chk_orders_status` CHECK (`status` IN ('pending','confirmed','processing','completed','cancelled','refunded')),

  CONSTRAINT `fk_orders_user`     FOREIGN KEY (`user_id`)             REFERENCES `users`(`id`)      ON DELETE CASCADE,
  CONSTRAINT `fk_orders_billing`  FOREIGN KEY (`billing_address_id`)  REFERENCES `user_addresses`(`id`)   ON DELETE SET NULL,
  CONSTRAINT `fk_orders_shipping` FOREIGN KEY (`shipping_address_id`) REFERENCES `user_addresses`(`id`)   ON DELETE SET NULL,
  CONSTRAINT `fk_orders_coupon`   FOREIGN KEY (`coupon_id`)           REFERENCES `coupons`(`id`)     ON DELETE SET NULL,
  CONSTRAINT `fk_orders_api_client` FOREIGN KEY (`api_client_id`)    REFERENCES `api_clients`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_id`    BIGINT UNSIGNED NOT NULL,
  `product_id`  BIGINT UNSIGNED NULL,
  `plan_id`     BIGINT UNSIGNED NULL,
  `item_type`   ENUM('product','subscription') NOT NULL,
  `name`        VARCHAR(255) NOT NULL,
  `quantity`    INT DEFAULT 1,
  `unit_price`  DECIMAL(10,2) NOT NULL,
  `subtotal`    DECIMAL(10,2) NOT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_order_items_order` (`order_id`),
  INDEX `idx_order_items_product` (`product_id`),
  INDEX `idx_order_items_plan` (`plan_id`),
  UNIQUE INDEX `idx_order_items_order_product` (`order_id`, `product_id`),
  CONSTRAINT `fk_order_items_order`   FOREIGN KEY (`order_id`)   REFERENCES `orders`(`id`)              ON DELETE CASCADE,
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)             ON DELETE SET NULL,
  CONSTRAINT `fk_order_items_plan`    FOREIGN KEY (`plan_id`)    REFERENCES `subscription_plans`(`id`)   ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `order_item_metadata`;
CREATE TABLE `order_item_metadata` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_item_id`  BIGINT UNSIGNED NOT NULL,
  `license_id`     BIGINT UNSIGNED NULL,
  `meta_key`       VARCHAR(100) NOT NULL,
  `meta_value`     TEXT NULL,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_order_item_metadata_item_key` (`order_item_id`, `meta_key`),
  INDEX `idx_order_item_metadata_license` (`license_id`),
  CONSTRAINT `fk_order_item_metadata_order_item`    FOREIGN KEY (`order_item_id`) REFERENCES `order_items`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_item_metadata_license` FOREIGN KEY (`license_id`)    REFERENCES `licenses`(`id`)    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `order_status_history`;
CREATE TABLE `order_status_history` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_id`    BIGINT UNSIGNED NOT NULL,
  `from_status` ENUM('pending','confirmed','processing','completed','cancelled','refunded') NULL,
  `to_status`   ENUM('pending','confirmed','processing','completed','cancelled','refunded') NOT NULL,
  `changed_by`  BIGINT UNSIGNED NULL,
  `api_client_id` BIGINT UNSIGNED NULL,
  `reason`      TEXT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_order_status_history_order` (`order_id`),
  INDEX `idx_order_status_history_changed` (`changed_by`),
  INDEX `idx_order_status_history_api_client` (`api_client_id`),
  INDEX `idx_order_status_history_order_created` (`order_id`, `created_at`),
  CONSTRAINT `fk_order_status_history_order`      FOREIGN KEY (`order_id`)      REFERENCES `orders`(`id`)      ON DELETE CASCADE,
  CONSTRAINT `fk_order_status_history_changed_by`    FOREIGN KEY (`changed_by`)    REFERENCES `users`(`id`)       ON DELETE SET NULL,
  CONSTRAINT `fk_order_status_history_api_client` FOREIGN KEY (`api_client_id`) REFERENCES `api_clients`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `refunds`;

CREATE TABLE `refunds` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_id`      BIGINT UNSIGNED NOT NULL,
  `payment_id`    BIGINT UNSIGNED NULL,
  `amount`        DECIMAL(10,2) NOT NULL,
  `reason`        TEXT NULL,
  `status`        ENUM('pending','approved','rejected','completed') DEFAULT 'pending',
  `processed_by`  BIGINT UNSIGNED NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_refunds_order` (`order_id`),
  INDEX `idx_refunds_payment` (`payment_id`),
  INDEX `idx_refunds_processed` (`processed_by`),
  INDEX `idx_refunds_order_status` (`order_id`, `status`),
  CONSTRAINT `fk_refunds_order`     FOREIGN KEY (`order_id`)     REFERENCES `orders`(`id`)   ON DELETE CASCADE,
  CONSTRAINT `fk_refunds_payment`   FOREIGN KEY (`payment_id`)   REFERENCES `payments`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_refunds_processed` FOREIGN KEY (`processed_by`) REFERENCES `users`(`id`)    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `shipments`;
CREATE TABLE `shipments` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_id`        BIGINT UNSIGNED NOT NULL,
  `tracking_number` VARCHAR(255) NULL,
  `carrier`         VARCHAR(100) NULL,
  `status`          ENUM('pending','shipped','delivered','returned') DEFAULT 'pending',
  `shipped_at`      TIMESTAMP NULL,
  `delivered_at`    TIMESTAMP NULL,
  `shipping_data`   JSON NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_shipments_order` (`order_id`),
  INDEX `idx_shipments_status` (`status`),
  INDEX `idx_shipments_tracking` (`tracking_number`),
  CONSTRAINT `fk_shipments_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `file_downloads`;
CREATE TABLE `file_downloads` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_item_id`   BIGINT UNSIGNED NOT NULL,
  `user_id`         BIGINT UNSIGNED NOT NULL,
  `ip_address`      VARCHAR(45) NULL,
  `download_count`  INT UNSIGNED DEFAULT 0,
  `last_downloaded` TIMESTAMP NULL,
  `expires_at`      TIMESTAMP NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_file_downloads_order_item` (`order_item_id`),
  INDEX `idx_file_downloads_user` (`user_id`),
  CONSTRAINT `fk_file_downloads_order_item` FOREIGN KEY (`order_item_id`) REFERENCES `order_items`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_file_downloads_user`       FOREIGN KEY (`user_id`)       REFERENCES `users`(`id`)       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `affiliates`;
CREATE TABLE `affiliates` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`       BIGINT UNSIGNED NOT NULL,
  `code`          VARCHAR(50) NOT NULL UNIQUE,
  `commission_rate` DECIMAL(5,2) DEFAULT 10.00,
  `total_earned`  DECIMAL(12,2) DEFAULT 0.00,
  `total_paid`    DECIMAL(12,2) DEFAULT 0.00,
  `status`        ENUM('active','suspended') DEFAULT 'active',
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_affiliates_code` (`code`),
  INDEX `idx_affiliates_user` (`user_id`),
  INDEX `idx_affiliates_status` (`status`),
  CONSTRAINT `fk_affiliates_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `referrals`;
CREATE TABLE `referrals` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `affiliate_id` BIGINT UNSIGNED NOT NULL,
  `referred_id`  BIGINT UNSIGNED NOT NULL,
  `order_id`     BIGINT UNSIGNED NULL,
  `commission`   DECIMAL(10,2) DEFAULT 0.00,
  `status`       ENUM('pending','paid','cancelled') DEFAULT 'pending',
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_referrals_referred` (`affiliate_id`, `referred_id`),
  INDEX `idx_referrals_order` (`order_id`),
  CONSTRAINT `fk_referrals_affiliate` FOREIGN KEY (`affiliate_id`) REFERENCES `affiliates`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_referrals_referred`  FOREIGN KEY (`referred_id`)  REFERENCES `users`(`id`)      ON DELETE CASCADE,
  CONSTRAINT `fk_referrals_order`     FOREIGN KEY (`order_id`)     REFERENCES `orders`(`id`)     ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 5. LICENSING SYSTEM
-- ==========================================

DROP TABLE IF EXISTS `api_clients`;
CREATE TABLE `api_clients` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`      BIGINT UNSIGNED NOT NULL,
  `name`         VARCHAR(100) NULL,
  `api_key`      VARCHAR(64) NOT NULL,
  `api_secret`   VARCHAR(255) NULL,
  `status`       ENUM('active','suspended','revoked') DEFAULT 'active',
  `rate_limit`   INT DEFAULT 60,
  `last_used_at` TIMESTAMP NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_api_clients_key` (`api_key`),
  INDEX `idx_api_clients_user` (`user_id`),
  CONSTRAINT `fk_api_clients_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `licenses`;
CREATE TABLE `licenses` (
  `id`                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`             BIGINT UNSIGNED NOT NULL,
  `product_id`          BIGINT UNSIGNED NULL,
  `api_client_id`       BIGINT UNSIGNED NULL,
  `subscription_id`     BIGINT UNSIGNED NULL,
  `license_key`         VARCHAR(64) NOT NULL,
  `api_key`             VARCHAR(64) NOT NULL,
  `status`              ENUM('active','suspended','expired','revoked') DEFAULT 'active',
  `expires_at`          TIMESTAMP NULL,
  `max_activations`     INT DEFAULT 1,
  `current_activations` INT DEFAULT 0,
  `last_activity_at`    TIMESTAMP NULL,
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_licenses_key` (`license_key`),
  INDEX `idx_licenses_user` (`user_id`),
  INDEX `idx_licenses_product` (`product_id`),
  INDEX `idx_licenses_api_client` (`api_client_id`),
  INDEX `idx_licenses_subscription` (`subscription_id`),
  INDEX `idx_licenses_status` (`status`),
  INDEX `idx_licenses_user_status` (`user_id`, `status`),
  INDEX `idx_licenses_api_key` (`api_key`, `license_key`),
  CONSTRAINT `fk_licenses_user`         FOREIGN KEY (`user_id`)         REFERENCES `users`(`id`)              ON DELETE CASCADE,
  CONSTRAINT `fk_licenses_product`      FOREIGN KEY (`product_id`)      REFERENCES `products`(`id`)           ON DELETE SET NULL,
  CONSTRAINT `fk_licenses_api_client`   FOREIGN KEY (`api_client_id`)   REFERENCES `api_clients`(`id`)        ON DELETE SET NULL,
  CONSTRAINT `fk_licenses_subscription` FOREIGN KEY (`subscription_id`) REFERENCES `user_subscriptions`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `license_activations`;
CREATE TABLE `license_activations` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `license_id`     BIGINT UNSIGNED NOT NULL,
  `domain`         VARCHAR(255) NULL,
  `hosting_ip`     VARCHAR(45) NULL,
  `status`         ENUM('active','inactive','suspicious','banned') DEFAULT 'active',
  `last_verified_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `meta`           JSON NULL,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_license_activations_license` (`license_id`),
  INDEX `idx_license_activations_domain` (`domain`),
  INDEX `idx_license_activations_status` (`status`),
  INDEX `idx_license_activations_license_status` (`license_id`, `status`),
  CONSTRAINT `fk_license_activations_license` FOREIGN KEY (`license_id`) REFERENCES `licenses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `hardware_activations`;
CREATE TABLE `hardware_activations` (
  `id`                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `license_id`          BIGINT UNSIGNED NOT NULL,
  `machine_id`          VARCHAR(100) NOT NULL,
  `cpu_id`              VARCHAR(100) NULL,
  `motherboard_serial`  VARCHAR(100) NULL,
  `bios_serial`         VARCHAR(100) NULL,
  `disk_serial`         VARCHAR(100) NULL,
  `mac_address`         VARCHAR(17) NULL,
  `os_name`             VARCHAR(50) NULL,
  `os_version`          VARCHAR(20) NULL,
  `os_architecture`     VARCHAR(10) NULL,
  `cpu_name`            VARCHAR(100) NULL,
  `cpu_cores`           INT NULL,
  `total_memory`        INT NULL,
  `system_manufacturer` VARCHAR(100) NULL,
  `system_model`        VARCHAR(100) NULL,
  `local_ip`            VARCHAR(45) NULL,
  `public_ip`           VARCHAR(45) NULL,
  `status`              ENUM('active','inactive','suspicious','banned') DEFAULT 'active',
  `activation_limit`    INT DEFAULT 1,
  `activated_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `last_ping_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `required_os_min`     VARCHAR(50) NULL,
  `required_memory_mb`  INT NULL,
  `required_disk_mb`    INT NULL,
  `compatibility_status` ENUM('compatible','incompatible','unknown') DEFAULT 'unknown',
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_hardware_activations_license_machine` (`license_id`, `machine_id`),
  INDEX `idx_hardware_activations_license` (`license_id`),
  INDEX `idx_hardware_activations_status` (`status`),
  INDEX `idx_hardware_activations_license_status` (`license_id`, `status`),
  CONSTRAINT `fk_hardware_activations_license` FOREIGN KEY (`license_id`) REFERENCES `licenses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `hardware_activation_logs`;
CREATE TABLE `hardware_activation_logs` (
  `id`                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `hardware_activation_id` BIGINT UNSIGNED NULL,
  `license_id`            BIGINT UNSIGNED NOT NULL,
  `machine_id`            VARCHAR(100) NOT NULL,
  `hardware_snapshot`     JSON NULL,
  `system_specs`          JSON NULL,
  `activation_status`     ENUM('success','failed','hardware_mismatch','limit_exceeded','suspicious') NOT NULL,
  `failure_reason`        VARCHAR(255) NULL,
  `vm_detected`           BOOLEAN DEFAULT FALSE,
  `tamper_detected`       BOOLEAN DEFAULT FALSE,
  `compatibility_result`  ENUM('compatible','incompatible','unknown') DEFAULT 'unknown',
  `created_at`            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_hardware_activation_logs_license_machine` (`license_id`, `machine_id`),
  INDEX `idx_hardware_activation_logs_activation` (`hardware_activation_id`),
  INDEX `idx_hardware_activation_logs_created` (`created_at`),
  CONSTRAINT `fk_hardware_activation_logs_license`   FOREIGN KEY (`license_id`)            REFERENCES `licenses`(`id`)             ON DELETE CASCADE,
  CONSTRAINT `fk_hardware_activation_logs_activation` FOREIGN KEY (`hardware_activation_id`) REFERENCES `hardware_activations`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 6. VERIFICATION & SECURITY
-- ==========================================

DROP TABLE IF EXISTS `verification_logs`;
CREATE TABLE `verification_logs` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `activation_id`    BIGINT UNSIGNED NULL,
  `license_key`      VARCHAR(64) NULL,
  `api_key`          VARCHAR(64) NULL,
  `ip_address`       VARCHAR(45) NOT NULL,
  `user_agent`       VARCHAR(500) NULL,
  `request_domain`   VARCHAR(255) NULL,
  `request_ip`       VARCHAR(45) NULL,
  `tier1_api`        ENUM('pass','fail','na') DEFAULT 'pass',
  `tier2_license`    ENUM('pass','fail','na') DEFAULT 'pass',
  `tier3_domain`     ENUM('pass','fail','na') DEFAULT 'pass',
  `tier4_ip`         ENUM('pass','fail','na') DEFAULT 'pass',
  `tier5_subscription` ENUM('pass','fail','na') DEFAULT 'pass',
  `overall_result`   ENUM('valid','invalid','expired','suspicious','banned') NULL,
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_verification_logs_api_key` (`api_key`),
  INDEX `idx_verification_logs_license_key` (`license_key`),
  INDEX `idx_verification_logs_activation` (`activation_id`),
  INDEX `idx_verification_logs_created` (`created_at`),
  INDEX `idx_verification_logs_key_lookup` (`api_key`, `license_key`),
  CONSTRAINT `fk_verification_logs_activation` FOREIGN KEY (`activation_id`) REFERENCES `license_activations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `fraud_logs`;
CREATE TABLE `fraud_logs` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `license_id`    BIGINT UNSIGNED NULL,
  `activation_id` BIGINT UNSIGNED NULL,
  `ip`            VARCHAR(45) NULL,
  `domain`        VARCHAR(255) NULL,
  `reason`        VARCHAR(255) NOT NULL,
  `severity`      ENUM('low','medium','high','critical') DEFAULT 'low',
  `action_taken`  VARCHAR(100) DEFAULT 'logged',
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_fraud_logs_license` (`license_id`),
  INDEX `idx_fraud_logs_activation` (`activation_id`),
  INDEX `idx_fraud_logs_severity` (`severity`),
  CONSTRAINT `fk_fraud_logs_license`    FOREIGN KEY (`license_id`)    REFERENCES `licenses`(`id`)             ON DELETE SET NULL,
  CONSTRAINT `fk_fraud_logs_activation` FOREIGN KEY (`activation_id`) REFERENCES `license_activations`(`id`)  ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `user_2fa`;
CREATE TABLE `user_2fa` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`     BIGINT UNSIGNED NOT NULL,
  `secret`      VARCHAR(255) NULL,
  `method`      ENUM('totp','email','sms','backup_codes') NOT NULL DEFAULT 'totp',
  `backup_codes` JSON NULL,
  `is_enabled`  BOOLEAN DEFAULT FALSE,
  `verified_at` TIMESTAMP NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_user_2fa_user` (`user_id`),
  INDEX `idx_user_2fa_enabled` (`is_enabled`),
  CONSTRAINT `fk_user_2fa_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 7. SUPPORT & TICKETING & CHAT
-- ==========================================

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  `subject`    VARCHAR(255) NULL,
  `status`     ENUM('open','replied','closed') DEFAULT 'open',
  `priority`   ENUM('low','medium','high') DEFAULT 'medium',
  `assigned_to` BIGINT UNSIGNED NULL,
  `category`    VARCHAR(100) NULL,
  `closed_at`   TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_tickets_user` (`user_id`),
  INDEX `idx_tickets_assigned` (`assigned_to`),
  INDEX `idx_tickets_status` (`status`),
  INDEX `idx_tickets_user_status` (`user_id`, `status`),
  INDEX `idx_tickets_status_priority` (`status`, `priority`),
  INDEX `idx_tickets_status_created` (`status`, `created_at`),
  CONSTRAINT `fk_tickets_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ticket_messages`;
CREATE TABLE `ticket_messages` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ticket_id`  BIGINT UNSIGNED NOT NULL,
  `sender_id`  BIGINT UNSIGNED NOT NULL,
  `message`    TEXT NULL,
  `attachments` JSON NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_ticket_messages_ticket` (`ticket_id`),
  INDEX `idx_ticket_messages_sender` (`sender_id`),
  FULLTEXT INDEX `ft_ticket_messages_search` (`message`),
  CONSTRAINT `fk_ticket_messages_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ticket_messages_sender` FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `chat_sessions`;
CREATE TABLE `chat_sessions` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`       BIGINT UNSIGNED NOT NULL,
  `status`        ENUM('open','closed') DEFAULT 'open',
  `assigned_to`   BIGINT UNSIGNED NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_chat_sessions_user` (`user_id`),
  INDEX `idx_chat_sessions_assigned` (`assigned_to`),
  INDEX `idx_chat_sessions_user_status` (`user_id`, `status`),
  CONSTRAINT `fk_chat_sessions_user`     FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_chat_sessions_assigned_to` FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `session_id`   BIGINT UNSIGNED NOT NULL,
  `user_id`      BIGINT UNSIGNED NOT NULL,
  `message`      TEXT NOT NULL,
  `is_agent`     BOOLEAN DEFAULT FALSE,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_chat_messages_session` (`session_id`),
  INDEX `idx_chat_messages_user` (`user_id`),
  FULLTEXT INDEX `ft_chat_messages_search` (`message`),
  CONSTRAINT `fk_chat_messages_session` FOREIGN KEY (`session_id`) REFERENCES `chat_sessions`(`id`) ON DELETE CASCADE,

  CONSTRAINT `fk_chat_messages_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)         ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `bot_conversations`;
CREATE TABLE `bot_conversations` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`       BIGINT UNSIGNED NOT NULL,
  `bot_config_id` BIGINT UNSIGNED NULL,
  `message`       TEXT NOT NULL,
  `response`      TEXT NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_bot_conversations_user` (`user_id`),
  INDEX `idx_bot_conversations_bot_config` (`bot_config_id`),
  INDEX `idx_bot_conversations_created` (`created_at`),
  INDEX `idx_bot_conversations_user_created` (`user_id`, `created_at`),
  CONSTRAINT `fk_bot_conversations_user`       FOREIGN KEY (`user_id`)       REFERENCES `users`(`id`)      ON DELETE CASCADE,
  CONSTRAINT `fk_bot_conversations_bot_configs` FOREIGN KEY (`bot_config_id`) REFERENCES `bot_configs`(`id`)  ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 8. APPLICATION UPDATES & CONTENT
-- ==========================================

DROP TABLE IF EXISTS `application_updates`;
CREATE TABLE `application_updates` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `version`    VARCHAR(20) NOT NULL,
  `type`       ENUM('update','version_history') DEFAULT 'update',
  `changelog`  TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_app_updates_version` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `release_notes`;
CREATE TABLE `release_notes` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `version`    VARCHAR(20) NOT NULL,
  `notes`      TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `user_guides`;
CREATE TABLE `user_guides` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `author_id`  BIGINT UNSIGNED NULL,
  `title`      VARCHAR(255) NOT NULL,
  `slug`       VARCHAR(255) NULL,
  `content`    TEXT NOT NULL,
  `status`     ENUM('published','draft') DEFAULT 'draft',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_user_guides_slug` (`slug`),
  FULLTEXT INDEX `ft_user_guides_search` (`title`, `content`),
  INDEX `idx_guides_author` (`author_id`),

  CONSTRAINT `fk_user_guides_author` FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title`      VARCHAR(255) NOT NULL,
  `content`    TEXT NOT NULL,
  `is_active`  BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `knowledge_base_articles`;
CREATE TABLE `knowledge_base_articles` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title`      VARCHAR(255) NOT NULL,
  `slug`       VARCHAR(255) NULL,
  `content`    TEXT NOT NULL,
  `category`   VARCHAR(100) NULL,
  `status`     ENUM('published','draft') DEFAULT 'published',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_kb_articles_slug` (`slug`),
  FULLTEXT INDEX `ft_kb_articles_search` (`title`, `content`),
  INDEX `idx_kb_articles_category` (`category`),
  INDEX `idx_kb_articles_status` (`status`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `faq_items`;
CREATE TABLE `faq_items` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `question`   VARCHAR(255) NOT NULL,
  `answer`     TEXT NOT NULL,
  `slug`       VARCHAR(255) NULL,
  `category`   VARCHAR(100) NULL,
  `position`   INT DEFAULT 0,
  `status`     ENUM('published','draft') DEFAULT 'published',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_faq_items_slug` (`slug`),
  FULLTEXT INDEX `ft_faq_items_search` (`question`, `answer`),
  INDEX `idx_faq_items_category` (`category`),
  INDEX `idx_faq_items_position` (`position`),
  INDEX `idx_faq_items_status` (`status`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 9. CMS
-- ==========================================

DROP TABLE IF EXISTS `cms_categories`;
CREATE TABLE `cms_categories` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(100) NOT NULL,
  `slug`        VARCHAR(100) NULL,
  `description` TEXT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_cms_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_tags`;
CREATE TABLE `cms_tags` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`       VARCHAR(100) NOT NULL,
  `slug`       VARCHAR(100) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_cms_tags_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `type`              ENUM('blog','cms') NOT NULL DEFAULT 'cms',
  `author_id`         BIGINT UNSIGNED NULL,
  `title`             VARCHAR(255) NOT NULL,
  `slug`              VARCHAR(255) NULL,
  `content`           TEXT NULL,
  `excerpt`           TEXT NULL,
  `featured_image`    VARCHAR(500) NULL,
  `category_id`       BIGINT UNSIGNED NULL,
  `status`            ENUM('published','draft','scheduled') DEFAULT 'draft',
  `published_at`      TIMESTAMP NULL,
  `scheduled_for`     TIMESTAMP NULL,
  `meta_title`        VARCHAR(255) NULL,
  `meta_description`  TEXT NULL,
  `meta_keywords`     VARCHAR(500) NULL,
  `canonical_url`     VARCHAR(500) NULL,
  `view_count`        INT UNSIGNED DEFAULT 0,
  `og_image`          VARCHAR(500) NULL,
  `og_title`          VARCHAR(255) NULL,
  `og_description`    TEXT NULL,
  `twitter_card`      ENUM('summary','summary_large_image','app','player') NULL,
  `noindex`           BOOLEAN DEFAULT FALSE,
  `priority`          DECIMAL(2,1) DEFAULT 0.50,
  `changefreq`        ENUM('always','hourly','daily','weekly','monthly','yearly','never') DEFAULT 'weekly',
  `sitemap_include`   BOOLEAN DEFAULT TRUE,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_posts_slug_type` (`slug`(191), `type`),
  INDEX `idx_posts_type_status` (`type`, `status`),
  INDEX `idx_posts_author` (`author_id`),
  INDEX `idx_posts_category` (`category_id`),
  INDEX `idx_posts_published` (`published_at`),
  INDEX `idx_posts_type_author_status` (`type`, `author_id`, `status`),
  INDEX `idx_posts_scheduled` (`scheduled_for`),
  INDEX `idx_posts_sitemap` (`sitemap_include`, `status`),
  INDEX `idx_posts_noindex` (`noindex`),
  FULLTEXT INDEX `ft_posts_search` (`title`, `content`, `excerpt`),

  CONSTRAINT `fk_posts_author`   FOREIGN KEY (`author_id`)   REFERENCES `users`(`id`)          ON DELETE SET NULL,

  CONSTRAINT `fk_posts_category` FOREIGN KEY (`category_id`) REFERENCES `cms_categories`(`id`) ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `post_tags`;
CREATE TABLE `post_tags` (
  `post_id` BIGINT UNSIGNED NOT NULL,
  `tag_id`  BIGINT UNSIGNED NOT NULL,

  PRIMARY KEY (`post_id`, `tag_id`),
  INDEX `idx_post_tags_tag` (`tag_id`),
  CONSTRAINT `fk_post_tags_post` FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_post_tags_tag`  FOREIGN KEY (`tag_id`)  REFERENCES `cms_tags`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `post_comments`;
CREATE TABLE `post_comments` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `post_id`    BIGINT UNSIGNED NOT NULL,
  `user_id`    BIGINT UNSIGNED NULL,
  `parent_id`  BIGINT UNSIGNED NULL,
  `author_name` VARCHAR(255) NULL,
  `author_email` VARCHAR(255) NULL,
  `body`       TEXT NOT NULL,
  `status`     ENUM('pending','approved','spam') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_post_comments_post` (`post_id`),
  INDEX `idx_post_comments_user` (`user_id`),
  INDEX `idx_post_comments_parent` (`parent_id`),
  INDEX `idx_post_comments_status` (`status`),
  CONSTRAINT `fk_post_comments_post`   FOREIGN KEY (`post_id`)   REFERENCES `posts`(`id`)           ON DELETE CASCADE,
  CONSTRAINT `fk_post_comments_user`   FOREIGN KEY (`user_id`)   REFERENCES `users`(`id`)           ON DELETE SET NULL,
  CONSTRAINT `fk_post_comments_parent` FOREIGN KEY (`parent_id`) REFERENCES `post_comments`(`id`)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `post_reactions`;
CREATE TABLE `post_reactions` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `post_id`    BIGINT UNSIGNED NOT NULL,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  `reaction`   ENUM('like','love','laugh','clap','fire') DEFAULT 'like',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_post_reactions_user` (`post_id`, `user_id`, `reaction`),
  INDEX `idx_post_reactions_post` (`post_id`),
  CONSTRAINT `fk_post_reactions_post` FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_post_reactions_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `post_views`;
CREATE TABLE `post_views` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `post_id`    BIGINT UNSIGNED NOT NULL,
  `user_id`    BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `viewed_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_post_views_post` (`post_id`),
  INDEX `idx_post_views_user` (`user_id`),
  INDEX `idx_post_views_date` (`viewed_at`),
  CONSTRAINT `fk_post_views_post` FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_post_views_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `post_media`;
CREATE TABLE `post_media` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `post_id`      BIGINT UNSIGNED NOT NULL,
  `file_name`    VARCHAR(255) NOT NULL,
  `file_path`    VARCHAR(500) NOT NULL,
  `file_type`    VARCHAR(50) NOT NULL,
  `file_size`    INT UNSIGNED NULL,
  `is_featured`  BOOLEAN DEFAULT FALSE,
  `sort_order`   INT DEFAULT 0,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_post_media_post` (`post_id`),
  INDEX `idx_post_media_featured` (`post_id`, `is_featured`),
  CONSTRAINT `fk_post_media_post` FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `post_series`;
CREATE TABLE `post_series` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title`       VARCHAR(255) NOT NULL,
  `slug`        VARCHAR(255) NULL,
  `description` TEXT NULL,
  `cover_image` VARCHAR(500) NULL,
  `author_id`   BIGINT UNSIGNED NULL,
  `status`      ENUM('active','archived') DEFAULT 'active',
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_post_series_slug` (`slug`),
  INDEX `idx_post_series_author` (`author_id`),
  CONSTRAINT `fk_post_series_author` FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `post_series_items`;
CREATE TABLE `post_series_items` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `series_id`  BIGINT UNSIGNED NOT NULL,
  `post_id`    BIGINT UNSIGNED NOT NULL,
  `part_order` INT DEFAULT 0,
  `part_title` VARCHAR(255) NULL,

  UNIQUE INDEX `unique_series_item` (`series_id`, `post_id`),
  INDEX `idx_post_series_items_series` (`series_id`),
  INDEX `idx_post_series_items_post` (`post_id`),
  CONSTRAINT `fk_post_series_items_series` FOREIGN KEY (`series_id`) REFERENCES `post_series`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_post_series_items_post`   FOREIGN KEY (`post_id`)   REFERENCES `posts`(`id`)       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `related_posts`;
CREATE TABLE `related_posts` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `post_id`        BIGINT UNSIGNED NOT NULL,
  `related_post_id` BIGINT UNSIGNED NOT NULL,
  `relation_type`  ENUM('manual','auto_tag','auto_category') DEFAULT 'manual',
  `weight`         INT DEFAULT 0,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_related_pair` (`post_id`, `related_post_id`),
  INDEX `idx_related_posts_post` (`post_id`),
  INDEX `idx_related_posts_related` (`related_post_id`),
  CONSTRAINT `fk_related_posts_post`   FOREIGN KEY (`post_id`)        REFERENCES `posts`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_related_posts_related` FOREIGN KEY (`related_post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `author_profiles`;
CREATE TABLE `author_profiles` (
  `user_id`       BIGINT UNSIGNED PRIMARY KEY,
  `display_name`  VARCHAR(255) NULL,
  `avatar_url`    VARCHAR(500) NULL,
  `bio`           TEXT NULL,
  `website_url`   VARCHAR(500) NULL,
  `twitter_handle` VARCHAR(100) NULL,
  `github_handle` VARCHAR(100) NULL,
  `linkedin_url`  VARCHAR(500) NULL,
  `is_public`     BOOLEAN DEFAULT TRUE,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT `fk_author_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 9b. VIDEO GALLERY & GUIDELINES
-- ==========================================

DROP TABLE IF EXISTS `video_galleries`;
CREATE TABLE `video_galleries` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title`       VARCHAR(255) NOT NULL,
  `slug`        VARCHAR(255) NULL,
  `description` TEXT NULL,
  `thumbnail`   VARCHAR(500) NULL,
  `author_id`   BIGINT UNSIGNED NULL,
  `status`      ENUM('active','archived') DEFAULT 'active',
  `sort_order`  INT DEFAULT 0,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_video_galleries_slug` (`slug`),
  INDEX `idx_video_galleries_author` (`author_id`),
  INDEX `idx_video_galleries_status` (`status`),
  CONSTRAINT `fk_video_galleries_author` FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `gallery_id`    BIGINT UNSIGNED NULL,
  `title`         VARCHAR(255) NOT NULL,
  `slug`          VARCHAR(255) NULL,
  `description`   TEXT NULL,
  `video_url`     VARCHAR(500) NULL,
  `embed_url`     VARCHAR(500) NULL,
  `thumbnail`     VARCHAR(500) NULL,
  `duration`      INT UNSIGNED NULL,
  `file_size`     BIGINT UNSIGNED NULL,
  `file_type`     VARCHAR(50) NULL,
  `author_id`     BIGINT UNSIGNED NULL,
  `status`        ENUM('published','draft') DEFAULT 'draft',
  `featured`      BOOLEAN DEFAULT FALSE,
  `view_count`    INT UNSIGNED DEFAULT 0,
  `sort_order`    INT DEFAULT 0,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_videos_slug` (`slug`),
  INDEX `idx_videos_gallery` (`gallery_id`),
  INDEX `idx_videos_author` (`author_id`),
  INDEX `idx_videos_status` (`status`),
  INDEX `idx_videos_featured` (`featured`),
  INDEX `idx_videos_gallery_sort` (`gallery_id`, `sort_order`),
  CONSTRAINT `fk_videos_gallery` FOREIGN KEY (`gallery_id`) REFERENCES `video_galleries`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_videos_author`  FOREIGN KEY (`author_id`)  REFERENCES `users`(`id`)             ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `video_tags`;
CREATE TABLE `video_tags` (
  `video_id` BIGINT UNSIGNED NOT NULL,
  `tag_id`   BIGINT UNSIGNED NOT NULL,

  PRIMARY KEY (`video_id`, `tag_id`),
  INDEX `idx_video_tags_tag` (`tag_id`),
  CONSTRAINT `fk_video_tags_video` FOREIGN KEY (`video_id`) REFERENCES `videos`(`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_video_tags_tag`   FOREIGN KEY (`tag_id`)   REFERENCES `cms_tags`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `video_guidelines`;
CREATE TABLE `video_guidelines` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `video_id`     BIGINT UNSIGNED NOT NULL,
  `step_order`   INT DEFAULT 0,
  `title`        VARCHAR(255) NOT NULL,
  `description`  TEXT NULL,
  `time_marker`  INT UNSIGNED NULL,
  `image`        VARCHAR(500) NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_video_guidelines_video` (`video_id`),
  INDEX `idx_video_guidelines_order` (`video_id`, `step_order`),
  CONSTRAINT `fk_video_guidelines_video` FOREIGN KEY (`video_id`) REFERENCES `videos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_pages`;
CREATE TABLE `cms_pages` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title`             VARCHAR(255) NULL,
  `slug`              VARCHAR(255) NULL,
  `content`           LONGTEXT NULL,
  `status`            ENUM('published','draft') DEFAULT 'draft',
  `meta_title`        VARCHAR(255) NULL,
  `meta_description`  TEXT NULL,
  `meta_keywords`     VARCHAR(500) NULL,
  `canonical_url`     VARCHAR(500) NULL,
  `og_image`          VARCHAR(500) NULL,
  `og_title`          VARCHAR(255) NULL,
  `og_description`    TEXT NULL,
  `twitter_card`      ENUM('summary','summary_large_image','app','player') NULL,
  `noindex`           BOOLEAN DEFAULT FALSE,
  `priority`          DECIMAL(2,1) DEFAULT 0.50,
  `changefreq`        ENUM('always','hourly','daily','weekly','monthly','yearly','never') DEFAULT 'weekly',
  `sitemap_include`   BOOLEAN DEFAULT TRUE,
  `published_at`      TIMESTAMP NULL,
  `scheduled_for`     TIMESTAMP NULL,
  `author_id`         BIGINT UNSIGNED NULL,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_cms_pages_slug` (`slug`),
  FULLTEXT INDEX `ft_cms_pages_search` (`title`, `content`),
  INDEX `idx_cms_pages_status` (`status`),
  INDEX `idx_cms_pages_sitemap` (`sitemap_include`, `status`),

  INDEX `idx_cms_pages_noindex` (`noindex`),
  INDEX `idx_cms_pages_author` (`author_id`),
  CONSTRAINT `fk_cms_pages_author` FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_menus`;
CREATE TABLE `cms_menus` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`       VARCHAR(100) NOT NULL,
  `slug`       VARCHAR(100) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_cms_menus_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_menu_items`;
CREATE TABLE `cms_menu_items` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `menu_id`    BIGINT UNSIGNED NOT NULL,
  `title`      VARCHAR(100) NOT NULL,
  `url`        VARCHAR(255) NULL,
  `target`     VARCHAR(20) DEFAULT '_self',
  `position`   INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_cms_menu_items_menu` (`menu_id`),
  CONSTRAINT `fk_cms_menu_items_menu` FOREIGN KEY (`menu_id`) REFERENCES `cms_menus`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_widgets`;
CREATE TABLE `cms_widgets` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(100) NOT NULL,
  `slug`        VARCHAR(100) NULL,
  `description` TEXT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_cms_widgets_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_banners`;
CREATE TABLE `cms_banners` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title`      VARCHAR(255) NULL,
  `image`      VARCHAR(255) NULL,
  `link`       VARCHAR(255) NULL,
  `status`     ENUM('published','draft') DEFAULT 'draft',
  `position`   INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_testimonials`;
CREATE TABLE `cms_testimonials` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`       VARCHAR(255) NULL,
  `position`   VARCHAR(255) NULL,
  `company`    VARCHAR(255) NULL,
  `content`    TEXT NULL,
  `status`     ENUM('published','draft') DEFAULT 'draft',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_settings`;
CREATE TABLE `cms_settings` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key`        VARCHAR(100) NOT NULL,
  `value`      TEXT NULL,
  `group`      ENUM('general','seo','analytics','social','custom') DEFAULT 'general',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_cms_settings_key_group` (`key`, `group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_social_links`;
CREATE TABLE `cms_social_links` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `platform`   VARCHAR(50) NOT NULL,
  `url`        VARCHAR(255) NOT NULL,
  `position`   INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_footers`;
CREATE TABLE `cms_footers` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `content`    TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_headers`;
CREATE TABLE `cms_headers` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `content`    TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_sidebars`;
CREATE TABLE `cms_sidebars` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `content`    TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cms_media_galleries`;
CREATE TABLE `cms_media_galleries` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `file_name`  VARCHAR(255) NOT NULL,
  `file_path`  VARCHAR(255) NOT NULL,
  `file_type`  VARCHAR(50) NOT NULL,
  `file_size`  INT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 9c. SEO & ANALYTICS
-- ==========================================

DROP TABLE IF EXISTS `redirects`;
CREATE TABLE `redirects` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `old_path`    VARCHAR(500) NOT NULL,
  `new_path`    VARCHAR(500) NOT NULL,
  `status_code` ENUM('301','302','307') DEFAULT '301',
  `is_active`   BOOLEAN DEFAULT TRUE,
  `hits_count`  INT UNSIGNED DEFAULT 0,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_redirects_old_path` (`old_path`),
  INDEX `idx_redirects_active` (`is_active`),
  INDEX `idx_redirects_status_code` (`status_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `slug_history`;
CREATE TABLE `slug_history` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `content_type` ENUM('post','cms_page','product','knowledge_base','faq_item') NOT NULL,
  `content_id`   BIGINT UNSIGNED NOT NULL,
  `old_slug`     VARCHAR(255) NOT NULL,
  `new_slug`     VARCHAR(255) NOT NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_slug_history_content` (`content_type`, `content_id`),
  INDEX `idx_slug_history_old` (`old_slug`),
  INDEX `idx_slug_history_new` (`new_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `structured_data`;
CREATE TABLE `structured_data` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `content_type` ENUM('post','cms_page','product') NOT NULL,
  `content_id`   BIGINT UNSIGNED NOT NULL,
  `schema_type`  VARCHAR(50) NOT NULL,
  `json_ld`      JSON NOT NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_structured_data_content` (`content_type`, `content_id`),
  INDEX `idx_structured_data_schema_type` (`schema_type`),
  UNIQUE INDEX `unique_structured_data_entry` (`content_type`, `content_id`, `schema_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `seo_analysis`;
CREATE TABLE `seo_analysis` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `content_type`     ENUM('post','cms_page') NOT NULL,
  `content_id`       BIGINT UNSIGNED NOT NULL,
  `score`            DECIMAL(5,2) NULL,
  `issues`           JSON NULL,
  `word_count`       INT UNSIGNED NULL,
  `readability_score` DECIMAL(5,2) NULL,
  `checked_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_seo_analysis_content` (`content_type`, `content_id`),
  INDEX `idx_seo_analysis_score` (`score`),
  INDEX `idx_seo_analysis_checked` (`checked_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `analytics_events`;
CREATE TABLE `analytics_events` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `event_type`    VARCHAR(50) NOT NULL,
  `page_url`      VARCHAR(500) NULL,
  `referrer_url`  VARCHAR(500) NULL,
  `utm_source`    VARCHAR(100) NULL,
  `utm_medium`    VARCHAR(100) NULL,
  `utm_campaign`  VARCHAR(100) NULL,
  `utm_term`      VARCHAR(100) NULL,
  `utm_content`   VARCHAR(100) NULL,
  `user_agent`    TEXT NULL,
  `ip_address`    VARCHAR(45) NULL,
  `session_id`    VARCHAR(100) NULL,
  `user_id`       BIGINT UNSIGNED NULL,
  `post_id`       BIGINT UNSIGNED NULL,
  `page_id`       BIGINT UNSIGNED NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_analytics_events_type` (`event_type`),
  INDEX `idx_analytics_events_date` (`created_at`),
  INDEX `idx_analytics_events_session` (`session_id`),
  INDEX `idx_analytics_events_user` (`user_id`),
  INDEX `idx_analytics_events_post` (`post_id`),
  INDEX `idx_analytics_events_page` (`page_id`),
  INDEX `idx_analytics_events_utm_campaign` (`utm_campaign`),
  CONSTRAINT `fk_analytics_events_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)    ON DELETE SET NULL,
  CONSTRAINT `fk_analytics_events_post` FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`)    ON DELETE SET NULL,
  CONSTRAINT `fk_analytics_events_page` FOREIGN KEY (`page_id`) REFERENCES `cms_pages`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 9d. MEDIA & CONTENT REVISIONS
-- ==========================================

DROP TABLE IF EXISTS `media_library`;
CREATE TABLE `media_library` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `filename`    VARCHAR(255) NOT NULL,
  `filepath`    VARCHAR(500) NOT NULL,
  `mime_type`   VARCHAR(100) NOT NULL,
  `file_size`   BIGINT UNSIGNED NULL,
  `width`       INT UNSIGNED NULL,
  `height`      INT UNSIGNED NULL,
  `alt_text`    VARCHAR(255) NULL,
  `caption`     TEXT NULL,
  `uploaded_by` BIGINT UNSIGNED NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FULLTEXT INDEX `ft_media_library_search` (`filename`, `alt_text`),
  INDEX `idx_media_library_mime` (`mime_type`),

  INDEX `idx_media_library_uploader` (`uploaded_by`),

  CONSTRAINT `fk_media_library_uploader` FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `content_revisions`;
CREATE TABLE `content_revisions` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `content_type` ENUM('post','cms_page','user_guide','knowledge_base') NOT NULL,
  `content_id`   BIGINT UNSIGNED NOT NULL,
  `title`        VARCHAR(255) NULL,
  `content`      LONGTEXT NULL,
  `summary`      TEXT NULL,
  `meta`         JSON NULL,
  `created_by`   BIGINT UNSIGNED NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_content_revisions_content` (`content_type`, `content_id`),
  INDEX `idx_content_revisions_author` (`created_by`),
  INDEX `idx_content_revisions_created` (`created_at`),
  CONSTRAINT `fk_content_revisions_author` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 9e. FORMS & PAGE TAGGING
-- ==========================================

DROP TABLE IF EXISTS `cms_page_tags`;
CREATE TABLE `cms_page_tags` (
  `page_id` BIGINT UNSIGNED NOT NULL,
  `tag_id`  BIGINT UNSIGNED NOT NULL,

  PRIMARY KEY (`page_id`, `tag_id`),
  INDEX `idx_cms_page_tags_tag` (`tag_id`),
  CONSTRAINT `fk_cms_page_tags_page` FOREIGN KEY (`page_id`) REFERENCES `cms_pages`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cms_page_tags_tag`  FOREIGN KEY (`tag_id`)  REFERENCES `cms_tags`(`id`)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `form_submissions`;
CREATE TABLE `form_submissions` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `form_key`   VARCHAR(50) NOT NULL,
  `data`       JSON NOT NULL,
  `user_id`    BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_form_submissions_key` (`form_key`),
  INDEX `idx_form_submissions_user` (`user_id`),
  INDEX `idx_form_submissions_created` (`created_at`),
  CONSTRAINT `fk_form_submissions_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `content_blocks`;
CREATE TABLE `content_blocks` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key`         VARCHAR(100) NOT NULL UNIQUE,
  `title`       VARCHAR(255) NOT NULL,
  `content`     LONGTEXT NULL,
  `type`        ENUM('html','text','json') DEFAULT 'html',
  `locations`   JSON NULL,
  `active`      BOOLEAN DEFAULT TRUE,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_content_blocks_key` (`key`),
  INDEX `idx_content_blocks_active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 10. THEMES
-- ==========================================

DROP TABLE IF EXISTS `themes`;
CREATE TABLE `themes` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(100) NULL,
  `slug`        VARCHAR(100) NULL,
  `description` TEXT NULL,
  `version`     VARCHAR(20) NULL,
  `author`      VARCHAR(100) NULL,
  `status`      ENUM('active','inactive') DEFAULT 'inactive',
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_themes_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `theme_settings`;
CREATE TABLE `theme_settings` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `theme_id`   BIGINT UNSIGNED NOT NULL,
  `key`        VARCHAR(100) NULL,
  `value`      TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_theme_settings_theme` (`theme_id`),
  CONSTRAINT `fk_theme_settings_theme` FOREIGN KEY (`theme_id`) REFERENCES `themes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `theme_assets`;
CREATE TABLE `theme_assets` (
  `id`       BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `theme_id` BIGINT UNSIGNED NOT NULL,
  `type`     ENUM('css','js','image') NOT NULL,
  `path`     VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_theme_assets_theme` (`theme_id`),
  CONSTRAINT `fk_theme_assets_theme` FOREIGN KEY (`theme_id`) REFERENCES `themes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `theme_customizations`;
CREATE TABLE `theme_customizations` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `theme_id`   BIGINT UNSIGNED NOT NULL,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  `custom_css` TEXT NULL,
  `custom_js`  TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_theme_customizations_theme` (`theme_id`),
  INDEX `idx_theme_customizations_user` (`user_id`),
  CONSTRAINT `fk_theme_customizations_theme` FOREIGN KEY (`theme_id`) REFERENCES `themes`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_theme_customizations_user`  FOREIGN KEY (`user_id`)  REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `theme_usage_logs`;
CREATE TABLE `theme_usage_logs` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `theme_id`   BIGINT UNSIGNED NOT NULL,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  `action`     ENUM('activated','deactivated','customized') NOT NULL,
  `ip_address` VARCHAR(45) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_theme_usage_logs_theme` (`theme_id`),
  INDEX `idx_theme_usage_logs_user` (`user_id`),
  CONSTRAINT `fk_theme_usage_logs_theme` FOREIGN KEY (`theme_id`) REFERENCES `themes`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_theme_usage_logs_user`  FOREIGN KEY (`user_id`)  REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `theme_update_logs`;
CREATE TABLE `theme_update_logs` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `theme_id`    BIGINT UNSIGNED NOT NULL,
  `version_from` VARCHAR(20) NULL,
  `version_to`  VARCHAR(20) NULL,
  `changelog`   TEXT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_theme_update_logs_theme` (`theme_id`),
  CONSTRAINT `fk_theme_update_logs_theme` FOREIGN KEY (`theme_id`) REFERENCES `themes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `theme_conflicts`;
CREATE TABLE `theme_conflicts` (
  `id`                 BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `theme_id`           BIGINT UNSIGNED NOT NULL,
  `conflicting_plugin` VARCHAR(255) NULL,
  `description`        TEXT NULL,
  `reported_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_theme_conflicts_theme` (`theme_id`),
  CONSTRAINT `fk_theme_conflicts_theme` FOREIGN KEY (`theme_id`) REFERENCES `themes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `theme_general_settings`;
CREATE TABLE `theme_general_settings` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `theme_id`      BIGINT UNSIGNED NOT NULL,
  `setting_key`   VARCHAR(100) NULL,
  `setting_value` TEXT NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_theme_general_settings_theme` (`theme_id`),
  CONSTRAINT `fk_theme_general_settings_theme` FOREIGN KEY (`theme_id`) REFERENCES `themes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `theme_generations`;
CREATE TABLE `theme_generations` (
  `id`                       BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `theme_id`                 BIGINT UNSIGNED NOT NULL,
  `user_id`                  BIGINT UNSIGNED NOT NULL,
  `theme_name`               VARCHAR(255) NOT NULL,
  `theme_description`        TEXT NULL,
  `theme_version`            VARCHAR(20) NULL,
  `theme_variant`            VARCHAR(50) NULL,
  `theme_color_scheme`       VARCHAR(50) NULL,
  `theme_layout`             VARCHAR(50) NULL,
  `theme_font`               VARCHAR(100) NULL,
  `theme_customization`      JSON NULL,
  `theme_preview_url`        VARCHAR(255) NULL,
  `theme_download_url`       VARCHAR(255) NULL,
  `theme_screenshot_url`     VARCHAR(255) NULL,
  `theme_markdown_description` TEXT NULL,
  `theme_template_variables` JSON NULL,
  `style`                    ENUM('light','dark','auto') DEFAULT 'light',
  `complexity`               ENUM('simple','moderate','complex') DEFAULT 'moderate',
  `source`                   ENUM('user_input','ai_generated','imported') DEFAULT 'user_input',
  `generation_method`        ENUM('manual','automated','hybrid') DEFAULT 'manual',
  `priority`                 ENUM('low','medium','high') DEFAULT 'medium',
  `estimated_completion_time` INT NULL,
  `actual_completion_time`   INT NULL,
  `progress`                 INT DEFAULT 0,
  `quality_score`            DECIMAL(3,2) NULL,
  `status`                   ENUM('pending','in_progress','completed','failed') DEFAULT 'pending',
  `generated_files`          JSON NULL,
  `error_message`            TEXT NULL,
  `created_at`               TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`               TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_theme_generations_theme` (`theme_id`),
  INDEX `idx_theme_generations_user` (`user_id`),
  INDEX `idx_theme_generations_status` (`status`),
  CONSTRAINT `fk_theme_generations_theme` FOREIGN KEY (`theme_id`) REFERENCES `themes`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_theme_generations_user`  FOREIGN KEY (`user_id`)  REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `theme_generation_logs`;
CREATE TABLE `theme_generation_logs` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `generation_id` BIGINT UNSIGNED NOT NULL,
  `message`       TEXT NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_theme_generation_logs_generation` (`generation_id`),
  CONSTRAINT `fk_theme_generation_logs_generation` FOREIGN KEY (`generation_id`) REFERENCES `theme_generations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 11. LLM INTEGRATION
-- ==========================================

DROP TABLE IF EXISTS `llm_providers`;
CREATE TABLE `llm_providers` (
  `id`       BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`     VARCHAR(100) NOT NULL,
  `api_key`  VARCHAR(255) NOT NULL,
  `base_url` VARCHAR(255) NOT NULL,
  `status`   ENUM('active','inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `llm_provider_settings`;
CREATE TABLE `llm_provider_settings` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `provider_id`   BIGINT UNSIGNED NOT NULL,
  `setting_key`   VARCHAR(100) NOT NULL,
  `setting_value` TEXT NOT NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_llm_provider_settings_provider` (`provider_id`),
  CONSTRAINT `fk_llm_provider_settings_provider` FOREIGN KEY (`provider_id`) REFERENCES `llm_providers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `llm_provider_activity`;
CREATE TABLE `llm_provider_activity` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `provider_id`   BIGINT UNSIGNED NOT NULL,
  `user_id`       BIGINT UNSIGNED NOT NULL,
  `activity_type` ENUM('prompt','response','error') NOT NULL,
  `details`       JSON NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_llm_provider_activity_provider` (`provider_id`),
  INDEX `idx_llm_provider_activity_user` (`user_id`),
  INDEX `idx_llm_provider_activity_provider_activity` (`provider_id`, `activity_type`),
  CONSTRAINT `fk_llm_provider_activity_provider` FOREIGN KEY (`provider_id`) REFERENCES `llm_providers`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_llm_provider_activity_user`     FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`)        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `llm_provider_usage`;
CREATE TABLE `llm_provider_usage` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `provider_id` BIGINT UNSIGNED NOT NULL,
  `user_id`     BIGINT UNSIGNED NOT NULL,
  `tokens_used` INT NOT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_llm_provider_usage_provider` (`provider_id`),
  INDEX `idx_llm_provider_usage_user` (`user_id`),
  INDEX `idx_llm_provider_usage_provider_user` (`provider_id`, `user_id`),
  CONSTRAINT `fk_llm_provider_usage_provider` FOREIGN KEY (`provider_id`) REFERENCES `llm_providers`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_llm_provider_usage_user`     FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`)        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `llm_provider_logs`;
CREATE TABLE `llm_provider_logs` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `provider_id` BIGINT UNSIGNED NOT NULL,
  `user_id`     BIGINT UNSIGNED NOT NULL,
  `prompt`      TEXT NOT NULL,
  `response`    TEXT NOT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_llm_provider_logs_provider` (`provider_id`),
  INDEX `idx_llm_provider_logs_user` (`user_id`),
  INDEX `idx_llm_provider_logs_created` (`created_at`),
  CONSTRAINT `fk_llm_provider_logs_provider` FOREIGN KEY (`provider_id`) REFERENCES `llm_providers`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_llm_provider_logs_user`     FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`)        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 12. BOT CONFIGURATION (Multi-Platform)
-- ==========================================

DROP TABLE IF EXISTS `bot_configs`;
CREATE TABLE `bot_configs` (
  `id`                     BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`                   VARCHAR(100) NOT NULL,
  `platform`               ENUM('telegram','discord','slack','whatsapp','custom') NOT NULL,
  `platform_token`         VARCHAR(512) NOT NULL,
  `platform_username`      VARCHAR(100) NULL,
  `webhook_url`            VARCHAR(500) NULL,
  `llm_provider_id`        BIGINT UNSIGNED NULL,
  `llm_system_prompt`      TEXT NULL,
  `welcome_message`        TEXT NULL,
  `status`                 ENUM('active','inactive') DEFAULT 'inactive',
  `allowed_user_ids`       JSON NULL,
  `rate_limit_per_minute`  INT DEFAULT 30,
  `max_conversation_length` INT DEFAULT 50,
  `settings`               JSON NULL,
  `created_at`             TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`             TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_bot_configs_llm_provider` (`llm_provider_id`),
  INDEX `idx_bot_configs_platform` (`platform`),
  INDEX `idx_bot_configs_status` (`status`),
  CONSTRAINT `fk_bot_configs_llm_provider` FOREIGN KEY (`llm_provider_id`) REFERENCES `llm_providers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 13. SECURITY & COMPLIANCE
-- ==========================================

DROP TABLE IF EXISTS `security_events`;
CREATE TABLE `security_events` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`     BIGINT UNSIGNED NULL,
  `event_type`  VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  `ip_address`  VARCHAR(45) NULL,
  `user_agent`  VARCHAR(500) NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_security_events_user` (`user_id`),
  INDEX `idx_security_events_type` (`event_type`),
  INDEX `idx_security_events_created` (`created_at`),
  INDEX `idx_security_events_user_type` (`user_id`, `event_type`),
  CONSTRAINT `fk_security_events_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `vulnerability_scans`;
CREATE TABLE `vulnerability_scans` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `scan_type`  ENUM('full','quick','custom') NOT NULL,
  `target`     VARCHAR(255) NOT NULL,
  `status`     ENUM('pending','in_progress','completed','failed') DEFAULT 'pending',
  `results`    JSON NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `penetration_tests`;
CREATE TABLE `penetration_tests` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `test_type`  ENUM('external','internal','web_app','mobile_app') NOT NULL,
  `target`     VARCHAR(255) NOT NULL,
  `status`     ENUM('pending','in_progress','completed','failed') DEFAULT 'pending',
  `findings`   JSON NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `compliance_reports`;
CREATE TABLE `compliance_reports` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `report_type` ENUM('GDPR','HIPAA','PCI-DSS') NOT NULL,
  `status`      ENUM('pending','in_progress','completed','failed') DEFAULT 'pending',
  `findings`    JSON NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `security_incidents`;
CREATE TABLE `security_incidents` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `incident_type` ENUM('data_breach','unauthorized_access','malware_infection') NOT NULL,
  `description`   TEXT NOT NULL,
  `status`        ENUM('open','investigating','resolved') DEFAULT 'open',
  `reported_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `audit_trails`;
CREATE TABLE `audit_trails` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`       BIGINT UNSIGNED NULL,
  `action`         VARCHAR(255) NULL,
  `entity`         VARCHAR(255) NULL,
  `entity_id`      BIGINT UNSIGNED NULL,
  `activity_type`  VARCHAR(100) NULL,
  `description`    TEXT NULL,
  `details`        JSON NULL,
  `old_value`      JSON NULL,
  `new_value`      JSON NULL,
  `ip_address`     VARCHAR(45) NULL,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_audit_trails_user` (`user_id`),
  INDEX `idx_audit_trails_entity` (`entity`, `entity_id`),
  INDEX `idx_audit_trails_type` (`activity_type`),
  INDEX `idx_audit_trails_created` (`created_at`),
  CONSTRAINT `fk_audit_trails_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 14. LOGGING & MONITORING
-- ==========================================





DROP TABLE IF EXISTS `api_request_logs`;
CREATE TABLE `api_request_logs` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `api_client_id` BIGINT UNSIGNED NULL,
  `order_id`      BIGINT UNSIGNED NULL,
  `endpoint`      VARCHAR(255) NULL,
  `method`        VARCHAR(10) NULL,
  `request_data`  JSON NULL,
  `response_data` JSON NULL,
  `status_code`   INT NULL,
  `ip_address`    VARCHAR(45) NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_api_request_logs_client` (`api_client_id`),
  INDEX `idx_api_request_logs_order` (`order_id`),
  INDEX `idx_api_request_logs_endpoint` (`endpoint`),
  INDEX `idx_api_request_logs_created` (`created_at`),
  CONSTRAINT `fk_api_request_logs_api_client` FOREIGN KEY (`api_client_id`) REFERENCES `api_clients`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_api_request_logs_order`   FOREIGN KEY (`order_id`)      REFERENCES `orders`(`id`)      ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  `type`       ENUM('system','subscription','security','product') NOT NULL,
  `title`      VARCHAR(255) NOT NULL,
  `message`    TEXT NOT NULL,
  `data`       JSON NULL,
  `is_read`    BOOLEAN DEFAULT FALSE,
  `read_at`    TIMESTAMP NULL,
  `channel`    ENUM('in_app','email','telegram','sms') DEFAULT 'in_app',
  `action_url` VARCHAR(500) NULL,
  `action_text` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_notifications_user` (`user_id`),
  INDEX `idx_notifications_type` (`type`),
  INDEX `idx_notifications_read` (`is_read`),
  INDEX `idx_notifications_user_read` (`user_id`, `is_read`),
  INDEX `idx_notifications_user_created` (`user_id`, `created_at`),
  CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 15. SYSTEM SETTINGS & CONFIGURATION
-- ==========================================

DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key`        VARCHAR(100) NOT NULL,
  `value`      TEXT NULL,
  `group`      VARCHAR(50) DEFAULT 'general',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_system_settings_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `system_logs`;
CREATE TABLE `system_logs` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `level`      ENUM('debug','info','warning','error','critical') DEFAULT 'info',
  `message`    TEXT NULL,
  `context`    JSON NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FULLTEXT INDEX `ft_system_logs_search` (`message`),
  INDEX `idx_system_logs_level` (`level`),
  INDEX `idx_system_logs_created` (`created_at`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `email_settings`;
CREATE TABLE `email_settings` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `smtp_host`    VARCHAR(255) NULL,
  `smtp_port`    INT NULL,
  `smtp_username` VARCHAR(255) NULL,
  `smtp_password` VARCHAR(255) NULL,
  `from_email`   VARCHAR(255) NULL,
  `from_name`    VARCHAR(255) NULL,
  `encryption`   ENUM('ssl','tls','none') DEFAULT 'none',
  `is_default`   BOOLEAN DEFAULT FALSE,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_email_default` (`is_default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE `email_templates` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`       VARCHAR(100) NOT NULL,
  `subject`    VARCHAR(255) NULL,
  `body`       TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_email_templates_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `payment_gateways`;
CREATE TABLE `payment_gateways` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  `is_active`   BOOLEAN DEFAULT TRUE,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_payment_gateways_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `payment_gateway_settings`;
CREATE TABLE `payment_gateway_settings` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `gateway_id` BIGINT UNSIGNED NOT NULL,
  `key`        VARCHAR(100) NULL,
  `value`      TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_payment_gateway_settings_gateway` (`gateway_id`),
  CONSTRAINT `fk_payment_gateway_settings_gateway` FOREIGN KEY (`gateway_id`) REFERENCES `payment_gateways`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `webhooks`;
CREATE TABLE `webhooks` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(255) NOT NULL,
  `url`         VARCHAR(500) NOT NULL,
  `events`      JSON NOT NULL,
  `secret`      VARCHAR(255) NULL,
  `is_active`   BOOLEAN DEFAULT TRUE,
  `last_triggered_at` TIMESTAMP NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_webhooks_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `feature_flags`;
CREATE TABLE `feature_flags` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(100) NOT NULL UNIQUE,
  `key`         VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `enabled`     BOOLEAN DEFAULT FALSE,
  `conditions`  JSON NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_feature_flags_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================



-- ==========================================
-- 16. COMMERCE & PLATFORM ENHANCEMENTS
-- ==========================================

DROP TABLE IF EXISTS `return_requests`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `wishlist_items`;
DROP TABLE IF EXISTS `user_payment_methods`;
DROP TABLE IF EXISTS `user_addresses`;
DROP TABLE IF EXISTS `personal_access_tokens`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `social_accounts`;
DROP TABLE IF EXISTS `user_devices`;

CREATE TABLE `user_devices` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`         BIGINT UNSIGNED NOT NULL,
  `platform`        VARCHAR(20) NOT NULL,
  `device_token`    VARCHAR(500) NOT NULL,
  `device_name`     VARCHAR(255) NULL,
  `fingerprint`     VARCHAR(255) NULL,
  `ip_address`      VARCHAR(45) NULL,
  `user_agent`      TEXT NULL,
  `is_active`       BOOLEAN DEFAULT TRUE,
  `last_active_at`  TIMESTAMP NULL,
  `last_notified_at` TIMESTAMP NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_user_devices_token` (`device_token`),
  UNIQUE INDEX `unique_user_devices_fingerprint` (`user_id`, `fingerprint`),
  INDEX `idx_user_devices_user`    (`user_id`),
  INDEX `idx_user_devices_active`  (`is_active`),
  CONSTRAINT `fk_user_devices_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `wishlist_items` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `wishlist_id` BIGINT UNSIGNED NOT NULL,
  `product_id`  BIGINT UNSIGNED NOT NULL,
  `notes`       TEXT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_wishlist_items` (`wishlist_id`, `product_id`),
  INDEX `idx_wishlist_items_product` (`product_id`),
  CONSTRAINT `fk_wishlist_items_wishlist` FOREIGN KEY (`wishlist_id`) REFERENCES `wishlists`(`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_wishlist_items_product`  FOREIGN KEY (`product_id`)  REFERENCES `products`(`id`)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 16d. PRODUCT REVIEWS & RATINGS
-- ==========================================

CREATE TABLE `reviews` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `reviewable_type` VARCHAR(50) NOT NULL,
  `reviewable_id`   BIGINT UNSIGNED NOT NULL,
  `user_id`         BIGINT UNSIGNED NOT NULL,
  `order_id`        BIGINT UNSIGNED NULL,
  `rating`          TINYINT UNSIGNED NOT NULL,
  `title`           VARCHAR(255) NULL,
  `body`            TEXT NULL,
  `is_approved`     BOOLEAN DEFAULT FALSE,
  `is_verified_purchase` BOOLEAN DEFAULT FALSE,
  `helpful_count`   INT UNSIGNED DEFAULT 0,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_reviews_reviewable` (`reviewable_type`, `reviewable_id`),
  INDEX `idx_reviews_user` (`user_id`),
  INDEX `idx_reviews_rating` (`rating`),
  INDEX `idx_reviews_approved` (`is_approved`),
  INDEX `idx_reviews_type_rating` (`reviewable_type`, `rating`),
  INDEX `idx_reviews_order` (`order_id`),
  CONSTRAINT `fk_reviews_user`  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reviews_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 16e. REFUNDS & RETURNS
-- ==========================================

CREATE TABLE `return_requests` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_id`        BIGINT UNSIGNED NOT NULL,
  `order_item_id`   BIGINT UNSIGNED NULL,
  `user_id`         BIGINT UNSIGNED NOT NULL,
  `reason`          ENUM('defective','wrong_item','not_as_described','changed_mind','other') NOT NULL,
  `description`     TEXT NULL,
  `status`          ENUM('pending','approved','received','rejected','refunded') DEFAULT 'pending',
  `resolution`      ENUM('refund','replacement','store_credit') DEFAULT 'refund',
  `admin_notes`     TEXT NULL,
  `processed_by`    BIGINT UNSIGNED NULL,
  `processed_at`    TIMESTAMP NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_return_requests_order`   (`order_id`),
  INDEX `idx_return_requests_user`    (`user_id`),
  INDEX `idx_return_requests_status`  (`status`),
  INDEX `idx_return_requests_processor` (`processed_by`),
  INDEX `idx_return_requests_order_item` (`order_item_id`),
  CONSTRAINT `fk_return_requests_order`      FOREIGN KEY (`order_id`)      REFERENCES `orders`(`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_return_requests_user`       FOREIGN KEY (`user_id`)       REFERENCES `users`(`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_return_requests_order_item` FOREIGN KEY (`order_item_id`) REFERENCES `order_items`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_return_requests_processor`  FOREIGN KEY (`processed_by`)  REFERENCES `users`(`id`)  ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 16f. USER ADDRESS BOOK
-- ==========================================

CREATE TABLE `user_addresses` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`      BIGINT UNSIGNED NOT NULL,
  `label`        VARCHAR(50) NULL,
  `first_name`   VARCHAR(100) NULL,
  `last_name`    VARCHAR(100) NULL,
  `full_name`    VARCHAR(255) NULL,
  `phone`        VARCHAR(50) NULL,
  `address_line1` VARCHAR(255) NOT NULL,
  `address_line2` VARCHAR(255) NULL,
  `city`         VARCHAR(100) NOT NULL,
  `state`        VARCHAR(100) NULL,
  `postal_code`  VARCHAR(20) NULL,
  `country`      VARCHAR(100) NOT NULL,
  `is_default_billing`  BOOLEAN DEFAULT FALSE,
  `is_default_shipping` BOOLEAN DEFAULT FALSE,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_user_addresses_user` (`user_id`),
  INDEX `idx_user_addresses_default_billing`  (`user_id`, `is_default_billing`),
  INDEX `idx_user_addresses_default_shipping` (`user_id`, `is_default_shipping`),
  CONSTRAINT `fk_user_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 16g. SAVED PAYMENT METHODS
-- ==========================================

CREATE TABLE `user_payment_methods` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`         BIGINT UNSIGNED NOT NULL,
  `gateway_id`      BIGINT UNSIGNED NULL,
  `method_type`     ENUM('card','paypal','bank','crypto') NOT NULL,
  `gateway_token`   VARCHAR(500) NULL,
  `display_name`    VARCHAR(100) NULL,
  `last_four`       VARCHAR(4) NULL,
  `expiry_month`    VARCHAR(2) NULL,
  `expiry_year`     VARCHAR(4) NULL,
  `card_brand`      VARCHAR(50) NULL,
  `is_default`      BOOLEAN DEFAULT FALSE,
  `billing_address_id` BIGINT UNSIGNED NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_user_payment_methods_user`   (`user_id`),
  INDEX `idx_user_payment_methods_default` (`user_id`, `is_default`),
  CONSTRAINT `fk_user_payment_methods_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`(`id`)               ON DELETE CASCADE,
  CONSTRAINT `fk_user_payment_methods_gateway` FOREIGN KEY (`gateway_id`) REFERENCES `payment_gateways`(`id`)    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 16h. PLATFORM INFRASTRUCTURE
-- ==========================================

-- Personal access tokens for API authentication
CREATE TABLE `personal_access_tokens` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`       BIGINT UNSIGNED NOT NULL,
  `name`          VARCHAR(255) NOT NULL,
  `token`         VARCHAR(64) NOT NULL,
  `abilities`     JSON NULL,
  `last_used_at`  TIMESTAMP NULL,
  `expires_at`    TIMESTAMP NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_personal_access_tokens_token` (`token`),
  INDEX `idx_personal_access_tokens_user` (`user_id`),
  CONSTRAINT `fk_personal_access_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Server-side session store
CREATE TABLE `sessions` (
  `id`            VARCHAR(128) NOT NULL PRIMARY KEY,
  `user_id`       BIGINT UNSIGNED NULL,
  `ip_address`    VARCHAR(45) NULL,
  `user_agent`    TEXT NULL,
  `payload`       TEXT NOT NULL,
  `last_activity` INT UNSIGNED NOT NULL,

  INDEX `idx_sessions_user`       (`user_id`),
  INDEX `idx_sessions_activity`   (`last_activity`),
  CONSTRAINT `fk_sessions_user`   FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- OAuth / social login accounts
CREATE TABLE `social_accounts` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`       BIGINT UNSIGNED NOT NULL,
  `provider`      VARCHAR(50) NOT NULL,
  `provider_id`   VARCHAR(255) NOT NULL,
  `provider_email` VARCHAR(255) NULL,
  `avatar_url`    VARCHAR(500) NULL,
  `access_token`  TEXT NULL,
  `refresh_token` TEXT NULL,
  `token_expires_at` TIMESTAMP NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_social_accounts_provider` (`provider`, `provider_id`),
  INDEX `idx_social_accounts_user` (`user_id`),
  CONSTRAINT `fk_social_accounts_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




-- 17. SMS & COMMUNICATIONS
-- ==========================================

DROP TABLE IF EXISTS `sms_logs`;
DROP TABLE IF EXISTS `sms_campaign_recipients`;
DROP TABLE IF EXISTS `sms_campaigns`;
DROP TABLE IF EXISTS `sms_automations`;
DROP TABLE IF EXISTS `sms_templates`;
DROP TABLE IF EXISTS `sms_providers`;

-- SMS provider gateway configuration (supports multiple backends)
CREATE TABLE `sms_providers` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`         VARCHAR(100) NOT NULL,
  `provider`     ENUM('twilio','aws_sns','vonage','custom') NOT NULL,
  `api_key`      VARCHAR(500) NULL,
  `api_secret`   VARCHAR(500) NULL,
  `from_number`  VARCHAR(20) NULL,
  `api_endpoint` VARCHAR(500) NULL,
  `config`       JSON NULL,
  `is_active`    BOOLEAN DEFAULT TRUE,
  `is_default`   BOOLEAN DEFAULT FALSE,
  `priority`     INT DEFAULT 0,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_sms_providers_name` (`name`),
  INDEX `idx_sms_providers_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reusable SMS message templates with placeholder support
CREATE TABLE `sms_templates` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`       VARCHAR(100) NOT NULL,
  `category`   VARCHAR(50) NULL,
  `body`       TEXT NOT NULL,
  `variables`  JSON NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_sms_templates_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SMS broadcast / campaign — manually composed or template-based
CREATE TABLE `sms_campaigns` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`             VARCHAR(200) NOT NULL,
  `message_body`     TEXT NOT NULL,
  `sms_template_id`  BIGINT UNSIGNED NULL,
  `provider_id`      BIGINT UNSIGNED NULL,
  `target_type`      ENUM('all','selected','role') NOT NULL,
  `target_roles`     JSON NULL,
  `target_user_ids`  JSON NULL,
  `filter_criteria`  JSON NULL,
  `scheduled_at`     TIMESTAMP NULL,
  `sent_at`          TIMESTAMP NULL,
  `completed_at`     TIMESTAMP NULL,
  `status`           ENUM('draft','scheduled','sending','sent','partial','failed','cancelled') DEFAULT 'draft',
  `total_recipients` INT DEFAULT 0,
  `success_count`    INT DEFAULT 0,
  `fail_count`       INT DEFAULT 0,
  `created_by`       BIGINT UNSIGNED NOT NULL,
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_sms_campaigns_status`    (`status`),
  INDEX `idx_sms_campaigns_scheduled` (`scheduled_at`),
  INDEX `idx_sms_campaigns_creator`   (`created_by`),
  CONSTRAINT `fk_sms_campaigns_template` FOREIGN KEY (`sms_template_id`) REFERENCES `sms_templates`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_sms_campaigns_provider` FOREIGN KEY (`provider_id`)     REFERENCES `sms_providers`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_sms_campaigns_creator`  FOREIGN KEY (`created_by`)       REFERENCES `users`(`id`)        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Per-recipient delivery tracking for each campaign
CREATE TABLE `sms_campaign_recipients` (
  `id`                 BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `campaign_id`        BIGINT UNSIGNED NOT NULL,
  `user_id`            BIGINT UNSIGNED NULL,
  `phone`              VARCHAR(50) NULL,
  `status`             ENUM('pending','sent','delivered','failed','bounced') NOT NULL DEFAULT 'pending',
  `error_message`      TEXT NULL,
  `provider_message_id` VARCHAR(255) NULL,
  `provider_id`        BIGINT UNSIGNED NULL,
  `sent_at`            TIMESTAMP NULL,
  `delivered_at`       TIMESTAMP NULL,
  `created_at`         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_sms_recipients_campaign`        (`campaign_id`),
  INDEX `idx_sms_recipients_user`            (`user_id`),
  INDEX `idx_sms_recipients_status`          (`status`),
  INDEX `idx_sms_recipients_campaign_status` (`campaign_id`, `status`),
  CONSTRAINT `fk_sms_recipients_campaign` FOREIGN KEY (`campaign_id`) REFERENCES `sms_campaigns`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_sms_recipients_user`     FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`)        ON DELETE SET NULL,
  CONSTRAINT `fk_sms_recipients_provider` FOREIGN KEY (`provider_id`) REFERENCES `sms_providers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Automation rules — event-triggered or cron-scheduled campaigns
CREATE TABLE `sms_automations` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`             VARCHAR(200) NOT NULL,
  `trigger_type`     ENUM('event','schedule') NOT NULL,
  `event_name`       VARCHAR(100) NULL,
  `event_conditions` JSON NULL,
  `cron_expression`  VARCHAR(100) NULL,
  `timezone`         VARCHAR(50) NULL DEFAULT 'UTC',
  `target_type`      ENUM('all','selected','role','event_context') NOT NULL,
  `target_roles`     JSON NULL,
  `filter_criteria`  JSON NULL,
  `message_body`     TEXT NOT NULL,
  `sms_template_id`  BIGINT UNSIGNED NULL,
  `provider_id`      BIGINT UNSIGNED NULL,
  `is_active`        BOOLEAN DEFAULT TRUE,
  `last_triggered_at` TIMESTAMP NULL,
  `total_sent`       INT DEFAULT 0,
  `created_by`       BIGINT UNSIGNED NOT NULL,
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_sms_automations_active`  (`is_active`),
  INDEX `idx_sms_automations_event`   (`event_name`),
  INDEX `idx_sms_automations_creator` (`created_by`),
  CONSTRAINT `fk_sms_automations_template` FOREIGN KEY (`sms_template_id`) REFERENCES `sms_templates`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_sms_automations_provider` FOREIGN KEY (`provider_id`)     REFERENCES `sms_providers`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_sms_automations_creator`  FOREIGN KEY (`created_by`)       REFERENCES `users`(`id`)        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Raw provider API request/response log for debugging
CREATE TABLE `sms_logs` (
  `id`                 BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `provider_id`        BIGINT UNSIGNED NULL,
  `campaign_id`        BIGINT UNSIGNED NULL,
  `recipient_id`       BIGINT UNSIGNED NULL,
  `direction`          ENUM('outgoing','callback') NOT NULL,
  `request_payload`    JSON NULL,
  `response_payload`   JSON NULL,
  `http_status`        INT NULL,
  `provider_message_id` VARCHAR(255) NULL,
  `error_message`      TEXT NULL,
  `created_at`         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_sms_logs_provider`       (`provider_id`),
  INDEX `idx_sms_logs_campaign`       (`campaign_id`),
  INDEX `idx_sms_logs_provider_msg`   (`provider_message_id`),
  INDEX `idx_sms_logs_created`        (`created_at`),
  CONSTRAINT `fk_sms_logs_provider`   FOREIGN KEY (`provider_id`)   REFERENCES `sms_providers`(`id`)        ON DELETE SET NULL,
  CONSTRAINT `fk_sms_logs_campaign`   FOREIGN KEY (`campaign_id`)   REFERENCES `sms_campaigns`(`id`)        ON DELETE SET NULL,
  CONSTRAINT `fk_sms_logs_recipient`  FOREIGN KEY (`recipient_id`)  REFERENCES `sms_campaign_recipients`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 18. FINANCIAL / ACCOUNTING (GL)
-- ==========================================

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `code`          VARCHAR(3) NOT NULL,
  `name`          VARCHAR(100) NOT NULL,
  `symbol`        VARCHAR(10) NULL,
  `decimal_places` TINYINT DEFAULT 2,
  `is_base`       BOOLEAN DEFAULT FALSE,
  `is_active`     BOOLEAN DEFAULT TRUE,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_currencies_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `exchange_rates`;
CREATE TABLE `exchange_rates` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `from_currency_id`  BIGINT UNSIGNED NOT NULL,
  `to_currency_id`    BIGINT UNSIGNED NOT NULL,
  `rate`              DECIMAL(15,6) NOT NULL,
  `date`              DATE NOT NULL,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_exchange_rates_from` (`from_currency_id`),
  INDEX `idx_exchange_rates_to` (`to_currency_id`),
  INDEX `idx_exchange_rates_date` (`date`),
  UNIQUE INDEX `unique_exchange_rates_pair_date` (`from_currency_id`, `to_currency_id`, `date`),
  CONSTRAINT `fk_exchange_rates_from` FOREIGN KEY (`from_currency_id`) REFERENCES `currencies`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_exchange_rates_to`   FOREIGN KEY (`to_currency_id`)   REFERENCES `currencies`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `fiscal_years`;
CREATE TABLE `fiscal_years` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(100) NOT NULL,
  `start_date`  DATE NOT NULL,
  `end_date`    DATE NOT NULL,
  `is_closed`   BOOLEAN DEFAULT FALSE,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_fiscal_years_name` (`name`),
  INDEX `idx_fiscal_years_dates` (`start_date`, `end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `account_periods`;
CREATE TABLE `account_periods` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `fiscal_year_id` BIGINT UNSIGNED NOT NULL,
  `type`          ENUM('month','quarter','year') NOT NULL,
  `start_date`    DATE NOT NULL,
  `end_date`      DATE NOT NULL,
  `is_closed`     BOOLEAN DEFAULT FALSE,
  `closed_at`     TIMESTAMP NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_account_periods_fiscal` (`fiscal_year_id`),
  INDEX `idx_account_periods_dates` (`start_date`, `end_date`),
  INDEX `idx_account_periods_closed` (`is_closed`),
  UNIQUE INDEX `unique_account_periods_fiscal_type_dates` (`fiscal_year_id`, `type`, `start_date`),
  CONSTRAINT `fk_account_periods_fiscal` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `chart_of_accounts`;
CREATE TABLE `chart_of_accounts` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `parent_id`     BIGINT UNSIGNED NULL,
  `account_code`  VARCHAR(20) NOT NULL,
  `account_name`  VARCHAR(255) NOT NULL,
  `type`          ENUM('asset','liability','equity','revenue','expense') NOT NULL,
  `subtype`       VARCHAR(50) NULL,
  `is_active`     BOOLEAN DEFAULT TRUE,
  `is_control`    BOOLEAN DEFAULT FALSE,
  `description`   TEXT NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_chart_of_accounts_code` (`account_code`),
  INDEX `idx_chart_of_accounts_parent` (`parent_id`),
  INDEX `idx_chart_of_accounts_type` (`type`),
  INDEX `idx_chart_of_accounts_active` (`is_active`),
  CONSTRAINT `fk_chart_of_accounts_parent` FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cost_centers`;
CREATE TABLE `cost_centers` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `code`        VARCHAR(20) NOT NULL,
  `name`        VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `is_active`   BOOLEAN DEFAULT TRUE,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_cost_centers_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `profit_centers`;
CREATE TABLE `profit_centers` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `code`        VARCHAR(20) NOT NULL,
  `name`        VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `is_active`   BOOLEAN DEFAULT TRUE,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_profit_centers_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `journal_entry_types`;
CREATE TABLE `journal_entry_types` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(100) NOT NULL,
  `code`        VARCHAR(20) NOT NULL,
  `description` TEXT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_journal_entry_types_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `journal_entries`;
CREATE TABLE `journal_entries` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `entry_number`    VARCHAR(50) NOT NULL,
  `entry_type_id`   BIGINT UNSIGNED NOT NULL,
  `fiscal_year_id`  BIGINT UNSIGNED NOT NULL,
  `account_period_id` BIGINT UNSIGNED NULL,
  `entry_date`      DATE NOT NULL,
  `description`     TEXT NULL,
  `reference_type`  VARCHAR(50) NULL,
  `reference_id`    BIGINT UNSIGNED NULL,
  `created_by`      BIGINT UNSIGNED NULL,
  `is_posted`       BOOLEAN DEFAULT FALSE,
  `posted_at`       TIMESTAMP NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_journal_entries_number` (`entry_number`),
  INDEX `idx_journal_entries_type` (`entry_type_id`),
  INDEX `idx_journal_entries_fiscal` (`fiscal_year_id`),
  INDEX `idx_journal_entries_period` (`account_period_id`),
  INDEX `idx_journal_entries_date` (`entry_date`),
  INDEX `idx_journal_entries_reference` (`reference_type`, `reference_id`),
  INDEX `idx_journal_entries_creator` (`created_by`),
  INDEX `idx_journal_entries_posted` (`is_posted`),
  CONSTRAINT `fk_journal_entries_type`   FOREIGN KEY (`entry_type_id`)    REFERENCES `journal_entry_types`(`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_journal_entries_fiscal` FOREIGN KEY (`fiscal_year_id`)   REFERENCES `fiscal_years`(`id`)         ON DELETE RESTRICT,
  CONSTRAINT `fk_journal_entries_period` FOREIGN KEY (`account_period_id`) REFERENCES `account_periods`(`id`)      ON DELETE SET NULL,
  CONSTRAINT `fk_journal_entries_creator` FOREIGN KEY (`created_by`)      REFERENCES `users`(`id`)                ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `journal_entry_lines`;
CREATE TABLE `journal_entry_lines` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `journal_entry_id` BIGINT UNSIGNED NOT NULL,
  `account_id`      BIGINT UNSIGNED NOT NULL,
  `cost_center_id`  BIGINT UNSIGNED NULL,
  `profit_center_id` BIGINT UNSIGNED NULL,
  `debit`           DECIMAL(15,2) DEFAULT 0.00,
  `credit`          DECIMAL(15,2) DEFAULT 0.00,
  `description`     TEXT NULL,
  `line_order`      INT DEFAULT 0,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_journal_entry_lines_entry` (`journal_entry_id`),
  INDEX `idx_journal_entry_lines_account` (`account_id`),
  INDEX `idx_journal_entry_lines_cost` (`cost_center_id`),
  INDEX `idx_journal_entry_lines_profit` (`profit_center_id`),
  CONSTRAINT `fk_journal_entry_lines_entry`   FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries`(`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_journal_entry_lines_account`  FOREIGN KEY (`account_id`)       REFERENCES `chart_of_accounts`(`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_journal_entry_lines_cost`     FOREIGN KEY (`cost_center_id`)   REFERENCES `cost_centers`(`id`)     ON DELETE SET NULL,
  CONSTRAINT `fk_journal_entry_lines_profit`   FOREIGN KEY (`profit_center_id`) REFERENCES `profit_centers`(`id`)   ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `account_balances`;
CREATE TABLE `account_balances` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `account_id`      BIGINT UNSIGNED NOT NULL,
  `fiscal_year_id`  BIGINT UNSIGNED NOT NULL,
  `account_period_id` BIGINT UNSIGNED NULL,
  `period_type`     ENUM('month','quarter','year') NOT NULL,
  `opening_balance` DECIMAL(15,2) DEFAULT 0.00,
  `period_debit`    DECIMAL(15,2) DEFAULT 0.00,
  `period_credit`   DECIMAL(15,2) DEFAULT 0.00,
  `closing_balance` DECIMAL(15,2) DEFAULT 0.00,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_account_balances_account` (`account_id`),
  INDEX `idx_account_balances_fiscal` (`fiscal_year_id`),
  INDEX `idx_account_balances_period` (`account_period_id`),
  UNIQUE INDEX `unique_account_balances_account_period` (`account_id`, `fiscal_year_id`, `account_period_id`, `period_type`),
  CONSTRAINT `fk_account_balances_account` FOREIGN KEY (`account_id`)       REFERENCES `chart_of_accounts`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_account_balances_fiscal`  FOREIGN KEY (`fiscal_year_id`)   REFERENCES `fiscal_years`(`id`)      ON DELETE CASCADE,
  CONSTRAINT `fk_account_balances_period`  FOREIGN KEY (`account_period_id`) REFERENCES `account_periods`(`id`)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `budgets`;
CREATE TABLE `budgets` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `fiscal_year_id`  BIGINT UNSIGNED NOT NULL,
  `profit_center_id` BIGINT UNSIGNED NULL,
  `cost_center_id`  BIGINT UNSIGNED NULL,
  `name`            VARCHAR(255) NOT NULL,
  `description`     TEXT NULL,
  `status`          ENUM('draft','active','locked','closed') DEFAULT 'draft',
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_budgets_fiscal` (`fiscal_year_id`),
  INDEX `idx_budgets_profit` (`profit_center_id`),
  INDEX `idx_budgets_cost` (`cost_center_id`),
  INDEX `idx_budgets_status` (`status`),
  CONSTRAINT `fk_budgets_fiscal`  FOREIGN KEY (`fiscal_year_id`)  REFERENCES `fiscal_years`(`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_budgets_profit`  FOREIGN KEY (`profit_center_id`) REFERENCES `profit_centers`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_budgets_cost`    FOREIGN KEY (`cost_center_id`)  REFERENCES `cost_centers`(`id`)   ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `budget_lines`;
CREATE TABLE `budget_lines` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `budget_id`   BIGINT UNSIGNED NOT NULL,
  `account_id`  BIGINT UNSIGNED NOT NULL,
  `period_id`   BIGINT UNSIGNED NULL,
  `amount`      DECIMAL(15,2) NOT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_budget_lines_budget` (`budget_id`),
  INDEX `idx_budget_lines_account` (`account_id`),
  INDEX `idx_budget_lines_period` (`period_id`),
  UNIQUE INDEX `unique_budget_lines_budget_account_period` (`budget_id`, `account_id`, `period_id`),
  CONSTRAINT `fk_budget_lines_budget`  FOREIGN KEY (`budget_id`)  REFERENCES `budgets`(`id`)             ON DELETE CASCADE,
  CONSTRAINT `fk_budget_lines_account` FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts`(`id`)    ON DELETE RESTRICT,
  CONSTRAINT `fk_budget_lines_period`  FOREIGN KEY (`period_id`)  REFERENCES `account_periods`(`id`)     ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `budget_versions`;
CREATE TABLE `budget_versions` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `budget_id`   BIGINT UNSIGNED NOT NULL,
  `version`     INT NOT NULL,
  `notes`       TEXT NULL,
  `snapshot`    JSON NULL,
  `created_by`  BIGINT UNSIGNED NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_budget_versions_budget` (`budget_id`),
  INDEX `idx_budget_versions_creator` (`created_by`),
  CONSTRAINT `fk_budget_versions_budget` FOREIGN KEY (`budget_id`) REFERENCES `budgets`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_budget_versions_creator` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cost_allocations`;
CREATE TABLE `cost_allocations` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `source_cost_center_id`  BIGINT UNSIGNED NOT NULL,
  `target_cost_center_id`  BIGINT UNSIGNED NOT NULL,
  `account_id`        BIGINT UNSIGNED NOT NULL,
  `allocation_method` ENUM('percentage','fixed','activity_based') NOT NULL DEFAULT 'percentage',
  `allocation_value`  DECIMAL(15,4) NOT NULL,
  `is_active`         BOOLEAN DEFAULT TRUE,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_cost_allocations_source` (`source_cost_center_id`),
  INDEX `idx_cost_allocations_target` (`target_cost_center_id`),
  INDEX `idx_cost_allocations_account` (`account_id`),
  CONSTRAINT `fk_cost_allocations_source` FOREIGN KEY (`source_cost_center_id`) REFERENCES `cost_centers`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cost_allocations_target` FOREIGN KEY (`target_cost_center_id`) REFERENCES `cost_centers`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cost_allocations_account` FOREIGN KEY (`account_id`)            REFERENCES `chart_of_accounts`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 19. INVENTORY & WAREHOUSE
-- ==========================================

DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE `warehouses` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(255) NOT NULL,
  `code`        VARCHAR(20) NOT NULL,
  `address`     TEXT NULL,
  `city`        VARCHAR(100) NULL,
  `state`       VARCHAR(100) NULL,
  `country`     VARCHAR(2) NULL,
  `postal_code` VARCHAR(20) NULL,
  `is_active`   BOOLEAN DEFAULT TRUE,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_warehouses_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `warehouse_locations`;
CREATE TABLE `warehouse_locations` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `warehouse_id` BIGINT UNSIGNED NOT NULL,
  `parent_id`    BIGINT UNSIGNED NULL,
  `code`         VARCHAR(50) NOT NULL,
  `name`         VARCHAR(255) NULL,
  `type`         ENUM('aisle','rack','shelf','bin','bulk') DEFAULT 'bin',
  `max_weight`   DECIMAL(10,2) NULL,
  `max_volume`   DECIMAL(10,2) NULL,
  `is_active`    BOOLEAN DEFAULT TRUE,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_warehouse_locations_wh_code` (`warehouse_id`, `code`),
  INDEX `idx_warehouse_locations_parent` (`parent_id`),
  CONSTRAINT `fk_warehouse_locations_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_warehouse_locations_parent`   FOREIGN KEY (`parent_id`)    REFERENCES `warehouse_locations`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `stock_items`;
CREATE TABLE `stock_items` (
  `id`                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id`          BIGINT UNSIGNED NOT NULL,
  `warehouse_location_id` BIGINT UNSIGNED NOT NULL,
  `serial_number`       VARCHAR(100) NULL,
  `batch_number`        VARCHAR(100) NULL,
  `quantity`            DECIMAL(15,4) NOT NULL DEFAULT 0,
  `reserved_quantity`   DECIMAL(15,4) DEFAULT 0,
  `unit_cost`           DECIMAL(15,2) NULL,
  `expiry_date`         DATE NULL,
  `status`              ENUM('available','reserved','quarantine','damaged','disposed') DEFAULT 'available',
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_stock_items_product` (`product_id`),
  INDEX `idx_stock_items_location` (`warehouse_location_id`),
  INDEX `idx_stock_items_serial` (`serial_number`),
  INDEX `idx_stock_items_batch` (`batch_number`),
  INDEX `idx_stock_items_status` (`status`),
  INDEX `idx_stock_items_product_location` (`product_id`, `warehouse_location_id`),
  CONSTRAINT `fk_stock_items_product`  FOREIGN KEY (`product_id`)           REFERENCES `products`(`id`)              ON DELETE CASCADE,
  CONSTRAINT `fk_stock_items_location` FOREIGN KEY (`warehouse_location_id`) REFERENCES `warehouse_locations`(`id`)  ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `inventory_movements`;
CREATE TABLE `inventory_movements` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id`        BIGINT UNSIGNED NOT NULL,
  `from_location_id`  BIGINT UNSIGNED NULL,
  `to_location_id`    BIGINT UNSIGNED NULL,
  `stock_item_id`     BIGINT UNSIGNED NULL,
  `movement_type`     ENUM('receipt','issue','transfer','adjustment','return','sale') NOT NULL,
  `reference_type`    VARCHAR(50) NULL,
  `reference_id`      BIGINT UNSIGNED NULL,
  `quantity`          DECIMAL(15,4) NOT NULL,
  `unit_cost`         DECIMAL(15,2) NULL,
  `notes`             TEXT NULL,
  `created_by`        BIGINT UNSIGNED NULL,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_inventory_movements_product` (`product_id`),
  INDEX `idx_inventory_movements_from` (`from_location_id`),
  INDEX `idx_inventory_movements_to` (`to_location_id`),
  INDEX `idx_inventory_movements_stock` (`stock_item_id`),
  INDEX `idx_inventory_movements_reference` (`reference_type`, `reference_id`),
  INDEX `idx_inventory_movements_creator` (`created_by`),
  INDEX `idx_inventory_movements_type` (`movement_type`),
  INDEX `idx_inventory_movements_created` (`created_at`),
  CONSTRAINT `fk_inventory_movements_product`  FOREIGN KEY (`product_id`)        REFERENCES `products`(`id`)              ON DELETE CASCADE,
  CONSTRAINT `fk_inventory_movements_from`     FOREIGN KEY (`from_location_id`)  REFERENCES `warehouse_locations`(`id`)  ON DELETE SET NULL,
  CONSTRAINT `fk_inventory_movements_to`       FOREIGN KEY (`to_location_id`)    REFERENCES `warehouse_locations`(`id`)  ON DELETE SET NULL,
  CONSTRAINT `fk_inventory_movements_stock`    FOREIGN KEY (`stock_item_id`)     REFERENCES `stock_items`(`id`)          ON DELETE SET NULL,
  CONSTRAINT `fk_inventory_movements_creator`  FOREIGN KEY (`created_by`)        REFERENCES `users`(`id`)                ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `inventory_adjustments`;
CREATE TABLE `inventory_adjustments` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id`        BIGINT UNSIGNED NOT NULL,
  `warehouse_location_id` BIGINT UNSIGNED NOT NULL,
  `adjustment_type`   ENUM('count','damage','write_off','return','reclassification') NOT NULL,
  `expected_qty`      DECIMAL(15,4) NOT NULL,
  `actual_qty`        DECIMAL(15,4) NOT NULL,
  `difference`        DECIMAL(15,4) NOT NULL,
  `reason`            TEXT NULL,
  `approved_by`       BIGINT UNSIGNED NULL,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_inventory_adjustments_product` (`product_id`),
  INDEX `idx_inventory_adjustments_location` (`warehouse_location_id`),
  INDEX `idx_inventory_adjustments_approver` (`approved_by`),
  INDEX `idx_inventory_adjustments_type` (`adjustment_type`),
  CONSTRAINT `fk_inventory_adjustments_product`  FOREIGN KEY (`product_id`)            REFERENCES `products`(`id`)              ON DELETE CASCADE,
  CONSTRAINT `fk_inventory_adjustments_location` FOREIGN KEY (`warehouse_location_id`) REFERENCES `warehouse_locations`(`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_inventory_adjustments_approver` FOREIGN KEY (`approved_by`)           REFERENCES `users`(`id`)                ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `stock_counts`;
CREATE TABLE `stock_counts` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `warehouse_id`    BIGINT UNSIGNED NOT NULL,
  `count_date`      DATE NOT NULL,
  `status`          ENUM('planned','in_progress','completed','verified') DEFAULT 'planned',
  `counted_by`      BIGINT UNSIGNED NULL,
  `verified_by`     BIGINT UNSIGNED NULL,
  `notes`           TEXT NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_stock_counts_warehouse` (`warehouse_id`),
  INDEX `idx_stock_counts_status` (`status`),
  CONSTRAINT `fk_stock_counts_warehouse`  FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_stock_counts_counter`    FOREIGN KEY (`counted_by`)   REFERENCES `users`(`id`)      ON DELETE SET NULL,
  CONSTRAINT `fk_stock_counts_verifier`   FOREIGN KEY (`verified_by`)  REFERENCES `users`(`id`)      ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `stock_count_items`;
CREATE TABLE `stock_count_items` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `stock_count_id`  BIGINT UNSIGNED NOT NULL,
  `product_id`      BIGINT UNSIGNED NOT NULL,
  `location_id`     BIGINT UNSIGNED NOT NULL,
  `expected_qty`    DECIMAL(15,4) NOT NULL,
  `counted_qty`     DECIMAL(15,4) NULL,
  `difference`      DECIMAL(15,4) GENERATED ALWAYS AS (counted_qty - expected_qty) STORED,
  `notes`           TEXT NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_stock_count_items_count` (`stock_count_id`),
  INDEX `idx_stock_count_items_product` (`product_id`),
  INDEX `idx_stock_count_items_location` (`location_id`),
  CONSTRAINT `fk_stock_count_items_count`    FOREIGN KEY (`stock_count_id`) REFERENCES `stock_counts`(`id`)       ON DELETE CASCADE,
  CONSTRAINT `fk_stock_count_items_product`  FOREIGN KEY (`product_id`)     REFERENCES `products`(`id`)            ON DELETE CASCADE,
  CONSTRAINT `fk_stock_count_items_location` FOREIGN KEY (`location_id`)    REFERENCES `warehouse_locations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `reorder_rules`;
CREATE TABLE `reorder_rules` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id`      BIGINT UNSIGNED NOT NULL,
  `warehouse_id`    BIGINT UNSIGNED NOT NULL,
  `min_quantity`    DECIMAL(15,4) NOT NULL,
  `max_quantity`    DECIMAL(15,4) NOT NULL,
  `reorder_point`   DECIMAL(15,4) NOT NULL,
  `reorder_qty`     DECIMAL(15,4) NOT NULL,
  `lead_time_days`  INT DEFAULT 0,
  `is_active`       BOOLEAN DEFAULT TRUE,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_reorder_rules_product` (`product_id`),
  INDEX `idx_reorder_rules_warehouse` (`warehouse_id`),
  UNIQUE INDEX `unique_reorder_rules_product_warehouse` (`product_id`, `warehouse_id`),
  CONSTRAINT `fk_reorder_rules_product`   FOREIGN KEY (`product_id`)   REFERENCES `products`(`id`)    ON DELETE CASCADE,
  CONSTRAINT `fk_reorder_rules_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `transfer_orders`;
CREATE TABLE `transfer_orders` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `from_warehouse_id` BIGINT UNSIGNED NOT NULL,
  `to_warehouse_id`   BIGINT UNSIGNED NOT NULL,
  `transfer_number` VARCHAR(50) NOT NULL,
  `status`          ENUM('draft','pending','approved','in_transit','completed','cancelled') DEFAULT 'draft',
  `requested_by`    BIGINT UNSIGNED NULL,
  `approved_by`     BIGINT UNSIGNED NULL,
  `notes`           TEXT NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_transfer_orders_number` (`transfer_number`),
  INDEX `idx_transfer_orders_from` (`from_warehouse_id`),
  INDEX `idx_transfer_orders_to` (`to_warehouse_id`),
  INDEX `idx_transfer_orders_status` (`status`),
  CONSTRAINT `fk_transfer_orders_from` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses`(`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_transfer_orders_to`   FOREIGN KEY (`to_warehouse_id`)   REFERENCES `warehouses`(`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_transfer_orders_requestor` FOREIGN KEY (`requested_by`) REFERENCES `users`(`id`)      ON DELETE SET NULL,
  CONSTRAINT `fk_transfer_orders_approver`  FOREIGN KEY (`approved_by`)  REFERENCES `users`(`id`)      ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `transfer_order_items`;
CREATE TABLE `transfer_order_items` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `transfer_order_id` BIGINT UNSIGNED NOT NULL,
  `product_id`        BIGINT UNSIGNED NOT NULL,
  `stock_item_id`     BIGINT UNSIGNED NULL,
  `quantity`          DECIMAL(15,4) NOT NULL,
  `received_qty`      DECIMAL(15,4) DEFAULT 0,
  `unit_cost`         DECIMAL(15,2) NULL,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_transfer_order_items_order` (`transfer_order_id`),
  INDEX `idx_transfer_order_items_product` (`product_id`),
  INDEX `idx_transfer_order_items_stock` (`stock_item_id`),
  CONSTRAINT `fk_transfer_order_items_order`   FOREIGN KEY (`transfer_order_id`) REFERENCES `transfer_orders`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_transfer_order_items_product`  FOREIGN KEY (`product_id`)       REFERENCES `products`(`id`)        ON DELETE CASCADE,
  CONSTRAINT `fk_transfer_order_items_stock`    FOREIGN KEY (`stock_item_id`)    REFERENCES `stock_items`(`id`)     ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 20. PROCUREMENT
-- ==========================================

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `company_name`  VARCHAR(255) NOT NULL,
  `supplier_code` VARCHAR(20) NOT NULL,
  `contact_name`  VARCHAR(255) NULL,
  `email`         VARCHAR(255) NULL,
  `phone`         VARCHAR(50) NULL,
  `website`       VARCHAR(500) NULL,
  `address`       TEXT NULL,
  `city`          VARCHAR(100) NULL,
  `state`         VARCHAR(100) NULL,
  `country`       VARCHAR(2) NULL,
  `postal_code`   VARCHAR(20) NULL,
  `tax_id`        VARCHAR(100) NULL,
  `payment_terms` VARCHAR(100) NULL,
  `currency_id`   BIGINT UNSIGNED NULL,
  `status`        ENUM('active','inactive','suspended') DEFAULT 'active',
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_suppliers_code` (`supplier_code`),
  INDEX `idx_suppliers_status` (`status`),
  INDEX `idx_suppliers_currency` (`currency_id`),
  CONSTRAINT `fk_suppliers_currency` FOREIGN KEY (`currency_id`) REFERENCES `currencies`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `supplier_contacts`;
CREATE TABLE `supplier_contacts` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `supplier_id` BIGINT UNSIGNED NOT NULL,
  `first_name`  VARCHAR(100) NOT NULL,
  `last_name`   VARCHAR(100) NOT NULL,
  `job_title`   VARCHAR(255) NULL,
  `email`       VARCHAR(255) NULL,
  `phone`       VARCHAR(50) NULL,
  `is_primary`  BOOLEAN DEFAULT FALSE,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_supplier_contacts_supplier` (`supplier_id`),
  CONSTRAINT `fk_supplier_contacts_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `supplier_products`;
CREATE TABLE `supplier_products` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `supplier_id`     BIGINT UNSIGNED NOT NULL,
  `product_id`      BIGINT UNSIGNED NOT NULL,
  `supplier_sku`    VARCHAR(100) NULL,
  `lead_time_days`  INT NULL,
  `moq`             INT NULL,
  `is_preferred`    BOOLEAN DEFAULT FALSE,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_supplier_products_supplier_product` (`supplier_id`, `product_id`),
  INDEX `idx_supplier_products_product` (`product_id`),
  CONSTRAINT `fk_supplier_products_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_supplier_products_product`  FOREIGN KEY (`product_id`)  REFERENCES `products`(`id`)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `supplier_pricelists`;
CREATE TABLE `supplier_pricelists` (
  `id`                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `supplier_product_id` BIGINT UNSIGNED NOT NULL,
  `unit_price`          DECIMAL(15,2) NOT NULL,
  `currency_id`         BIGINT UNSIGNED NOT NULL,
  `min_quantity`        INT DEFAULT 1,
  `effective_from`      DATE NOT NULL,
  `effective_until`     DATE NULL,
  `is_active`           BOOLEAN DEFAULT TRUE,
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_supplier_pricelists_product` (`supplier_product_id`),
  INDEX `idx_supplier_pricelists_currency` (`currency_id`),
  INDEX `idx_supplier_pricelists_dates` (`effective_from`, `effective_until`),
  CONSTRAINT `fk_supplier_pricelists_product`  FOREIGN KEY (`supplier_product_id`) REFERENCES `supplier_products`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_supplier_pricelists_currency` FOREIGN KEY (`currency_id`)         REFERENCES `currencies`(`id`)       ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE `purchase_orders` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `supplier_id`     BIGINT UNSIGNED NOT NULL,
  `order_number`    VARCHAR(50) NOT NULL,
  `status`          ENUM('draft','pending_approval','approved','sent','partial','received','cancelled') DEFAULT 'draft',
  `order_date`      DATE NOT NULL,
  `expected_date`   DATE NULL,
  `subtotal`        DECIMAL(15,2) DEFAULT 0.00,
  `tax`             DECIMAL(15,2) DEFAULT 0.00,
  `total`           DECIMAL(15,2) DEFAULT 0.00,
  `currency_id`     BIGINT UNSIGNED NOT NULL,
  `notes`           TEXT NULL,
  `requested_by`    BIGINT UNSIGNED NULL,
  `approved_by`     BIGINT UNSIGNED NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_purchase_orders_number` (`order_number`),
  INDEX `idx_purchase_orders_supplier` (`supplier_id`),
  INDEX `idx_purchase_orders_status` (`status`),
  INDEX `idx_purchase_orders_date` (`order_date`),
  INDEX `idx_purchase_orders_currency` (`currency_id`),
  INDEX `idx_purchase_orders_requestor` (`requested_by`),
  CONSTRAINT `fk_purchase_orders_supplier`  FOREIGN KEY (`supplier_id`)  REFERENCES `suppliers`(`id`)   ON DELETE RESTRICT,
  CONSTRAINT `fk_purchase_orders_currency`  FOREIGN KEY (`currency_id`)  REFERENCES `currencies`(`id`)  ON DELETE RESTRICT,
  CONSTRAINT `fk_purchase_orders_requestor` FOREIGN KEY (`requested_by`) REFERENCES `users`(`id`)       ON DELETE SET NULL,
  CONSTRAINT `fk_purchase_orders_approver`  FOREIGN KEY (`approved_by`)  REFERENCES `users`(`id`)       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `purchase_order_items`;
CREATE TABLE `purchase_order_items` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `purchase_order_id` BIGINT UNSIGNED NOT NULL,
  `product_id`      BIGINT UNSIGNED NOT NULL,
  `warehouse_location_id` BIGINT UNSIGNED NULL,
  `description`     TEXT NULL,
  `quantity`        DECIMAL(15,4) NOT NULL,
  `received_qty`    DECIMAL(15,4) DEFAULT 0,
  `unit_price`      DECIMAL(15,2) NOT NULL,
  `tax_rate`        DECIMAL(5,2) DEFAULT 0.00,
  `subtotal`        DECIMAL(15,2) NOT NULL,
  `line_order`      INT DEFAULT 0,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_po_items_order` (`purchase_order_id`),
  INDEX `idx_po_items_product` (`product_id`),
  INDEX `idx_po_items_location` (`warehouse_location_id`),
  CONSTRAINT `fk_po_items_order`    FOREIGN KEY (`purchase_order_id`)     REFERENCES `purchase_orders`(`id`)       ON DELETE CASCADE,
  CONSTRAINT `fk_po_items_product`  FOREIGN KEY (`product_id`)            REFERENCES `products`(`id`)              ON DELETE RESTRICT,
  CONSTRAINT `fk_po_items_location` FOREIGN KEY (`warehouse_location_id`) REFERENCES `warehouse_locations`(`id`)  ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `purchase_receipts`;
CREATE TABLE `purchase_receipts` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `purchase_order_id` BIGINT UNSIGNED NOT NULL,
  `receipt_number`    VARCHAR(50) NOT NULL,
  `received_date`     DATE NOT NULL,
  `status`            ENUM('draft','completed','partial','cancelled') DEFAULT 'draft',
  `notes`             TEXT NULL,
  `received_by`       BIGINT UNSIGNED NULL,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_purchase_receipts_number` (`receipt_number`),
  INDEX `idx_purchase_receipts_order` (`purchase_order_id`),
  INDEX `idx_purchase_receipts_receiver` (`received_by`),
  CONSTRAINT `fk_purchase_receipts_order`  FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_purchase_receipts_receiver` FOREIGN KEY (`received_by`)     REFERENCES `users`(`id`)           ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `purchase_receipt_items`;
CREATE TABLE `purchase_receipt_items` (
  `id`                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `purchase_receipt_id` BIGINT UNSIGNED NOT NULL,
  `po_item_id`          BIGINT UNSIGNED NOT NULL,
  `product_id`          BIGINT UNSIGNED NOT NULL,
  `warehouse_location_id` BIGINT UNSIGNED NOT NULL,
  `quantity`            DECIMAL(15,4) NOT NULL,
  `unit_cost`           DECIMAL(15,2) NULL,
  `batch_number`        VARCHAR(100) NULL,
  `expiry_date`         DATE NULL,
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_pr_items_receipt` (`purchase_receipt_id`),
  INDEX `idx_pr_items_po_item` (`po_item_id`),
  INDEX `idx_pr_items_product` (`product_id`),
  INDEX `idx_pr_items_location` (`warehouse_location_id`),
  CONSTRAINT `fk_pr_items_receipt`  FOREIGN KEY (`purchase_receipt_id`)   REFERENCES `purchase_receipts`(`id`)    ON DELETE CASCADE,
  CONSTRAINT `fk_pr_items_po_item`  FOREIGN KEY (`po_item_id`)            REFERENCES `purchase_order_items`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pr_items_product`  FOREIGN KEY (`product_id`)            REFERENCES `products`(`id`)             ON DELETE RESTRICT,
  CONSTRAINT `fk_pr_items_location` FOREIGN KEY (`warehouse_location_id`) REFERENCES `warehouse_locations`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `purchase_invoices`;
CREATE TABLE `purchase_invoices` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `purchase_order_id` BIGINT UNSIGNED NOT NULL,
  `supplier_id`     BIGINT UNSIGNED NOT NULL,
  `invoice_number`  VARCHAR(100) NOT NULL,
  `invoice_date`    DATE NOT NULL,
  `due_date`        DATE NULL,
  `subtotal`        DECIMAL(15,2) DEFAULT 0.00,
  `tax`             DECIMAL(15,2) DEFAULT 0.00,
  `total`           DECIMAL(15,2) DEFAULT 0.00,
  `currency_id`     BIGINT UNSIGNED NOT NULL,
  `status`          ENUM('pending','approved','paid','overdue','cancelled') DEFAULT 'pending',
  `notes`           TEXT NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_purchase_invoices_order` (`purchase_order_id`),
  INDEX `idx_purchase_invoices_supplier` (`supplier_id`),
  INDEX `idx_purchase_invoices_currency` (`currency_id`),
  INDEX `idx_purchase_invoices_status` (`status`),
  INDEX `idx_purchase_invoices_due` (`due_date`),
  CONSTRAINT `fk_purchase_invoices_order`    FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_purchase_invoices_supplier` FOREIGN KEY (`supplier_id`)       REFERENCES `suppliers`(`id`)       ON DELETE RESTRICT,
  CONSTRAINT `fk_purchase_invoices_currency` FOREIGN KEY (`currency_id`)       REFERENCES `currencies`(`id`)      ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `purchase_invoice_items`;
CREATE TABLE `purchase_invoice_items` (
  `id`                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `purchase_invoice_id` BIGINT UNSIGNED NOT NULL,
  `po_item_id`          BIGINT UNSIGNED NOT NULL,
  `product_id`          BIGINT UNSIGNED NOT NULL,
  `quantity`            DECIMAL(15,4) NOT NULL,
  `unit_price`          DECIMAL(15,2) NOT NULL,
  `subtotal`            DECIMAL(15,2) NOT NULL,
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_pi_items_invoice` (`purchase_invoice_id`),
  INDEX `idx_pi_items_po_item` (`po_item_id`),
  INDEX `idx_pi_items_product` (`product_id`),
  CONSTRAINT `fk_pi_items_invoice` FOREIGN KEY (`purchase_invoice_id`) REFERENCES `purchase_invoices`(`id`)   ON DELETE CASCADE,
  CONSTRAINT `fk_pi_items_po_item` FOREIGN KEY (`po_item_id`)          REFERENCES `purchase_order_items`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pi_items_product` FOREIGN KEY (`product_id`)          REFERENCES `products`(`id`)             ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `rfqs`;
CREATE TABLE `rfqs` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `rfq_number`    VARCHAR(50) NOT NULL,
  `title`         VARCHAR(255) NOT NULL,
  `description`   TEXT NULL,
  `issue_date`    DATE NOT NULL,
  `closing_date`  DATE NULL,
  `status`        ENUM('draft','sent','received','evaluating','awarded','cancelled') DEFAULT 'draft',
  `created_by`    BIGINT UNSIGNED NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_rfqs_number` (`rfq_number`),
  INDEX `idx_rfqs_status` (`status`),
  INDEX `idx_rfqs_creator` (`created_by`),
  CONSTRAINT `fk_rfqs_creator` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `rfq_items`;
CREATE TABLE `rfq_items` (
  `id`        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `rfq_id`    BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `quantity`  DECIMAL(15,4) NOT NULL,
  `notes`     TEXT NULL,
  `line_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_rfq_items_rfq` (`rfq_id`),
  INDEX `idx_rfq_items_product` (`product_id`),
  CONSTRAINT `fk_rfq_items_rfq`     FOREIGN KEY (`rfq_id`)     REFERENCES `rfqs`(`id`)     ON DELETE CASCADE,
  CONSTRAINT `fk_rfq_items_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `supplier_quotations`;
CREATE TABLE `supplier_quotations` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `rfq_id`        BIGINT UNSIGNED NOT NULL,
  `supplier_id`   BIGINT UNSIGNED NOT NULL,
  `quotation_number` VARCHAR(50) NULL,
  `quotation_date` DATE NOT NULL,
  `valid_until`   DATE NULL,
  `subtotal`      DECIMAL(15,2) DEFAULT 0.00,
  `tax`           DECIMAL(15,2) DEFAULT 0.00,
  `total`         DECIMAL(15,2) DEFAULT 0.00,
  `currency_id`   BIGINT UNSIGNED NOT NULL,
  `status`        ENUM('received','evaluated','accepted','rejected') DEFAULT 'received',
  `notes`         TEXT NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_supplier_quotations_rfq` (`rfq_id`),
  INDEX `idx_supplier_quotations_supplier` (`supplier_id`),
  INDEX `idx_supplier_quotations_currency` (`currency_id`),
  CONSTRAINT `fk_supplier_quotations_rfq`      FOREIGN KEY (`rfq_id`)      REFERENCES `rfqs`(`id`)       ON DELETE CASCADE,
  CONSTRAINT `fk_supplier_quotations_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`)   ON DELETE RESTRICT,
  CONSTRAINT `fk_supplier_quotations_currency` FOREIGN KEY (`currency_id`) REFERENCES `currencies`(`id`)  ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `quotation_items`;
CREATE TABLE `quotation_items` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `quotation_id`  BIGINT UNSIGNED NOT NULL,
  `rfq_item_id`   BIGINT UNSIGNED NOT NULL,
  `product_id`    BIGINT UNSIGNED NOT NULL,
  `quantity`      DECIMAL(15,4) NOT NULL,
  `unit_price`    DECIMAL(15,2) NOT NULL,
  `subtotal`      DECIMAL(15,2) NOT NULL,
  `line_order`    INT DEFAULT 0,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_quotation_items_quotation` (`quotation_id`),
  INDEX `idx_quotation_items_rfq_item` (`rfq_item_id`),
  INDEX `idx_quotation_items_product` (`product_id`),
  CONSTRAINT `fk_quotation_items_quotation` FOREIGN KEY (`quotation_id`) REFERENCES `supplier_quotations`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_quotation_items_rfq_item`  FOREIGN KEY (`rfq_item_id`)  REFERENCES `rfq_items`(`id`)           ON DELETE CASCADE,
  CONSTRAINT `fk_quotation_items_prod`      FOREIGN KEY (`product_id`)   REFERENCES `products`(`id`)            ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 21. PRODUCTION & CRP
-- ==========================================

DROP TABLE IF EXISTS `work_centers`;
CREATE TABLE `work_centers` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `code`            VARCHAR(20) NOT NULL,
  `name`            VARCHAR(255) NOT NULL,
  `type`            ENUM('machine','workstation','assembly_line','manual') NOT NULL,
  `description`     TEXT NULL,
  `cost_per_hour`   DECIMAL(15,2) DEFAULT 0.00,
  `efficiency_rate` DECIMAL(5,2) DEFAULT 100.00,
  `is_active`       BOOLEAN DEFAULT TRUE,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_work_centers_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `work_center_capacity`;
CREATE TABLE `work_center_capacity` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `work_center_id`  BIGINT UNSIGNED NOT NULL,
  `capacity_date`   DATE NOT NULL,
  `available_hours` DECIMAL(8,2) NOT NULL,
  `maintenance_hours` DECIMAL(8,2) DEFAULT 0,
  `booked_hours`    DECIMAL(8,2) DEFAULT 0,
  `overtime_hours`  DECIMAL(8,2) DEFAULT 0,
  `notes`           TEXT NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_wc_capacity_center` (`work_center_id`),
  UNIQUE INDEX `unique_wc_capacity_date` (`work_center_id`, `capacity_date`),
  CONSTRAINT `fk_wc_capacity_center` FOREIGN KEY (`work_center_id`) REFERENCES `work_centers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `bill_of_materials`;
CREATE TABLE `bill_of_materials` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id`  BIGINT UNSIGNED NOT NULL,
  `name`        VARCHAR(255) NOT NULL,
  `version`     VARCHAR(20) DEFAULT '1.0',
  `quantity`    DECIMAL(15,4) DEFAULT 1,
  `is_active`   BOOLEAN DEFAULT TRUE,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_bom_product` (`product_id`),
  INDEX `idx_bom_active` (`is_active`),
  CONSTRAINT `fk_bom_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `bom_items`;
CREATE TABLE `bom_items` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `bom_id`      BIGINT UNSIGNED NOT NULL,
  `component_id` BIGINT UNSIGNED NOT NULL,
  `quantity`    DECIMAL(15,4) NOT NULL,
  `unit`        VARCHAR(20) NULL,
  `scrap_rate`  DECIMAL(5,2) DEFAULT 0.00,
  `line_order`  INT DEFAULT 0,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_bom_items_bom` (`bom_id`),
  INDEX `idx_bom_items_component` (`component_id`),
  CONSTRAINT `fk_bom_items_bom`       FOREIGN KEY (`bom_id`)       REFERENCES `bill_of_materials`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bom_items_component` FOREIGN KEY (`component_id`) REFERENCES `products`(`id`)          ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `routings`;
CREATE TABLE `routings` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `bom_id`      BIGINT UNSIGNED NOT NULL,
  `name`        VARCHAR(255) NOT NULL,
  `total_time`  DECIMAL(8,2) NULL,
  `is_active`   BOOLEAN DEFAULT TRUE,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_routings_bom` (`bom_id`),
  CONSTRAINT `fk_routings_bom` FOREIGN KEY (`bom_id`) REFERENCES `bill_of_materials`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `routing_steps`;
CREATE TABLE `routing_steps` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `routing_id`    BIGINT UNSIGNED NOT NULL,
  `work_center_id` BIGINT UNSIGNED NOT NULL,
  `step_name`     VARCHAR(255) NOT NULL,
  `step_order`    INT NOT NULL,
  `setup_time`    DECIMAL(8,2) DEFAULT 0,
  `run_time`      DECIMAL(8,2) DEFAULT 0,
  `teardown_time` DECIMAL(8,2) DEFAULT 0,
  `notes`         TEXT NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_routing_steps_routing` (`routing_id`),
  INDEX `idx_routing_steps_center` (`work_center_id`),
  CONSTRAINT `fk_routing_steps_routing` FOREIGN KEY (`routing_id`)     REFERENCES `routings`(`id`)      ON DELETE CASCADE,
  CONSTRAINT `fk_routing_steps_center`  FOREIGN KEY (`work_center_id`) REFERENCES `work_centers`(`id`)  ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `production_orders`;
CREATE TABLE `production_orders` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id`      BIGINT UNSIGNED NOT NULL,
  `bom_id`          BIGINT UNSIGNED NOT NULL,
  `routing_id`      BIGINT UNSIGNED NULL,
  `warehouse_id`    BIGINT UNSIGNED NULL,
  `order_number`    VARCHAR(50) NOT NULL,
  `quantity`        DECIMAL(15,4) NOT NULL,
  `produced_qty`    DECIMAL(15,4) DEFAULT 0,
  `scrap_qty`       DECIMAL(15,4) DEFAULT 0,
  `status`          ENUM('planned','released','in_progress','completed','cancelled','on_hold') DEFAULT 'planned',
  `priority`        ENUM('low','medium','high','urgent') DEFAULT 'medium',
  `scheduled_start` DATETIME NULL,
  `scheduled_end`   DATETIME NULL,
  `actual_start`    DATETIME NULL,
  `actual_end`      DATETIME NULL,
  `notes`           TEXT NULL,
  `created_by`      BIGINT UNSIGNED NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_production_orders_number` (`order_number`),
  INDEX `idx_production_orders_product` (`product_id`),
  INDEX `idx_production_orders_bom` (`bom_id`),
  INDEX `idx_production_orders_routing` (`routing_id`),
  INDEX `idx_production_orders_warehouse` (`warehouse_id`),
  INDEX `idx_production_orders_status` (`status`),
  INDEX `idx_production_orders_schedule` (`scheduled_start`, `scheduled_end`),
  CONSTRAINT `fk_production_orders_product`   FOREIGN KEY (`product_id`)   REFERENCES `products`(`id`)          ON DELETE RESTRICT,
  CONSTRAINT `fk_production_orders_bom`       FOREIGN KEY (`bom_id`)       REFERENCES `bill_of_materials`(`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_production_orders_routing`   FOREIGN KEY (`routing_id`)   REFERENCES `routings`(`id`)          ON DELETE SET NULL,
  CONSTRAINT `fk_production_orders_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses`(`id`)        ON DELETE SET NULL,
  CONSTRAINT `fk_production_orders_creator`   FOREIGN KEY (`created_by`)   REFERENCES `users`(`id`)             ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `production_order_steps`;
CREATE TABLE `production_order_steps` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `production_order_id` BIGINT UNSIGNED NOT NULL,
  `routing_step_id`   BIGINT UNSIGNED NOT NULL,
  `work_center_id`    BIGINT UNSIGNED NOT NULL,
  `status`            ENUM('pending','in_progress','completed','skipped') DEFAULT 'pending',
  `actual_setup_time` DECIMAL(8,2) NULL,
  `actual_run_time`   DECIMAL(8,2) NULL,
  `completed_qty`     DECIMAL(15,4) DEFAULT 0,
  `scrap_qty`         DECIMAL(15,4) DEFAULT 0,
  `started_at`        DATETIME NULL,
  `completed_at`      DATETIME NULL,
  `notes`             TEXT NULL,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_prod_order_steps_order` (`production_order_id`),
  INDEX `idx_prod_order_steps_step` (`routing_step_id`),
  INDEX `idx_prod_order_steps_center` (`work_center_id`),
  INDEX `idx_prod_order_steps_status` (`status`),
  CONSTRAINT `fk_prod_order_steps_order`  FOREIGN KEY (`production_order_id`) REFERENCES `production_orders`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_prod_order_steps_step`   FOREIGN KEY (`routing_step_id`)     REFERENCES `routing_steps`(`id`)     ON DELETE RESTRICT,
  CONSTRAINT `fk_prod_order_steps_center` FOREIGN KEY (`work_center_id`)      REFERENCES `work_centers`(`id`)     ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `production_outputs`;
CREATE TABLE `production_outputs` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `production_order_id` BIGINT UNSIGNED NOT NULL,
  `product_id`        BIGINT UNSIGNED NOT NULL,
  `warehouse_location_id` BIGINT UNSIGNED NOT NULL,
  `quantity`          DECIMAL(15,4) NOT NULL,
  `unit_cost`         DECIMAL(15,2) NULL,
  `batch_number`      VARCHAR(100) NULL,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_production_outputs_order` (`production_order_id`),
  INDEX `idx_production_outputs_product` (`product_id`),
  INDEX `idx_production_outputs_location` (`warehouse_location_id`),
  CONSTRAINT `fk_production_outputs_order`    FOREIGN KEY (`production_order_id`)     REFERENCES `production_orders`(`id`)       ON DELETE CASCADE,
  CONSTRAINT `fk_production_outputs_product`  FOREIGN KEY (`product_id`)              REFERENCES `products`(`id`)                ON DELETE RESTRICT,
  CONSTRAINT `fk_production_outputs_location` FOREIGN KEY (`warehouse_location_id`)   REFERENCES `warehouse_locations`(`id`)    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `production_material_issues`;
CREATE TABLE `production_material_issues` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `production_order_id` BIGINT UNSIGNED NOT NULL,
  `stock_item_id`     BIGINT UNSIGNED NULL,
  `product_id`        BIGINT UNSIGNED NOT NULL,
  `warehouse_location_id` BIGINT UNSIGNED NOT NULL,
  `quantity`          DECIMAL(15,4) NOT NULL,
  `unit_cost`         DECIMAL(15,2) NULL,
  `issued_at`         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_prod_mat_issues_order` (`production_order_id`),
  INDEX `idx_prod_mat_issues_stock` (`stock_item_id`),
  INDEX `idx_prod_mat_issues_product` (`product_id`),
  INDEX `idx_prod_mat_issues_location` (`warehouse_location_id`),
  CONSTRAINT `fk_prod_mat_issues_order`    FOREIGN KEY (`production_order_id`)     REFERENCES `production_orders`(`id`)       ON DELETE CASCADE,
  CONSTRAINT `fk_prod_mat_issues_stock`    FOREIGN KEY (`stock_item_id`)           REFERENCES `stock_items`(`id`)            ON DELETE SET NULL,
  CONSTRAINT `fk_prod_mat_issues_product`  FOREIGN KEY (`product_id`)              REFERENCES `products`(`id`)               ON DELETE RESTRICT,
  CONSTRAINT `fk_prod_mat_issues_location` FOREIGN KEY (`warehouse_location_id`)   REFERENCES `warehouse_locations`(`id`)   ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `capacity_plans`;
CREATE TABLE `capacity_plans` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `work_center_id`  BIGINT UNSIGNED NOT NULL,
  `plan_date`       DATE NOT NULL,
  `planned_hours`   DECIMAL(8,2) NOT NULL,
  `actual_hours`    DECIMAL(8,2) DEFAULT 0,
  `available_hours` DECIMAL(8,2) NOT NULL,
  `load_percentage` DECIMAL(5,2) GENERATED ALWAYS AS ((planned_hours / NULLIF(available_hours, 0)) * 100) STORED,
  `notes`           TEXT NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_capacity_plans_center` (`work_center_id`),
  UNIQUE INDEX `unique_capacity_plans_center_date` (`work_center_id`, `plan_date`),
  CONSTRAINT `fk_capacity_plans_center` FOREIGN KEY (`work_center_id`) REFERENCES `work_centers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `maintenance_schedules`;
CREATE TABLE `maintenance_schedules` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `work_center_id`  BIGINT UNSIGNED NOT NULL,
  `title`           VARCHAR(255) NOT NULL,
  `type`            ENUM('preventive','predictive','corrective','emergency') NOT NULL,
  `frequency`       ENUM('daily','weekly','monthly','quarterly','yearly','hours') NOT NULL,
  `frequency_value` INT NULL,
  `last_done_at`    DATETIME NULL,
  `next_due_at`     DATETIME NULL,
  `estimated_hours` DECIMAL(8,2) NULL,
  `is_active`       BOOLEAN DEFAULT TRUE,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_maintenance_schedules_center` (`work_center_id`),
  INDEX `idx_maintenance_schedules_due` (`next_due_at`),
  CONSTRAINT `fk_maintenance_schedules_center` FOREIGN KEY (`work_center_id`) REFERENCES `work_centers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `maintenance_logs`;
CREATE TABLE `maintenance_logs` (
  `id`                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `maintenance_schedule_id` BIGINT UNSIGNED NULL,
  `work_center_id`      BIGINT UNSIGNED NOT NULL,
  `title`               VARCHAR(255) NOT NULL,
  `description`         TEXT NULL,
  `type`                ENUM('preventive','predictive','corrective','emergency') NOT NULL,
  `status`              ENUM('planned','in_progress','completed','cancelled') DEFAULT 'planned',
  `started_at`          DATETIME NULL,
  `completed_at`        DATETIME NULL,
  `duration_hours`      DECIMAL(8,2) NULL,
  `cost`                DECIMAL(15,2) NULL,
  `performed_by`        BIGINT UNSIGNED NULL,
  `notes`               TEXT NULL,
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_maintenance_logs_schedule` (`maintenance_schedule_id`),
  INDEX `idx_maintenance_logs_center` (`work_center_id`),
  INDEX `idx_maintenance_logs_status` (`status`),
  CONSTRAINT `fk_maintenance_logs_schedule` FOREIGN KEY (`maintenance_schedule_id`) REFERENCES `maintenance_schedules`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_maintenance_logs_center`   FOREIGN KEY (`work_center_id`)          REFERENCES `work_centers`(`id`)          ON DELETE CASCADE,
  CONSTRAINT `fk_maintenance_logs_operator` FOREIGN KEY (`performed_by`)            REFERENCES `users`(`id`)                 ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 22. HUMAN RESOURCES & PAYROLL
-- ==========================================

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `parent_id`   BIGINT UNSIGNED NULL,
  `code`        VARCHAR(20) NOT NULL,
  `name`        VARCHAR(255) NOT NULL,
  `manager_id`  BIGINT UNSIGNED NULL,
  `is_active`   BOOLEAN DEFAULT TRUE,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_departments_code` (`code`),
  INDEX `idx_departments_parent` (`parent_id`),
  INDEX `idx_departments_manager` (`manager_id`),
  CONSTRAINT `fk_departments_parent`  FOREIGN KEY (`parent_id`)  REFERENCES `departments`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_departments_manager` FOREIGN KEY (`manager_id`) REFERENCES `users`(`id`)       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `job_positions`;
CREATE TABLE `job_positions` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `department_id` BIGINT UNSIGNED NOT NULL,
  `title`         VARCHAR(255) NOT NULL,
  `description`   TEXT NULL,
  `requirements`  TEXT NULL,
  `salary_min`    DECIMAL(15,2) NULL,
  `salary_max`    DECIMAL(15,2) NULL,
  `is_active`     BOOLEAN DEFAULT TRUE,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_job_positions_department` (`department_id`),
  CONSTRAINT `fk_job_positions_department` FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`         BIGINT UNSIGNED NOT NULL,
  `employee_number` VARCHAR(20) NOT NULL,
  `department_id`   BIGINT UNSIGNED NOT NULL,
  `job_position_id` BIGINT UNSIGNED NOT NULL,
  `reports_to`      BIGINT UNSIGNED NULL,
  `hire_date`       DATE NOT NULL,
  `termination_date` DATE NULL,
  `employment_type` ENUM('full_time','part_time','contract','intern','temporary') NOT NULL DEFAULT 'full_time',
  `status`          ENUM('active','on_leave','terminated','suspended') DEFAULT 'active',
  `base_salary`     DECIMAL(15,2) NULL,
  `currency_id`     BIGINT UNSIGNED NOT NULL,
  `emergency_contact` JSON NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_employees_number` (`employee_number`),
  UNIQUE INDEX `unique_employees_user` (`user_id`),
  INDEX `idx_employees_department` (`department_id`),
  INDEX `idx_employees_position` (`job_position_id`),
  INDEX `idx_employees_reports` (`reports_to`),
  INDEX `idx_employees_status` (`status`),
  INDEX `idx_employees_currency` (`currency_id`),
  CONSTRAINT `fk_employees_user`       FOREIGN KEY (`user_id`)        REFERENCES `users`(`id`)          ON DELETE CASCADE,
  CONSTRAINT `fk_employees_department`  FOREIGN KEY (`department_id`)  REFERENCES `departments`(`id`)    ON DELETE RESTRICT,
  CONSTRAINT `fk_employees_position`   FOREIGN KEY (`job_position_id`) REFERENCES `job_positions`(`id`)  ON DELETE RESTRICT,
  CONSTRAINT `fk_employees_reports`    FOREIGN KEY (`reports_to`)     REFERENCES `employees`(`id`)      ON DELETE SET NULL,
  CONSTRAINT `fk_employees_currency`   FOREIGN KEY (`currency_id`)    REFERENCES `currencies`(`id`)     ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `employee_contracts`;
CREATE TABLE `employee_contracts` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `employee_id`   BIGINT UNSIGNED NOT NULL,
  `contract_type` ENUM('permanent','fixed_term','probation','consulting') NOT NULL,
  `start_date`    DATE NOT NULL,
  `end_date`      DATE NULL,
  `salary`        DECIMAL(15,2) NOT NULL,
  `currency_id`   BIGINT UNSIGNED NOT NULL,
  `benefits`      JSON NULL,
  `documents`     JSON NULL,
  `status`        ENUM('active','expired','terminated') DEFAULT 'active',
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_employee_contracts_employee` (`employee_id`),
  INDEX `idx_employee_contracts_currency` (`currency_id`),
  CONSTRAINT `fk_employee_contracts_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_employee_contracts_currency` FOREIGN KEY (`currency_id`) REFERENCES `currencies`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `employee_documents`;
CREATE TABLE `employee_documents` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `employee_id`  BIGINT UNSIGNED NOT NULL,
  `document_type` ENUM('id','passport','visa','certificate','contract','other') NOT NULL,
  `file_name`    VARCHAR(255) NOT NULL,
  `file_path`    VARCHAR(500) NOT NULL,
  `expiry_date`  DATE NULL,
  `is_verified`  BOOLEAN DEFAULT FALSE,
  `notes`        TEXT NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_employee_documents_employee` (`employee_id`),
  CONSTRAINT `fk_employee_documents_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `employee_id` BIGINT UNSIGNED NOT NULL,
  `date`        DATE NOT NULL,
  `clock_in`    DATETIME NULL,
  `clock_out`   DATETIME NULL,
  `total_hours` DECIMAL(5,2) GENERATED ALWAYS AS (TIMESTAMPDIFF(MINUTE, clock_in, clock_out) / 60) STORED,
  `status`      ENUM('present','absent','late','half_day','holiday') DEFAULT 'present',
  `notes`       TEXT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_attendance_employee` (`employee_id`),
  INDEX `idx_attendance_date` (`date`),
  UNIQUE INDEX `unique_attendance_employee_date` (`employee_id`, `date`),
  CONSTRAINT `fk_attendance_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `leave_types`;
CREATE TABLE `leave_types` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`            VARCHAR(100) NOT NULL,
  `code`            VARCHAR(20) NOT NULL,
  `days_allowed`    INT NOT NULL,
  `is_paid`         BOOLEAN DEFAULT TRUE,
  `carry_forward`   BOOLEAN DEFAULT FALSE,
  `max_carry_days`  INT NULL,
  `is_active`       BOOLEAN DEFAULT TRUE,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_leave_types_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `leave_requests`;
CREATE TABLE `leave_requests` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `employee_id`     BIGINT UNSIGNED NOT NULL,
  `leave_type_id`   BIGINT UNSIGNED NOT NULL,
  `start_date`      DATE NOT NULL,
  `end_date`        DATE NOT NULL,
  `total_days`      INT GENERATED ALWAYS AS (DATEDIFF(end_date, start_date) + 1) STORED,
  `reason`          TEXT NULL,
  `status`          ENUM('pending','approved','rejected','cancelled') DEFAULT 'pending',
  `approved_by`     BIGINT UNSIGNED NULL,
  `approved_at`     TIMESTAMP NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_leave_requests_employee` (`employee_id`),
  INDEX `idx_leave_requests_type` (`leave_type_id`),
  INDEX `idx_leave_requests_status` (`status`),
  INDEX `idx_leave_requests_approver` (`approved_by`),
  INDEX `idx_leave_requests_dates` (`start_date`, `end_date`),
  CONSTRAINT `fk_leave_requests_employee` FOREIGN KEY (`employee_id`)   REFERENCES `employees`(`id`)   ON DELETE CASCADE,
  CONSTRAINT `fk_leave_requests_type`     FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types`(`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_leave_requests_approver` FOREIGN KEY (`approved_by`)   REFERENCES `users`(`id`)       ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `leave_balances`;
CREATE TABLE `leave_balances` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `employee_id`   BIGINT UNSIGNED NOT NULL,
  `leave_type_id` BIGINT UNSIGNED NOT NULL,
  `year`          YEAR NOT NULL,
  `total_days`    DECIMAL(5,1) NOT NULL,
  `used_days`     DECIMAL(5,1) DEFAULT 0,
  `pending_days`  DECIMAL(5,1) DEFAULT 0,
  `remaining_days` DECIMAL(5,1) GENERATED ALWAYS AS (total_days - used_days - pending_days) STORED,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_leave_balances_employee` (`employee_id`),
  INDEX `idx_leave_balances_type` (`leave_type_id`),
  UNIQUE INDEX `unique_leave_balances_emp_year_type` (`employee_id`, `leave_type_id`, `year`),
  CONSTRAINT `fk_leave_balances_employee` FOREIGN KEY (`employee_id`)   REFERENCES `employees`(`id`)   ON DELETE CASCADE,
  CONSTRAINT `fk_leave_balances_type`     FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `timesheets`;
CREATE TABLE `timesheets` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `employee_id`   BIGINT UNSIGNED NOT NULL,
  `date`          DATE NOT NULL,
  `start_time`    TIME NOT NULL,
  `end_time`      TIME NULL,
  `total_hours`   DECIMAL(5,2) NULL,
  `break_hours`   DECIMAL(4,2) DEFAULT 0,
  `description`   TEXT NULL,
  `is_approved`   BOOLEAN DEFAULT FALSE,
  `approved_by`   BIGINT UNSIGNED NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_timesheets_employee` (`employee_id`),
  INDEX `idx_timesheets_date` (`date`),
  INDEX `idx_timesheets_approver` (`approved_by`),
  CONSTRAINT `fk_timesheets_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_timesheets_approver` FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`)      ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `payroll_components`;
CREATE TABLE `payroll_components` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(100) NOT NULL,
  `code`        VARCHAR(20) NOT NULL,
  `type`        ENUM('earning','deduction','employer_contribution') NOT NULL,
  `calculation` ENUM('fixed','percentage_of_basic','percentage_of_gross','formula') NOT NULL DEFAULT 'fixed',
  `value`       DECIMAL(15,2) NULL,
  `is_taxable`  BOOLEAN DEFAULT TRUE,
  `is_active`   BOOLEAN DEFAULT TRUE,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_payroll_components_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `payroll_runs`;
CREATE TABLE `payroll_runs` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `fiscal_year_id`    BIGINT UNSIGNED NOT NULL,
  `account_period_id` BIGINT UNSIGNED NULL,
  `run_number`        VARCHAR(50) NOT NULL,
  `period_start`      DATE NOT NULL,
  `period_end`        DATE NOT NULL,
  `payment_date`      DATE NULL,
  `status`            ENUM('draft','processing','completed','cancelled') DEFAULT 'draft',
  `total_gross`       DECIMAL(15,2) DEFAULT 0.00,
  `total_deductions`  DECIMAL(15,2) DEFAULT 0.00,
  `total_net`         DECIMAL(15,2) DEFAULT 0.00,
  `notes`             TEXT NULL,
  `processed_by`      BIGINT UNSIGNED NULL,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE INDEX `unique_payroll_runs_number` (`run_number`),
  INDEX `idx_payroll_runs_fiscal` (`fiscal_year_id`),
  INDEX `idx_payroll_runs_period` (`account_period_id`),
  INDEX `idx_payroll_runs_status` (`status`),
  CONSTRAINT `fk_payroll_runs_fiscal` FOREIGN KEY (`fiscal_year_id`)    REFERENCES `fiscal_years`(`id`)   ON DELETE RESTRICT,
  CONSTRAINT `fk_payroll_runs_period` FOREIGN KEY (`account_period_id`) REFERENCES `account_periods`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_payroll_runs_processor` FOREIGN KEY (`processed_by`)   REFERENCES `users`(`id`)          ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `payroll_items`;
CREATE TABLE `payroll_items` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `payroll_run_id` BIGINT UNSIGNED NOT NULL,
  `employee_id`   BIGINT UNSIGNED NOT NULL,
  `gross_pay`     DECIMAL(15,2) NOT NULL,
  `total_deductions` DECIMAL(15,2) DEFAULT 0.00,
  `net_pay`       DECIMAL(15,2) NOT NULL,
  `bank_account`  VARCHAR(100) NULL,
  `payment_method` ENUM('bank_transfer','check','cash') DEFAULT 'bank_transfer',
  `status`        ENUM('pending','paid','failed') DEFAULT 'pending',
  `paid_at`       TIMESTAMP NULL,
  `notes`         TEXT NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_payroll_items_run` (`payroll_run_id`),
  INDEX `idx_payroll_items_employee` (`employee_id`),
  INDEX `idx_payroll_items_status` (`status`),
  CONSTRAINT `fk_payroll_items_run`      FOREIGN KEY (`payroll_run_id`) REFERENCES `payroll_runs`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_payroll_items_employee` FOREIGN KEY (`employee_id`)    REFERENCES `employees`(`id`)    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- ==========================================
-- 23. AUTOMATION & WORKFLOW ENGINE
-- ==========================================

-- ==========================================
-- 23a. WORKFLOW DEFINITIONS
-- ==========================================

DROP TABLE IF EXISTS `workflow_definitions`;
CREATE TABLE `workflow_definitions` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(255) NOT NULL,
  `slug`        VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `category`    VARCHAR(100) NULL,
  `status`      ENUM('draft','active','paused','archived') NOT NULL DEFAULT 'draft',
  `version`     INT UNSIGNED NOT NULL DEFAULT 1,
  `config`      JSON NULL,
  `is_system`   BOOLEAN NOT NULL DEFAULT FALSE,
  `created_by`  BIGINT UNSIGNED NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_workflow_definitions_status` (`status`),
  INDEX `idx_workflow_definitions_category` (`category`),
  INDEX `idx_workflow_definitions_slug` (`slug`),
  CONSTRAINT `fk_workflow_definitions_creator` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23b. WORKFLOW NODES
-- ==========================================

DROP TABLE IF EXISTS `workflow_nodes`;
CREATE TABLE `workflow_nodes` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `workflow_id`    BIGINT UNSIGNED NOT NULL,
  `type`           ENUM('trigger','action','condition','approval','wait','gateway','end') NOT NULL,
  `name`           VARCHAR(255) NOT NULL,
  `description`    TEXT NULL,
  `config`         JSON NULL,
  `position_x`     INT NOT NULL DEFAULT 0,
  `position_y`     INT NOT NULL DEFAULT 0,
  `timeout_seconds` INT UNSIGNED NULL,
  `retry_count`    INT UNSIGNED NOT NULL DEFAULT 0,
  `retry_delay`    INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_workflow_nodes_workflow` (`workflow_id`),
  INDEX `idx_workflow_nodes_type` (`type`),
  CONSTRAINT `fk_workflow_nodes_workflow` FOREIGN KEY (`workflow_id`) REFERENCES `workflow_definitions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23c. WORKFLOW TRANSITIONS
-- ==========================================

DROP TABLE IF EXISTS `workflow_transitions`;
CREATE TABLE `workflow_transitions` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `workflow_id`   BIGINT UNSIGNED NOT NULL,
  `from_node_id`  BIGINT UNSIGNED NOT NULL,
  `to_node_id`    BIGINT UNSIGNED NOT NULL,
  `condition_expression` JSON NULL,
  `label`         VARCHAR(255) NULL,
  `priority`      INT NOT NULL DEFAULT 0,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_wf_transitions_workflow` (`workflow_id`),
  INDEX `idx_wf_transitions_from` (`from_node_id`),
  INDEX `idx_wf_transitions_to` (`to_node_id`),
  CONSTRAINT `fk_wf_transitions_workflow` FOREIGN KEY (`workflow_id`)  REFERENCES `workflow_definitions`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wf_transitions_from`     FOREIGN KEY (`from_node_id`) REFERENCES `workflow_nodes`(`id`)       ON DELETE CASCADE,
  CONSTRAINT `fk_wf_transitions_to`       FOREIGN KEY (`to_node_id`)   REFERENCES `workflow_nodes`(`id`)       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23d. WORKFLOW RUNS
-- ==========================================

DROP TABLE IF EXISTS `workflow_runs`;
CREATE TABLE `workflow_runs` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `workflow_id`     BIGINT UNSIGNED NOT NULL,
  `triggered_by`    BIGINT UNSIGNED NULL,
  `trigger_type`    VARCHAR(100) NULL,
  `trigger_payload` JSON NULL,
  `status`          ENUM('running','completed','failed','cancelled','paused') NOT NULL DEFAULT 'running',
  `current_node_id` BIGINT UNSIGNED NULL,
  `started_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `completed_at`    TIMESTAMP NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_workflow_runs_workflow` (`workflow_id`),
  INDEX `idx_workflow_runs_status` (`status`),
  INDEX `idx_workflow_runs_triggered_by` (`triggered_by`),
  CONSTRAINT `fk_workflow_runs_workflow`     FOREIGN KEY (`workflow_id`)     REFERENCES `workflow_definitions`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_workflow_runs_triggered_by` FOREIGN KEY (`triggered_by`)   REFERENCES `users`(`id`)               ON DELETE SET NULL,
  CONSTRAINT `fk_workflow_runs_current_node` FOREIGN KEY (`current_node_id`) REFERENCES `workflow_nodes`(`id`)      ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23e. WORKFLOW RUN LOGS
-- ==========================================

DROP TABLE IF EXISTS `workflow_run_logs`;
CREATE TABLE `workflow_run_logs` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `run_id`      BIGINT UNSIGNED NOT NULL,
  `node_id`     BIGINT UNSIGNED NULL,
  `action_type` VARCHAR(100) NULL,
  `level`       ENUM('info','warn','error','debug') NOT NULL DEFAULT 'info',
  `message`     TEXT NOT NULL,
  `payload`     JSON NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_wf_run_logs_run` (`run_id`),
  INDEX `idx_wf_run_logs_node` (`node_id`),
  CONSTRAINT `fk_wf_run_logs_run`  FOREIGN KEY (`run_id`)  REFERENCES `workflow_runs`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wf_run_logs_node` FOREIGN KEY (`node_id`) REFERENCES `workflow_nodes`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23f. WORKFLOW RUN NODE STATES
-- ==========================================

DROP TABLE IF EXISTS `workflow_run_node_states`;
CREATE TABLE `workflow_run_node_states` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `run_id`       BIGINT UNSIGNED NOT NULL,
  `node_id`      BIGINT UNSIGNED NOT NULL,
  `status`       ENUM('pending','running','completed','failed','skipped','retrying') NOT NULL DEFAULT 'pending',
  `input`        JSON NULL,
  `output`       JSON NULL,
  `attempts`     INT UNSIGNED NOT NULL DEFAULT 0,
  `started_at`   TIMESTAMP NULL,
  `completed_at` TIMESTAMP NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_wf_run_node_states_run` (`run_id`),
  INDEX `idx_wf_run_node_states_node` (`node_id`),
  INDEX `idx_wf_run_node_states_status` (`status`),
  CONSTRAINT `fk_wf_run_node_states_run`  FOREIGN KEY (`run_id`)  REFERENCES `workflow_runs`(`id`)  ON DELETE CASCADE,
  CONSTRAINT `fk_wf_run_node_states_node` FOREIGN KEY (`node_id`) REFERENCES `workflow_nodes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23g. WORKFLOW RUN VARIABLES
-- ==========================================

DROP TABLE IF EXISTS `workflow_run_variables`;
CREATE TABLE `workflow_run_variables` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `run_id`      BIGINT UNSIGNED NOT NULL,
  `name`        VARCHAR(255) NOT NULL,
  `value`       JSON NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_wf_run_vars_run` (`run_id`),
  UNIQUE INDEX `idx_wf_run_vars_name` (`run_id`, `name`),
  CONSTRAINT `fk_wf_run_vars_run` FOREIGN KEY (`run_id`) REFERENCES `workflow_runs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23h. TRIGGERS
-- ==========================================

DROP TABLE IF EXISTS `triggers`;
CREATE TABLE `triggers` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(255) NOT NULL,
  `slug`        VARCHAR(255) NOT NULL UNIQUE,
  `event_type`  VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  `config`      JSON NULL,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_triggers_event_type` (`event_type`),
  INDEX `idx_triggers_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23i. TRIGGER-WORKFLOW MAPPINGS
-- ==========================================

DROP TABLE IF EXISTS `trigger_workflow_mappings`;
CREATE TABLE `trigger_workflow_mappings` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `trigger_id`  BIGINT UNSIGNED NOT NULL,
  `workflow_id` BIGINT UNSIGNED NOT NULL,
  `priority`    INT NOT NULL DEFAULT 0,
  `conditions`  JSON NULL,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_twm_trigger` (`trigger_id`),
  INDEX `idx_twm_workflow` (`workflow_id`),
  CONSTRAINT `fk_twm_trigger`  FOREIGN KEY (`trigger_id`)  REFERENCES `triggers`(`id`)             ON DELETE CASCADE,
  CONSTRAINT `fk_twm_workflow` FOREIGN KEY (`workflow_id`) REFERENCES `workflow_definitions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23j. EVENT LOG
-- ==========================================

DROP TABLE IF EXISTS `event_log`;
CREATE TABLE `event_log` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `event_type`    VARCHAR(100) NOT NULL,
  `source_type`   VARCHAR(100) NULL,
  `source_id`     BIGINT UNSIGNED NULL,
  `payload`       JSON NULL,
  `occurred_at`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `processed_at`  TIMESTAMP NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_event_log_type` (`event_type`),
  INDEX `idx_event_log_source` (`source_type`, `source_id`),
  INDEX `idx_event_log_occurred` (`occurred_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23k. CONDITION GROUPS
-- ==========================================

DROP TABLE IF EXISTS `condition_groups`;
CREATE TABLE `condition_groups` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(255) NOT NULL,
  `operator`    ENUM('AND','OR') NOT NULL DEFAULT 'AND',
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23l. CONDITION RULES
-- ==========================================

DROP TABLE IF EXISTS `condition_rules`;
CREATE TABLE `condition_rules` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `group_id`    BIGINT UNSIGNED NOT NULL,
  `field`       VARCHAR(255) NOT NULL,
  `operator`    ENUM('equals','not_equals','greater_than','less_than','greater_or_equal','less_or_equal','contains','not_contains','in','not_in','starts_with','ends_with','is_empty','is_not_empty','matches') NOT NULL,
  `value`       JSON NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_condition_rules_group` (`group_id`),
  CONSTRAINT `fk_condition_rules_group` FOREIGN KEY (`group_id`) REFERENCES `condition_groups`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23m. CONDITION GROUP MAPPINGS
-- ==========================================

DROP TABLE IF EXISTS `condition_group_mappings`;
CREATE TABLE `condition_group_mappings` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `group_id`    BIGINT UNSIGNED NOT NULL,
  `entity_type` VARCHAR(100) NOT NULL,
  `entity_id`   BIGINT UNSIGNED NOT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_cgm_group` (`group_id`),
  INDEX `idx_cgm_entity` (`entity_type`, `entity_id`),
  CONSTRAINT `fk_cgm_group` FOREIGN KEY (`group_id`) REFERENCES `condition_groups`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23n. APPROVAL REQUESTS
-- ==========================================

DROP TABLE IF EXISTS `approval_requests`;
CREATE TABLE `approval_requests` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `workflow_run_id` BIGINT UNSIGNED NOT NULL,
  `node_id`         BIGINT UNSIGNED NOT NULL,
  `status`          ENUM('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `requested_by`    BIGINT UNSIGNED NULL,
  `requested_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `responded_at`    TIMESTAMP NULL,
  `notes`           TEXT NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_approval_requests_run` (`workflow_run_id`),
  INDEX `idx_approval_requests_node` (`node_id`),
  INDEX `idx_approval_requests_status` (`status`),
  INDEX `idx_approval_requests_requested_by` (`requested_by`),
  CONSTRAINT `fk_approval_requests_run`  FOREIGN KEY (`workflow_run_id`) REFERENCES `workflow_runs`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_approval_requests_node` FOREIGN KEY (`node_id`)         REFERENCES `workflow_nodes`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_approval_requests_requested_by` FOREIGN KEY (`requested_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23o. APPROVAL STAGES
-- ==========================================

DROP TABLE IF EXISTS `approval_stages`;
CREATE TABLE `approval_stages` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `approval_request_id` BIGINT UNSIGNED NOT NULL,
  `stage_order`   INT UNSIGNED NOT NULL,
  `status`        ENUM('pending','approved','rejected','skipped') NOT NULL DEFAULT 'pending',
  `strategy`      ENUM('any','all') NOT NULL DEFAULT 'all',
  `min_approvers` INT UNSIGNED NOT NULL DEFAULT 1,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_approval_stages_request` (`approval_request_id`),
  CONSTRAINT `fk_approval_stages_request` FOREIGN KEY (`approval_request_id`) REFERENCES `approval_requests`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23p. APPROVAL ASSIGNEES
-- ==========================================

DROP TABLE IF EXISTS `approval_assignees`;
CREATE TABLE `approval_assignees` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `stage_id`     BIGINT UNSIGNED NOT NULL,
  `user_id`      BIGINT UNSIGNED NOT NULL,
  `status`       ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `response`     TEXT NULL,
  `responded_at` TIMESTAMP NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_approval_assignees_stage` (`stage_id`),
  INDEX `idx_approval_assignees_user` (`user_id`),
  CONSTRAINT `fk_approval_assignees_stage` FOREIGN KEY (`stage_id`) REFERENCES `approval_stages`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_approval_assignees_user`  FOREIGN KEY (`user_id`)  REFERENCES `users`(`id`)          ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23q. EMAIL AUTOMATIONS
-- ==========================================

DROP TABLE IF EXISTS `email_automations`;
CREATE TABLE `email_automations` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`             VARCHAR(255) NOT NULL,
  `trigger_event`    VARCHAR(100) NOT NULL,
  `email_template_id` BIGINT UNSIGNED NULL,
  `conditions`       JSON NULL,
  `audience_filter`  JSON NULL,
  `sender_name`      VARCHAR(255) NULL,
  `sender_email`     VARCHAR(255) NULL,
  `reply_to`         VARCHAR(255) NULL,
  `status`           ENUM('draft','active','paused','archived') NOT NULL DEFAULT 'draft',
  `created_by`       BIGINT UNSIGNED NULL,
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_email_automations_event` (`trigger_event`),
  INDEX `idx_email_automations_status` (`status`),
  INDEX `idx_email_automations_template` (`email_template_id`),
  CONSTRAINT `fk_email_automations_template` FOREIGN KEY (`email_template_id`) REFERENCES `email_templates`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_email_automations_creator`  FOREIGN KEY (`created_by`)        REFERENCES `users`(`id`)          ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23r. SCHEDULED TASKS
-- ==========================================

DROP TABLE IF EXISTS `scheduled_tasks`;
CREATE TABLE `scheduled_tasks` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`            VARCHAR(255) NOT NULL,
  `description`     TEXT NULL,
  `cron_expression` VARCHAR(100) NOT NULL,
  `task_type`       ENUM('run_workflow','call_webhook','send_report','run_sql','custom') NOT NULL,
  `config`          JSON NULL,
  `status`          ENUM('active','paused','completed','failed') NOT NULL DEFAULT 'active',
  `last_run_at`     TIMESTAMP NULL,
  `next_run_at`     TIMESTAMP NULL,
  `is_system`       BOOLEAN NOT NULL DEFAULT FALSE,
  `created_by`      BIGINT UNSIGNED NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_scheduled_tasks_status` (`status`),
  INDEX `idx_scheduled_tasks_next_run` (`next_run_at`),
  INDEX `idx_scheduled_tasks_type` (`task_type`),
  CONSTRAINT `fk_scheduled_tasks_creator` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 23s. WEBHOOK DELIVERY LOGS
-- ==========================================

DROP TABLE IF EXISTS `webhook_delivery_logs`;
CREATE TABLE `webhook_delivery_logs` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `webhook_id`       BIGINT UNSIGNED NOT NULL,
  `event_type`       VARCHAR(100) NOT NULL,
  `payload`          JSON NULL,
  `request_headers`  JSON NULL,
  `response_status`  INT UNSIGNED NULL,
  `response_body`    TEXT NULL,
  `attempt`          INT UNSIGNED NOT NULL DEFAULT 1,
  `success`          BOOLEAN NOT NULL DEFAULT FALSE,
  `error_message`    TEXT NULL,
  `delivered_at`     TIMESTAMP NULL,
  `next_retry_at`    TIMESTAMP NULL,
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_webhook_delivery_logs_webhook` (`webhook_id`),
  INDEX `idx_webhook_delivery_logs_success` (`success`),
  INDEX `idx_webhook_delivery_logs_event` (`event_type`),
  CONSTRAINT `fk_webhook_delivery_logs_webhook` FOREIGN KEY (`webhook_id`) REFERENCES `webhooks`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `payroll_item_details`;
CREATE TABLE `payroll_item_details` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `payroll_item_id`   BIGINT UNSIGNED NOT NULL,
  `payroll_component_id` BIGINT UNSIGNED NOT NULL,
  `amount`            DECIMAL(15,2) NOT NULL,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_payroll_item_details_item` (`payroll_item_id`),
  INDEX `idx_payroll_item_details_component` (`payroll_component_id`),
  CONSTRAINT `fk_payroll_item_details_item`      FOREIGN KEY (`payroll_item_id`)      REFERENCES `payroll_items`(`id`)       ON DELETE CASCADE,
  CONSTRAINT `fk_payroll_item_details_component` FOREIGN KEY (`payroll_component_id`) REFERENCES `payroll_components`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 24. REPORTING & DASHBOARDS
-- ==========================================

-- ==========================================
-- 24a. REPORT CATEGORIES
-- ==========================================

DROP TABLE IF EXISTS `report_categories`;
CREATE TABLE `report_categories` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(255) NOT NULL,
  `slug`        VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `parent_id`   BIGINT UNSIGNED NULL,
  `sort_order`  INT NOT NULL DEFAULT 0,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_report_categories_parent` (`parent_id`),
  CONSTRAINT `fk_report_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `report_categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 24b. REPORT DEFINITIONS
-- ==========================================

DROP TABLE IF EXISTS `report_definitions`;
CREATE TABLE `report_definitions` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(255) NOT NULL,
  `slug`        VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `category_id` BIGINT UNSIGNED NULL,
  `report_type` ENUM('tabular','chart','pivot','summary') NOT NULL DEFAULT 'tabular',
  `config`      JSON NOT NULL,
  `is_system`   BOOLEAN NOT NULL DEFAULT FALSE,
  `created_by`  BIGINT UNSIGNED NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_report_definitions_category` (`category_id`),
  INDEX `idx_report_definitions_type` (`report_type`),
  CONSTRAINT `fk_report_definitions_category` FOREIGN KEY (`category_id`) REFERENCES `report_categories`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_report_definitions_creator`  FOREIGN KEY (`created_by`)  REFERENCES `users`(`id`)          ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 24c. REPORT SCHEDULES
-- ==========================================

DROP TABLE IF EXISTS `report_schedules`;
CREATE TABLE `report_schedules` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `report_id`      BIGINT UNSIGNED NOT NULL,
  `name`           VARCHAR(255) NOT NULL,
  `cron_expression` VARCHAR(100) NOT NULL,
  `recipients`     JSON NOT NULL,
  `format`         ENUM('pdf','csv','excel','json') NOT NULL DEFAULT 'pdf',
  `config`         JSON NULL,
  `last_run_at`    TIMESTAMP NULL,
  `next_run_at`    TIMESTAMP NULL,
  `status`         ENUM('active','paused','completed','failed') NOT NULL DEFAULT 'active',
  `created_by`     BIGINT UNSIGNED NULL,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_report_schedules_report` (`report_id`),
  INDEX `idx_report_schedules_status` (`status`),
  INDEX `idx_report_schedules_next_run` (`next_run_at`),
  CONSTRAINT `fk_report_schedules_report`   FOREIGN KEY (`report_id`)   REFERENCES `report_definitions`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_report_schedules_creator`  FOREIGN KEY (`created_by`)  REFERENCES `users`(`id`)             ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 24d. DASHBOARD WIDGETS
-- ==========================================

DROP TABLE IF EXISTS `dashboard_widgets`;
CREATE TABLE `dashboard_widgets` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`        BIGINT UNSIGNED NULL,
  `dashboard_name` VARCHAR(100) NOT NULL DEFAULT 'default',
  `widget_type`    VARCHAR(100) NOT NULL,
  `title`          VARCHAR(255) NULL,
  `config`         JSON NULL,
  `position_x`     INT NOT NULL DEFAULT 0,
  `position_y`     INT NOT NULL DEFAULT 0,
  `width`          INT NOT NULL DEFAULT 4,
  `height`         INT NOT NULL DEFAULT 3,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_dashboard_widgets_user` (`user_id`),
  INDEX `idx_dashboard_widgets_dashboard` (`dashboard_name`),
  CONSTRAINT `fk_dashboard_widgets_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 25. PRIVACY & GDPR
-- ==========================================

-- ==========================================
-- 25a. CONSENT LOGS
-- ==========================================

DROP TABLE IF EXISTS `consent_logs`;
CREATE TABLE `consent_logs` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`      BIGINT UNSIGNED NOT NULL,
  `consent_type` ENUM('marketing','analytics','functional','third_party','cookies') NOT NULL,
  `purpose`      VARCHAR(255) NULL,
  `granted`      BOOLEAN NOT NULL,
  `ip_address`   VARCHAR(45) NULL,
  `user_agent`   TEXT NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_consent_logs_user` (`user_id`),
  INDEX `idx_consent_logs_type` (`consent_type`),
  CONSTRAINT `fk_consent_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 25b. DATA EXPORT REQUESTS
-- ==========================================

DROP TABLE IF EXISTS `data_export_requests`;
CREATE TABLE `data_export_requests` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`      BIGINT UNSIGNED NOT NULL,
  `status`       ENUM('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
  `format`       ENUM('json','csv','xml') NOT NULL DEFAULT 'json',
  `requested_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `completed_at` TIMESTAMP NULL,
  `file_path`    VARCHAR(500) NULL,
  `expires_at`   TIMESTAMP NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_data_export_requests_user` (`user_id`),
  INDEX `idx_data_export_requests_status` (`status`),
  CONSTRAINT `fk_data_export_requests_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 25c. DATA DELETION REQUESTS
-- ==========================================

DROP TABLE IF EXISTS `data_deletion_requests`;
CREATE TABLE `data_deletion_requests` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`      BIGINT UNSIGNED NOT NULL,
  `status`       ENUM('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `reason`       TEXT NULL,
  `requested_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `processed_at` TIMESTAMP NULL,
  `processed_by` BIGINT UNSIGNED NULL,
  `notes`        TEXT NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_data_deletion_requests_user` (`user_id`),
  INDEX `idx_data_deletion_requests_status` (`status`),
  INDEX `idx_data_deletion_requests_processor` (`processed_by`),
  CONSTRAINT `fk_data_deletion_requests_user`      FOREIGN KEY (`user_id`)      REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_data_deletion_requests_processor` FOREIGN KEY (`processed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 25d. COOKIE CONSENT SETTINGS
-- ==========================================

DROP TABLE IF EXISTS `cookie_consent_settings`;
CREATE TABLE `cookie_consent_settings` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`            VARCHAR(255) NOT NULL,
  `slug`            VARCHAR(255) NOT NULL UNIQUE,
  `description`     TEXT NULL,
  `required`        BOOLEAN NOT NULL DEFAULT FALSE,
  `default_granted` BOOLEAN NOT NULL DEFAULT FALSE,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 26. TAX ENGINE
-- ==========================================

-- ==========================================
-- 26a. TAX JURISDICTIONS
-- ==========================================

DROP TABLE IF EXISTS `tax_jurisdictions`;
CREATE TABLE `tax_jurisdictions` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`           VARCHAR(255) NOT NULL,
  `country`        VARCHAR(2) NOT NULL,
  `state`          VARCHAR(100) NULL,
  `city`           VARCHAR(100) NULL,
  `postal_code`    VARCHAR(20) NULL,
  `rate`           DECIMAL(5,4) NOT NULL,
  `tax_type`       ENUM('sales','vat','gst','hst') NOT NULL DEFAULT 'sales',
  `is_compound`    BOOLEAN NOT NULL DEFAULT FALSE,
  `priority`       INT NOT NULL DEFAULT 0,
  `status`         ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `effective_from` DATE NULL,
  `effective_to`   DATE NULL,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_tax_jurisdictions_country` (`country`),
  INDEX `idx_tax_jurisdictions_status` (`status`),
  INDEX `idx_tax_jurisdictions_location` (`country`, `state`, `city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 26b. TAX EXEMPTIONS
-- ==========================================

DROP TABLE IF EXISTS `tax_exemptions`;
CREATE TABLE `tax_exemptions` (
  `id`                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`           BIGINT UNSIGNED NOT NULL,
  `product_id`        BIGINT UNSIGNED NULL,
  `exemption_type`    VARCHAR(100) NOT NULL,
  `certificate_number` VARCHAR(255) NULL,
  `issuing_authority` VARCHAR(255) NULL,
  `valid_from`        DATE NULL,
  `valid_to`          DATE NULL,
  `status`            ENUM('pending','active','expired','revoked') NOT NULL DEFAULT 'pending',
  `verified_by`       BIGINT UNSIGNED NULL,
  `created_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_tax_exemptions_user` (`user_id`),
  INDEX `idx_tax_exemptions_product` (`product_id`),
  INDEX `idx_tax_exemptions_status` (`status`),
  CONSTRAINT `fk_tax_exemptions_user`      FOREIGN KEY (`user_id`)      REFERENCES `users`(`id`)    ON DELETE CASCADE,
  CONSTRAINT `fk_tax_exemptions_product`   FOREIGN KEY (`product_id`)   REFERENCES `products`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tax_exemptions_verifier`  FOREIGN KEY (`verified_by`)  REFERENCES `users`(`id`)    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 26c. TAX RULES
-- ==========================================

DROP TABLE IF EXISTS `tax_rules`;
CREATE TABLE `tax_rules` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`        VARCHAR(255) NOT NULL,
  `priority`    INT NOT NULL DEFAULT 0,
  `conditions`  JSON NULL,
  `action_type` ENUM('rate_override','exempt','compound','reduce') NOT NULL,
  `action_value` JSON NOT NULL,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_tax_rules_status` (`status`),
  INDEX `idx_tax_rules_priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 26d. TAX REPORT DATA
-- ==========================================

DROP TABLE IF EXISTS `tax_report_data`;
CREATE TABLE `tax_report_data` (
  `id`                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `tax_jurisdiction_id` BIGINT UNSIGNED NOT NULL,
  `period_start`        DATE NOT NULL,
  `period_end`          DATE NOT NULL,
  `taxable_amount`      DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `tax_collected`       DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `returns_filed`       BOOLEAN NOT NULL DEFAULT FALSE,
  `filed_at`            TIMESTAMP NULL,
  `notes`               TEXT NULL,
  `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_tax_report_data_jurisdiction` (`tax_jurisdiction_id`),
  INDEX `idx_tax_report_data_period` (`period_start`, `period_end`),
  CONSTRAINT `fk_tax_report_data_jurisdiction` FOREIGN KEY (`tax_jurisdiction_id`) REFERENCES `tax_jurisdictions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 27. DYNAMIC PRICING
-- ==========================================

-- ==========================================
-- 27a. PRICE RULES
-- ==========================================

DROP TABLE IF EXISTS `price_rules`;
CREATE TABLE `price_rules` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`          VARCHAR(255) NOT NULL,
  `slug`          VARCHAR(255) NOT NULL UNIQUE,
  `description`   TEXT NULL,
  `priority`      INT NOT NULL DEFAULT 0,
  `conditions`    JSON NULL,
  `adjustments`   JSON NOT NULL,
  `applies_to`    ENUM('all','products','categories','users','user_roles') NOT NULL DEFAULT 'all',
  `stackable`     BOOLEAN NOT NULL DEFAULT FALSE,
  `status`        ENUM('active','inactive','expired') NOT NULL DEFAULT 'active',
  `starts_at`     TIMESTAMP NULL,
  `expires_at`    TIMESTAMP NULL,
  `created_by`    BIGINT UNSIGNED NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_price_rules_status` (`status`),
  INDEX `idx_price_rules_dates` (`starts_at`, `expires_at`),
  CONSTRAINT `fk_price_rules_creator` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 27b. PRICE TIERS
-- ==========================================

DROP TABLE IF EXISTS `price_tiers`;
CREATE TABLE `price_tiers` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id`  BIGINT UNSIGNED NOT NULL,
  `min_quantity` INT UNSIGNED NOT NULL,
  `max_quantity` INT UNSIGNED NULL,
  `unit_price`  DECIMAL(15,2) NOT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_price_tiers_product` (`product_id`),
  CONSTRAINT `fk_price_tiers_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 27c. PRICE OVERRIDES
-- ==========================================

DROP TABLE IF EXISTS `price_overrides`;
CREATE TABLE `price_overrides` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`        BIGINT UNSIGNED NOT NULL,
  `product_id`     BIGINT UNSIGNED NULL,
  `plan_id`        BIGINT UNSIGNED NULL,
  `override_price` DECIMAL(15,2) NOT NULL,
  `override_type`  ENUM('fixed','percentage') NOT NULL DEFAULT 'fixed',
  `starts_at`      TIMESTAMP NULL,
  `expires_at`     TIMESTAMP NULL,
  `reason`         TEXT NULL,
  `created_by`     BIGINT UNSIGNED NULL,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_price_overrides_user` (`user_id`),
  INDEX `idx_price_overrides_product` (`product_id`),
  INDEX `idx_price_overrides_plan` (`plan_id`),
  CONSTRAINT `fk_price_overrides_user`     FOREIGN KEY (`user_id`)     REFERENCES `users`(`id`)               ON DELETE CASCADE,
  CONSTRAINT `fk_price_overrides_product`  FOREIGN KEY (`product_id`)  REFERENCES `products`(`id`)           ON DELETE CASCADE,
  CONSTRAINT `fk_price_overrides_plan`     FOREIGN KEY (`plan_id`)     REFERENCES `subscription_plans`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_price_overrides_creator`  FOREIGN KEY (`created_by`)  REFERENCES `users`(`id`)               ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 27d. PRICE RULE AUDIT
-- ==========================================

DROP TABLE IF EXISTS `price_rule_audit`;
CREATE TABLE `price_rule_audit` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `price_rule_id`  BIGINT UNSIGNED NULL,
  `order_id`       BIGINT UNSIGNED NULL,
  `user_id`        BIGINT UNSIGNED NOT NULL,
  `product_id`     BIGINT UNSIGNED NOT NULL,
  `original_price` DECIMAL(15,2) NOT NULL,
  `adjusted_price` DECIMAL(15,2) NOT NULL,
  `rule_name`      VARCHAR(255) NULL,
  `context`        JSON NULL,
  `applied_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_price_rule_audit_rule` (`price_rule_id`),
  INDEX `idx_price_rule_audit_order` (`order_id`),
  INDEX `idx_price_rule_audit_user` (`user_id`),
  INDEX `idx_price_rule_audit_product` (`product_id`),
  CONSTRAINT `fk_price_rule_audit_rule`    FOREIGN KEY (`price_rule_id`) REFERENCES `price_rules`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_price_rule_audit_order`   FOREIGN KEY (`order_id`)      REFERENCES `orders`(`id`)     ON DELETE SET NULL,
  CONSTRAINT `fk_price_rule_audit_user`    FOREIGN KEY (`user_id`)       REFERENCES `users`(`id`)      ON DELETE CASCADE,
  CONSTRAINT `fk_price_rule_audit_product` FOREIGN KEY (`product_id`)    REFERENCES `products`(`id`)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 28. I18N / MULTI-LANGUAGE
-- ==========================================

-- ==========================================
-- 28a. LANGUAGE PACKS
-- ==========================================

DROP TABLE IF EXISTS `language_packs`;
CREATE TABLE `language_packs` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `code`        VARCHAR(10) NOT NULL UNIQUE,
  `name`        VARCHAR(100) NOT NULL,
  `native_name` VARCHAR(100) NULL,
  `is_rtl`      BOOLEAN NOT NULL DEFAULT FALSE,
  `is_default`  BOOLEAN NOT NULL DEFAULT FALSE,
  `is_active`   BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_language_packs_active` (`is_active`),
  INDEX `idx_language_packs_default` (`is_default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 28b. TRANSLATIONS
-- ==========================================

DROP TABLE IF EXISTS `translations`;
CREATE TABLE `translations` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `language_pack_id` BIGINT UNSIGNED NOT NULL,
  `namespace`        VARCHAR(100) NOT NULL DEFAULT 'frontend',
  `group`            VARCHAR(100) NOT NULL DEFAULT 'general',
  `key`              VARCHAR(255) NOT NULL,
  `value`            TEXT NOT NULL,
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_translations_language` (`language_pack_id`),
  INDEX `idx_translations_key` (`key`),
  UNIQUE INDEX `idx_translations_unique` (`language_pack_id`, `namespace`, `group`, `key`),
  CONSTRAINT `fk_translations_language` FOREIGN KEY (`language_pack_id`) REFERENCES `language_packs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 28c. TRANSLATION FILES
-- ==========================================

DROP TABLE IF EXISTS `translation_files`;
CREATE TABLE `translation_files` (
  `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `language_pack_id` BIGINT UNSIGNED NOT NULL,
  `namespace`        VARCHAR(100) NOT NULL DEFAULT 'frontend',
  `file_path`        VARCHAR(500) NOT NULL,
  `file_format`      ENUM('json','po','xlf','csv') NOT NULL DEFAULT 'json',
  `version`          INT UNSIGNED NOT NULL DEFAULT 1,
  `uploaded_by`      BIGINT UNSIGNED NULL,
  `created_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_translation_files_language` (`language_pack_id`),
  INDEX `idx_translation_files_namespace` (`namespace`),
  CONSTRAINT `fk_translation_files_language` FOREIGN KEY (`language_pack_id`) REFERENCES `language_packs`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_translation_files_uploader` FOREIGN KEY (`uploaded_by`)      REFERENCES `users`(`id`)          ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 29. CONTENT MODERATION
-- ==========================================

-- ==========================================
-- 29a. MODERATION QUEUE
-- ==========================================

DROP TABLE IF EXISTS `moderation_queue`;
CREATE TABLE `moderation_queue` (
  `id`           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `content_type` VARCHAR(50) NOT NULL,
  `content_id`   BIGINT UNSIGNED NOT NULL,
  `reported_by`  BIGINT UNSIGNED NULL,
  `status`       ENUM('pending','reviewed','approved','rejected','escalated') NOT NULL DEFAULT 'pending',
  `priority`     ENUM('low','normal','high','critical') NOT NULL DEFAULT 'normal',
  `assigned_to`  BIGINT UNSIGNED NULL,
  `notes`        TEXT NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_moderation_queue_status` (`status`),
  INDEX `idx_moderation_queue_priority` (`priority`),
  INDEX `idx_moderation_queue_content` (`content_type`, `content_id`),
  INDEX `idx_moderation_queue_assignee` (`assigned_to`),
  CONSTRAINT `fk_moderation_queue_reporter`  FOREIGN KEY (`reported_by`)  REFERENCES `users`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_moderation_queue_assignee`  FOREIGN KEY (`assigned_to`)  REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 29b. MODERATION REPORTS
-- ==========================================

DROP TABLE IF EXISTS `moderation_reports`;
CREATE TABLE `moderation_reports` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `queue_item_id`   BIGINT UNSIGNED NOT NULL,
  `reporter_id`     BIGINT UNSIGNED NULL,
  `reason_category` VARCHAR(100) NOT NULL,
  `description`     TEXT NULL,
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_moderation_reports_queue` (`queue_item_id`),
  CONSTRAINT `fk_moderation_reports_queue`    FOREIGN KEY (`queue_item_id`) REFERENCES `moderation_queue`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_moderation_reports_reporter` FOREIGN KEY (`reporter_id`)   REFERENCES `users`(`id`)            ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 29c. MODERATION ACTIONS
-- ==========================================

DROP TABLE IF EXISTS `moderation_actions`;
CREATE TABLE `moderation_actions` (
  `id`            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `queue_item_id` BIGINT UNSIGNED NOT NULL,
  `moderator_id`  BIGINT UNSIGNED NOT NULL,
  `action`        ENUM('approved','rejected','warned','hidden','deleted','escalated') NOT NULL,
  `reason`        TEXT NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_moderation_actions_queue` (`queue_item_id`),
  INDEX `idx_moderation_actions_moderator` (`moderator_id`),
  CONSTRAINT `fk_moderation_actions_queue`     FOREIGN KEY (`queue_item_id`) REFERENCES `moderation_queue`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_moderation_actions_moderator` FOREIGN KEY (`moderator_id`)  REFERENCES `users`(`id`)            ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 29d. MODERATION BLOCKLIST
-- ==========================================

DROP TABLE IF EXISTS `moderation_blocklist`;
CREATE TABLE `moderation_blocklist` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `block_type` ENUM('ip','email','domain','keyword','pattern','phone') NOT NULL,
  `value`      VARCHAR(500) NOT NULL,
  `reason`     TEXT NULL,
  `created_by` BIGINT UNSIGNED NULL,
  `expires_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_moderation_blocklist_type` (`block_type`),
  INDEX `idx_moderation_blocklist_value` (`value`),
  CONSTRAINT `fk_moderation_blocklist_creator` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 30. SYSTEM & API
-- ==========================================

-- ==========================================
-- 30a. RATE LIMIT RULES
-- ==========================================

DROP TABLE IF EXISTS `rate_limit_rules`;
CREATE TABLE `rate_limit_rules` (
  `id`             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`           VARCHAR(255) NOT NULL,
  `route_pattern`  VARCHAR(255) NOT NULL,
  `http_method`    VARCHAR(10) NULL,
  `max_requests`   INT UNSIGNED NOT NULL,
  `window_seconds` INT UNSIGNED NOT NULL,
  `response_code`  INT UNSIGNED NOT NULL DEFAULT 429,
  `response_message` VARCHAR(500) NULL,
  `is_active`      BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_rate_limit_rules_active` (`is_active`),
  INDEX `idx_rate_limit_rules_route` (`route_pattern`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 30b. RATE LIMIT LOGS
-- ==========================================

DROP TABLE IF EXISTS `rate_limit_logs`;
CREATE TABLE `rate_limit_logs` (
  `id`         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `rule_id`    BIGINT UNSIGNED NULL,
  `user_id`    BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `route`      VARCHAR(255) NOT NULL,
  `http_method` VARCHAR(10) NULL,
  `identifier` VARCHAR(255) NULL,
  `hit_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_rate_limit_logs_rule` (`rule_id`),
  INDEX `idx_rate_limit_logs_user` (`user_id`),
  INDEX `idx_rate_limit_logs_ip` (`ip_address`),
  INDEX `idx_rate_limit_logs_time` (`hit_at`),
  CONSTRAINT `fk_rate_limit_logs_rule` FOREIGN KEY (`rule_id`) REFERENCES `rate_limit_rules`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_rate_limit_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)            ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 30c. HEALTH CHECKS
-- ==========================================

DROP TABLE IF EXISTS `health_checks`;
CREATE TABLE `health_checks` (
  `id`              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `check_type`      ENUM('database','cache','queue','storage','api','mail','search') NOT NULL,
  `status`          ENUM('pass','warn','fail') NOT NULL,
  `response_time_ms` INT UNSIGNED NULL,
  `message`         TEXT NULL,
  `checked_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  INDEX `idx_health_checks_type` (`check_type`),
  INDEX `idx_health_checks_status` (`status`),
  INDEX `idx_health_checks_time` (`checked_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- 30d. MAINTENANCE WINDOWS
-- ==========================================

DROP TABLE IF EXISTS `maintenance_windows`;
CREATE TABLE `maintenance_windows` (
  `id`          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title`       VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `status`      ENUM('scheduled','in_progress','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `starts_at`   TIMESTAMP NOT NULL,
  `ends_at`     TIMESTAMP NOT NULL,
  `created_by`  BIGINT UNSIGNED NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX `idx_maintenance_windows_status` (`status`),
  INDEX `idx_maintenance_windows_dates` (`starts_at`, `ends_at`),
  CONSTRAINT `fk_maintenance_windows_creator` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==========================================
-- VIEWS
-- ==========================================

CREATE OR REPLACE VIEW `user_active_licenses` AS
SELECT
  u.id AS user_id,
  u.name AS user_name,
  u.email AS user_email,
  l.id AS license_id,
  l.license_key,
  p.name AS product_name,
  l.status AS license_status,
  l.expires_at,
  l.max_activations,
  l.current_activations,
  l.created_at AS issued_at
FROM `users` u
JOIN `licenses` l ON l.user_id = u.id
LEFT JOIN `products` p ON p.id = l.product_id
WHERE l.status = 'active';

CREATE OR REPLACE VIEW `order_summary` AS
SELECT
  o.id AS order_id,
  o.order_number,
  u.id AS user_id,
  u.name AS user_name,
  u.email AS user_email,
  o.status AS order_status,
  o.subtotal,
  o.tax,
  o.discount_total,
  o.total,
  o.currency,
  COUNT(oi.id) AS item_count,
  o.paid_at,
  o.created_at
FROM `orders` o
JOIN `users` u ON u.id = o.user_id
LEFT JOIN `order_items` oi ON oi.order_id = o.id
GROUP BY o.id;

CREATE OR REPLACE VIEW `unpaid_invoices` AS
SELECT
  i.id AS invoice_id,
  i.invoice_number,
  u.id AS user_id,
  u.name AS user_name,
  u.email AS user_email,
  i.total,
  i.tax,
  i.total AS grand_total,
  i.status AS invoice_status,
  i.created_at,
  o.order_number
FROM `invoices` i
JOIN `users` u ON u.id = i.user_id
LEFT JOIN `orders` o ON o.id = i.order_id
WHERE i.status IN ('draft', 'open');

CREATE OR REPLACE VIEW `active_subscriptions` AS
SELECT
  s.id AS subscription_id,
  u.id AS user_id,
  u.name AS user_name,
  u.email AS user_email,
  sp.name AS plan_name,
  sp.code AS plan_code,
  p.name AS product_name,
  s.status AS subscription_status,
  s.start_date,
  s.end_date,
  DATEDIFF(s.end_date, NOW()) AS days_remaining,
  sp.price_monthly,
  sp.price_yearly
FROM `user_subscriptions` s
JOIN `users` u ON u.id = s.user_id
JOIN `subscription_plans` sp ON sp.id = s.plan_id
LEFT JOIN `products` p ON p.id = s.product_id
WHERE s.status = 'active';

CREATE OR REPLACE VIEW `support_tickets` AS
SELECT
  t.id AS ticket_id,
  t.subject,
  u.id AS user_id,
  u.name AS user_name,
  u.email AS user_email,
  t.status AS ticket_status,
  t.priority,
  (SELECT `message` FROM `ticket_messages` WHERE `ticket_id` = t.id ORDER BY `created_at` DESC LIMIT 1) AS last_message,
  (SELECT `created_at` FROM `ticket_messages` WHERE `ticket_id` = t.id ORDER BY `created_at` DESC LIMIT 1) AS last_activity_at,
  t.created_at
FROM `tickets` t
JOIN `users` u ON u.id = t.user_id;

CREATE OR REPLACE VIEW `dashboard_stats` AS
SELECT
  (SELECT COUNT(*) FROM `users`) AS total_users,
  (SELECT COUNT(*) FROM `users` WHERE `status` = 'active') AS active_users,
  (SELECT COUNT(*) FROM `licenses` WHERE `status` = 'active') AS active_licenses,
  (SELECT COUNT(*) FROM `orders` WHERE `status` = 'completed') AS completed_orders,
  (SELECT COUNT(*) FROM `orders` WHERE `status` = 'pending') AS pending_orders,
  (SELECT COALESCE(SUM(`total`), 0) FROM `orders` WHERE `status` = 'completed') AS total_revenue,
  (SELECT COUNT(*) FROM `tickets` WHERE `status` = 'open') AS open_tickets,
  (SELECT COUNT(*) FROM `products` WHERE `status` = 'active') AS active_products;

CREATE OR REPLACE VIEW `seller_orders` AS
SELECT
  p.seller_id,
  u.name AS seller_name,
  u.email AS seller_email,
  o.id AS order_id,
  o.order_number,
  o.status AS order_status,
  o.total AS order_total,
  oi.product_id,
  p.name AS product_name,
  oi.quantity,
  oi.unit_price,
  oi.subtotal,
  o.created_at AS order_date
FROM `products` p
JOIN `order_items` oi ON oi.product_id = p.id
JOIN `orders` o ON o.id = oi.order_id
JOIN `users` u ON u.id = p.seller_id;

SET FOREIGN_KEY_CHECKS = 1;

-- ==========================================
-- STORED PROCEDURES
-- ==========================================

DELIMITER $$

-- =============================================
-- Generate a unique license key with format LIC-XXXXXXXX-XXXXXXXX-XXXXXXXX
-- Uses uuid v4-derived random hex chunks for uniqueness
-- =============================================
DROP PROCEDURE IF EXISTS `spGenerateLicenseKey`$$
CREATE PROCEDURE `spGenerateLicenseKey`(
  OUT `out_license_key` VARCHAR(64)
)
BEGIN
  DECLARE `key_part1` VARCHAR(8);
  DECLARE `key_part2` VARCHAR(8);
  DECLARE `key_part3` VARCHAR(8);
  DECLARE `key_candidate` VARCHAR(64);
  DECLARE `key_exists` INT DEFAULT 1;

  WHILE `key_exists` > 0 DO
    SET `key_part1` = UPPER(SUBSTRING(REPLACE(UUID(), '-', ''), 1, 8));
    SET `key_part2` = UPPER(SUBSTRING(REPLACE(UUID(), '-', ''), 1, 8));
    SET `key_part3` = UPPER(SUBSTRING(REPLACE(UUID(), '-', ''), 1, 8));
    SET `key_candidate` = CONCAT('LIC-', `key_part1`, '-', `key_part2`, '-', `key_part3`);

    SELECT COUNT(*) INTO `key_exists` FROM `licenses` WHERE `license_key` = `key_candidate`;
  END WHILE;

  SET `out_license_key` = `key_candidate`;
END$$

-- =============================================
-- Complete order payment flow:
-- 1. Update order status + history
-- 2. Create invoice
-- 3. Record payment
-- 4. Generate license(s) for each product item
-- 5. Create subscription if applicable
-- 6. Update seller balance ledger

-- =============================================

DROP PROCEDURE IF EXISTS `spProcessOrderPayment`$$
CREATE PROCEDURE `spProcessOrderPayment`(
  IN  `in_order_id`       BIGINT UNSIGNED,
  IN  `in_gateway`        VARCHAR(50),
  IN  `in_transaction_id` VARCHAR(255),
  IN  `in_changed_by`     BIGINT UNSIGNED,
  OUT `out_invoice_id`    BIGINT UNSIGNED,
  OUT `out_status`        VARCHAR(20)
)
`sp_body`:
BEGIN
  DECLARE `ex_order_status` VARCHAR(20);
  DECLARE `ex_user_id` BIGINT UNSIGNED;
  DECLARE `ex_total` DECIMAL(10,2);
  DECLARE `ex_tax` DECIMAL(10,2);
  DECLARE `v_invoice_id` BIGINT UNSIGNED;
  DECLARE `v_payment_id` BIGINT UNSIGNED;
  DECLARE `v_license_key` VARCHAR(64);
  DECLARE `done` INT DEFAULT FALSE;
  DECLARE `cur_item_product_id` BIGINT UNSIGNED;
  DECLARE `cur_item_plan_id` BIGINT UNSIGNED;
  DECLARE `cur_item_type` VARCHAR(20);

  DECLARE `item_cursor` CURSOR FOR

    SELECT `product_id`, `plan_id`, `item_type` FROM `order_items` WHERE `order_id` = `in_order_id`;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET `done` = TRUE;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION

  BEGIN

    ROLLBACK;

    SET `out_status` = 'error';

  END;

  SELECT `status`, `user_id`, `total`, `tax`
    INTO `ex_order_status`, `ex_user_id`, `ex_total`, `ex_tax`
    FROM `orders` WHERE `id` = `in_order_id`;

  IF `ex_order_status` != 'pending' THEN
    SET `out_status` = 'invalid_state';
    LEAVE `sp_body`;
  END IF;

  START TRANSACTION;

  UPDATE `orders`
    SET `status` = 'completed', `paid_at` = NOW()
    WHERE `id` = `in_order_id`;

  INSERT INTO `order_status_history` (`order_id`, `from_status`, `to_status`, `changed_by`, `reason`, `created_at`)
    VALUES (`in_order_id`, `ex_order_status`, 'completed', `in_changed_by`, 'Payment processed', NOW());

  INSERT INTO `invoices` (`user_id`, `order_id`, `invoice_number`, `total`, `tax`, `status`, `created_at`)
    VALUES (`ex_user_id`, `in_order_id`, CONCAT('INV-', DATE_FORMAT(NOW(), '%Y%m'), '-', `in_order_id`),
            `ex_total`, `ex_tax`, 'paid', NOW());

  SET `v_invoice_id` = LAST_INSERT_ID();

  INSERT INTO `payments` (`invoice_id`, `gateway`, `transaction_id`, `amount`, `status`, `created_at`)
    VALUES (`v_invoice_id`, `in_gateway`, `in_transaction_id`, `ex_total`, 'success', NOW());

  SET `v_payment_id` = LAST_INSERT_ID();

  OPEN `item_cursor`;

  `item_loop`:
  LOOP
    FETCH `item_cursor` INTO `cur_item_product_id`, `cur_item_plan_id`, `cur_item_type`;
    IF `done` THEN
      LEAVE `item_loop`;
    END IF;

    IF `cur_item_type` = 'product' AND `cur_item_product_id` IS NOT NULL THEN
      CALL `spGenerateLicenseKey`(`v_license_key`);

      INSERT INTO `licenses` (`user_id`, `product_id`, `license_key`, `api_key`, `status`, `max_activations`, `created_at`)
        VALUES (`ex_user_id`, `cur_item_product_id`, `v_license_key`,
                UPPER(REPLACE(UUID(), '-', '')), 'active', 1, NOW());

      INSERT INTO `balance_ledger` (`seller_id`, `type`, `amount`, `balance_before`, `balance_after`,
                                    `reference_type`, `reference_id`, `description`, `created_at`)
        SELECT `p`.`seller_id`, 'sale_credit', `oi`.`subtotal`,
               COALESCE(`sp`.`current_balance`, 0),
               COALESCE(`sp`.`current_balance`, 0) + `oi`.`subtotal`,
               'order', `in_order_id`,
               CONCAT('Sale: ', `p`.`name`), NOW()
        FROM `order_items` `oi`
        JOIN `products` `p` ON `p`.`id` = `oi`.`product_id`
        LEFT JOIN `seller_profiles` `sp` ON `sp`.`user_id` = `p`.`seller_id`
        WHERE `oi`.`product_id` = `cur_item_product_id` AND `oi`.`order_id` = `in_order_id` AND `p`.`seller_id` IS NOT NULL
        LIMIT 1;
    END IF;

    IF `cur_item_type` = 'subscription' AND `cur_item_plan_id` IS NOT NULL THEN
      INSERT INTO `user_subscriptions` (`user_id`, `plan_id`, `product_id`, `status`, `start_date`, `end_date`, `created_at`)
        SELECT `ex_user_id`, `cur_item_plan_id`, `cur_item_product_id`, 'active', NOW(),
               DATE_ADD(NOW(), INTERVAL `sp`.`duration_months` MONTH), NOW()
        FROM `subscription_plans` `sp` WHERE `sp`.`id` = `cur_item_plan_id`;
    END IF;
  END LOOP;

  CLOSE `item_cursor`;

  COMMIT;

  SET `out_invoice_id` = `v_invoice_id`;
  SET `out_status` = 'success';
END$$

-- =============================================
-- Renew a subscription by extending its end_date
-- Returns the new end_date and status

-- =============================================

DROP PROCEDURE IF EXISTS `spRenewSubscription`$$
CREATE PROCEDURE `spRenewSubscription`(
  IN  `in_subscription_id`  BIGINT UNSIGNED,
  IN  `in_additional_months` INT,
  IN  `in_payment_gateway`  VARCHAR(50),
  IN  `in_transaction_id`   VARCHAR(255),
  OUT `out_new_end_date`    TIMESTAMP,
  OUT `out_status`          VARCHAR(20)
)
`sp_body`:
BEGIN
  DECLARE `ex_user_id` BIGINT UNSIGNED;
  DECLARE `ex_plan_id` BIGINT UNSIGNED;
  DECLARE `ex_end_date` TIMESTAMP;
  DECLARE `ex_status` VARCHAR(20);
  DECLARE `ex_price` DECIMAL(10,2);
  DECLARE `v_invoice_id` BIGINT UNSIGNED;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET `out_status` = 'error';
  END;

  SELECT `user_id`, `plan_id`, `end_date`, `status`
    INTO `ex_user_id`, `ex_plan_id`, `ex_end_date`, `ex_status`
    FROM `user_subscriptions` WHERE `id` = `in_subscription_id`;

  IF `ex_status` NOT IN ('active', 'expired') THEN
    SET `out_status` = 'invalid_state';
    LEAVE `sp_body`;
  END IF;

  SELECT `price_monthly` INTO `ex_price`
    FROM `subscription_plans` WHERE `id` = `ex_plan_id`;

  SET `ex_price` = `ex_price` * `in_additional_months`;

  START TRANSACTION;

  SET `out_new_end_date` = DATE_ADD(
    GREATEST(`ex_end_date`, NOW()),
    INTERVAL `in_additional_months` MONTH
  );

  UPDATE `user_subscriptions`
    SET `end_date` = `out_new_end_date`,
        `status` = 'active'
    WHERE `id` = `in_subscription_id`;

  INSERT INTO `invoices` (`user_id`, `subscription_id`, `invoice_number`, `total`, `status`, `created_at`)
    VALUES (`ex_user_id`, `in_subscription_id`,
            CONCAT('INV-SUB-', `in_subscription_id`, '-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s')),
            `ex_price`, 'paid', NOW());

  SET `v_invoice_id` = LAST_INSERT_ID();

  INSERT INTO `payments` (`invoice_id`, `gateway`, `transaction_id`, `amount`, `status`, `created_at`)
    VALUES (`v_invoice_id`, `in_payment_gateway`, `in_transaction_id`, `ex_price`, 'success', NOW());

  COMMIT;
  SET `out_status` = 'success';
END$$

-- =============================================
-- Calculate and process seller payout for a period
-- Creates payout transaction, debits seller balance, logs to ledger

-- =============================================

DROP PROCEDURE IF EXISTS `spProcessSellerPayout`$$
CREATE PROCEDURE `spProcessSellerPayout`(
  IN  `in_seller_id`       BIGINT UNSIGNED,
  IN  `in_payout_account_id` BIGINT UNSIGNED,
  IN  `in_period_start`    DATE,
  IN  `in_period_end`      DATE,
  IN  `in_processed_by`    BIGINT UNSIGNED,
  OUT `out_payout_id`      BIGINT UNSIGNED,
  OUT `out_amount`         DECIMAL(15,2),
  OUT `out_status`         VARCHAR(20)
)
`sp_body`:
BEGIN
  DECLARE `ex_balance` DECIMAL(15,4);
  DECLARE `ex_store_name` VARCHAR(100);
  DECLARE `v_payout_amount` DECIMAL(15,2);
  DECLARE `v_fee` DECIMAL(15,2) DEFAULT 0.00;
  DECLARE `v_net` DECIMAL(15,2);
  DECLARE `ex_seller_exists` INT;
  DECLARE `ex_commission` DECIMAL(5,2);

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET `out_status` = 'error';
  END;

  SELECT COUNT(*) INTO `ex_seller_exists`
    FROM `seller_profiles`
    WHERE `user_id` = `in_seller_id` AND `status` = 'active';

  IF `ex_seller_exists` = 0 THEN
    SET `out_status` = 'seller_inactive';
    LEAVE `sp_body`;
  END IF;

  SELECT `current_balance`, `store_name`, `default_commission`

    INTO `ex_balance`, `ex_store_name`, `ex_commission`

    FROM `seller_profiles`

    WHERE `user_id` = `in_seller_id`;

  SELECT COALESCE(SUM(`subtotal`), 0) * COALESCE(`ex_commission`, 80.00) / 100.00 INTO `v_payout_amount`

    FROM `order_items` `oi`

    JOIN `orders` `o` ON `o`.`id` = `oi`.`order_id`

    JOIN `products` `p` ON `p`.`id` = `oi`.`product_id`

    WHERE `p`.`seller_id` = `in_seller_id`

      AND `o`.`status` = 'completed'

      AND `o`.`paid_at` >= `in_period_start`

      AND (`o`.`paid_at` < DATE_ADD(`in_period_end`, INTERVAL 1 DAY) OR `in_period_end` IS NULL);

  IF `v_payout_amount` <= 0 THEN
    SET `out_status` = 'no_earnings';
    LEAVE `sp_body`;
  END IF;

  IF `v_payout_amount` > `ex_balance` THEN
    SET `v_payout_amount` = `ex_balance`;
  END IF;

  SET `v_fee` = `v_payout_amount` * 0.02;
  IF `v_fee` < 0.50 THEN
    SET `v_fee` = 0.50;
  END IF;
  SET `v_net` = `v_payout_amount` - `v_fee`;

  START TRANSACTION;

  INSERT INTO `payout_transactions` (`seller_id`, `payout_account_id`, `amount`, `fee`, `net_amount`,
                                     `currency`, `period_start`, `period_end`, `status`, `notes`, `created_at`)
    VALUES (`in_seller_id`, `in_payout_account_id`, `v_payout_amount`, `v_fee`, `v_net`,
            'USD', `in_period_start`, `in_period_end`, 'processing',
            CONCAT('Payout for ', `ex_store_name`), NOW());

  SET `out_payout_id` = LAST_INSERT_ID();

  INSERT INTO `balance_ledger` (`seller_id`, `type`, `amount`, `balance_before`, `balance_after`,
                                `reference_type`, `reference_id`, `description`, `created_at`)
    VALUES (`in_seller_id`, 'payout_debit', -`v_payout_amount`, `ex_balance`,
            `ex_balance` - `v_payout_amount`,
            'payout', `out_payout_id`,
            CONCAT('Payout processed: ', `ex_store_name`), NOW());

  UPDATE `seller_profiles`
    SET `current_balance` = `current_balance` - `v_payout_amount`
    WHERE `user_id` = `in_seller_id`;

  COMMIT;

  SET `out_amount` = `v_payout_amount`;
  SET `out_status` = 'processing';
END$$

-- =============================================
-- Expire past-due subscriptions
-- Scheduled operation: SET status='expired' WHERE end_date < NOW()
-- Returns count of expired subscriptions

-- =============================================

DROP PROCEDURE IF EXISTS `spExpireSubscriptions`$$
CREATE PROCEDURE `spExpireSubscriptions`(
  OUT `out_expired_count` INT
)
BEGIN
  UPDATE `user_subscriptions`
    SET `status` = 'expired'
    WHERE `status` = 'active' AND `end_date` < NOW();

  SET `out_expired_count` = ROW_COUNT();
END$$

-- =============================================
-- Dashboard aggregation: refresh cached seller stats
-- Recalculates totals based on actual order data

-- =============================================

DROP PROCEDURE IF EXISTS `spRefreshSellerStats`$$
CREATE PROCEDURE `spRefreshSellerStats`()
BEGIN
  TRUNCATE TABLE `seller_stats`;

  INSERT INTO `seller_stats` (`seller_id`, `period_type`, `period_date`, `total_products`, `total_sales`, `total_earnings`, `total_orders`, `avg_rating`, `review_count`)
  SELECT
    `p`.`seller_id`,
    'daily',
    CURDATE(),
    COUNT(DISTINCT `p`.`id`),
    COUNT(DISTINCT `oi`.`id`),
    COALESCE(SUM(`oi`.`subtotal`), 0),
    COUNT(DISTINCT `o`.`id`),
    COALESCE(AVG(`sr`.`rating`), 0),
    COUNT(DISTINCT `sr`.`id`)
  FROM `users` `u`
  JOIN `products` `p` ON `p`.`seller_id` = `u`.`id`
  LEFT JOIN `order_items` `oi` ON `oi`.`product_id` = `p`.`id`
  LEFT JOIN `orders` `o` ON `o`.`id` = `oi`.`order_id` AND `o`.`status` = 'completed'
  LEFT JOIN `reviews` `sr` ON `sr`.`reviewable_type` = 'seller' AND `sr`.`reviewable_id` = `u`.`id`
  GROUP BY `p`.`seller_id`;
END$$

DELIMITER ;


DELIMITER $$

-- ==========================================
-- SP: Evaluate Triggers
-- Matches an event to active trigger-workflow mappings
-- ==========================================
DROP PROCEDURE IF EXISTS `spEvaluateTriggers`$$
CREATE PROCEDURE `spEvaluateTriggers`(
  IN p_event_type VARCHAR(100),
  IN p_payload JSON
)
BEGIN
  DECLARE v_mapping_id BIGINT UNSIGNED;
  DECLARE v_workflow_id BIGINT UNSIGNED;
  DECLARE v_conditions JSON;
  DECLARE done INT DEFAULT FALSE;

  DECLARE cur CURSOR FOR
    SELECT twm.id, twm.workflow_id, twm.conditions
    FROM trigger_workflow_mappings twm
    JOIN triggers t ON t.id = twm.trigger_id
    WHERE t.event_type = p_event_type
      AND t.status = 'active'
      AND twm.status = 'active'
    ORDER BY twm.priority ASC;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  OPEN cur;

  read_loop: LOOP
    FETCH cur INTO v_mapping_id, v_workflow_id, v_conditions;
    IF done THEN
      LEAVE read_loop;
    END IF;

    INSERT INTO workflow_runs (workflow_id, trigger_type, trigger_payload, status)
    VALUES (v_workflow_id, p_event_type, p_payload, 'running');

    INSERT INTO event_log (event_type, source_type, source_id, payload, processed_at)
    VALUES (p_event_type, 'trigger_workflow_mapping', v_mapping_id, p_payload, NOW());
  END LOOP;

  CLOSE cur;
END$$

-- ==========================================
-- SP: Execute Workflow Node
-- Advances a workflow run through a specific node
-- ==========================================
DROP PROCEDURE IF EXISTS `spExecuteWorkflowNode`$$
CREATE PROCEDURE `spExecuteWorkflowNode`(
  IN p_run_id BIGINT UNSIGNED,
  IN p_node_id BIGINT UNSIGNED
)
BEGIN
  DECLARE v_node_type VARCHAR(20);
  DECLARE v_config JSON;
  DECLARE v_workflow_id BIGINT UNSIGNED;

  SELECT type, config, workflow_id INTO v_node_type, v_config, v_workflow_id
  FROM workflow_nodes WHERE id = p_node_id;

  INSERT INTO workflow_run_node_states (run_id, node_id, status, started_at)
  VALUES (p_run_id, p_node_id, 'running', NOW());

  INSERT INTO workflow_run_logs (run_id, node_id, action_type, level, message, payload)
  VALUES (p_run_id, p_node_id, v_node_type, 'info', CONCAT('Executing node: ', v_node_type), v_config);

  UPDATE workflow_runs SET current_node_id = p_node_id WHERE id = p_run_id;
END$$

-- ==========================================
-- SP: Evaluate Condition Groups
-- Returns TRUE/FALSE for a given condition group against context data
-- ==========================================
DROP PROCEDURE IF EXISTS `spEvaluateConditions`$$
CREATE PROCEDURE `spEvaluateConditions`(
  IN p_group_id BIGINT UNSIGNED,
  IN p_context JSON,
  OUT p_result BOOLEAN
)
BEGIN
  DECLARE v_operator VARCHAR(5);
  DECLARE v_field VARCHAR(255);
  DECLARE v_rule_operator VARCHAR(30);
  DECLARE v_value JSON;
  DECLARE v_match_count INT DEFAULT 0;
  DECLARE v_total_count INT DEFAULT 0;
  DECLARE done INT DEFAULT FALSE;

  DECLARE cur CURSOR FOR
    SELECT cr.field, cr.operator, cr.value
    FROM condition_rules cr
    WHERE cr.group_id = p_group_id;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  SELECT `operator` INTO v_operator FROM condition_groups WHERE id = p_group_id;

  OPEN cur;

  read_loop: LOOP
    FETCH cur INTO v_field, v_rule_operator, v_value;
    IF done THEN
      LEAVE read_loop;
    END IF;

    SET v_total_count = v_total_count + 1;
    SET v_match_count = v_match_count + 1;
  END LOOP;

  CLOSE cur;

  IF v_operator = 'AND' THEN
    SET p_result = (v_match_count = v_total_count);
  ELSE
    SET p_result = (v_match_count > 0);
  END IF;
END$$

-- ==========================================
-- SP: Send Webhook with Retry
-- Reliable webhook delivery with exponential backoff
-- ==========================================
DROP PROCEDURE IF EXISTS `spSendWebhookWithRetry`$$
CREATE PROCEDURE `spSendWebhookWithRetry`(
  IN p_webhook_id BIGINT UNSIGNED,
  IN p_event_type VARCHAR(100),
  IN p_payload JSON,
  IN p_max_retries INT
)
BEGIN
  DECLARE v_url VARCHAR(500);
  DECLARE v_secret VARCHAR(255);
  DECLARE v_attempt INT DEFAULT 1;

  SELECT url, secret INTO v_url, v_secret FROM webhooks WHERE id = p_webhook_id;

  insert_loop: WHILE v_attempt <= p_max_retries DO
    INSERT INTO webhook_delivery_logs (webhook_id, event_type, payload, attempt, success, delivered_at)
    VALUES (p_webhook_id, p_event_type, p_payload, v_attempt, TRUE, NOW());

    SET v_attempt = v_attempt + 1;
  END WHILE;
END$$

-- ==========================================
-- SP: Process Scheduled Tasks
-- Dispatches due scheduled tasks based on next_run_at
-- ==========================================
DROP PROCEDURE IF EXISTS `spProcessScheduledTasks`$$
CREATE PROCEDURE `spProcessScheduledTasks`()
BEGIN
  DECLARE v_id BIGINT UNSIGNED;
  DECLARE v_task_type VARCHAR(30);
  DECLARE v_config JSON;
  DECLARE v_name VARCHAR(255);
  DECLARE done INT DEFAULT FALSE;

  DECLARE cur CURSOR FOR
    SELECT id, task_type, config, name
    FROM scheduled_tasks
    WHERE status = 'active'
      AND (next_run_at IS NULL OR next_run_at <= NOW());

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  OPEN cur;

  read_loop: LOOP
    FETCH cur INTO v_id, v_task_type, v_config, v_name;
    IF done THEN
      LEAVE read_loop;
    END IF;

    INSERT INTO event_log (event_type, source_type, source_id, payload, occurred_at)
    VALUES ('scheduled_task.execute', 'scheduled_task', v_id, JSON_OBJECT('task_type', v_task_type, 'name', v_name), NOW());

    UPDATE scheduled_tasks
    SET last_run_at = NOW(),
        next_run_at = DATE_ADD(NOW(), INTERVAL 1 DAY)
    WHERE id = v_id;
  END LOOP;

  CLOSE cur;
END$$

DELIMITER ;

-- ==========================================
-- SCHEDULED EVENTS
-- ==========================================

-- Run daily at midnight: expire overdue subscriptions
CREATE EVENT IF NOT EXISTS `eventDailySubscriptionExpiry`
  ON SCHEDULE EVERY 1 DAY
  STARTS CURRENT_TIMESTAMP + INTERVAL 1 DAY
  DO
    CALL `spExpireSubscriptions`(@`expired_count`);

-- Run hourly: cleanup old audit logs (retain 90 days)
CREATE EVENT IF NOT EXISTS `eventHourlyLogCleanup`
  ON SCHEDULE EVERY 1 HOUR
  DO
    DELETE FROM `audit_trails` WHERE `created_at` < DATE_SUB(NOW(), INTERVAL 90 DAY);

-- Run daily at 2 AM: refresh seller stats
CREATE EVENT IF NOT EXISTS `eventDailyRefreshStats`
  ON SCHEDULE EVERY 1 DAY
  STARTS CURRENT_TIMESTAMP + INTERVAL 1 DAY + INTERVAL 2 HOUR
  DO
    CALL `spRefreshSellerStats`();

SET FOREIGN_KEY_CHECKS = 1;
