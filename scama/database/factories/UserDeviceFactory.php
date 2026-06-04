<?php

namespace Database\Factories;

use App\Models\Auth\UserDevice;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserDeviceFactory extends Factory
{
    protected $model = UserDevice::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'platform' => fake()->randomElement(['windows', 'macos', 'linux', 'ios', 'android']),
            'device_token' => fake()->sha256(),
            'device_name' => fake()->randomElement(['Chrome', 'Firefox', 'Safari', 'Edge', 'Mobile App']),
            'fingerprint' => fake()->sha256(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'is_active' => fake()->boolean(),
        ];
    }

}
