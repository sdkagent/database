<?php

namespace Database\Factories;

use App\Models\Procurement\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Billing\Currency;


class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'company_name' => fake()->company(),
            'supplier_code' => fake()->unique()->bothify('SUP-####'),
            'contact_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->country(),
            'postal_code' => fake()->postcode(),
            'tax_id' => fake()->bothify('??-#######'),
            'payment_terms' => fake()->randomElement(['Net 30', 'Net 60', 'Net 90', 'Due on Receipt']),
            'currency_id' => Currency::factory(),
            'status' => fake()->randomElement(['active', 'inactive', 'suspended']),
        ];
    }

    public function status_active(): static
    {
        return $this->state(['status' => 'active']);
    }

    public function status_inactive(): static
    {
        return $this->state(['status' => 'inactive']);
    }

    public function status_suspended(): static
    {
        return $this->state(['status' => 'suspended']);
    }

}
