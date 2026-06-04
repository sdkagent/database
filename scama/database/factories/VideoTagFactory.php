<?php

namespace Database\Factories;

use App\Models\Cms\VideoTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoTagFactory extends Factory
{
    protected $model = VideoTag::class;

    public function definition(): array
    {
        return [
            'video_id' => \App\Models\Video::factory(),
            'tag_id'   => \App\Models\CmsTag::factory(),
        ];
    }
}
