<?php

namespace Database\Factories;

use App\Models\I18n\LanguagePack;
use Illuminate\Database\Eloquent\Factories\Factory;

class LanguagePackFactory extends Factory
{
    protected $model = LanguagePack::class;

    public function definition(): array
    {
        return [
            'code'         => fake()->unique()->languageCode(),
            'name'         => fake()->unique()->randomElement(['English', 'Spanish', 'French', 'German', 'Chinese', 'Japanese', 'Arabic']),
            'native_name'  => fake()->randomElement(['English', 'Español', 'Français', 'Deutsch', '中文', '日本語', 'العربية']),
            'is_rtl'       => fake()->boolean(10),
            'is_default'   => fake()->boolean(5),
            'is_active'    => fake()->boolean(80),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => ['is_active' => true]);
    }

    public function rtl(): static
    {
        return $this->state(fn(array $attrs) => ['is_rtl' => true]);
    }

    public function default(): static
    {
        return $this->state(fn(array $attrs) => ['is_default' => true]);
    }
}
