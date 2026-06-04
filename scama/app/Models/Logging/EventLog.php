<?php

namespace App\Models\Logging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class EventLog extends Model
{
    use HasFactory;

    protected $table = 'event_log';

    public $timestamps = false;

    protected $fillable = [
        'event_type',
        'source_type',
        'source_id',
        'payload',
        'occurred_at',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'payload'      => 'json',
            'occurred_at'  => 'datetime',
            'processed_at' => 'datetime',
            'created_at'   => 'datetime',
        ];
    }
}
