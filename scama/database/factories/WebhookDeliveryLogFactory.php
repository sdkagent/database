<?php

namespace Database\Factories;

use App\Models\Logging\WebhookDeliveryLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Logging\Webhook;


class WebhookDeliveryLogFactory extends Factory
{
    protected $model = WebhookDeliveryLog::class;

    public function definition(): array
    {
        return [
            'webhook_id' => Webhook::factory(),
            'event_type' => fake()->randomElement(['order.completed', 'payment.failed', 'license.activated']),
            'payload' => json_encode([]),
            'request_headers' => json_encode([]),
            'response_status' => (string) fake()->numberBetween(200, 500),
            'response_body' => fake()->sentence(),
            'attempt' => fake()->numberBetween(0, 100),
            'success' => fake()->boolean(),
            'error_message' => fake()->sentence(),
        ];
    }

}
