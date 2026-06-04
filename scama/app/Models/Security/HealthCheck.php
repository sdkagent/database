<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class HealthCheck extends Model
{
    use HasFactory;

    protected $table = 'health_checks';

    public $timestamps = false;

    protected $fillable = [
        'check_type', 'status', 'response_time_ms', 'message',
    ];

    protected function casts(): array
    {
        return [
            'response_time_ms' => 'integer',
            'checked_at'       => 'datetime',
        ];
    }
}
