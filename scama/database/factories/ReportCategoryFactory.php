<?php

namespace Database\Factories;

use App\Models\Reporting\ReportCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportCategoryFactory extends Factory
{
    protected $model = ReportCategory::class;

    public function definition(): array
    {
        return [
            'name'       => fake()->randomElement(['Sales', 'Users', 'Products', 'Finance']),
            'slug'       => fake()->unique()->slug(),
            'description' => fake()->sentence(),
            'parent_id'  => null,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    public function childOf(ReportCategory $parent): static
    {
        return $this->state(fn(array $attrs) => ['parent_id' => $parent->id]);
    }
}
