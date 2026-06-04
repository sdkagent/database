<?php

namespace Database\Factories;

use App\Models\Commerce\ReturnRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReturnRequestFactory extends Factory
{
    protected $model = ReturnRequest::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'order_item_id' => OrderItem::factory(),
            'user_id' => User::factory(),
            'reason' => fake()->randomElement(['defective', 'wrong_item', 'not_as_described', 'changed_mind', 'other']),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(['pending', 'approved', 'received', 'rejected', 'refunded']),
            'resolution' => fake()->randomElement(['refund', 'replacement', 'store_credit']),
            'admin_notes' => fake()->sentence(),
            'processed_by' => User::factory(),
        ];
    }

    public function reason_defective(): static
    {
        return $this->state(['reason' => 'defective']);
    }

    public function reason_wrong_item(): static
    {
        return $this->state(['reason' => 'wrong_item']);
    }

    public function reason_not_as_described(): static
    {
        return $this->state(['reason' => 'not_as_described']);
    }

    public function reason_changed_mind(): static
    {
        return $this->state(['reason' => 'changed_mind']);
    }

    public function reason_other(): static
    {
        return $this->state(['reason' => 'other']);
    }

    public function status_pending(): static
    {
        return $this->state(['status' => 'pending']);
    }

    public function status_approved(): static
    {
        return $this->state(['status' => 'approved']);
    }

    public function status_received(): static
    {
        return $this->state(['status' => 'received']);
    }

    public function status_rejected(): static
    {
        return $this->state(['status' => 'rejected']);
    }

    public function status_refunded(): static
    {
        return $this->state(['status' => 'refunded']);
    }

    public function resolution_refund(): static
    {
        return $this->state(['resolution' => 'refund']);
    }

    public function resolution_replacement(): static
    {
        return $this->state(['resolution' => 'replacement']);
    }

    public function resolution_store_credit(): static
    {
        return $this->state(['resolution' => 'store_credit']);
    }

}
