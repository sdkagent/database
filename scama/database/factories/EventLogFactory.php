<?php

namespace Database\Factories;

use App\Models\Logging\EventLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventLogFactory extends Factory
{
    protected $model = EventLog::class;

    public function definition(): array
    {
        return [
            'event_type' => fake()->randomElement(['created', 'updated', 'deleted', 'viewed']),
            'source_type' => fake()->randomElement(['user', 'system', 'api', 'admin']),
            'source_id' => fake()->numberBetween(1, 1000),
            'payload' => json_encode([]),
        ];
    }

}
