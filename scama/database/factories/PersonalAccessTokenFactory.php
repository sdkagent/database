<?php

namespace Database\Factories;

use App\Models\Auth\PersonalAccessToken;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalAccessTokenFactory extends Factory
{
    protected $model = PersonalAccessToken::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'token' => fake()->sha256(),
            'abilities' => json_encode(['*']),
        ];
    }

}
