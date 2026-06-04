<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class TaxReportDatum extends Model
{
    use HasFactory;

    protected $table = 'tax_report_data';

    public $timestamps = false;

    protected $fillable = [
        'tax_jurisdiction_id', 'period_start', 'period_end',
        'taxable_amount', 'tax_collected', 'returns_filed', 'filed_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'period_start'    => 'date',
            'period_end'      => 'date',
            'taxable_amount'  => 'decimal:2',
            'tax_collected'   => 'decimal:2',
            'returns_filed'   => 'boolean',
            'filed_at'        => 'datetime',
            'created_at'      => 'datetime',
        ];
    }

    public function taxJurisdiction(): BelongsTo
    {
        return $this->belongsTo(TaxJurisdiction::class);
    }
}
