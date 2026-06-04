<?php

namespace Database\Factories;

use App\Models\Logging\AuditTrail;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditTrailFactory extends Factory
{
    protected $model = AuditTrail::class;

    public function definition(): array
    {
        return [
            'user_id'       => \App\Models\User::factory(),
            'action'        => fake()->randomElement(['created', 'updated', 'deleted', 'viewed']),
            'entity'        => fake()->randomElement(['user', 'product', 'order', 'license']),
            'entity_id'     => fake()->numberBetween(1, 1000),
            'activity_type' => fake()->randomElement(['admin', 'system', 'user']),
            'description'   => fake()->sentence(),
            'details'       => ['ip' => fake()->ipv4()],
            'old_value'     => null,
            'new_value'     => null,
            'ip_address'    => fake()->ipv4(),
        ];
    }

    public function actionCreated(): static
    {
        return $this->state(['action' => 'created']);
    }

    public function actionDeleted(): static
    {
        return $this->state(['action' => 'deleted']);
    }

    public function actionUpdated(): static
    {
        return $this->state(['action' => 'updated']);
    }

    public function actionViewed(): static
    {
        return $this->state(['action' => 'viewed']);
    }

    public function activityTypeAdmin(): static
    {
        return $this->state(['activity_type' => 'admin']);
    }

    public function activityTypeSystem(): static
    {
        return $this->state(['activity_type' => 'system']);
    }

    public function activityTypeUser(): static
    {
        return $this->state(['activity_type' => 'user']);
    }

    public function entityLicense(): static
    {
        return $this->state(['entity' => 'license']);
    }

    public function entityOrder(): static
    {
        return $this->state(['entity' => 'order']);
    }

    public function entityProduct(): static
    {
        return $this->state(['entity' => 'product']);
    }

    public function entityUser(): static
    {
        return $this->state(['entity' => 'user']);
    }
}
