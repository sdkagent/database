<?php

namespace App\Models\Pricing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class ConditionRule extends Model
{
    use HasFactory;

    protected $table = 'condition_rules';

    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'field',
        'operator',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'operator'   => 'string',
            'value'      => 'json',
            'created_at' => 'datetime',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(ConditionGroup::class);
    }
}
