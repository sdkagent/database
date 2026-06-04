<?php

namespace Database\Factories;

use App\Models\Theme\ThemeAsset;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeAssetFactory extends Factory
{
    protected $model = ThemeAsset::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['css', 'js', 'image']);
        $ext = $type === 'css' ? 'css' : ($type === 'js' ? 'js' : 'png');
        return [
            'theme_id' => \App\Models\Theme::factory(),
            'type'     => $type,
            'path'     => 'themes/' . fake()->slug() . '/assets/' . fake()->unique()->word() . '.' . $ext,
        ];
    }
}
