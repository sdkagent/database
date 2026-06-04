<?php

namespace Database\Factories;

use App\Models\Commerce\OrderStatusHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderStatusHistoryFactory extends Factory
{
    protected $model = OrderStatusHistory::class;

    public function definition(): array
    {
        $statuses = ['pending', 'confirmed', 'processing', 'completed', 'cancelled', 'refunded'];
        $from = fake()->randomElement($statuses);
        $to = fake()->randomElement(array_filter($statuses, fn($s) => $s !== $from));
        return [
            'order_id'      => \App\Models\Order::factory(),
            'from_status'   => $from,
            'to_status'     => $to,
            'changed_by'    => \App\Models\User::factory(),
            'api_client_id' => \App\Models\ApiClient::factory(),
            'reason'        => fake()->optional()->sentence(),
        ];
    }
}
