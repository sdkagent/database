<?php

namespace Database\Factories;

use App\Models\Email\EmailSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailSettingFactory extends Factory
{
    protected $model = EmailSetting::class;

    public function definition(): array
    {
        return [
            'smtp_host'     => fake()->randomElement(['smtp.gmail.com', 'smtp.sendgrid.net', 'smtp.mailgun.org']),
            'smtp_port'     => fake()->randomElement([587, 465, 25]),
            'smtp_username' => fake()->email(),
            'smtp_password' => fake()->password(),
            'from_email'    => fake()->companyEmail(),
            'from_name'     => fake()->company(),
            'encryption'    => fake()->randomElement(['ssl', 'tls', 'none']),
            'is_default'    => false,
        ];
    }

    public function encryption_none(): static
    {
        return $this->state(['encryption' => 'none']);
    }

    public function encryption_ssl(): static
    {
        return $this->state(['encryption' => 'ssl']);
    }

    public function encryption_tls(): static
    {
        return $this->state(['encryption' => 'tls']);
    }

    public function default(): static
    {
        return $this->state(fn(array $attrs) => ['is_default' => true]);
    }
}
