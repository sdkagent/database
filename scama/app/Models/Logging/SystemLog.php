<?php

namespace App\Models\Logging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class SystemLog extends Model
{
    use HasFactory;

    protected $table = 'system_logs';

    protected $fillable = [
        'level', 'message', 'context',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'context'    => 'json',
            'created_at' => 'datetime',
        ];
    }
}
