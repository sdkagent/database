<?php

namespace App\Models\Procurement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class Rfq extends Model
{
    use HasFactory;

    protected $table = 'rfqs';

    protected $fillable = [
        'rfq_number',
        'title',
        'description',
        'issue_date',
        'closing_date',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'issue_date'   => 'date',
            'closing_date' => 'date',
            'status'       => 'string',
            'created_at'   => 'datetime',
            'updated_at'   => 'datetime',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rfqItems(): HasMany
    {
        return $this->hasMany(RfqItem::class, 'rfq_id');
    }

    public function supplierQuotations(): HasMany
    {
        return $this->hasMany(SupplierQuotation::class, 'rfq_id');
    }
}
