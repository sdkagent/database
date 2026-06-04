<?php

namespace Database\Factories;

use App\Models\Cms\CmsSocialLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class CmsSocialLinkFactory extends Factory
{
    protected $model = CmsSocialLink::class;

    public function definition(): array
    {
        return [
            'platform' => fake()->randomElement(['facebook', 'twitter', 'linkedin', 'github', 'youtube']),
            'url'      => fake()->url(),
            'position' => fake()->numberBetween(0, 100),
        ];
    }

    public function platform_facebook(): static
    {
        return $this->state(['platform' => 'facebook']);
    }

    public function platform_github(): static
    {
        return $this->state(['platform' => 'github']);
    }

    public function platform_linkedin(): static
    {
        return $this->state(['platform' => 'linkedin']);
    }

    public function platform_twitter(): static
    {
        return $this->state(['platform' => 'twitter']);
    }

    public function platform_youtube(): static
    {
        return $this->state(['platform' => 'youtube']);
    }
}
