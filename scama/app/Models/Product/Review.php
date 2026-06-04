<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Commerce\Order;
use App\Models\Auth\User;

#[UseFactory]
class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'reviewable_type',
        'reviewable_id',
        'user_id',
        'order_id',
        'rating',
        'title',
        'body',
        'is_approved',
        'is_verified_purchase',
        'helpful_count',
    ];

    protected function casts(): array
    {
        return [
            'is_approved'          => 'boolean',
            'is_verified_purchase' => 'boolean',
            'created_at'           => 'datetime',
            'updated_at'           => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
