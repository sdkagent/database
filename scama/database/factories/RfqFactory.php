<?php

namespace Database\Factories;

use App\Models\Procurement\Rfq;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\User;


class RfqFactory extends Factory
{
    protected $model = Rfq::class;

    public function definition(): array
    {
        return [
            'rfq_number' => fake()->unique()->bothify('RFQ-####'),
            'title' => fake()->sentence(),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(['draft', 'sent', 'received', 'evaluating', 'awarded', 'cancelled']),
            'created_by' => User::factory(),
        ];
    }

    public function status_draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function status_sent(): static
    {
        return $this->state(['status' => 'sent']);
    }

    public function status_received(): static
    {
        return $this->state(['status' => 'received']);
    }

    public function status_evaluating(): static
    {
        return $this->state(['status' => 'evaluating']);
    }

    public function status_awarded(): static
    {
        return $this->state(['status' => 'awarded']);
    }

    public function status_cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }

}
