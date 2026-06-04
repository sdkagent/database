<?php

namespace Database\Factories;

use App\Models\Auth\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        return [
            'name'            => fake()->randomElement(['create', 'read', 'update', 'delete']),
            'slug'            => Str::slug(fake()->unique()->word()),
            'description'     => fake()->optional()->sentence(),
            'group'           => fake()->optional()->word(),
            'organization_id' => \App\Models\Organization::factory(),
        ];
    }
}
