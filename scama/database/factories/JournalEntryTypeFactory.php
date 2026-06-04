<?php

namespace Database\Factories;

use App\Models\Accounting\JournalEntryType;
use Illuminate\Database\Eloquent\Factories\Factory;

class JournalEntryTypeFactory extends Factory
{
    protected $model = JournalEntryType::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => fake()->unique()->bothify('??-####'),
            'description' => fake()->sentence(),
        ];
    }

}
