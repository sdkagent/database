<?php

namespace Database\Factories;

use App\Models\Moderation\ModerationBlocklist;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModerationBlocklistFactory extends Factory
{
    protected $model = ModerationBlocklist::class;

    public function definition(): array
    {
        return [
            'block_type' => fake()->randomElement(['ip', 'email', 'domain', 'keyword', 'pattern', 'phone']),
            'value'      => fake()->unique()->word(),
            'reason'     => fake()->sentence(),
            'created_by' => User::factory(),
            'expires_at' => null,
        ];
    }

    public function ip(): static
    {
        return $this->state(fn(array $attrs) => [
            'block_type' => 'ip',
            'value'      => fake()->ipv4(),
        ]);
    }

    public function email(): static
    {
        return $this->state(fn(array $attrs) => [
            'block_type' => 'email',
            'value'      => fake()->email(),
        ]);
    }

    public function domain(): static
    {
        return $this->state(fn(array $attrs) => [
            'block_type' => 'domain',
            'value'      => fake()->domainName(),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn(array $attrs) => ['expires_at' => fake()->dateTimeThisMonth()]);
    }

    public function permanent(): static
    {
        return $this->state(fn(array $attrs) => ['expires_at' => null]);
    }

    public function block_type_keyword(): static
    {
        return $this->state(['block_type' => 'keyword']);
    }

    public function block_type_pattern(): static
    {
        return $this->state(['block_type' => 'pattern']);
    }

    public function block_type_phone(): static
    {
        return $this->state(['block_type' => 'phone']);
    }
}
