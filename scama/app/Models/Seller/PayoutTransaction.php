<?php

namespace App\Models\Seller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class PayoutTransaction extends Model
{
    use HasFactory;

    protected $table = 'payout_transactions';

    public $timestamps = false;

    protected $fillable = [
        'seller_id', 'payout_account_id', 'amount', 'fee', 'net_amount',
        'currency', 'period_start', 'period_end', 'status', 'reference',
        'notes', 'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'decimal:2',
            'fee'          => 'decimal:2',
            'net_amount'   => 'decimal:2',
            'period_start' => 'date',
            'period_end'   => 'date',
            'processed_at' => 'datetime',
            'created_at'   => 'datetime',
        ];
    }

    public function sellerProfile(): BelongsTo
    {
        return $this->belongsTo(SellerProfile::class, 'seller_id');
    }

    public function payoutAccount(): BelongsTo
    {
        return $this->belongsTo(PayoutAccount::class);
    }
}
