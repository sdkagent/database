<?php

namespace Database\Factories;

use App\Models\Cms\CmsMenu;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CmsMenuFactory extends Factory
{
    protected $model = CmsMenu::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Main Menu', 'Footer Menu', 'Sidebar Menu', 'Top Bar']);
        return [
            'name' => ucfirst($name) . ' Menu',
            'slug' => Str::slug($name . '-menu'),
        ];
    }
}
