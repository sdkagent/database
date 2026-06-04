<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class StructuredData extends Model
{
    use HasFactory;

    protected $table = 'structured_data';

    protected $fillable = [
        'content_type', 'content_id', 'schema_type', 'json_ld',
    ];

    protected function casts(): array
    {
        return [
            'json_ld'    => 'json',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
