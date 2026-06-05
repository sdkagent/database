<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Hr\PayrollRun;

#[UseFactory]
class AccountPeriod extends Model
{
    use HasFactory;

    protected $table = 'account_periods';

    public $timestamps = false;

    protected $fillable = [
        'fiscal_year_id',
        'type',
        'start_date',
        'end_date',
        'is_closed',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'type'       => 'string',
            'start_date' => 'date',
            'end_date'   => 'date',
            'is_closed'  => 'boolean',
            'closed_at'  => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id');
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class, 'account_period_id');
    }

    public function accountBalances(): HasMany
    {
        return $this->hasMany(AccountBalance::class, 'account_period_id');
    }

    public function budgetLines(): HasMany
    {
        return $this->hasMany(BudgetLine::class, 'period_id');
    }

    public function payrollRuns(): HasMany
    {
        return $this->hasMany(PayrollRun::class, 'account_period_id');
    }
}
