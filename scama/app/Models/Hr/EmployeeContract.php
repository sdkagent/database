<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Billing\Currency;

#[UseFactory]
class EmployeeContract extends Model
{
    use HasFactory;

    protected $table = 'employee_contracts';

    protected $fillable = [
        'employee_id',
        'contract_type',
        'start_date',
        'end_date',
        'salary',
        'currency_id',
        'benefits',
        'documents',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'contract_type' => 'string',
            'start_date'    => 'date',
            'end_date'      => 'date',
            'salary'        => 'decimal:2',
            'benefits'      => 'json',
            'documents'     => 'json',
            'status'        => 'string',
            'created_at'    => 'datetime',
            'updated_at'    => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
