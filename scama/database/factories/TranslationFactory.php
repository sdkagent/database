<?php

namespace Database\Factories;

use App\Models\I18n\LanguagePack;
use App\Models\I18n\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    public function definition(): array
    {
        return [
            'language_pack_id' => LanguagePack::factory(),
            'namespace'        => fake()->randomElement(['frontend', 'backend', 'api', 'email']),
            'group'            => fake()->randomElement(['general', 'auth', 'errors', 'validation']),
            'key'              => fake()->randomElement(['welcome_message', 'checkout_title', 'error_404', 'success_message']),
            'value'            => fake()->sentence(),
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

    public function namespace_api(): static
    {
        return $this->state(['namespace' => 'api']);
    }

    public function namespace_email(): static
    {
        return $this->state(['namespace' => 'email']);
    }

    public function group_general(): static
    {
        return $this->state(['group' => 'general']);
    }

    public function group_auth(): static
    {
        return $this->state(['group' => 'auth']);
    }

    public function group_errors(): static
    {
        return $this->state(['group' => 'errors']);
    }

    public function group_validation(): static
    {
        return $this->state(['group' => 'validation']);
    }
}
