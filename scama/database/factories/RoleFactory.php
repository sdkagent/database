<?php

namespace Database\Factories;

use App\Models\Auth\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name'            => fake()->randomElement(['admin', 'editor', 'viewer', 'moderator']),
            'slug'            => Str::slug(fake()->unique()->word()),
            'description'     => fake()->optional()->sentence(),
            'is_system'       => fake()->boolean(20),
            'organization_id' => \App\Models\Organization::factory(),
        ];
    }

    public function system(): static
    {
        return $this->state(fn(array $attrs) => [
            'is_system' => true,
        ]);
    }
}
