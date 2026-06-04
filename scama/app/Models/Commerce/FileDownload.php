<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class FileDownload extends Model
{
    use HasFactory;

    protected $table = 'file_downloads';

    public $timestamps = false;

    protected $fillable = [
        'order_item_id', 'user_id', 'ip_address', 'download_count',
        'last_downloaded', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'download_count'  => 'integer',
            'last_downloaded' => 'datetime',
            'expires_at'      => 'datetime',
            'created_at'      => 'datetime',
        ];
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
