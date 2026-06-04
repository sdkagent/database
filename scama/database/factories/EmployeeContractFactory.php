<?php

namespace Database\Factories;

use App\Models\Hr\EmployeeContract;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeContractFactory extends Factory
{
    protected $model = EmployeeContract::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'contract_type' => fake()->randomElement(['permanent', 'fixed_term', 'probation', 'consulting']),
            'salary' => fake()->randomFloat(2, 1000, 100000),
            'currency_id' => Currency::factory(),
            'benefits' => json_encode([]),
            'documents' => json_encode([]),
            'status' => fake()->randomElement(['active', 'expired', 'terminated']),
        ];
    }

    public function contract_type_permanent(): static
    {
        return $this->state(['contract_type' => 'permanent']);
    }

    public function contract_type_fixed_term(): static
    {
        return $this->state(['contract_type' => 'fixed_term']);
    }

    public function contract_type_probation(): static
    {
        return $this->state(['contract_type' => 'probation']);
    }

    public function contract_type_consulting(): static
    {
        return $this->state(['contract_type' => 'consulting']);
    }

    public function status_active(): static
    {
        return $this->state(['status' => 'active']);
    }

    public function status_expired(): static
    {
        return $this->state(['status' => 'expired']);
    }

    public function status_terminated(): static
    {
        return $this->state(['status' => 'terminated']);
    }

}
