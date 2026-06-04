<?php

namespace Database\Factories;

use App\Models\Cms\CmsPageTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class CmsPageTagFactory extends Factory
{
    protected $model = CmsPageTag::class;

    public function definition(): array
    {
        return [
            'page_id' => \App\Models\CmsPage::factory(),
            'tag_id'  => \App\Models\CmsTag::factory(),
        ];
    }
}
