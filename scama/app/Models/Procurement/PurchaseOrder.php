<?php

namespace App\Models\Procurement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Billing\Currency;
use App\Models\Auth\User;

#[UseFactory]
class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'supplier_id',
        'order_number',
        'status',
        'order_date',
        'expected_date',
        'subtotal',
        'tax',
        'total',
        'currency_id',
        'notes',
        'requested_by',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'status'        => 'string',
            'order_date'    => 'date',
            'expected_date' => 'date',
            'subtotal'      => 'decimal:2',
            'tax'           => 'decimal:2',
            'total'         => 'decimal:2',
            'created_at'    => 'datetime',
            'updated_at'    => 'datetime',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }

    public function purchaseReceipts(): HasMany
    {
        return $this->hasMany(PurchaseReceipt::class, 'purchase_order_id');
    }

    public function purchaseInvoices(): HasMany
    {
        return $this->hasMany(PurchaseInvoice::class, 'purchase_order_id');
    }
}
