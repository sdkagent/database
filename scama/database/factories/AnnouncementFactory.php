<?php

namespace Database\Factories;

use App\Models\System\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        return [
            'title'     => fake()->sentence(),
            'content'   => fake()->paragraphs(3, true),
            'is_active' => fake()->boolean(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => ['is_active' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attrs) => ['is_active' => false]);
    }
}
