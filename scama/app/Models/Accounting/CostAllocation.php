<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class CostAllocation extends Model
{
    use HasFactory;

    protected $table = 'cost_allocations';

    protected $fillable = [
        'source_cost_center_id',
        'target_cost_center_id',
        'account_id',
        'allocation_method',
        'allocation_value',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'allocation_method' => 'string',
            'allocation_value'  => 'decimal:2',
            'is_active'         => 'boolean',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
        ];
    }

    public function sourceCostCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class, 'source_cost_center_id');
    }

    public function targetCostCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class, 'target_cost_center_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class);
    }
}
