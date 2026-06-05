<?php

namespace App\Models\Pricing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class ConditionGroup extends Model
{
    use HasFactory;

    protected $table = 'condition_groups';

    protected $fillable = [
        'name',
        'operator',
    ];

    protected function casts(): array
    {
        return [
            'operator'   => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function conditionRules(): HasMany
    {
        return $this->hasMany(ConditionRule::class, 'group_id');
    }

    public function conditionGroupMappings(): HasMany
    {
        return $this->hasMany(ConditionGroupMapping::class, 'group_id');
    }
}
