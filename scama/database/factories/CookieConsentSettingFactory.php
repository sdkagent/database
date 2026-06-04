<?php

namespace Database\Factories;

use App\Models\Gdpr\CookieConsentSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class CookieConsentSettingFactory extends Factory
{
    protected $model = CookieConsentSetting::class;

    public function definition(): array
    {
        return [
            'name'            => fake()->randomElement(['Analytics Cookies', 'Marketing Cookies', 'Functional Cookies', 'Essential Cookies']),
            'slug'            => fake()->unique()->slug(),
            'description'     => fake()->sentence(),
            'required'        => fake()->boolean(30),
            'default_granted' => fake()->boolean(70),
        ];
    }

    public function required(): static
    {
        return $this->state(fn(array $attrs) => ['required' => true]);
    }

    public function optional(): static
    {
        return $this->state(fn(array $attrs) => ['required' => false]);
    }
}
