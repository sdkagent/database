<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class StockCount extends Model
{
    use HasFactory;

    protected $table = 'stock_counts';

    protected $fillable = [
        'warehouse_id',
        'count_date',
        'status',
        'counted_by',
        'verified_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'count_date' => 'date',
            'status'     => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function countedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counted_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function stockCountItems(): HasMany
    {
        return $this->hasMany(StockCountItem::class, 'stock_count_id');
    }
}
