<?php

namespace Database\Factories;

use App\Models\Auth\Session;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\User;


class SessionFactory extends Factory
{
    protected $model = Session::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'payload' => json_encode([]),
            'last_activity' => fake()->unixTime(),
        ];
    }

}
