<?php

namespace App\Models\Email;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class EmailAutomation extends Model
{
    use HasFactory;

    protected $table = 'email_automations';

    protected $fillable = [
        'name',
        'trigger_event',
        'email_template_id',
        'conditions',
        'audience_filter',
        'sender_name',
        'sender_email',
        'reply_to',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'conditions'      => 'json',
            'audience_filter' => 'json',
            'status'          => 'string',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
        ];
    }

    public function emailTemplate(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
