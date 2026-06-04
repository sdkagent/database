<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
            $table->string('order_number', 50)->notNull();
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'sent', 'partial', 'received', 'cancelled'])->default('draft');
            $table->date('order_date')->notNull();
            $table->date('expected_date')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0.00);
            $table->decimal('tax', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2)->default(0.00);
            $table->foreignId('currency_id')->constrained('currencies')->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('order_number', 'unique_purchase_orders_number');
            $table->index('supplier_id', 'idx_purchase_orders_supplier');
            $table->index('status', 'idx_purchase_orders_status');
            $table->index('order_date', 'idx_purchase_orders_date');
            $table->index('currency_id', 'idx_purchase_orders_currency');
            $table->index('requested_by', 'idx_purchase_orders_requestor');
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('warehouse_location_id')->nullable()->constrained('warehouse_locations')->nullOnDelete();
            $table->text('description')->nullable();
            $table->decimal('quantity', 15, 4)->notNull();
            $table->decimal('received_qty', 15, 4)->default(0);
            $table->decimal('unit_price', 15, 2)->notNull();
            $table->decimal('tax_rate', 5, 2)->default(0.00);
            $table->decimal('subtotal', 15, 2)->notNull();
            $table->integer('line_order')->default(0);
            $table->timestamps();

            $table->index('purchase_order_id', 'idx_po_items_order');
            $table->index('product_id', 'idx_po_items_product');
            $table->index('warehouse_location_id', 'idx_po_items_location');
        });

        Schema::create('purchase_receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->string('receipt_number', 50)->notNull();
            $table->date('received_date')->notNull();
            $table->enum('status', ['draft', 'completed', 'partial', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('receipt_number', 'unique_purchase_receipts_number');
            $table->index('purchase_order_id', 'idx_purchase_receipts_order');
            $table->index('received_by', 'idx_purchase_receipts_receiver');
        });

        Schema::create('purchase_receipt_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('purchase_receipt_id')->constrained('purchase_receipts')->cascadeOnDelete();
            $table->foreignId('po_item_id')->constrained('purchase_order_items')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('warehouse_location_id')->constrained('warehouse_locations')->restrictOnDelete();
            $table->decimal('quantity', 15, 4)->notNull();
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->string('batch_number', 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('purchase_receipt_id', 'idx_pr_items_receipt');
            $table->index('po_item_id', 'idx_pr_items_po_item');
            $table->index('product_id', 'idx_pr_items_product');
            $table->index('warehouse_location_id', 'idx_pr_items_location');
        });

        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
            $table->string('invoice_number', 100)->notNull();
            $table->date('invoice_date')->notNull();
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0.00);
            $table->decimal('tax', 15, 2)->default(0.00);
            $table->decimal('total', 15, 2)->default(0.00);
            $table->foreignId('currency_id')->constrained('currencies')->restrictOnDelete();
            $table->enum('status', ['pending', 'approved', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('purchase_order_id', 'idx_purchase_invoices_order');
            $table->index('supplier_id', 'idx_purchase_invoices_supplier');
            $table->index('currency_id', 'idx_purchase_invoices_currency');
            $table->index('status', 'idx_purchase_invoices_status');
            $table->index('due_date', 'idx_purchase_invoices_due');
        });

        Schema::create('purchase_invoice_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('purchase_invoice_id')->constrained('purchase_invoices')->cascadeOnDelete();
            $table->foreignId('po_item_id')->constrained('purchase_order_items')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->decimal('quantity', 15, 4)->notNull();
            $table->decimal('unit_price', 15, 2)->notNull();
            $table->decimal('subtotal', 15, 2)->notNull();
            $table->timestamp('created_at')->useCurrent();

            $table->index('purchase_invoice_id', 'idx_pi_items_invoice');
            $table->index('po_item_id', 'idx_pi_items_po_item');
            $table->index('product_id', 'idx_pi_items_product');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_invoice_items');
        Schema::dropIfExists('purchase_invoices');
        Schema::dropIfExists('purchase_receipt_items');
        Schema::dropIfExists('purchase_receipts');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
    }
};
