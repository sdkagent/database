<?php

namespace Database\Factories;

use App\Models\Support\KnowledgeBaseArticle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KnowledgeBaseArticleFactory extends Factory
{
    protected $model = KnowledgeBaseArticle::class;

    public function definition(): array
    {
        $title = fake()->sentence();
        return [
            'title'    => $title,
            'slug'     => Str::slug($title),
            'content'  => fake()->paragraphs(5, true),
            'category' => fake()->randomElement(['Getting Started', 'Troubleshooting', 'FAQ', 'Advanced']),
            'status'   => fake()->randomElement(['published', 'draft']),
        ];
    }

    public function published(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'published']);
    }

    public function draft(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'draft']);
    }

    public function category_getting_started(): static
    {
        return $this->state(['category' => 'Getting Started']);
    }

    public function category_troubleshooting(): static
    {
        return $this->state(['category' => 'Troubleshooting']);
    }

    public function category_faq(): static
    {
        return $this->state(['category' => 'FAQ']);
    }

    public function category_advanced(): static
    {
        return $this->state(['category' => 'Advanced']);
    }
}
