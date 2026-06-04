<?php

namespace Database\Factories;

use App\Models\Auth\UserPaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserPaymentMethodFactory extends Factory
{
    protected $model = UserPaymentMethod::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'gateway_id' => PaymentGateway::factory(),
            'method_type' => fake()->randomElement(['card', 'paypal', 'bank', 'crypto']),
            'gateway_token' => fake()->sha256(),
            'display_name' => fake()->name(),
            'last_four' => fake()->creditCardNumber('Visa', false, true),
            'expiry_month' => fake()->numberBetween(1, 12),
            'expiry_year' => fake()->numberBetween(2025, 2030),
            'card_brand' => fake()->randomElement(['visa', 'mastercard', 'amex', 'discover']),
            'is_default' => fake()->boolean(),
            'billing_address_id' => \App\Models\UserAddress::factory(),
        ];
    }

    public function method_type_card(): static
    {
        return $this->state(['method_type' => 'card']);
    }

    public function method_type_paypal(): static
    {
        return $this->state(['method_type' => 'paypal']);
    }

    public function method_type_bank(): static
    {
        return $this->state(['method_type' => 'bank']);
    }

    public function method_type_crypto(): static
    {
        return $this->state(['method_type' => 'crypto']);
    }

}
