<?php

namespace App\Models\I18n;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class TranslationFile extends Model
{
    use HasFactory;

    protected $table = 'translation_files';

    protected $fillable = [
        'language_pack_id', 'namespace', 'file_path', 'file_format', 'version', 'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'version'    => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function languagePack(): BelongsTo
    {
        return $this->belongsTo(LanguagePack::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
