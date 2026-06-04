<?php

namespace Database\Factories;

use App\Models\Sms\SmsTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmsTemplateFactory extends Factory
{
    protected $model = SmsTemplate::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'category' => fake()->randomElement(['promotional', 'transactional', 'alert', 'otp']),
            'body' => fake()->sentence(),
            'variables' => json_encode(['name', 'amount']),
        ];
    }

}
