<?php

namespace Database\Factories;

use App\Models\Auth\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserRoleFactory extends Factory
{
    protected $model = UserRole::class;

    public function definition(): array
    {
        return [
            'user_id'         => \App\Models\User::factory(),
            'role_id'         => \App\Models\Role::factory(),
            'organization_id' => \App\Models\Organization::factory(),
            'assigned_at'     => fake()->dateTimeThisYear(),
        ];
    }
}
