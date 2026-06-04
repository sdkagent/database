<?php

namespace Database\Factories;

use App\Models\Seller\SellerVerification;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellerVerificationFactory extends Factory
{
    protected $model = SellerVerification::class;

    public function definition(): array
    {
        return [
            'seller_id'        => \App\Models\SellerProfile::factory(),
            'document_type'    => fake()->randomElement(['id_card', 'passport', 'business_license', 'tax_id']),
            'document_url'     => fake()->url(),
            'status'           => fake()->randomElement(['pending', 'approved', 'rejected']),
            'verified_by'      => fake()->optional(0.5)->randomElement([\App\Models\User::factory()]),
            'verified_at'      => fake()->optional(0.5)->dateTimeThisYear(),
            'rejection_reason' => fake()->optional(0.3)->sentence(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'pending',
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'      => 'approved',
            'verified_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'           => 'rejected',
            'rejection_reason' => fake()->sentence(),
        ]);
    }

    public function document_type_id_card(): static
    {
        return $this->state(['document_type' => 'id_card']);
    }

    public function document_type_passport(): static
    {
        return $this->state(['document_type' => 'passport']);
    }

    public function document_type_business_license(): static
    {
        return $this->state(['document_type' => 'business_license']);
    }

    public function document_type_tax_id(): static
    {
        return $this->state(['document_type' => 'tax_id']);
    }
}
