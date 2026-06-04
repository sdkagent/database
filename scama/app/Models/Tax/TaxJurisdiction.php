<?php

namespace App\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class TaxJurisdiction extends Model
{
    use HasFactory;

    protected $table = 'tax_jurisdictions';

    protected $fillable = [
        'name', 'country', 'state', 'city', 'postal_code', 'rate',
        'tax_type', 'is_compound', 'priority', 'status',
        'effective_from', 'effective_to',
    ];

    protected function casts(): array
    {
        return [
            'rate'           => 'decimal:4',
            'is_compound'    => 'boolean',
            'priority'       => 'integer',
            'effective_from' => 'date',
            'effective_to'   => 'date',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
        ];
    }

    public function taxReportData(): HasMany
    {
        return $this->hasMany(TaxReportDatum::class, 'tax_jurisdiction_id');
    }
}
