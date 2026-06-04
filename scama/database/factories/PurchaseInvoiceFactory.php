<?php

namespace Database\Factories;

use App\Models\Procurement\PurchaseInvoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseInvoiceFactory extends Factory
{
    protected $model = PurchaseInvoice::class;

    public function definition(): array
    {
        return [
            'purchase_order_id' => PurchaseOrder::factory(),
            'supplier_id' => Supplier::factory(),
            'invoice_number' => fake()->unique()->bothify('INV-########'),
            'subtotal' => fake()->randomFloat(2, 0, 1000),
            'tax' => fake()->randomFloat(2, 0, 1000),
            'total' => fake()->randomFloat(2, 0, 1000),
            'currency_id' => Currency::factory(),
            'status' => fake()->randomElement(['pending', 'approved', 'paid', 'overdue', 'cancelled']),
            'notes' => fake()->sentence(),
        ];
    }

    public function status_pending(): static
    {
        return $this->state(['status' => 'pending']);
    }

    public function status_approved(): static
    {
        return $this->state(['status' => 'approved']);
    }

    public function status_paid(): static
    {
        return $this->state(['status' => 'paid']);
    }

    public function status_overdue(): static
    {
        return $this->state(['status' => 'overdue']);
    }

    public function status_cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }

}
