<?php

namespace App\Models\Llm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class LlmProviderActivity extends Model
{
    use HasFactory;

    protected $table = 'llm_provider_activity';

    protected $fillable = [
        'provider_id', 'user_id', 'activity_type', 'details',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'details'    => 'json',
            'created_at' => 'datetime',
        ];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(LlmProvider::class, 'provider_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
