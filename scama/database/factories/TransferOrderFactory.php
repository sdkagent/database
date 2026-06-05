<?php

namespace Database\Factories;

use App\Models\Inventory\TransferOrder;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\User;
use App\Models\Inventory\Warehouse;


class TransferOrderFactory extends Factory
{
    protected $model = TransferOrder::class;

    public function definition(): array
    {
        return [
            'from_warehouse_id' => Warehouse::factory(),
            'to_warehouse_id' => Warehouse::factory(),
            'transfer_number' => fake()->unique()->bothify('TO-####'),
            'status' => fake()->randomElement(['draft', 'pending', 'approved', 'in_transit', 'completed', 'cancelled']),
            'requested_by' => User::factory(),
            'approved_by' => User::factory(),
            'notes' => fake()->sentence(),
        ];
    }

    public function status_draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function status_pending(): static
    {
        return $this->state(['status' => 'pending']);
    }

    public function status_approved(): static
    {
        return $this->state(['status' => 'approved']);
    }

    public function status_in_transit(): static
    {
        return $this->state(['status' => 'in_transit']);
    }

    public function status_completed(): static
    {
        return $this->state(['status' => 'completed']);
    }

    public function status_cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }

}
