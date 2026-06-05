<?php

namespace Database\Factories;

use App\Models\Procurement\SupplierQuotation;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Billing\Currency;
use App\Models\Procurement\Rfq;
use App\Models\Procurement\Supplier;


class SupplierQuotationFactory extends Factory
{
    protected $model = SupplierQuotation::class;

    public function definition(): array
    {
        return [
            'rfq_id' => Rfq::factory(),
            'supplier_id' => Supplier::factory(),
            'quotation_number' => fake()->unique()->bothify('QTN-####'),
            'valid_until' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'subtotal' => fake()->randomFloat(2, 0, 1000),
            'tax' => fake()->randomFloat(2, 0, 1000),
            'total' => fake()->randomFloat(2, 0, 1000),
            'currency_id' => Currency::factory(),
            'status' => fake()->randomElement(['received', 'evaluated', 'accepted', 'rejected']),
            'notes' => fake()->sentence(),
        ];
    }

    public function status_received(): static
    {
        return $this->state(['status' => 'received']);
    }

    public function status_evaluated(): static
    {
        return $this->state(['status' => 'evaluated']);
    }

    public function status_accepted(): static
    {
        return $this->state(['status' => 'accepted']);
    }

    public function status_rejected(): static
    {
        return $this->state(['status' => 'rejected']);
    }

}
