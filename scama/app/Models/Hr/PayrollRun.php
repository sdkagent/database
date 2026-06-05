<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Accounting\AccountPeriod;
use App\Models\Accounting\FiscalYear;
use App\Models\Auth\User;

#[UseFactory]
class PayrollRun extends Model
{
    use HasFactory;

    protected $table = 'payroll_runs';

    protected $fillable = [
        'fiscal_year_id',
        'account_period_id',
        'run_number',
        'period_start',
        'period_end',
        'payment_date',
        'status',
        'total_gross',
        'total_deductions',
        'total_net',
        'notes',
        'processed_by',
    ];

    protected function casts(): array
    {
        return [
            'period_start'     => 'date',
            'period_end'       => 'date',
            'payment_date'     => 'date',
            'status'           => 'string',
            'total_gross'      => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'total_net'        => 'decimal:2',
            'created_at'       => 'datetime',
            'updated_at'       => 'datetime',
        ];
    }

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id');
    }

    public function accountPeriod(): BelongsTo
    {
        return $this->belongsTo(AccountPeriod::class, 'account_period_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function payrollItems(): HasMany
    {
        return $this->hasMany(PayrollItem::class, 'payroll_run_id');
    }
}
