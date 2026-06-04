<?php

namespace Database\Factories;

use App\Models\Content\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = fake()->sentence();
        return [
            'type'             => fake()->randomElement(['blog', 'cms']),
            'author_id'        => \App\Models\User::factory(),
            'title'            => $title,
            'slug'             => Str::slug($title),
            'content'          => fake()->paragraphs(5, true),
            'excerpt'          => fake()->paragraph(),
            'featured_image'   => fake()->optional()->imageUrl(),
            'category_id'      => \App\Models\CmsCategory::factory(),
            'status'           => fake()->randomElement(['published', 'draft', 'scheduled']),
            'published_at'     => fake()->optional()->dateTimeThisYear(),
            'scheduled_for'    => null,
            'meta_title'       => fake()->optional()->sentence(),
            'meta_description' => fake()->optional()->sentence(),
            'meta_keywords'    => fake()->optional()->words(5, true),
            'canonical_url'    => fake()->optional()->url(),
            'view_count'       => fake()->numberBetween(0, 10000),
            'og_image'         => fake()->optional()->imageUrl(),
            'og_title'         => fake()->optional()->sentence(),
            'og_description'   => fake()->optional()->sentence(),
            'twitter_card'     => fake()->optional()->randomElement(['summary', 'summary_large_image', 'app', 'player']),
            'noindex'          => fake()->boolean(10),
            'priority'         => fake()->randomFloat(1, 0.0, 1.0),
            'changefreq'       => fake()->randomElement(['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never']),
            'sitemap_include'  => true,
        ];
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

    public function scheduled(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'scheduled',
            'scheduled_for' => now()->addDays(7),
        ]);
    }

    public function blog(): static
    {
        return $this->state(fn(array $attrs) => [
            'type' => 'blog',
        ]);
    }

    public function cms(): static
    {
        return $this->state(fn(array $attrs) => [
            'type' => 'cms',
        ]);
    }

    public function always(): static
    {
        return $this->state(fn(array $attrs) => [
            'changefreq' => 'always',
        ]);
    }

    public function hourly(): static
    {
        return $this->state(fn(array $attrs) => [
            'changefreq' => 'hourly',
        ]);
    }

    public function daily(): static
    {
        return $this->state(fn(array $attrs) => [
            'changefreq' => 'daily',
        ]);
    }

    public function weekly(): static
    {
        return $this->state(fn(array $attrs) => [
            'changefreq' => 'weekly',
        ]);
    }

    public function monthly(): static
    {
        return $this->state(fn(array $attrs) => [
            'changefreq' => 'monthly',
        ]);
    }

    public function yearly(): static
    {
        return $this->state(fn(array $attrs) => [
            'changefreq' => 'yearly',
        ]);
    }

    public function never(): static
    {
        return $this->state(fn(array $attrs) => [
            'changefreq' => 'never',
        ]);
    }
}
