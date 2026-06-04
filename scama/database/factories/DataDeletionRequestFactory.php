<?php

namespace Database\Factories;

use App\Models\Gdpr\DataDeletionRequest;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataDeletionRequestFactory extends Factory
{
    protected $model = DataDeletionRequest::class;

    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'status'       => fake()->randomElement(['pending', 'approved', 'rejected', 'completed']),
            'reason'       => fake()->sentence(),
            'requested_at' => now(),
            'processed_at' => null,
            'processed_by' => null,
            'notes'        => null,
        ];
    }

    public function status_completed(): static
    {
        return $this->state(['status' => 'completed']);
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'pending']);
    }

    public function approved(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'       => 'approved',
            'processed_at' => now(),
            'processed_by' => User::factory(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'       => 'rejected',
            'processed_at' => now(),
            'processed_by' => User::factory(),
            'notes'        => 'Request does not meet deletion criteria',
        ]);
    }
}
