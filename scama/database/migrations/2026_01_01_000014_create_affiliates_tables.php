<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('code', 50);
            $table->decimal('commission_rate', 5, 2)->default(10.00);
            $table->decimal('total_earned', 12, 2)->default(0.00);
            $table->decimal('total_paid', 12, 2)->default(0.00);
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->unique('code', 'unique_affiliates_code');
            $table->index('user_id', 'idx_affiliates_user');
            $table->index('status', 'idx_affiliates_status');
        });

        Schema::create('referrals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('affiliate_id')->constrained('affiliates')->cascadeOnDelete();
            $table->foreignId('referred_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->decimal('commission', 10, 2)->default(0.00);
            $table->string('status', 20)->default('pending');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['affiliate_id', 'referred_id'], 'unique_referrals_referred');
            $table->index('order_id', 'idx_referrals_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('affiliates');
    }
};
