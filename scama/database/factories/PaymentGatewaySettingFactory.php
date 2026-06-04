<?php

namespace Database\Factories;

use App\Models\Billing\PaymentGatewaySetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentGatewaySettingFactory extends Factory
{
    protected $model = PaymentGatewaySetting::class;

    public function definition(): array
    {
        return [
            'gateway_id' => \App\Models\PaymentGateway::factory(),
            'key'        => fake()->randomElement(['api_key', 'secret_key', 'webhook_secret', 'mode']),
            'value'      => fake()->sha256(),
        ];
    }
}
