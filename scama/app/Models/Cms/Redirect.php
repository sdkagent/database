<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class Redirect extends Model
{
    use HasFactory;

    protected $table = 'redirects';

    protected $fillable = [
        'old_path', 'new_path', 'status_code', 'is_active', 'hits_count',
    ];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'hits_count' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
