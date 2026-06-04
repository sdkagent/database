<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_jurisdictions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->notNull();
            $table->string('country', 2)->notNull();
            $table->string('state', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->decimal('rate', 5, 4)->notNull();
            $table->enum('tax_type', ['sales', 'vat', 'gst', 'hst'])->default('sales');
            $table->boolean('is_compound')->default(false);
            $table->integer('priority')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->timestamps();

            $table->index('country', 'idx_tax_jurisdictions_country');
            $table->index('status', 'idx_tax_jurisdictions_status');
            $table->index(['country', 'state', 'city'], 'idx_tax_jurisdictions_location');
        });

        Schema::create('tax_exemptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('exemption_type', 100)->notNull();
            $table->string('certificate_number', 255)->nullable();
            $table->string('issuing_authority', 255)->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->enum('status', ['pending', 'active', 'expired', 'revoked'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('user_id', 'idx_tax_exemptions_user');
            $table->index('product_id', 'idx_tax_exemptions_product');
            $table->index('status', 'idx_tax_exemptions_status');
        });

        Schema::create('tax_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->notNull();
            $table->integer('priority')->default(0);
            $table->json('conditions')->nullable();
            $table->enum('action_type', ['rate_override', 'exempt', 'compound', 'reduce'])->notNull();
            $table->json('action_value')->notNull();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('status', 'idx_tax_rules_status');
            $table->index('priority', 'idx_tax_rules_priority');
        });

        Schema::create('tax_report_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('tax_jurisdiction_id')->constrained('tax_jurisdictions')->cascadeOnDelete();
            $table->date('period_start')->notNull();
            $table->date('period_end')->notNull();
            $table->decimal('taxable_amount', 15, 2)->default(0.00);
            $table->decimal('tax_collected', 15, 2)->default(0.00);
            $table->boolean('returns_filed')->default(false);
            $table->timestamp('filed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('tax_jurisdiction_id', 'idx_tax_report_data_jurisdiction');
            $table->index(['period_start', 'period_end'], 'idx_tax_report_data_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_report_data');
        Schema::dropIfExists('tax_rules');
        Schema::dropIfExists('tax_exemptions');
        Schema::dropIfExists('tax_jurisdictions');
    }
};
