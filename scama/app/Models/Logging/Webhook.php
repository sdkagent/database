<?php

namespace App\Models\Logging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class Webhook extends Model
{
    use HasFactory;

    protected $table = 'webhooks';

    protected $hidden = [
        'secret',
    ];

    protected $fillable = [
        'name', 'url', 'events', 'secret', 'is_active', 'last_triggered_at',
    ];

    protected function casts(): array
    {
        return [
            'events'            => 'json',
            'is_active'         => 'boolean',
            'last_triggered_at' => 'datetime',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
        ];
    }
}
