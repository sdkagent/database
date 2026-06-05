<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Product\Product;

#[UseFactory]
class BomItem extends Model
{
    use HasFactory;

    protected $table = 'bom_items';

    protected $fillable = [
        'bom_id',
        'component_id',
        'quantity',
        'unit',
        'scrap_rate',
        'line_order',
    ];

    protected function casts(): array
    {
        return [
            'quantity'   => 'decimal:2',
            'scrap_rate' => 'decimal:2',
            'line_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function bom(): BelongsTo
    {
        return $this->belongsTo(BillOfMaterial::class);
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
