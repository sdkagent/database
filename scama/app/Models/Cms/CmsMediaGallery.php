<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class CmsMediaGallery extends Model
{
    use HasFactory;

    protected $table = 'cms_media_galleries';

    protected $fillable = [
        'file_name', 'file_path', 'file_type', 'file_size',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'file_size'  => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
