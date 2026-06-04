<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class FeatureFlag extends Model
{
    use HasFactory;

    protected $table = 'feature_flags';

    protected $fillable = [
        'name', 'key', 'description', 'enabled', 'conditions',
    ];

    protected function casts(): array
    {
        return [
            'enabled'    => 'boolean',
            'conditions' => 'json',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
