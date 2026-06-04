<?php

namespace Database\Factories;

use App\Models\Email\EmailAutomation;
use App\Models\Email\EmailTemplate;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailAutomationFactory extends Factory
{
    protected $model = EmailAutomation::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'trigger_event' => fake()->randomElement(['user.registered', 'order.placed', 'payment.received', 'subscription.renewed']),
            'email_template_id' => EmailTemplate::factory(),
            'conditions' => json_encode([]),
            'audience_filter' => json_encode([]),
            'sender_name' => fake()->name(),
            'sender_email' => fake()->email(),
            'reply_to' => fake()->email(),
            'status' => fake()->randomElement(['draft', 'active', 'paused', 'archived']),
            'created_by' => User::factory(),
        ];
    }

    public function status_draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function status_active(): static
    {
        return $this->state(['status' => 'active']);
    }

    public function status_paused(): static
    {
        return $this->state(['status' => 'paused']);
    }

    public function status_archived(): static
    {
        return $this->state(['status' => 'archived']);
    }

}
