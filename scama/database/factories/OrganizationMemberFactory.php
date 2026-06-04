<?php

namespace Database\Factories;

use App\Models\Auth\OrganizationMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationMemberFactory extends Factory
{
    protected $model = OrganizationMember::class;

    public function definition(): array
    {
        return [
            'organization_id' => \App\Models\Organization::factory(),
            'user_id'         => \App\Models\User::factory(),
            'role'            => fake()->randomElement(['owner', 'admin', 'member', 'viewer']),
            'joined_at'       => fake()->dateTimeThisYear(),
        ];
    }

    public function owner(): static
    {
        return $this->state(fn(array $attrs) => [
            'role' => 'owner',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn(array $attrs) => [
            'role' => 'admin',
        ]);
    }

    public function member(): static
    {
        return $this->state(fn(array $attrs) => [
            'role' => 'member',
        ]);
    }

    public function viewer(): static
    {
        return $this->state(fn(array $attrs) => [
            'role' => 'viewer',
        ]);
    }
}
