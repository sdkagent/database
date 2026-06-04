# Database ERD — LicensePro Platform







> 259 tables organized into 40 domains, ~380 relationships. Generated from the optimized schema.







---







## Mermaid.js ER Diagram







```mermaid



erDiagram



    %% ==========================================



    %% 1. CORE - USERS & AUTH



    %% ==========================================



    users {



        bigint id PK



        enum role "admin|user|seller|support"



        varchar name



        varchar email UK



        timestamp email_verified_at



        varchar password



        varchar phone



        enum status "active|suspended|pending|banned"



        json ip_whitelist



        varchar telegram_chat_id



        json settings



        varchar avatar_url



        timestamp last_login_at



        varchar locale



        varchar timezone



        timestamp created_at



        timestamp updated_at



    }



    password_resets {



        bigint id PK



        bigint user_id FK



        varchar token



        timestamp expires_at



        timestamp used_at



        timestamp created_at



    }



    roles {

        bigint id PK

        varchar name

        varchar slug UK

        text description

        bool is_system

        bigint organization_id FK

        timestamp created_at

        timestamp updated_at

    }



    permissions {

        bigint id PK

        varchar name

        varchar slug UK

        text description

        varchar group

        bigint organization_id FK

        timestamp created_at

    }



    role_permissions {



        bigint role_id PK,FK



        bigint permission_id PK,FK



    }



    user_roles {

        bigint user_id PK,FK

        bigint role_id PK,FK

        bigint organization_id PK,FK

        timestamp assigned_at

    }



    organizations {



        bigint id PK



        varchar name



        varchar slug UK



        varchar logo_url



        varchar website



        enum status "active|suspended"



        timestamp created_at



        timestamp updated_at



    }



    organization_members {



        bigint id PK



        bigint organization_id FK



        bigint user_id FK



        enum role "owner|admin|member|viewer"



        timestamp joined_at



    }



    seller_profiles {



        bigint user_id PK,FK



        varchar store_name



        text store_description



        varchar store_logo_url



        varchar store_cover_url



        enum status "pending|active|suspended|banned"



        decimal current_balance



        decimal default_commission



        timestamp verified_at



        timestamp created_at



        timestamp updated_at



    }



    payout_accounts {



        bigint id PK



        bigint seller_id FK



        enum method "bank|paypal|stripe|crypto|bkash"



        varchar account_label



        json account_details



        bool is_default



        enum status "active|inactive"



        timestamp created_at



        timestamp updated_at



    }



    payout_transactions {



        bigint id PK



        bigint seller_id FK



        bigint payout_account_id FK



        decimal amount



        decimal fee



        decimal net_amount



        varchar currency



        date period_start



        date period_end



        enum status "pending|processing|completed|failed"



        varchar reference



        text notes



        timestamp processed_at



        timestamp created_at



    }



    balance_ledger {



        bigint id PK



        bigint seller_id FK



        enum type "sale_credit|commission_earned|payout_debit|adjustment|fee"



        decimal amount



        decimal balance_before



        decimal balance_after



        varchar reference_type



        bigint reference_id



        text description



        timestamp created_at



    }



    seller_verification {



        bigint id PK



        bigint seller_id FK



        enum document_type "id_card|passport|business_license|tax_id"



        varchar document_url



        enum status "pending|approved|rejected"



        bigint verified_by FK



        timestamp verified_at



        text rejection_reason



        timestamp created_at



        timestamp updated_at



    }







    seller_stats {



        bigint id PK



        bigint seller_id FK



        enum period_type "daily|weekly|monthly"



        date period_date



        decimal total_sales



        decimal total_earnings



        int total_orders



        int total_products



        decimal avg_rating



        int review_count



        timestamp created_at



    }



  auth_logs {



        bigint id PK



        bigint user_id FK



        enum type



        varchar ip_address



        varchar device_fingerprint



        enum status "success|failed|suspicious"



        json details



        timestamp created_at



    }







    %% ==========================================



    %% 2. PRODUCTS & SUBSCRIPTIONS



    %% ==========================================



    products {



        bigint id PK



        bigint seller_id FK



        varchar name



        varchar slug UK



        text description



        enum type "script|software|plugin|saas|desktop"



        decimal base_price



        varchar sku



        int stock



        int download_limit



        int total_sales



        varchar version



        varchar download_url



        enum status "draft|active|archived"



        varchar demo_url



        varchar docs_url



        timestamp created_at



        timestamp updated_at



    }



    product_hardware_requirements {



        bigint id PK



        bigint product_id FK



        varchar os_name



        varchar os_version_min



        int cpu_cores_min



        int memory_mb_min



        int disk_mb_min



        text additional_notes



    }



    subscription_plans {



        bigint id PK



        varchar name



        varchar code UK



        int duration_months



        int max_activations



        decimal price_monthly



        decimal price_yearly



        json features



        bool status



        timestamp created_at



        timestamp updated_at



    }



    user_subscriptions {



        bigint id PK



        bigint user_id FK



        bigint plan_id FK



        bigint product_id FK



        enum status "active|cancelled|expired|past_due"



        timestamp start_date



        timestamp end_date



        timestamp trial_ends_at



        varchar stripe_subscription_id



        timestamp created_at



        timestamp updated_at



    }



    wishlists {



        bigint id PK



        bigint user_id FK



        bigint product_id FK



        timestamp created_at



    }



    product_categories {



        bigint id PK



        bigint parent_id FK



        varchar name



        varchar slug UK



        text description



        varchar image_url



        int sort_order



        timestamp created_at



        timestamp updated_at



    }



    product_category_items {



        bigint product_id PK,FK



        bigint category_id PK,FK



    }



    product_discounts {



        bigint id PK



        bigint product_id FK



        varchar name



        enum type "percentage|fixed"



        decimal value



        int max_uses



        int used_count



        timestamp starts_at



        timestamp ends_at



        timestamp created_at



        timestamp updated_at



    }







    %% ==========================================



    %% 3. BILLING



    %% ==========================================



    invoices {



        bigint id PK



        bigint user_id FK



        bigint order_id FK



        bigint subscription_id FK



        varchar invoice_number UK



        decimal total



        decimal tax



        enum status "draft|open|paid|void|refunded"



        timestamp created_at



        timestamp updated_at



    }



    payments {



        bigint id PK



        bigint invoice_id FK



        varchar gateway



        varchar transaction_id



        decimal amount



        enum status "pending|success|failed"



        json meta



        timestamp created_at



    }



    tax_rates {



        bigint id PK



        varchar name



        decimal rate



        enum type "percentage|fixed"



        varchar country



        varchar region



        bool is_default



        bool active



        timestamp created_at



        timestamp updated_at



    }







    %% ==========================================



    %% 4. ORDERS & COMMERCE



    %% ==========================================



    coupons {



        bigint id PK



        varchar code UK



        enum type "percentage|fixed_amount"



        decimal value



        decimal min_order_amount



        int max_uses



        int used_count



        timestamp starts_at



        timestamp expires_at



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    carts {



        bigint id PK



        bigint user_id FK



        bigint coupon_id FK



        decimal subtotal



        decimal tax



        decimal total



        timestamp expires_at



        timestamp created_at



        timestamp updated_at



    }



    cart_items {



        bigint id PK



        bigint cart_id FK



        bigint product_id FK



        bigint plan_id FK



        int quantity



        decimal unit_price



        decimal subtotal



        timestamp created_at



    }



    orders {



        bigint id PK



        bigint user_id FK



        varchar order_number UK



        enum status "pending|confirmed|processing|completed|cancelled|refunded"



        decimal subtotal



        decimal tax



        decimal discount_total



        decimal total



        varchar currency



        text notes



        bigint billing_address_id FK



        bigint shipping_address_id FK



        bigint coupon_id FK



        bigint api_client_id FK



        text customer_notes



        varchar ip_address



        text user_agent



        timestamp paid_at



        timestamp cancelled_at



        timestamp created_at



        timestamp updated_at



    }



    order_items {



        bigint id PK



        bigint order_id FK



        bigint product_id FK



        bigint plan_id FK



        enum item_type "product|subscription"



        varchar name



        int quantity



        decimal unit_price



        decimal subtotal



        timestamp created_at



    }



    order_item_metadata {



        bigint id PK



        bigint order_item_id FK



        bigint license_id FK



        varchar meta_key



        text meta_value



        timestamp created_at



        timestamp updated_at



    }



    order_status_history {



        bigint id PK



        bigint order_id FK



        enum from_status



        enum to_status



        bigint changed_by FK



        bigint api_client_id FK



        text reason



        timestamp created_at



    }



    refunds {



        bigint id PK



        bigint order_id FK



        bigint payment_id FK



        decimal amount



        text reason



        enum status "pending|approved|rejected|completed"



        bigint processed_by FK



        timestamp created_at



        timestamp updated_at



    }



    shipments {



        bigint id PK



        bigint order_id FK



        varchar tracking_number



        varchar carrier



        enum status "pending|shipped|delivered|returned"



        timestamp shipped_at



        timestamp delivered_at



        json shipping_data



        timestamp created_at



        timestamp updated_at



    }



    file_downloads {



        bigint id PK



        bigint order_item_id FK



        bigint user_id FK



        varchar ip_address



        int download_count



        timestamp last_downloaded



        timestamp expires_at



        timestamp created_at



    }



    affiliates {



        bigint id PK



        bigint user_id FK



        varchar code UK



        decimal commission_rate



        decimal total_earned



        decimal total_paid



        enum status "active|suspended"



        timestamp created_at



        timestamp updated_at



    }



    referrals {



        bigint id PK



        bigint affiliate_id FK



        bigint referred_id FK



        bigint order_id FK



        decimal commission



        enum status "pending|paid|cancelled"



        timestamp created_at



    }







    %% ==========================================



    %% 5. LICENSING



    %% ==========================================



    api_clients {



        bigint id PK



        bigint user_id FK



        varchar name



        varchar api_key UK



        varchar api_secret



        enum status "active|suspended|revoked"



        int rate_limit



        timestamp last_used_at



        timestamp created_at



    }



    licenses {



        bigint id PK



        bigint user_id FK



        bigint product_id FK



        bigint api_client_id FK



        bigint subscription_id FK



        varchar license_key UK



        varchar api_key



        enum status "active|suspended|expired|revoked"



        timestamp expires_at



        int max_activations



        int current_activations



        timestamp last_activity_at



        timestamp created_at



        timestamp updated_at



    }



    license_activations {



        bigint id PK



        bigint license_id FK



        varchar domain



        varchar hosting_ip



        enum status "active|inactive|suspicious|banned"



        timestamp last_verified_at



        json meta



        timestamp created_at



        timestamp updated_at



    }



    hardware_activations {



        bigint id PK



        bigint license_id FK



        varchar machine_id



        varchar cpu_id



        varchar motherboard_serial



        varchar bios_serial



        varchar disk_serial



        varchar mac_address



        varchar os_name



        varchar os_version



        varchar os_architecture



        varchar cpu_name



        int cpu_cores



        int total_memory



        varchar system_manufacturer



        varchar system_model



        varchar local_ip



        varchar public_ip



        enum status



        int activation_limit



        timestamp activated_at



        timestamp last_ping_at



        varchar required_os_min



        int required_memory_mb



        int required_disk_mb



        enum compatibility_status



        timestamp created_at



        timestamp updated_at



    }



    hardware_activation_logs {



        bigint id PK



        bigint hardware_activation_id FK



        bigint license_id FK



        varchar machine_id



        json hardware_snapshot



        json system_specs



        enum activation_status



        varchar failure_reason



        bool vm_detected



        bool tamper_detected



        enum compatibility_result



        timestamp created_at



    }







    %% ==========================================



    %% 6. VERIFICATION & FRAUD



    %% ==========================================



    verification_logs {



        bigint id PK



        bigint activation_id FK



        varchar license_key



        varchar api_key



        varchar ip_address



        varchar user_agent



        varchar request_domain



        varchar request_ip



        enum tier1_api



        enum tier2_license



        enum tier3_domain



        enum tier4_ip



        enum tier5_subscription



        enum overall_result



        timestamp created_at



    }



    fraud_logs {



        bigint id PK



        bigint license_id FK



        bigint activation_id FK



        varchar ip



        varchar domain



        varchar reason



        enum severity "low|medium|high|critical"



        varchar action_taken



        timestamp created_at



    }



    user_2fa {



        bigint id PK



        bigint user_id FK



        varchar secret



        enum method "totp|email|sms|backup_codes"



        json backup_codes



        bool is_enabled



        timestamp verified_at



        timestamp created_at



        timestamp updated_at



    }







    %% ==========================================



    %% 7. SUPPORT & TICKETING & CHAT



    %% ==========================================



    %% 7. SUPPORT & CHAT



    %% ==========================================



    tickets {



        bigint id PK



        bigint user_id FK



        varchar subject



        enum status "open|replied|closed"



        enum priority "low|medium|high"



        bigint assigned_to FK



        varchar category



        timestamp closed_at



        timestamp created_at



        timestamp updated_at



    }



    ticket_messages {



        bigint id PK



        bigint ticket_id FK



        bigint sender_id FK



        text message



        json attachments



        timestamp created_at



    }



    chat_sessions {



        bigint id PK



        bigint user_id FK



        enum status "open|closed"



        bigint assigned_to FK



        timestamp created_at



        timestamp updated_at



    }



    chat_messages {



        bigint id PK



        bigint session_id FK



        bigint user_id FK



        text message



        bool is_agent



        timestamp created_at



    }



    bot_conversations {



        bigint id PK



        bigint user_id FK



        bigint bot_config_id FK



        text message



        text response



        timestamp created_at



    }







    %% ==========================================



    %% 8. APPLICATION UPDATES & CONTENT



    %% ==========================================



    application_updates {



        bigint id PK



        varchar version



        varchar type "major|minor|patch|security"



        varchar title



        text changelog



        varchar download_url



        enum status "draft|published|archived"



        timestamp created_at



        timestamp updated_at



    }



    posts {



        bigint id PK



        enum type "blog|cms"



        bigint author_id FK



        varchar title



        varchar slug UK



        text content



        text excerpt



        varchar featured_image



        bigint category_id FK



        enum status "published|draft|scheduled"



        timestamp published_at



        timestamp scheduled_for



        varchar meta_title



        text meta_description



        varchar meta_keywords



        varchar canonical_url



        int view_count



        timestamp created_at



        timestamp updated_at



    }



    release_notes {



        bigint id PK



        varchar version



        varchar title



        text content



        enum status "published|draft"



        timestamp created_at



        timestamp updated_at



    }



    user_guides {



        bigint id PK



        bigint author_id FK



        varchar title



        varchar slug UK



        text content



        varchar category



        int sort_order



        timestamp created_at



        timestamp updated_at



    }



    announcements {



        bigint id PK



        varchar title



        text content



        enum type "info|warning|maintenance"



        enum status "published|draft"



        timestamp created_at



        timestamp updated_at



    }



    knowledge_base_articles {



        bigint id PK



        varchar title



        varchar slug UK



        text content



        varchar category



        enum status "published|draft"



        timestamp created_at



        timestamp updated_at



    }



    faq_items {



        bigint id PK



        varchar question



        text answer



        varchar category



        int sort_order



        enum status "published|draft"



        timestamp created_at



        timestamp updated_at



    }







    %% ==========================================



    %% 9. CMS



    %% ==========================================



    cms_categories {



        bigint id PK



        varchar name



        varchar slug UK



        text description



        timestamp created_at



        timestamp updated_at



    }



    cms_tags {



        bigint id PK



        varchar name



        varchar slug UK



        timestamp created_at



        timestamp updated_at



    }



    post_tags {



        bigint post_id PK,FK



        bigint tag_id PK,FK



    }



    cms_pages {



        bigint id PK



        varchar title



        varchar slug UK



        text content



        enum status "published|draft"



        timestamp published_at



        timestamp scheduled_for



        bigint author_id FK



        timestamp created_at



        timestamp updated_at



    }



    cms_menus {



        bigint id PK



        varchar name



        varchar slug UK



        timestamp created_at



        timestamp updated_at



    }



    cms_menu_items {



        bigint id PK



        bigint menu_id FK



        varchar title



        varchar url



        varchar target



        int position



        timestamp created_at



        timestamp updated_at



    }



    cms_settings {



        bigint id PK



        varchar key



        text value



        enum group "general|seo|analytics|social|custom"



        timestamp created_at



        timestamp updated_at



    }



    cms_widgets {



        bigint id PK



        varchar name



        varchar type



        text content



        varchar position



        int sort_order



        enum status "active|inactive"



        timestamp created_at



        timestamp updated_at



    }



    cms_banners {



        bigint id PK



        varchar title



        varchar image_url



        varchar link_url



        varchar position



        enum status "active|inactive"



        timestamp created_at



        timestamp updated_at



    }



    cms_testimonials {



        bigint id PK



        varchar author_name



        varchar author_title



        text content



        varchar avatar_url



        int rating



        enum status "published|draft"



        timestamp created_at



        timestamp updated_at



    }



    cms_social_links {



        bigint id PK



        varchar platform



        varchar url



        varchar icon



        int sort_order



        timestamp created_at



        timestamp updated_at



    }



    cms_footers {



        bigint id PK



        varchar name



        text content



        varchar position



        enum status "active|inactive"



        timestamp created_at



        timestamp updated_at



    }



    cms_headers {



        bigint id PK



        varchar name



        text content



        enum status "active|inactive"



        timestamp created_at



        timestamp updated_at



    }



    cms_sidebars {



        bigint id PK



        varchar name



        text content



        varchar position



        enum status "active|inactive"



        timestamp created_at



        timestamp updated_at



    }



    cms_media_galleries {



        bigint id PK



        varchar file_name



        varchar file_path



        varchar file_type



        bigint file_size



        timestamp created_at



    }







    %% CONTENT FEATURES (Posts, Comments, Reactions, Series, Media)



    post_tags {



        bigint post_id PK,FK



        bigint tag_id PK,FK



    }



    post_comments {



        bigint id PK



        bigint post_id FK



        bigint user_id FK



        bigint parent_id FK



        varchar author_name



        varchar author_email



        text body



        enum status "pending|approved|spam"



        timestamp created_at



        timestamp updated_at



    }



    post_reactions {



        bigint id PK



        bigint post_id FK



        bigint user_id FK



        enum reaction "like|love|laugh|clap|fire"



        timestamp created_at



    }



    post_views {



        bigint id PK



        bigint post_id FK



        bigint user_id FK



        varchar ip_address



        text user_agent



        timestamp viewed_at



    }



    post_media {



        bigint id PK



        bigint post_id FK



        varchar file_name



        varchar file_path



        varchar file_type



        int file_size



        bool is_featured



        int sort_order



        timestamp created_at



    }



    post_series {



        bigint id PK



        varchar title



        varchar slug UK



        text description



        varchar cover_image



        bigint author_id FK



        enum status "active|archived"



        timestamp created_at



        timestamp updated_at



    }



    post_series_items {



        bigint id PK



        bigint series_id FK



        bigint post_id FK



        int part_order



        varchar part_title



    }



    related_posts {



        bigint id PK



        bigint post_id FK



        bigint related_post_id FK



        enum relation_type "manual|auto_tag|auto_category"



        int weight



        timestamp created_at



    }



    author_profiles {



        bigint user_id PK,FK



        varchar display_name



        varchar avatar_url



        text bio



        varchar website_url



        varchar twitter_handle



        varchar github_handle



        varchar linkedin_url



        bool is_public



        timestamp created_at



        timestamp updated_at



    }







    %% ==========================================



    %% 9b. VIDEO GALLERY & GUIDELINES



    %% ==========================================



    video_galleries {



        bigint id PK



        varchar title



        varchar slug UK



        text description



        varchar thumbnail



        bigint author_id FK



        enum status "active|archived"



        int sort_order



        timestamp created_at



        timestamp updated_at



    }



    videos {



        bigint id PK



        bigint gallery_id FK



        varchar title



        varchar slug UK



        text description



        varchar video_url



        varchar embed_url



        varchar thumbnail



        int duration



        bigint file_size



        varchar file_type



        bigint author_id FK



        enum status "published|draft"



        bool featured



        int view_count



        int sort_order



        timestamp created_at



        timestamp updated_at



    }



    video_tags {



        bigint video_id PK,FK



        bigint tag_id PK,FK



    }



    video_guidelines {



        bigint id PK



        bigint video_id FK



        int step_order



        varchar title



        text description



        int time_marker



        varchar image



        timestamp created_at



        timestamp updated_at



    }







    %% ==========================================



    %% 9c. SEO & ANALYTICS



    %% ==========================================



    redirects {



        bigint id PK



        varchar old_path UK



        varchar new_path



        enum status_code "301|302|307"



        bool is_active



        int hits_count



        timestamp created_at



        timestamp updated_at



    }



    slug_history {



        bigint id PK



        enum content_type "post|cms_page|product|knowledge_base|faq_item"



        bigint content_id



        varchar old_slug



        varchar new_slug



        timestamp created_at



    }



    structured_data {



        bigint id PK



        enum content_type "post|cms_page|product"



        bigint content_id



        varchar schema_type



        json json_ld



        timestamp created_at



        timestamp updated_at



    }



    seo_analysis {



        bigint id PK



        enum content_type "post|cms_page"



        bigint content_id



        decimal score



        json issues



        int word_count



        decimal readability_score



        timestamp checked_at



    }



    analytics_events {



        bigint id PK



        varchar event_type



        varchar page_url



        varchar referrer_url



        varchar utm_source



        varchar utm_medium



        varchar utm_campaign



        varchar utm_term



        varchar utm_content



        text user_agent



        varchar ip_address



        varchar session_id



        bigint user_id FK



        bigint post_id FK



        bigint page_id FK



        timestamp created_at



    }







    %% ==========================================



    %% 9d. MEDIA & CONTENT REVISIONS



    %% ==========================================



    media_library {



        bigint id PK



        varchar filename



        varchar filepath



        varchar mime_type



        bigint file_size



        int width



        int height



        varchar alt_text



        text caption



        bigint uploaded_by FK



        timestamp created_at



        timestamp updated_at



    }



    content_revisions {



        bigint id PK



        enum content_type "post|cms_page|user_guide|knowledge_base"



        bigint content_id



        varchar title



        longtext content



        text summary



        json meta



        bigint created_by FK



        timestamp created_at



    }







    %% ==========================================



    %% 9e. FORMS & PAGE TAGGING



    %% ==========================================



    cms_page_tags {



        bigint page_id PK,FK



        bigint tag_id PK,FK



    }



    form_submissions {



        bigint id PK



        varchar form_key



        json data



        bigint user_id FK



        varchar ip_address



        text user_agent



        timestamp created_at



    }



    content_blocks {



        bigint id PK



        varchar key UK



        varchar title



        longtext content



        enum type "html|text|json"



        json locations



        bool active



        timestamp created_at



        timestamp updated_at



    }







    %% ==========================================



    %% 10. THEMES



    %% ==========================================



    themes {



        bigint id PK



        varchar name



        varchar slug UK



        text description



        varchar version



        varchar author



        enum status "active|inactive"



        timestamp created_at



        timestamp updated_at



    }



    theme_settings {



        bigint id PK



        bigint theme_id FK



        varchar key



        text value



        timestamp created_at



    }



    theme_generations {



        bigint id PK



        bigint theme_id FK



        bigint user_id FK



        varchar theme_name



        text theme_description



        enum status "pending|in_progress|completed|failed"



        timestamp created_at



        timestamp updated_at



    }



    theme_assets {



        bigint id PK



        bigint theme_id FK



        varchar file_name



        varchar file_url



        varchar asset_type "css|js|image|font|other"



        bigint file_size



        timestamp created_at



    }



    theme_customizations {



        bigint id PK



        bigint theme_id FK



        varchar setting_key



        text setting_value



        timestamp created_at



        timestamp updated_at



    }



    theme_usage_logs {



        bigint id PK



        bigint theme_id FK



        bigint user_id FK



        varchar action "activated|deactivated|previewed|customized"



        timestamp created_at



    }



    theme_update_logs {



        bigint id PK



        bigint theme_id FK



        varchar previous_version



        varchar new_version



        enum status "pending|completed|failed"



        timestamp created_at



    }



    theme_conflicts {



        bigint id PK



        bigint theme_id FK



        bigint conflicting_theme_id FK



        varchar conflict_type "css|js|function"



        text description



        enum status "resolved|unresolved"



        timestamp created_at



        timestamp updated_at



    }



    theme_general_settings {



        bigint id PK



        varchar setting_key UK



        text setting_value



        timestamp created_at



        timestamp updated_at



    }



    theme_generation_logs {



        bigint id PK



        bigint generation_id FK



        varchar message



        timestamp created_at



    }







    %% ==========================================



    %% 11. LLM & BOT CONFIG



    %% ==========================================



    llm_providers {



        bigint id PK



        varchar name



        varchar api_key



        varchar base_url



        enum status "active|inactive"



        timestamp created_at



        timestamp updated_at



    }



    llm_provider_settings {



        bigint id PK



        bigint provider_id FK



        varchar setting_key



        text setting_value



        timestamp created_at



        timestamp updated_at



    }



    llm_provider_activity {



        bigint id PK



        bigint provider_id FK



        bigint user_id FK



        varchar action



        text details



        timestamp created_at



    }



    llm_provider_usage {



        bigint id PK



        bigint provider_id FK



        bigint user_id FK



        int total_requests



        int total_tokens



        decimal cost



        date usage_date



        timestamp created_at



    }



    bot_configs {



        bigint id PK



        varchar name



        enum platform "telegram|discord|slack|whatsapp|custom"



        varchar platform_token



        varchar platform_username



        varchar webhook_url



        bigint llm_provider_id FK



        text llm_system_prompt



        text welcome_message



        enum status "active|inactive"



        json allowed_user_ids



        int rate_limit_per_minute



        int max_conversation_length



        json settings



        timestamp created_at



        timestamp updated_at



    }



    llm_provider_logs {



        bigint id PK



        bigint provider_id FK



        bigint user_id FK



        text prompt



        text response



        timestamp created_at



    }







    %% ==========================================



    %% 12. SECURITY & COMPLIANCE



    %% ==========================================



    security_events {



        bigint id PK



        bigint user_id FK



        varchar event_type



        text description



        varchar ip_address



        varchar user_agent



        timestamp created_at



    }



    vulnerability_scans {



        bigint id PK



        varchar scan_type



        varchar target



        enum severity "low|medium|high|critical"



        text findings



        enum status "open|resolved|false_positive"



        timestamp created_at



        timestamp updated_at



    }



    penetration_tests {



        bigint id PK



        varchar test_name



        varchar target



        enum methodology "blackbox|whitebox|greybox"



        enum status "planned|in_progress|completed|failed"



        text summary



        timestamp scheduled_at



        timestamp completed_at



        timestamp created_at



    }



    compliance_reports {



        bigint id PK



        varchar report_type "gdpr|soc2|hipaa|iso27001|custom"



        varchar title



        enum status "draft|generated|archived"



        text content



        timestamp generated_at



        timestamp created_at



    }



    security_incidents {



        bigint id PK



        varchar incident_type



        enum severity "low|medium|high|critical"



        varchar title



        text description



        enum status "open|investigating|resolved|closed"



        timestamp detected_at



        timestamp resolved_at



        timestamp created_at



        timestamp updated_at



    }



    audit_trails {



        bigint id PK



        bigint user_id FK



        varchar action



        varchar entity



        bigint entity_id



        varchar activity_type



        text description



        json old_value



        json new_value



        varchar ip_address



        json details



        timestamp created_at



    }







    %% ==========================================



    %% 13. NOTIFICATIONS & LOGS



    %% ==========================================



    notifications {



        bigint id PK



        bigint user_id FK



        enum type "system|subscription|security|product"



        varchar title



        text message



        json data



        bool is_read



        timestamp read_at



        enum channel "in_app|email|telegram|sms"



        varchar action_url



        varchar action_text



        timestamp created_at



    }



    api_request_logs {



        bigint id PK



        bigint api_client_id FK



        varchar endpoint



        varchar method



        json request_data



        json response_data



        int status_code



        varchar ip_address



        timestamp created_at



    }



    %% ==========================================



    %% 14. SYSTEM SETTINGS



    %% ==========================================



    system_settings {



        bigint id PK



        varchar key UK



        text value



        varchar group



        timestamp created_at



        timestamp updated_at



    }



    system_logs {



        bigint id PK



        enum level "debug|info|warning|error|critical"



        text message



        json context



        timestamp created_at



    }



    email_settings {



        bigint id PK



        varchar smtp_host



        int smtp_port



        varchar encryption "tls|ssl|none"



        varchar username



        varchar from_address



        varchar from_name



        enum status "active|inactive"



        timestamp created_at



        timestamp updated_at



    }



    email_templates {



        bigint id PK



        varchar name



        varchar subject



        text body



        varchar variables



        enum status "active|inactive"



        timestamp created_at



        timestamp updated_at



    }



    payment_gateways {



        bigint id PK



        varchar code "stripe|paypal|bkash|crypto"



        varchar name



        varchar api_key



        varchar api_secret



        varchar webhook_secret



        enum status "active|inactive"



        timestamp created_at



        timestamp updated_at



    }



    payment_gateway_settings {



        bigint id PK



        bigint gateway_id FK



        varchar key



        text value



        timestamp created_at



        timestamp updated_at



    }



    webhooks {



        bigint id PK



        varchar name



        varchar url



        json events



        varchar secret



        bool is_active



        timestamp last_triggered_at



        timestamp created_at



        timestamp updated_at



    }



    feature_flags {



        bigint id PK



        varchar name UK



        varchar key UK



        text description



        bool enabled



        json conditions



        timestamp created_at



        timestamp updated_at



    }







    %% ==========================================



    %% RELATIONSHIPS



    %% ==========================================







    %% CORE



    
    %% Section 16 - Commerce & Platform Enhancements
    carts {
        bigint id PK
        bigint user_id FK
        varchar session_id
        bigint coupon_id FK
        text notes
        timestamp expires_at
        timestamp created_at
        timestamp updated_at
    }
    cart_items {
        bigint id PK
        bigint cart_id FK
        bigint product_id FK
        bigint plan_id FK
        int quantity
        decimal unit_price
        decimal subtotal
        timestamp created_at
    }
    coupons {
        bigint id PK
        varchar code
        text description
        enum discount_type
        decimal discount_value
        decimal min_order_amount
        int max_uses
        int used_count
        int max_uses_per_user
        bool is_active
        timestamp starts_at
        timestamp expires_at
        json product_ids
        json excluded_product_ids
        bigint created_by FK
        timestamp created_at
        timestamp updated_at
    }
    wishlists {
        bigint id PK
        bigint user_id FK
        varchar name
        bool is_public
        timestamp created_at
        timestamp updated_at
    }
    wishlist_items {
        bigint id PK
        bigint wishlist_id FK
        bigint product_id FK
        text notes
        timestamp created_at
    }
    reviews {
        bigint id PK
        varchar reviewable_type
        bigint reviewable_id
        bigint user_id FK
        bigint order_id FK
        tinyint rating
        varchar title
        text body
        bool is_approved
        bool is_verified_purchase
        int helpful_count
        timestamp created_at
        timestamp updated_at
    }
    refunds {
        bigint id PK
        bigint order_id FK
        bigint order_item_id FK
        bigint user_id FK
        decimal amount
        text reason
        enum status
        varchar payment_method
        varchar transaction_ref
        bigint processed_by FK
        timestamp processed_at
        timestamp created_at
        timestamp updated_at
    }
    return_requests {
        bigint id PK
        bigint order_id FK
        bigint order_item_id FK
        bigint user_id FK
        enum reason
        text description
        enum status
        enum resolution
        text admin_notes
        bigint processed_by FK
        timestamp processed_at
        timestamp created_at
        timestamp updated_at
    }
    user_addresses {
        bigint id PK
        bigint user_id FK
        varchar label
        varchar full_name
        varchar phone
        varchar address_line1
        varchar address_line2
        varchar city
        varchar state
        varchar postal_code
        varchar country
        bool is_default_billing
        bool is_default_shipping
        timestamp created_at
        timestamp updated_at
    }
    user_payment_methods {
        bigint id PK
        bigint user_id FK
        bigint gateway_id FK
        enum method_type
        varchar gateway_token
        varchar display_name
        varchar last_four
        varchar expiry_month
        varchar expiry_year
        varchar card_brand
        bool is_default
        bigint billing_address_id
        timestamp created_at
        timestamp updated_at
    }
    personal_access_tokens {
        bigint id PK
        bigint user_id FK
        varchar name
        varchar token
        json abilities
        timestamp last_used_at
        timestamp expires_at
        timestamp created_at
        timestamp updated_at
    }
    sessions {
        varchar id PK
        bigint user_id FK
        varchar ip_address
        text user_agent
        text payload
        int last_activity
    }
    social_accounts {
        bigint id PK
        bigint user_id FK
        varchar provider
        varchar provider_id
        varchar provider_email
        varchar avatar_url
        text access_token
        text refresh_token
        timestamp token_expires_at
        timestamp created_at
        timestamp updated_at
    }
    user_devices {
        bigint id PK
        bigint user_id FK
        enum platform
        varchar device_token
        varchar device_name
        bool is_active
        timestamp last_notified_at
        timestamp created_at
        timestamp updated_at
    }


    users ||--o{ carts : "1:N"
    users ||--o{ coupons : "1:N"
    users ||--o{ wishlists : "1:N"
    users ||--o{ reviews : "1:N"
    users ||--o{ refunds : "1:N"
    users ||--o{ return_requests : "1:N"
    users ||--o{ user_addresses : "1:N"
    users ||--o{ user_payment_methods : "1:N"
    users ||--o{ personal_access_tokens : "1:N"
    users ||--o{ sessions : "1:N"
    users ||--o{ social_accounts : "1:N"
    users ||--o{ user_devices : "1:N"
    products ||--o{ cart_items : "1:N"
    products ||--o{ wishlist_items : "1:N"
    products ||--o{ reviews : "1:N"
    orders ||--o{ reviews : "1:N"
    orders ||--o{ refunds : "1:N"
    orders ||--o{ return_requests : "1:N"
    carts ||--o{ cart_items : "1:N"
    coupons ||--o{ carts : "1:N"
    wishlists ||--o{ wishlist_items : "1:N"
    payment_gateways ||--o{ user_payment_methods : "1:N"
    order_items ||--o{ refunds : "1:N"
    order_items ||--o{ return_requests : "1:N"

%% Section 17 - SMS & Communications

    sms_providers {

        bigint id PK

        varchar name

        enum provider

        varchar api_key

        varchar api_secret

        varchar from_number

        varchar api_endpoint

        json config

        bool is_active

        bool is_default

        int priority

        timestamp created_at

        timestamp updated_at

    }

    sms_templates {

        bigint id PK

        varchar name

        varchar category

        text body

        json variables

        timestamp created_at

        timestamp updated_at

    }

    sms_campaigns {

        bigint id PK

        varchar name

        text message_body

        bigint sms_template_id FK

        bigint provider_id FK

        enum target_type

        json target_roles

        json target_user_ids

        json filter_criteria

        timestamp scheduled_at

        timestamp sent_at

        timestamp completed_at

        enum status

        int total_recipients

        int success_count

        int fail_count

        bigint created_by FK

        timestamp created_at

        timestamp updated_at

    }

    sms_campaign_recipients {

        bigint id PK

        bigint campaign_id FK

        bigint user_id FK

        varchar phone

        enum status

        text error_message

        varchar provider_message_id

        bigint provider_id FK

        timestamp sent_at

        timestamp delivered_at

        timestamp created_at

    }

    sms_automations {

        bigint id PK

        varchar name

        enum trigger_type

        varchar event_name

        json event_conditions

        varchar cron_expression

        varchar timezone

        enum target_type

        json target_roles

        json filter_criteria

        text message_body

        bigint sms_template_id FK

        bigint provider_id FK

        bool is_active

        timestamp last_triggered_at

        int total_sent

        bigint created_by FK

        timestamp created_at

        timestamp updated_at

    }

    sms_logs {

        bigint id PK

        bigint provider_id FK

        bigint campaign_id FK

        bigint recipient_id FK

        enum direction

        json request_payload

        json response_payload

        int http_status

        varchar provider_message_id

        text error_message

        timestamp created_at

    }



    sms_providers ||--o{ sms_campaigns : "1:N"

    sms_providers ||--o{ sms_campaign_recipients : "1:N"

    sms_providers ||--o{ sms_automations : "1:N"

    sms_providers ||--o{ sms_logs : "1:N"

    sms_templates ||--o{ sms_campaigns : "1:N"

    sms_templates ||--o{ sms_automations : "1:N"

    sms_campaigns ||--o{ sms_campaign_recipients : "1:N"

    sms_campaigns ||--o{ sms_logs : "1:N"

    sms_campaign_recipients ||--o{ sms_logs : "1:N"

    users ||--o{ sms_campaigns : "1:N"

    users ||--o{ sms_campaign_recipients : "1:N"

    users ||--o{ sms_automations : "1:N"





    users ||--o| seller_profiles : "1:1 (seller)"



    seller_profiles ||--o{ payout_accounts : "1:N"



    seller_profiles ||--o{ payout_transactions : "1:N"



    seller_profiles ||--o{ balance_ledger : "1:N"



    seller_profiles ||--o{ seller_verification : "1:N"



    seller_profiles ||--o{ reviews : "1:N"



    seller_profiles ||--o{ seller_stats : "1:N"



    payout_accounts ||--o{ payout_transactions : "1:N"



    users ||--o{ seller_verification : "1:N (verified_by)"



    users ||--o{ reviews : "1:N (buyer)"



    orders ||--o{ reviews : "1:N"



    users ||users ||--o{ auth_logs : "1:N"







    %% PRODUCTS



    users ||--o{ products : "1:N (seller)"



    products ||--o{ product_hardware_requirements : "1:N"







    %% SUBSCRIPTIONS



    users ||--o{ user_subscriptions : "1:N"



    subscription_plans ||--o{ user_subscriptions : "1:N"



    products ||--o{ user_subscriptions : "1:N"







    %% BILLING



    users ||--o{ invoices : "1:N"



    orders ||--o{ invoices : "1:N"



    user_subscriptions ||--o{ invoices : "1:N"



    invoices ||--o{ payments : "1:N"







    %% ORDERS



    users ||--o{ carts : "1:N"



    users ||--o{ user_addresses : "1:N"



    carts ||--o{ cart_items : "1:N"



    products ||--o{ cart_items : "1:N"



    subscription_plans ||--o{ cart_items : "1:N"



    coupons ||--o{ carts : "1:N"



    users ||--o{ orders : "1:N"



    orders ||--o{ order_items : "1:N"



    products ||--o{ order_items : "1:N"



    subscription_plans ||--o{ order_items : "1:N"



    order_items ||--o{ order_item_metadata : "1:N"



    licenses ||--o{ order_item_metadata : "1:N"



    user_addresses ||--o{ orders : "1:N (billing)"



    user_addresses ||--o{ orders : "1:N (shipping)"



    coupons ||--o{ orders : "1:N"



    orders ||--o{ order_status_history : "1:N"



    users ||--o{ order_status_history : "1:N (changed_by)"



    api_clients ||--o{ orders : "1:N"



    api_clients ||--o{ order_status_history : "1:N"



    orders ||--o{ refunds : "1:N"



    payments ||--o{ refunds : "1:N"



    users ||--o{ refunds : "1:N (processed_by)"







    %% LICENSING



    users ||--o{ api_clients : "1:N"



    users ||--o{ licenses : "1:N"



    products ||--o{ licenses : "1:N"



    api_clients ||--o{ licenses : "1:N"



    user_subscriptions ||--o{ licenses : "1:N"



    licenses ||--o{ license_activations : "1:N"



    licenses ||--o{ hardware_activations : "1:N"



    hardware_activations ||--o{ hardware_activation_logs : "1:N"



    licenses ||--o{ hardware_activation_logs : "1:N"







    %% VERIFICATION



    license_activations ||--o{ verification_logs : "1:N"



    licenses ||--o{ fraud_logs : "1:N"



    license_activations ||--o{ fraud_logs : "1:N"







    %% SUPPORT



    users ||--o{ tickets : "1:N"



    tickets ||--o{ ticket_messages : "1:N"



    users ||--o{ ticket_messages : "1:N (sender)"



    users ||--o{ chat_sessions : "1:N"



    users ||--o{ chat_sessions : "1:N (assigned)"



    chat_sessions ||--o{ chat_messages : "1:N"



    users ||--o{ chat_messages : "1:N"



    users ||--o{ bot_conversations : "1:N"







    %% NEW CORE FKs



    password_resets }o--|| users : "fk_password_resets_user"



    role_permissions }o--|| roles : "fk_role_permissions_role"



    role_permissions }o--|| permissions : "fk_role_permissions_permission"



    user_roles }o--|| users : "fk_user_roles_user"



    user_roles }o--|| roles : "fk_user_roles_role"



    organization_members }o--|| organizations : "fk_org_members_org"



    organization_members }o--|| users : "fk_org_members_user"



    user_2fa }o--|| users : "fk_user_2fa_user"







    %% NEW PRODUCTS FKs



    wishlists }o--|| users : "fk_wishlists_user"



    wishlists }o--|| products : "fk_wishlists_product"



    product_categories }o--o| product_categories : "fk_product_categories_parent"



    product_category_items }o--|| products : "fk_prod_cat_items_product"



    product_category_items }o--|| product_categories : "fk_prod_cat_items_category"



    product_discounts }o--|| products : "fk_product_discounts_product"







    %% NEW ORDERS FKs



    shipments }o--|| orders : "fk_shipments_order"



    file_downloads }o--|| order_items : "fk_file_downloads_order_item"



    file_downloads }o--|| users : "fk_file_downloads_user"



    affiliates }o--|| users : "fk_affiliates_user"



    referrals }o--|| affiliates : "fk_referrals_affiliate"



    referrals }o--|| users : "fk_referrals_referred"



    referrals }o--o| orders : "fk_referrals_order"







    %% CMS



    users ||--o{ posts : "1:N (author)"



    cms_categories ||--o{ posts : "1:N"



    posts ||--o{ post_tags : "1:N"



    cms_tags ||--o{ post_tags : "1:N"



    cms_menus ||--o{ cms_menu_items : "1:N"







    %% NEW CMS FK



    cms_pages }o--o| users : "fk_cms_pages_author"







    %% THEMES



    themes ||--o{ theme_settings : "1:N"



    themes ||--o{ theme_generations : "1:N"



    users ||--o{ theme_generations : "1:N"







    %% LLM



    llm_providers ||--o{ llm_provider_logs : "1:N"



    users ||--o{ llm_provider_logs : "1:N"



    llm_providers ||--o{ bot_configs : "1:N"



    bot_configs ||--o{ bot_conversations : "1:N"







    %% SECURITY



    users ||--o{ security_events : "1:N"



    users ||--o{ audit_trails : "1:N"







    %% NOTIFICATIONS



    users ||--o{ notifications : "1:N"







    %% API LOGS



    api_clients ||--o{ api_request_logs : "1:N"



    orders ||--o{ api_request_logs : "1:N"







    %% APP & CONTENT



    users ||--o{ user_guides : "1:N (author)"







    %% THEMES (extended)



    themes ||--o{ theme_assets : "1:N"



    themes ||--o{ theme_customizations : "1:N"



    users ||--o{ theme_customizations : "1:N"



    themes ||--o{ theme_usage_logs : "1:N"



    users ||--o{ theme_usage_logs : "1:N"



    themes ||--o{ theme_update_logs : "1:N"



    themes ||--o{ theme_conflicts : "1:N"



    theme_generations ||--o{ theme_generation_logs : "1:N"







    %% LLM (extended)



    llm_providers ||--o{ llm_provider_settings : "1:N"



    llm_providers ||--o{ llm_provider_activity : "1:N"



    users ||--o{ llm_provider_activity : "1:N"



    llm_providers ||--o{ llm_provider_usage : "1:N"



    users ||--o{ llm_provider_usage : "1:N"







    %% AUDIT LOGS



    users ||--o{ audit_trails : "1:N"







    %% PAYMENT GATEWAYS



    payment_gateways ||--o{ payment_gateway_settings : "1:N"







    %% CONTENT FEATURES



    posts ||--o{ post_comments : "1:N"



    users ||--o{ post_comments : "1:N"



    post_comments ||--o{ post_comments : "1:N (parent)"



    posts ||--o{ post_reactions : "1:N"



    users ||--o{ post_reactions : "1:N"



    posts ||--o{ post_views : "1:N"



    posts ||--o{ post_media : "1:N"



    post_series ||--o{ post_series_items : "1:N"



    posts ||--o{ post_series_items : "1:N"



    posts ||--o{ related_posts : "1:N (source)"



    posts ||--o{ related_posts : "1:N (target)"



    users ||--o{ author_profiles : "1:1"



    users ||--o{ post_series : "1:N"







    %% VIDEO GALLERY & GUIDELINES



    users ||--o{ video_galleries : "1:N"



    video_galleries ||--o{ videos : "1:N"



    users ||--o{ videos : "1:N"



    videos ||--o{ video_tags : "1:N"



    cms_tags ||--o{ video_tags : "1:N"



    videos ||--o{ video_guidelines : "1:N"







    %% SEO & ANALYTICS



    users ||--o{ analytics_events : "1:N"



    posts ||--o{ analytics_events : "1:N"



    cms_pages ||--o{ analytics_events : "1:N"



    users ||--o{ media_library : "1:N"



    users ||--o{ content_revisions : "1:N"



    users ||--o{ form_submissions : "1:N"



    cms_pages ||--o{ cms_page_tags : "1:N"



    cms_tags ||--o{ cms_page_tags : "1:N"







    %% ==========================================



    %% 16. Financial / Accounting (GL)



    %% ==========================================



    currencies {



        bigint id PK



        varchar code



        varchar name



        varchar symbol



        tinyint decimal_places



        bool is_base



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    exchange_rates {



        bigint id PK



        bigint from_currency_id FK



        bigint to_currency_id FK



        decimal rate



        date date



        timestamp created_at



    }



    fiscal_years {



        bigint id PK



        varchar name



        date start_date



        date end_date



        bool is_closed



        timestamp created_at



    }



    account_periods {



        bigint id PK



        bigint fiscal_year_id FK



        enum type



        date start_date



        date end_date



        bool is_closed



        timestamp closed_at



        timestamp created_at



    }



    chart_of_accounts {



        bigint id PK



        bigint parent_id FK



        varchar account_code



        varchar account_name



        enum type



        varchar subtype



        bool is_active



        bool is_control



        text description



        timestamp created_at



        timestamp updated_at



    }



    cost_centers {



        bigint id PK



        varchar code



        varchar name



        text description



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    profit_centers {



        bigint id PK



        varchar code



        varchar name



        text description



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    journal_entry_types {



        bigint id PK



        varchar name



        varchar code



        text description



        timestamp created_at



    }



    journal_entries {



        bigint id PK



        varchar entry_number



        bigint entry_type_id FK



        bigint fiscal_year_id FK



        bigint account_period_id FK



        date entry_date



        text description



        varchar reference_type



        bigint reference_id



        bigint created_by FK



        bool is_posted



        timestamp posted_at



        timestamp created_at



        timestamp updated_at



    }



    journal_entry_lines {



        bigint id PK



        bigint journal_entry_id FK



        bigint account_id FK



        bigint cost_center_id FK



        bigint profit_center_id FK



        decimal debit



        decimal credit



        text description



        int line_order



        timestamp created_at



    }



    account_balances {



        bigint id PK



        bigint account_id FK



        bigint fiscal_year_id FK



        bigint account_period_id FK



        enum period_type



        decimal opening_balance



        decimal period_debit



        decimal period_credit



        decimal closing_balance



        timestamp created_at



        timestamp updated_at



    }



    budgets {



        bigint id PK



        bigint fiscal_year_id FK



        bigint profit_center_id FK



        bigint cost_center_id FK



        varchar name



        text description



        enum status



        timestamp created_at



        timestamp updated_at



    }



    budget_lines {



        bigint id PK



        bigint budget_id FK



        bigint account_id FK



        bigint period_id FK



        decimal amount



        timestamp created_at



        timestamp updated_at



    }



    budget_versions {



        bigint id PK



        bigint budget_id FK



        int version



        text notes



        json snapshot



        bigint created_by FK



        timestamp created_at



    }



    cost_allocations {



        bigint id PK



        bigint source_cost_center_id FK



        bigint target_cost_center_id FK



        bigint account_id FK



        enum allocation_method



        decimal allocation_value



        bool is_active



        timestamp created_at



        timestamp updated_at



    }







    currencies ||--|{ exchange_rates : "fk_exchange_rates_from"



    currencies ||--|{ exchange_rates : "fk_exchange_rates_to"



    fiscal_years ||--|{ account_periods : "fk_account_periods_fiscal"



    chart_of_accounts ||--o{ chart_of_accounts : "fk_chart_of_accounts_parent"



    journal_entry_types ||--|{ journal_entries : "fk_journal_entries_type"



    fiscal_years ||--|{ journal_entries : "fk_journal_entries_fiscal"



    account_periods ||--o{ journal_entries : "fk_journal_entries_period"



    users ||--o{ journal_entries : "fk_journal_entries_creator"



    journal_entries ||--|{ journal_entry_lines : "fk_journal_entry_lines_entry"



    chart_of_accounts ||--|{ journal_entry_lines : "fk_journal_entry_lines_account"



    cost_centers ||--o{ journal_entry_lines : "fk_journal_entry_lines_cost"



    profit_centers ||--o{ journal_entry_lines : "fk_journal_entry_lines_profit"



    chart_of_accounts ||--|{ account_balances : "fk_account_balances_account"



    fiscal_years ||--|{ account_balances : "fk_account_balances_fiscal"



    account_periods ||--o{ account_balances : "fk_account_balances_period"



    fiscal_years ||--|{ budgets : "fk_budgets_fiscal"



    profit_centers ||--o{ budgets : "fk_budgets_profit"



    cost_centers ||--o{ budgets : "fk_budgets_cost"



    budgets ||--|{ budget_lines : "fk_budget_lines_budget"



    chart_of_accounts ||--|{ budget_lines : "fk_budget_lines_account"



    account_periods ||--o{ budget_lines : "fk_budget_lines_period"



    budgets ||--|{ budget_versions : "fk_budget_versions_budget"



    users ||--o{ budget_versions : "fk_budget_versions_creator"



    cost_centers ||--|{ cost_allocations : "fk_cost_allocations_source"



    cost_centers ||--|{ cost_allocations : "fk_cost_allocations_target"



    chart_of_accounts ||--|{ cost_allocations : "fk_cost_allocations_account"



    %% ==========================================



    %% 17. Inventory & Warehouse



    %% ==========================================



    warehouses {



        bigint id PK



        varchar name



        varchar code



        text address



        varchar city



        varchar state



        varchar country



        varchar postal_code



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    warehouse_locations {



        bigint id PK



        bigint warehouse_id FK



        bigint parent_id FK



        varchar code



        varchar name



        enum type



        decimal max_weight



        decimal max_volume



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    stock_items {



        bigint id PK



        bigint product_id FK



        bigint warehouse_location_id FK



        varchar serial_number



        varchar batch_number



        decimal quantity



        decimal reserved_quantity



        decimal unit_cost



        date expiry_date



        enum status



        timestamp created_at



        timestamp updated_at



    }



    inventory_movements {



        bigint id PK



        bigint product_id FK



        bigint from_location_id FK



        bigint to_location_id FK



        bigint stock_item_id FK



        enum movement_type



        varchar reference_type



        bigint reference_id



        decimal quantity



        decimal unit_cost



        text notes



        bigint created_by FK



        timestamp created_at



    }



    inventory_adjustments {



        bigint id PK



        bigint product_id FK



        bigint warehouse_location_id FK



        enum adjustment_type



        decimal expected_qty



        decimal actual_qty



        decimal difference



        text reason



        bigint approved_by FK



        timestamp created_at



        timestamp updated_at



    }



    stock_counts {



        bigint id PK



        bigint warehouse_id FK



        date count_date



        enum status



        bigint counted_by FK



        bigint verified_by FK



        text notes



        timestamp created_at



        timestamp updated_at



    }



    stock_count_items {



        bigint id PK



        bigint stock_count_id FK



        bigint product_id FK



        bigint location_id FK



        decimal expected_qty



        decimal counted_qty



        decimal difference



        text notes



        timestamp created_at



    }



    reorder_rules {



        bigint id PK



        bigint product_id FK



        bigint warehouse_id FK



        decimal min_quantity



        decimal max_quantity



        decimal reorder_point



        decimal reorder_qty



        int lead_time_days



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    transfer_orders {



        bigint id PK



        bigint from_warehouse_id FK



        bigint to_warehouse_id FK



        varchar transfer_number



        enum status



        bigint requested_by FK



        bigint approved_by FK



        text notes



        timestamp created_at



        timestamp updated_at



    }



    transfer_order_items {



        bigint id PK



        bigint transfer_order_id FK



        bigint product_id FK



        bigint stock_item_id FK



        decimal quantity



        decimal received_qty



        decimal unit_cost



        timestamp created_at



    }







    warehouses ||--|{ warehouse_locations : "fk_warehouse_locations_warehouse"



    warehouse_locations ||--o{ warehouse_locations : "fk_warehouse_locations_parent"



    products ||--|{ stock_items : "fk_stock_items_product"



    warehouse_locations ||--|{ stock_items : "fk_stock_items_location"



    products ||--|{ inventory_movements : "fk_inventory_movements_product"



    warehouse_locations ||--o{ inventory_movements : "fk_inventory_movements_from"



    warehouse_locations ||--o{ inventory_movements : "fk_inventory_movements_to"



    stock_items ||--o{ inventory_movements : "fk_inventory_movements_stock"



    users ||--o{ inventory_movements : "fk_inventory_movements_creator"



    products ||--|{ inventory_adjustments : "fk_inventory_adjustments_product"



    warehouse_locations ||--|{ inventory_adjustments : "fk_inventory_adjustments_location"



    users ||--o{ inventory_adjustments : "fk_inventory_adjustments_approver"



    warehouses ||--|{ stock_counts : "fk_stock_counts_warehouse"



    users ||--o{ stock_counts : "fk_stock_counts_counter"



    users ||--o{ stock_counts : "fk_stock_counts_verifier"



    stock_counts ||--|{ stock_count_items : "fk_stock_count_items_count"



    products ||--|{ stock_count_items : "fk_stock_count_items_product"



    warehouse_locations ||--|{ stock_count_items : "fk_stock_count_items_location"



    products ||--|{ reorder_rules : "fk_reorder_rules_product"



    warehouses ||--|{ reorder_rules : "fk_reorder_rules_warehouse"



    warehouses ||--|{ transfer_orders : "fk_transfer_orders_from"



    warehouses ||--|{ transfer_orders : "fk_transfer_orders_to"



    users ||--o{ transfer_orders : "fk_transfer_orders_requestor"



    users ||--o{ transfer_orders : "fk_transfer_orders_approver"



    transfer_orders ||--|{ transfer_order_items : "fk_transfer_order_items_order"



    products ||--|{ transfer_order_items : "fk_transfer_order_items_product"



    stock_items ||--o{ transfer_order_items : "fk_transfer_order_items_stock"



    %% ==========================================



    %% 18. Procurement



    %% ==========================================



    suppliers {



        bigint id PK



        varchar company_name



        varchar supplier_code



        varchar contact_name



        varchar email



        varchar phone



        varchar website



        text address



        varchar city



        varchar state



        varchar country



        varchar postal_code



        varchar tax_id



        varchar payment_terms



        bigint currency_id FK



        enum status



        timestamp created_at



        timestamp updated_at



    }



    supplier_contacts {



        bigint id PK



        bigint supplier_id FK



        varchar first_name



        varchar last_name



        varchar job_title



        varchar email



        varchar phone



        bool is_primary



        timestamp created_at



        timestamp updated_at



    }



    supplier_products {



        bigint id PK



        bigint supplier_id FK



        bigint product_id FK



        varchar supplier_sku



        int lead_time_days



        int moq



        bool is_preferred



        timestamp created_at



        timestamp updated_at



    }



    supplier_pricelists {



        bigint id PK



        bigint supplier_product_id FK



        decimal unit_price



        bigint currency_id FK



        int min_quantity



        date effective_from



        date effective_until



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    purchase_orders {



        bigint id PK



        bigint supplier_id FK



        varchar order_number



        enum status



        date order_date



        date expected_date



        decimal subtotal



        decimal tax



        decimal total



        bigint currency_id FK



        text notes



        bigint requested_by FK



        bigint approved_by FK



        timestamp created_at



        timestamp updated_at



    }



    purchase_order_items {



        bigint id PK



        bigint purchase_order_id FK



        bigint product_id FK



        bigint warehouse_location_id FK



        text description



        decimal quantity



        decimal received_qty



        decimal unit_price



        decimal tax_rate



        decimal subtotal



        int line_order



        timestamp created_at



        timestamp updated_at



    }



    purchase_receipts {



        bigint id PK



        bigint purchase_order_id FK



        varchar receipt_number



        date received_date



        enum status



        text notes



        bigint received_by FK



        timestamp created_at



        timestamp updated_at



    }



    purchase_receipt_items {



        bigint id PK



        bigint purchase_receipt_id FK



        bigint po_item_id FK



        bigint product_id FK



        bigint warehouse_location_id FK



        decimal quantity



        decimal unit_cost



        varchar batch_number



        date expiry_date



        timestamp created_at



    }



    purchase_invoices {



        bigint id PK



        bigint purchase_order_id FK



        bigint supplier_id FK



        varchar invoice_number



        date invoice_date



        date due_date



        decimal subtotal



        decimal tax



        decimal total



        bigint currency_id FK



        enum status



        text notes



        timestamp created_at



        timestamp updated_at



    }



    purchase_invoice_items {



        bigint id PK



        bigint purchase_invoice_id FK



        bigint po_item_id FK



        bigint product_id FK



        decimal quantity



        decimal unit_price



        decimal subtotal



        timestamp created_at



    }



    rfqs {



        bigint id PK



        varchar rfq_number



        varchar title



        text description



        date issue_date



        date closing_date



        enum status



        bigint created_by FK



        timestamp created_at



        timestamp updated_at



    }



    rfq_items {



        bigint id PK



        bigint rfq_id FK



        bigint product_id FK



        decimal quantity



        text notes



        int line_order



        timestamp created_at



    }



    supplier_quotations {



        bigint id PK



        bigint rfq_id FK



        bigint supplier_id FK



        varchar quotation_number



        date quotation_date



        date valid_until



        decimal subtotal



        decimal tax



        decimal total



        bigint currency_id FK



        enum status



        text notes



        timestamp created_at



        timestamp updated_at



    }



    quotation_items {



        bigint id PK



        bigint quotation_id FK



        bigint rfq_item_id FK



        bigint product_id FK



        decimal quantity



        decimal unit_price



        decimal subtotal



        int line_order



        timestamp created_at



    }







    currencies ||--o{ suppliers : "fk_suppliers_currency"



    suppliers ||--|{ supplier_contacts : "fk_supplier_contacts_supplier"



    suppliers ||--|{ supplier_products : "fk_supplier_products_supplier"



    products ||--|{ supplier_products : "fk_supplier_products_product"



    supplier_products ||--|{ supplier_pricelists : "fk_supplier_pricelists_product"



    currencies ||--|{ supplier_pricelists : "fk_supplier_pricelists_currency"



    suppliers ||--|{ purchase_orders : "fk_purchase_orders_supplier"



    currencies ||--|{ purchase_orders : "fk_purchase_orders_currency"



    users ||--o{ purchase_orders : "fk_purchase_orders_requestor"



    users ||--o{ purchase_orders : "fk_purchase_orders_approver"



    purchase_orders ||--|{ purchase_order_items : "fk_po_items_order"



    products ||--|{ purchase_order_items : "fk_po_items_product"



    warehouse_locations ||--o{ purchase_order_items : "fk_po_items_location"



    purchase_orders ||--|{ purchase_receipts : "fk_purchase_receipts_order"



    users ||--o{ purchase_receipts : "fk_purchase_receipts_receiver"



    purchase_receipts ||--|{ purchase_receipt_items : "fk_pr_items_receipt"



    purchase_order_items ||--|{ purchase_receipt_items : "fk_pr_items_po_item"



    products ||--|{ purchase_receipt_items : "fk_pr_items_product"



    warehouse_locations ||--|{ purchase_receipt_items : "fk_pr_items_location"



    purchase_orders ||--|{ purchase_invoices : "fk_purchase_invoices_order"



    suppliers ||--|{ purchase_invoices : "fk_purchase_invoices_supplier"



    currencies ||--|{ purchase_invoices : "fk_purchase_invoices_currency"



    purchase_invoices ||--|{ purchase_invoice_items : "fk_pi_items_invoice"



    purchase_order_items ||--|{ purchase_invoice_items : "fk_pi_items_po_item"



    products ||--|{ purchase_invoice_items : "fk_pi_items_product"



    users ||--o{ rfqs : "fk_rfqs_creator"



    rfqs ||--|{ rfq_items : "fk_rfq_items_rfq"



    products ||--|{ rfq_items : "fk_rfq_items_product"



    rfqs ||--|{ supplier_quotations : "fk_supplier_quotations_rfq"



    suppliers ||--|{ supplier_quotations : "fk_supplier_quotations_supplier"



    currencies ||--|{ supplier_quotations : "fk_supplier_quotations_currency"



    supplier_quotations ||--|{ quotation_items : "fk_quotation_items_quotation"



    rfq_items ||--|{ quotation_items : "fk_quotation_items_rfq_item"



    products ||--|{ quotation_items : "fk_quotation_items_prod"



    %% ==========================================



    %% 19. Production & CRP



    %% ==========================================



    work_centers {



        bigint id PK



        varchar code



        varchar name



        enum type



        text description



        decimal cost_per_hour



        decimal efficiency_rate



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    work_center_capacity {



        bigint id PK



        bigint work_center_id FK



        date capacity_date



        decimal available_hours



        decimal maintenance_hours



        decimal booked_hours



        decimal overtime_hours



        text notes



        timestamp created_at



        timestamp updated_at



    }



    bill_of_materials {



        bigint id PK



        bigint product_id FK



        varchar name



        varchar version



        decimal quantity



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    bom_items {



        bigint id PK



        bigint bom_id FK



        bigint component_id FK



        decimal quantity



        varchar unit



        decimal scrap_rate



        int line_order



        timestamp created_at



        timestamp updated_at



    }



    routings {



        bigint id PK



        bigint bom_id FK



        varchar name



        decimal total_time



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    routing_steps {



        bigint id PK



        bigint routing_id FK



        bigint work_center_id FK



        varchar step_name



        int step_order



        decimal setup_time



        decimal run_time



        decimal teardown_time



        text notes



        timestamp created_at



        timestamp updated_at



    }



    production_orders {



        bigint id PK



        bigint product_id FK



        bigint bom_id FK



        bigint routing_id FK



        bigint warehouse_id FK



        varchar order_number



        decimal quantity



        decimal produced_qty



        decimal scrap_qty



        enum status



        enum priority



        date scheduled_start



        date scheduled_end



        date actual_start



        date actual_end



        text notes



        bigint created_by FK



        timestamp created_at



        timestamp updated_at



    }



    production_order_steps {



        bigint id PK



        bigint production_order_id FK



        bigint routing_step_id FK



        bigint work_center_id FK



        enum status



        decimal actual_setup_time



        decimal actual_run_time



        decimal completed_qty



        decimal scrap_qty



        date started_at



        date completed_at



        text notes



        timestamp created_at



        timestamp updated_at



    }



    production_outputs {



        bigint id PK



        bigint production_order_id FK



        bigint product_id FK



        bigint warehouse_location_id FK



        decimal quantity



        decimal unit_cost



        varchar batch_number



        timestamp created_at



    }



    production_material_issues {



        bigint id PK



        bigint production_order_id FK



        bigint stock_item_id FK



        bigint product_id FK



        bigint warehouse_location_id FK



        decimal quantity



        decimal unit_cost



        timestamp issued_at



    }



    capacity_plans {



        bigint id PK



        bigint work_center_id FK



        date plan_date



        decimal planned_hours



        decimal actual_hours



        decimal available_hours



        decimal load_percentage



        text notes



        timestamp created_at



        timestamp updated_at



    }



    maintenance_schedules {



        bigint id PK



        bigint work_center_id FK



        varchar title



        enum type



        enum frequency



        int frequency_value



        date last_done_at



        date next_due_at



        decimal estimated_hours



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    maintenance_logs {



        bigint id PK



        bigint maintenance_schedule_id FK



        bigint work_center_id FK



        varchar title



        text description



        enum type



        enum status



        date started_at



        date completed_at



        decimal duration_hours



        decimal cost



        bigint performed_by FK



        text notes



        timestamp created_at



        timestamp updated_at



    }







    work_centers ||--|{ work_center_capacity : "fk_wc_capacity_center"



    products ||--|{ bill_of_materials : "fk_bom_product"



    bill_of_materials ||--|{ bom_items : "fk_bom_items_bom"



    products ||--|{ bom_items : "fk_bom_items_component"



    bill_of_materials ||--|{ routings : "fk_routings_bom"



    routings ||--|{ routing_steps : "fk_routing_steps_routing"



    work_centers ||--|{ routing_steps : "fk_routing_steps_center"



    products ||--|{ production_orders : "fk_production_orders_product"



    bill_of_materials ||--|{ production_orders : "fk_production_orders_bom"



    routings ||--o{ production_orders : "fk_production_orders_routing"



    warehouses ||--o{ production_orders : "fk_production_orders_warehouse"



    users ||--o{ production_orders : "fk_production_orders_creator"



    production_orders ||--|{ production_order_steps : "fk_prod_order_steps_order"



    routing_steps ||--|{ production_order_steps : "fk_prod_order_steps_step"



    work_centers ||--|{ production_order_steps : "fk_prod_order_steps_center"



    production_orders ||--|{ production_outputs : "fk_production_outputs_order"



    products ||--|{ production_outputs : "fk_production_outputs_product"



    warehouse_locations ||--|{ production_outputs : "fk_production_outputs_location"



    production_orders ||--|{ production_material_issues : "fk_prod_mat_issues_order"



    stock_items ||--o{ production_material_issues : "fk_prod_mat_issues_stock"



    products ||--|{ production_material_issues : "fk_prod_mat_issues_product"



    warehouse_locations ||--|{ production_material_issues : "fk_prod_mat_issues_location"



    work_centers ||--|{ capacity_plans : "fk_capacity_plans_center"



    work_centers ||--|{ maintenance_schedules : "fk_maintenance_schedules_center"



    maintenance_schedules ||--o{ maintenance_logs : "fk_maintenance_logs_schedule"



    work_centers ||--|{ maintenance_logs : "fk_maintenance_logs_center"



    users ||--o{ maintenance_logs : "fk_maintenance_logs_operator"



    %% ==========================================



    %% 20. Human Resources & Payroll



    %% ==========================================



    departments {



        bigint id PK



        bigint parent_id FK



        varchar code



        varchar name



        bigint manager_id FK



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    job_positions {



        bigint id PK



        bigint department_id FK



        varchar title



        text description



        text requirements



        decimal salary_min



        decimal salary_max



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    employees {



        bigint id PK



        bigint user_id FK



        varchar employee_number



        bigint department_id FK



        bigint job_position_id FK



        bigint reports_to FK



        date hire_date



        date termination_date



        enum employment_type



        enum status



        decimal base_salary



        bigint currency_id FK



        json emergency_contact



        timestamp created_at



        timestamp updated_at



    }



    employee_contracts {



        bigint id PK



        bigint employee_id FK



        enum contract_type



        date start_date



        date end_date



        decimal salary



        bigint currency_id FK



        json benefits



        json documents



        enum status



        timestamp created_at



        timestamp updated_at



    }



    employee_documents {



        bigint id PK



        bigint employee_id FK



        enum document_type



        varchar file_name



        varchar file_path



        date expiry_date



        bool is_verified



        text notes



        timestamp created_at



        timestamp updated_at



    }



    attendance {



        bigint id PK



        bigint employee_id FK



        date date



        date clock_in



        date clock_out



        decimal total_hours



        enum status



        text notes



        timestamp created_at



        timestamp updated_at



    }



    leave_types {



        bigint id PK



        varchar name



        varchar code



        int days_allowed



        bool is_paid



        bool carry_forward



        int max_carry_days



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    leave_requests {



        bigint id PK



        bigint employee_id FK



        bigint leave_type_id FK



        date start_date



        date end_date



        int total_days



        text reason



        enum status



        bigint approved_by FK



        timestamp approved_at



        timestamp created_at



        timestamp updated_at



    }



    leave_balances {



        bigint id PK



        bigint employee_id FK



        bigint leave_type_id FK



        year year



        decimal total_days



        decimal used_days



        decimal pending_days



        decimal remaining_days



        timestamp created_at



        timestamp updated_at



    }



    timesheets {



        bigint id PK



        bigint employee_id FK



        date date



        time start_time



        time end_time



        decimal total_hours



        decimal break_hours



        text description



        bool is_approved



        bigint approved_by FK



        timestamp created_at



        timestamp updated_at



    }



    payroll_components {



        bigint id PK



        varchar name



        varchar code



        enum type



        enum calculation



        decimal value



        bool is_taxable



        bool is_active



        timestamp created_at



        timestamp updated_at



    }



    payroll_runs {



        bigint id PK



        bigint fiscal_year_id FK



        bigint account_period_id FK



        varchar run_number



        date period_start



        date period_end



        date payment_date



        enum status



        decimal total_gross



        decimal total_deductions



        decimal total_net



        text notes



        bigint processed_by FK



        timestamp created_at



        timestamp updated_at



    }



    payroll_items {



        bigint id PK



        bigint payroll_run_id FK



        bigint employee_id FK



        decimal gross_pay



        decimal total_deductions



        decimal net_pay



        varchar bank_account



        enum payment_method



        enum status



        timestamp paid_at



        text notes



        timestamp created_at



        timestamp updated_at



    }



    payroll_item_details {



        bigint id PK



        bigint payroll_item_id FK



        bigint payroll_component_id FK



        decimal amount



        timestamp created_at



    }
    %% Section 23 - Automation & Workflow Engine
    workflow_definitions {
        bigint id PK
        varchar name
        varchar slug UK
        text description
        varchar category
        enum status "draft|active|paused|archived"
        int version
        json config
        bool is_system
        bigint created_by FK
        timestamp created_at
        timestamp updated_at
    }
    workflow_nodes {
        bigint id PK
        bigint workflow_id FK
        enum type "trigger|action|condition|approval|wait|gateway|end"
        varchar name
        text description
        json config
        int position_x
        int position_y
        int timeout_seconds
        int retry_count
        int retry_delay
        timestamp created_at
        timestamp updated_at
    }
    workflow_transitions {
        bigint id PK
        bigint workflow_id FK
        bigint from_node_id FK
        bigint to_node_id FK
        json condition_expression
        varchar label
        int priority
        timestamp created_at
    }
    workflow_runs {
        bigint id PK
        bigint workflow_id FK
        bigint triggered_by FK
        varchar trigger_type
        json trigger_payload
        enum status "running|completed|failed|cancelled|paused"
        bigint current_node_id FK
        timestamp started_at
        timestamp completed_at
        timestamp created_at
        timestamp updated_at
    }
    workflow_run_logs {
        bigint id PK
        bigint run_id FK
        bigint node_id FK
        varchar action_type
        enum level "info|warn|error|debug"
        text message
        json payload
        timestamp created_at
    }
    workflow_run_node_states {
        bigint id PK
        bigint run_id FK
        bigint node_id FK
        enum status "pending|running|completed|failed|skipped|retrying"
        json input
        json output
        int attempts
        timestamp started_at
        timestamp completed_at
        timestamp created_at
        timestamp updated_at
    }
    workflow_run_variables {
        bigint id PK
        bigint run_id FK
        varchar name
        json value
        timestamp created_at
        timestamp updated_at
    }
    triggers {
        bigint id PK
        varchar name
        varchar slug UK
        varchar event_type
        text description
        json config
        enum status "active|inactive"
        timestamp created_at
        timestamp updated_at
    }
    trigger_workflow_mappings {
        bigint id PK
        bigint trigger_id FK
        bigint workflow_id FK
        int priority
        json conditions
        enum status "active|inactive"
        timestamp created_at
    }
    event_log {
        bigint id PK
        varchar event_type
        varchar source_type
        bigint source_id
        json payload
        timestamp occurred_at
        timestamp processed_at
        timestamp created_at
    }
    condition_groups {
        bigint id PK
        varchar name
        enum operator "AND|OR"
        timestamp created_at
        timestamp updated_at
    }
    condition_rules {
        bigint id PK
        bigint group_id FK
        varchar field
        enum operator "equals|not_equals|greater_than|less_than|greater_or_equal|less_or_equal|contains|not_contains|in|not_in|starts_with|ends_with|is_empty|is_not_empty|matches"
        json value
        timestamp created_at
    }
    condition_group_mappings {
        bigint id PK
        bigint group_id FK
        varchar entity_type
        bigint entity_id
        timestamp created_at
    }
    approval_requests {
        bigint id PK
        bigint workflow_run_id FK
        bigint node_id FK
        enum status "pending|approved|rejected|cancelled"
        bigint requested_by FK
        timestamp requested_at
        timestamp responded_at
        text notes
        timestamp created_at
        timestamp updated_at
    }
    approval_stages {
        bigint id PK
        bigint approval_request_id FK
        int stage_order
        enum status "pending|approved|rejected|skipped"
        enum strategy "any|all"
        int min_approvers
        timestamp created_at
        timestamp updated_at
    }
    approval_assignees {
        bigint id PK
        bigint stage_id FK
        bigint user_id FK
        enum status "pending|approved|rejected"
        text response
        timestamp responded_at
        timestamp created_at
    }
    email_automations {
        bigint id PK
        varchar name
        varchar trigger_event
        bigint email_template_id FK
        json conditions
        json audience_filter
        varchar sender_name
        varchar sender_email
        varchar reply_to
        enum status "draft|active|paused|archived"
        bigint created_by FK
        timestamp created_at
        timestamp updated_at
    }
    scheduled_tasks {
        bigint id PK
        varchar name
        text description
        varchar cron_expression
        enum task_type "run_workflow|call_webhook|send_report|run_sql|custom"
        json config
        enum status "active|paused|completed|failed"
        timestamp last_run_at
        timestamp next_run_at
        bool is_system
        bigint created_by FK
        timestamp created_at
        timestamp updated_at
    }
    webhook_delivery_logs {
        bigint id PK
        bigint webhook_id FK
        varchar event_type
        json payload
        json request_headers
        int response_status
        text response_body
        int attempt
        bool success
        text error_message
        timestamp delivered_at
        timestamp next_retry_at
        timestamp created_at
    }

    workflow_definitions ||--o{ workflow_nodes : "1:N"
    workflow_definitions ||--o{ workflow_transitions : "1:N"
    workflow_definitions ||--o{ workflow_runs : "1:N"
    workflow_nodes ||--o{ workflow_transitions : "1:N (from)"
    workflow_nodes ||--o{ workflow_run_logs : "1:N"
    workflow_nodes ||--o{ workflow_run_node_states : "1:N"
    workflow_nodes ||--o{ approval_requests : "1:N"
    workflow_runs ||--o{ workflow_run_logs : "1:N"
    workflow_runs ||--o{ workflow_run_node_states : "1:N"
    workflow_runs ||--o{ workflow_run_variables : "1:N"
    workflow_runs ||--o{ approval_requests : "1:N"
    triggers ||--o{ trigger_workflow_mappings : "1:N"
    workflow_definitions ||--o{ trigger_workflow_mappings : "1:N"
    condition_groups ||--o{ condition_rules : "1:N"
    condition_groups ||--o{ condition_group_mappings : "1:N"
    approval_requests ||--o{ approval_stages : "1:N"
    approval_stages ||--o{ approval_assignees : "1:N"
    users ||--o{ approval_assignees : "1:N"
    users ||--o{ approval_requests : "1:N"
    users ||--o{ workflow_runs : "1:N"
    users ||--o{ workflow_definitions : "1:N"
    users ||--o{ email_automations : "1:N"
    users ||--o{ scheduled_tasks : "1:N"
    email_templates ||--o{ email_automations : "1:N"
    webhooks ||--o{ webhook_delivery_logs : "1:N"

    %% ==========================================
    %% 24. REPORTING & DASHBOARDS
    %% ==========================================
    report_categories {
        bigint id PK
        varchar name
        varchar slug UK
        text description
        bigint parent_id FK
        int sort_order
        timestamp created_at
        timestamp updated_at
    }
    report_definitions {
        bigint id PK
        varchar name
        varchar slug UK
        text description
        bigint category_id FK
        enum report_type "tabular|chart|pivot|summary"
        json config
        bool is_system
        bigint created_by FK
        timestamp created_at
        timestamp updated_at
    }
    report_schedules {
        bigint id PK
        bigint report_id FK
        enum frequency "daily|weekly|monthly|quarterly"
        json recipients
        enum format "pdf|csv|xlsx"
        json config
        bool is_active
        timestamp last_sent_at
        timestamp next_send_at
        bigint created_by FK
        timestamp created_at
        timestamp updated_at
    }
    dashboard_widgets {
        bigint id PK
        varchar name
        varchar slug UK
        enum widget_type "kpi|chart|table|metric"
        json config
        int width
        int height
        int refresh_interval
        bool is_system
        bool is_public
        bigint created_by FK
        timestamp created_at
        timestamp updated_at
    }

    %% ==========================================
    %% 25. PRIVACY & GDPR
    %% ==========================================
    consent_logs {
        bigint id PK
        bigint user_id FK
        varchar consent_type
        bool consent_given
        varchar ip_address
        text user_agent
        varchar consent_version
        timestamp created_at
    }
    data_export_requests {
        bigint id PK
        bigint user_id FK
        enum request_type "full|orders|account"
        enum status "pending|processing|completed|failed"
        varchar file_path
        timestamp expires_at
        timestamp processed_at
        text notes
    }
    data_deletion_requests {
        bigint id PK
        bigint user_id FK
        enum request_type "anonymize|delete"
        enum status "pending|approved|processing|completed"
        bigint approved_by FK
        timestamp processed_at
        text notes
    }
    cookie_consent_settings {
        bigint id PK
        varchar category UK
        bool required
        bool default_consent
        text description
        json cookie_names
        int retention_days
    }

    %% ==========================================
    %% 26. TAX ENGINE
    %% ==========================================
    tax_jurisdictions {
        bigint id PK
        char country_code
        varchar state
        varchar city
        varchar zip_pattern
        varchar name
        enum tax_type "state|county|city|special"
        bool is_active
        timestamp created_at
        timestamp updated_at
    }
    tax_exemptions {
        bigint id PK
        bigint user_id FK
        enum exemption_type "reseller|nonprofit|government|educational"
        varchar exemption_number
        bigint jurisdiction_id FK
        varchar certificate_url
        enum status "pending|verified|rejected|expired"
        bigint verified_by FK
        timestamp verified_at
        timestamp expires_at
    }
    tax_rules {
        bigint id PK
        bigint jurisdiction_id FK
        bigint product_category_id FK
        decimal tax_rate
        varchar tax_name
        varchar tax_code
        bool is_compound
        int priority
        timestamp effective_from
        timestamp effective_to
        bool is_active
    }
    tax_report_data {
        bigint id PK
        bigint jurisdiction_id FK
        timestamp period_start
        timestamp period_end
        decimal total_sales
        decimal total_tax_collected
        decimal total_tax_due
        enum filing_status "pending|filed|amended|overdue"
        timestamp filed_at
    }

    %% ==========================================
    %% 27. DYNAMIC PRICING
    %% ==========================================
    price_rules {
        bigint id PK
        varchar name
        varchar slug UK
        text description
        enum rule_type "discount|markup|flat_fee"
        int priority
        json conditions
        json actions
        bool is_cumulative
        bool is_active
        timestamp valid_from
        timestamp valid_to
        int max_uses
        int used_count
        bigint created_by FK
    }
    price_tiers {
        bigint id PK
        varchar name
        varchar slug UK
        text description
        enum tier_type "group|loyalty|volume|seasonal"
        json conditions
        json price_adjustment
        int priority
        bool is_active
        bigint created_by FK
    }
    price_overrides {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        bigint tier_id FK
        decimal override_price
        int min_quantity
        int max_quantity
        bool is_active
        timestamp valid_from
        timestamp valid_to
        bigint created_by FK
    }
    price_rule_audit {
        bigint id PK
        bigint price_rule_id FK
        bigint price_tier_id FK
        bigint price_override_id FK
        bigint product_id FK
        bigint user_id FK
        decimal old_price
        decimal new_price
        enum action "rule_applied|override_created|tier_assigned"
        bigint applied_by FK
        text notes
    }

    %% ==========================================
    %% 28. INTERNATIONALIZATION
    %% ==========================================
    language_packs {
        bigint id PK
        varchar locale UK
        varchar name
        varchar native_name
        enum direction "ltr|rtl"
        bool is_rtl
        varchar version
        bool is_active
        bool is_default
        bigint created_by FK
    }
    translations {
        bigint id PK
        bigint language_pack_id FK
        varchar group
        varchar key
        text value
        text plural_form
        varchar context
        bool is_auto_translated
        bool is_reviewed
        bigint updated_by FK
    }
    translation_files {
        bigint id PK
        bigint language_pack_id FK
        varchar file_path
        enum file_type "application|module|theme"
        varchar format
        varchar source_hash
        int entries_count
        bool is_synced
        timestamp last_synced_at
    }

    %% ==========================================
    %% 29. CONTENT MODERATION
    %% ==========================================
    moderation_queue {
        bigint id PK
        varchar content_type
        bigint content_id
        bigint reported_by FK
        text reason
        enum status "pending|flagged|approved|rejected|escalated"
        bigint reviewed_by FK
        timestamp reviewed_at
        text notes
    }
    moderation_reports {
        bigint id PK
        bigint queue_id FK
        bigint reporter_id FK
        enum report_type "abuse|spam|harassment|illegal|other"
        text description
        json evidence
        enum status "pending|investigating|resolved|dismissed"
        bigint resolved_by FK
        timestamp resolved_at
    }
    moderation_actions {
        bigint id PK
        bigint queue_id FK
        enum action_type "warn_user|hide_content|remove_content|suspend_user|ban_user"
        json action_detail
        bigint performed_by FK
        text notes
    }
    moderation_blocklist {
        bigint id PK
        enum block_type "email|ip|keyword|domain"
        varchar block_value
        text reason
        bool is_active
        bigint created_by FK
        timestamp expires_at
    }

    %% ==========================================
    %% 30. SYSTEM & API CONFIGURATION
    %% ==========================================
    rate_limit_rules {
        bigint id PK
        varchar name
        varchar route_pattern
        varchar method
        int max_requests
        int window_seconds
        int response_code
        varchar response_message
        bool is_active
        bigint created_by FK
    }
    rate_limit_logs {
        bigint id PK
        bigint rule_id FK
        varchar ip_address
        bigint user_id FK
        varchar identifier
        varchar url
        varchar method
        int request_count
        timestamp window_start
        bool blocked
        timestamp created_at
    }
    health_checks {
        bigint id PK
        varchar check_name
        enum check_type "tcp|http|ping|dns|mysql"
        varchar endpoint
        int expected_status
        varchar expected_pattern
        int timeout_ms
        int interval_ms
        bool is_critical
        bool is_active
        enum last_status "healthy|degraded|unhealthy"
        timestamp last_check_at
        int last_duration_ms
        int consecutive_failures
        bigint created_by FK
    }
    maintenance_windows {
        bigint id PK
        varchar title
        text description
        enum status "scheduled|active|completed|cancelled"
        timestamp scheduled_start
        timestamp scheduled_end
        timestamp actual_start
        timestamp actual_end
        json affected_services
        bool notify_users
        bigint created_by FK
    }

    %% RELATIONSHIPS - Domain 34-40
    report_categories ||--o{ report_categories : "self-ref parent"
    report_categories ||--o{ report_definitions : "1:N"
    report_definitions ||--o{ report_schedules : "1:N"
    users ||--o{ report_definitions : "1:N"
    users ||--o{ report_schedules : "1:N"
    users ||--o{ dashboard_widgets : "1:N"
    users ||--o{ consent_logs : "1:N"
    users ||--o{ data_export_requests : "1:N"
    users ||--o{ data_deletion_requests : "1:N"
    data_deletion_requests ||--o{ users : "N:1 approved_by"
    users ||--o{ tax_exemptions : "1:N"
    tax_jurisdictions ||--o{ tax_exemptions : "1:N"
    tax_exemptions ||--o{ users : "N:1 verified_by"
    tax_jurisdictions ||--o{ tax_rules : "1:N"
    product_categories ||--o{ tax_rules : "1:N"
    tax_jurisdictions ||--o{ tax_report_data : "1:N"
    users ||--o{ price_rules : "1:N"
    users ||--o{ price_tiers : "1:N"
    products ||--o{ price_overrides : "1:N"
    users ||--o{ price_overrides : "1:N"
    price_tiers ||--o{ price_overrides : "1:N"
    price_rules ||--o{ price_rule_audit : "1:N"
    users ||--o{ language_packs : "1:N"
    language_packs ||--o{ translations : "1:N"
    language_packs ||--o{ translation_files : "1:N"
    users ||--o{ moderation_queue : "1:N reported_by"
    moderation_queue ||--o{ moderation_reports : "1:N"
    moderation_queue ||--o{ moderation_actions : "1:N"
    users ||--o{ moderation_blocklist : "1:N"
    users ||--o{ rate_limit_rules : "1:N"
    rate_limit_rules ||--o{ rate_limit_logs : "1:N"
    users ||--o{ rate_limit_logs : "1:N"
    users ||--o{ health_checks : "1:N"
    users ||--o{ maintenance_windows : "1:N"








    departments ||--o{ departments : "fk_departments_parent"



    users ||--o{ departments : "fk_departments_manager"



    departments ||--|{ job_positions : "fk_job_positions_department"



    users ||--|{ employees : "fk_employees_user"



    departments ||--|{ employees : "fk_employees_department"



    job_positions ||--|{ employees : "fk_employees_position"



    employees ||--o{ employees : "fk_employees_reports"



    currencies ||--|{ employees : "fk_employees_currency"



    employees ||--|{ employee_contracts : "fk_employee_contracts_employee"



    currencies ||--|{ employee_contracts : "fk_employee_contracts_currency"



    employees ||--|{ employee_documents : "fk_employee_documents_employee"



    employees ||--|{ attendance : "fk_attendance_employee"



    employees ||--|{ leave_requests : "fk_leave_requests_employee"



    leave_types ||--|{ leave_requests : "fk_leave_requests_type"



    users ||--o{ leave_requests : "fk_leave_requests_approver"



    employees ||--|{ leave_balances : "fk_leave_balances_employee"



    leave_types ||--|{ leave_balances : "fk_leave_balances_type"



    employees ||--|{ timesheets : "fk_timesheets_employee"



    users ||--o{ timesheets : "fk_timesheets_approver"



    fiscal_years ||--|{ payroll_runs : "fk_payroll_runs_fiscal"



    account_periods ||--o{ payroll_runs : "fk_payroll_runs_period"



    users ||--o{ payroll_runs : "fk_payroll_runs_processor"



    payroll_runs ||--|{ payroll_items : "fk_payroll_items_run"



    employees ||--|{ payroll_items : "fk_payroll_items_employee"



    payroll_items ||--|{ payroll_item_details : "fk_payroll_item_details_item"



    payroll_components ||--|{ payroll_item_details : "fk_payroll_item_details_component"



```









    %% Section 17 - SMS & Communications


    sms_providers {


        bigint id PK


        varchar name


        enum provider


        varchar api_key


        varchar api_secret


        varchar from_number


        varchar api_endpoint


        json config


        bool is_active


        bool is_default


        int priority


        timestamp created_at


        timestamp updated_at


    }


    sms_templates {


        bigint id PK


        varchar name


        varchar category


        text body


        json variables


        timestamp created_at


        timestamp updated_at


    }


    sms_campaigns {


        bigint id PK


        varchar name


        text message_body


        bigint sms_template_id FK


        bigint provider_id FK


        enum target_type


        json target_roles


        json target_user_ids


        json filter_criteria


        timestamp scheduled_at


        timestamp sent_at


        timestamp completed_at


        enum status


        int total_recipients


        int success_count


        int fail_count


        bigint created_by FK


        timestamp created_at


        timestamp updated_at


    }


    sms_campaign_recipients {


        bigint id PK


        bigint campaign_id FK


        bigint user_id FK


        varchar phone


        enum status


        text error_message


        varchar provider_message_id


        bigint provider_id FK


        timestamp sent_at


        timestamp delivered_at


        timestamp created_at


    }


    sms_automations {


        bigint id PK


        varchar name


        enum trigger_type


        varchar event_name


        json event_conditions


        varchar cron_expression


        varchar timezone


        enum target_type


        json target_roles


        json filter_criteria


        text message_body


        bigint sms_template_id FK


        bigint provider_id FK


        bool is_active


        timestamp last_triggered_at


        int total_sent


        bigint created_by FK


        timestamp created_at


        timestamp updated_at


    }


    sms_logs {


        bigint id PK


        bigint provider_id FK


        bigint campaign_id FK


        bigint recipient_id FK


        enum direction


        json request_payload


        json response_payload


        int http_status


        varchar provider_message_id


        text error_message


        timestamp created_at


    }





    sms_providers ||--o{ sms_campaigns : "1:N"


    sms_providers ||--o{ sms_campaign_recipients : "1:N"


    sms_providers ||--o{ sms_automations : "1:N"


    sms_providers ||--o{ sms_logs : "1:N"


    sms_templates ||--o{ sms_campaigns : "1:N"


    sms_templates ||--o{ sms_automations : "1:N"


    sms_campaigns ||--o{ sms_campaign_recipients : "1:N"


    sms_campaigns ||--o{ sms_logs : "1:N"


    sms_campaign_recipients ||--o{ sms_logs : "1:N"


    users ||--o{ sms_campaigns : "1:N"


    users ||--o{ sms_campaign_recipients : "1:N"


    users ||--o{ sms_automations : "1:N"








## Relationship Catalog



### Domain 1: Core — Users & Auth







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 1 | `users` → `seller_profiles` | **1:1** | Each seller has a profile with store info, balance, and commission settings. PK = FK to `users.id`. |



| 2 



| 3 | `users` → `auth_logs` | **1:N** | Each authentication event is logged against the user (or NULL for anonymous). |



| 124 | `users` → `password_resets` | **1:N** | Users can request many password reset tokens. CASCADE. |







### Domain 2: Seller Marketplace







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 4 | `seller_profiles` → `payout_accounts` | **1:N** | A seller can have multiple payout accounts (bank, PayPal, crypto, etc.). CASCADE. |



| 5 | `seller_profiles` → `payout_transactions` | **1:N** | A seller has many payout transactions (commission payouts). CASCADE. |



| 6 | `seller_profiles` → `balance_ledger` | **1:N** | Every balance change is logged in the ledger for audit trail. CASCADE. |



| 7 | `seller_profiles` → `seller_verification` | **1:N** | A seller can submit multiple KYC documents for verification. CASCADE. |



| 8 



| 9 | `seller_profiles` → `seller_stats` | **1:N** | Aggregated seller performance metrics per period. CASCADE. |



| 10 | `payout_accounts` → `payout_transactions` | **1:N** | Each payout is linked to the account it was sent to. SET NULL. |



| 11 | `users` → `seller_verification` | **1:N** | Admin staff who approved/rejected verification documents. SET NULL. |



| 12 | `users` → `seller_reviews` | **1:N** | Buyers who wrote reviews. CASCADE. |



| 13 | `orders` → `seller_reviews` | **1:N** | Reviews are linked to the order that triggered them. SET NULL. |







### Domain 3: Products & Subscriptions







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 14 | `users` → `products` | **1:N** | A seller can list many products. `seller_id` is nullable (admin products). |



| 15 | `products` → `product_hardware_requirements` | **1:N** | A desktop product can have multiple OS-specific hardware requirement rows. |



| 16 | `users` → `user_subscriptions` | **1:N** | A user can have multiple subscriptions (active + historical). |



| 17 | `subscription_plans` → `user_subscriptions` | **1:N** | Each subscription references a plan (Starter, Pro, Enterprise). |



| 18 | `products` → `user_subscriptions` | **1:N** | A subscription can be tied to a specific product. |



| 125 | `users` → `wishlists` | **1:N** | Users can add products to their wishlist. CASCADE. |



| 126 | `products` → `wishlists` | **1:N** | Products can be wishlisted by many users. CASCADE. |



| 127 | `product_categories` → `product_categories` | **1:N** | Self-referencing parent category hierarchy via `parent_id`. SET NULL. |



| 128 | `products` ↔ `product_categories` | **M:N** | Many-to-many via `product_category_items` pivot. CASCADE. |



| 129 | `products` → `product_discounts` | **1:N** | Products can have time-limited discount offers. CASCADE. |







### Domain 4: Billing







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 19 | `users` → `invoices` | **1:N** | Invoices belong to a user. |



| 20 | `orders` → `invoices` | **1:N** | Invoices are derived from orders (via `order_id` FK). |



| 21 | `user_subscriptions` → `invoices` | **1:N** | An invoice is generated from a subscription. |



| 22 | `invoices` → `payments` | **1:N** | An invoice can have multiple payment attempts (partials, retries). |







### Domain 5: Orders & Commerce







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 23 | `users` → `carts` | **1:N** | A user can have one active shopping cart. |



| 24 | `carts` → `cart_items` | **1:N** | A cart contains many line items (CASCADE). |



| 25 | `products` → `cart_items` | **1:N** | Cart items reference a product at the time of adding. |



| 26 | `subscription_plans` → `cart_items` | **1:N** | Cart items may reference a subscription plan. |



| 27 | `coupons` → `carts` | **1:N** | A coupon can be applied to a cart. |



| 28 | `users` → `user_addresses` | **1:N** | Users can save multiple billing/shipping addresses. |



| 29 | `users` → `orders` | **1:N** | Users place orders. |



| 30 | `orders` → `order_items` | **1:N** | An order contains snapshotted line items (CASCADE). |



| 31 | `products` → `order_items` | **1:N** | Order items reference the original product (SET NULL safe). |



| 32 | `subscription_plans` → `order_items` | **1:N** | Order items may reference a subscription plan. |



| 33 | `order_items` → `order_item_metadata` | **1:N** | Per-order-item key-value metadata (license key, activation details, notes). CASCADE. |



| 34 | `licenses` → `order_item_metadata` | **1:N** | Metadata can optionally reference the generated license. SET NULL. |



| 35 | `user_addresses` → `orders` (billing) | **1:N** | Billing address assigned to order. |



| 36 | `user_addresses` → `orders` (shipping) | **1:N** | Shipping address assigned to order (nullable). |



| 37 | `coupons` → `orders` | **1:N** | A coupon applied at order time. |



| 38 | `orders` → `order_status_history` | **1:N** | Full audit trail of all status transitions (CASCADE). |



| 39 | `users` → `order_status_history` | **1:N** | Tracks who changed the order status. |



| 40 | `orders` → `refunds` | **1:N** | An order can have refund requests. |



| 41 | `payments` → `refunds` | **1:N** | Refunds are linked to the original payment. |



| 42 | `users` → `refunds` | **1:N** | Tracks who processed the refund. |



| 43 | `api_clients` → `orders` | **1:N** | Orders can be placed programmatically via an API client. |



| 44 | `api_clients` → `order_status_history` | **1:N** | API clients can trigger order status changes. |



| 130 | `orders` → `shipments` | **1:N** | Orders can have shipment tracking records. CASCADE. |



| 131 | `order_items` → `file_downloads` | **1:N** | Each order item tracks file download attempts. CASCADE. |



| 132 | `users` → `file_downloads` | **1:N** | Users download files associated with their orders. CASCADE. |







### Domain 6: Licensing (Core Domain)







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 45 | `users` → `api_clients` | **1:N** | Users register API clients for programmatic access. |



| 46 | `users` → `licenses` | **1:N** | License keys are issued to users. |



| 47 | `products` → `licenses` | **1:N** | Each license is bound to a product. |



| 48 | `api_clients` → `licenses` | **1:N** | Licenses can be associated with an API client. |



| 49 | `user_subscriptions` → `licenses` | **1:N** | A subscription generates licenses. |



| 50 | `licenses` → `license_activations` | **1:N** | A license can be activated on many domains/IPs. |



| 51 | `licenses` → `hardware_activations` | **1:N** | Desktop licenses are locked to specific hardware machines. |



| 52 | `licenses` → `hardware_activation_logs` | **1:N** | History of all hardware activation attempts. |



| 53 | `hardware_activations` → `hardware_activation_logs` | **1:N** | Each activation record has activity logs. |







### Domain 7: Verification & Security







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 54 | `license_activations` → `verification_logs` | **1:N** | Every verification check (5-tier) is logged per activation. |



| 55 | `licenses` → `fraud_logs` | **1:N** | Fraud incidents are tied to a license. |



| 56 | `license_activations` → `fraud_logs` | **1:N** | Fraud can also reference a specific activation attempt. |



| 133 | `users` → `user_2fa` | **1:N** | Users can configure two-factor authentication methods. CASCADE. |







### Domain 8: Support & Chat







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 57 | `users` → `tickets` | **1:N** | Users submit support tickets. |



| 58 | `tickets` → `ticket_messages` | **1:N** | A ticket has many messages (CASCADE delete). |



| 59 | `users` → `ticket_messages` | **1:N** | Messages are sent by users or support agents (via `sender_id`). |



| 60 | `users` → `chat_sessions` | **1:N** | Live chat sessions belong to a user. |



| 61 | `users` → `chat_sessions` (assigned) | **1:N** | Support agents can be assigned to sessions (`assigned_to`). |



| 62 | `chat_sessions` → `chat_messages` | **1:N** | Each session contains many messages (CASCADE). |



| 63 | `users` → `chat_messages` | **1:N** | Messages have a sender. |



| 64 | `users` → `bot_conversations` | **1:N** | Bot interactions are per user. |



| 65 | `bot_configs` → `bot_conversations` | **1:N** | Each bot config has many conversation logs logged against it (via `bot_config_id` FK). |







### Domain 9: CMS







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 66 | `users` → `posts` | **1:N** | Authors write posts (both blog and CMS). |



| 67 | `cms_categories` → `posts` | **1:N** | Posts belong to a category. |



| 68 | `posts` ↔ `cms_tags` | **M:N** | Many-to-many via `post_tags` pivot table. |



| 69 | `cms_menus` → `cms_menu_items` | **1:N** | A menu contains many items (CASCADE). |



| 134 | `users` → `cms_pages` | **1:N** | Users can be assigned as page authors. SET NULL. |







### Domain 10: Themes







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 70 | `themes` → `theme_settings` | **1:N** | Each theme has many settings key-value pairs. |



| 71 | `themes` → `theme_generations` | **1:N** | A theme can have multiple AI/user generation attempts. |



| 72 | `users` → `theme_generations` | **1:N** | Users trigger theme generation jobs. |







### Domain 11: LLM Integration







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 73 | `llm_providers` → `llm_provider_logs` | **1:N** | Each provider handles many prompt/response pairs. |



| 74 | `users` → `llm_provider_logs` | **1:N** | Users submit prompts to LLM providers. |



| 75 | `llm_providers` → `bot_configs` | **1:N** | An LLM provider can be used by many bot configs as their AI backend. |







### Domain 12: Security & Compliance







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 76 | `users` → `security_events` | **1:N** | Security events are optionally linked to users. |



| 77 | `users` → `audit_trails` | **1:N** | Audit trail entries track user actions on entities. |







### Domain 13: Notifications & Logs







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 78 | `users` → `notifications` | **1:N** | Users receive in-app notifications (CASCADE). |



| 79 | `orders` → `api_request_logs` | **1:N** | API request logs can be traced back to the resulting order. |



| 80 | `api_clients` → `api_request_logs` | **1:N** | API request logs are per client. |







### Domain 14: Application & Content







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 81 | `users` → `posts` (blog type) | **1:N** | Authors write blog posts. |



| 82 | `users` → `user_guides` | **1:N** | Authors write user guide documents. |







### Domain 15: Extended Theme Relationships







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 83 | `themes` → `theme_assets` | **1:N** | A theme bundles CSS, JS, image, and font assets. |



| 84 | `themes` → `theme_customizations` | **1:N** | Users can customize theme settings (key-value per theme). |



| 85 | `users` → `theme_customizations` | **1:N** | Tracks who made the customizations. |



| 86 | `themes` → `theme_usage_logs` | **1:N** | Every theme activation/deactivation is logged. |



| 87 | `users` → `theme_usage_logs` | **1:N** | Tracks which user triggered the action. |



| 88 | `themes` → `theme_update_logs` | **1:N** | Version update history per theme. |



| 89 | `themes` → `theme_conflicts` | **1:N** | Records incompatibilities with other themes/plugins. |



| 90 | `theme_generations` → `theme_generation_logs` | **1:N** | Log entries for each AI generation attempt. |








### Domain 16: Commerce & Platform Enhancements

| # | Relationship | Type | Description |
|---|-------------|------|-------------|
| 1 | `fk_carts_user` | `carts.user_id` → `users.id` | ON DELETE CASCADE |
| 2 | `fk_carts_coupon` | `carts.coupon_id` → `coupons.id` | ON DELETE SET NULL |
| 3 | `fk_cart_items_cart` | `cart_items.cart_id` → `carts.id` | ON DELETE CASCADE |
| 4 | `fk_cart_items_product` | `cart_items.product_id` → `products.id` | ON DELETE SET NULL |
| 5 | `fk_cart_items_plan` | `cart_items.plan_id` → `subscription_plans.id` | ON DELETE SET NULL |
| 6 | `fk_coupons_creator` | `coupons.created_by` → `users.id` | ON DELETE CASCADE |
| 7 | `fk_wishlists_user` | `wishlists.user_id` → `users.id` | ON DELETE CASCADE |
| 8 | `fk_wishlist_items_wishlist` | `wishlist_items.wishlist_id` → `wishlists.id` | ON DELETE CASCADE |
| 9 | `fk_wishlist_items_product` | `wishlist_items.product_id` → `products.id` | ON DELETE CASCADE |
| 10 
| 11 
| 12 
| 13 | `fk_refunds_order` | `refunds.order_id` → `orders.id` | ON DELETE CASCADE |
| 14 | `fk_refunds_user` | `refunds.user_id` → `users.id` | ON DELETE CASCADE |
| 15 | `fk_refunds_order_item` | `refunds.order_item_id` → `order_items.id` | ON DELETE SET NULL |
| 16 | `fk_refunds_processor` | `refunds.processed_by` → `users.id` | ON DELETE SET NULL |
| 17 | `fk_return_requests_order` | `return_requests.order_id` → `orders.id` | ON DELETE CASCADE |
| 18 | `fk_return_requests_user` | `return_requests.user_id` → `users.id` | ON DELETE CASCADE |
| 19 | `fk_return_requests_order_item` | `return_requests.order_item_id` → `order_items.id` | ON DELETE SET NULL |
| 20 | `fk_return_requests_processor` | `return_requests.processed_by` → `users.id` | ON DELETE SET NULL |
| 21 | `fk_user_addresses_user` | `user_addresses.user_id` → `users.id` | ON DELETE CASCADE |
| 22 | `fk_user_payment_methods_user` | `user_payment_methods.user_id` → `users.id` | ON DELETE CASCADE |
| 23 | `fk_user_payment_methods_gateway` | `user_payment_methods.gateway_id` → `payment_gateways.id` | ON DELETE SET NULL |
| 24 | `fk_personal_access_tokens_user` | `personal_access_tokens.user_id` → `users.id` | ON DELETE CASCADE |
| 25 | `fk_sessions_user` | `sessions.user_id` → `users.id` | ON DELETE CASCADE |
| 26 | `fk_social_accounts_user` | `social_accounts.user_id` → `users.id` | ON DELETE CASCADE |
| 27 | `fk_user_devices_user` | `user_devices.user_id` → `users.id` | ON DELETE CASCADE |



### Domain 17: SMS & Communications

| # | Relationship | Type | Description |
|---|-------------|------|-------------|
| 1 | `fk_sms_campaigns_template` | `sms_campaigns.sms_template_id` → `sms_templates.id` | ON DELETE SET NULL |
| 2 | `fk_sms_campaigns_provider` | `sms_campaigns.provider_id` → `sms_providers.id` | ON DELETE SET NULL |
| 3 | `fk_sms_campaigns_creator` | `sms_campaigns.created_by` → `users.id` | ON DELETE CASCADE |
| 4 | `fk_sms_recipients_campaign` | `sms_campaign_recipients.campaign_id` → `sms_campaigns.id` | ON DELETE CASCADE |
| 5 | `fk_sms_recipients_user` | `sms_campaign_recipients.user_id` → `users.id` | ON DELETE SET NULL |
| 6 | `fk_sms_recipients_provider` | `sms_campaign_recipients.provider_id` → `sms_providers.id` | ON DELETE SET NULL |
| 7 | `fk_sms_automations_template` | `sms_automations.sms_template_id` → `sms_templates.id` | ON DELETE SET NULL |
| 8 | `fk_sms_automations_provider` | `sms_automations.provider_id` → `sms_providers.id` | ON DELETE SET NULL |
| 9 | `fk_sms_automations_creator` | `sms_automations.created_by` → `users.id` | ON DELETE CASCADE |
| 10 | `fk_sms_logs_provider` | `sms_logs.provider_id` → `sms_providers.id` | ON DELETE SET NULL |
| 11 | `fk_sms_logs_campaign` | `sms_logs.campaign_id` → `sms_campaigns.id` | ON DELETE SET NULL |
| 12 | `fk_sms_logs_recipient` | `sms_logs.recipient_id` → `sms_campaign_recipients.id` | ON DELETE SET NULL |

### Domain 18: Extended LLM Relationships







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 91 | `llm_providers` → `llm_provider_settings` | **1:N** | Provider-specific configuration key-value pairs. |



| 92 | `llm_providers` → `llm_provider_activity` | **1:N** | Activity logs per LLM provider. |



| 93 | `users` → `llm_provider_activity` | **1:N** | Users trigger LLM provider actions. |



| 94 | `llm_providers` → `llm_provider_usage` | **1:N** | Usage metrics per provider (requests, tokens, cost). |



| 95 | `users` → `llm_provider_usage` | **1:N** | Usage tracked per user. |







### Domain 19: Extended Security & System







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 96 



| 97 



| 98 | `payment_gateways` → `payment_gateway_settings` | **1:N** | Gateway-specific configuration per gateway. |







### Domain 20: Content Features







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 99 | `posts` → `post_comments` | **1:N** | Posts have many comments (CASCADE). |



| 100 | `users` → `post_comments` | **1:N** | Registered users leave comments (SET NULL for guests). |



| 101 | `post_comments` → `post_comments` | **1:N** | Threaded/nested replies via `parent_id` self-FK. |



| 102 | `posts` → `post_reactions` | **1:N** | Users react to posts (like, love, laugh, etc.). |



| 103 | `users` → `post_reactions` | **1:N** | Each reaction is tied to a user (CASCADE). |



| 104 | `posts` → `post_views` | **1:N** | View tracking per post. |



| 105 | `posts` → `post_media` | **1:N** | Media attachments per post (images, PDFs, etc.). |



| 106 | `post_series` → `post_series_items` | **1:N** | A series contains many posts in order. |



| 107 | `posts` → `post_series_items` | **1:N** | Posts can belong to a series. |



| 108 | `posts` ↔ `posts` | **M:N** | Related/cross-reference posts via `related_posts` table. |



| 109 | `users` → `author_profiles` | **1:1** | Each user can have an optional author profile. |



| 110 | `users` → `post_series` | **1:N** | Authors create post series/collections. |







### Domain 21: Video Gallery & Guidelines







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 111 | `users` → `video_galleries` | **1:N** | Authors create video gallery collections. |



| 112 | `video_galleries` → `videos` | **1:N** | A gallery contains many videos (SET NULL on delete). |



| 113 | `users` → `videos` | **1:N** | Users upload and manage videos. |



| 114 | `videos` ↔ `cms_tags` | **M:N** | Many-to-many via `video_tags` pivot table. |



| 115 | `videos` → `video_guidelines` | **1:N** | Step-by-step guideline annotations per video (CASCADE). |







### Domain 22: SEO & Analytics







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 116 | `users` → `analytics_events` | **1:N** | Registered users tracked in analytics events (SET NULL for guests). |



| 117 | `posts` → `analytics_events` | **1:N** | Pageview and event tracking per post. |



| 118 | `cms_pages` → `analytics_events` | **1:N** | Pageview and event tracking per CMS page. |







### Domain 23: Media & Content Revisions







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 119 | `users` → `media_library` | **1:N** | Users upload files to the shared media library. |



| 120 | `users` → `content_revisions` | **1:N** | Users create content revision snapshots. |







### Domain 24: Forms & Page Tagging







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 121 | `cms_pages` → `cms_page_tags` | **1:N** | CMS pages can have multiple tags via pivot. |



| 122 | `cms_tags` → `cms_page_tags` | **1:N** | Tags can be assigned to multiple CMS pages. |



| 123 | `users` → `form_submissions` | **1:N** | Registered users submit forms (SET NULL for guests). |







### Domain 25: RBAC







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 135 | `roles` ↔ `permissions` | **M:N** | Many-to-many via `role_permissions` pivot. CASCADE. |



| 136 | `users` ↔ `roles` | **M:N** | Users can have multiple roles via `user_roles` pivot. CASCADE. |







### Domain 26: Organizations







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 137 | `organizations` ↔ `users` | **M:N** | Many-to-many via `organization_members` pivot with role discriminator. CASCADE. |







### Domain 27: Affiliates & Referrals







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| 138 | `users` → `affiliates` | **1:N** | Users can become affiliates with a unique referral code. CASCADE. |



| 139 | `affiliates` → `referrals` | **1:N** | Affiliates refer users who place orders. CASCADE. |



| 140 | `users` → `referrals` | **1:N** | Referred users are linked to the referral record. CASCADE. |



| 141 | `orders` → `referrals` | **1:N** | Referral commissions are tied to the originating order. SET NULL. |







### Domain 28: Financial / Accounting (GL)







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| `fk_exchange_rates_from` | `exchange_rates`.`from_currency_id` | `currencies`.`id` | ON DELETE CASCADE |



| `fk_exchange_rates_to` | `exchange_rates`.`to_currency_id` | `currencies`.`id` | ON DELETE CASCADE |



| `fk_account_periods_fiscal` | `account_periods`.`fiscal_year_id` | `fiscal_years`.`id` | ON DELETE CASCADE |



| `fk_chart_of_accounts_parent` | `chart_of_accounts`.`parent_id` | `chart_of_accounts`.`id` | ON DELETE SET |



| `fk_journal_entries_type` | `journal_entries`.`entry_type_id` | `journal_entry_types`.`id` | ON DELETE RESTRICT |



| `fk_journal_entries_fiscal` | `journal_entries`.`fiscal_year_id` | `fiscal_years`.`id` | ON DELETE RESTRICT |



| `fk_journal_entries_period` | `journal_entries`.`account_period_id` | `account_periods`.`id` | ON DELETE SET |



| `fk_journal_entries_creator` | `journal_entries`.`created_by` | `users`.`id` | ON DELETE SET |



| `fk_journal_entry_lines_entry` | `journal_entry_lines`.`journal_entry_id` | `journal_entries`.`id` | ON DELETE CASCADE |



| `fk_journal_entry_lines_account` | `journal_entry_lines`.`account_id` | `chart_of_accounts`.`id` | ON DELETE RESTRICT |



| `fk_journal_entry_lines_cost` | `journal_entry_lines`.`cost_center_id` | `cost_centers`.`id` | ON DELETE SET |



| `fk_journal_entry_lines_profit` | `journal_entry_lines`.`profit_center_id` | `profit_centers`.`id` | ON DELETE SET |



| `fk_account_balances_account` | `account_balances`.`account_id` | `chart_of_accounts`.`id` | ON DELETE CASCADE |



| `fk_account_balances_fiscal` | `account_balances`.`fiscal_year_id` | `fiscal_years`.`id` | ON DELETE CASCADE |



| `fk_account_balances_period` | `account_balances`.`account_period_id` | `account_periods`.`id` | ON DELETE CASCADE |



| `fk_budgets_fiscal` | `budgets`.`fiscal_year_id` | `fiscal_years`.`id` | ON DELETE CASCADE |



| `fk_budgets_profit` | `budgets`.`profit_center_id` | `profit_centers`.`id` | ON DELETE SET |



| `fk_budgets_cost` | `budgets`.`cost_center_id` | `cost_centers`.`id` | ON DELETE SET |



| `fk_budget_lines_budget` | `budget_lines`.`budget_id` | `budgets`.`id` | ON DELETE CASCADE |



| `fk_budget_lines_account` | `budget_lines`.`account_id` | `chart_of_accounts`.`id` | ON DELETE RESTRICT |



| `fk_budget_lines_period` | `budget_lines`.`period_id` | `account_periods`.`id` | ON DELETE CASCADE |



| `fk_budget_versions_budget` | `budget_versions`.`budget_id` | `budgets`.`id` | ON DELETE CASCADE |



| `fk_budget_versions_creator` | `budget_versions`.`created_by` | `users`.`id` | ON DELETE SET |



| `fk_cost_allocations_source` | `cost_allocations`.`source_cost_center_id` | `cost_centers`.`id` | ON DELETE CASCADE |



| `fk_cost_allocations_target` | `cost_allocations`.`target_cost_center_id` | `cost_centers`.`id` | ON DELETE CASCADE |



| `fk_cost_allocations_account` | `cost_allocations`.`account_id` | `chart_of_accounts`.`id` | ON DELETE RESTRICT |











### Domain 29: Inventory & Warehouse







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| `fk_warehouse_locations_warehouse` | `warehouse_locations`.`warehouse_id` | `warehouses`.`id` | ON DELETE CASCADE |



| `fk_warehouse_locations_parent` | `warehouse_locations`.`parent_id` | `warehouse_locations`.`id` | ON DELETE SET |



| `fk_stock_items_product` | `stock_items`.`product_id` | `products`.`id` | ON DELETE CASCADE |



| `fk_stock_items_location` | `stock_items`.`warehouse_location_id` | `warehouse_locations`.`id` | ON DELETE RESTRICT |



| `fk_inventory_movements_product` | `inventory_movements`.`product_id` | `products`.`id` | ON DELETE CASCADE |



| `fk_inventory_movements_from` | `inventory_movements`.`from_location_id` | `warehouse_locations`.`id` | ON DELETE SET |



| `fk_inventory_movements_to` | `inventory_movements`.`to_location_id` | `warehouse_locations`.`id` | ON DELETE SET |



| `fk_inventory_movements_stock` | `inventory_movements`.`stock_item_id` | `stock_items`.`id` | ON DELETE SET |



| `fk_inventory_movements_creator` | `inventory_movements`.`created_by` | `users`.`id` | ON DELETE SET |



| `fk_inventory_adjustments_product` | `inventory_adjustments`.`product_id` | `products`.`id` | ON DELETE CASCADE |



| `fk_inventory_adjustments_location` | `inventory_adjustments`.`warehouse_location_id` | `warehouse_locations`.`id` | ON DELETE CASCADE |



| `fk_inventory_adjustments_approver` | `inventory_adjustments`.`approved_by` | `users`.`id` | ON DELETE SET |



| `fk_stock_counts_warehouse` | `stock_counts`.`warehouse_id` | `warehouses`.`id` | ON DELETE CASCADE |



| `fk_stock_counts_counter` | `stock_counts`.`counted_by` | `users`.`id` | ON DELETE SET |



| `fk_stock_counts_verifier` | `stock_counts`.`verified_by` | `users`.`id` | ON DELETE SET |



| `fk_stock_count_items_count` | `stock_count_items`.`stock_count_id` | `stock_counts`.`id` | ON DELETE CASCADE |



| `fk_stock_count_items_product` | `stock_count_items`.`product_id` | `products`.`id` | ON DELETE CASCADE |



| `fk_stock_count_items_location` | `stock_count_items`.`location_id` | `warehouse_locations`.`id` | ON DELETE CASCADE |



| `fk_reorder_rules_product` | `reorder_rules`.`product_id` | `products`.`id` | ON DELETE CASCADE |



| `fk_reorder_rules_warehouse` | `reorder_rules`.`warehouse_id` | `warehouses`.`id` | ON DELETE CASCADE |



| `fk_transfer_orders_from` | `transfer_orders`.`from_warehouse_id` | `warehouses`.`id` | ON DELETE RESTRICT |



| `fk_transfer_orders_to` | `transfer_orders`.`to_warehouse_id` | `warehouses`.`id` | ON DELETE RESTRICT |



| `fk_transfer_orders_requestor` | `transfer_orders`.`requested_by` | `users`.`id` | ON DELETE SET |



| `fk_transfer_orders_approver` | `transfer_orders`.`approved_by` | `users`.`id` | ON DELETE SET |



| `fk_transfer_order_items_order` | `transfer_order_items`.`transfer_order_id` | `transfer_orders`.`id` | ON DELETE CASCADE |



| `fk_transfer_order_items_product` | `transfer_order_items`.`product_id` | `products`.`id` | ON DELETE CASCADE |



| `fk_transfer_order_items_stock` | `transfer_order_items`.`stock_item_id` | `stock_items`.`id` | ON DELETE SET |











### Domain 30: Procurement







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| `fk_suppliers_currency` | `suppliers`.`currency_id` | `currencies`.`id` | ON DELETE SET |



| `fk_supplier_contacts_supplier` | `supplier_contacts`.`supplier_id` | `suppliers`.`id` | ON DELETE CASCADE |



| `fk_supplier_products_supplier` | `supplier_products`.`supplier_id` | `suppliers`.`id` | ON DELETE CASCADE |



| `fk_supplier_products_product` | `supplier_products`.`product_id` | `products`.`id` | ON DELETE CASCADE |



| `fk_supplier_pricelists_product` | `supplier_pricelists`.`supplier_product_id` | `supplier_products`.`id` | ON DELETE CASCADE |



| `fk_supplier_pricelists_currency` | `supplier_pricelists`.`currency_id` | `currencies`.`id` | ON DELETE RESTRICT |



| `fk_purchase_orders_supplier` | `purchase_orders`.`supplier_id` | `suppliers`.`id` | ON DELETE RESTRICT |



| `fk_purchase_orders_currency` | `purchase_orders`.`currency_id` | `currencies`.`id` | ON DELETE RESTRICT |



| `fk_purchase_orders_requestor` | `purchase_orders`.`requested_by` | `users`.`id` | ON DELETE SET |



| `fk_purchase_orders_approver` | `purchase_orders`.`approved_by` | `users`.`id` | ON DELETE SET |



| `fk_po_items_order` | `purchase_order_items`.`purchase_order_id` | `purchase_orders`.`id` | ON DELETE CASCADE |



| `fk_po_items_product` | `purchase_order_items`.`product_id` | `products`.`id` | ON DELETE RESTRICT |



| `fk_po_items_location` | `purchase_order_items`.`warehouse_location_id` | `warehouse_locations`.`id` | ON DELETE SET |



| `fk_purchase_receipts_order` | `purchase_receipts`.`purchase_order_id` | `purchase_orders`.`id` | ON DELETE CASCADE |



| `fk_purchase_receipts_receiver` | `purchase_receipts`.`received_by` | `users`.`id` | ON DELETE SET |



| `fk_pr_items_receipt` | `purchase_receipt_items`.`purchase_receipt_id` | `purchase_receipts`.`id` | ON DELETE CASCADE |



| `fk_pr_items_po_item` | `purchase_receipt_items`.`po_item_id` | `purchase_order_items`.`id` | ON DELETE CASCADE |



| `fk_pr_items_product` | `purchase_receipt_items`.`product_id` | `products`.`id` | ON DELETE RESTRICT |



| `fk_pr_items_location` | `purchase_receipt_items`.`warehouse_location_id` | `warehouse_locations`.`id` | ON DELETE RESTRICT |



| `fk_purchase_invoices_order` | `purchase_invoices`.`purchase_order_id` | `purchase_orders`.`id` | ON DELETE CASCADE |



| `fk_purchase_invoices_supplier` | `purchase_invoices`.`supplier_id` | `suppliers`.`id` | ON DELETE RESTRICT |



| `fk_purchase_invoices_currency` | `purchase_invoices`.`currency_id` | `currencies`.`id` | ON DELETE RESTRICT |



| `fk_pi_items_invoice` | `purchase_invoice_items`.`purchase_invoice_id` | `purchase_invoices`.`id` | ON DELETE CASCADE |



| `fk_pi_items_po_item` | `purchase_invoice_items`.`po_item_id` | `purchase_order_items`.`id` | ON DELETE CASCADE |



| `fk_pi_items_product` | `purchase_invoice_items`.`product_id` | `products`.`id` | ON DELETE RESTRICT |



| `fk_rfqs_creator` | `rfqs`.`created_by` | `users`.`id` | ON DELETE SET |



| `fk_rfq_items_rfq` | `rfq_items`.`rfq_id` | `rfqs`.`id` | ON DELETE CASCADE |



| `fk_rfq_items_product` | `rfq_items`.`product_id` | `products`.`id` | ON DELETE CASCADE |



| `fk_supplier_quotations_rfq` | `supplier_quotations`.`rfq_id` | `rfqs`.`id` | ON DELETE CASCADE |



| `fk_supplier_quotations_supplier` | `supplier_quotations`.`supplier_id` | `suppliers`.`id` | ON DELETE RESTRICT |



| `fk_supplier_quotations_currency` | `supplier_quotations`.`currency_id` | `currencies`.`id` | ON DELETE RESTRICT |



| `fk_quotation_items_quotation` | `quotation_items`.`quotation_id` | `supplier_quotations`.`id` | ON DELETE CASCADE |



| `fk_quotation_items_rfq_item` | `quotation_items`.`rfq_item_id` | `rfq_items`.`id` | ON DELETE CASCADE |



| `fk_quotation_items_prod` | `quotation_items`.`product_id` | `products`.`id` | ON DELETE RESTRICT |











### Domain 31: Production & CRP







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| `fk_wc_capacity_center` | `work_center_capacity`.`work_center_id` | `work_centers`.`id` | ON DELETE CASCADE |



| `fk_bom_product` | `bill_of_materials`.`product_id` | `products`.`id` | ON DELETE CASCADE |



| `fk_bom_items_bom` | `bom_items`.`bom_id` | `bill_of_materials`.`id` | ON DELETE CASCADE |



| `fk_bom_items_component` | `bom_items`.`component_id` | `products`.`id` | ON DELETE RESTRICT |



| `fk_routings_bom` | `routings`.`bom_id` | `bill_of_materials`.`id` | ON DELETE CASCADE |



| `fk_routing_steps_routing` | `routing_steps`.`routing_id` | `routings`.`id` | ON DELETE CASCADE |



| `fk_routing_steps_center` | `routing_steps`.`work_center_id` | `work_centers`.`id` | ON DELETE RESTRICT |



| `fk_production_orders_product` | `production_orders`.`product_id` | `products`.`id` | ON DELETE RESTRICT |



| `fk_production_orders_bom` | `production_orders`.`bom_id` | `bill_of_materials`.`id` | ON DELETE RESTRICT |



| `fk_production_orders_routing` | `production_orders`.`routing_id` | `routings`.`id` | ON DELETE SET |



| `fk_production_orders_warehouse` | `production_orders`.`warehouse_id` | `warehouses`.`id` | ON DELETE SET |



| `fk_production_orders_creator` | `production_orders`.`created_by` | `users`.`id` | ON DELETE SET |



| `fk_prod_order_steps_order` | `production_order_steps`.`production_order_id` | `production_orders`.`id` | ON DELETE CASCADE |



| `fk_prod_order_steps_step` | `production_order_steps`.`routing_step_id` | `routing_steps`.`id` | ON DELETE RESTRICT |



| `fk_prod_order_steps_center` | `production_order_steps`.`work_center_id` | `work_centers`.`id` | ON DELETE RESTRICT |



| `fk_production_outputs_order` | `production_outputs`.`production_order_id` | `production_orders`.`id` | ON DELETE CASCADE |



| `fk_production_outputs_product` | `production_outputs`.`product_id` | `products`.`id` | ON DELETE RESTRICT |



| `fk_production_outputs_location` | `production_outputs`.`warehouse_location_id` | `warehouse_locations`.`id` | ON DELETE RESTRICT |



| `fk_prod_mat_issues_order` | `production_material_issues`.`production_order_id` | `production_orders`.`id` | ON DELETE CASCADE |



| `fk_prod_mat_issues_stock` | `production_material_issues`.`stock_item_id` | `stock_items`.`id` | ON DELETE SET |



| `fk_prod_mat_issues_product` | `production_material_issues`.`product_id` | `products`.`id` | ON DELETE RESTRICT |



| `fk_prod_mat_issues_location` | `production_material_issues`.`warehouse_location_id` | `warehouse_locations`.`id` | ON DELETE RESTRICT |



| `fk_capacity_plans_center` | `capacity_plans`.`work_center_id` | `work_centers`.`id` | ON DELETE CASCADE |



| `fk_maintenance_schedules_center` | `maintenance_schedules`.`work_center_id` | `work_centers`.`id` | ON DELETE CASCADE |



| `fk_maintenance_logs_schedule` | `maintenance_logs`.`maintenance_schedule_id` | `maintenance_schedules`.`id` | ON DELETE SET |



| `fk_maintenance_logs_center` | `maintenance_logs`.`work_center_id` | `work_centers`.`id` | ON DELETE CASCADE |



| `fk_maintenance_logs_operator` | `maintenance_logs`.`performed_by` | `users`.`id` | ON DELETE SET |











### Domain 32: Human Resources & Payroll







| # | Relationship | Type | Description |



|---|-------------|------|-------------|



| `fk_departments_parent` | `departments`.`parent_id` | `departments`.`id` | ON DELETE SET |



| `fk_departments_manager` | `departments`.`manager_id` | `users`.`id` | ON DELETE SET |



| `fk_job_positions_department` | `job_positions`.`department_id` | `departments`.`id` | ON DELETE CASCADE |



| `fk_employees_user` | `employees`.`user_id` | `users`.`id` | ON DELETE CASCADE |



| `fk_employees_department` | `employees`.`department_id` | `departments`.`id` | ON DELETE RESTRICT |



| `fk_employees_position` | `employees`.`job_position_id` | `job_positions`.`id` | ON DELETE RESTRICT |



| `fk_employees_reports` | `employees`.`reports_to` | `employees`.`id` | ON DELETE SET |



| `fk_employees_currency` | `employees`.`currency_id` | `currencies`.`id` | ON DELETE RESTRICT |



| `fk_employee_contracts_employee` | `employee_contracts`.`employee_id` | `employees`.`id` | ON DELETE CASCADE |



| `fk_employee_contracts_currency` | `employee_contracts`.`currency_id` | `currencies`.`id` | ON DELETE RESTRICT |



| `fk_employee_documents_employee` | `employee_documents`.`employee_id` | `employees`.`id` | ON DELETE CASCADE |



| `fk_attendance_employee` | `attendance`.`employee_id` | `employees`.`id` | ON DELETE CASCADE |



| `fk_leave_requests_employee` | `leave_requests`.`employee_id` | `employees`.`id` | ON DELETE CASCADE |



| `fk_leave_requests_type` | `leave_requests`.`leave_type_id` | `leave_types`.`id` | ON DELETE RESTRICT |



| `fk_leave_requests_approver` | `leave_requests`.`approved_by` | `users`.`id` | ON DELETE SET |



| `fk_leave_balances_employee` | `leave_balances`.`employee_id` | `employees`.`id` | ON DELETE CASCADE |



| `fk_leave_balances_type` | `leave_balances`.`leave_type_id` | `leave_types`.`id` | ON DELETE RESTRICT |



| `fk_timesheets_employee` | `timesheets`.`employee_id` | `employees`.`id` | ON DELETE CASCADE |



| `fk_timesheets_approver` | `timesheets`.`approved_by` | `users`.`id` | ON DELETE SET |



| `fk_payroll_runs_fiscal` | `payroll_runs`.`fiscal_year_id` | `fiscal_years`.`id` | ON DELETE RESTRICT |



| `fk_payroll_runs_period` | `payroll_runs`.`account_period_id` | `account_periods`.`id` | ON DELETE SET |



| `fk_payroll_runs_processor` | `payroll_runs`.`processed_by` | `users`.`id` | ON DELETE SET |



| `fk_payroll_items_run` | `payroll_items`.`payroll_run_id` | `payroll_runs`.`id` | ON DELETE CASCADE |



| `fk_payroll_items_employee` | `payroll_items`.`employee_id` | `employees`.`id` | ON DELETE RESTRICT |



| `fk_payroll_item_details_item` | `payroll_item_details`.`payroll_item_id` | `payroll_items`.`id` | ON DELETE CASCADE |



| `fk_payroll_item_details_component` | `payroll_item_details`.`payroll_component_id` | `payroll_components`.`id` | ON DELETE RESTRICT |







---












### Domain 33: Automation & Workflow Engine

| # | Relationship | Type | Description |
|---|-------------|------|-------------|
| 1 | `fk_wf_nodes_workflow` | `workflow_nodes.workflow_id` → `workflow_definitions.id` | ON DELETE CASCADE |
| 2 | `fk_wf_transitions_workflow` | `workflow_transitions.workflow_id` → `workflow_definitions.id` | ON DELETE CASCADE |
| 3 | `fk_wf_transitions_from` | `workflow_transitions.from_node_id` → `workflow_nodes.id` | ON DELETE CASCADE |
| 4 | `fk_wf_transitions_to` | `workflow_transitions.to_node_id` → `workflow_nodes.id` | ON DELETE CASCADE |
| 5 | `fk_wf_runs_workflow` | `workflow_runs.workflow_id` → `workflow_definitions.id` | ON DELETE CASCADE |
| 6 | `fk_wf_runs_triggered_by` | `workflow_runs.triggered_by` → `users.id` | ON DELETE SET NULL |
| 7 | `fk_wf_runs_current_node` | `workflow_runs.current_node_id` → `workflow_nodes.id` | ON DELETE SET NULL |
| 8 | `fk_wf_run_logs_run` | `workflow_run_logs.run_id` → `workflow_runs.id` | ON DELETE CASCADE |
| 9 | `fk_wf_run_logs_node` | `workflow_run_logs.node_id` → `workflow_nodes.id` | ON DELETE SET NULL |
| 10 | `fk_wf_run_node_states_run` | `workflow_run_node_states.run_id` → `workflow_runs.id` | ON DELETE CASCADE |
| 11 | `fk_wf_run_node_states_node` | `workflow_run_node_states.node_id` → `workflow_nodes.id` | ON DELETE CASCADE |
| 12 | `fk_wf_run_vars_run` | `workflow_run_variables.run_id` → `workflow_runs.id` | ON DELETE CASCADE |
| 13 | `fk_twm_trigger` | `trigger_workflow_mappings.trigger_id` → `triggers.id` | ON DELETE CASCADE |
| 14 | `fk_twm_workflow` | `trigger_workflow_mappings.workflow_id` → `workflow_definitions.id` | ON DELETE CASCADE |
| 15 | `fk_condition_rules_group` | `condition_rules.group_id` → `condition_groups.id` | ON DELETE CASCADE |
| 16 | `fk_cgm_group` | `condition_group_mappings.group_id` → `condition_groups.id` | ON DELETE CASCADE |
| 17 | `fk_approval_requests_run` | `approval_requests.workflow_run_id` → `workflow_runs.id` | ON DELETE CASCADE |
| 18 | `fk_approval_requests_node` | `approval_requests.node_id` → `workflow_nodes.id` | ON DELETE CASCADE |
| 19 | `fk_approval_requests_requested_by` | `approval_requests.requested_by` → `users.id` | ON DELETE SET NULL |
| 20 | `fk_approval_stages_request` | `approval_stages.approval_request_id` → `approval_requests.id` | ON DELETE CASCADE |
| 21 | `fk_approval_assignees_stage` | `approval_assignees.stage_id` → `approval_stages.id` | ON DELETE CASCADE |
| 22 | `fk_approval_assignees_user` | `approval_assignees.user_id` → `users.id` | ON DELETE CASCADE |
| 23 | `fk_email_automations_template` | `email_automations.email_template_id` → `email_templates.id` | ON DELETE SET NULL |
| 24 | `fk_email_automations_creator` | `email_automations.created_by` → `users.id` | ON DELETE SET NULL |
| 25 | `fk_scheduled_tasks_creator` | `scheduled_tasks.created_by` → `users.id` | ON DELETE SET NULL |
| 26 | `fk_webhook_delivery_logs_webhook` | `webhook_delivery_logs.webhook_id` → `webhooks.id` | ON DELETE CASCADE |
| 27 | `fk_workflow_definitions_creator` | `workflow_definitions.created_by` → `users.id` | ON DELETE SET NULL |

## Entity Count by Domain







| # | Domain | Tables | Relationships |



|---|--------|--------|--------------|



| 1 | Core — Users & Auth | 4 | 2 |



| 2 | Seller Marketplace | 7 | 10 |



| 3 | Products & Subscriptions | 8 | 10 |



| 4 | Billing | 3 | 4 |



| 5 | Orders & Commerce | 13 | 26 |



| 6 | Licensing | 6 | 9 |



| 7 | Verification & Security | 3 | 4 |



| 8 | Support & Chat | 6 | 9 |



| 9 | Application & Content | 6 | 2 |



| 10 | CMS | 17 | 5 |



| 11 | Themes | 10 | 11 |



| 12 | LLM Integration | 5 | 8 |



| 13 | Security & Compliance | 6 | 3 |



| 14 | Logging & Monitoring | 4 | 4 |



| 15 | Notifications | 1 | 1 |



| 16 | Commerce & Platform Enhancements | 14 | ~25 |
| 17 | SMS & Communications | 6 | ~12 |

| 18 | System Settings | 8 | 2 |



| 19 | Agentic Features | 3 | 4 |



| 20 | Content Features | 8 | 12 |



| 21 | Video Gallery & Guidelines | 4 | 5 |



| 22 | SEO & Analytics | 5 | 3 |



| 23 | Media & Content Revisions | 2 | 2 |



| 24 | Forms & Page Tagging | 2 | 3 |



| 25 | RBAC | 4 | 2 |



| 26 | Organizations | 2 | 1 |



| 27 | Affiliates & Referrals | 2 | 4 |



| 28 | Financial / Accounting (GL) | 15 | ~41 |



| 29 | Inventory & Warehouse | 10 | ~37 |



| 30 | Procurement | 14 | ~48 |



| 31 | Production & CRP | 13 | ~40 |



| 32 | Human Resources & Payroll | 14 | ~40 |





| 33 | Automation & Workflow Engine | 19 | ~60 || | **Total** | **254** | **~380** |
| 34 | Reporting & Dashboards | 4 | ~6 |
| 35 | Privacy & GDPR | 4 | ~6 |
| 36 | Tax Engine | 4 | ~10 |
| 37 | Dynamic Pricing | 4 | ~8 |
| 38 | Internationalization (i18n) | 3 | ~4 |
| 39 | Content Moderation | 4 | ~7 |
| 40 | System & API Configuration | 4 | ~6 |








---







## Catalog of All Tables



### Core - Users & Auth







#### `users`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `role` | ENUM('admin','user','seller','support') | NOT NULL, DEFAULT 'user' |



| `name` | VARCHAR(255) | NOT NULL |



| `email` | VARCHAR(255) | NOT NULL |



| `email_verified_at` | TIMESTAMP | NULL |



| `password` | VARCHAR(255) | NULL |



| `phone` | VARCHAR(50) | NULL |



| `status` | ENUM('active','suspended','pending','banned') | DEFAULT 'active' |



| `ip_whitelist` | JSON | NULL |



| `telegram_chat_id` | VARCHAR(100) | NULL |



| `settings` | JSON | NULL |



| `avatar_url` | VARCHAR(500) | NULL |



| `last_login_at` | TIMESTAMP | NULL |



| `locale` | VARCHAR(5) | NULL, DEFAULT 'en' |



| `timezone` | VARCHAR(50) | NULL, DEFAULT 'UTC' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `password_resets`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | FK → users(id), NOT NULL |



| `token` | VARCHAR(255) | NOT NULL |



| `expires_at` | TIMESTAMP | NOT NULL |



| `used_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `seller_profiles`



| Column | Type | Constraints |



|---|---|---|



| `user_id` | BIGINT UNSIGNED | FK → users(id), NOT NULL |



| `store_name` | VARCHAR(100) | NULL |



| `store_description` | TEXT | NULL |



| `store_logo_url` | VARCHAR(500) | NULL |



| `store_cover_url` | VARCHAR(500) | NULL |



| `status` | ENUM('pending','active','suspended','banned') | DEFAULT 'pending' |



| `current_balance` | DECIMAL(15,4) | DEFAULT 0.0000 |



| `default_commission` | DECIMAL(5,2) | DEFAULT 80.00 |



| `verified_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







### Seller Marketplace







#### `payout_accounts`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `seller_id` | BIGINT UNSIGNED | FK → seller_profiles(user_id), NOT NULL |



| `method` | ENUM('bank','paypal','stripe','crypto','bkash') | NOT NULL |



| `account_label` | VARCHAR(100) | NULL |



| `account_details` | JSON | NULL |



| `is_default` | BOOLEAN | DEFAULT FALSE |



| `status` | ENUM('active','inactive') | DEFAULT 'active' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `payout_transactions`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `seller_id` | BIGINT UNSIGNED | NOT NULL |



| `payout_account_id` | BIGINT UNSIGNED | FK → payout_accounts(id), NULL |



| `amount` | DECIMAL(15,2) | NOT NULL |



| `fee` | DECIMAL(15,2) | DEFAULT 0.00 |



| `net_amount` | DECIMAL(15,2) | NOT NULL |



| `currency` | VARCHAR(3) | DEFAULT 'USD' |



| `period_start` | DATE | NULL |



| `period_end` | DATE | NULL |



| `status` | ENUM('pending','processing','completed','failed') | DEFAULT 'pending' |



| `reference` | VARCHAR(255) | NULL |



| `notes` | TEXT | NULL |



| `processed_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `balance_ledger`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `seller_id` | BIGINT UNSIGNED | FK → seller_profiles(user_id), NULL |



| `type` | ENUM('sale_credit','commission_earned','payout_debit','adjustment','fee') | NOT NULL |



| `amount` | DECIMAL(15,4) | NOT NULL |



| `balance_before` | DECIMAL(15,4) | NOT NULL |



| `balance_after` | DECIMAL(15,4) | NOT NULL |



| `reference_type` | VARCHAR(50) | NULL |



| `reference_id` | BIGINT UNSIGNED | NULL |



| `description` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `seller_verification`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `seller_id` | BIGINT UNSIGNED | NOT NULL |



| `document_type` | ENUM('id_card','passport','business_license','tax_id') | NOT NULL |



| `document_url` | VARCHAR(500) | NOT NULL |



| `status` | ENUM('pending','approved','rejected') | DEFAULT 'pending' |



| `verified_by` | BIGINT UNSIGNED | NULL |



| `verified_at` | TIMESTAMP | NULL |



| `rejection_reason` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `seller_stats`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `seller_id` | BIGINT UNSIGNED | FK → seller_profiles(user_id), NOT NULL |



| `period_type` | ENUM('daily','weekly','monthly') | NOT NULL |



| `period_date` | DATE | NOT NULL |



| `total_sales` | DECIMAL(15,2) | DEFAULT 0.00 |



| `total_earnings` | DECIMAL(15,2) | DEFAULT 0.00 |



| `total_orders` | INT | DEFAULT 0 |



| `total_products` | INT | DEFAULT 0 |



| `avg_rating` | DECIMAL(3,2) | NULL |



| `review_count` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### `auth_logs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | FK → users(id), NULL |



| `type` | ENUM('login','logout','failed_login','2fa_attempt','magic_link','password_reset') | NOT NULL |



| `ip_address` | VARCHAR(45) | NOT NULL |



| `device_fingerprint` | VARCHAR(255) | NULL |



| `status` | ENUM('success','failed','suspicious') | DEFAULT 'success' |



| `details` | JSON | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### RBAC







#### `roles`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `slug` | VARCHAR(100) UNIQUE | NOT NULL |



| `description` | TEXT | NULL |



| `is_system` | BOOLEAN | DEFAULT FALSE |

| `organization_id` | BIGINT UNSIGNED | FK → organizations(id), ON DELETE CASCADE, NULL |

| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |





| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `permissions`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `slug` | VARCHAR(100) UNIQUE | NOT NULL |



| `description` | TEXT | NULL |



| `group` | VARCHAR(100) | NULL |

| `organization_id` | BIGINT UNSIGNED | FK → organizations(id), ON DELETE CASCADE, NULL |

| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |





| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `role_permissions`



| Column | Type | Constraints |



|---|---|---|



| `role_id` | BIGINT UNSIGNED | NOT NULL |



| `permission_id` | BIGINT UNSIGNED | FK → permissions(id), NOT NULL |







#### `user_roles`



| Column | Type | Constraints |



|---|---|---|



| `user_id` | BIGINT UNSIGNED | FK → users(id), NOT NULL |



| `role_id` | BIGINT UNSIGNED | FK → roles(id), NOT NULL |



| `organization_id` | BIGINT UNSIGNED | FK → organizations(id), NOT NULL |



| `assigned_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### Organizations







#### `organizations`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(255) | NOT NULL |



| `slug` | VARCHAR(255) UNIQUE | NOT NULL |



| `logo_url` | VARCHAR(500) | NULL |



| `website` | VARCHAR(500) | NULL |



| `status` | ENUM('active','suspended') | DEFAULT 'active' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `organization_members`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `organization_id` | BIGINT UNSIGNED | FK → organizations(id), NOT NULL |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `role` | ENUM('owner','admin','member','viewer') | NOT NULL, DEFAULT 'member' |



| `joined_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### Products & Subscriptions







#### `products`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `seller_id` | BIGINT UNSIGNED | FK → seller_profiles(user_id), NULL |



| `name` | VARCHAR(255) | NOT NULL |



| `slug` | VARCHAR(255) | NOT NULL |



| `description` | TEXT | NULL |



| `type` | ENUM('script','software','plugin','saas','desktop') | NOT NULL |



| `base_price` | DECIMAL(10,2) | DEFAULT 0.00 |



| `sku` | VARCHAR(100) | NULL |



| `stock` | INT UNSIGNED | NULL |



| `download_limit` | INT UNSIGNED | NULL |



| `total_sales` | INT UNSIGNED | DEFAULT 0 |



| `version` | VARCHAR(20) | NULL |



| `download_url` | VARCHAR(500) | NULL |



| `status` | ENUM('draft','active','archived') | DEFAULT 'draft' |



| `demo_url` | VARCHAR(500) | NULL |



| `docs_url` | VARCHAR(500) | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `product_hardware_requirements`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `product_id` | BIGINT UNSIGNED | FK → products(id), NOT NULL |



| `os_name` | VARCHAR(50) | NULL |



| `os_version_min` | VARCHAR(20) | NULL |



| `cpu_cores_min` | INT | NULL |



| `memory_mb_min` | INT | NULL |



| `disk_mb_min` | INT | NULL |



| `additional_notes` | TEXT | NULL |







#### `subscription_plans`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(50) | NOT NULL |



| `code` | VARCHAR(50) | NULL |



| `duration_months` | INT | NOT NULL |



| `max_activations` | INT | NOT NULL, DEFAULT 1 |



| `price_monthly` | DECIMAL(10,2) | NOT NULL |



| `price_yearly` | DECIMAL(10,2) | NOT NULL |



| `features` | JSON | NULL |



| `status` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `user_subscriptions`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `plan_id` | BIGINT UNSIGNED | NOT NULL |



| `product_id` | BIGINT UNSIGNED | FK → products(id), NULL |



| `status` | ENUM('active','cancelled','expired','past_due') | DEFAULT 'active' |



| `start_date` | TIMESTAMP | NOT NULL |



| `end_date` | TIMESTAMP | NOT NULL |



| `trial_ends_at` | TIMESTAMP | NULL |



| `stripe_subscription_id` | VARCHAR(255) | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `product_categories`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `parent_id` | BIGINT UNSIGNED | FK → product_categories(id), NULL |



| `name` | VARCHAR(255) | NOT NULL |



| `slug` | VARCHAR(255) UNIQUE | NOT NULL |



| `description` | TEXT | NULL |



| `image_url` | VARCHAR(500) | NULL |



| `sort_order` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `product_category_items`



| Column | Type | Constraints |



|---|---|---|



| `reviewable_type` | VARCHAR(50) | NOT NULL |
| `reviewable_id` | BIGINT UNSIGNED | NOT NULL |



| `category_id` | BIGINT UNSIGNED | FK → product_categories(id), NOT NULL |







#### `product_discounts`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `product_id` | BIGINT UNSIGNED | FK → products(id), NOT NULL |



| `name` | VARCHAR(255) | NOT NULL |



| `type` | ENUM('percentage','fixed') | NOT NULL |



| `value` | DECIMAL(10,2) | NOT NULL |



| `max_uses` | INT UNSIGNED | NULL |



| `used_count` | INT UNSIGNED | DEFAULT 0 |



| `starts_at` | TIMESTAMP | NOT NULL |



| `ends_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `wishlists`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `product_id` | BIGINT UNSIGNED | FK → products(id), NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### Billing







#### `invoices`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `order_id` | BIGINT UNSIGNED | NULL |



| `subscription_id` | BIGINT UNSIGNED | FK → user_subscriptions(id), NULL |



| `invoice_number` | VARCHAR(50) | NULL |



| `total` | DECIMAL(10,2) | NOT NULL |



| `tax` | DECIMAL(10,2) | DEFAULT 0.00 |



| `status` | ENUM('draft','open','paid','void','refunded') | DEFAULT 'draft' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `payments`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `invoice_id` | BIGINT UNSIGNED | FK → invoices(id), NOT NULL |



| `gateway` | VARCHAR(50) | NOT NULL |



| `transaction_id` | VARCHAR(255) | NULL |



| `amount` | DECIMAL(10,2) | NOT NULL |



| `status` | ENUM('pending','success','failed') | DEFAULT 'pending' |



| `meta` | JSON | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `tax_rates`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(255) | NOT NULL |



| `rate` | DECIMAL(5,2) | NOT NULL |



| `type` | ENUM('percentage','fixed') | NOT NULL, DEFAULT 'percentage' |



| `country` | VARCHAR(2) | NULL |



| `region` | VARCHAR(100) | NULL |



| `is_default` | BOOLEAN | DEFAULT FALSE |



| `active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







### Orders & Commerce







#### `coupons`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `code` | VARCHAR(50) | NOT NULL |



| `type` | ENUM('percentage','fixed_amount') | NOT NULL |



| `value` | DECIMAL(10,2) | NOT NULL |



| `min_order_amount` | DECIMAL(10,2) | DEFAULT 0.00 |



| `max_uses` | INT | NULL |



| `used_count` | INT | DEFAULT 0 |



| `starts_at` | TIMESTAMP | NULL |



| `expires_at` | TIMESTAMP | NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `carts`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `coupon_id` | BIGINT UNSIGNED | FK → coupons(id), NULL |



| `subtotal` | DECIMAL(10,2) | DEFAULT 0.00 |



| `tax` | DECIMAL(10,2) | DEFAULT 0.00 |



| `total` | DECIMAL(10,2) | DEFAULT 0.00 |



| `expires_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cart_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `cart_id` | BIGINT UNSIGNED | NOT NULL |



| `product_id` | BIGINT UNSIGNED | FK → products(id), NOT NULL |



| `plan_id` | BIGINT UNSIGNED | NULL |



| `quantity` | INT | DEFAULT 1 |



| `unit_price` | DECIMAL(10,2) | NOT NULL |



| `subtotal` | DECIMAL(10,2) | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `orders`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `order_number` | VARCHAR(50) | NOT NULL |



| `status` | ENUM('pending','confirmed','processing','completed','cancelled','refunded') | DEFAULT 'pending' |



| `subtotal` | DECIMAL(10,2) | NOT NULL |



| `tax` | DECIMAL(10,2) | DEFAULT 0.00 |



| `discount_total` | DECIMAL(10,2) | DEFAULT 0.00 |



| `total` | DECIMAL(10,2) | NOT NULL |



| `currency` | VARCHAR(3) | DEFAULT 'USD' |



| `notes` | TEXT | NULL |



| `billing_address_id` | BIGINT UNSIGNED | NULL |



| `shipping_address_id` | BIGINT UNSIGNED | FK → addresses(id), NULL |



| `coupon_id` | BIGINT UNSIGNED | NULL |



| `api_client_id` | BIGINT UNSIGNED | NULL |



| `customer_notes` | TEXT | NULL |



| `ip_address` | VARCHAR(45) | NULL |



| `user_agent` | TEXT | NULL |



| `paid_at` | TIMESTAMP | NULL |



| `cancelled_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `order_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `order_id` | BIGINT UNSIGNED | NOT NULL |



| `product_id` | BIGINT UNSIGNED | FK → products(id), NULL |



| `plan_id` | BIGINT UNSIGNED | NULL |



| `item_type` | ENUM('product','subscription') | NOT NULL |



| `name` | VARCHAR(255) | NOT NULL |



| `quantity` | INT | DEFAULT 1 |



| `unit_price` | DECIMAL(10,2) | NOT NULL |



| `subtotal` | DECIMAL(10,2) | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `order_item_metadata`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `order_item_id` | BIGINT UNSIGNED | NOT NULL |



| `license_id` | BIGINT UNSIGNED | NULL |



| `meta_key` | VARCHAR(100) | NOT NULL |



| `meta_value` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `order_status_history`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `order_id` | BIGINT UNSIGNED | NOT NULL |



| `from_status` | ENUM('pending','confirmed','processing','completed','cancelled','refunded') | NULL |



| `to_status` | ENUM('pending','confirmed','processing','completed','cancelled','refunded') | NOT NULL |



| `changed_by` | BIGINT UNSIGNED | NULL |



| `api_client_id` | BIGINT UNSIGNED | FK → api_clients(id), NULL |



| `reason` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `refunds`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `order_id` | BIGINT UNSIGNED | NOT NULL |



| `payment_id` | BIGINT UNSIGNED | NULL |



| `amount` | DECIMAL(10,2) | NOT NULL |



| `reason` | TEXT | NULL |



| `status` | ENUM('pending','approved','rejected','completed') | DEFAULT 'pending' |



| `processed_by` | BIGINT UNSIGNED | FK → users(id), NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `shipments`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `order_id` | BIGINT UNSIGNED | FK → orders(id), NOT NULL |



| `tracking_number` | VARCHAR(255) | NULL |



| `carrier` | VARCHAR(100) | NULL |



| `status` | ENUM('pending','shipped','delivered','returned') | DEFAULT 'pending' |



| `shipped_at` | TIMESTAMP | NULL |



| `delivered_at` | TIMESTAMP | NULL |



| `shipping_data` | JSON | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `file_downloads`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `order_item_id` | BIGINT UNSIGNED | FK → order_items(id), NOT NULL |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `ip_address` | VARCHAR(45) | NULL |



| `download_count` | INT UNSIGNED | DEFAULT 0 |



| `last_downloaded` | TIMESTAMP | NULL |



| `expires_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `affiliates`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | FK → users(id), NOT NULL |



| `code` | VARCHAR(50) UNIQUE | NOT NULL |



| `commission_rate` | DECIMAL(5,2) | DEFAULT 10.00 |



| `total_earned` | DECIMAL(12,2) | DEFAULT 0.00 |



| `total_paid` | DECIMAL(12,2) | DEFAULT 0.00 |



| `status` | ENUM('active','suspended') | DEFAULT 'active' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `referrals`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `affiliate_id` | BIGINT UNSIGNED | FK → affiliates(id), NOT NULL |



| `referred_id` | BIGINT UNSIGNED | NOT NULL |



| `order_id` | BIGINT UNSIGNED | NULL |



| `commission` | DECIMAL(10,2) | DEFAULT 0.00 |



| `status` | ENUM('pending','paid','cancelled') | DEFAULT 'pending' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### Licensing (Core Domain)







#### `api_clients`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | FK → users(id), NOT NULL |



| `name` | VARCHAR(100) | NULL |



| `api_key` | VARCHAR(64) | NOT NULL |



| `api_secret` | VARCHAR(255) | NULL |



| `status` | ENUM('active','suspended','revoked') | DEFAULT 'active' |



| `rate_limit` | INT | DEFAULT 60 |



| `last_used_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `licenses`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `product_id` | BIGINT UNSIGNED | NULL |



| `api_client_id` | BIGINT UNSIGNED | NULL |



| `subscription_id` | BIGINT UNSIGNED | FK → user_subscriptions(id), NULL |



| `license_key` | VARCHAR(64) | NOT NULL |



| `api_key` | VARCHAR(64) | NOT NULL |



| `status` | ENUM('active','suspended','expired','revoked') | DEFAULT 'active' |



| `expires_at` | TIMESTAMP | NULL |



| `max_activations` | INT | DEFAULT 1 |



| `current_activations` | INT | DEFAULT 0 |



| `last_activity_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `license_activations`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `license_id` | BIGINT UNSIGNED | FK → licenses(id), NOT NULL |



| `domain` | VARCHAR(255) | NULL |



| `hosting_ip` | VARCHAR(45) | NULL |



| `status` | ENUM('active','inactive','suspicious','banned') | DEFAULT 'active' |



| `last_verified_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `meta` | JSON | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `hardware_activations`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `license_id` | BIGINT UNSIGNED | FK → licenses(id), NOT NULL |



| `machine_id` | VARCHAR(100) | NOT NULL |



| `cpu_id` | VARCHAR(100) | NULL |



| `motherboard_serial` | VARCHAR(100) | NULL |



| `bios_serial` | VARCHAR(100) | NULL |



| `disk_serial` | VARCHAR(100) | NULL |



| `mac_address` | VARCHAR(17) | NULL |



| `os_name` | VARCHAR(50) | NULL |



| `os_version` | VARCHAR(20) | NULL |



| `os_architecture` | VARCHAR(10) | NULL |



| `cpu_name` | VARCHAR(100) | NULL |



| `cpu_cores` | INT | NULL |



| `total_memory` | INT | NULL |



| `system_manufacturer` | VARCHAR(100) | NULL |



| `system_model` | VARCHAR(100) | NULL |



| `local_ip` | VARCHAR(45) | NULL |



| `public_ip` | VARCHAR(45) | NULL |



| `status` | ENUM('active','inactive','suspicious','banned') | DEFAULT 'active' |



| `activation_limit` | INT | DEFAULT 1 |



| `activated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `last_ping_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |



| `required_os_min` | VARCHAR(50) | NULL |



| `required_memory_mb` | INT | NULL |



| `required_disk_mb` | INT | NULL |



| `compatibility_status` | ENUM('compatible','incompatible','unknown') | DEFAULT 'unknown' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `hardware_activation_logs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `hardware_activation_id` | BIGINT UNSIGNED | FK → hardware_activations(id), NULL |



| `license_id` | BIGINT UNSIGNED | NOT NULL |



| `machine_id` | VARCHAR(100) | NOT NULL |



| `hardware_snapshot` | JSON | NULL |



| `system_specs` | JSON | NULL |



| `activation_status` | ENUM('success','failed','hardware_mismatch','limit_exceeded','suspicious') | NOT NULL |



| `failure_reason` | VARCHAR(255) | NULL |



| `vm_detected` | BOOLEAN | DEFAULT FALSE |



| `tamper_detected` | BOOLEAN | DEFAULT FALSE |



| `compatibility_result` | ENUM('compatible','incompatible','unknown') | DEFAULT 'unknown' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### Verification & Security







#### `verification_logs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `activation_id` | BIGINT UNSIGNED | FK → license_activations(id), NULL |



| `license_key` | VARCHAR(64) | NULL |



| `api_key` | VARCHAR(64) | NULL |



| `ip_address` | VARCHAR(45) | NOT NULL |



| `user_agent` | VARCHAR(500) | NULL |



| `request_domain` | VARCHAR(255) | NULL |



| `request_ip` | VARCHAR(45) | NULL |



| `tier1_api` | ENUM('pass','fail','na') | DEFAULT 'pass' |



| `tier2_license` | ENUM('pass','fail','na') | DEFAULT 'pass' |



| `tier3_domain` | ENUM('pass','fail','na') | DEFAULT 'pass' |



| `tier4_ip` | ENUM('pass','fail','na') | DEFAULT 'pass' |



| `tier5_subscription` | ENUM('pass','fail','na') | DEFAULT 'pass' |



| `overall_result` | ENUM('valid','invalid','expired','suspicious','banned') | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `fraud_logs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `license_id` | BIGINT UNSIGNED | NULL |



| `activation_id` | BIGINT UNSIGNED | FK → license_activations(id), NULL |



| `ip` | VARCHAR(45) | NULL |



| `domain` | VARCHAR(255) | NULL |



| `reason` | VARCHAR(255) | NOT NULL |



| `severity` | ENUM('low','medium','high','critical') | DEFAULT 'low' |



| `action_taken` | VARCHAR(100) | DEFAULT 'logged' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `user_2fa`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | FK → users(id), NOT NULL |



| `secret` | VARCHAR(255) | NULL |



| `method` | ENUM('totp','email','sms','backup_codes') | NOT NULL, DEFAULT 'totp' |



| `backup_codes` | JSON | NULL |



| `is_enabled` | BOOLEAN | DEFAULT FALSE |



| `verified_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







### Support & Chat







#### `tickets`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | FK → users(id), NOT NULL |



| `subject` | VARCHAR(255) | NULL |



| `status` | ENUM('open','replied','closed') | DEFAULT 'open' |



| `priority` | ENUM('low','medium','high') | DEFAULT 'medium' |



| `assigned_to` | BIGINT UNSIGNED | NULL |



| `category` | VARCHAR(100) | NULL |



| `closed_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `ticket_messages`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `ticket_id` | BIGINT UNSIGNED | FK → tickets(id), NOT NULL |



| `sender_id` | BIGINT UNSIGNED | FK → users(id), NOT NULL |



| `message` | TEXT | NULL |



| `attachments` | JSON | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `chat_sessions`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `status` | ENUM('open','closed') | DEFAULT 'open' |



| `assigned_to` | BIGINT UNSIGNED | FK → users(id), NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `chat_messages`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `session_id` | BIGINT UNSIGNED | FK → chat_sessions(id), NOT NULL |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `message` | TEXT | NOT NULL |



| `is_agent` | BOOLEAN | DEFAULT FALSE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `bot_conversations`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `bot_config_id` | BIGINT UNSIGNED | FK → bot_configs(id), NULL |



| `message` | TEXT | NOT NULL |



| `response` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### Application & Content







#### `application_updates`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `version` | VARCHAR(20) | NOT NULL |



| `type` | ENUM('update','version_history') | DEFAULT 'update' |



| `changelog` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `release_notes`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `version` | VARCHAR(20) | NOT NULL |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `user_guides`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `author_id` | BIGINT UNSIGNED | FK → users(id), NULL |



| `title` | VARCHAR(255) | NOT NULL |



| `slug` | VARCHAR(255) | NULL |



| `content` | TEXT | NOT NULL |



| `status` | ENUM('published','draft') | DEFAULT 'draft' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `announcements`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `title` | VARCHAR(255) | NOT NULL |



| `content` | TEXT | NOT NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `knowledge_base_articles`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `title` | VARCHAR(255) | NOT NULL |



| `slug` | VARCHAR(255) | NULL |



| `content` | TEXT | NOT NULL |



| `category` | VARCHAR(100) | NULL |



| `status` | ENUM('published','draft') | DEFAULT 'published' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `faq_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `question` | VARCHAR(255) | NOT NULL |



| `answer` | TEXT | NOT NULL |



| `slug` | VARCHAR(255) | NULL |



| `category` | VARCHAR(100) | NULL |



| `position` | INT | DEFAULT 0 |



| `status` | ENUM('published','draft') | DEFAULT 'published' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### CMS







#### `cms_categories`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `slug` | VARCHAR(100) | NULL |



| `description` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cms_tags`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `slug` | VARCHAR(100) | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `posts`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `type` | ENUM('blog','cms') | NOT NULL, DEFAULT 'cms' |



| `author_id` | BIGINT UNSIGNED | NULL |



| `title` | VARCHAR(255) | NOT NULL |



| `slug` | VARCHAR(255) | NULL |



| `content` | TEXT | NULL |



| `excerpt` | TEXT | NULL |



| `featured_image` | VARCHAR(500) | NULL |



| `category_id` | BIGINT UNSIGNED | FK → cms_categories(id), NULL |



| `status` | ENUM('published','draft','scheduled') | DEFAULT 'draft' |



| `published_at` | TIMESTAMP | NULL |



| `scheduled_for` | TIMESTAMP | NULL |



| `meta_title` | VARCHAR(255) | NULL |



| `meta_description` | TEXT | NULL |



| `meta_keywords` | VARCHAR(500) | NULL |



| `canonical_url` | VARCHAR(500) | NULL |



| `view_count` | INT UNSIGNED | DEFAULT 0 |



| `og_image` | VARCHAR(500) | NULL |



| `og_title` | VARCHAR(255) | NULL |



| `og_description` | TEXT | NULL |



| `twitter_card` | ENUM('summary','summary_large_image','app','player') | NULL |



| `noindex` | BOOLEAN | DEFAULT FALSE |



| `priority` | DECIMAL(2,1) | DEFAULT 0.50 |



| `changefreq` | ENUM('always','hourly','daily','weekly','monthly','yearly','never') | DEFAULT 'weekly' |



| `sitemap_include` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `post_tags`



| Column | Type | Constraints |



|---|---|---|



| `post_id` | BIGINT UNSIGNED | FK → posts(id), NOT NULL |



| `tag_id` | BIGINT UNSIGNED | NOT NULL |







#### `post_comments`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `post_id` | BIGINT UNSIGNED | NOT NULL |



| `user_id` | BIGINT UNSIGNED | NULL |



| `parent_id` | BIGINT UNSIGNED | FK → post_comments(id), NULL |



| `author_name` | VARCHAR(255) | NULL |



| `author_email` | VARCHAR(255) | NULL |



| `body` | TEXT | NOT NULL |



| `status` | ENUM('pending','approved','spam') | DEFAULT 'pending' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `post_reactions`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `post_id` | BIGINT UNSIGNED | FK → posts(id), NOT NULL |



| `user_id` | BIGINT UNSIGNED | FK → users(id), NOT NULL |



| `reaction` | ENUM('like','love','laugh','clap','fire') | DEFAULT 'like' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `post_views`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `post_id` | BIGINT UNSIGNED | FK → posts(id), NOT NULL |



| `user_id` | BIGINT UNSIGNED | FK → users(id), NULL |



| `ip_address` | VARCHAR(45) | NULL |



| `user_agent` | TEXT | NULL |



| `viewed_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `post_media`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `post_id` | BIGINT UNSIGNED | FK → posts(id), NOT NULL |



| `file_name` | VARCHAR(255) | NOT NULL |



| `file_path` | VARCHAR(500) | NOT NULL |



| `file_type` | VARCHAR(50) | NOT NULL |



| `file_size` | INT UNSIGNED | NULL |



| `is_featured` | BOOLEAN | DEFAULT FALSE |



| `sort_order` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `post_series`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `title` | VARCHAR(255) | NOT NULL |



| `slug` | VARCHAR(255) | NULL |



| `description` | TEXT | NULL |



| `cover_image` | VARCHAR(500) | NULL |



| `author_id` | BIGINT UNSIGNED | FK → users(id), NULL |



| `status` | ENUM('active','archived') | DEFAULT 'active' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `post_series_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `series_id` | BIGINT UNSIGNED | FK → post_series(id), NOT NULL |



| `post_id` | BIGINT UNSIGNED | NOT NULL |



| `part_order` | INT | DEFAULT 0 |



| `part_title` | VARCHAR(255) | NULL |







#### `related_posts`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `post_id` | BIGINT UNSIGNED | NOT NULL |



| `related_post_id` | BIGINT UNSIGNED | FK → posts(id), NOT NULL |



| `relation_type` | ENUM('manual','auto_tag','auto_category') | DEFAULT 'manual' |



| `weight` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `author_profiles`



| Column | Type | Constraints |



|---|---|---|



| `user_id` | BIGINT UNSIGNED | FK → users(id), PK |



| `display_name` | VARCHAR(255) | NULL |



| `avatar_url` | VARCHAR(500) | NULL |



| `bio` | TEXT | NULL |



| `website_url` | VARCHAR(500) | NULL |



| `twitter_handle` | VARCHAR(100) | NULL |



| `github_handle` | VARCHAR(100) | NULL |



| `linkedin_url` | VARCHAR(500) | NULL |



| `is_public` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cms_pages`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `title` | VARCHAR(255) | NULL |



| `slug` | VARCHAR(255) | NULL |



| `content` | LONGTEXT | NULL |



| `status` | ENUM('published','draft') | DEFAULT 'draft' |



| `meta_title` | VARCHAR(255) | NULL |



| `meta_description` | TEXT | NULL |



| `meta_keywords` | VARCHAR(500) | NULL |



| `canonical_url` | VARCHAR(500) | NULL |



| `og_image` | VARCHAR(500) | NULL |



| `og_title` | VARCHAR(255) | NULL |



| `og_description` | TEXT | NULL |



| `twitter_card` | ENUM('summary','summary_large_image','app','player') | NULL |



| `noindex` | BOOLEAN | DEFAULT FALSE |



| `priority` | DECIMAL(2,1) | DEFAULT 0.50 |



| `changefreq` | ENUM('always','hourly','daily','weekly','monthly','yearly','never') | DEFAULT 'weekly' |



| `sitemap_include` | BOOLEAN | DEFAULT TRUE |



| `published_at` | TIMESTAMP | NULL |



| `scheduled_for` | TIMESTAMP | NULL |



| `author_id` | BIGINT UNSIGNED | FK → users(id), NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cms_menus`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `slug` | VARCHAR(100) | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cms_menu_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `menu_id` | BIGINT UNSIGNED | FK → cms_menus(id), NOT NULL |



| `title` | VARCHAR(100) | NOT NULL |



| `url` | VARCHAR(255) | NULL |



| `target` | VARCHAR(20) | DEFAULT '_self' |



| `position` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cms_widgets`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `slug` | VARCHAR(100) | NULL |



| `description` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cms_banners`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `title` | VARCHAR(255) | NULL |



| `image` | VARCHAR(255) | NULL |



| `link` | VARCHAR(255) | NULL |



| `status` | ENUM('published','draft') | DEFAULT 'draft' |



| `position` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cms_testimonials`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(255) | NULL |



| `position` | VARCHAR(255) | NULL |



| `company` | VARCHAR(255) | NULL |



| `content` | TEXT | NULL |



| `status` | ENUM('published','draft') | DEFAULT 'draft' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cms_settings`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `key` | VARCHAR(100) | NOT NULL |



| `value` | TEXT | NULL |



| `group` | ENUM('general','seo','analytics','social','custom') | DEFAULT 'general' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cms_social_links`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `platform` | VARCHAR(50) | NOT NULL |



| `url` | VARCHAR(255) | NOT NULL |



| `position` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cms_footers`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `content` | TEXT | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cms_headers`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `content` | TEXT | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cms_sidebars`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `content` | TEXT | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cms_media_galleries`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `file_name` | VARCHAR(255) | NOT NULL |



| `file_path` | VARCHAR(255) | NOT NULL |



| `file_type` | VARCHAR(50) | NOT NULL |



| `file_size` | INT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `redirects`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `old_path` | VARCHAR(500) | NOT NULL |



| `new_path` | VARCHAR(500) | NOT NULL |



| `status_code` | ENUM('301','302','307') | DEFAULT '301' |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `hits_count` | INT UNSIGNED | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `slug_history`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `content_type` | ENUM('post','cms_page','product','knowledge_base','faq_item') | NOT NULL |



| `content_id` | BIGINT UNSIGNED | NOT NULL |



| `old_slug` | VARCHAR(255) | NOT NULL |



| `new_slug` | VARCHAR(255) | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `structured_data`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `content_type` | ENUM('post','cms_page','product') | NOT NULL |



| `content_id` | BIGINT UNSIGNED | NOT NULL |



| `schema_type` | VARCHAR(50) | NOT NULL |



| `json_ld` | JSON | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `seo_analysis`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `content_type` | ENUM('post','cms_page') | NOT NULL |



| `content_id` | BIGINT UNSIGNED | NOT NULL |



| `score` | DECIMAL(5,2) | NULL |



| `issues` | JSON | NULL |



| `word_count` | INT UNSIGNED | NULL |



| `readability_score` | DECIMAL(5,2) | NULL |



| `checked_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `analytics_events`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `event_type` | VARCHAR(50) | NOT NULL |



| `page_url` | VARCHAR(500) | NULL |



| `referrer_url` | VARCHAR(500) | NULL |



| `utm_source` | VARCHAR(100) | NULL |



| `utm_medium` | VARCHAR(100) | NULL |



| `utm_campaign` | VARCHAR(100) | NULL |



| `utm_term` | VARCHAR(100) | NULL |



| `utm_content` | VARCHAR(100) | NULL |



| `user_agent` | TEXT | NULL |



| `ip_address` | VARCHAR(45) | NULL |



| `session_id` | VARCHAR(100) | NULL |



| `user_id` | BIGINT UNSIGNED | FK → users(id), NULL |



| `post_id` | BIGINT UNSIGNED | FK → posts(id), NULL |



| `page_id` | BIGINT UNSIGNED | FK → cms_pages(id), NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `media_library`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `filename` | VARCHAR(255) | NOT NULL |



| `filepath` | VARCHAR(500) | NOT NULL |



| `mime_type` | VARCHAR(100) | NOT NULL |



| `file_size` | BIGINT UNSIGNED | NULL |



| `width` | INT UNSIGNED | NULL |



| `height` | INT UNSIGNED | NULL |



| `alt_text` | VARCHAR(255) | NULL |



| `caption` | TEXT | NULL |



| `uploaded_by` | BIGINT UNSIGNED | FK → users(id), NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `content_revisions`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `content_type` | ENUM('post','cms_page','user_guide','knowledge_base') | NOT NULL |



| `content_id` | BIGINT UNSIGNED | NOT NULL |



| `title` | VARCHAR(255) | NULL |



| `content` | LONGTEXT | NULL |



| `summary` | TEXT | NULL |



| `meta` | JSON | NULL |



| `created_by` | BIGINT UNSIGNED | FK → users(id), NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `cms_page_tags`



| Column | Type | Constraints |



|---|---|---|



| `page_id` | BIGINT UNSIGNED | FK → cms_pages(id), NOT NULL |



| `tag_id` | BIGINT UNSIGNED | NOT NULL |







#### `form_submissions`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `form_key` | VARCHAR(50) | NOT NULL |



| `data` | JSON | NOT NULL |



| `user_id` | BIGINT UNSIGNED | FK → users(id), NULL |



| `ip_address` | VARCHAR(45) | NULL |



| `user_agent` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `content_blocks`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `key` | VARCHAR(100) UNIQUE | NOT NULL |



| `title` | VARCHAR(255) | NOT NULL |



| `content` | LONGTEXT | NULL |



| `type` | ENUM('html','text','json') | DEFAULT 'html' |



| `locations` | JSON | NULL |



| `active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







### Video Gallery & Guidelines







#### `video_galleries`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `title` | VARCHAR(255) | NOT NULL |



| `slug` | VARCHAR(255) | NULL |



| `description` | TEXT | NULL |



| `thumbnail` | VARCHAR(500) | NULL |



| `author_id` | BIGINT UNSIGNED | FK → users(id), NULL |



| `status` | ENUM('active','archived') | DEFAULT 'active' |



| `sort_order` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `videos`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `gallery_id` | BIGINT UNSIGNED | FK → video_galleries(id), NULL |



| `title` | VARCHAR(255) | NOT NULL |



| `slug` | VARCHAR(255) | NULL |



| `description` | TEXT | NULL |



| `video_url` | VARCHAR(500) | NULL |



| `embed_url` | VARCHAR(500) | NULL |



| `thumbnail` | VARCHAR(500) | NULL |



| `duration` | INT UNSIGNED | NULL |



| `file_size` | BIGINT UNSIGNED | NULL |



| `file_type` | VARCHAR(50) | NULL |



| `author_id` | BIGINT UNSIGNED | NULL |



| `status` | ENUM('published','draft') | DEFAULT 'draft' |



| `featured` | BOOLEAN | DEFAULT FALSE |



| `view_count` | INT UNSIGNED | DEFAULT 0 |



| `sort_order` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `video_tags`



| Column | Type | Constraints |



|---|---|---|



| `video_id` | BIGINT UNSIGNED | FK → videos(id), NOT NULL |



| `tag_id` | BIGINT UNSIGNED | NOT NULL |







#### `video_guidelines`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `video_id` | BIGINT UNSIGNED | FK → videos(id), NOT NULL |



| `step_order` | INT | DEFAULT 0 |



| `title` | VARCHAR(255) | NOT NULL |



| `description` | TEXT | NULL |



| `time_marker` | INT UNSIGNED | NULL |



| `image` | VARCHAR(500) | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







### Themes







#### `themes`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NULL |



| `slug` | VARCHAR(100) | NULL |



| `description` | TEXT | NULL |



| `version` | VARCHAR(20) | NULL |



| `author` | VARCHAR(100) | NULL |



| `status` | ENUM('active','inactive') | DEFAULT 'inactive' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `theme_settings`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `theme_id` | BIGINT UNSIGNED | FK → themes(id), NOT NULL |



| `key` | VARCHAR(100) | NULL |



| `value` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `theme_assets`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `theme_id` | BIGINT UNSIGNED | FK → themes(id), NOT NULL |



| `type` | ENUM('css','js','image') | NOT NULL |



| `path` | VARCHAR(255) | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `theme_customizations`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `theme_id` | BIGINT UNSIGNED | FK → themes(id), NOT NULL |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `custom_css` | TEXT | NULL |



| `custom_js` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `theme_usage_logs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `theme_id` | BIGINT UNSIGNED | FK → themes(id), NOT NULL |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `action` | ENUM('activated','deactivated','customized') | NOT NULL |



| `ip_address` | VARCHAR(45) | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `theme_update_logs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `theme_id` | BIGINT UNSIGNED | FK → themes(id), NOT NULL |



| `version_from` | VARCHAR(20) | NULL |



| `version_to` | VARCHAR(20) | NULL |



| `changelog` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `theme_conflicts`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `theme_id` | BIGINT UNSIGNED | FK → themes(id), NOT NULL |



| `conflicting_plugin` | VARCHAR(255) | NULL |



| `description` | TEXT | NULL |



| `reported_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `theme_general_settings`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `theme_id` | BIGINT UNSIGNED | FK → themes(id), NOT NULL |



| `setting_key` | VARCHAR(100) | NULL |



| `setting_value` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `theme_generations`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `theme_id` | BIGINT UNSIGNED | FK → themes(id), NOT NULL |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `theme_name` | VARCHAR(255) | NOT NULL |



| `theme_description` | TEXT | NULL |



| `theme_version` | VARCHAR(20) | NULL |



| `theme_variant` | VARCHAR(50) | NULL |



| `theme_color_scheme` | VARCHAR(50) | NULL |



| `theme_layout` | VARCHAR(50) | NULL |



| `theme_font` | VARCHAR(100) | NULL |



| `theme_customization` | JSON | NULL |



| `theme_preview_url` | VARCHAR(255) | NULL |



| `theme_download_url` | VARCHAR(255) | NULL |



| `theme_screenshot_url` | VARCHAR(255) | NULL |



| `theme_markdown_description` | TEXT | NULL |



| `theme_template_variables` | JSON | NULL |



| `style` | ENUM('light','dark','auto') | DEFAULT 'light' |



| `complexity` | ENUM('simple','moderate','complex') | DEFAULT 'moderate' |



| `source` | ENUM('user_input','ai_generated','imported') | DEFAULT 'user_input' |



| `generation_method` | ENUM('manual','automated','hybrid') | DEFAULT 'manual' |



| `priority` | ENUM('low','medium','high') | DEFAULT 'medium' |



| `estimated_completion_time` | INT | NULL |



| `actual_completion_time` | INT | NULL |



| `progress` | INT | DEFAULT 0 |



| `quality_score` | DECIMAL(3,2) | NULL |



| `status` | ENUM('pending','in_progress','completed','failed') | DEFAULT 'pending' |



| `generated_files` | JSON | NULL |



| `error_message` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `theme_generation_logs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `generation_id` | BIGINT UNSIGNED | FK → theme_generations(id), NOT NULL |



| `message` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### LLM Integration







#### `llm_providers`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `api_key` | VARCHAR(255) | NOT NULL |



| `base_url` | VARCHAR(255) | NOT NULL |



| `status` | ENUM('active','inactive') | DEFAULT 'active' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `llm_provider_settings`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `provider_id` | BIGINT UNSIGNED | FK → llm_providers(id), NOT NULL |



| `setting_key` | VARCHAR(100) | NOT NULL |



| `setting_value` | TEXT | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `llm_provider_activity`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `provider_id` | BIGINT UNSIGNED | FK → llm_providers(id), NOT NULL |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `activity_type` | ENUM('prompt','response','error') | NOT NULL |



| `details` | JSON | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `llm_provider_usage`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `provider_id` | BIGINT UNSIGNED | FK → llm_providers(id), NOT NULL |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `tokens_used` | INT | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `llm_provider_logs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `provider_id` | BIGINT UNSIGNED | FK → llm_providers(id), NOT NULL |



| `user_id` | BIGINT UNSIGNED | NOT NULL |



| `prompt` | TEXT | NOT NULL |



| `response` | TEXT | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### Bot Configuration







#### `bot_configs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `platform` | ENUM('telegram','discord','slack','whatsapp','custom') | NOT NULL |



| `platform_token` | VARCHAR(512) | NOT NULL |



| `platform_username` | VARCHAR(100) | NULL |



| `webhook_url` | VARCHAR(500) | NULL |



| `llm_provider_id` | BIGINT UNSIGNED | FK → llm_providers(id), NULL |



| `llm_system_prompt` | TEXT | NULL |



| `welcome_message` | TEXT | NULL |



| `status` | ENUM('active','inactive') | DEFAULT 'inactive' |



| `allowed_user_ids` | JSON | NULL |



| `rate_limit_per_minute` | INT | DEFAULT 30 |



| `max_conversation_length` | INT | DEFAULT 50 |



| `settings` | JSON | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







### Security & Compliance







#### `security_events`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | FK → users(id), NULL |



| `event_type` | VARCHAR(100) | NOT NULL |



| `description` | TEXT | NULL |



| `ip_address` | VARCHAR(45) | NULL |



| `user_agent` | VARCHAR(500) | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `vulnerability_scans`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `scan_type` | ENUM('full','quick','custom') | NOT NULL |



| `target` | VARCHAR(255) | NOT NULL |



| `status` | ENUM('pending','in_progress','completed','failed') | DEFAULT 'pending' |



| `results` | JSON | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `penetration_tests`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `test_type` | ENUM('external','internal','web_app','mobile_app') | NOT NULL |



| `target` | VARCHAR(255) | NOT NULL |



| `status` | ENUM('pending','in_progress','completed','failed') | DEFAULT 'pending' |



| `findings` | JSON | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `compliance_reports`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `report_type` | ENUM('GDPR','HIPAA','PCI-DSS') | NOT NULL |



| `status` | ENUM('pending','in_progress','completed','failed') | DEFAULT 'pending' |



| `findings` | JSON | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `security_incidents`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `incident_type` | ENUM('data_breach','unauthorized_access','malware_infection') | NOT NULL |



| `description` | TEXT | NOT NULL |



| `status` | ENUM('open','investigating','resolved') | DEFAULT 'open' |



| `reported_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### Logging & Monitoring







#### `audit_trails`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | FK → users(id), NULL |



| `action` | VARCHAR(255) | NULL |


| `entity` | VARCHAR(255) | NULL |


| `entity_id` | BIGINT UNSIGNED | NULL |


| `activity_type` | VARCHAR(100) | NULL |


| `description` | TEXT | NULL |


| `details` | JSON | NULL |


| `old_value` | JSON | NULL |


| `new_value` | JSON | NULL |


| `ip_address` | VARCHAR(45) | NULL |


| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |




#### `api_request_logs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `api_client_id` | BIGINT UNSIGNED | FK → api_clients(id), NULL |



| `order_id` | BIGINT UNSIGNED | NULL |



| `endpoint` | VARCHAR(255) | NULL |



| `method` | VARCHAR(10) | NULL |



| `request_data` | JSON | NULL |



| `response_data` | JSON | NULL |



| `status_code` | INT | NULL |



| `ip_address` | VARCHAR(45) | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### Notifications







#### `notifications`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | FK → users(id), NOT NULL |



| `type` | ENUM('system','subscription','security','product') | NOT NULL |



| `title` | VARCHAR(255) | NOT NULL |



| `message` | TEXT | NOT NULL |



| `data` | JSON | NULL |



| `is_read` | BOOLEAN | DEFAULT FALSE |



| `read_at` | TIMESTAMP | NULL |



| `channel` | ENUM('in_app','email','telegram','sms') | DEFAULT 'in_app' |



| `action_url` | VARCHAR(500) | NULL |



| `action_text` | VARCHAR(255) | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







### System Settings







#### `system_settings`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `key` | VARCHAR(100) | NOT NULL |



| `value` | TEXT | NULL |



| `group` | VARCHAR(50) | DEFAULT 'general' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `system_logs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `level` | ENUM('debug','info','warning','error','critical') | DEFAULT 'info' |



| `message` | TEXT | NULL |



| `context` | JSON | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `email_settings`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `smtp_host` | VARCHAR(255) | NULL |



| `smtp_port` | INT | NULL |



| `smtp_username` | VARCHAR(255) | NULL |



| `smtp_password` | VARCHAR(255) | NULL |



| `from_email` | VARCHAR(255) | NULL |



| `from_name` | VARCHAR(255) | NULL |



| `encryption` | ENUM('ssl','tls','none') | DEFAULT 'none' |



| `is_default` | BOOLEAN | DEFAULT FALSE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `email_templates`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `subject` | VARCHAR(255) | NULL |



| `body` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `payment_gateways`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `description` | TEXT | NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `payment_gateway_settings`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `gateway_id` | BIGINT UNSIGNED | FK → payment_gateways(id), NOT NULL |



| `key` | VARCHAR(100) | NULL |



| `value` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `webhooks`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(255) | NOT NULL |



| `url` | VARCHAR(500) | NOT NULL |



| `events` | JSON | NOT NULL |



| `secret` | VARCHAR(255) | NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `last_triggered_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `feature_flags`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) UNIQUE | NOT NULL |



| `key` | VARCHAR(100) UNIQUE | NOT NULL |



| `description` | TEXT | NULL |



| `enabled` | BOOLEAN | DEFAULT FALSE |



| `conditions` | JSON | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |











---



































### Financial / Accounting (GL)







#### `currencies`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `code` | VARCHAR(3) | NOT NULL |



| `name` | VARCHAR(100) | NOT NULL |



| `symbol` | VARCHAR(10) | NULL |



| `decimal_places` | TINYINT | DEFAULT 2 |



| `is_base` | BOOLEAN | DEFAULT FALSE |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `exchange_rates`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `from_currency_id` | BIGINT UNSIGNED | NOT NULL, FK → currencies(id), ON DELETE CASCADE |



| `to_currency_id` | BIGINT UNSIGNED | NOT NULL, FK → currencies(id), ON DELETE CASCADE |



| `rate` | DECIMAL(15,6) | NOT NULL |



| `date` | DATE | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `fiscal_years`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `start_date` | DATE | NOT NULL |



| `end_date` | DATE | NOT NULL |



| `is_closed` | BOOLEAN | DEFAULT FALSE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `account_periods`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `fiscal_year_id` | BIGINT UNSIGNED | NOT NULL, FK → fiscal_years(id), ON DELETE CASCADE |



| `type` | ENUM('month','quarter','year') | NOT NULL |



| `start_date` | DATE | NOT NULL |



| `end_date` | DATE | NOT NULL |



| `is_closed` | BOOLEAN | DEFAULT FALSE |



| `closed_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `chart_of_accounts`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `parent_id` | BIGINT UNSIGNED | FK → chart_of_accounts(id), ON DELETE SET |



| `account_code` | VARCHAR(20) | NOT NULL |



| `account_name` | VARCHAR(255) | NOT NULL |



| `type` | ENUM('asset','liability','equity','revenue','expense') | NOT NULL |



| `subtype` | VARCHAR(50) | NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `is_control` | BOOLEAN | DEFAULT FALSE |



| `description` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `cost_centers`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `code` | VARCHAR(20) | NOT NULL |



| `name` | VARCHAR(255) | NOT NULL |



| `description` | TEXT | NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `profit_centers`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `code` | VARCHAR(20) | NOT NULL |



| `name` | VARCHAR(255) | NOT NULL |



| `description` | TEXT | NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `journal_entry_types`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `code` | VARCHAR(20) | NOT NULL |



| `description` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `journal_entries`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `entry_number` | VARCHAR(50) | NOT NULL |



| `entry_type_id` | BIGINT UNSIGNED | NOT NULL, FK → journal_entry_types(id), ON DELETE RESTRICT |



| `fiscal_year_id` | BIGINT UNSIGNED | NOT NULL, FK → fiscal_years(id), ON DELETE RESTRICT |



| `account_period_id` | BIGINT UNSIGNED | FK → account_periods(id), ON DELETE SET |



| `entry_date` | DATE | NOT NULL |



| `description` | TEXT | NULL |



| `reference_type` | VARCHAR(50) | NULL |



| `reference_id` | BIGINT UNSIGNED | NULL |



| `created_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `is_posted` | BOOLEAN | DEFAULT FALSE |



| `posted_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `journal_entry_lines`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `journal_entry_id` | BIGINT UNSIGNED | NOT NULL, FK → journal_entries(id), ON DELETE CASCADE |



| `account_id` | BIGINT UNSIGNED | NOT NULL, FK → chart_of_accounts(id), ON DELETE RESTRICT |



| `cost_center_id` | BIGINT UNSIGNED | FK → cost_centers(id), ON DELETE SET |



| `profit_center_id` | BIGINT UNSIGNED | FK → profit_centers(id), ON DELETE SET |



| `debit` | DECIMAL(15,2) | DEFAULT 0.00 |



| `credit` | DECIMAL(15,2) | DEFAULT 0.00 |



| `description` | TEXT | NULL |



| `line_order` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `account_balances`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `account_id` | BIGINT UNSIGNED | NOT NULL, FK → chart_of_accounts(id), ON DELETE CASCADE |



| `fiscal_year_id` | BIGINT UNSIGNED | NOT NULL, FK → fiscal_years(id), ON DELETE CASCADE |



| `account_period_id` | BIGINT UNSIGNED | FK → account_periods(id), ON DELETE CASCADE |



| `period_type` | ENUM('month','quarter','year') | NOT NULL |



| `opening_balance` | DECIMAL(15,2) | DEFAULT 0.00 |



| `period_debit` | DECIMAL(15,2) | DEFAULT 0.00 |



| `period_credit` | DECIMAL(15,2) | DEFAULT 0.00 |



| `closing_balance` | DECIMAL(15,2) | DEFAULT 0.00 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `budgets`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `fiscal_year_id` | BIGINT UNSIGNED | NOT NULL, FK → fiscal_years(id), ON DELETE CASCADE |



| `profit_center_id` | BIGINT UNSIGNED | FK → profit_centers(id), ON DELETE SET |



| `cost_center_id` | BIGINT UNSIGNED | FK → cost_centers(id), ON DELETE SET |



| `name` | VARCHAR(255) | NOT NULL |



| `description` | TEXT | NULL |



| `status` | ENUM('draft','active','locked','closed') | DEFAULT 'draft' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `budget_lines`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `budget_id` | BIGINT UNSIGNED | NOT NULL, FK → budgets(id), ON DELETE CASCADE |



| `account_id` | BIGINT UNSIGNED | NOT NULL, FK → chart_of_accounts(id), ON DELETE RESTRICT |



| `period_id` | BIGINT UNSIGNED | FK → account_periods(id), ON DELETE CASCADE |



| `amount` | DECIMAL(15,2) | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `budget_versions`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `budget_id` | BIGINT UNSIGNED | NOT NULL, FK → budgets(id), ON DELETE CASCADE |



| `version` | INT | NOT NULL |



| `notes` | TEXT | NULL |



| `snapshot` | JSON | NULL |



| `created_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `cost_allocations`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `source_cost_center_id` | BIGINT UNSIGNED | NOT NULL, FK → cost_centers(id), ON DELETE CASCADE |



| `target_cost_center_id` | BIGINT UNSIGNED | NOT NULL, FK → cost_centers(id), ON DELETE CASCADE |



| `account_id` | BIGINT UNSIGNED | NOT NULL, FK → chart_of_accounts(id), ON DELETE RESTRICT |



| `allocation_method` | ENUM('percentage','fixed','activity_based') | NOT NULL, DEFAULT 'percentage' |



| `allocation_value` | DECIMAL(15,4) | NOT NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |











### Inventory & Warehouse







#### `warehouses`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(255) | NOT NULL |



| `code` | VARCHAR(20) | NOT NULL |



| `address` | TEXT | NULL |



| `city` | VARCHAR(100) | NULL |



| `state` | VARCHAR(100) | NULL |



| `country` | VARCHAR(2) | NULL |



| `postal_code` | VARCHAR(20) | NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `warehouse_locations`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `warehouse_id` | BIGINT UNSIGNED | NOT NULL, FK → warehouses(id), ON DELETE CASCADE |



| `parent_id` | BIGINT UNSIGNED | FK → warehouse_locations(id), ON DELETE SET |



| `code` | VARCHAR(50) | NOT NULL |



| `name` | VARCHAR(255) | NULL |



| `type` | ENUM('aisle','rack','shelf','bin','bulk') | DEFAULT 'bin' |



| `max_weight` | DECIMAL(10,2) | NULL |



| `max_volume` | DECIMAL(10,2) | NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `stock_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE CASCADE |



| `warehouse_location_id` | BIGINT UNSIGNED | NOT NULL, FK → warehouse_locations(id), ON DELETE RESTRICT |



| `serial_number` | VARCHAR(100) | NULL |



| `batch_number` | VARCHAR(100) | NULL |



| `quantity` | DECIMAL(15,4) | NOT NULL, DEFAULT 0 |



| `reserved_quantity` | DECIMAL(15,4) | DEFAULT 0 |



| `unit_cost` | DECIMAL(15,2) | NULL |



| `expiry_date` | DATE | NULL |



| `status` | ENUM('available','reserved','quarantine','damaged','disposed') | DEFAULT 'available' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `inventory_movements`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE CASCADE |



| `from_location_id` | BIGINT UNSIGNED | FK → warehouse_locations(id), ON DELETE SET |



| `to_location_id` | BIGINT UNSIGNED | FK → warehouse_locations(id), ON DELETE SET |



| `stock_item_id` | BIGINT UNSIGNED | FK → stock_items(id), ON DELETE SET |



| `movement_type` | ENUM('receipt','issue','transfer','adjustment','return','sale') | NOT NULL |



| `reference_type` | VARCHAR(50) | NULL |



| `reference_id` | BIGINT UNSIGNED | NULL |



| `quantity` | DECIMAL(15,4) | NOT NULL |



| `unit_cost` | DECIMAL(15,2) | NULL |



| `notes` | TEXT | NULL |



| `created_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `inventory_adjustments`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE CASCADE |



| `warehouse_location_id` | BIGINT UNSIGNED | NOT NULL, FK → warehouse_locations(id), ON DELETE CASCADE |



| `adjustment_type` | ENUM('count','damage','write_off','return','reclassification') | NOT NULL |



| `expected_qty` | DECIMAL(15,4) | NOT NULL |



| `actual_qty` | DECIMAL(15,4) | NOT NULL |



| `difference` | DECIMAL(15,4) | NOT NULL |



| `reason` | TEXT | NULL |



| `approved_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `stock_counts`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `warehouse_id` | BIGINT UNSIGNED | NOT NULL, FK → warehouses(id), ON DELETE CASCADE |



| `count_date` | DATE | NOT NULL |



| `status` | ENUM('planned','in_progress','completed','verified') | DEFAULT 'planned' |



| `counted_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `verified_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `stock_count_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `stock_count_id` | BIGINT UNSIGNED | NOT NULL, FK → stock_counts(id), ON DELETE CASCADE |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE CASCADE |



| `location_id` | BIGINT UNSIGNED | NOT NULL, FK → warehouse_locations(id), ON DELETE CASCADE |



| `expected_qty` | DECIMAL(15,4) | NOT NULL |



| `counted_qty` | DECIMAL(15,4) | NULL |



| `difference` | DECIMAL(15,4) GENERATED ALWAYS AS (counted_qty - expected_qty) STORED | NULL |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `reorder_rules`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE CASCADE |



| `warehouse_id` | BIGINT UNSIGNED | NOT NULL, FK → warehouses(id), ON DELETE CASCADE |



| `min_quantity` | DECIMAL(15,4) | NOT NULL |



| `max_quantity` | DECIMAL(15,4) | NOT NULL |



| `reorder_point` | DECIMAL(15,4) | NOT NULL |



| `reorder_qty` | DECIMAL(15,4) | NOT NULL |



| `lead_time_days` | INT | DEFAULT 0 |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `transfer_orders`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `from_warehouse_id` | BIGINT UNSIGNED | NOT NULL, FK → warehouses(id), ON DELETE RESTRICT |



| `to_warehouse_id` | BIGINT UNSIGNED | NOT NULL, FK → warehouses(id), ON DELETE RESTRICT |



| `transfer_number` | VARCHAR(50) | NOT NULL |



| `status` | ENUM('draft','pending','approved','in_transit','completed','cancelled') | DEFAULT 'draft' |



| `requested_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `approved_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `transfer_order_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `transfer_order_id` | BIGINT UNSIGNED | NOT NULL, FK → transfer_orders(id), ON DELETE CASCADE |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE CASCADE |



| `stock_item_id` | BIGINT UNSIGNED | FK → stock_items(id), ON DELETE SET |



| `quantity` | DECIMAL(15,4) | NOT NULL |



| `received_qty` | DECIMAL(15,4) | DEFAULT 0 |



| `unit_cost` | DECIMAL(15,2) | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |











### Procurement







#### `suppliers`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `company_name` | VARCHAR(255) | NOT NULL |



| `supplier_code` | VARCHAR(20) | NOT NULL |



| `contact_name` | VARCHAR(255) | NULL |



| `email` | VARCHAR(255) | NULL |



| `phone` | VARCHAR(50) | NULL |



| `website` | VARCHAR(500) | NULL |



| `address` | TEXT | NULL |



| `city` | VARCHAR(100) | NULL |



| `state` | VARCHAR(100) | NULL |



| `country` | VARCHAR(2) | NULL |



| `postal_code` | VARCHAR(20) | NULL |



| `tax_id` | VARCHAR(100) | NULL |



| `payment_terms` | VARCHAR(100) | NULL |



| `currency_id` | BIGINT UNSIGNED | FK → currencies(id), ON DELETE SET |



| `status` | ENUM('active','inactive','suspended') | DEFAULT 'active' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `supplier_contacts`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `supplier_id` | BIGINT UNSIGNED | NOT NULL, FK → suppliers(id), ON DELETE CASCADE |



| `first_name` | VARCHAR(100) | NOT NULL |



| `last_name` | VARCHAR(100) | NOT NULL |



| `job_title` | VARCHAR(255) | NULL |



| `email` | VARCHAR(255) | NULL |



| `phone` | VARCHAR(50) | NULL |



| `is_primary` | BOOLEAN | DEFAULT FALSE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `supplier_products`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `supplier_id` | BIGINT UNSIGNED | NOT NULL, FK → suppliers(id), ON DELETE CASCADE |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE CASCADE |



| `supplier_sku` | VARCHAR(100) | NULL |



| `lead_time_days` | INT | NULL |



| `moq` | INT | NULL |



| `is_preferred` | BOOLEAN | DEFAULT FALSE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `supplier_pricelists`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `supplier_product_id` | BIGINT UNSIGNED | NOT NULL, FK → supplier_products(id), ON DELETE CASCADE |



| `unit_price` | DECIMAL(15,2) | NOT NULL |



| `currency_id` | BIGINT UNSIGNED | NOT NULL, FK → currencies(id), ON DELETE RESTRICT |



| `min_quantity` | INT | DEFAULT 1 |



| `effective_from` | DATE | NOT NULL |



| `effective_until` | DATE | NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `purchase_orders`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `supplier_id` | BIGINT UNSIGNED | NOT NULL, FK → suppliers(id), ON DELETE RESTRICT |



| `order_number` | VARCHAR(50) | NOT NULL |



| `status` | ENUM('draft','pending_approval','approved','sent','partial','received','cancelled') | DEFAULT 'draft' |



| `order_date` | DATE | NOT NULL |



| `expected_date` | DATE | NULL |



| `subtotal` | DECIMAL(15,2) | DEFAULT 0.00 |



| `tax` | DECIMAL(15,2) | DEFAULT 0.00 |



| `total` | DECIMAL(15,2) | DEFAULT 0.00 |



| `currency_id` | BIGINT UNSIGNED | NOT NULL, FK → currencies(id), ON DELETE RESTRICT |



| `notes` | TEXT | NULL |



| `requested_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `approved_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `purchase_order_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `purchase_order_id` | BIGINT UNSIGNED | NOT NULL, FK → purchase_orders(id), ON DELETE CASCADE |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE RESTRICT |



| `warehouse_location_id` | BIGINT UNSIGNED | FK → warehouse_locations(id), ON DELETE SET |



| `description` | TEXT | NULL |



| `quantity` | DECIMAL(15,4) | NOT NULL |



| `received_qty` | DECIMAL(15,4) | DEFAULT 0 |



| `unit_price` | DECIMAL(15,2) | NOT NULL |



| `tax_rate` | DECIMAL(5,2) | DEFAULT 0.00 |



| `subtotal` | DECIMAL(15,2) | NOT NULL |



| `line_order` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `purchase_receipts`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `purchase_order_id` | BIGINT UNSIGNED | NOT NULL, FK → purchase_orders(id), ON DELETE CASCADE |



| `receipt_number` | VARCHAR(50) | NOT NULL |



| `received_date` | DATE | NOT NULL |



| `status` | ENUM('draft','completed','partial','cancelled') | DEFAULT 'draft' |



| `notes` | TEXT | NULL |



| `received_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `purchase_receipt_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `purchase_receipt_id` | BIGINT UNSIGNED | NOT NULL, FK → purchase_receipts(id), ON DELETE CASCADE |



| `po_item_id` | BIGINT UNSIGNED | NOT NULL, FK → purchase_order_items(id), ON DELETE CASCADE |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE RESTRICT |



| `warehouse_location_id` | BIGINT UNSIGNED | NOT NULL, FK → warehouse_locations(id), ON DELETE RESTRICT |



| `quantity` | DECIMAL(15,4) | NOT NULL |



| `unit_cost` | DECIMAL(15,2) | NULL |



| `batch_number` | VARCHAR(100) | NULL |



| `expiry_date` | DATE | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `purchase_invoices`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `purchase_order_id` | BIGINT UNSIGNED | NOT NULL, FK → purchase_orders(id), ON DELETE CASCADE |



| `supplier_id` | BIGINT UNSIGNED | NOT NULL, FK → suppliers(id), ON DELETE RESTRICT |



| `invoice_number` | VARCHAR(100) | NOT NULL |



| `invoice_date` | DATE | NOT NULL |



| `due_date` | DATE | NULL |



| `subtotal` | DECIMAL(15,2) | DEFAULT 0.00 |



| `tax` | DECIMAL(15,2) | DEFAULT 0.00 |



| `total` | DECIMAL(15,2) | DEFAULT 0.00 |



| `currency_id` | BIGINT UNSIGNED | NOT NULL, FK → currencies(id), ON DELETE RESTRICT |



| `status` | ENUM('pending','approved','paid','overdue','cancelled') | DEFAULT 'pending' |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `purchase_invoice_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `purchase_invoice_id` | BIGINT UNSIGNED | NOT NULL, FK → purchase_invoices(id), ON DELETE CASCADE |



| `po_item_id` | BIGINT UNSIGNED | NOT NULL, FK → purchase_order_items(id), ON DELETE CASCADE |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE RESTRICT |



| `quantity` | DECIMAL(15,4) | NOT NULL |



| `unit_price` | DECIMAL(15,2) | NOT NULL |



| `subtotal` | DECIMAL(15,2) | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `rfqs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `rfq_number` | VARCHAR(50) | NOT NULL |



| `title` | VARCHAR(255) | NOT NULL |



| `description` | TEXT | NULL |



| `issue_date` | DATE | NOT NULL |



| `closing_date` | DATE | NULL |



| `status` | ENUM('draft','sent','received','evaluating','awarded','cancelled') | DEFAULT 'draft' |



| `created_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `rfq_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `rfq_id` | BIGINT UNSIGNED | NOT NULL, FK → rfqs(id), ON DELETE CASCADE |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE CASCADE |



| `quantity` | DECIMAL(15,4) | NOT NULL |



| `notes` | TEXT | NULL |



| `line_order` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `supplier_quotations`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `rfq_id` | BIGINT UNSIGNED | NOT NULL, FK → rfqs(id), ON DELETE CASCADE |



| `supplier_id` | BIGINT UNSIGNED | NOT NULL, FK → suppliers(id), ON DELETE RESTRICT |



| `quotation_number` | VARCHAR(50) | NULL |



| `quotation_date` | DATE | NOT NULL |



| `valid_until` | DATE | NULL |



| `subtotal` | DECIMAL(15,2) | DEFAULT 0.00 |



| `tax` | DECIMAL(15,2) | DEFAULT 0.00 |



| `total` | DECIMAL(15,2) | DEFAULT 0.00 |



| `currency_id` | BIGINT UNSIGNED | NOT NULL, FK → currencies(id), ON DELETE RESTRICT |



| `status` | ENUM('received','evaluated','accepted','rejected') | DEFAULT 'received' |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `quotation_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `quotation_id` | BIGINT UNSIGNED | NOT NULL, FK → supplier_quotations(id), ON DELETE CASCADE |



| `rfq_item_id` | BIGINT UNSIGNED | NOT NULL, FK → rfq_items(id), ON DELETE CASCADE |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE RESTRICT |



| `quantity` | DECIMAL(15,4) | NOT NULL |



| `unit_price` | DECIMAL(15,2) | NOT NULL |



| `subtotal` | DECIMAL(15,2) | NOT NULL |



| `line_order` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |











### Production & CRP







#### `work_centers`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `code` | VARCHAR(20) | NOT NULL |



| `name` | VARCHAR(255) | NOT NULL |



| `type` | ENUM('machine','workstation','assembly_line','manual') | NOT NULL |



| `description` | TEXT | NULL |



| `cost_per_hour` | DECIMAL(15,2) | DEFAULT 0.00 |



| `efficiency_rate` | DECIMAL(5,2) | DEFAULT 100.00 |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `work_center_capacity`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `work_center_id` | BIGINT UNSIGNED | NOT NULL, FK → work_centers(id), ON DELETE CASCADE |



| `capacity_date` | DATE | NOT NULL |



| `available_hours` | DECIMAL(8,2) | NOT NULL |



| `maintenance_hours` | DECIMAL(8,2) | DEFAULT 0 |



| `booked_hours` | DECIMAL(8,2) | DEFAULT 0 |



| `overtime_hours` | DECIMAL(8,2) | DEFAULT 0 |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `bill_of_materials`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE CASCADE |



| `name` | VARCHAR(255) | NOT NULL |



| `version` | VARCHAR(20) | DEFAULT '1.0' |



| `quantity` | DECIMAL(15,4) | DEFAULT 1 |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `bom_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `bom_id` | BIGINT UNSIGNED | NOT NULL, FK → bill_of_materials(id), ON DELETE CASCADE |



| `component_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE RESTRICT |



| `quantity` | DECIMAL(15,4) | NOT NULL |



| `unit` | VARCHAR(20) | NULL |



| `scrap_rate` | DECIMAL(5,2) | DEFAULT 0.00 |



| `line_order` | INT | DEFAULT 0 |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `routings`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `bom_id` | BIGINT UNSIGNED | NOT NULL, FK → bill_of_materials(id), ON DELETE CASCADE |



| `name` | VARCHAR(255) | NOT NULL |



| `total_time` | DECIMAL(8,2) | NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `routing_steps`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `routing_id` | BIGINT UNSIGNED | NOT NULL, FK → routings(id), ON DELETE CASCADE |



| `work_center_id` | BIGINT UNSIGNED | NOT NULL, FK → work_centers(id), ON DELETE RESTRICT |



| `step_name` | VARCHAR(255) | NOT NULL |



| `step_order` | INT | NOT NULL |



| `setup_time` | DECIMAL(8,2) | DEFAULT 0 |



| `run_time` | DECIMAL(8,2) | DEFAULT 0 |



| `teardown_time` | DECIMAL(8,2) | DEFAULT 0 |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `production_orders`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE RESTRICT |



| `bom_id` | BIGINT UNSIGNED | NOT NULL, FK → bill_of_materials(id), ON DELETE RESTRICT |



| `routing_id` | BIGINT UNSIGNED | FK → routings(id), ON DELETE SET |



| `warehouse_id` | BIGINT UNSIGNED | FK → warehouses(id), ON DELETE SET |



| `order_number` | VARCHAR(50) | NOT NULL |



| `quantity` | DECIMAL(15,4) | NOT NULL |



| `produced_qty` | DECIMAL(15,4) | DEFAULT 0 |



| `scrap_qty` | DECIMAL(15,4) | DEFAULT 0 |



| `status` | ENUM('planned','released','in_progress','completed','cancelled','on_hold') | DEFAULT 'planned' |



| `priority` | ENUM('low','medium','high','urgent') | DEFAULT 'medium' |



| `scheduled_start` | DATETIME | NULL |



| `scheduled_end` | DATETIME | NULL |



| `actual_start` | DATETIME | NULL |



| `actual_end` | DATETIME | NULL |



| `notes` | TEXT | NULL |



| `created_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `production_order_steps`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `production_order_id` | BIGINT UNSIGNED | NOT NULL, FK → production_orders(id), ON DELETE CASCADE |



| `routing_step_id` | BIGINT UNSIGNED | NOT NULL, FK → routing_steps(id), ON DELETE RESTRICT |



| `work_center_id` | BIGINT UNSIGNED | NOT NULL, FK → work_centers(id), ON DELETE RESTRICT |



| `status` | ENUM('pending','in_progress','completed','skipped') | DEFAULT 'pending' |



| `actual_setup_time` | DECIMAL(8,2) | NULL |



| `actual_run_time` | DECIMAL(8,2) | NULL |



| `completed_qty` | DECIMAL(15,4) | DEFAULT 0 |



| `scrap_qty` | DECIMAL(15,4) | DEFAULT 0 |



| `started_at` | DATETIME | NULL |



| `completed_at` | DATETIME | NULL |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `production_outputs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `production_order_id` | BIGINT UNSIGNED | NOT NULL, FK → production_orders(id), ON DELETE CASCADE |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE RESTRICT |



| `warehouse_location_id` | BIGINT UNSIGNED | NOT NULL, FK → warehouse_locations(id), ON DELETE RESTRICT |



| `quantity` | DECIMAL(15,4) | NOT NULL |



| `unit_cost` | DECIMAL(15,2) | NULL |



| `batch_number` | VARCHAR(100) | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `production_material_issues`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `production_order_id` | BIGINT UNSIGNED | NOT NULL, FK → production_orders(id), ON DELETE CASCADE |



| `stock_item_id` | BIGINT UNSIGNED | FK → stock_items(id), ON DELETE SET |



| `product_id` | BIGINT UNSIGNED | NOT NULL, FK → products(id), ON DELETE RESTRICT |



| `warehouse_location_id` | BIGINT UNSIGNED | NOT NULL, FK → warehouse_locations(id), ON DELETE RESTRICT |



| `quantity` | DECIMAL(15,4) | NOT NULL |



| `unit_cost` | DECIMAL(15,2) | NULL |



| `issued_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







#### `capacity_plans`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `work_center_id` | BIGINT UNSIGNED | NOT NULL, FK → work_centers(id), ON DELETE CASCADE |



| `plan_date` | DATE | NOT NULL |



| `planned_hours` | DECIMAL(8,2) | NOT NULL |



| `actual_hours` | DECIMAL(8,2) | DEFAULT 0 |



| `available_hours` | DECIMAL(8,2) | NOT NULL |



| `load_percentage` | DECIMAL(5,2) GENERATED ALWAYS AS ((planned_hours / | NULL |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `maintenance_schedules`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `work_center_id` | BIGINT UNSIGNED | NOT NULL, FK → work_centers(id), ON DELETE CASCADE |



| `title` | VARCHAR(255) | NOT NULL |



| `type` | ENUM('preventive','predictive','corrective','emergency') | NOT NULL |



| `frequency` | ENUM('daily','weekly','monthly','quarterly','yearly','hours') | NOT NULL |



| `frequency_value` | INT | NULL |



| `last_done_at` | DATETIME | NULL |



| `next_due_at` | DATETIME | NULL |



| `estimated_hours` | DECIMAL(8,2) | NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `maintenance_logs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `maintenance_schedule_id` | BIGINT UNSIGNED | FK → maintenance_schedules(id), ON DELETE SET |



| `work_center_id` | BIGINT UNSIGNED | NOT NULL, FK → work_centers(id), ON DELETE CASCADE |



| `title` | VARCHAR(255) | NOT NULL |



| `description` | TEXT | NULL |



| `type` | ENUM('preventive','predictive','corrective','emergency') | NOT NULL |



| `status` | ENUM('planned','in_progress','completed','cancelled') | DEFAULT 'planned' |



| `started_at` | DATETIME | NULL |



| `completed_at` | DATETIME | NULL |



| `duration_hours` | DECIMAL(8,2) | NULL |



| `cost` | DECIMAL(15,2) | NULL |



| `performed_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |











### Human Resources & Payroll







#### `departments`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `parent_id` | BIGINT UNSIGNED | FK → departments(id), ON DELETE SET |



| `code` | VARCHAR(20) | NOT NULL |



| `name` | VARCHAR(255) | NOT NULL |



| `manager_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `job_positions`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `department_id` | BIGINT UNSIGNED | NOT NULL, FK → departments(id), ON DELETE CASCADE |



| `title` | VARCHAR(255) | NOT NULL |



| `description` | TEXT | NULL |



| `requirements` | TEXT | NULL |



| `salary_min` | DECIMAL(15,2) | NULL |



| `salary_max` | DECIMAL(15,2) | NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `employees`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `user_id` | BIGINT UNSIGNED | NOT NULL, FK → users(id), ON DELETE CASCADE |



| `employee_number` | VARCHAR(20) | NOT NULL |



| `department_id` | BIGINT UNSIGNED | NOT NULL, FK → departments(id), ON DELETE RESTRICT |



| `job_position_id` | BIGINT UNSIGNED | NOT NULL, FK → job_positions(id), ON DELETE RESTRICT |



| `reports_to` | BIGINT UNSIGNED | FK → employees(id), ON DELETE SET |



| `hire_date` | DATE | NOT NULL |



| `termination_date` | DATE | NULL |



| `employment_type` | ENUM('full_time','part_time','contract','intern','temporary') | NOT NULL, DEFAULT 'full_time' |



| `status` | ENUM('active','on_leave','terminated','suspended') | DEFAULT 'active' |



| `base_salary` | DECIMAL(15,2) | NULL |



| `currency_id` | BIGINT UNSIGNED | NOT NULL, FK → currencies(id), ON DELETE RESTRICT |



| `emergency_contact` | JSON | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `employee_contracts`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `employee_id` | BIGINT UNSIGNED | NOT NULL, FK → employees(id), ON DELETE CASCADE |



| `contract_type` | ENUM('permanent','fixed_term','probation','consulting') | NOT NULL |



| `start_date` | DATE | NOT NULL |



| `end_date` | DATE | NULL |



| `salary` | DECIMAL(15,2) | NOT NULL |



| `currency_id` | BIGINT UNSIGNED | NOT NULL, FK → currencies(id), ON DELETE RESTRICT |



| `benefits` | JSON | NULL |



| `documents` | JSON | NULL |



| `status` | ENUM('active','expired','terminated') | DEFAULT 'active' |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `employee_documents`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `employee_id` | BIGINT UNSIGNED | NOT NULL, FK → employees(id), ON DELETE CASCADE |



| `document_type` | ENUM('id','passport','visa','certificate','contract','other') | NOT NULL |



| `file_name` | VARCHAR(255) | NOT NULL |



| `file_path` | VARCHAR(500) | NOT NULL |



| `expiry_date` | DATE | NULL |



| `is_verified` | BOOLEAN | DEFAULT FALSE |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `attendance`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `employee_id` | BIGINT UNSIGNED | NOT NULL, FK → employees(id), ON DELETE CASCADE |



| `date` | DATE | NOT NULL |



| `clock_in` | DATETIME | NULL |



| `clock_out` | DATETIME | NULL |



| `total_hours` | DECIMAL(5,2) GENERATED ALWAYS AS (TIMESTAMPDIFF(MINUTE, clock_in, clock_out) / 60) STORED | NULL |



| `status` | ENUM('present','absent','late','half_day','holiday') | DEFAULT 'present' |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `leave_types`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `code` | VARCHAR(20) | NOT NULL |



| `days_allowed` | INT | NOT NULL |



| `is_paid` | BOOLEAN | DEFAULT TRUE |



| `carry_forward` | BOOLEAN | DEFAULT FALSE |



| `max_carry_days` | INT | NULL |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `leave_requests`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `employee_id` | BIGINT UNSIGNED | NOT NULL, FK → employees(id), ON DELETE CASCADE |



| `leave_type_id` | BIGINT UNSIGNED | NOT NULL, FK → leave_types(id), ON DELETE RESTRICT |



| `start_date` | DATE | NOT NULL |



| `end_date` | DATE | NOT NULL |



| `total_days` | INT GENERATED ALWAYS AS (DATEDIFF(end_date, start_date) + 1) STORED | NULL |



| `reason` | TEXT | NULL |



| `status` | ENUM('pending','approved','rejected','cancelled') | DEFAULT 'pending' |



| `approved_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `approved_at` | TIMESTAMP | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `leave_balances`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `employee_id` | BIGINT UNSIGNED | NOT NULL, FK → employees(id), ON DELETE CASCADE |



| `leave_type_id` | BIGINT UNSIGNED | NOT NULL, FK → leave_types(id), ON DELETE RESTRICT |



| `year` | YEAR | NOT NULL |



| `total_days` | DECIMAL(5,1) | NOT NULL |



| `used_days` | DECIMAL(5,1) | DEFAULT 0 |



| `pending_days` | DECIMAL(5,1) | DEFAULT 0 |



| `remaining_days` | DECIMAL(5,1) GENERATED ALWAYS AS (total_days - used_days - pending_days) STORED | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `timesheets`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `employee_id` | BIGINT UNSIGNED | NOT NULL, FK → employees(id), ON DELETE CASCADE |



| `date` | DATE | NOT NULL |



| `start_time` | TIME | NOT NULL |



| `end_time` | TIME | NULL |



| `total_hours` | DECIMAL(5,2) | NULL |



| `break_hours` | DECIMAL(4,2) | DEFAULT 0 |



| `description` | TEXT | NULL |



| `is_approved` | BOOLEAN | DEFAULT FALSE |



| `approved_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `payroll_components`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `name` | VARCHAR(100) | NOT NULL |



| `code` | VARCHAR(20) | NOT NULL |



| `type` | ENUM('earning','deduction','employer_contribution') | NOT NULL |



| `calculation` | ENUM('fixed','percentage_of_basic','percentage_of_gross','formula') | NOT NULL, DEFAULT 'fixed' |



| `value` | DECIMAL(15,2) | NULL |



| `is_taxable` | BOOLEAN | DEFAULT TRUE |



| `is_active` | BOOLEAN | DEFAULT TRUE |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `payroll_runs`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `fiscal_year_id` | BIGINT UNSIGNED | NOT NULL, FK → fiscal_years(id), ON DELETE RESTRICT |



| `account_period_id` | BIGINT UNSIGNED | FK → account_periods(id), ON DELETE SET |



| `run_number` | VARCHAR(50) | NOT NULL |



| `period_start` | DATE | NOT NULL |



| `period_end` | DATE | NOT NULL |



| `payment_date` | DATE | NULL |



| `status` | ENUM('draft','processing','completed','cancelled') | DEFAULT 'draft' |



| `total_gross` | DECIMAL(15,2) | DEFAULT 0.00 |



| `total_deductions` | DECIMAL(15,2) | DEFAULT 0.00 |



| `total_net` | DECIMAL(15,2) | DEFAULT 0.00 |



| `notes` | TEXT | NULL |



| `processed_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `payroll_items`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `payroll_run_id` | BIGINT UNSIGNED | NOT NULL, FK → payroll_runs(id), ON DELETE CASCADE |



| `employee_id` | BIGINT UNSIGNED | NOT NULL, FK → employees(id), ON DELETE RESTRICT |



| `gross_pay` | DECIMAL(15,2) | NOT NULL |



| `total_deductions` | DECIMAL(15,2) | DEFAULT 0.00 |



| `net_pay` | DECIMAL(15,2) | NOT NULL |



| `bank_account` | VARCHAR(100) | NULL |



| `payment_method` | ENUM('bank_transfer','check','cash') | DEFAULT 'bank_transfer' |



| `status` | ENUM('pending','paid','failed') | DEFAULT 'pending' |



| `paid_at` | TIMESTAMP | NULL |



| `notes` | TEXT | NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |







#### `payroll_item_details`



| Column | Type | Constraints |



|---|---|---|



| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |



| `payroll_item_id` | BIGINT UNSIGNED | NOT NULL, FK → payroll_items(id), ON DELETE CASCADE |



| `payroll_component_id` | BIGINT UNSIGNED | NOT NULL, FK → payroll_components(id), ON DELETE RESTRICT |



| `amount` | DECIMAL(15,2) | NOT NULL |



| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |







---








### Commerce & Platform Enhancements

#### `carts`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE CASCADE, NULL |
| `session_id` | VARCHAR(100) | NULL |
| `coupon_id` | BIGINT UNSIGNED | FK → coupons(id), ON DELETE SET NULL, NULL |
| `notes` | TEXT | NULL |
| `expires_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `cart_items`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `cart_id` | BIGINT UNSIGNED | FK → carts(id), ON DELETE CASCADE, NOT NULL |
| `product_id` | BIGINT UNSIGNED | FK → products(id), ON DELETE SET NULL, NULL |
| `plan_id` | BIGINT UNSIGNED | FK → subscription_plans(id), ON DELETE SET NULL, NULL |
| `quantity` | INT UNSIGNED | DEFAULT 1 |
| `unit_price` | DECIMAL(15,2) | NOT NULL |
| `subtotal` | DECIMAL(15,2) | GENERATED ALWAYS AS (quantity * unit_price) STORED |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

#### `coupons`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `code` | VARCHAR(50) | NOT NULL, UNIQUE |
| `description` | TEXT | NULL |
| `discount_type` | ENUM('percentage','fixed') | NOT NULL |
| `discount_value` | DECIMAL(15,2) | NOT NULL |
| `min_order_amount` | DECIMAL(15,2) | NULL |
| `max_uses` | INT UNSIGNED | NULL |
| `used_count` | INT UNSIGNED | DEFAULT 0 |
| `max_uses_per_user` | INT UNSIGNED | DEFAULT 1, NULL |
| `is_active` | BOOLEAN | DEFAULT TRUE |
| `starts_at` | TIMESTAMP | NULL |
| `expires_at` | TIMESTAMP | NULL |
| `product_ids` | JSON | NULL |
| `excluded_product_ids` | JSON | NULL |
| `created_by` | BIGINT UNSIGNED | FK → users(id), NOT NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `wishlists`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE CASCADE, NOT NULL |
| `name` | VARCHAR(100) | DEFAULT 'Default' |
| `is_public` | BOOLEAN | DEFAULT FALSE |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `wishlist_items`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `wishlist_id` | BIGINT UNSIGNED | FK → wishlists(id), ON DELETE CASCADE, NOT NULL |
| `product_id` | BIGINT UNSIGNED | FK → products(id), ON DELETE CASCADE, NOT NULL |
| `notes` | TEXT | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

#### `reviews`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `reviewable_type` | VARCHAR(50) | NOT NULL |
| `reviewable_id` | BIGINT UNSIGNED | NOT NULL |
| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE CASCADE, NOT NULL |
| `order_id` | BIGINT UNSIGNED | FK → orders(id), ON DELETE SET NULL, NULL |
| `rating` | TINYINT UNSIGNED | NOT NULL |
| `title` | VARCHAR(255) | NULL |
| `body` | TEXT | NULL |
| `is_approved` | BOOLEAN | DEFAULT FALSE |
| `is_verified_purchase` | BOOLEAN | DEFAULT FALSE |
| `helpful_count` | INT UNSIGNED | DEFAULT 0 |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `refunds`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `order_id` | BIGINT UNSIGNED | FK → orders(id), ON DELETE CASCADE, NOT NULL |
| `order_item_id` | BIGINT UNSIGNED | FK → order_items(id), ON DELETE SET NULL, NULL |
| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE CASCADE, NOT NULL |
| `amount` | DECIMAL(15,2) | NOT NULL |
| `reason` | TEXT | NULL |
| `status` | ENUM('pending','approved','processed','rejected') | DEFAULT 'pending' |
| `payment_method` | VARCHAR(50) | NULL |
| `transaction_ref` | VARCHAR(255) | NULL |
| `processed_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET NULL, NULL |
| `processed_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `return_requests`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `order_id` | BIGINT UNSIGNED | FK → orders(id), ON DELETE CASCADE, NOT NULL |
| `order_item_id` | BIGINT UNSIGNED | FK → order_items(id), ON DELETE SET NULL, NULL |
| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE CASCADE, NOT NULL |
| `reason` | ENUM('defective','wrong_item','not_as_described','changed_mind','other') | NOT NULL |
| `description` | TEXT | NULL |
| `status` | ENUM('pending','approved','received','rejected','refunded') | DEFAULT 'pending' |
| `resolution` | ENUM('refund','replacement','store_credit') | DEFAULT 'refund' |
| `admin_notes` | TEXT | NULL |
| `processed_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET NULL, NULL |
| `processed_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `user_addresses`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE CASCADE, NOT NULL |
| `label` | VARCHAR(50) | NULL |
| `first_name` | VARCHAR(100) | NULL |
| `last_name` | VARCHAR(100) | NULL |
| `full_name` | VARCHAR(255) | NULL |
| `phone` | VARCHAR(50) | NULL |
| `address_line1` | VARCHAR(255) | NOT NULL |
| `address_line2` | VARCHAR(255) | NULL |
| `city` | VARCHAR(100) | NOT NULL |
| `state` | VARCHAR(100) | NULL |
| `postal_code` | VARCHAR(20) | NULL |
| `country` | VARCHAR(100) | NOT NULL |
| `is_default_billing` | BOOLEAN | DEFAULT FALSE |
| `is_default_shipping` | BOOLEAN | DEFAULT FALSE |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `user_payment_methods`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE CASCADE, NOT NULL |
| `gateway_id` | BIGINT UNSIGNED | FK → payment_gateways(id), ON DELETE SET NULL, NULL |
| `method_type` | ENUM('card','paypal','bank','crypto') | NOT NULL |
| `gateway_token` | VARCHAR(500) | NULL |
| `display_name` | VARCHAR(100) | NULL |
| `last_four` | VARCHAR(4) | NULL |
| `expiry_month` | VARCHAR(2) | NULL |
| `expiry_year` | VARCHAR(4) | NULL |
| `card_brand` | VARCHAR(50) | NULL |
| `is_default` | BOOLEAN | DEFAULT FALSE |
| `billing_address_id` | BIGINT UNSIGNED | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `personal_access_tokens`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE CASCADE, NOT NULL |
| `name` | VARCHAR(255) | NOT NULL |
| `token` | VARCHAR(64) | NOT NULL, UNIQUE |
| `abilities` | JSON | NULL |
| `last_used_at` | TIMESTAMP | NULL |
| `expires_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `sessions`

| Column | Type | Constraints |
|---|---|---|
| `id` | VARCHAR(128) | PK |
| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE CASCADE, NULL |
| `ip_address` | VARCHAR(45) | NULL |
| `user_agent` | TEXT | NULL |
| `payload` | TEXT | NOT NULL |
| `last_activity` | INT UNSIGNED | NOT NULL |

#### `social_accounts`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE CASCADE, NOT NULL |
| `provider` | VARCHAR(50) | NOT NULL |
| `provider_id` | VARCHAR(255) | NOT NULL |
| `provider_email` | VARCHAR(255) | NULL |
| `avatar_url` | VARCHAR(500) | NULL |
| `access_token` | TEXT | NULL |
| `refresh_token` | TEXT | NULL |
| `token_expires_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `user_devices`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE CASCADE, NOT NULL |
| `platform` | VARCHAR(20) | NOT NULL |
| `device_token` | VARCHAR(500) | NOT NULL, UNIQUE |
| `device_name` | VARCHAR(255) | NULL |
| `fingerprint` | VARCHAR(255) | NULL |
| `ip_address` | VARCHAR(45) | NULL |
| `user_agent` | TEXT | NULL |
| `is_active` | BOOLEAN | DEFAULT TRUE |
| `last_active_at` | TIMESTAMP | NULL |
| `last_notified_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |


### SMS & Communications





#### `sms_providers`


| Column | Type | Constraints |


|---|---|---|


| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |


| `name` | VARCHAR(100) | NOT NULL |


| `provider` | ENUM('twilio','aws_sns','vonage','custom') | NOT NULL |


| `api_key` | VARCHAR(500) | NULL |


| `api_secret` | VARCHAR(500) | NULL |


| `from_number` | VARCHAR(20) | NULL |


| `api_endpoint` | VARCHAR(500) | NULL |


| `config` | JSON | NULL |


| `is_active` | BOOLEAN | DEFAULT TRUE |


| `is_default` | BOOLEAN | DEFAULT FALSE |


| `priority` | INT | DEFAULT 0 |


| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |


| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |





#### `sms_templates`


| Column | Type | Constraints |


|---|---|---|


| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |


| `name` | VARCHAR(100) | NOT NULL, UNIQUE |


| `category` | VARCHAR(50) | NULL |


| `body` | TEXT | NOT NULL |


| `variables` | JSON | NULL |


| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |


| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |





#### `sms_campaigns`


| Column | Type | Constraints |


|---|---|---|


| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |


| `name` | VARCHAR(200) | NOT NULL |


| `message_body` | TEXT | NOT NULL |


| `sms_template_id` | BIGINT UNSIGNED | FK → sms_templates(id), ON DELETE SET NULL |


| `provider_id` | BIGINT UNSIGNED | FK → sms_providers(id), ON DELETE SET NULL |


| `target_type` | ENUM('all','selected','role') | NOT NULL |


| `target_roles` | JSON | NULL |


| `target_user_ids` | JSON | NULL |


| `filter_criteria` | JSON | NULL |


| `scheduled_at` | TIMESTAMP | NULL |


| `sent_at` | TIMESTAMP | NULL |


| `completed_at` | TIMESTAMP | NULL |


| `status` | ENUM('draft','scheduled','sending','sent','partial','failed','cancelled') | DEFAULT 'draft' |


| `total_recipients` | INT | DEFAULT 0 |


| `success_count` | INT | DEFAULT 0 |


| `fail_count` | INT | DEFAULT 0 |


| `created_by` | BIGINT UNSIGNED | FK → users(id), NOT NULL |


| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |


| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |





#### `sms_campaign_recipients`


| Column | Type | Constraints |


|---|---|---|


| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |


| `campaign_id` | BIGINT UNSIGNED | FK → sms_campaigns(id), ON DELETE CASCADE |


| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET NULL |


| `phone` | VARCHAR(50) | NULL |


| `status` | ENUM('pending','sent','delivered','failed','bounced') | NOT NULL, DEFAULT 'pending' |


| `error_message` | TEXT | NULL |


| `provider_message_id` | VARCHAR(255) | NULL |


| `provider_id` | BIGINT UNSIGNED | FK → sms_providers(id), ON DELETE SET NULL |


| `sent_at` | TIMESTAMP | NULL |


| `delivered_at` | TIMESTAMP | NULL |


| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |





#### `sms_automations`


| Column | Type | Constraints |


|---|---|---|


| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |


| `name` | VARCHAR(200) | NOT NULL |


| `trigger_type` | ENUM('event','schedule') | NOT NULL |


| `event_name` | VARCHAR(100) | NULL |


| `event_conditions` | JSON | NULL |


| `cron_expression` | VARCHAR(100) | NULL |


| `timezone` | VARCHAR(50) | NULL, DEFAULT 'UTC' |


| `target_type` | ENUM('all','selected','role','event_context') | NOT NULL |


| `target_roles` | JSON | NULL |


| `filter_criteria` | JSON | NULL |


| `message_body` | TEXT | NOT NULL |


| `sms_template_id` | BIGINT UNSIGNED | FK → sms_templates(id), ON DELETE SET NULL |


| `provider_id` | BIGINT UNSIGNED | FK → sms_providers(id), ON DELETE SET NULL |


| `is_active` | BOOLEAN | DEFAULT TRUE |


| `last_triggered_at` | TIMESTAMP | NULL |


| `total_sent` | INT | DEFAULT 0 |


| `created_by` | BIGINT UNSIGNED | FK → users(id), NOT NULL |


| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |


| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |





#### `sms_logs`


| Column | Type | Constraints |


|---|---|---|


| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |


| `provider_id` | BIGINT UNSIGNED | FK → sms_providers(id), ON DELETE SET NULL |


| `campaign_id` | BIGINT UNSIGNED | FK → sms_campaigns(id), ON DELETE SET NULL |


| `recipient_id` | BIGINT UNSIGNED | FK → sms_campaign_recipients(id), ON DELETE SET NULL |


| `direction` | ENUM('outgoing','callback') | NOT NULL |


| `request_payload` | JSON | NULL |


| `response_payload` | JSON | NULL |


| `http_status` | INT | NULL |


| `provider_message_id` | VARCHAR(255) | NULL |


| `error_message` | TEXT | NULL |


| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |








### SMS & Communications



#### `sms_providers`

| Column | Type | Constraints |

|---|---|---|

| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |

| `name` | VARCHAR(100) | NOT NULL |

| `provider` | ENUM('twilio','aws_sns','vonage','custom') | NOT NULL |

| `api_key` | VARCHAR(500) | NULL |

| `api_secret` | VARCHAR(500) | NULL |

| `from_number` | VARCHAR(20) | NULL |

| `api_endpoint` | VARCHAR(500) | NULL |

| `config` | JSON | NULL |

| `is_active` | BOOLEAN | DEFAULT TRUE |

| `is_default` | BOOLEAN | DEFAULT FALSE |

| `priority` | INT | DEFAULT 0 |

| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |



#### `sms_templates`

| Column | Type | Constraints |

|---|---|---|

| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |

| `name` | VARCHAR(100) | NOT NULL, UNIQUE |

| `category` | VARCHAR(50) | NULL |

| `body` | TEXT | NOT NULL |

| `variables` | JSON | NULL |

| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |



#### `sms_campaigns`

| Column | Type | Constraints |

|---|---|---|

| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |

| `name` | VARCHAR(200) | NOT NULL |

| `message_body` | TEXT | NOT NULL |

| `sms_template_id` | BIGINT UNSIGNED | FK → sms_templates(id), ON DELETE SET NULL |

| `provider_id` | BIGINT UNSIGNED | FK → sms_providers(id), ON DELETE SET NULL |

| `target_type` | ENUM('all','selected','role') | NOT NULL |

| `target_roles` | JSON | NULL |

| `target_user_ids` | JSON | NULL |

| `filter_criteria` | JSON | NULL |

| `scheduled_at` | TIMESTAMP | NULL |

| `sent_at` | TIMESTAMP | NULL |

| `completed_at` | TIMESTAMP | NULL |

| `status` | ENUM('draft','scheduled','sending','sent','partial','failed','cancelled') | DEFAULT 'draft' |

| `total_recipients` | INT | DEFAULT 0 |

| `success_count` | INT | DEFAULT 0 |

| `fail_count` | INT | DEFAULT 0 |

| `created_by` | BIGINT UNSIGNED | FK → users(id), NOT NULL |

| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |



#### `sms_campaign_recipients`

| Column | Type | Constraints |

|---|---|---|

| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |

| `campaign_id` | BIGINT UNSIGNED | FK → sms_campaigns(id), ON DELETE CASCADE |

| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET NULL |

| `phone` | VARCHAR(50) | NULL |

| `status` | ENUM('pending','sent','delivered','failed','bounced') | NOT NULL, DEFAULT 'pending' |

| `error_message` | TEXT | NULL |

| `provider_message_id` | VARCHAR(255) | NULL |

| `provider_id` | BIGINT UNSIGNED | FK → sms_providers(id), ON DELETE SET NULL |

| `sent_at` | TIMESTAMP | NULL |

| `delivered_at` | TIMESTAMP | NULL |

| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



#### `sms_automations`

| Column | Type | Constraints |

|---|---|---|

| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |

| `name` | VARCHAR(200) | NOT NULL |

| `trigger_type` | ENUM('event','schedule') | NOT NULL |

| `event_name` | VARCHAR(100) | NULL |

| `event_conditions` | JSON | NULL |

| `cron_expression` | VARCHAR(100) | NULL |

| `timezone` | VARCHAR(50) | NULL, DEFAULT 'UTC' |

| `target_type` | ENUM('all','selected','role','event_context') | NOT NULL |

| `target_roles` | JSON | NULL |

| `filter_criteria` | JSON | NULL |

| `message_body` | TEXT | NOT NULL |

| `sms_template_id` | BIGINT UNSIGNED | FK → sms_templates(id), ON DELETE SET NULL |

| `provider_id` | BIGINT UNSIGNED | FK → sms_providers(id), ON DELETE SET NULL |

| `is_active` | BOOLEAN | DEFAULT TRUE |

| `last_triggered_at` | TIMESTAMP | NULL |

| `total_sent` | INT | DEFAULT 0 |

| `created_by` | BIGINT UNSIGNED | FK → users(id), NOT NULL |

| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |



#### `sms_logs`

| Column | Type | Constraints |

|---|---|---|

| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |

| `provider_id` | BIGINT UNSIGNED | FK → sms_providers(id), ON DELETE SET NULL |

| `campaign_id` | BIGINT UNSIGNED | FK → sms_campaigns(id), ON DELETE SET NULL |

| `recipient_id` | BIGINT UNSIGNED | FK → sms_campaign_recipients(id), ON DELETE SET NULL |

| `direction` | ENUM('outgoing','callback') | NOT NULL |

| `request_payload` | JSON | NULL |

| `response_payload` | JSON | NULL |

| `http_status` | INT | NULL |

| `provider_message_id` | VARCHAR(255) | NULL |

| `error_message` | TEXT | NULL |

| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |






### Automation & Workflow Engine

#### `workflow_definitions`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `name` | VARCHAR(255) | NOT NULL |
| `slug` | VARCHAR(255) | NOT NULL, UNIQUE |
| `description` | TEXT | NULL |
| `category` | VARCHAR(100) | NULL |
| `status` | ENUM('draft','active','paused','archived') | NOT NULL, DEFAULT 'draft' |
| `version` | INT UNSIGNED | NOT NULL, DEFAULT 1 |
| `config` | JSON | NULL |
| `is_system` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `created_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET NULL, NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `workflow_nodes`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `workflow_id` | BIGINT UNSIGNED | FK → workflow_definitions(id), ON DELETE CASCADE, NOT NULL |
| `type` | ENUM('trigger','action','condition','approval','wait','gateway','end') | NOT NULL |
| `name` | VARCHAR(255) | NOT NULL |
| `description` | TEXT | NULL |
| `config` | JSON | NULL |
| `position_x` | INT | NOT NULL, DEFAULT 0 |
| `position_y` | INT | NOT NULL, DEFAULT 0 |
| `timeout_seconds` | INT UNSIGNED | NULL |
| `retry_count` | INT UNSIGNED | NOT NULL, DEFAULT 0 |
| `retry_delay` | INT UNSIGNED | NOT NULL, DEFAULT 0 |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `workflow_transitions`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `workflow_id` | BIGINT UNSIGNED | FK → workflow_definitions(id), ON DELETE CASCADE, NOT NULL |
| `from_node_id` | BIGINT UNSIGNED | FK → workflow_nodes(id), ON DELETE CASCADE, NOT NULL |
| `to_node_id` | BIGINT UNSIGNED | FK → workflow_nodes(id), ON DELETE CASCADE, NOT NULL |
| `condition_expression` | JSON | NULL |
| `label` | VARCHAR(255) | NULL |
| `priority` | INT | NOT NULL, DEFAULT 0 |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

#### `workflow_runs`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `workflow_id` | BIGINT UNSIGNED | FK → workflow_definitions(id), ON DELETE CASCADE, NOT NULL |
| `triggered_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET NULL, NULL |
| `trigger_type` | VARCHAR(100) | NULL |
| `trigger_payload` | JSON | NULL |
| `status` | ENUM('running','completed','failed','cancelled','paused') | NOT NULL, DEFAULT 'running' |
| `current_node_id` | BIGINT UNSIGNED | FK → workflow_nodes(id), ON DELETE SET NULL, NULL |
| `started_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `completed_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `workflow_run_logs`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `run_id` | BIGINT UNSIGNED | FK → workflow_runs(id), ON DELETE CASCADE, NOT NULL |
| `node_id` | BIGINT UNSIGNED | FK → workflow_nodes(id), ON DELETE SET NULL, NULL |
| `action_type` | VARCHAR(100) | NULL |
| `level` | ENUM('info','warn','error','debug') | NOT NULL, DEFAULT 'info' |
| `message` | TEXT | NOT NULL |
| `payload` | JSON | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

#### `workflow_run_node_states`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `run_id` | BIGINT UNSIGNED | FK → workflow_runs(id), ON DELETE CASCADE, NOT NULL |
| `node_id` | BIGINT UNSIGNED | FK → workflow_nodes(id), ON DELETE CASCADE, NOT NULL |
| `status` | ENUM('pending','running','completed','failed','skipped','retrying') | NOT NULL, DEFAULT 'pending' |
| `input` | JSON | NULL |
| `output` | JSON | NULL |
| `attempts` | INT UNSIGNED | NOT NULL, DEFAULT 0 |
| `started_at` | TIMESTAMP | NULL |
| `completed_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `workflow_run_variables`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `run_id` | BIGINT UNSIGNED | FK → workflow_runs(id), ON DELETE CASCADE, NOT NULL |
| `name` | VARCHAR(255) | NOT NULL |
| `value` | JSON | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `triggers`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `name` | VARCHAR(255) | NOT NULL |
| `slug` | VARCHAR(255) | NOT NULL, UNIQUE |
| `event_type` | VARCHAR(100) | NOT NULL |
| `description` | TEXT | NULL |
| `config` | JSON | NULL |
| `status` | ENUM('active','inactive') | NOT NULL, DEFAULT 'active' |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `trigger_workflow_mappings`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `trigger_id` | BIGINT UNSIGNED | FK → triggers(id), ON DELETE CASCADE, NOT NULL |
| `workflow_id` | BIGINT UNSIGNED | FK → workflow_definitions(id), ON DELETE CASCADE, NOT NULL |
| `priority` | INT | NOT NULL, DEFAULT 0 |
| `conditions` | JSON | NULL |
| `status` | ENUM('active','inactive') | NOT NULL, DEFAULT 'active' |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

#### `event_log`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `event_type` | VARCHAR(100) | NOT NULL |
| `source_type` | VARCHAR(100) | NULL |
| `source_id` | BIGINT UNSIGNED | NULL |
| `payload` | JSON | NULL |
| `occurred_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP |
| `processed_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

#### `condition_groups`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `name` | VARCHAR(255) | NOT NULL |
| `operator` | ENUM('AND','OR') | NOT NULL, DEFAULT 'AND' |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `condition_rules`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `group_id` | BIGINT UNSIGNED | FK → condition_groups(id), ON DELETE CASCADE, NOT NULL |
| `field` | VARCHAR(255) | NOT NULL |
| `operator` | ENUM('equals','not_equals','greater_than','less_than','greater_or_equal','less_or_equal','contains','not_contains','in','not_in','starts_with','ends_with','is_empty','is_not_empty','matches') | NOT NULL |
| `value` | JSON | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

#### `condition_group_mappings`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `group_id` | BIGINT UNSIGNED | FK → condition_groups(id), ON DELETE CASCADE, NOT NULL |
| `entity_type` | VARCHAR(100) | NOT NULL |
| `entity_id` | BIGINT UNSIGNED | NOT NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

#### `approval_requests`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `workflow_run_id` | BIGINT UNSIGNED | FK → workflow_runs(id), ON DELETE CASCADE, NOT NULL |
| `node_id` | BIGINT UNSIGNED | FK → workflow_nodes(id), ON DELETE CASCADE, NOT NULL |
| `status` | ENUM('pending','approved','rejected','cancelled') | NOT NULL, DEFAULT 'pending' |
| `requested_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET NULL, NULL |
| `requested_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `responded_at` | TIMESTAMP | NULL |
| `notes` | TEXT | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `approval_stages`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `approval_request_id` | BIGINT UNSIGNED | FK → approval_requests(id), ON DELETE CASCADE, NOT NULL |
| `stage_order` | INT UNSIGNED | NOT NULL |
| `status` | ENUM('pending','approved','rejected','skipped') | NOT NULL, DEFAULT 'pending' |
| `strategy` | ENUM('any','all') | NOT NULL, DEFAULT 'all' |
| `min_approvers` | INT UNSIGNED | NOT NULL, DEFAULT 1 |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `approval_assignees`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `stage_id` | BIGINT UNSIGNED | FK → approval_stages(id), ON DELETE CASCADE, NOT NULL |
| `user_id` | BIGINT UNSIGNED | FK → users(id), ON DELETE CASCADE, NOT NULL |
| `status` | ENUM('pending','approved','rejected') | NOT NULL, DEFAULT 'pending' |
| `response` | TEXT | NULL |
| `responded_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

#### `email_automations`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `name` | VARCHAR(255) | NOT NULL |
| `trigger_event` | VARCHAR(100) | NOT NULL |
| `email_template_id` | BIGINT UNSIGNED | FK → email_templates(id), ON DELETE SET NULL, NULL |
| `conditions` | JSON | NULL |
| `audience_filter` | JSON | NULL |
| `sender_name` | VARCHAR(255) | NULL |
| `sender_email` | VARCHAR(255) | NULL |
| `reply_to` | VARCHAR(255) | NULL |
| `status` | ENUM('draft','active','paused','archived') | NOT NULL, DEFAULT 'draft' |
| `created_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET NULL, NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `scheduled_tasks`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `name` | VARCHAR(255) | NOT NULL |
| `description` | TEXT | NULL |
| `cron_expression` | VARCHAR(100) | NOT NULL |
| `task_type` | ENUM('run_workflow','call_webhook','send_report','run_sql','custom') | NOT NULL |
| `config` | JSON | NULL |
| `status` | ENUM('active','paused','completed','failed') | NOT NULL, DEFAULT 'active' |
| `last_run_at` | TIMESTAMP | NULL |
| `next_run_at` | TIMESTAMP | NULL |
| `is_system` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `created_by` | BIGINT UNSIGNED | FK → users(id), ON DELETE SET NULL, NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP |

#### `webhook_delivery_logs`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `webhook_id` | BIGINT UNSIGNED | FK → webhooks(id), ON DELETE CASCADE, NOT NULL |
| `event_type` | VARCHAR(100) | NOT NULL |
| `payload` | JSON | NULL |
| `request_headers` | JSON | NULL |
| `response_status` | INT UNSIGNED | NULL |
| `response_body` | TEXT | NULL |
| `attempt` | INT UNSIGNED | NOT NULL, DEFAULT 1 |
| `success` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `error_message` | TEXT | NULL |
| `delivered_at` | TIMESTAMP | NULL |
| `next_retry_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



### Reporting & Dashboards

#### `report_categories`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `name` | VARCHAR(255) | NOT NULL |
| `slug` | VARCHAR(255) | NOT NULL, UNIQUE |
| `description` | TEXT | NULL |
| `parent_id` | BIGINT UNSIGNED | NULL |
| `sort_order` | INT | NOT NULL, DEFAULT 0 |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `report_definitions`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `name` | VARCHAR(255) | NOT NULL |
| `slug` | VARCHAR(255) | NOT NULL, UNIQUE |
| `description` | TEXT | NULL |
| `category_id` | BIGINT UNSIGNED | NULL |
| `report_type` | ENUM('tabular','chart','pivot','summary') | NOT NULL, DEFAULT 'tabular' |
| `config` | JSON | NOT NULL |
| `is_system` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `created_by` | BIGINT UNSIGNED | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `report_schedules`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `report_id` | BIGINT UNSIGNED | NOT NULL |
| `name` | VARCHAR(255) | NOT NULL |
| `cron_expression` | VARCHAR(100) | NOT NULL |
| `recipients` | JSON | NOT NULL |
| `format` | ENUM('pdf','csv','excel','json') | NOT NULL, DEFAULT 'pdf' |
| `config` | JSON | NULL |
| `last_run_at` | TIMESTAMP | NULL |
| `next_run_at` | TIMESTAMP | NULL |
| `status` | ENUM('active','paused','completed','failed') | NOT NULL, DEFAULT 'active' |
| `created_by` | BIGINT UNSIGNED | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `dashboard_widgets`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `user_id` | BIGINT UNSIGNED | NULL |
| `dashboard_name` | VARCHAR(100) | NOT NULL, DEFAULT 'default' |
| `widget_type` | VARCHAR(100) | NOT NULL |
| `title` | VARCHAR(255) | NULL |
| `config` | JSON | NULL |
| `position_x` | INT | NOT NULL, DEFAULT 0 |
| `position_y` | INT | NOT NULL, DEFAULT 0 |
| `width` | INT | NOT NULL, DEFAULT 4 |
| `height` | INT | NOT NULL, DEFAULT 3 |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |



### Privacy & GDPR

#### `consent_logs`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `user_id` | BIGINT UNSIGNED | NOT NULL |
| `consent_type` | ENUM('marketing','analytics','functional','third_party','cookies') | NOT NULL |
| `purpose` | VARCHAR(255) | NULL |
| `granted` | BOOLEAN | NOT NULL |
| `ip_address` | VARCHAR(45) | NULL |
| `user_agent` | TEXT | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |


#### `data_export_requests`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `user_id` | BIGINT UNSIGNED | NOT NULL |
| `status` | ENUM('pending','processing','completed','failed') | NOT NULL, DEFAULT 'pending' |
| `format` | ENUM('json','csv','xml') | NOT NULL, DEFAULT 'json' |
| `requested_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `completed_at` | TIMESTAMP | NULL |
| `file_path` | VARCHAR(500) | NULL |
| `expires_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |


#### `data_deletion_requests`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `user_id` | BIGINT UNSIGNED | NOT NULL |
| `status` | ENUM('pending','approved','rejected','completed') | NOT NULL, DEFAULT 'pending' |
| `reason` | TEXT | NULL |
| `requested_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `processed_at` | TIMESTAMP | NULL |
| `processed_by` | BIGINT UNSIGNED | NULL |
| `notes` | TEXT | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `cookie_consent_settings`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `name` | VARCHAR(255) | NOT NULL |
| `slug` | VARCHAR(255) | NOT NULL, UNIQUE |
| `description` | TEXT | NULL |
| `required` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `default_granted` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |



### Tax Engine

#### `tax_jurisdictions`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `name` | VARCHAR(255) | NOT NULL |
| `country` | VARCHAR(2) | NOT NULL |
| `state` | VARCHAR(100) | NULL |
| `city` | VARCHAR(100) | NULL |
| `postal_code` | VARCHAR(20) | NULL |
| `rate` | DECIMAL(5,4) | NOT NULL |
| `tax_type` | ENUM('sales','vat','gst','hst') | NOT NULL, DEFAULT 'sales' |
| `is_compound` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `priority` | INT | NOT NULL, DEFAULT 0 |
| `status` | ENUM('active','inactive') | NOT NULL, DEFAULT 'active' |
| `effective_from` | DATE | NULL |
| `effective_to` | DATE | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `tax_exemptions`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `user_id` | BIGINT UNSIGNED | NOT NULL |
| `product_id` | BIGINT UNSIGNED | NULL |
| `exemption_type` | VARCHAR(100) | NOT NULL |
| `certificate_number` | VARCHAR(255) | NULL |
| `issuing_authority` | VARCHAR(255) | NULL |
| `valid_from` | DATE | NULL |
| `valid_to` | DATE | NULL |
| `status` | ENUM('pending','active','expired','revoked') | NOT NULL, DEFAULT 'pending' |
| `verified_by` | BIGINT UNSIGNED | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `tax_rules`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `name` | VARCHAR(255) | NOT NULL |
| `priority` | INT | NOT NULL, DEFAULT 0 |
| `conditions` | JSON | NULL |
| `action_type` | ENUM('rate_override','exempt','compound','reduce') | NOT NULL |
| `action_value` | JSON | NOT NULL |
| `status` | ENUM('active','inactive') | NOT NULL, DEFAULT 'active' |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `tax_report_data`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `tax_jurisdiction_id` | BIGINT UNSIGNED | NOT NULL |
| `period_start` | DATE | NOT NULL |
| `period_end` | DATE | NOT NULL |
| `taxable_amount` | DECIMAL(15,2) | NOT NULL, DEFAULT 0.00 |
| `tax_collected` | DECIMAL(15,2) | NOT NULL, DEFAULT 0.00 |
| `returns_filed` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `filed_at` | TIMESTAMP | NULL |
| `notes` | TEXT | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



### Dynamic Pricing

#### `price_rules`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `name` | VARCHAR(255) | NOT NULL |
| `slug` | VARCHAR(255) | NOT NULL, UNIQUE |
| `description` | TEXT | NULL |
| `priority` | INT | NOT NULL, DEFAULT 0 |
| `conditions` | JSON | NULL |
| `adjustments` | JSON | NOT NULL |
| `applies_to` | ENUM('all','products','categories','users','user_roles') | NOT NULL, DEFAULT 'all' |
| `stackable` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `status` | ENUM('active','inactive','expired') | NOT NULL, DEFAULT 'active' |
| `starts_at` | TIMESTAMP | NULL |
| `expires_at` | TIMESTAMP | NULL |
| `created_by` | BIGINT UNSIGNED | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `price_tiers`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `reviewable_type` | VARCHAR(50) | NOT NULL |
| `min_quantity` | INT | NOT NULL |
| `max_quantity` | INT | NULL |
| `unit_price` | DECIMAL(15,2) | NOT NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `price_overrides`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `user_id` | BIGINT UNSIGNED | NOT NULL |
| `product_id` | BIGINT UNSIGNED | NULL |
| `plan_id` | BIGINT UNSIGNED | NULL |
| `override_price` | DECIMAL(15,2) | NOT NULL |
| `override_type` | ENUM('fixed','percentage') | NOT NULL, DEFAULT 'fixed' |
| `starts_at` | TIMESTAMP | NULL |
| `expires_at` | TIMESTAMP | NULL |
| `reason` | TEXT | NULL |
| `created_by` | BIGINT UNSIGNED | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `price_rule_audit`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `price_rule_id` | BIGINT UNSIGNED | NULL |
| `order_id` | BIGINT UNSIGNED | NULL |
| `user_id` | BIGINT UNSIGNED | NOT NULL |
| `product_id` | BIGINT UNSIGNED | NOT NULL |
| `original_price` | DECIMAL(15,2) | NOT NULL |
| `adjusted_price` | DECIMAL(15,2) | NOT NULL |
| `rule_name` | VARCHAR(255) | NULL |
| `context` | JSON | NULL |
| `applied_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



### Internationalization (i18n)

#### `language_packs`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `code` | VARCHAR(10) | NOT NULL, UNIQUE |
| `name` | VARCHAR(100) | NOT NULL |
| `native_name` | VARCHAR(100) | NULL |
| `is_rtl` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `is_default` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `is_active` | BOOLEAN | NOT NULL, DEFAULT TRUE |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `translations`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `language_pack_id` | BIGINT UNSIGNED | NOT NULL |
| `namespace` | VARCHAR(100) | NOT NULL, DEFAULT 'frontend' |
| `group` | VARCHAR(100) | NOT NULL, DEFAULT 'general' |
| `key` | VARCHAR(255) | NOT NULL |
| `value` | TEXT | NOT NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `translation_files`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `language_pack_id` | BIGINT UNSIGNED | NOT NULL |
| `namespace` | VARCHAR(100) | NOT NULL, DEFAULT 'frontend' |
| `file_path` | VARCHAR(500) | NOT NULL |
| `file_format` | ENUM('json','po','xlf','csv') | NOT NULL, DEFAULT 'json' |
| `version` | INT | NOT NULL, DEFAULT 1 |
| `uploaded_by` | BIGINT UNSIGNED | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |



### Content Moderation

#### `moderation_queue`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `content_type` | VARCHAR(50) | NOT NULL |
| `content_id` | BIGINT UNSIGNED | NOT NULL |
| `reported_by` | BIGINT UNSIGNED | NULL |
| `status` | ENUM('pending','reviewed','approved','rejected','escalated') | NOT NULL, DEFAULT 'pending' |
| `priority` | ENUM('low','normal','high','critical') | NOT NULL, DEFAULT 'normal' |
| `assigned_to` | BIGINT UNSIGNED | NULL |
| `notes` | TEXT | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `moderation_reports`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `queue_item_id` | BIGINT UNSIGNED | NOT NULL |
| `reporter_id` | BIGINT UNSIGNED | NULL |
| `reason_category` | VARCHAR(100) | NOT NULL |
| `description` | TEXT | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |


#### `moderation_actions`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `queue_item_id` | BIGINT UNSIGNED | NOT NULL |
| `moderator_id` | BIGINT UNSIGNED | NOT NULL |
| `action` | ENUM('approved','rejected','warned','hidden','deleted','escalated') | NOT NULL |
| `reason` | TEXT | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |


#### `moderation_blocklist`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `block_type` | ENUM('ip','email','domain','keyword','pattern','phone') | NOT NULL |
| `value` | VARCHAR(500) | NOT NULL |
| `reason` | TEXT | NULL |
| `created_by` | BIGINT UNSIGNED | NULL |
| `expires_at` | TIMESTAMP | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |



### System & API Configuration

#### `rate_limit_rules`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `name` | VARCHAR(255) | NOT NULL |
| `route_pattern` | VARCHAR(255) | NOT NULL |
| `http_method` | VARCHAR(10) | NULL |
| `max_requests` | INT | NOT NULL |
| `window_seconds` | INT | NOT NULL |
| `response_code` | INT | NOT NULL, DEFAULT 429 |
| `response_message` | VARCHAR(500) | NULL |
| `is_active` | BOOLEAN | NOT NULL, DEFAULT TRUE |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |


#### `rate_limit_logs`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `rule_id` | BIGINT UNSIGNED | NULL |
| `user_id` | BIGINT UNSIGNED | NULL |
| `ip_address` | VARCHAR(45) | NOT NULL |
| `route` | VARCHAR(255) | NOT NULL |
| `http_method` | VARCHAR(10) | NULL |
| `identifier` | VARCHAR(255) | NULL |
| `hit_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |


#### `health_checks`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `check_type` | ENUM('database','cache','queue','storage','api','mail','search') | NOT NULL |
| `status` | ENUM('pass','warn','fail') | NOT NULL |
| `response_time_ms` | INT | NULL |
| `message` | TEXT | NULL |
| `checked_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |


#### `maintenance_windows`

| Column | Type | Constraints |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| `title` | VARCHAR(255) | NOT NULL |
| `description` | TEXT | NULL |
| `status` | ENUM('scheduled','in_progress','completed','cancelled') | NOT NULL, DEFAULT 'scheduled' |
| `starts_at` | TIMESTAMP | NOT NULL |
| `ends_at` | TIMESTAMP | NOT NULL |
| `created_by` | BIGINT UNSIGNED | NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP, ON UPDATE CURRENT_TIMESTAMP |







## Key Design Decisions







1. **PKs are always `BIGINT UNSIGNED AUTO_INCREMENT`** for consistency and storage efficiency.



2. **FK columns match PK types exactly** (`BIGINT UNSIGNED`), enabling proper JOIN indexing.



3. **Every FK has an explicit ON DELETE/ON UPDATE** — CASCADE for ownership, RESTRICT/SET NULL for lookups.



4. **Indexing strategy** — PKs auto-indexed, FKs indexed for JOIN performance, plus composite indexes on high-query column pairs.



5. **`cms_settings` merged** from 3 separate tables (settings, themes, layouts) into one with type/group categorization.



6. **`version_histories` merged** from per-entity tables into a single polymorphic table with JSON snapshot.



7. **`live_chats` → `chat_sessions` + `chat_messages`** — normalized flat chat into session/message hierarchy.



8. **`bot_conversation_logs` removed** — redundant; `bot_conversations` already stores both message and response.



9. **`product_hardware_requirements` normalized** — moved from JSON blob to a separate table for queryability.



10. **`post_tags` pivot added** — enables many-to-many posts ↔ tags without denormalization.



11. **`bot_configs` table added** — generic multi-platform bot config (Telegram, Discord, WhatsApp, Slack) with LLM provider linking, rate limiting, and user whitelist support.



12. **34 stored procedures & events** — `spGenerateLicenseKey`, `spProcessOrderPayment`, `spRenewSubscription`, `spProcessSellerPayout`, `spExpireSubscriptions`, `spRefreshSellerStats` + 3 scheduled events for daily subscription expiry, hourly log cleanup, and daily stats refresh.



13. **Full SEO suite for all content types** — `cms_pages` now has meta_title, meta_description, canonical_url, OG/Twitter Card, noindex, sitemap priority/changefreq; `posts` upgraded with OG/Twitter Card, noindex, sitemap controls.



14. **SEO infrastructure tables**: `redirects` (301/302 management), `slug_history` (track content moves), `structured_data` (JSON-LD schema per entity), `seo_analysis` (audit scoring).



15. **Analytics & media features**: `analytics_events` (UTM-tagged pageview/event tracking), `media_library` (unified file repository), `content_revisions` (versioning), `cms_page_tags` (page tagging), `form_submissions` (lead capture).



