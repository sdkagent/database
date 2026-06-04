<?php

namespace Database\Factories;

use App\Models\Auth\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition(): array
    {
        return [
            'name'      => fake()->company(),
            'slug'      => Str::slug(fake()->unique()->company()),
            'logo_url'  => fake()->optional()->imageUrl(),
            'website'   => fake()->optional()->url(),
            'status'    => fake()->randomElement(['active', 'suspended']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'active',
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'suspended',
        ]);
    }
}
