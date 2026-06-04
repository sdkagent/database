<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class JournalEntryLine extends Model
{
    use HasFactory;

    protected $table = 'journal_entry_lines';

    public $timestamps = false;

    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'cost_center_id',
        'profit_center_id',
        'debit',
        'credit',
        'description',
        'line_order',
    ];

    protected function casts(): array
    {
        return [
            'debit'      => 'decimal:2',
            'credit'     => 'decimal:2',
            'line_order' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function profitCenter(): BelongsTo
    {
        return $this->belongsTo(ProfitCenter::class, 'profit_center_id');
    }
}
