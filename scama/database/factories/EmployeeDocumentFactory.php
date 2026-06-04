<?php

namespace Database\Factories;

use App\Models\Hr\EmployeeDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeDocumentFactory extends Factory
{
    protected $model = EmployeeDocument::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'document_type' => fake()->randomElement(['id', 'passport', 'visa', 'certificate', 'contract', 'other']),
            'file_name' => fake()->unique()->word() . '.' . fake()->fileExtension(),
            'file_path' => fake()->url(),
            'is_verified' => fake()->boolean(),
            'notes' => fake()->sentence(),
        ];
    }

    public function document_type_id(): static
    {
        return $this->state(['document_type' => 'id']);
    }

    public function document_type_passport(): static
    {
        return $this->state(['document_type' => 'passport']);
    }

    public function document_type_visa(): static
    {
        return $this->state(['document_type' => 'visa']);
    }

    public function document_type_certificate(): static
    {
        return $this->state(['document_type' => 'certificate']);
    }

    public function document_type_contract(): static
    {
        return $this->state(['document_type' => 'contract']);
    }

    public function document_type_other(): static
    {
        return $this->state(['document_type' => 'other']);
    }

}
