<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Billing\PaymentGateway;

#[UseFactory]
class UserPaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'user_payment_methods';

    protected $fillable = [
        'user_id',
        'gateway_id',
        'method_type',
        'gateway_token',
        'display_name',
        'last_four',
        'expiry_month',
        'expiry_year',
        'card_brand',
        'is_default',
        'billing_address_id',
    ];

    protected function casts(): array
    {
        return [
            'method_type' => 'string',
            'is_default'  => 'boolean',
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }
}
