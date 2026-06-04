<?php

namespace App\Models\Seller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class PayoutAccount extends Model
{
    use HasFactory;

    protected $table = 'payout_accounts';

    protected $fillable = [
        'seller_id', 'method', 'account_label', 'account_details',
        'is_default', 'status',
    ];

    protected function casts(): array
    {
        return [
            'account_details' => 'array',
            'is_default'      => 'boolean',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
        ];
    }

    public function sellerProfile(): BelongsTo
    {
        return $this->belongsTo(SellerProfile::class, 'seller_id');
    }

    public function payoutTransactions(): HasMany
    {
        return $this->hasMany(PayoutTransaction::class);
    }
}
