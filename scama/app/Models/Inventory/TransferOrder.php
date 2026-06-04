<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class TransferOrder extends Model
{
    use HasFactory;

    protected $table = 'transfer_orders';

    protected $fillable = [
        'from_warehouse_id',
        'to_warehouse_id',
        'transfer_number',
        'status',
        'requested_by',
        'approved_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status'     => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function transferOrderItems(): HasMany
    {
        return $this->hasMany(TransferOrderItem::class, 'transfer_order_id');
    }
}
