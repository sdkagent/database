<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class AccountBalance extends Model
{
    use HasFactory;

    protected $table = 'account_balances';

    protected $fillable = [
        'account_id',
        'fiscal_year_id',
        'account_period_id',
        'period_type',
        'opening_balance',
        'period_debit',
        'period_credit',
        'closing_balance',
    ];

    protected function casts(): array
    {
        return [
            'period_type'     => 'string',
            'opening_balance' => 'decimal:2',
            'period_debit'    => 'decimal:2',
            'period_credit'   => 'decimal:2',
            'closing_balance' => 'decimal:2',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id');
    }

    public function accountPeriod(): BelongsTo
    {
        return $this->belongsTo(AccountPeriod::class, 'account_period_id');
    }
}
