<?php

namespace Database\Factories;

use App\Models\Cms\CmsCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CmsCategoryFactory extends Factory
{
    protected $model = CmsCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['News', 'Tutorials', 'Reviews', 'Updates', 'Events']);
        return [
            'name'        => ucfirst($name),
            'slug'        => Str::slug($name),
            'description' => fake()->sentence(),
        ];
    }
}
