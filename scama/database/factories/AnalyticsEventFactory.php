<?php

namespace Database\Factories;

use App\Models\Logging\AnalyticsEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnalyticsEventFactory extends Factory
{
    protected $model = AnalyticsEvent::class;

    public function definition(): array
    {
        return [
            'event_type'     => fake()->randomElement(['page_view', 'click', 'scroll', 'form_submit', 'purchase']),
            'page_url'       => '/' . fake()->slug(),
            'referrer_url'   => fake()->optional()->url(),
            'utm_source'     => fake()->optional()->randomElement(['google', 'facebook', 'twitter', 'newsletter']),
            'utm_medium'     => fake()->optional()->randomElement(['cpc', 'social', 'email', 'organic']),
            'utm_campaign'   => fake()->optional()->word(),
            'utm_term'       => fake()->optional()->word(),
            'utm_content'    => fake()->optional()->word(),
            'user_agent'     => fake()->userAgent(),
            'ip_address'     => fake()->ipv4(),
            'session_id'     => fake()->uuid(),
            'user_id'        => \App\Models\User::factory(),
            'post_id'        => null,
            'page_id'        => null,
        ];
    }

    public function eventTypeClick(): static
    {
        return $this->state(['event_type' => 'click']);
    }

    public function eventTypeFormSubmit(): static
    {
        return $this->state(['event_type' => 'form_submit']);
    }

    public function eventTypePageView(): static
    {
        return $this->state(['event_type' => 'page_view']);
    }

    public function eventTypePurchase(): static
    {
        return $this->state(['event_type' => 'purchase']);
    }

    public function eventTypeScroll(): static
    {
        return $this->state(['event_type' => 'scroll']);
    }
}
