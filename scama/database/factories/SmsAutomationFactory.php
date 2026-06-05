<?php

namespace Database\Factories;

use App\Models\Sms\SmsAutomation;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\User;
use App\Models\Sms\SmsProvider;
use App\Models\Sms\SmsTemplate;


class SmsAutomationFactory extends Factory
{
    protected $model = SmsAutomation::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'trigger_type' => fake()->randomElement(['event', 'schedule']),
            'event_name' => fake()->randomElement(['user.registered', 'order.placed', 'payment.due']),
            'event_conditions' => json_encode(['status' => 'active']),
            'cron_expression' => fake()->randomElement(['0 0 * * *', '*/5 * * * *', '0 */6 * * *']),
            'timezone' => fake()->timezone(),
            'target_type' => fake()->randomElement(['all', 'selected', 'role', 'event_context']),
            'target_roles' => json_encode(['customer', 'admin']),
            'filter_criteria' => json_encode(['status' => 'active']),
            'message_body' => fake()->sentence(),
            'sms_template_id' => SmsTemplate::factory(),
            'provider_id' => SmsProvider::factory(),
            'is_active' => fake()->boolean(),
            'total_sent' => fake()->numberBetween(0, 1000),
            'created_by' => User::factory(),
        ];
    }

    public function trigger_type_event(): static
    {
        return $this->state(['trigger_type' => 'event']);
    }

    public function trigger_type_schedule(): static
    {
        return $this->state(['trigger_type' => 'schedule']);
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

    public function target_type_event_context(): static
    {
        return $this->state(['target_type' => 'event_context']);
    }

}
