<?php

namespace Database\Factories;

use App\Models\Logging\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id'     => \App\Models\User::factory(),
            'type'        => fake()->randomElement(['system', 'subscription', 'security', 'product']),
            'title'       => fake()->sentence(),
            'message'     => fake()->paragraph(),
'data' => ['action' => fake()->randomElement(['created', 'updated', 'deleted', 'commented']), 'entity_id' => fake()->numberBetween(1, 1000)],
            'is_read'     => fake()->boolean(30),
            'read_at'     => null,
            'channel'     => fake()->randomElement(['in_app', 'email', 'telegram', 'sms']),
            'action_url'  => fake()->optional()->url(),
            'action_text' => fake()->optional()->sentence(3),
        ];
    }

    public function read(): static
    {
        return $this->state(fn(array $attrs) => [
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function unread(): static
    {
        return $this->state(fn(array $attrs) => [
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    public function type_system(): static
    {
        return $this->state(['type' => 'system']);
    }

    public function type_subscription(): static
    {
        return $this->state(['type' => 'subscription']);
    }

    public function type_security(): static
    {
        return $this->state(['type' => 'security']);
    }

    public function type_product(): static
    {
        return $this->state(['type' => 'product']);
    }

    public function channel_in_app(): static
    {
        return $this->state(['channel' => 'in_app']);
    }

    public function channel_email(): static
    {
        return $this->state(['channel' => 'email']);
    }

    public function channel_telegram(): static
    {
        return $this->state(['channel' => 'telegram']);
    }

    public function channel_sms(): static
    {
        return $this->state(['channel' => 'sms']);
    }
}
