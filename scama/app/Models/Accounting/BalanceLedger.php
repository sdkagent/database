<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Seller\SellerProfile;

#[UseFactory]
class BalanceLedger extends Model
{
    use HasFactory;

    protected $table = 'balance_ledger';

    public $timestamps = false;

    protected $fillable = [
        'seller_id', 'type', 'amount', 'balance_before', 'balance_after',
        'reference_type', 'reference_id', 'description',
    ];

    protected function casts(): array
    {
        return [
            'amount'         => 'decimal:4',
            'balance_before' => 'decimal:4',
            'balance_after'  => 'decimal:4',
            'created_at'     => 'datetime',
        ];
    }

    public function sellerProfile(): BelongsTo
    {
        return $this->belongsTo(SellerProfile::class, 'seller_id');
    }
}
