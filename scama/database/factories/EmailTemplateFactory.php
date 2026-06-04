<?php

namespace Database\Factories;

use App\Models\Email\EmailTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailTemplateFactory extends Factory
{
    protected $model = EmailTemplate::class;

    public function definition(): array
    {
        $name = fake()->randomElement(['welcome', 'reset_password', 'order_confirmation', 'license_expired']);
        return [
            'name'    => $name,
            'subject' => '{{subject}}',
            'body'    => '<h1>' . fake()->sentence() . '</h1><p>' . fake()->paragraph() . '</p>',
        ];
    }
}
