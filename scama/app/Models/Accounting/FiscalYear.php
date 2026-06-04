<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Hr\PayrollRun;

#[UseFactory]
class FiscalYear extends Model
{
    use HasFactory;

    protected $table = 'fiscal_years';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_closed',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
            'is_closed'  => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function accountPeriods(): HasMany
    {
        return $this->hasMany(AccountPeriod::class, 'fiscal_year_id');
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class, 'fiscal_year_id');
    }

    public function accountBalances(): HasMany
    {
        return $this->hasMany(AccountBalance::class, 'fiscal_year_id');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class, 'fiscal_year_id');
    }

    public function payrollRuns(): HasMany
    {
        return $this->hasMany(PayrollRun::class, 'fiscal_year_id');
    }
}
