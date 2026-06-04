<?php

namespace App\Models\Logging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class AuditTrail extends Model
{
    use HasFactory;

    protected $table = 'audit_trails';

    protected $fillable = [
        'user_id', 'action', 'entity', 'entity_id',
        'activity_type', 'description', 'details',
        'old_value', 'new_value', 'ip_address',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'details'    => 'json',
            'old_value'  => 'json',
            'new_value'  => 'json',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
