<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class CmsSocialLink extends Model
{
    use HasFactory;

    protected $table = 'cms_social_links';

    protected $fillable = [
        'platform', 'url', 'position',
    ];

    protected function casts(): array
    {
        return [
            'position'   => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
