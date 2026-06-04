<?php

namespace Database\Factories;

use App\Models\Billing\Currency;
use App\Models\Hr\Department;
use App\Models\Hr\Employee;
use App\Models\Hr\JobPosition;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'employee_number' => fake()->unique()->bothify('EMP-####'),
            'department_id' => Department::factory(),
            'job_position_id' => JobPosition::factory(),
            'reports_to' => Employee::factory(),
            'hire_date' => fake()->dateTimeBetween('-5 years', '-1 month')->format('Y-m-d'),
            'termination_date' => fake()->optional(0.1)->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'employment_type' => fake()->randomElement(['full_time', 'part_time', 'contract', 'intern', 'temporary']),
            'status' => fake()->randomElement(['active', 'on_leave', 'terminated', 'suspended']),
            'base_salary' => fake()->randomFloat(2, 0, 1000),
            'currency_id' => Currency::factory(),
            'emergency_contact' => json_encode([]),
        ];
    }

    public function employment_type_full_time(): static
    {
        return $this->state(['employment_type' => 'full_time']);
    }

    public function employment_type_part_time(): static
    {
        return $this->state(['employment_type' => 'part_time']);
    }

    public function employment_type_contract(): static
    {
        return $this->state(['employment_type' => 'contract']);
    }

    public function employment_type_intern(): static
    {
        return $this->state(['employment_type' => 'intern']);
    }

    public function employment_type_temporary(): static
    {
        return $this->state(['employment_type' => 'temporary']);
    }

    public function status_active(): static
    {
        return $this->state(['status' => 'active']);
    }

    public function status_on_leave(): static
    {
        return $this->state(['status' => 'on_leave']);
    }

    public function status_terminated(): static
    {
        return $this->state(['status' => 'terminated']);
    }

    public function status_suspended(): static
    {
        return $this->state(['status' => 'suspended']);
    }

}
