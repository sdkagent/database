<?php

namespace Database\Factories;

use App\Models\Billing\PaymentGateway;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentGatewayFactory extends Factory
{
    protected $model = PaymentGateway::class;

    public function definition(): array
    {
        return [
            'name'        => fake()->randomElement(['Stripe', 'PayPal', 'Square', 'Authorize.net']),
            'description' => fake()->sentence(),
            'is_active'   => true,
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => ['is_active' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attrs) => ['is_active' => false]);
    }

    public function stripe(): static
    {
        return $this->state(fn(array $attrs) => ['name' => 'Stripe']);
    }

    public function payPal(): static
    {
        return $this->state(fn(array $attrs) => ['name' => 'PayPal']);
    }

    public function square(): static
    {
        return $this->state(fn(array $attrs) => ['name' => 'Square']);
    }

    public function authorizeNet(): static
    {
        return $this->state(fn(array $attrs) => ['name' => 'Authorize.net']);
    }
}
