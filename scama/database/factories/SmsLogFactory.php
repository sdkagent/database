<?php

namespace Database\Factories;

use App\Models\Sms\SmsLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmsLogFactory extends Factory
{
    protected $model = SmsLog::class;

    public function definition(): array
    {
        return [
            'provider_id' => SmsProvider::factory(),
            'campaign_id' => SmsCampaign::factory(),
            'recipient_id' => SmsCampaignRecipient::factory(),
            'direction' => fake()->randomElement(['outgoing', 'callback']),
            'request_payload' => fake()->sentence(),
            'response_payload' => fake()->sentence(),
            'http_status' => (string) fake()->numberBetween(200, 500),
            'provider_message_id' => fake()->uuid(),
            'error_message' => fake()->sentence(),
        ];
    }

    public function direction_outgoing(): static
    {
        return $this->state(['direction' => 'outgoing']);
    }

    public function direction_callback(): static
    {
        return $this->state(['direction' => 'callback']);
    }

}
