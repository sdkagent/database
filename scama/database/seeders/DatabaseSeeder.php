<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Core - Users & Auth
            UsersAuthSeeder::class,
            AuthLogsSeeder::class,

            // Organizations
            // (organizations data is in UsersAuthSeeder)

            // Seller
            SellerTablesSeeder::class,

            // Products
            ProductsTablesSeeder::class,

            // Subscriptions & Billing
            BillingTablesSeeder::class,

            // Orders & Commerce
            OrdersCommerceSeeder::class,
            CommerceEnhancementsSeeder::class,

            // Licensing
            LicensingTablesSeeder::class,
            VerificationSecuritySeeder::class,

            // Support
            SupportTablesSeeder::class,

            // CMS / Content
            CmsCoreSeeder::class,
            CmsPagesSeeder::class,
            CmsSeoSeeder::class,
            CmsSettingsSeeder::class,
            CmsVideoSeeder::class,
            ContentBlocksSeeder::class,
            ContentTablesSeeder::class,

            // Notifications
            NotificationsSeeder::class,

            // System & API
            SystemApiSeeder::class,
            SystemSettingsSeeder::class,

            // LLM
            LlmTablesSeeder::class,

            // I18n
            I18nSeeder::class,

            // Media Library
            MediaLibrarySeeder::class,

            // Bot Configs
            BotConfigsSeeder::class,

            // Workflows
            WorkflowCoreSeeder::class,
            WorkflowActionsSeeder::class,
            WorkflowTriggersSeeder::class,

            // Themes
            ThemesCoreSeeder::class,
            ThemesLogsSeeder::class,

            // Logging & Monitoring
            // (data in SystemApiSeeder and SecurityComplianceSeeder)

            // Commerce Enhancements
            // (already covered above)

            // Accounting / Financial
            AccountingCoreSeeder::class,
            JournalTablesSeeder::class,
            BudgetTablesSeeder::class,

            // Inventory
            InventoryCoreSeeder::class,
            InventoryOpsSeeder::class,

            // Procurement
            ProcurementSupplierSeeder::class,
            ProcurementPurchaseSeeder::class,

            // Production
            ProductionCoreSeeder::class,
            ProductionOpsSeeder::class,

            // HR
            HrCoreSeeder::class,
            PayrollTablesSeeder::class,

            // Reporting
            ReportingTablesSeeder::class,

            // Tax Engine
            TaxEngineSeeder::class,

            // Dynamic Pricing
            DynamicPricingSeeder::class,

            // Security & Compliance
            SecurityComplianceSeeder::class,

            // Moderation
            ModerationSeeder::class,

            // Privacy / GDPR
            PrivacyGdprSeeder::class,

            // Form Submissions
            FormSubmissionsSeeder::class,

            // SMS
            SmsTablesSeeder::class,

            // Deprecated / Legacy data
            // (none)
        ]);
    }
}
