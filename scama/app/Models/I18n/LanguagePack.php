<?php

namespace App\Models\I18n;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class LanguagePack extends Model
{
    use HasFactory;

    protected $table = 'language_packs';

    protected $fillable = [
        'code', 'name', 'native_name', 'is_rtl', 'is_default', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_rtl'     => 'boolean',
            'is_default' => 'boolean',
            'is_active'  => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }

    public function translationFiles(): HasMany
    {
        return $this->hasMany(TranslationFile::class);
    }
}
