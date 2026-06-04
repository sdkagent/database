<?php

namespace Database\Factories;

use App\Models\I18n\LanguagePack;
use App\Models\I18n\TranslationFile;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFileFactory extends Factory
{
    protected $model = TranslationFile::class;

    public function definition(): array
    {
        return [
            'language_pack_id' => LanguagePack::factory(),
            'namespace'        => fake()->randomElement(['frontend', 'backend']),
            'file_path'        => fake()->filePath() . '.json',
            'file_format'      => fake()->randomElement(['json', 'po', 'xlf', 'csv']),
            'version'          => fake()->numberBetween(1, 10),
            'uploaded_by'      => User::factory(),
        ];
    }

    public function namespace_frontend(): static
    {
        return $this->state(['namespace' => 'frontend']);
    }

    public function namespace_backend(): static
    {
        return $this->state(['namespace' => 'backend']);
    }

    public function file_format_json(): static
    {
        return $this->state(['file_format' => 'json']);
    }

    public function file_format_po(): static
    {
        return $this->state(['file_format' => 'po']);
    }

    public function file_format_xlf(): static
    {
        return $this->state(['file_format' => 'xlf']);
    }

    public function file_format_csv(): static
    {
        return $this->state(['file_format' => 'csv']);
    }
}
