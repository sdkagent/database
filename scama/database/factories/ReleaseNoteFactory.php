<?php

namespace Database\Factories;

use App\Models\System\ReleaseNote;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReleaseNoteFactory extends Factory
{
    protected $model = ReleaseNote::class;

    public function definition(): array
    {
        return [
            'version' => fake()->semver(),
            'notes'   => fake()->paragraphs(2, true),
        ];
    }
}
