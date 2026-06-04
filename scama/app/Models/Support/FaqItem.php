<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class FaqItem extends Model
{
    use HasFactory;

    protected $table = 'faq_items';

    protected $fillable = [
        'question', 'answer', 'slug', 'category', 'position', 'status',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}
