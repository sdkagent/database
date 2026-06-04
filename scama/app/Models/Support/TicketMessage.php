<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class TicketMessage extends Model
{
    use HasFactory;

    protected $table = 'ticket_messages';

    public $timestamps = false;

    protected $fillable = [
        'ticket_id', 'sender_id', 'message', 'attachments',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'created_at'  => 'datetime',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
