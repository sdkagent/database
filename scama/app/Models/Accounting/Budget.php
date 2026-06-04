<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class Budget extends Model
{
    use HasFactory;

    protected $table = 'budgets';

    protected $fillable = [
        'fiscal_year_id',
        'profit_center_id',
        'cost_center_id',
        'name',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status'     => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id');
    }

    public function profitCenter(): BelongsTo
    {
        return $this->belongsTo(ProfitCenter::class, 'profit_center_id');
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function budgetLines(): HasMany
    {
        return $this->hasMany(BudgetLine::class, 'budget_id');
    }

    public function budgetVersions(): HasMany
    {
        return $this->hasMany(BudgetVersion::class, 'budget_id');
    }
}
