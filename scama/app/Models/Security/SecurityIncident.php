<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class SecurityIncident extends Model
{
    use HasFactory;

    protected $table = 'security_incidents';

    protected $fillable = [
        'incident_type', 'description', 'status',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'reported_at' => 'datetime',
        ];
    }
}
