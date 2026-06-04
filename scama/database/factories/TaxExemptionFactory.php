<?php

namespace Database\Factories;

use App\Models\Product\Product;
use App\Models\Tax\TaxExemption;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxExemptionFactory extends Factory
{
    protected $model = TaxExemption::class;

    public function definition(): array
    {
        return [
            'user_id'            => User::factory(),
            'product_id'         => null,
            'exemption_type'     => fake()->randomElement(['wholesale', 'resale', 'nonprofit', 'government', 'educational']),
            'certificate_number' => fake()->bothify('EX-####-????'),
            'issuing_authority'  => fake()->company(),
            'valid_from'         => fake()->date(),
            'valid_to'           => fake()->dateTimeBetween('+1 year', '+5 years')->format('Y-m-d'),
            'status'             => fake()->randomElement(['pending', 'active', 'expired', 'revoked']),
            'verified_by'        => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'active']);
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'pending', 'verified_by' => null]);
    }

    public function exemption_type_wholesale(): static
    {
        return $this->state(['exemption_type' => 'wholesale']);
    }

    public function exemption_type_resale(): static
    {
        return $this->state(['exemption_type' => 'resale']);
    }

    public function exemption_type_nonprofit(): static
    {
        return $this->state(['exemption_type' => 'nonprofit']);
    }

    public function exemption_type_government(): static
    {
        return $this->state(['exemption_type' => 'government']);
    }

    public function exemption_type_educational(): static
    {
        return $this->state(['exemption_type' => 'educational']);
    }

    public function status_expired(): static
    {
        return $this->state(['status' => 'expired']);
    }

    public function status_revoked(): static
    {
        return $this->state(['status' => 'revoked']);
    }
}
