<?php

namespace Database\Factories;

use App\Models\Cms\AuthorProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorProfileFactory extends Factory
{
    protected $model = AuthorProfile::class;

    public function definition(): array
    {
        return [
            'user_id'        => \App\Models\User::factory(),
            'display_name'   => fake()->name(),
            'avatar_url'     => fake()->imageUrl(),
            'bio'            => fake()->paragraph(),
            'website_url'    => fake()->optional()->url(),
            'twitter_handle' => fake()->optional()->userName(),
            'github_handle'  => fake()->optional()->userName(),
            'linkedin_url'   => fake()->optional()->url(),
            'is_public'      => true,
        ];
    }
}
