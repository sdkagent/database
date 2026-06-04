<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class PayrollItem extends Model
{
    use HasFactory;

    protected $table = 'payroll_items';

    protected $fillable = [
        'payroll_run_id',
        'employee_id',
        'gross_pay',
        'total_deductions',
        'net_pay',
        'bank_account',
        'payment_method',
        'status',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'gross_pay'        => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'net_pay'          => 'decimal:2',
            'payment_method'   => 'string',
            'status'           => 'string',
            'paid_at'          => 'datetime',
            'created_at'       => 'datetime',
            'updated_at'       => 'datetime',
        ];
    }

    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class, 'payroll_run_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollItemDetails(): HasMany
    {
        return $this->hasMany(PayrollItemDetail::class, 'payroll_item_id');
    }
}
