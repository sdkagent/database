<?php

namespace Database\Factories;

use App\Models\Gdpr\DataExportRequest;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataExportRequestFactory extends Factory
{
    protected $model = DataExportRequest::class;

    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'status'       => fake()->randomElement(['pending', 'processing', 'completed', 'failed']),
            'format'       => fake()->randomElement(['json', 'csv', 'xml']),
            'requested_at' => fake()->dateTimeThisMonth(),
            'completed_at' => null,
            'file_path'    => null,
            'expires_at'   => null,
        ];
    }

    public function format_csv(): static
    {
        return $this->state(['format' => 'csv']);
    }

    public function format_json(): static
    {
        return $this->state(['format' => 'json']);
    }

    public function format_xml(): static
    {
        return $this->state(['format' => 'xml']);
    }

    public function status_processing(): static
    {
        return $this->state(['status' => 'processing']);
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'pending']);
    }

    public function completed(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'       => 'completed',
            'completed_at' => now(),
            'file_path'    => 'exports/user-data-' . fake()->uuid() . '.json',
            'expires_at'   => now()->addDays(7),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'failed']);
    }
}
