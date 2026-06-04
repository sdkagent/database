<?php

namespace App\Models\Reporting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class ReportDefinition extends Model
{
    use HasFactory;

    protected $table = 'report_definitions';

    protected $fillable = [
        'name', 'slug', 'description', 'category_id', 'report_type',
        'config', 'is_system', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'config'    => 'array',
            'is_system' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ReportCategory::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
