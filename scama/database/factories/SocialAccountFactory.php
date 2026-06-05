<?php

namespace Database\Factories;

use App\Models\Auth\SocialAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\User;


class SocialAccountFactory extends Factory
{
    protected $model = SocialAccount::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'provider' => fake()->randomElement(['google', 'facebook', 'github', 'twitter']),
            'provider_id' => fake()->uuid(),
            'provider_email' => fake()->email(),
            'avatar_url' => fake()->url(),
            'access_token' => fake()->sha256(),
            'refresh_token' => fake()->sha256(),
        ];
    }

}
