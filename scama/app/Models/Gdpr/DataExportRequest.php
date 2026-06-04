<?php

namespace App\Models\Gdpr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class DataExportRequest extends Model
{
    use HasFactory;

    protected $table = 'data_export_requests';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'status', 'format', 'requested_at', 'completed_at',
        'file_path', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'requested_at' => 'datetime',
            'completed_at' => 'datetime',
            'expires_at'   => 'datetime',
            'created_at'   => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
