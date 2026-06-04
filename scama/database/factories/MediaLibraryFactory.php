<?php

namespace Database\Factories;

use App\Models\Content\MediaLibrary;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaLibraryFactory extends Factory
{
    protected $model = MediaLibrary::class;

    public function definition(): array
    {
        return [
            'filename'  => fake()->unique()->word() . '.' . fake()->fileExtension(),
            'filepath'  => 'uploads/' . fake()->uuid() . '.' . fake()->fileExtension(),
            'mime_type' => fake()->mimeType(),
            'file_size' => fake()->numberBetween(1000, 10000000),
            'width'     => fake()->optional()->numberBetween(100, 4000),
            'height'    => fake()->optional()->numberBetween(100, 4000),
            'alt_text'  => fake()->optional()->sentence(),
            'caption'   => fake()->optional()->sentence(),
            'uploaded_by' => \App\Models\User::factory(),
        ];
    }
}
