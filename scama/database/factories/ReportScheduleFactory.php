<?php

namespace Database\Factories;

use App\Models\Reporting\ReportDefinition;
use App\Models\Reporting\ReportSchedule;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportScheduleFactory extends Factory
{
    protected $model = ReportSchedule::class;

    public function definition(): array
    {
        return [
            'report_id'       => ReportDefinition::factory(),
            'name'            => fake()->unique()->randomElement(['Weekly Summary', 'Monthly Report', 'Daily Digest']),
            'cron_expression' => fake()->randomElement(['0 0 * * *', '0 * * * *', '0 0 1 * *']),
            'recipients'      => [fake()->email()],
            'format'          => fake()->randomElement(['pdf', 'csv', 'excel', 'json']),
            'config'          => null,
            'last_run_at'     => null,
            'next_run_at'     => fake()->dateTimeBetween('now', '+1 month'),
            'status'          => fake()->randomElement(['active', 'paused', 'completed', 'failed']),
            'created_by'      => User::factory(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'active']);
    }

    public function paused(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'paused']);
    }

    public function completed(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'completed']);
    }

    public function failed(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'failed']);
    }

    public function format_pdf(): static
    {
        return $this->state(['format' => 'pdf']);
    }

    public function format_csv(): static
    {
        return $this->state(['format' => 'csv']);
    }

    public function format_excel(): static
    {
        return $this->state(['format' => 'excel']);
    }

    public function format_json(): static
    {
        return $this->state(['format' => 'json']);
    }
}
