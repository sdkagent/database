<?php

namespace Database\Factories;

use App\Models\Cms\CmsMediaGallery;
use Illuminate\Database\Eloquent\Factories\Factory;

class CmsMediaGalleryFactory extends Factory
{
    protected $model = CmsMediaGallery::class;

    public function definition(): array
    {
        return [
            'file_name' => fake()->unique()->word() . '.' . fake()->fileExtension(),
            'file_path' => 'media/' . fake()->uuid() . '.' . fake()->fileExtension(),
            'file_type' => fake()->mimeType(),
            'file_size' => fake()->numberBetween(1000, 5000000),
        ];
    }
}
