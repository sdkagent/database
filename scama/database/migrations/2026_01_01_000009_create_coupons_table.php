<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 50);
            $table->string('type', 20);
            $table->decimal('value', 10, 2);
            $table->decimal('min_order_amount', 10, 2)->default(0.00);
            $table->integer('max_uses')->nullable();
            $table->integer('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code', 'unique_coupons_code');
            $table->index('is_active', 'idx_coupons_active');
            $table->index(['code', 'is_active'], 'idx_coupons_code_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
