<?php

namespace Database\Factories;

use App\Models\Support\FaqItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FaqItemFactory extends Factory
{
    protected $model = FaqItem::class;

    public function definition(): array
    {
        return [
            'question' => fake()->sentence() . '?',
            'answer'   => fake()->paragraphs(2, true),
            'slug'     => Str::slug(fake()->sentence()),
            'category' => fake()->randomElement(['General', 'Billing', 'Technical', 'Account']),
            'position' => fake()->numberBetween(0, 100),
            'status'   => fake()->randomElement(['published', 'draft']),
        ];
    }

    public function category_Account(): static
    {
        return $this->state(['category' => 'Account']);
    }

    public function category_Billing(): static
    {
        return $this->state(['category' => 'Billing']);
    }

    public function category_General(): static
    {
        return $this->state(['category' => 'General']);
    }

    public function category_Technical(): static
    {
        return $this->state(['category' => 'Technical']);
    }

    public function published(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'published']);
    }

    public function draft(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'draft']);
    }
}
