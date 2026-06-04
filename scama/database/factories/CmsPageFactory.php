<?php

namespace Database\Factories;

use App\Models\Cms\CmsPage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CmsPageFactory extends Factory
{
    protected $model = CmsPage::class;

    public function definition(): array
    {
        $title = fake()->sentence();
        return [
            'title'            => $title,
            'slug'             => Str::slug($title),
            'content'          => fake()->paragraphs(5, true),
            'status'           => fake()->randomElement(['published', 'draft']),
            'meta_title'       => fake()->optional()->sentence(),
            'meta_description' => fake()->optional()->sentence(),
            'meta_keywords'    => fake()->optional()->words(5, true),
            'canonical_url'    => fake()->optional()->url(),
            'og_image'         => fake()->optional()->imageUrl(),
            'og_title'         => fake()->optional()->sentence(),
            'og_description'   => fake()->optional()->sentence(),
            'twitter_card'     => fake()->optional()->randomElement(['summary', 'summary_large_image', 'app', 'player']),
            'noindex'          => fake()->boolean(10),
            'priority'         => fake()->randomFloat(1, 0.0, 1.0),
            'changefreq'       => fake()->randomElement(['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never']),
            'sitemap_include'  => true,
            'published_at'     => fake()->optional()->dateTimeThisYear(),
            'scheduled_for'    => null,
            'author_id'        => \App\Models\User::factory(),
        ];
    }

    public function changefreq_always(): static
    {
        return $this->state(['changefreq' => 'always']);
    }

    public function changefreq_daily(): static
    {
        return $this->state(['changefreq' => 'daily']);
    }

    public function changefreq_hourly(): static
    {
        return $this->state(['changefreq' => 'hourly']);
    }

    public function changefreq_monthly(): static
    {
        return $this->state(['changefreq' => 'monthly']);
    }

    public function changefreq_never(): static
    {
        return $this->state(['changefreq' => 'never']);
    }

    public function changefreq_weekly(): static
    {
        return $this->state(['changefreq' => 'weekly']);
    }

    public function changefreq_yearly(): static
    {
        return $this->state(['changefreq' => 'yearly']);
    }

    public function published(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }
}
