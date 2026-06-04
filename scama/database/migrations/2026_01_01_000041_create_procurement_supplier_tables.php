<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name', 255)->notNull();
            $table->string('supplier_code', 20)->notNull();
            $table->string('contact_name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('website', 500)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 2)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('tax_id', 100)->nullable();
            $table->string('payment_terms', 100)->nullable();
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();

            $table->unique('supplier_code', 'unique_suppliers_code');
            $table->index('status', 'idx_suppliers_status');
            $table->index('currency_id', 'idx_suppliers_currency');
        });

        Schema::create('supplier_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->string('first_name', 100)->notNull();
            $table->string('last_name', 100)->notNull();
            $table->string('job_title', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index('supplier_id', 'idx_supplier_contacts_supplier');
        });

        Schema::create('supplier_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('supplier_sku', 100)->nullable();
            $table->integer('lead_time_days')->nullable();
            $table->integer('moq')->nullable();
            $table->boolean('is_preferred')->default(false);
            $table->timestamps();

            $table->unique(['supplier_id', 'product_id'], 'unique_supplier_products_supplier_product');
            $table->index('product_id', 'idx_supplier_products_product');
        });

        Schema::create('supplier_pricelists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('supplier_product_id')->constrained('supplier_products')->cascadeOnDelete();
            $table->decimal('unit_price', 15, 2)->notNull();
            $table->foreignId('currency_id')->constrained('currencies')->restrictOnDelete();
            $table->integer('min_quantity')->default(1);
            $table->date('effective_from')->notNull();
            $table->date('effective_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('supplier_product_id', 'idx_supplier_pricelists_product');
            $table->index('currency_id', 'idx_supplier_pricelists_currency');
            $table->index(['effective_from', 'effective_until'], 'idx_supplier_pricelists_dates');
        });

        Schema::create('rfqs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rfq_number', 50)->notNull();
            $table->string('title', 255)->notNull();
            $table->text('description')->nullable();
            $table->date('issue_date')->notNull();
            $table->date('closing_date')->nullable();
            $table->enum('status', ['draft', 'sent', 'received', 'evaluating', 'awarded', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('rfq_number', 'unique_rfqs_number');
            $table->index('status', 'idx_rfqs_status');
            $table->index('created_by', 'idx_rfqs_creator');
        });

        Schema::create('rfq_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('rfq_id')->constrained('rfqs')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('quantity', 15, 4)->notNull();
            $table->text('notes')->nullable();
            $table->integer('line_order')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('rfq_id', 'idx_rfq_items_rfq');
            $table->index('product_id', 'idx_rfq_items_product');
        });

        Schema::create('supplier_quotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('rfq_id')->constrained('rfqs')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
            $table->string('quotation_number', 50)->nullable();
            $table->date('quotation_date')->notNull();
            $table->date('valid_until')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0.00);
            $table->decimal('tax', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2)->default(0.00);
            $table->foreignId('currency_id')->constrained('currencies')->restrictOnDelete();
            $table->enum('status', ['received', 'evaluated', 'accepted', 'rejected'])->default('received');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('rfq_id', 'idx_supplier_quotations_rfq');
            $table->index('supplier_id', 'idx_supplier_quotations_supplier');
            $table->index('currency_id', 'idx_supplier_quotations_currency');
        });

        Schema::create('quotation_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('quotation_id')->constrained('supplier_quotations')->cascadeOnDelete();
            $table->foreignId('rfq_item_id')->constrained('rfq_items')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->decimal('quantity', 15, 4)->notNull();
            $table->decimal('unit_price', 15, 2)->notNull();
            $table->decimal('subtotal', 15, 2)->notNull();
            $table->integer('line_order')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('quotation_id', 'idx_quotation_items_quotation');
            $table->index('rfq_item_id', 'idx_quotation_items_rfq_item');
            $table->index('product_id', 'idx_quotation_items_product');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('supplier_quotations');
        Schema::dropIfExists('rfq_items');
        Schema::dropIfExists('rfqs');
        Schema::dropIfExists('supplier_pricelists');
        Schema::dropIfExists('supplier_products');
        Schema::dropIfExists('supplier_contacts');
        Schema::dropIfExists('suppliers');
    }
};
