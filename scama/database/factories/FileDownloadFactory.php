<?php

namespace Database\Factories;

use App\Models\Commerce\FileDownload;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileDownloadFactory extends Factory
{
    protected $model = FileDownload::class;

    public function definition(): array
    {
        return [
            'order_item_id'   => \App\Models\OrderItem::factory(),
            'user_id'         => \App\Models\User::factory(),
            'ip_address'      => fake()->optional()->ipv4(),
            'download_count'  => fake()->numberBetween(0, 50),
            'last_downloaded' => fake()->optional()->dateTimeThisMonth(),
            'expires_at'      => fake()->optional()->dateTimeBetween('now', '+1 year'),
        ];
    }
}
