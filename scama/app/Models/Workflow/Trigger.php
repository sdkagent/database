<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class Trigger extends Model
{
    use HasFactory;

    protected $table = 'triggers';

    protected $fillable = [
        'name',
        'slug',
        'event_type',
        'description',
        'config',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'config'     => 'json',
            'status'     => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function triggerWorkflowMappings(): HasMany
    {
        return $this->hasMany(TriggerWorkflowMapping::class, 'trigger_id');
    }
}
