<?php

namespace Database\Factories;

use App\Models\Cms\CmsTag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CmsTagFactory extends Factory
{
    protected $model = CmsTag::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['php', 'laravel', 'vue', 'react', 'javascript', 'css']);
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
        ];
    }
}
