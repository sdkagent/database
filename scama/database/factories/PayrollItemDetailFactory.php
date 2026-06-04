<?php

namespace Database\Factories;

use App\Models\Hr\PayrollItemDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollItemDetailFactory extends Factory
{
    protected $model = PayrollItemDetail::class;

    public function definition(): array
    {
        return [
            'payroll_item_id' => PayrollItem::factory(),
            'payroll_component_id' => PayrollComponent::factory(),
            'amount' => fake()->randomFloat(2, 0, 1000),
        ];
    }

}
