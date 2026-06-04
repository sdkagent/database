<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class PayrollComponent extends Model
{
    use HasFactory;

    protected $table = 'payroll_components';

    protected $fillable = [
        'name',
        'code',
        'type',
        'calculation',
        'value',
        'is_taxable',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type'        => 'string',
            'calculation' => 'string',
            'value'       => 'decimal:2',
            'is_taxable'  => 'boolean',
            'is_active'   => 'boolean',
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }

    public function payrollItemDetails(): HasMany
    {
        return $this->hasMany(PayrollItemDetail::class, 'payroll_component_id');
    }
}
