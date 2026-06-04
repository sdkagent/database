<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class Referral extends Model
{
    use HasFactory;

    protected $table = 'referrals';

    public $timestamps = false;

    protected $fillable = [
        'affiliate_id', 'referred_id', 'order_id', 'commission', 'status',
    ];

    protected function casts(): array
    {
        return [
            'commission' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
