<?php

namespace Database\Factories;

use App\Models\Security\SecurityIncident;
use Illuminate\Database\Eloquent\Factories\Factory;

class SecurityIncidentFactory extends Factory
{
    protected $model = SecurityIncident::class;

    public function definition(): array
    {
        return [
            'incident_type' => fake()->randomElement(['data_breach', 'unauthorized_access', 'malware_infection']),
            'description'   => fake()->paragraph(),
            'status'        => fake()->randomElement(['open', 'investigating', 'resolved']),
        ];
    }

    public function open(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'open']);
    }

    public function resolved(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'resolved']);
    }

    public function investigating(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'investigating']);
    }

    public function incident_type_data_breach(): static
    {
        return $this->state(['incident_type' => 'data_breach']);
    }

    public function incident_type_unauthorized_access(): static
    {
        return $this->state(['incident_type' => 'unauthorized_access']);
    }

    public function incident_type_malware_infection(): static
    {
        return $this->state(['incident_type' => 'malware_infection']);
    }
}
