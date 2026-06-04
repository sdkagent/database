<?php

namespace Database\Factories;

use App\Models\Form\FormSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormSubmissionFactory extends Factory
{
    protected $model = FormSubmission::class;

    public function definition(): array
    {
        return [
            'form_key'   => fake()->randomElement(['contact', 'newsletter', 'support', 'feedback']),
            'data'       => [
                'name'    => fake()->name(),
                'email'   => fake()->email(),
                'message' => fake()->paragraph(),
            ],
            'user_id'    => \App\Models\User::factory(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }

    public function form_key_contact(): static
    {
        return $this->state(['form_key' => 'contact']);
    }

    public function form_key_feedback(): static
    {
        return $this->state(['form_key' => 'feedback']);
    }

    public function form_key_newsletter(): static
    {
        return $this->state(['form_key' => 'newsletter']);
    }

    public function form_key_support(): static
    {
        return $this->state(['form_key' => 'support']);
    }
}
