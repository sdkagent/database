<?php

namespace Database\Factories;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'seller_id'      => \App\Models\SellerProfile::factory(),
            'name'           => fake()->words(3, true),
            'slug'           => Str::slug(fake()->unique()->words(3, true)),
            'description'    => fake()->optional()->paragraph(),
            'type'           => fake()->randomElement(['script', 'software', 'plugin', 'saas', 'desktop']),
            'base_price'     => fake()->randomFloat(2, 5, 500),
            'sku'            => fake()->optional()->ean13(),
            'stock'          => fake()->optional()->numberBetween(0, 1000),
            'download_limit' => fake()->optional()->numberBetween(1, 100),
            'total_sales'    => fake()->numberBetween(0, 1000),
            'avg_rating'     => fake()->randomFloat(2, 1, 5),
            'review_count'   => fake()->numberBetween(0, 100),
            'version'        => fake()->optional()->semver(),
            'download_url'   => fake()->optional()->url(),
            'status'         => fake()->randomElement(['draft', 'active', 'archived']),
            'demo_url'       => fake()->optional()->url(),
            'docs_url'       => fake()->optional()->url(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'draft',
        ]);
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'active',
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'archived',
        ]);
    }

    public function type_script(): static
    {
        return $this->state(['type' => 'script']);
    }

    public function type_software(): static
    {
        return $this->state(['type' => 'software']);
    }

    public function type_plugin(): static
    {
        return $this->state(['type' => 'plugin']);
    }

    public function type_saas(): static
    {
        return $this->state(['type' => 'saas']);
    }

    public function type_desktop(): static
    {
        return $this->state(['type' => 'desktop']);
    }
}
