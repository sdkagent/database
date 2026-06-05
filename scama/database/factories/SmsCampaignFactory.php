<?php

namespace Database\Factories;

use App\Models\Sms\SmsCampaign;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\User;
use App\Models\Sms\SmsProvider;
use App\Models\Sms\SmsTemplate;


class SmsCampaignFactory extends Factory
{
    protected $model = SmsCampaign::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'message_body' => fake()->sentence(),
            'sms_template_id' => SmsTemplate::factory(),
            'provider_id' => SmsProvider::factory(),
            'target_type' => fake()->randomElement(['all', 'selected', 'role']),
            'target_roles' => json_encode(['customer']),
            'target_user_ids' => json_encode([fake()->numberBetween(1, 100)]),
            'filter_criteria' => json_encode(['status' => 'active']),
            'status' => fake()->randomElement(['draft', 'scheduled', 'sending', 'sent', 'partial', 'failed', 'cancelled']),
            'total_recipients' => fake()->numberBetween(0, 5000),
            'success_count' => fake()->numberBetween(0, 100),
            'fail_count' => fake()->numberBetween(0, 100),
            'created_by' => User::factory(),
        ];
    }

    public function target_type_all(): static
    {
        return $this->state(['target_type' => 'all']);
    }

    public function target_type_selected(): static
    {
        return $this->state(['target_type' => 'selected']);
    }

    public function target_type_role(): static
    {
        return $this->state(['target_type' => 'role']);
    }

    public function status_draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function status_scheduled(): static
    {
        return $this->state(['status' => 'scheduled']);
    }

    public function status_sending(): static
    {
        return $this->state(['status' => 'sending']);
    }

    public function status_sent(): static
    {
        return $this->state(['status' => 'sent']);
    }

    public function status_partial(): static
    {
        return $this->state(['status' => 'partial']);
    }

    public function status_failed(): static
    {
        return $this->state(['status' => 'failed']);
    }

    public function status_cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }

}
