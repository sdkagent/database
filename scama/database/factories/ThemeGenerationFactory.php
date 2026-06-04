<?php

namespace Database\Factories;

use App\Models\Theme\ThemeGeneration;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeGenerationFactory extends Factory
{
    protected $model = ThemeGeneration::class;

    public function definition(): array
    {
        return [
            'theme_id'                  => \App\Models\Theme::factory(),
            'user_id'                   => \App\Models\User::factory(),
            'theme_name'                => fake()->words(2, true),
            'theme_description'         => fake()->paragraph(),
            'theme_version'             => fake()->semver(),
            'theme_variant'             => fake()->optional()->word(),
            'theme_color_scheme'        => fake()->randomElement(['light', 'dark', 'blue', 'green', 'custom']),
            'theme_layout'              => fake()->randomElement(['fixed', 'fluid', 'boxed']),
            'theme_font'                => fake()->randomElement(['Inter', 'Roboto', 'Open Sans', 'Lato']),
            'theme_customization'       => ['primary_color' => '#007bff', 'secondary_color' => '#6c757d'],
            'theme_preview_url'         => fake()->optional()->url(),
            'theme_download_url'        => fake()->optional()->url(),
            'theme_screenshot_url'      => fake()->optional()->imageUrl(),
            'theme_markdown_description' => fake()->optional()->markdown(),
            'theme_template_variables'  => ['site_name' => 'My Site', 'tagline' => 'Welcome'],
            'style'                     => fake()->randomElement(['light', 'dark', 'auto']),
            'complexity'                => fake()->randomElement(['simple', 'moderate', 'complex']),
            'source'                    => fake()->randomElement(['user_input', 'ai_generated', 'imported']),
            'generation_method'         => fake()->randomElement(['manual', 'automated', 'hybrid']),
            'priority'                  => fake()->randomElement(['low', 'medium', 'high']),
            'estimated_completion_time' => fake()->numberBetween(30, 300),
            'actual_completion_time'    => fake()->optional()->numberBetween(20, 400),
            'progress'                  => fake()->numberBetween(0, 100),
            'quality_score'             => fake()->optional()->randomFloat(2, 0, 10),
            'status'                    => fake()->randomElement(['pending', 'in_progress', 'completed', 'failed']),
            'generated_files'           => ['style.css', 'script.js', 'index.html'],
            'error_message'             => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'pending']);
    }

    public function completed(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'completed', 'progress' => 100]);
    }

    public function failed(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'failed',
            'error_message' => fake()->sentence(),
        ]);
    }

    public function theme_color_scheme_light(): static
    {
        return $this->state(['theme_color_scheme' => 'light']);
    }

    public function theme_color_scheme_dark(): static
    {
        return $this->state(['theme_color_scheme' => 'dark']);
    }

    public function theme_color_scheme_blue(): static
    {
        return $this->state(['theme_color_scheme' => 'blue']);
    }

    public function theme_color_scheme_green(): static
    {
        return $this->state(['theme_color_scheme' => 'green']);
    }

    public function theme_color_scheme_custom(): static
    {
        return $this->state(['theme_color_scheme' => 'custom']);
    }

    public function theme_layout_fixed(): static
    {
        return $this->state(['theme_layout' => 'fixed']);
    }

    public function theme_layout_fluid(): static
    {
        return $this->state(['theme_layout' => 'fluid']);
    }

    public function theme_layout_boxed(): static
    {
        return $this->state(['theme_layout' => 'boxed']);
    }

    public function theme_font_inter(): static
    {
        return $this->state(['theme_font' => 'Inter']);
    }

    public function theme_font_roboto(): static
    {
        return $this->state(['theme_font' => 'Roboto']);
    }

    public function theme_font_open_sans(): static
    {
        return $this->state(['theme_font' => 'Open Sans']);
    }

    public function theme_font_lato(): static
    {
        return $this->state(['theme_font' => 'Lato']);
    }

    public function style_light(): static
    {
        return $this->state(['style' => 'light']);
    }

    public function style_dark(): static
    {
        return $this->state(['style' => 'dark']);
    }

    public function style_auto(): static
    {
        return $this->state(['style' => 'auto']);
    }

    public function complexity_simple(): static
    {
        return $this->state(['complexity' => 'simple']);
    }

    public function complexity_moderate(): static
    {
        return $this->state(['complexity' => 'moderate']);
    }

    public function complexity_complex(): static
    {
        return $this->state(['complexity' => 'complex']);
    }

    public function source_user_input(): static
    {
        return $this->state(['source' => 'user_input']);
    }

    public function source_ai_generated(): static
    {
        return $this->state(['source' => 'ai_generated']);
    }

    public function source_imported(): static
    {
        return $this->state(['source' => 'imported']);
    }

    public function generation_method_manual(): static
    {
        return $this->state(['generation_method' => 'manual']);
    }

    public function generation_method_automated(): static
    {
        return $this->state(['generation_method' => 'automated']);
    }

    public function generation_method_hybrid(): static
    {
        return $this->state(['generation_method' => 'hybrid']);
    }

    public function priority_low(): static
    {
        return $this->state(['priority' => 'low']);
    }

    public function priority_medium(): static
    {
        return $this->state(['priority' => 'medium']);
    }

    public function priority_high(): static
    {
        return $this->state(['priority' => 'high']);
    }

    public function status_in_progress(): static
    {
        return $this->state(['status' => 'in_progress']);
    }
}
