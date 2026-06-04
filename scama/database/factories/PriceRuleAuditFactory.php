<?php

namespace Database\Factories;

use App\Models\Commerce\Order;
use App\Models\Pricing\PriceRule;
use App\Models\Pricing\PriceRuleAudit;
use App\Models\Product\Product;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceRuleAuditFactory extends Factory
{
    protected $model = PriceRuleAudit::class;

    public function definition(): array
    {
        return [
            'price_rule_id'  => PriceRule::factory(),
            'order_id'       => Order::factory(),
            'user_id'        => User::factory(),
            'product_id'     => Product::factory(),
            'original_price' => fake()->randomFloat(2, 50, 500),
            'adjusted_price' => fake()->randomFloat(2, 30, 450),
            'rule_name'      => fake()->randomElement(['Summer Sale', 'Flash Deal', 'Bulk Discount']),
            'context'        => ['applied' => true, 'reason' => 'bulk_discount'],
        ];
    }
}
