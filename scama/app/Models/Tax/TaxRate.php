<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class TaxRate extends Model
{
    use HasFactory;

    protected $table = 'tax_rates';

    protected $fillable = [
        'name', 'rate', 'type', 'country', 'region', 'is_default', 'active',
    ];

    protected function casts(): array
    {
        return [
            'rate'       => 'decimal:2',
            'is_default' => 'boolean',
            'active'     => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
