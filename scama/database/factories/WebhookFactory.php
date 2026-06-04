<?php

namespace Database\Factories;

use App\Models\Logging\Webhook;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebhookFactory extends Factory
{
    protected $model = Webhook::class;

    public function definition(): array
    {
        return [
            'name'              => fake()->unique()->randomElement(['Order Created', 'Payment Received', 'User Registered']) . '-webhook',
            'url'               => fake()->url(),
            'events'            => ['order.created', 'license.activated'],
            'secret'            => fake()->sha256(),
            'is_active'         => true,
            'last_triggered_at' => null,
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
}
