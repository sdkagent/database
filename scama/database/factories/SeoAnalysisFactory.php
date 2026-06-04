<?php

namespace Database\Factories;

use App\Models\Cms\SeoAnalysis;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeoAnalysisFactory extends Factory
{
    protected $model = SeoAnalysis::class;

    public function definition(): array
    {
        return [
            'content_type'      => fake()->randomElement(['post', 'cms_page']),
            'content_id'        => fake()->numberBetween(1, 1000),
            'score'             => fake()->randomFloat(2, 0, 100),
            'issues'            => fake()->optional()->randomElement([
                ['Missing meta description', 'No alt text on images'],
                ['Duplicate title tag', 'Low word count'],
                null,
            ]),
            'word_count'        => fake()->numberBetween(100, 5000),
            'readability_score' => fake()->randomFloat(2, 0, 100),
        ];
    }

    public function content_type_post(): static
    {
        return $this->state(['content_type' => 'post']);
    }

    public function content_type_cms_page(): static
    {
        return $this->state(['content_type' => 'cms_page']);
    }
}
