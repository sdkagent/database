<?php

namespace Database\Factories;

use App\Models\Security\ComplianceReport;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplianceReportFactory extends Factory
{
    protected $model = ComplianceReport::class;

    public function definition(): array
    {
        return [
            'report_type' => fake()->randomElement(['GDPR', 'HIPAA', 'PCI-DSS']),
            'status'      => fake()->randomElement(['pending', 'in_progress', 'completed', 'failed']),
            'findings'    => ['compliant' => true, 'issues' => fake()->numberBetween(0, 10)],
        ];
    }

    public function report_type_GDPR(): static
    {
        return $this->state(['report_type' => 'GDPR']);
    }

    public function report_type_HIPAA(): static
    {
        return $this->state(['report_type' => 'HIPAA']);
    }

    public function report_type_PCI_DSS(): static
    {
        return $this->state(['report_type' => 'PCI-DSS']);
    }

    public function status_completed(): static
    {
        return $this->state(['status' => 'completed']);
    }

    public function status_failed(): static
    {
        return $this->state(['status' => 'failed']);
    }

    public function status_in_progress(): static
    {
        return $this->state(['status' => 'in_progress']);
    }

    public function status_pending(): static
    {
        return $this->state(['status' => 'pending']);
    }
}
