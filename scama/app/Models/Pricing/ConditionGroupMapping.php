<?php

namespace App\Models\Pricing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class ConditionGroupMapping extends Model
{
    use HasFactory;

    protected $table = 'condition_group_mappings';

    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'entity_type',
        'entity_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(ConditionGroup::class);
    }
}
