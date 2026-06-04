<?php

namespace App\Models\Llm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class LlmProvider extends Model
{
    use HasFactory;

    protected $table = 'llm_providers';

    protected $fillable = [
        'name', 'api_key', 'base_url', 'status',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function settings(): HasMany
    {
        return $this->hasMany(LlmProviderSetting::class, 'provider_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LlmProviderActivity::class, 'provider_id');
    }

    public function usage(): HasMany
    {
        return $this->hasMany(LlmProviderUsage::class, 'provider_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(LlmProviderLog::class, 'provider_id');
    }
}
