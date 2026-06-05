<?php

namespace App\Models\Form;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Production\BillOfMaterial;
use App\Models\Production\ProductionOrder;

#[UseFactory]
class Routing extends Model
{
    use HasFactory;

    protected $table = 'routings';

    protected $fillable = [
        'bom_id',
        'name',
        'total_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'total_time' => 'decimal:2',
            'is_active'  => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function bom(): BelongsTo
    {
        return $this->belongsTo(BillOfMaterial::class);
    }

    public function routingSteps(): HasMany
    {
        return $this->hasMany(RoutingStep::class, 'routing_id');
    }

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class, 'routing_id');
    }
}
