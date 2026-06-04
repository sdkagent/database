<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class ProductHardwareRequirement extends Model
{
    use HasFactory;

    protected $table = 'product_hardware_requirements';

    public $timestamps = false;

    protected $fillable = [
        'product_id', 'os_name', 'os_version_min', 'cpu_cores_min',
        'memory_mb_min', 'disk_mb_min', 'additional_notes',
    ];

    protected function casts(): array
    {
        return [
            'cpu_cores_min' => 'integer',
            'memory_mb_min' => 'integer',
            'disk_mb_min'   => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
