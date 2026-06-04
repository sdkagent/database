<?php

namespace Database\Factories;

use App\Models\Cms\CmsSidebar;
use Illuminate\Database\Eloquent\Factories\Factory;

class CmsSidebarFactory extends Factory
{
    protected $model = CmsSidebar::class;

    public function definition(): array
    {
        return [
            'content' => '<aside>' . fake()->paragraph() . '</aside>',
        ];
    }
}
