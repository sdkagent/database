<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class TaxRule extends Model
{
    use HasFactory;

    protected $table = 'tax_rules';

    protected $fillable = [
        'name', 'priority', 'conditions', 'action_type', 'action_value', 'status',
    ];

    protected function casts(): array
    {
        return [
            'priority'    => 'integer',
            'conditions'  => 'array',
            'action_value' => 'array',
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }
}
