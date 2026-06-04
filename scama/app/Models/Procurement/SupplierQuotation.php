<?php

namespace App\Models\Procurement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Billing\Currency;

#[UseFactory]
class SupplierQuotation extends Model
{
    use HasFactory;

    protected $table = 'supplier_quotations';

    protected $fillable = [
        'rfq_id',
        'supplier_id',
        'quotation_number',
        'quotation_date',
        'valid_until',
        'subtotal',
        'tax',
        'total',
        'currency_id',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quotation_date' => 'date',
            'valid_until'    => 'date',
            'subtotal'       => 'decimal:2',
            'tax'            => 'decimal:2',
            'total'          => 'decimal:2',
            'status'         => 'string',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
        ];
    }

    public function rfq(): BelongsTo
    {
        return $this->belongsTo(Rfq::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class, 'quotation_id');
    }
}
