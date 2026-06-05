<?php

namespace Database\Factories;

use App\Models\Procurement\SupplierContact;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Procurement\Supplier;


class SupplierContactFactory extends Factory
{
    protected $model = SupplierContact::class;

    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'job_title' => fake()->jobTitle(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'is_primary' => fake()->boolean(),
        ];
    }

}
