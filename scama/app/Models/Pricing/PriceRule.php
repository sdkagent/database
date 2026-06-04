<?php

namespace App\Models\Pricing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class PriceRule extends Model
{
    use HasFactory;

    protected $table = 'price_rules';

    protected $fillable = [
        'name', 'slug', 'description', 'priority', 'conditions', 'adjustments',
        'applies_to', 'stackable', 'status', 'starts_at', 'expires_at', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'priority'   => 'integer',
            'conditions' => 'array',
            'adjustments' => 'array',
            'stackable'  => 'boolean',
            'starts_at'  => 'datetime',
            'expires_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
