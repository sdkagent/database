<?php

namespace Database\Factories;

use App\Models\Sms\SmsCampaignRecipient;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmsCampaignRecipientFactory extends Factory
{
    protected $model = SmsCampaignRecipient::class;

    public function definition(): array
    {
        return [
            'campaign_id' => SmsCampaign::factory(),
            'user_id' => User::factory(),
            'phone' => fake()->phoneNumber(),
            'status' => fake()->randomElement(['pending', 'sent', 'delivered', 'failed', 'bounced']),
            'error_message' => fake()->sentence(),
            'provider_message_id' => fake()->uuid(),
            'provider_id' => SmsProvider::factory(),
        ];
    }

    public function status_pending(): static
    {
        return $this->state(['status' => 'pending']);
    }

    public function status_sent(): static
    {
        return $this->state(['status' => 'sent']);
    }

    public function status_delivered(): static
    {
        return $this->state(['status' => 'delivered']);
    }

    public function status_failed(): static
    {
        return $this->state(['status' => 'failed']);
    }

    public function status_bounced(): static
    {
        return $this->state(['status' => 'bounced']);
    }

}
