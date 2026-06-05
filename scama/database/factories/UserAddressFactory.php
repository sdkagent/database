<?php

namespace Database\Factories;

use App\Models\Auth\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\User;


class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'label' => fake()->randomElement(['Home', 'Work', 'Billing', 'Shipping']),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'full_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'address_line1' => fake()->address(),
            'address_line2' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),
            'is_default_billing' => fake()->boolean(),
            'is_default_shipping' => fake()->boolean(),
        ];
    }

}
