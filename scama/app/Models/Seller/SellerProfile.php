<?php

namespace App\Models\Seller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Accounting\BalanceLedger;
use App\Models\Product\Product;
use App\Models\Auth\User;

#[UseFactory]
class SellerProfile extends Model
{
    use HasFactory;

    protected $table = 'seller_profiles';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $fillable = [
        'user_id', 'store_name', 'store_description', 'store_logo_url',
        'store_cover_url', 'status', 'current_balance', 'default_commission',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'current_balance'    => 'decimal:4',
            'default_commission' => 'decimal:2',
            'verified_at'        => 'datetime',
            'created_at'         => 'datetime',
            'updated_at'         => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payoutAccounts(): HasMany
    {
        return $this->hasMany(PayoutAccount::class, 'seller_id');
    }

    public function payoutTransactions(): HasMany
    {
        return $this->hasMany(PayoutTransaction::class, 'seller_id');
    }

    public function balanceLedgers(): HasMany
    {
        return $this->hasMany(BalanceLedger::class, 'seller_id');
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(SellerVerification::class, 'seller_id');
    }

    public function stats(): HasMany
    {
        return $this->hasMany(SellerStat::class, 'seller_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id');
    }
}
