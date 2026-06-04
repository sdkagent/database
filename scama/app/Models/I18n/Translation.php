<?php

namespace App\Models\I18n;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class Translation extends Model
{
    use HasFactory;

    protected $table = 'translations';

    protected $fillable = [
        'language_pack_id', 'namespace', 'group', 'key', 'value',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function languagePack(): BelongsTo
    {
        return $this->belongsTo(LanguagePack::class);
    }
}
