<?php

namespace Database\Factories;

use App\Models\Content\PostTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostTagFactory extends Factory
{
    protected $model = PostTag::class;

    public function definition(): array
    {
        return [
            'post_id' => \App\Models\Post::factory(),
            'tag_id'  => \App\Models\CmsTag::factory(),
        ];
    }
}
