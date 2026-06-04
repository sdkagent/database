<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Product\Product;
use App\Models\Form\Routing;

#[UseFactory]
class BillOfMaterial extends Model
{
    use HasFactory;

    protected $table = 'bill_of_materials';

    protected $fillable = [
        'product_id',
        'name',
        'version',
        'quantity',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'quantity'   => 'decimal:2',
            'is_active'  => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function bomItems(): HasMany
    {
        return $this->hasMany(BomItem::class, 'bom_id');
    }

    public function routings(): HasMany
    {
        return $this->hasMany(Routing::class, 'bom_id');
    }

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class, 'bom_id');
    }
}
