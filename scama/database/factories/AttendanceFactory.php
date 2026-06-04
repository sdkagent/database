<?php

namespace Database\Factories;

use App\Models\Hr\Attendance;
use App\Models\Hr\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'date' => fake()->date(),
            'clock_in' => fake()->time(),
            'clock_out' => fake()->time(),
            'total_hours' => fake()->randomFloat(2, 0, 1000),
            'status' => fake()->randomElement(['present', 'absent', 'late', 'half_day', 'holiday']),
            'notes' => fake()->sentence(),
        ];
    }

    public function status_present(): static
    {
        return $this->state(['status' => 'present']);
    }

    public function status_absent(): static
    {
        return $this->state(['status' => 'absent']);
    }

    public function status_late(): static
    {
        return $this->state(['status' => 'late']);
    }

    public function status_half_day(): static
    {
        return $this->state(['status' => 'half_day']);
    }

    public function status_holiday(): static
    {
        return $this->state(['status' => 'holiday']);
    }

}
