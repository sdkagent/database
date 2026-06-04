<?php

namespace Database\Factories;

use App\Models\Cms\CmsHeader;
use Illuminate\Database\Eloquent\Factories\Factory;

class CmsHeaderFactory extends Factory
{
    protected $model = CmsHeader::class;

    public function definition(): array
    {
        return [
            'content' => '<header>' . fake()->sentence() . '</header>',
        ];
    }
}
