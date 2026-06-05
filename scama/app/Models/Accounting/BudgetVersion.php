<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class BudgetVersion extends Model
{
    use HasFactory;

    protected $table = 'budget_versions';

    public $timestamps = false;

    protected $fillable = [
        'budget_id',
        'version',
        'notes',
        'snapshot',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'version'    => 'integer',
            'snapshot'   => 'json',
            'created_at' => 'datetime',
        ];
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
