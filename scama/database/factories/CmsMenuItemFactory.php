<?php

namespace Database\Factories;

use App\Models\Cms\CmsMenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class CmsMenuItemFactory extends Factory
{
    protected $model = CmsMenuItem::class;

    public function definition(): array
    {
        return [
            'menu_id'  => \App\Models\CmsMenu::factory(),
            'title'    => fake()->words(2, true),
            'url'      => '/' . fake()->slug(),
            'target'   => fake()->randomElement(['_self', '_blank']),
            'position' => fake()->numberBetween(0, 100),
        ];
    }

    public function target__blank(): static
    {
        return $this->state(['target' => '_blank']);
    }

    public function target__self(): static
    {
        return $this->state(['target' => '_self']);
    }
}
