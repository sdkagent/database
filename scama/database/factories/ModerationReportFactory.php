<?php

namespace Database\Factories;

use App\Models\Moderation\ModerationQueue;
use App\Models\Moderation\ModerationReport;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModerationReportFactory extends Factory
{
    protected $model = ModerationReport::class;

    public function definition(): array
    {
        return [
            'queue_item_id'   => ModerationQueue::factory(),
            'reporter_id'     => User::factory(),
            'reason_category' => fake()->randomElement(['spam', 'abuse', 'inappropriate', 'copyright', 'misleading', 'other']),
            'description'     => fake()->sentence(),
        ];
    }

    public function reason_category_spam(): static
    {
        return $this->state(['reason_category' => 'spam']);
    }

    public function reason_category_abuse(): static
    {
        return $this->state(['reason_category' => 'abuse']);
    }

    public function reason_category_inappropriate(): static
    {
        return $this->state(['reason_category' => 'inappropriate']);
    }

    public function reason_category_copyright(): static
    {
        return $this->state(['reason_category' => 'copyright']);
    }

    public function reason_category_misleading(): static
    {
        return $this->state(['reason_category' => 'misleading']);
    }

    public function reason_category_other(): static
    {
        return $this->state(['reason_category' => 'other']);
    }
}
