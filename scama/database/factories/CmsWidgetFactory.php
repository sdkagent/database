<?php

namespace Database\Factories;

use App\Models\Cms\CmsWidget;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CmsWidgetFactory extends Factory
{
    protected $model = CmsWidget::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Recent Posts', 'Popular Tags', 'Newsletter', 'Social Links', 'Categories']);
        return [
            'name'        => ucfirst($name) . ' Widget',
            'slug'        => Str::slug($name . '-widget'),
            'description' => fake()->sentence(),
        ];
    }
}
