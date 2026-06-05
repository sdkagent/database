<?php

namespace App\Models\Procurement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Billing\Currency;

#[UseFactory]
class SupplierPricelist extends Model
{
    use HasFactory;

    protected $table = 'supplier_pricelists';

    protected $fillable = [
        'supplier_product_id',
        'unit_price',
        'currency_id',
        'min_quantity',
        'effective_from',
        'effective_until',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'unit_price'      => 'decimal:2',
            'min_quantity'    => 'integer',
            'effective_from'  => 'date',
            'effective_until' => 'date',
            'is_active'       => 'boolean',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
        ];
    }

    public function supplierProduct(): BelongsTo
    {
        return $this->belongsTo(SupplierProduct::class, 'supplier_product_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
