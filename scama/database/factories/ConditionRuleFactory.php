<?php

namespace Database\Factories;

use App\Models\Pricing\ConditionRule;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pricing\ConditionGroup;


class ConditionRuleFactory extends Factory
{
    protected $model = ConditionRule::class;

    public function definition(): array
    {
        return [
            'group_id' => ConditionGroup::factory(),
            'field' => fake()->randomElement(['status', 'amount', 'quantity', 'date', 'category']),
            'operator' => fake()->randomElement(['equals', 'not_equals', 'greater_than', 'less_than', 'greater_or_equal', 'less_or_equal', 'contains', 'not_contains', 'in', 'not_in', 'starts_with', 'ends_with', 'is_empty', 'is_not_empty', 'matches']),
            'value' => json_encode([]),
        ];
    }

    public function operator_equals(): static
    {
        return $this->state(['operator' => 'equals']);
    }

    public function operator_not_equals(): static
    {
        return $this->state(['operator' => 'not_equals']);
    }

    public function operator_greater_than(): static
    {
        return $this->state(['operator' => 'greater_than']);
    }

    public function operator_less_than(): static
    {
        return $this->state(['operator' => 'less_than']);
    }

    public function operator_greater_or_equal(): static
    {
        return $this->state(['operator' => 'greater_or_equal']);
    }

    public function operator_less_or_equal(): static
    {
        return $this->state(['operator' => 'less_or_equal']);
    }

    public function operator_contains(): static
    {
        return $this->state(['operator' => 'contains']);
    }

    public function operator_not_contains(): static
    {
        return $this->state(['operator' => 'not_contains']);
    }

    public function operator_in(): static
    {
        return $this->state(['operator' => 'in']);
    }

    public function operator_not_in(): static
    {
        return $this->state(['operator' => 'not_in']);
    }

    public function operator_starts_with(): static
    {
        return $this->state(['operator' => 'starts_with']);
    }

    public function operator_ends_with(): static
    {
        return $this->state(['operator' => 'ends_with']);
    }

    public function operator_is_empty(): static
    {
        return $this->state(['operator' => 'is_empty']);
    }

    public function operator_is_not_empty(): static
    {
        return $this->state(['operator' => 'is_not_empty']);
    }

    public function operator_matches(): static
    {
        return $this->state(['operator' => 'matches']);
    }

}
