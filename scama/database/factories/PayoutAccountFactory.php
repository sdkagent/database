<?php

namespace Database\Factories;

use App\Models\Seller\PayoutAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayoutAccountFactory extends Factory
{
    protected $model = PayoutAccount::class;

    public function definition(): array
    {
        return [
            'seller_id'       => \App\Models\SellerProfile::factory(),
            'method'          => fake()->randomElement(['bank', 'paypal', 'stripe', 'crypto', 'bkash']),
            'account_label'   => fake()->optional()->word(),
            'account_details' => [],
            'is_default'      => fake()->boolean(30),
            'status'          => fake()->randomElement(['active', 'inactive']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'inactive',
        ]);
    }

    public function default(): static
    {
        return $this->state(fn(array $attrs) => [
            'is_default' => true,
        ]);
    }

    public function bank(): static
    {
        return $this->state(fn(array $attrs) => [
            'method' => 'bank',
        ]);
    }

    public function paypal(): static
    {
        return $this->state(fn(array $attrs) => [
            'method' => 'paypal',
        ]);
    }

    public function stripe(): static
    {
        return $this->state(fn(array $attrs) => [
            'method' => 'stripe',
        ]);
    }

    public function crypto(): static
    {
        return $this->state(fn(array $attrs) => [
            'method' => 'crypto',
        ]);
    }

    public function bkash(): static
    {
        return $this->state(fn(array $attrs) => [
            'method' => 'bkash',
        ]);
    }
}
