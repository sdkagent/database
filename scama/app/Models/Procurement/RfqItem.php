<?php

namespace App\Models\Procurement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Product\Product;

#[UseFactory]
class RfqItem extends Model
{
    use HasFactory;

    protected $table = 'rfq_items';

    public $timestamps = false;

    protected $fillable = [
        'rfq_id',
        'product_id',
        'quantity',
        'notes',
        'line_order',
    ];

    protected function casts(): array
    {
        return [
            'quantity'   => 'decimal:2',
            'line_order' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function rfq(): BelongsTo
    {
        return $this->belongsTo(Rfq::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class, 'rfq_item_id');
    }
}
