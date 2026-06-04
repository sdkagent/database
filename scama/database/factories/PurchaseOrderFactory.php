<?php

namespace Database\Factories;

use App\Models\Billing\Currency;
use App\Models\Procurement\PurchaseOrder;
use App\Models\Procurement\Supplier;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderFactory extends Factory
{
    protected $model = PurchaseOrder::class;

    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'order_number' => fake()->unique()->bothify('PO-########'),
            'status' => fake()->randomElement(['draft', 'pending_approval', 'approved', 'sent', 'partial', 'received', 'cancelled']),
            'order_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'expected_date' => fake()->optional()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'subtotal' => fake()->randomFloat(2, 0, 1000),
            'tax' => fake()->randomFloat(2, 0, 1000),
            'total' => fake()->randomFloat(2, 0, 1000),
            'currency_id' => Currency::factory(),
            'notes' => fake()->sentence(),
            'requested_by' => User::factory(),
            'approved_by' => User::factory(),
        ];
    }

    public function status_draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function status_pending_approval(): static
    {
        return $this->state(['status' => 'pending_approval']);
    }

    public function status_approved(): static
    {
        return $this->state(['status' => 'approved']);
    }

    public function status_sent(): static
    {
        return $this->state(['status' => 'sent']);
    }

    public function status_partial(): static
    {
        return $this->state(['status' => 'partial']);
    }

    public function status_received(): static
    {
        return $this->state(['status' => 'received']);
    }

    public function status_cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }

}
