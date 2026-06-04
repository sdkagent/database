<?php

namespace App\Models\Form;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class FormSubmission extends Model
{
    use HasFactory;

    protected $table = 'form_submissions';

    protected $fillable = [
        'form_key', 'data', 'user_id', 'ip_address', 'user_agent',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'data'       => 'json',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
