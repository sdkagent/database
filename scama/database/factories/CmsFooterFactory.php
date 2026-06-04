<?php

namespace Database\Factories;

use App\Models\Cms\CmsFooter;
use Illuminate\Database\Eloquent\Factories\Factory;

class CmsFooterFactory extends Factory
{
    protected $model = CmsFooter::class;

    public function definition(): array
    {
        return [
            'content' => '<p>' . fake()->paragraph(3, true) . '</p>',
        ];
    }
}
