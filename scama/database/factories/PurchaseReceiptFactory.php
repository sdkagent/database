<?php

namespace Database\Factories;

use App\Models\Procurement\PurchaseReceipt;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\User;
use App\Models\Procurement\PurchaseOrder;


class PurchaseReceiptFactory extends Factory
{
    protected $model = PurchaseReceipt::class;

    public function definition(): array
    {
        return [
            'purchase_order_id' => PurchaseOrder::factory(),
            'receipt_number' => fake()->unique()->bothify('RCP-####'),
            'status' => fake()->randomElement(['draft', 'completed', 'partial', 'cancelled']),
            'notes' => fake()->sentence(),
            'received_by' => User::factory(),
        ];
    }

    public function status_draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function status_completed(): static
    {
        return $this->state(['status' => 'completed']);
    }

    public function status_partial(): static
    {
        return $this->state(['status' => 'partial']);
    }

    public function status_cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }

}
